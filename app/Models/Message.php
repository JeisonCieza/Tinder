<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /** @use HasFactory<\Database\Factories\MessageFactory> */
    use HasFactory;

    protected $guarded= [];

    protected $dates = ['read_at'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function isRead()
    {
        return $this->read_at!=null;
    }
}
