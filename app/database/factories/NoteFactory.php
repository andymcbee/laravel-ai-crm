<?php

namespace Database\Factories;

use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Select a contact that belongs to an account and a user
        $contact = Contact::inRandomOrder()->first();

        if (!$contact) {
            $contact = Contact::factory()->create(); // Create a contact if none exist
        }

        $startDate = Carbon::now()->subYears(1);
        $endDate = Carbon::now();

        return [
            'contact_id' => $contact->id,
            'account_id' => $contact->account_id,  // Ensure account_id matches the contact's account
            'user_id' => $contact->user_id,        // Ensure user_id matches the contact's user
            'text' => $this->faker->sentence(),
            'created_at' => $this->faker->dateTimeBetween($startDate, $endDate),
            'updated_at' => now(),
        ];
    }
}
