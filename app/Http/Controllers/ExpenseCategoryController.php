<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseCategoryRequest;
use App\Http\Requests\UpdateExpenseCategoryRequest;
use App\Models\ExpenseCategory;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $expenseCategories = ExpenseCategory::query()
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('expense-categories.index', compact('expenseCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('expense-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status');
        $data['store_id'] = auth()->user()->store_id ?? Store::query()->value('id');

        ExpenseCategory::create($data);

        return redirect()->route('expense-categories.index')->with('success', 'Expense category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpenseCategory $expenseCategory): View
    {
        return view('expense-categories.edit', compact('expenseCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseCategoryRequest $request, ExpenseCategory $expenseCategory): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status');

        $expenseCategory->update($data);

        return redirect()->route('expense-categories.index')->with('success', 'Expense category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseCategory $expenseCategory): RedirectResponse
    {
        try {
            $expenseCategory->delete();
        } catch (\Throwable $e) {
            return back()->with('error', 'Unable to delete this category. It may be in use.');
        }

        return redirect()->route('expense-categories.index')->with('success', 'Expense category deleted successfully.');
    }
}
