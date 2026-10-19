<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(10, 999),
            'type' => fake()->randomElement(['laptop', 'pc', 'sparepart']),
            'icon' => 'laptop',
            'description' => fake()->sentence(12),
            'meta_title' => ucwords($name).' - Cyber Gaming Store',
            'meta_description' => fake()->sentence(18),
            'sort_order' => 1,
            'is_active' => true,
        ];
    }
}
