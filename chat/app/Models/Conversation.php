<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Conversation extends Model
{
    use HasFactory;
    protected $fillable = ['sender_id' , 'receiver_id'];
    public function messages(){
        return $this->hasMany(Message::class);
    }
    public function unReadMessage(){
        return Message::where('conversation_id' , $this->id)->where('receiver_id' , Auth::user()->id)->whereNull('read_at')->count();
    }
    public function lastMessage()  {
        $lastMessage = $this->messages()->latest()->first();
        if($lastMessage){
            return $lastMessage->read_at != null && $lastMessage->sender_id = Auth::user()->id;
        }
    }
}
