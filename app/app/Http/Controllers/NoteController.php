<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NoteController extends Controller
{
    public function store(Request $request){


        $request->validate([
            'text' => 'required',
            'contact_id' => 'required'
        ]);

        Note::create([
            'text' => $request->text,
            'contact_id' => $request->contact_id,
        ]);

    }

    public function update(Request $request, Note $note){
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

        if (!$activeAccount || !$user->accounts->contains('id', $activeAccount->getAttribute('id'))) {
            abort(403, 'Unauthorized access to account.');
        }

        return Inertia::render('Notes/Edit', [
            'note' => $note,
            'activeAccount' => $activeAccount,
            'userAccounts' => $user->accounts,
        ]);
    }
}
