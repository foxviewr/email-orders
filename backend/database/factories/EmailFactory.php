<?php

namespace Database\Factories;

use App\Models\Email;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailFactory extends Factory
{
    protected $model = Email::class;

    public function definition(): array
    {
        return [
            'sender' => $this->faker->safeEmail(),
            'recipient' => $this->faker->safeEmail(),
            'subject' => $this->faker->sentence(),
            'body' => $this->faker->paragraph(),
            'messageId' => $this->faker->uuid(),
            'inReplyTo' => $this->faker->uuid(),
            'from' => $this->faker->email(),
            'to' => $this->faker->email(),
            'order_uuid' => Order::factory(),
        ];
    }
} 