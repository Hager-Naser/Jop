<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth ;
use Livewire\Component;

class Users extends Component
{
    public function message($userId){
        $authUser = Auth::user()->id;
        $conversations = Conversation::where(function($query) use ($userId , $authUser){
            $query->where("sender_id" , $authUser)->where("receiver_id" , $userId);
        })->orWhere(function($query) use ($userId , $authUser){
            $query->where("sender_id" , $userId)->where("receiver_id" , $authUser);
        })->first();

        if($conversations){
            return redirect()->route('chat.chat' , ["id" => $conversations->id]);
        }
        $createconver= Conversation::create([
            'sender_id' => $authUser,
            'receiver_id' => $userId
        ]);
        return redirect()->route('chat.chat' , ["id" => $createconver->id]);

    }
    public function render()
    {
        return view('livewire.chat.users' , ['users' => User::where('id' ,"!=" ,Auth::user()->id)->get()]);
    }
}
