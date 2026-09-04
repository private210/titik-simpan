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
        $timeGreeting = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 19 ? 'Selamat sore' : 'Selamat malam'));

        $safeQuotes = [
            'Setiap rupiah yang kamu hemat hari ini adalah investasi untuk masa depanmu.',
            'Kebebasan finansial dimulai dari keputusan kecil yang konsisten.',
            'Anggaran yang jelas adalah peta menuju tujuan keuanganmu.',
            'Disiplin hari ini adalah kemapanan esok hari.',
            'Mengatur keuangan adalah bentuk cinta untuk masa depanmu.',
            'Rencanakan dengan bijak agar bulan depannya terasa lebih ringan.',
        ];

        $cautionQuotes = [
            'Hati-hati, pengeluaran sudah cukup besar. Evaluasi kebutuhan vs keinginan.',
            'Pengeluaran mulai mendekati batas. Saatnya bijak berbelanja.',
            'Pengeluaran bulan ini sudah melewati 25% gaji. Perhatikan sisa anggaranmu.',
        ];

        $dangerQuotes = [
            'Pengeluaran sudah lebih dari 50% gaji! Saatnya berhenti dan evaluasi.',
            'Waspadalah! Pengeluaranmu sudah berlebihan. Prioritaskan kebutuhan pokok.',
            'Pengeluaran melebihi batas aman. Kurangi belanja yang tidak perlu sekarang juga.',
            'Sisa gaji semakin tipis. Hentikan pengeluaran yang tidak mendesak.',
        ];

        $totalIncomeForRatio = $totalIncome > 0 ? $totalIncome : ($salary?->amount ?? 0);
        $expenseRatio = $totalIncomeForRatio > 0 ? ($totalMonthlyExpenses / $totalIncomeForRatio) * 100 : 0;

        if ($expenseRatio > 50) {
            $motivation = $dangerQuotes[array_rand($dangerQuotes)];
            $motivationColor = 'text-red-200';
            $motivationBg = 'bg-red-500/20';
        } elseif ($expenseRatio > 25) {
            $motivation = $cautionQuotes[array_rand($cautionQuotes)];
            $motivationColor = 'text-yellow-200';
            $motivationBg = 'bg-yellow-500/20';
        } else {
            $motivation = $safeQuotes[array_rand($safeQuotes)];
            $motivationColor = 'text-white/85';
            $motivationBg = '';
        }

        return view('dashboard', compact(
            'salary',
            'totalAdditionalIncome',
            'totalIncome',
            'allocations',
            'totalAllocated',
            'totalSpent',
            'remaining',
            'totalMonthlyExpenses',
            'recentExpenses',
            'dueRecurring',
            'greetingName',
            'timeGreeting',
            'motivation',
            'motivationColor',
            'motivationBg',
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

        return back()->with('success', 'Semua data berhasil direset. Kategori tetap tersimpan.');
    }
}
