<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContactPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->accounts()->exists();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Contact $contact): bool
    {

        return $user->accounts()
            ->where('accounts.id', $contact->account_id)
            ->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Account $account): bool
    {
        // TODO: only allow mod and admin users to create
        return $user->accounts()
            ->where('accounts.id', $account->id)
            ->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Contact $contact): bool
    {
        // TODO: only allow mod and admin users to create
        return $user->accounts()
            ->where('accounts.id', $contact->account_id)
            ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Contact $contact): bool
    {
        // TODO: only allow admin users to create
        return $user->accounts()
            ->where('accounts.id', $contact->account_id)
            ->exists();
    }



}
