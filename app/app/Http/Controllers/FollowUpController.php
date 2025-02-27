<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\FollowUpSuggestion;
use App\Models\Note;
use Illuminate\Http\Request;

class FollowUpController extends Controller
{
    public function index(Request $request, Contact $contact)
    {
        $suggestions = FollowUpSuggestion::where('contact_id', $contact->id)
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();
        
        // Always return JSON (No Inertia rendering)
        return response()->json([
            'suggestions' => $suggestions
        ]);
    }


    public function generate(Request $request)
    {
        $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'user_message' => 'nullable|string'
        ]);

        $user = $request->user();
        $contact = Contact::findOrFail($request->contact_id);

        $notes = Note::where('contact_id', $contact->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $contextData = [
            'contact' => [
                'first_name' => $contact->first_name ?? 'Unknown',
                'last_name' => $contact->last_name ?? 'Unknown',
                'title' => $contact->title ?? 'Unknown',
                'company' => $contact->company ?? 'Unknown'
            ],
            'last_notes' => $notes->map(fn($note) => [
                'created_at' => $note->created_at,
                'content' => $note->content,
            ]),
            'user_message' => $request->user_message ?? '',
        ];

        // Placeholder AI response
        $generatedText = 'This is just a placeholder text.';

        // Store the follow-up suggestion in DB
        $suggestion = FollowUpSuggestion::create([
            'user_id' => $user->id,
            'contact_id' => $contact->id,
            'generated_text' => $generatedText,
            'context_data' => $contextData,
        ]);

        // Return JSON (No Inertia redirect)
        return response()->json([
            'message' => 'Follow-up generated successfully.',
            'suggestion' => $suggestion
        ]);
    }


}
