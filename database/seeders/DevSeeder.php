<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DevSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create or update a dev user with known credentials
        $user = User::updateOrCreate(
            ['email' => 'teste@teste.com'],
            ['name' => 'Teste Teste', 'password' => bcrypt('senha1234')]
        );

        // Remove existing categories and expenses for idempotent seeding
        Expense::where('user_id', $user->id)->delete();
        Category::where('user_id', $user->id)->delete();

        // Create 3 categories for the user
        $categories = Category::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        // For each category create some expenses
        foreach ($categories as $category) {
            Expense::factory()->count(5)->create([
                'user_id' => $user->id,
                'category_id' => $category->id,
            ]);
        }
    }
}
