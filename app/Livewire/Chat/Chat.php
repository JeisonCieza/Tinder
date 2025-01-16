<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use Livewire\Component;

class Chat extends Component
{
    public $chat;
    public $conversation;
    public $receiver;

    function mount()
    {
        #check auth
        abort_unless(auth()->check(),401);

        #get conversaation
        $this->conversation= Conversation::findOrFail($this->chat);

        #belongs to conversation
        $belognsToConversation = auth()->user()->conversations()
                                    ->where('id',$this->conversation->id)
                                    ->exists();
        abort_unless($belognsToConversation,403);

        #set receiver
        $this->receiver= $this->conversation->getReceiver();
    }
    public function render()
    {
        return view('livewire.chat.chat');
    }
}
