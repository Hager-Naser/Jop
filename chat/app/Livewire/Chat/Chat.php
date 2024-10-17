<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Chat extends Component
{
    public $id;
    public $selectedConversation ;
    // mount built in function in livewire like __construct
    public function mount(){
        $this->selectedConversation = Conversation::FindOrFail($this->id);
        Message::where('conversation_id' ,$this->selectedConversation->id)->OrWhere('receiver_id' , Auth::user()->id)->whereNull('read_at')->update(['read_at'=>now()]);
        // return $this->selected;
    }
    public function render()
    {
        return view('livewire.chat.chat');
    }
}
