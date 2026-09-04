<?php

namespace App\Http\Controllers;

use App\Models\AdditionalIncome;
use App\Models\Category;
use App\Models\Salary;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index()
    {
        $salary = Salary::currentMonth()->first();

        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();

        $additionalIncomes = AdditionalIncome::whereBetween('received_at', [$currentMonthStart, $currentMonthEnd])
            ->latest('received_at')
            ->get();

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
            ->with('success', 'Gaji berhasil disimpan!');
    }

    public function storeAdditionalIncome(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'received_at' => 'required|date',
        ]);

        AdditionalIncome::create($validated);

        return redirect()->route('budget.index')
            ->with('success', 'Pendapatan tambahan berhasil disimpan!');
    }
}
