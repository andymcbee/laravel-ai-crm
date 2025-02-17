<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $this->authorize('viewAny', Contact::class);
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
        $this->authorize('view', $contact);
        $activeAccount = session('active_account');

        if (!$activeAccount || !$user->accounts->contains('id', $activeAccount->getAttribute('id'))) {
            abort(403, 'Unauthorized access to account.');
        }

        $contact->load('notes');

        return Inertia::render('Contacts/Show', [
            'contact' => $contact->only(['id', 'first_name', 'last_name', 'phone', 'email', 'company', 'title']),
            'notes' => $contact->notes->map(fn($note) => [
                'id' => $note->id,
                'text' => $note->text,
                'created_at' => $note->created_at->diffForHumans(),
                'update_note' => Auth::user()->can('update', $note),
            ]),
            'activeAccount' => $activeAccount, // ✅ Pass active account explicitly
            'userAccounts' => $user->accounts, // ✅ Pass accounts explicitly for dropdown
            'can' => [
                'update_contact' => Auth::user()->can('update', $contact),
                'create_note' => Auth::user()->can('create', new Note(['account_id' => $activeAccount->getAttribute('id')])),

            ]
        ]);
    }

    public function update(Request $request, Contact $contact)
    {
        $this->authorize('update', $contact);

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
        $this->authorize('update', $contact);
        $activeAccount = session('active_account');

        if (!$activeAccount || !$user->accounts->contains('id', $activeAccount->getAttribute('id'))) {
            abort(403, 'Unauthorized access to account.');
        }

        // we pass the delete permission outcome because the delete section should only
        // be visible to admin, not moderators.
        return Inertia::render('Contacts/Edit', [
            'contact' => $contact,
            'activeAccount' => $activeAccount,
            'userAccounts' => $user->accounts,
            'can' => [
                'delete' => Auth::user()->can('delete', $contact),
            ]
        ]);
    }

    public function create()
    {

        $user = Auth::user();

        $activeAccount = session('active_account');

        // this does not work because the second param binds
        // to a specific policy that matches the type
        // instead, pass a new Contact and assign the relevant value
        $this->authorize('create', new Contact(['account_id' => $activeAccount->getAttribute('id')]));

        if (!$activeAccount || !$user->accounts->contains('id', $activeAccount->getAttribute('id'))) {
            abort(403, 'Unauthorized access to account.');
        }

        return Inertia::render('Contacts/Create', [
            'activeAccount' => $activeAccount,
            'userAccounts' => $user->accounts,
        ]);
    }

    public function store(Request $request)
    {

        $activeAccount = session('active_account');

        $this->authorize('create', new Contact(['account_id' => $activeAccount->getAttribute('id')]));


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
        $this->authorize('delete', $contact);
        $contact->delete();
        return redirect('/contacts')->with('success', 'Contact deleted successfully.');
    }



}
