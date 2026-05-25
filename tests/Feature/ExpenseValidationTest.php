<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ExpenseValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_amount_must_be_positive_and_date_not_too_future(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/expenses', [
                'description' => 'Bad amount',
                'amount' => 0,
                'date' => Carbon::today()->toDateString(),
                'category_id' => $category->id,
            ])
            ->assertStatus(422);

        $futureDate = Carbon::today()->addDays(2)->toDateString();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/expenses', [
                'description' => 'Too future',
                'amount' => 10.5,
                'date' => $futureDate,
                'category_id' => $category->id,
            ])
            ->assertStatus(422);
    }
}
