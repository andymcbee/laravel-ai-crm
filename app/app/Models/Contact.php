<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'title', 'company', 'email', 'phone', 'account_id', 'user_id'];
    public function account(): belongsTo { return $this->belongsTo(Account::class); }
    public function notes(): hasMany { return $this->hasMany(Note::class); }
}
