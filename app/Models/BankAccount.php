<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'number',
        'holder_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
