<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function user1()
    {
        return $this->belongsTo(User::class,'user1');
    }

    public function user2()
    {
        return $this->belongsTo(User::class,'user2');
    }
}
