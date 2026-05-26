<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Category;
use App\Models\Expense;
use Carbon\Carbon;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_returns_total_recent_and_by_category_for_current_month()
    {
        $user = User::factory()->create();

        $catA = Category::factory()->create(['user_id' => $user->id, 'name' => 'Alimentacao']);
        $catB = Category::factory()->create(['user_id' => $user->id, 'name' => 'Transporte']);

        $today = Carbon::now();

        $e1 = Expense::factory()->create([
            'user_id' => $user->id,
            'category_id' => $catA->id,
            'amount' => 100,
            'date' => $today->toDateString(),
        ]);

        $e2 = Expense::factory()->create([
            'user_id' => $user->id,
            'category_id' => $catA->id,
            'amount' => 50,
            'date' => $today->copy()->subDay()->toDateString(),
        ]);

        $e3 = Expense::factory()->create([
            'user_id' => $user->id,
            'category_id' => $catB->id,
            'amount' => 25,
            'date' => $today->copy()->subDays(2)->toDateString(),
        ]);

        // an expense outside current month should not be counted in total/by_category
        $old = Expense::factory()->create([
            'user_id' => $user->id,
            'category_id' => $catA->id,
            'amount' => 999,
            'date' => $today->copy()->subMonth()->toDateString(),
        ]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/auth/dashboard');

        $response->assertStatus(200)->assertJsonStructure(['total', 'recent', 'by_category']);

        $data = $response->json();

        // total should sum only current month expenses: 100 + 50 + 25 = 175
        $this->assertEquals(175.0, (float) $data['total']);

        // recent should include the created expenses (ids present)
        $recentIds = array_column($data['recent'] ?? [], 'id');
        $this->assertContains($e1->id, $recentIds);
        $this->assertContains($e2->id, $recentIds);
        $this->assertContains($e3->id, $recentIds);

        // by_category should reflect totals for current month
        $by = collect($data['by_category']);
        $rowA = $by->firstWhere('category_id', $catA->id);
        $rowB = $by->firstWhere('category_id', $catB->id);

        $this->assertNotNull($rowA);
        $this->assertNotNull($rowB);

        $this->assertEquals(150.0, (float) $rowA['total']);
        $this->assertEquals(25.0, (float) $rowB['total']);
    }
}
