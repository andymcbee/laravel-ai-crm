<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\Note;
use App\Models\User;

class NotePolicy
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
    public function view(User $user, Note $note): bool
    {

        // any role may view any contact within an account they belong to
        return $user->accounts()
            ->where('accounts.id', $note->account_id)
            ->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Note $note): bool
    {
        return $user->accounts()
            ->where('accounts.id', $note->account_id) // Extract account_id from the Note model
            ->wherePivotIn('role', ['admin', 'moderator'])
            ->exists();
    }


    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Note $note): bool
    {

        


        return $user->accounts()
            ->where('accounts.id', $note->account_id)
            ->wherePivotIn('role', ['admin', 'moderator'])
            ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Note $note): bool
    {
        return $user->accounts()
            ->where('accounts.id', $note->account_id)
            ->wherePivotIn('role', ['admin'])
            ->exists();
    }
}
