<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Supplier;
use App\Models\SupplierPayment;

class SupplierPaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SupplierPayment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::factory(),
            'payment' => $this->faker->randomFloat(2, 0, 9999999999.99),
            'payment_method' => $this->faker->randomElement(["cash","online"]),
            'reciver' => $this->faker->word(),
        ];
    }
}
