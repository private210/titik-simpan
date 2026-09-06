<?php

namespace App\Http\Controllers;

use App\Models\AdditionalIncome;
use App\Models\Category;
use App\Models\Salary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class BudgetController extends Controller
{
    public function index()
    {
        $salary = Salary::currentMonth()->first();

        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();

        $hasAdditional = Schema::hasTable('additional_incomes');
        $additionalIncomes = $hasAdditional
            ? AdditionalIncome::whereBetween('received_at', [$currentMonthStart, $currentMonthEnd])->latest('received_at')->get()
            : collect();

        $categories = Category::all();
        $allocations = $salary ? $salary->budgetAllocations()->with('category')->get() : collect();

        $totalAdditional = $additionalIncomes->sum('amount');

        return view('budget.index', compact(
            'salary',
            'additionalIncomes',
            'totalAdditional',
            'categories',
            'allocations'
        ));
    }

    public function storeSalary(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'received_at' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        $existing = Salary::whereDate('received_at', $validated['received_at'])->first();

        if ($existing) {
            $existing->update($validated);
            $salary = $existing;
        } else {
            $salary = Salary::create($validated);
        }

        return redirect()->route('budget.index')
            ->with('success', __('messages.budget.salary_saved'));
    }

    public function allocate(Request $request)
    {
        $validated = $request->validate([
            'salary_id' => 'required|exists:salaries,id',
            'allocations' => 'required|array',
            'allocations.*.category_id' => ['required', Rule::exists('categories', 'id')->where('user_id', auth()->id())],
            'allocations.*.amount' => 'required|numeric|min:0',
        ]);

        $salary = Salary::findOrFail($validated['salary_id']);

        DB::transaction(function () use ($salary, $validated) {
            $salary->budgetAllocations()->delete();

            foreach ($validated['allocations'] as $allocation) {
                if ($allocation['amount'] > 0) {
                    $salary->budgetAllocations()->create([
                        'category_id' => $allocation['category_id'],
                        'amount' => $allocation['amount'],
                    ]);
                }
            }
        });

        return redirect()->route('budget.index')
            ->with('success', __('messages.budget.allocation_saved'));
    }

    public function storeAdditionalIncome(Request $request)
    {
        if (! Schema::hasTable('additional_incomes')) {
            return back()->with('error', __('messages.budget.additional_unavailable'));
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'received_at' => 'required|date',
        ]);

        AdditionalIncome::create($validated);

        return redirect()->route('budget.index')
            ->with('success', __('messages.budget.additional_saved'));
    }
}
