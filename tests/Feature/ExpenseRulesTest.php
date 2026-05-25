<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseRulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_create_expense_with_other_users_category(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $categoryOfB = Category::factory()->for($userB)->create();

        $this->actingAs($userA, 'sanctum')
            ->postJson('/api/expenses', [
                'description' => 'Test',
                'amount' => 10.00,
                'date' => now()->toDateString(),
                'category_id' => $categoryOfB->id,
            ])
            ->assertStatus(422);
    }
}
