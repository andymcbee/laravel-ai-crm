<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowUpSuggestion extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'contact_id', 'generated_text', 'context_data'];

    // context_data is stored as a json array in db
    protected $casts = [
        'context_data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}
