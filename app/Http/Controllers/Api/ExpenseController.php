<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class ExpenseController extends Controller
{
    public function index(): JsonResponse
    {
        $expenses = request()->user()->expenses()->with('category')->latest()->get();

        return response()->json(['data' => $expenses]);
    }

    public function store(ExpenseRequest $request): JsonResponse
    {
        $user = $request->user();

        $category = Category::where('id', $request->validated('category_id'))
            ->where('user_id', $user->id)
            ->first();

        if (! $category) {
            return response()->json(['message' => 'Categoria inválida.'], 422);
        }

        $expense = Expense::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'description' => $request->validated('description'),
            'amount' => $request->validated('amount'),
            'date' => $request->validated('date'),
        ]);

        return response()->json($expense, 201);
    }

    public function show(Expense $expense): JsonResponse
    {
        $this->authorize('view', $expense);

        return response()->json($expense->load('category'));
    }

    public function update(ExpenseRequest $request, Expense $expense): JsonResponse
    {
        $this->authorize('update', $expense);

        $user = $request->user();

        $category = Category::where('id', $request->validated('category_id'))
            ->where('user_id', $user->id)
            ->first();

        if (! $category) {
            return response()->json(['message' => 'Categoria inválida.'], 422);
        }

        $expense->update($request->validated());

        return response()->json($expense->fresh());
    }

    public function destroy(Expense $expense): JsonResponse
    {
        $this->authorize('delete', $expense);

        $expense->delete();

        return response()->json(['message' => 'Expense deleted']);
    }
}
