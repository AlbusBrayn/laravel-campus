<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

   
    protected $fillable = [
        'message',
        'sender_id',
        'receiver_id',
        'is_read',
        'send_date',
        'read_date',
    ];
}
