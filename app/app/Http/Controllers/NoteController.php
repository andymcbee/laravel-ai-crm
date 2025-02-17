<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NoteController extends Controller
{
    public function store(Request $request){


        $user = Auth::user();
        $activeAccount = session('active_account');

        $this->authorize('create', new Note(['account_id' => $activeAccount->getAttribute('id')]));



        $validated = $request->validate([
            'text' => 'required',
            'contact_id' => 'required',
            'account_id' => 'required|exists:accounts,id',
        ]);



        Note::create([
            'text' => $request->text,
            'contact_id' => $request->contact_id,
            'account_id' => $request->account_id,
        ] + ['user_id' => $user->id]);

        return Inertia::location(route('contacts.show', ['contact' => $request->contact_id]));

    }

    public function update(Request $request, Note $note){

        $this->authorize('update', $note);


        $request->validate([
            'text' => 'required',
        ]);

        $note->update([
            'text' => $request->text,
        ]);

        return Inertia::location(route('contacts.show', ['contact' => $note->contact_id]));

    }

    public function edit(Note $note){
        $user = Auth::user();
        $activeAccount = session('active_account');
        $this->authorize('update', $note);

        if (!$activeAccount || !$user->accounts->contains('id', $activeAccount->getAttribute('id'))) {
            abort(403, 'Unauthorized access to account.');
        }

        return Inertia::render('Notes/Edit', [
            'note' => $note,
            'activeAccount' => $activeAccount,
            'userAccounts' => $user->accounts,
            'can' => [
                'update' => Auth::user()->can('update', $note),
                'create' => Auth::user()->can('create', $note),
            ]
        ]);
    }
}
