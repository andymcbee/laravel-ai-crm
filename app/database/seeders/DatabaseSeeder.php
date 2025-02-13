<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $account1 = Account::factory()->create();
        $account2 = Account::factory()->create();

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $account1->users()->attach([$user1->id, $user2->id]);
        $account2->users()->attach($user3->id);

        Contact::factory(5)->for($account1)->create();
        Contact::factory(5)->for($account2)->create();

        Note::factory(10)->create();
    }
}
