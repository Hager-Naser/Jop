<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Notifications\MessageSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ChatBox extends Component
{
    public $body;
    public $selectedConversation;
    public $loadMessages ;
    public $paginate = 10;
    protected $listeners = ['loadMore'];
    public function getListeners(){
        $auth_id = Auth::user()->id;
        return [
            'loadMore',
            "echo-private:users.{$auth_id},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => 'BroadcastedNotifications'
        ];
    }
    public function BroadcastedNotifications($event){
        if($event['type'] == MessageSent::class){
            if($event['conversation']['id'] == $this->selectedConversation->id){
                $this->dispatch('scroll-bottom');
                $newMessage = Message::findorfail($event['message']['id']);
                $this->loadMessages->push($newMessage);
            }
        }
    }
    public function LoadMessage() {
        $count = Message::where('conversation_id' , $this->selectedConversation->id)->count();
        $this->loadMessages = Message::where('conversation_id' , $this->selectedConversation->id)
        ->skip($count-$this->paginate)
        ->take($this->paginate)
        ->get();
        return $this->loadMessages;
    }
    public function loadMore() {
        // dd("ok");
        $this->paginate += 10;
        $this->loadMessage();
        $this->dispatch('update-scroll');
        // $this->dispatch('update-scroll')->to('App\Livewire\Chat\ChatBox');
    }
    public function mount(){
        $this->LoadMessage();
    }
    public function getReceiver(){
        $conversations = User::FindOrFail(Auth::user()->id)->getConversations()->latest('updated_at')->get();
        $id = Auth::user()->id;
        foreach($conversations as $key => $conversation){
            if ($conversation->sender_id == $id && $conversation->receiver_id == $this->selectedConversation->receiver_id) {
                // Current user is the sender, so fetch the receiver
                $user = User::find($conversation->receiver_id);
            } elseif ($conversation->sender_id == $this->selectedConversation->sender_id && $conversation->receiver_id == $id) {
                // Current user is the receiver, so fetch the sender
                $user = User::find($conversation->sender_id);
            }
        }
        return $user;
    }
    public function sendMessage(){
        $this->validate(['body' => 'required|string']);
        $receiver = $this->getReceiver();
        $createdMessage = Message::create([
            'sender_id' => Auth::user()->id,
            'receiver_id' => $receiver->id,
            'body' => $this->body,
            'conversation_id' => $this->selectedConversation->id,
        ]);

        $this->reset('body');
        $this->loadMessages->push($createdMessage);
        $this->dispatch('clearMessageInput');
        $this->selectedConversation->updated_at = now();
        $this->selectedConversation->save();
        $this->dispatch('refresh')->to('App\Livewire\Chat\ChatList');
        $receiver->notify(
            new MessageSent(
                Auth::user(),$this->selectedConversation , $this->selectedConversation->receiver_id , $createdMessage
            )
        );
    }
    public function render()
    {
        $receiver = $this->getReceiver();
        return view('livewire.chat.chat-box' , ['receiver' => $receiver]);
    }
}
