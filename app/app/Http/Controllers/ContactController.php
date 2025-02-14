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
        $contacts = Contact::whereIn('account_id', $user->accounts->pluck('id'))->get();


        return Inertia::render('Contacts/Index', [
            'contacts' => $contacts
        ]);
    }

    public function show(Contact $contact)
    {
        $user = Auth::user();

        $contact->load('notes');

        return Inertia::render('Contacts/Show', [
            'contact' => $contact->only(['id', 'first_name', 'last_name', 'phone', 'email', 'company', 'title']),
            'notes' => $contact->notes->map(fn($note) => [
                'text' => $note->text,
                'created_at' => $note->created_at->diffForHumans()
            ])
        ]);

    }
}
