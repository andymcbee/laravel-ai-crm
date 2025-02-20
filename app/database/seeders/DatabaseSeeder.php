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
            'first_name' => 'James',
            'last_name' => 'Dolce',
            'email' => 'james.dolcel@acme.com',
            'password' => Hash::make('password'),
        ]);

        $user2 = User::factory()->create([
            'first_name' => 'Tina',
            'last_name' => 'Steel',
            'email' => 'tina.steel@acme.com',
            'password' => Hash::make('password'),
        ]);

        $user3 = User::factory()->create([
            'first_name' => 'Jim',
            'last_name' => 'Smith',
            'email' => 'jsmith@jcorp.com',
            'password' => Hash::make('password'),
        ]);

        $account1->users()->attach([
            $user1->id => ['role' => 'admin'],
            $user2->id => ['role' => 'moderator']
        ]);

        $account2->users()->attach($user3->id, ['role' => 'moderator']);

        $account3->users()->attach($user1->id, ['role' => 'user']);


        Contact::factory(322)->for($account1)->create([
            'user_id' => $user1->id,
        ]);
        Contact::factory(443)->for($account1)->create([
            'user_id' => $user2->id,
        ]);

        Contact::factory(423)->for($account2)->create([
            'user_id' => $user3->id,
        ]);

        Contact::factory(402)->for($account3)->create([
            'user_id' => $user1->id,
        ]);



        $contacts = Contact::all();

        foreach ($contacts as $contact) {
            Note::factory(5)->create([
                'contact_id' => $contact->id,
                'account_id' => $contact->account_id,
                'user_id' => $contact->user_id,
            ]);
        }
    }
}
