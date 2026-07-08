<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $expenses = Expense::query()
            ->with(['store', 'user'])
            ->search($request->string('search')->toString())
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->string('category')))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('expense_date', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('expense_date', '<=', $request->date('to')))
            ->latest('expense_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['store_id'] = auth()->user()->store_id ?? Store::query()->value('id');
        $data['user_id'] = auth()->id();

        Expense::create($data);

        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense): View
    {
        $expense->load(['store', 'user']);

        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense): View
    {
        return view('expenses.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, Expense $expense): RedirectResponse
    {
        $expense->update($request->validated());

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense): RedirectResponse
    {
        try {
            $expense->delete();
        } catch (\Throwable $e) {
            return back()->with('error', 'Unable to delete this expense. Please try again.');
        }

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
