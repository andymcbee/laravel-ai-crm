<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\FollowUpSuggestion;
use App\Models\Note;
use App\Services\ActiveAccountService;
use Illuminate\Http\Request;
use Inertia\Inertia;


class ContactController extends Controller
{

    protected ActiveAccountService $activeAccountService;

    public function __construct(ActiveAccountService $activeAccountService)
    {
        $this->activeAccountService = $activeAccountService;
    }

    public function index(Request $request)
    {


        // Laravel's default dependancy injection gives us access to authenticated user
        // via the Request object. No need to manually use the auth facade.
        // bad: $user = Auth::user();
        $user = $request->user();

        // Authorization
        $this->authorize('viewAny', Contact::class);

        // Retrieve the active account from the session
        // moved this to a servivce class to check it is actually null or account type
        //$activeAccount = session('active_account');

        $activeAccount = $this->activeAccountService->getActiveAccount();


        // Fetch contacts only for the active account
        $contacts = Contact::where('account_id', $activeAccount->getAttribute('id'))->paginate(25);


        return Inertia::render('Contacts/Index', [
            'contacts' => $contacts,
            'activeAccount' => $activeAccount,
            'userAccounts' => $user->accounts,
        ]);
    }


    public function show(Request $request, Contact $contact)
    {
        $user = $request->user();

        // authorize
        $this->authorize('view', $contact);

        $activeAccount = $this->activeAccountService->getActiveAccount();

        // Paginate notes and retain 'update_note' field
        $notes = $contact->notes()->latest()->paginate(5)->through(fn($note) => [
            'id' => $note->id,
            'text' => $note->text,
            'created_at' => $note->created_at->diffForHumans(),
            'update_note' => $user->can('update', $note),
        ]);

        return Inertia::render('Contacts/Show', [
            'contact' => $contact->only(['id', 'first_name', 'last_name', 'phone', 'email', 'company', 'title']),
            'notes' => $notes,
            'followUps' => FollowUpSuggestion::where('contact_id', $contact->id)
                ->where('user_id', auth()->id())
                ->orderByDesc('created_at')
                ->get(),
            'activeAccount' => $activeAccount,
            'userAccounts' => $user->accounts,
            'can' => [
                'update_contact' => $user->can('update', $contact),
                'create_note' => $user->can('create', new Note(['account_id' => $activeAccount->getAttribute('id')])),
            ]
        ]);
    }


    public function update(Request $request, Contact $contact)
    {

        $this->authorize('update', $contact);

        // Validate only editable fields

        // always use array syntax for validation

        // bad:
//        $validated = $request->validate([
//            'first_name' => 'required|string|max:255',
//            'last_name' => 'required|string|max:255',
//            'email' => 'required|email|max:255',
//            'phone' => 'nullable|string|max:20',
//            'company' => 'nullable|string|max:255',
//            'title' => 'nullable|string|max:255',
//        ]);
        // good:
        $validated = $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'company' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
        ]);


        // Update and save contact
        // Never use mass assignment
        // Passing the validated model is secure, however it is unreadable and harder to debug
        // This also decreases the chances of a dev accidentally passing an unsafe object such as a direct unvalidated
        // request object into the db
        // bad: $contact->update($validated);

        // instead, incrementally build the contact

        $contact->first_name = $validated['first_name'] ?? null;
        $contact->last_name = $validated['last_name'] ?? null;
        $contact->email = $validated['email'] ?? null;
        $contact->phone = $validated['phone'] ?? null;
        $contact->company = $validated['company'] ?? null;
        $contact->title = $validated['title'] ?? null;

        // once the object has been built, save it
        $contact->save();

        return redirect()->back()->with('success', 'Contact updated successfully.');
    }

    public function edit(Request $request, Contact $contact)
    {

        $user = $request->user();


        $this->authorize('update', $contact);

        $activeAccount = $this->activeAccountService->getActiveAccount();


        // we pass the delete permission outcome because the delete section should only
        // be visible to admin, not moderators.
        return Inertia::render('Contacts/Edit', [
            'contact' => $contact,
            'activeAccount' => $activeAccount,
            'userAccounts' => $user->accounts,
            'can' => [
                'delete' => $user()->can('delete', $contact),
            ]
        ]);
    }

    public function create(Request $request)
    {

        $user = $request->user();

        $activeAccount = $this->activeAccountService->getActiveAccount();

        // this does not work because the second param binds
        // to a specific policy that matches the type
        // instead, pass a new Contact and assign the relevant value
        $this->authorize('create', new Contact(['account_id' => $activeAccount->getAttribute('id')]));


        return Inertia::render('Contacts/Create', [
            'activeAccount' => $activeAccount,
            'userAccounts' => $user->accounts,
        ]);
    }

    public function store(Request $request)
    {

        $user = $request->user();

        $activeAccount = $this->activeAccountService->getActiveAccount();

        $this->authorize('create', new Contact(['account_id' => $activeAccount->getAttribute('id')]));

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
        ]);

        // replace mass assignment with incremental build of the object

        //bad due to mass assigned validated object being passed:
        // Contact::create($validated + ['user_id' => $user->id]);

        // good:

        $contact = new Contact();
        $contact->first_name = $validated['first_name'] ?? null;
        $contact->last_name = $validated['last_name'] ?? null;
        $contact->email = $validated['email'] ?? null;
        $contact->phone = $validated['phone'] ?? null;
        $contact->company = $validated['company'] ?? null;
        $contact->title = $validated['title'] ?? null;

        // fetch account_id from session, not front end
        $contact->account_id = $activeAccount->getAttribute('id');

        $contact->user_id = $user->id;

        $contact->save();

        return redirect()->route('contacts.index')->with('success', 'Contact created successfully.');
    }

    public function destroy(Contact $contact)
    {
        // no need to access the auth instance due to it being protected
        // via middleware
        $this->authorize('delete', $contact);
        $contact->delete();
        return redirect('/contacts')->with('success', 'Contact deleted successfully.');
    }


}
