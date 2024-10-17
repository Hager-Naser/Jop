<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = ['sender_id' , 'receiver_id' , 'body' , 'conversation_id' , 'read_at' , 'sender_deleted_at' , 'receiver_deleted_at'];
    public function coversation(){
        return $this->belongsTo(Conversation::class);
    }
    protected $dates = ['read_at' , 'sender_deleted_at' , 'receiver_deleted_at'];
    public function isRead() :bool{
        return $this->read_at != null;
    }
}
