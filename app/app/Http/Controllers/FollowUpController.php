<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\FollowUpSuggestion;
use App\Models\Note;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FollowUpController extends Controller
{
    public function index(Request $request,Contact $contact)
    {
        $suggestions = FollowUpSuggestion::where('contact_id', $contact->id)
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('FollowUps/Index', [
            'contact' => $contact,
            'suggestions' => $suggestions,
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

        $notes = Note::where('contact_id', $request->contact_id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // contectData is where you pass in relevant data the LLM should reference
        // it's also stored in the db so we can see the actual data the LLM used

        $contextData = [
            'contact' => [
                'first_name' => $contact->first_name ?? 'Unknown',
                'last_name' => $contact->last_name ?? 'Unknown',
                'title' => $contact->title ?? 'Unknown',
                'company' => $contact->company ?? 'Unknown'
            ],
            'last_notes' => $notes->map(fn ($note) => [
                'created_at' => $note->created_at,
                'content' => $note->content,
            ]),
            'user_message' => $request->user_message ?? '',
        ];

        // construct context
        $formattedContext = "Contact: {$contact->first_name} {$contact->last_name}, {$contact->title} at {$contact->company}\n\n";
        foreach ($contextData['last_notes'] as $note) {
            $formattedContext .= "- {$note['content']} ({$note['created_at']})\n";
        }
        $formattedContext .= "\nUser Context: {$contextData['user_message']}";

        // temp placeholder
        // replace with actual openAI API call

        $generatedText = 'This is just a placeholder text.';

        // store suggestion in DB

        $suggestion = FollowUpSuggestion::create([
            'user_id' => $user->id,
            'contact_id' => $contact->id,
            'generated_text' => $generatedText,
            'context_data' => $contextData,
        ]);

        // intertia res

        return Inertia::location(route('follow-up.index', ['contact' => $contact->id]));


    }
}
