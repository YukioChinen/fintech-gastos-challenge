<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $start = Carbon::now()->startOfMonth()->toDateString();
        $end = Carbon::now()->endOfMonth()->toDateString();

        $total = Expense::where('user_id', $user->id)
            ->whereBetween('date', [$start, $end])
            ->sum('amount');

        $recent = Expense::where('user_id', $user->id)
            ->with('category')
            ->latest('date')
            ->limit(5)
            ->get();

        $byCategory = Expense::select('category_id', DB::raw('sum(amount) as total'))
            ->where('user_id', $user->id)
            ->whereBetween('date', [$start, $end])
            ->groupBy('category_id')
            ->with('category')
            ->get()
            ->map(function ($row) {
                return [
                    'category_id' => $row->category_id,
                    'category_name' => $row->category?->name,
                    'total' => (float) $row->total,
                ];
            });

        return response()->json([
            'total' => (float) $total,
            'recent' => $recent,
            'by_category' => $byCategory,
        ]);
    }
}
