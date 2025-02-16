<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
