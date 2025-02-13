<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $contacts = Contact::whereIn('account_id', $user->accounts->pluck('id'))->get();
        return response()->json($contacts, 200);
    }
}
