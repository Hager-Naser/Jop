<div class="flex flex-col transition-all  overflow-hidden">
    <header class="px-3 z-10 bg-white sticky top-0 w-full py-2">

        <div class="border-b justify-between flex items-center pb-2">

            <div class="flex items-center gap-2">
                <h5 class="font-extrabold text-2xl">Chats</h5>
            </div>

            <button>

                <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    viewBox="0 0 16 16">
                    <path
                        d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z" />
                </svg>

            </button>

        </div>

        {{-- Filters --}}

        <div class="flex gap-3 items-center p-2 bg-white" x-data="{ type: 'all' }">

            <button @click="type='all'" :class="{ 'bg-blue-100 border-0 text-black': type=='all' }"
                class="inline-flex justify-center items-center rounded-full gap-x-1 text-xs font-medium px-3 lg:px-5 py-1  lg:py-2.5 border ">
                All
            </button>
            <button @click="type='deleted'" :class="{ 'bg-blue-100 border-0 text-black': type=='deleted' }"
                class="inline-flex justify-center items-center rounded-full gap-x-1 text-xs font-medium px-3 lg:px-5 py-1  lg:py-2.5 border ">
                Deleted
            </button>

        </div>

    </header>
    <main class="overflow-hidden overflow-y-scroll relative h-full" style="contain: content">
        <ul class="p-2 grid w-full spacey-y-2">
            @forelse ($users as $user )
                @foreach ($conversations as $conversation)
                    @if ($conversation->sender_id == $user->id || $conversation->receiver_id == $user->id)
                        <li
                            class="py-3 hover:bg-gray-50 rounded-2xl {{ $conversation->id == $selectedConversation?->id ? 'bg-gray-100/70' : '' }} :hover:bg-gray-700/70 transition-colors duration-150 flex gap-4 relative w-full cursor-pointer px-2">
                            <a href="#" class="">
                                <x-avatar />
                            </a>
                            <aside class="grid grid-cols-12 w-full">
                                <a href="{{ route('chat.chat', $conversation->id) }}"
                                    class="col-span-11 border-b pb-2 hover:no-underline hover:text-gray-900 border-gray-200 relative  truncate w-full flex-nowrap p-1">

                                    <div class="flex justify-between w-full items-center">
                                        <h6 class="truncate font-medium tracking-wider text-gray-900">
                                            {{ $user->name }}
                                        </h6>
                                        <small class="text-gray-700">
                                            {{ $conversation->messages?->last()?->created_at?->shortAbsoluteDiffForHumans() }}</small>
                                    </div>
                                    <div class="flex gap-x-2 items-center">
                                        @if ($conversation->messages?->last()?->sender_id == Auth::user()->id)
                                            @if ($conversation->lastMessage())
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor" class="bi bi-check2-all"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z" />
                                                        <path
                                                            d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z" />
                                                    </svg>
                                                </span>
                                            @else
                                                <span>

                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor" class="bi bi-check2"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
                                                    </svg>
                                                </span>
                                            @endif
                                        @endif
                                        <p class="grow truncate text-sm font-[100]">
                                            {{ $conversation?->messages?->last()?->body ?? '' }}
                                        </p>
                                        @if ($conversation->unReadMessage() > 0)
                                            <span
                                                class="font-bold p-px px-2 text-xs shrink-0 rounded-full bg-blue-500 text-white">
                                                {{ $conversation->unReadMessage() }}
                                            </span>
                                        @endif
                                    </div>

                                </a>
                            </aside>
                        </li>
                    @endif
                @endforeach
            @empty
                no chat
            @endforelse
        </ul>
    </main>
</div>
