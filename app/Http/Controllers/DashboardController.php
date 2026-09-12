<?php

namespace App\Http\Controllers;

use App\Models\AdditionalIncome;
use App\Models\BudgetAllocation;
use App\Models\Expense;
use App\Models\RecurringExpense;
use App\Models\Salary;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $salary = Salary::currentMonth()->first();

        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();

        $hasAdditional = Schema::hasTable('additional_incomes');
        $totalAdditionalIncome = $hasAdditional ? AdditionalIncome::whereBetween('received_at', [$currentMonthStart, $currentMonthEnd])->sum('amount') : 0;
        $totalIncome = ($salary?->amount ?? 0) + $totalAdditionalIncome;

        $allocations = $salary ? $salary->budgetAllocations()->with('category')->get() : collect();
        $totalAllocated = $salary ? $salary->totalAllocated() : 0;
        $totalSpent = $salary ? $salary->totalSpent() : 0;
        $remaining = $salary ? $salary->remaining() : 0;
        $sisaAlokasi = $totalAllocated - $totalSpent;

        $totalMonthlyExpenses = Expense::where('spent_at', '>=', $currentMonthStart)->sum('amount');

        $recentExpenses = Expense::with('category')
            ->where('spent_at', '>=', $currentMonthStart)
            ->latest('spent_at')
            ->limit(10)
            ->get();

        $dueRecurring = RecurringExpense::with('category')
            ->where('is_active', true)
            ->where('next_due_date', '<=', now())
            ->get();

        $greetingName = strtok(auth()->user()->name, ' ');
        $hour = (int) now()->format('G');
        $timeGreeting = $hour < 11 ? __('messages.dashboard.greeting_morning')
            : ($hour < 15 ? __('messages.dashboard.greeting_afternoon')
            : ($hour < 19 ? __('messages.dashboard.greeting_evening')
            : __('messages.dashboard.greeting_night')));

        $totalIncomeForRatio = $totalIncome > 0 ? $totalIncome : ($salary?->amount ?? 0);
        $expenseRatio = $totalIncomeForRatio > 0 ? ($totalMonthlyExpenses / $totalIncomeForRatio) * 100 : 0;

        $safeQuotes = __('messages.motivation.safe');
        $cautionQuotes = __('messages.motivation.caution');
        $dangerQuotes = __('messages.motivation.danger');
        if (! is_array($safeQuotes)) {
            $safeQuotes = [$safeQuotes];
        }
        if (! is_array($cautionQuotes)) {
            $cautionQuotes = [$cautionQuotes];
        }
        if (! is_array($dangerQuotes)) {
            $dangerQuotes = [$dangerQuotes];
        }

        if ($expenseRatio > 50) {
            $motivation = $dangerQuotes[array_rand($dangerQuotes)];
            $motivationBg = 'bg-red-500/20';
            $greetingBg = 'bg-red-600';
            $greetingShadow = 'shadow-[0_12px_32px_-10px_rgba(220,38,38,0.6)]';
        } elseif ($expenseRatio > 25) {
            $motivation = $cautionQuotes[array_rand($cautionQuotes)];
            $motivationBg = 'bg-yellow-500/20';
            $greetingBg = 'bg-amber-500';
            $greetingShadow = 'shadow-[0_12px_32px_-10px_rgba(245,158,11,0.6)]';
        } else {
            $motivation = $safeQuotes[array_rand($safeQuotes)];
            $motivationBg = '';
            $greetingBg = 'bg-[#1BA37A]';
            $greetingShadow = 'shadow-[0_12px_32px_-10px_rgba(27,163,122,0.6)]';
        }

        return view('dashboard', compact(
            'salary',
            'totalAdditionalIncome',
            'totalIncome',
            'allocations',
            'totalAllocated',
            'totalSpent',
            'remaining',
            'sisaAlokasi',
            'totalMonthlyExpenses',
            'recentExpenses',
            'dueRecurring',
            'greetingName',
            'timeGreeting',
            'motivation',
            'motivationBg',
            'greetingBg',
            'greetingShadow',
        ));
    }

    public function resetData()
    {
        RecurringExpense::query()->delete();
        Expense::query()->delete();
        BudgetAllocation::query()->delete();
        Salary::query()->delete();
        if (Schema::hasTable('additional_incomes')) {
            AdditionalIncome::query()->delete();
        }

        return back()->with('success', __('messages.dashboard.reset_success'));
    }
}
