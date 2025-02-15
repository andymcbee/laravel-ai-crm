<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $account1 = Account::factory()->create();
        $account2 = Account::factory()->create();
        $account3 = Account::factory()->create();

        $user1 = User::factory()->create([
            'name' => 'User One',
            'email' => '1@example.com',
            'password' => Hash::make('password'),
        ]);

        $user2 = User::factory()->create([
            'name' => 'User Two',
            'email' => '2@example.com',
            'password' => Hash::make('password'),
        ]);

        $user3 = User::factory()->create([
            'name' => 'User Three',
            'email' => '3@example.com',
            'password' => Hash::make('password'),
        ]);

        $account1->users()->attach([$user1->id, $user2->id]);
        $account2->users()->attach($user3->id);
        $account3->users()->attach($user1->id);

        Contact::factory(5)->for($account1)->create();
        Contact::factory(5)->for($account2)->create();
        Contact::factory(5)->for($account3)->create();

        Note::factory(100)->create();
    }
}
