<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;

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
            'activeAccount' => $activeAccount,
            'userAccounts' => $user->accounts,  
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

    public function update(Request $request, Contact $contact)
    {
        // check for auth
        // investigate how to ensure account_id isn't tampered with
        // we need it to be fillable, but a user should not update it
        // to just anything. only their own... or not at all?
        // needs to be fillable for creating though.
        // maybe throw error if it detects account_id?

        // Validate only editable fields
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
        ]);

        // Update and save contact
        $contact->update($validated);

        return redirect()->back()->with('success', 'Contact updated successfully.');
    }

    public function edit(Contact $contact)
    {

        $user = Auth::user();
        $activeAccount = session('active_account');

        if (!$activeAccount || !$user->accounts->contains('id', $activeAccount->getAttribute('id'))) {
            abort(403, 'Unauthorized access to account.');
        }

        return Inertia::render('Contacts/Edit', [
            'contact' => $contact,
            'activeAccount' => $activeAccount,
            'userAccounts' => $user->accounts,
        ]);
    }

    public function create()
    {
        return Inertia::render('Contacts/Create', []);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'nullable|exists:accounts,id',  // Validate accountId
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
        ]);

        Contact::create($validated);

        return redirect()->route('contacts.index')->with('success', 'Contact created successfully.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect('/contacts')->with('success', 'Contact deleted successfully.');
    }



}
