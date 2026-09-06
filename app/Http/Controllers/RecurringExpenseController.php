<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use App\Models\RecurringExpense;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RecurringExpenseController extends Controller
{
    public function index()
    {
        $recurringExpenses = RecurringExpense::with('category')
            ->orderBy('next_due_date')
            ->get();

        return view('recurring.index', compact('recurringExpenses'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('recurring.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => ['required', Rule::exists('categories', 'id')->where('user_id', auth()->id())],
            'amount' => 'required|numeric|min:0.01',
            'frequency' => 'required|in:daily,weekly,monthly,yearly',
            'next_due_date' => 'required|date',
        ]);

        RecurringExpense::create($validated);

        return redirect()->route('recurring.index')
            ->with('success', __('messages.recurring.saved'));
    }

    public function update(RecurringExpense $recurringExpense, Request $request)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        $recurringExpense->update($validated);

        return back()->with('success', __('messages.recurring.updated'));
    }

    public function edit(RecurringExpense $recurringExpense)
    {
        $categories = Category::all();

        return view('recurring.edit', compact('recurringExpense', 'categories'));
    }

    public function destroy(RecurringExpense $recurringExpense)
    {
        $recurringExpense->delete();

        return back()->with('success', __('messages.recurring.deleted'));
    }

    public function markPaid(RecurringExpense $recurringExpense)
    {
        Expense::create([
            'category_id' => $recurringExpense->category_id,
            'amount' => $recurringExpense->amount,
            'description' => $recurringExpense->name,
            'spent_at' => now(),
            'is_recurring' => true,
        ]);

        $recurringExpense->markAsPaid();

        return back()->with('success', __('messages.recurring.paid_success'));
    }
}
