<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['contact_id', 'text'];
    public function contact(): belongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function account(): belongsTo { return $this->belongsTo(Account::class); }
}
