<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ChatList extends Component
{
    public $selectedConversation;
    public $listener = ['refresh' => '$refresh'];


    public function render()
    {
        $conversations = User::FindOrFail(Auth::user()->id)->getConversations()->latest('updated_at')->get();
        $users = [];
        $conver = [];
        $id = Auth::user()->id;
        foreach($conversations as $key => $conversation){
            if ($conversation->sender_id == $id) {
                // Current user is the sender, so fetch the receiver
                $users[$key] = DB::table('users')->where('id', $conversation->receiver_id)->first();
                $conver[$key] = $conversation;
            } elseif ($conversation->receiver_id == $id) {
                // Current user is the receiver, so fetch the sender
                $users[$key] = DB::table('users')->where('id', $conversation->sender_id)->first();
                $conver[$key] = $conversation;
            }
        }
        // $user = array_merge($users , $conver);
        return view('livewire.chat.chat-list' , ['users' => $users , 'conversations' => $conversations]);
    }
}
