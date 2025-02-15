<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $activeAccount = session('active_account');

        if (!$activeAccount || !$user->accounts->contains('id', $activeAccount->getAttribute('id'))) {
            abort(403, 'Unauthorized access to account.');
        }

        // Fetch contacts only for the active account
        $contacts = Contact::where('account_id', $activeAccount->getAttribute('id'))->get();

        return Inertia::render('Contacts/Index', [
            'contacts' => $contacts,
            'activeAccount' => $activeAccount,  // ✅ Pass active account explicitly
            'userAccounts' => $user->accounts,  // ✅ Pass accounts explicitly for dropdown
        ]);
    }


    public function show(Contact $contact)
    {
        $user = Auth::user();
        $activeAccount = session('active_account');

        if (!$activeAccount || !$user->accounts->contains('id', $activeAccount->getAttribute('id'))) {
            abort(403, 'Unauthorized access to account.');
        }

        $contact->load('notes');

        return Inertia::render('Contacts/Show', [
            'contact' => $contact->only(['id', 'first_name', 'last_name', 'phone', 'email', 'company', 'title']),
            'notes' => $contact->notes->map(fn($note) => [
                'text' => $note->text,
                'created_at' => $note->created_at->diffForHumans()
            ]),
            'activeAccount' => $activeAccount, // ✅ Pass active account explicitly
            'userAccounts' => $user->accounts, // ✅ Pass accounts explicitly for dropdown
        ]);
    }

}
