<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'supplier_id' => Supplier::factory(),
            'name' => $this->faker->name(),
            'slug' => $this->faker->slug(),
            'price' => $this->faker->randomFloat(2, 0, 999999.99),
            'discount_price' => $this->faker->randomFloat(2, 0, 999999.99),
            'qty' => $this->faker->numberBetween(-10000, 10000),
            'sale_qty' => $this->faker->numberBetween(-10000, 10000),
            'sku' => $this->faker->word(),
            'status' => $this->faker->randomElement(["true","false"]),
            'stock' => $this->faker->randomElement(["true","false"]),
            'image' => $this->faker->word(),
            'manufacture_date' => $this->faker->date(),
            'expiry_date' => $this->faker->date(),
        ];
    }
}
