<div x-data = "{height:0 , conversation:document.getElementById('conversation')}"
    x-init = "
height = conversation.scrollHeight;
$nextTick(() => conversation.scrollTop = height);
"
    @scroll-bottom.window = "$nextTick(() => conversation.scrollTop = conversation.scrollHeight" class="w-full overflow-hidden">
    <div class="border-b flex flex-col overflow-y-scroll grow h-full">
        {{-- header --}}
        <header class="w-full sticky  flex  top-0 z-10 bg-white border-b ">

            <div class="flex w-full  pt-3 px-3 lg:px-4 gap-2 md:gap-5">


                {{-- avatar --}}


                <div class="shrink-0">
                    <x-avatar class="h-9 w-9 lg:w-11 lg:h-11" />
                </div>


                <h6 class="font-bold truncate tracking-wider">{{ $receiver->name }}</h6>


            </div>


        </header>
        {{-- @scroll="
        $scrollTop = $el.scrollTop;
        console.log($scrollTop);
        if($scrollTop <= 0){
            window.livewire.emit('loadMore');
        }" --}}
        <main x-data="{ scrollTop: 0 , height:0}" x-ref="messageContainer"
            style="height: 500px; overflow-y: auto; border: 1px solid #ccc;"
            @scroll.debounce.100ms="scrollTop = $el.scrollTop;
                if(scrollTop <= 0){
                $wire.loadMore();
                }"
            @update-scroll.window = "
                newHeight = $el.scrollHeight;
                console.log('new position:', newHeight);
                oldHeight = height;
                $el.scrollTop =newHeight-oldHeight;
                height = newHeight;

            "
            {{-- x-init="$nextTick(() => $refs.messageContainer.scrollTop = $refs.messageContainer.scrollHeight)" --}}
            class="flex p-4 flex-col gap-3" id="conversation">

            @if ($loadMessages)
                @php
                    $previousMessage = null;
                @endphp
                @foreach ($loadMessages as $key => $message)
                    @if ($key > 0)
                        @php
                            $previousMessage = $loadMessages->get($key - 1);
                        @endphp
                    @endif
                    <div
                    wire:key ="{{ time().$key }}"
                    @class(['flex', 'ml-auto' => $message->sender_id == Auth::user()->id])>

                        <div @class([
                            'shrink-0 pr-3',
                            'invisible' => $previousMessage?->sender_id == $message->sender_id,
                            'hidden' => $message->sender_id == Auth::user()->id,
                        ])>
                            <x-avatar />
                        </div>
                        <div @class([
                            'flex flex-wrap rounded-t-lg p-2 text-[15px] flex flex-col',
                            'bg-blue-500 text-white border rounded-bl-lg  border-gray-200/40' =>
                                $message->sender_id == Auth::user()->id,
                            'bg-gray-900 text-white border rounded-bl-lg  border-gray-200/40' => !(
                                $message->sender_id == Auth::user()->id
                            ),
                        ])>
                            <p class="truncate tracking-wide">

                                {{ $message->body }}
                            </p>
                            <div class="ml-auto flex items-center  gap-2">
                                <p @class([
                                    'text-xs',
                                    'text-white' => $message->sender_id == Auth::user()->id,
                                    'text-gray-500' => !($message->sender_id == Auth::user()->id),
                                ])>{{ $message->created_at->format('g:i a') }}</p>
                                @if ($message->sender_id == Auth::user()->id)
                                    <div>
                                        @if ($message->isRead())
                                            <span class="text-gray-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-check2-all" viewBox="0 0 16 16">
                                                    <path
                                                        d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z" />
                                                    <path
                                                        d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z" />
                                                </svg>
                                            </span>
                                        @else
                                            <span @class('text-gray-200')>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                                                    <path
                                                        d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                @endif


                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

        </main>
        <footer class="shrink-0 z-10 bg-white mt-auto ">

            <div class=" p-2 border-t ">

                <form x-data="{ body: @entangle('body') }" @submit.prevent="$wire.sendMessage" method="POST">
                    @csrf

                    <input type="hidden" autocomplete="false" style="display:none">

                    <div class="grid grid-cols-12">
                        <input x-model="body" x-ref="messageInput" type="text" autocomplete="off" autofocus
                            placeholder="write your message here" maxlength="1700"
                            class="col-span-10 bg-gray-100 border-0 outline-0 focus:border-0 focus:ring-0 hover:ring-0 rounded-lg focus:outline-none">

                        <button x-bind:disabled="!body.trim()" class="col-span-2" type="submit">
                            Send
                        </button>
                    </div>
                </form>



                @error('body')
                    <p> {{ $message }} </p>
                @enderror

            </div>





        </footer>
    </div>
</div>
<script>
    window.addEventListener('clearMessageInput', event => {
        document.querySelector('[x-ref=messageInput]').value = '';
    });
</script>
