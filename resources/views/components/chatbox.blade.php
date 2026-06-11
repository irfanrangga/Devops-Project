@php
    $session = Auth::user();
@endphp

@if($session)
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="fixed bottom-5 right-5 z-50 flex flex-col items-end space-y-2 font-sans">

        <div id="chat-window" 
             class="hidden w-80 md:w-96 bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100 transition-all duration-300 transform origin-bottom-right scale-95 opacity-0">
            
            <div class="bg-blue-600 p-4 flex justify-between items-center shadow-sm">
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                    <h3 class="text-white font-bold text-sm">Admin Support</h3>
                </div>
            </div>

            <div id="chat-container" class="h-80 overflow-y-auto p-4 bg-gray-50 flex flex-col space-y-3 scroll-smooth">
                <div class="flex justify-center items-center h-full">
                    <p class="text-gray-400 text-xs italic">Memuat percakapan...</p>
                </div>
            </div>

            <form id="chat-form" class="bg-white p-3 border-t border-gray-100 flex items-center gap-2">
                <input 
                    type="text" 
                    id="message-input" 
                    class="flex-1 bg-gray-100 border-0 text-sm rounded-full px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:bg-white transition outline-none"
                    placeholder="Tulis pesan..." 
                    autocomplete="off"
                >
                <button 
                    type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-3 flex-shrink-0 transition shadow-md disabled:opacity-50">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                        <path d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z" />
                    </svg>
                </button>
            </form>
        </div>

        <button id="chat-launcher" onclick="toggleChat()" 
            class="w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg flex items-center justify-center transition-transform hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-blue-300">
            
            <svg id="icon-chat" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
            </svg>

            <svg id="icon-close" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="hidden w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <script>
        (function() {
            const config = {
                getUrl: "{{ route('chat.get') }}",
                postUrl: "{{ route('chat.store') }}",
                csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };

            const els = {
                window: document.getElementById('chat-window'),
                launcher: document.getElementById('chat-launcher'),
                iconChat: document.getElementById('icon-chat'),
                iconClose: document.getElementById('icon-close'),
                container: document.getElementById('chat-container'),
                form: document.getElementById('chat-form'),
                input: document.getElementById('message-input'),
            };

            let state = {
                isOpen: false,
                hasLoaded: false
            };

            // Expose function ke window agar bisa dipanggil onclick HTML
            window.toggleChat = function() {
                state.isOpen = !state.isOpen;

                if (state.isOpen) {
                    els.window.classList.remove('hidden');
                    setTimeout(() => {
                        els.window.classList.remove('scale-95', 'opacity-0');
                        els.window.classList.add('scale-100', 'opacity-100');
                    }, 10);
                    els.iconChat.classList.add('hidden');
                    els.iconClose.classList.remove('hidden');

                    if (!state.hasLoaded) {
                        loadMessages();
                        state.hasLoaded = true;
                        setInterval(loadMessages, 5000); 
                    }
                    setTimeout(() => els.input.focus(), 300);
                } else {
                    els.window.classList.remove('scale-100', 'opacity-100');
                    els.window.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => els.window.classList.add('hidden'), 300);
                    els.iconChat.classList.remove('hidden');
                    els.iconClose.classList.add('hidden');
                }
            };

            async function loadMessages() {
                try {
                    const res = await fetch(config.getUrl, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const result = await res.json();
                    if (result.status === 'success') renderMessages(result.data);
                } catch (e) { console.error(e); }
            }

            function renderMessages(messages) {
                els.container.innerHTML = '';
                if (messages.length === 0) {
                    els.container.innerHTML = `<div class="flex flex-col items-center justify-center h-full text-gray-400 text-xs"><p>Belum ada pesan.</p></div>`;
                    return;
                }
                
                messages.forEach(msg => {
                    const isAdmin = msg.is_admin == 1;
                    const bubble = isAdmin 
                        ? 'bg-white text-gray-800 border border-gray-100 items-start rounded-tl-none' 
                        : 'bg-blue-600 text-white items-end rounded-tr-none';
                    
                    const html = `
                        <div class="flex flex-col w-full max-w-[85%] mb-2 ${isAdmin ? 'items-start' : 'items-end'}">
                            <div class="${bubble} px-4 py-2 rounded-2xl text-sm shadow-sm break-words">
                                ${msg.message}
                            </div>
                            <span class="text-[10px] text-gray-400 px-1 mt-1">
                                ${new Date(msg.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}
                            </span>
                        </div>`;
                    els.container.innerHTML += html;
                });
                els.container.scrollTop = els.container.scrollHeight;
            }

            els.form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const msg = els.input.value.trim();
                if (!msg) return;

                // Optimistic UI (Langsung tampil sebelum request selesai)
                const tempHtml = `
                    <div class="flex flex-col w-full max-w-[85%] mb-2 items-end opacity-70">
                        <div class="bg-blue-600 text-white px-4 py-2 rounded-2xl rounded-tr-none text-sm shadow-sm break-words">
                            ${msg}
                        </div>
                        <span class="text-[10px] text-gray-400 px-1 mt-1">Mengirim...</span>
                    </div>`;
                els.container.innerHTML += tempHtml;
                els.container.scrollTop = els.container.scrollHeight;
                els.input.value = '';

                try {
                    await fetch(config.postUrl, {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': config.csrfToken
                        },
                        body: JSON.stringify({ message: msg })
                    });
                    loadMessages();
                } catch (e) { alert('Gagal mengirim'); }
            });
        })();
    </script>
@endif