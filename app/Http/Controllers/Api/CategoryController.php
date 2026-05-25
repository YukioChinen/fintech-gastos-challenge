<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = request()->user()->categories()->orderBy('name')->get();

        return response()->json(['data' => $categories]);
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        $category = Category::create([
            'user_id' => $request->user()->id,
            'name' => $request->validated('name'),
        ]);

        return response()->json($category, 201);
    }

    public function show(Category $category): JsonResponse
    {
        $this->authorize('view', $category);

        return response()->json($category);
    }

    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        $this->authorize('update', $category);

        $category->update($request->validated());

        return response()->json($category);
    }

    public function destroy(Category $category): JsonResponse
    {
        $this->authorize('delete', $category);

        $category->delete();

        return response()->json(['message' => 'Category deleted']);
    }
}
