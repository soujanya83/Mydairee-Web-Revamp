@extends('layout.master')
@section('title', 'Messaging')
@section('parentPageTitle', 'Messaging')

@section('content')
<style>
    /* Main container card */
    .messaging-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        max-width: 1400px;
        margin-top: -30px;
    }

    /* Header for the entire card */
    .messaging-card-header {
        padding: 20px 24px;
        border-bottom: 2px solid #f0f0f0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
    }

    .messaging-card-header h4 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .messaging-card-header .subtitle {
        font-size: 14px;
        opacity: 0.9;
        margin-top: 4px;
    }

    /* Layout */
    .messaging-wrapper { 
        display: flex; 
        gap: 0; 
        height: 75vh;
    }

    .contacts-list {  
        width: 380px;
        border-right: 2px solid #f0f0f0;
        display: flex;
        flex-direction: column;
        background: #fafbfc;
        height: 100%;
    }

    .chat-panel { 
        flex: 1; 
        display: flex; 
        flex-direction: column; 
        background: #ffffff;
        height: 100%;
    }

    /* Contacts list header & search */
    .contacts-list .contacts-header { 
        padding: 16px 20px; 
        display: flex; 
        align-items: center; 
        gap: 8px; 
        border-bottom: 1px solid #e8ecef;
        background: #fff;
    }

    
    .contacts-list .contacts-search { 
        padding: 12px 16px; 
        border-bottom: 1px solid #e8ecef;
        background: #fff;
    }

    .contacts-list .contacts-search input { 
        width: 100%; 
        padding: 12px 16px 12px 40px; 
        border-radius: 24px; 
        border: 1px solid #dde2e8;
        background: #f8f9fa url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23999' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E") no-repeat 14px center;
        font-size: 14px;
        transition: all 0.2s;
    }

    .contacts-list .contacts-search input:focus {
        outline: none;
        border-color: #667eea;
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .contacts-container {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* Contact items */
    .contact-item { 
        padding: 14px 16px; 
        border-bottom: 1px solid #f0f3f5; 
        cursor: pointer; 
        display: flex; 
        gap: 14px; 
        align-items: center; 
        transition: all 0.15s ease;
        position: relative;
        background: #fff;
    }

    .contact-item:hover { 
        background: #f8f9fb;
        transform: translateX(2px);
    }

    .contact-item.unread { 
        background: #e8f5e9;
    }

    .contact-item.unread:hover {
        background: #d4f1d8;
    }

    .contact-item.active { 
        background: #eef2ff;
        border-left: 4px solid #667eea;
        padding-left: 12px;
    }

    .contact-item.active:hover {
        background: #e0e7ff;
    }

    .contact-avatar-wrapper {
        position: relative;
        flex-shrink: 0;
    }

    .contact-avatar { 
        width: 56px; 
        height: 56px; 
        border-radius: 50%; 
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .contact-avatar-wrapper .online-indicator {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        background: #4caf50;
        border: 2px solid #fff;
        border-radius: 50%;
    }

    .contact-meta { 
        flex: 1; 
        display: flex; 
        flex-direction: column;
        gap: 4px;
        min-width: 0;
    }

    .contact-meta .name { 
        font-weight: 600;
        font-size: 15px;
        color: #1a1a1a;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .contact-meta .snippet { 
        color: #6b7280; 
        font-size: 13px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .badge-unread { 
        background: #667eea;
        color: #fff; 
        font-weight: 700; 
        padding: 4px 9px; 
        border-radius: 12px; 
        font-size: 11px;
        box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
    }

    /* Chat header */
    #chatHeader { 
        background: #ffffff;
        align-items: center; 
        padding: 16px 20px; 
        border-bottom: 2px solid #f0f3f5;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
    }

    #chatHeader .contact-avatar { 
        width: 48px; 
        height: 48px;
        border: 2px solid #f0f0f0;
    }

    #chatHeader .chat-info { 
        margin-left: 12px; 
    }

    #chatHeader .chat-info .name { 
        font-weight: 700;
        font-size: 16px;
        color: #1a1a1a;
    }

    #chatHeader .chat-info .status { 
        font-size: 13px; 
        color: #6b7280;
        margin-top: 2px;
    }

    #chatHeader .header-actions { 
        margin-left: auto; 
        display: flex; 
        gap: 4px; 
        align-items: center; 
    }

    #chatHeader .icon {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        cursor: pointer;
        color: #6b7280;
        transition: all 0.2s;
        font-size: 18px;
    }

    #chatHeader .icon:hover {
        background: #f3f4f6;
        color: #667eea;
    }

    /* Messages area */
    .messages { 
        padding: 20px 24px; 
        overflow-y: auto; 
        flex: 1; 
        background: linear-gradient(180deg, #fafbfc 0%, #f8f9fa 100%);
    }

    .message-row { 
        margin-bottom: 14px; 
        max-width: 65%; 
        display: flex;
        animation: messageSlide 0.3s ease;
    }

    @keyframes messageSlide {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message-row.me { 
        margin-left: auto; 
        justify-content: flex-end; 
    }

    .message-bubble { 
        padding: 12px 16px; 
        border-radius: 18px; 
        background: #ffffff; 
        color: #1a1a1a; 
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        display: inline-block; 
        position: relative;
        word-wrap: break-word;
        border: 1px solid #f0f0f0;
    }

    .message-bubble.me { 
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff; 
        border-bottom-right-radius: 4px;
        border: none;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .message-row .meta { 
        font-size: 11px; 
        color: #999; 
        margin-top: 6px; 
        display: flex; 
        gap: 6px; 
        align-items: center; 
    }

    .tick { 
        font-size: 13px; 
        color: rgba(255, 255, 255, 0.7);
        margin-left: 8px;
    }

    .tick.read { 
        color: #ffffff;
    }

    /* Date separator */
    .date-sep { 
        text-align: center; 
        color: #9ca3af; 
        font-size: 13px; 
        margin: 20px 0;
        font-weight: 500;
    }

    /* Chat input */
    #chatInput { 
        padding: 16px 20px; 
        border-top: 2px solid #f0f3f5;
        display: flex; 
        gap: 12px; 
        align-items: center; 
        background: #fafbfc;
    }

    #chatInput textarea { 
        flex: 1; 
        resize: none; 
        padding: 12px 16px; 
        border-radius: 24px; 
        border: 1px solid #dde2e8;
        font-size: 14px;
        transition: all 0.2s;
        background: #fff;
    }

    #chatInput textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    #chatInput .btn-primary {
        padding: 12px 24px;
        border-radius: 24px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        font-weight: 600;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    #chatInput .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
    }

    #chatInput .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* Placeholder */
    #messagesPlaceholder { 
        color: #9ca3af;
        font-size: 28px; 
        font-weight: 700;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
    }

    #messagesPlaceholder .icon-large {
        font-size: 72px;
        opacity: 0.3;
    }

    /* Scrollbar styling */
    .contacts-container::-webkit-scrollbar,
    .messages::-webkit-scrollbar {
        width: 6px;
    }

    .contacts-container::-webkit-scrollbar-track,
    .messages::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .contacts-container::-webkit-scrollbar-thumb,
    .messages::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 3px;
    }

    .contacts-container::-webkit-scrollbar-thumb:hover,
    .messages::-webkit-scrollbar-thumb:hover {
        background: #a0aec0;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .contacts-list {
            width: 320px;
        }
        
        .message-row {
            max-width: 75%;
        }
    }

    @media (max-width: 768px) {
        .messaging-card {
            border-radius: 8px;
        }

        .contacts-list {
            width: 100%;
            border-right: none;
            border-bottom: 2px solid #f0f0f0;
            max-height: 300px;
        }

        .messaging-wrapper {
            flex-direction: column;
            height: auto;
        }

        .chat-panel {
            min-height: 500px;
        }
    }
</style>

<div class="container-fluid px-4 py-4">
    <div class="messaging-card">
        <div class="messaging-card-header">
            <h4>
                <span>💬</span>
                Messaging
            </h4>
            <div class="subtitle">Stay connected with your team</div>
        </div>

        <div class="messaging-wrapper">
            <div class="contacts-list">
                <div class="contacts-search">
                    <input id="contactsSearch" type="text" placeholder="Search or start new chat" />
                </div>
                <div id="contacts" class="contacts-container"></div>
            </div>

            <div class="chat-panel">
                <div id="chatHeader" style="display:none; gap:12px;">
                    <div class="contact-avatar-wrapper">
                        <img id="chatAvatar" src="{{ asset('assets/img/xs/avatar1.jpg') }}" class="contact-avatar" />
                    </div>
                    <div class="chat-info">
                        <div id="chatName" class="name"></div>
                        <div id="chatSub" class="status">&nbsp;</div>
                    </div>
                    <div class="header-actions">
                        <div class="icon" title="Search">🔍</div>
                        <div class="icon" title="More">⋮</div>
                    </div>
                </div>

                <div class="messages" id="messages">
                    <div id="messagesPlaceholder" style="margin-top:35%;">
                        <div class="icon-large">💬</div>
                        <div>Select a contact to start messaging</div>
                    </div>
                </div>

                <div id="chatInput" style="display:none">
                    <textarea id="messageText" rows="2" placeholder="Type a message..."></textarea>
                    <button id="sendBtn" class="btn btn-primary">Send</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const _csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = _csrfMeta ? _csrfMeta.getAttribute('content') : '';
    let pollInterval = null;
    const authId = {{ auth()->id() ?? 'null' }};
    let currentContactId = null;

    async function fetchContacts() {
        const res = await fetch('{{ url('/messaging/contacts') }}', { credentials: 'same-origin' });
        const data = await res.json();
        if (!data.success) return;
        const container = document.getElementById('contacts');
        container.innerHTML = '';
        data.contacts.forEach(c => {
            const el = document.createElement('div');
            el.className = 'contact-item' + (c.unread_count && c.unread_count > 0 ? ' unread' : '');
            const avatar = c.imageUrl ? ('/'+c.imageUrl) : '{{ asset('assets/img/xs/avatar1.jpg') }}';
            const lastMsg = c.last_message ? c.last_message.slice(0,60) : '';
            const unread = c.unread_count && c.unread_count > 0 ? `<span class="badge-unread">${c.unread_count}</span>` : '';
            let role = '';
            if (c.userType) {
                const raw = String(c.userType).trim().toLowerCase();
                if (raw !== 'parent' && raw !== 'staff') {
                    const disp = String(c.userType).trim();
                    const label = disp.charAt(0).toUpperCase() + disp.slice(1);
                    role = `<small class="text-muted" style="margin-left:6px">(${label})</small>`;
                }
            }

            el.innerHTML = `
                <div class="contact-avatar-wrapper">
                    <img src="${avatar}" class="contact-avatar" />
                </div>
                <div class="contact-meta">
                    <div style="display:flex; align-items:center; justify-content:space-between">
                        <div class="name">${c.name} ${role}</div>
                        <div style="font-size:11px; color:#999">${c.last_at ? new Date(c.last_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : ''}</div>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px">
                        <div class="snippet">${lastMsg}</div>
                        <div>${unread}</div>
                    </div>
                </div>
            `;
            el.dataset.contactId = c.id;
            if (currentContactId && c.id === currentContactId) el.classList.add('active');
            el.addEventListener('click', function() { openChat(c.id, c.name, c.imageUrl); });
            container.appendChild(el);
        });
    }

    async function openChat(id, name, avatar) {
        currentContactId = id;
        const header = document.getElementById('chatHeader');
        if (header) header.style.display = 'flex';
        const chatInput = document.getElementById('chatInput');
        if (chatInput) chatInput.style.display = 'flex';

        document.getElementById('chatName').innerText = name;
        document.getElementById('chatSub').innerText = '';
        document.getElementById('chatAvatar').src = avatar ? ('/'+avatar) : '{{ asset('assets/img/xs/avatar1.jpg') }}';
        const ph = document.getElementById('messagesPlaceholder');
        if (ph) ph.style.display = 'none';
        const sendBtn = document.getElementById('sendBtn');
        if (sendBtn) sendBtn.disabled = false;
        await loadThread();
        fetchContacts();
        if (pollInterval) clearInterval(pollInterval);
        pollInterval = setInterval(() => { loadThread(true); }, 3000);
    }

    async function loadThread(silent = false) {
        const ph = document.getElementById('messagesPlaceholder');
        if (!currentContactId) {
            if (ph) ph.style.display = 'flex';
            const header = document.getElementById('chatHeader');
            if (header) header.style.display = 'none';
            const chatInput = document.getElementById('chatInput');
            if (chatInput) chatInput.style.display = 'none';
            const sendBtnHide = document.getElementById('sendBtn');
            if (sendBtnHide) sendBtnHide.disabled = true;
            return;
        }
        if (ph) ph.style.display = 'none';
        const res = await fetch(`{{ url('/messaging/thread') }}/${currentContactId}`, { credentials: 'same-origin' });
        const data = await res.json();
        if (!data.success) return;
        const container = document.getElementById('messages');
        container.innerHTML = '';
        data.messages.forEach(m => {
            const row = document.createElement('div');
            const me = m.sender_id === {{ auth()->id() ?? 'null' }};
            row.className = 'message-row ' + (me ? 'me' : '');
            const bubble = document.createElement('div');
            bubble.className = 'message-bubble ' + (me ? 'me' : '');
            const text = document.createElement('div');
            text.innerText = m.body;
            bubble.appendChild(text);

            if (me) {
                const tick = document.createElement('span');
                tick.className = 'tick' + (m.read_at ? ' read' : '');
                tick.innerText = (m.read_at ? '✓✓' : '✓');
                bubble.appendChild(tick);
            }

            row.appendChild(bubble);
            container.appendChild(row);
        });
        container.scrollTop = container.scrollHeight;
    }

    async function sendMessage() {
        const textarea = document.getElementById('messageText');
        const body = textarea.value.trim();
        if (!body || !currentContactId) return;
        const sendBtn = document.getElementById('sendBtn');
        sendBtn.disabled = true;
        try {
            const res = await fetch('{{ url('/messaging/send') }}', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ receiver_id: currentContactId, body })
            });
            const data = await res.json();
            if (data.success) {
                textarea.value = '';
                await loadThread();
                fetchContacts();
            } else {
                alert('Send failed');
            }
        } catch (e) {
            console.error(e);
            alert('Send error');
        } finally {
            sendBtn.disabled = false;
            textarea.focus();
        }
    }

    document.getElementById('sendBtn').addEventListener('click', sendMessage);
    document.getElementById('messageText').addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    fetchContacts();
    const phInit = document.getElementById('messagesPlaceholder');
    if (phInit) phInit.style.display = 'flex';
    const sendBtnInit = document.getElementById('sendBtn');
    if (sendBtnInit) sendBtnInit.disabled = true;
    const headerInit = document.getElementById('chatHeader');
    if (headerInit) headerInit.style.display = 'none';
    const chatInputInit = document.getElementById('chatInput');
    if (chatInputInit) chatInputInit.style.display = 'none';
</script>

@stop