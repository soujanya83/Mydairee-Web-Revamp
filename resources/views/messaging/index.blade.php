@extends('layout.master')
@section('title', 'Messaging')
@section('parentPageTitle', 'Messaging')
@section('content')

<style>
    /* Layout */
    .messaging-wrapper { display:flex; gap:16px; align-items:stretch; height:78vh; margin-top: 10px; }
    /* fixed-width contacts column so layout stays stable */
    .contacts-list { flex: 0 0 340px; width:340px; height:100%; overflow:auto; border-radius:8px; background:#fff; box-shadow:0 1px 2px rgba(0,0,0,0.04); }
    .chat-panel { flex:1 1 auto; display:flex; flex-direction:column; height:100%; border-radius:8px; overflow:hidden; background:#f6f6f6; min-width:420px; }

    /* Contacts list header & search */
    .contacts-list {  max-height: 100%; overflow:auto; border-radius:8px; background:#fff; box-shadow:0 1px 2px rgba(0,0,0,0.04); position:relative; }
    .contacts-list .contacts-header { padding:12px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #f0f0f0; background:#fff; position:sticky; top:0; z-index:5; }
    .contacts-list .contacts-search { padding:13px 12px; border-bottom:1px solid #f3f3f3; background:#fff; position:sticky; top:46px; z-index:4; }
    .contacts-list .contacts-search input { width:100%; padding:8px 10px; border-radius:20px; border:1px solid #e6e6e6; }

    /* Contact items */
    .contact-item { padding:12px; border-bottom:1px solid #f7f7f7; cursor:pointer; display:flex; gap:12px; align-items:center; transition:background .12s; }
    .contact-item:hover { background:#f2f6f9; }
    .contact-item.unread { background:#e8f7ee; }
    .contact-item.active { background:#eef7ff; border-left:4px solid #25d366; }
    .contact-avatar { width:52px; height:52px; border-radius:50%; object-fit:cover; }
    .contact-meta { flex:1; display:flex; flex-direction:column; }
    .contact-meta .name { font-weight:600; }
    .contact-meta .snippet { color:#7a7a7a; font-size:13px; margin-top:4px; }
    /* smaller badges (about half-size) shown inline after the name */
    .child-badge { background:#eef6ff; color:#2b6cb0; padding:2px 6px; border-radius:10px; font-size:10px; margin-left:6px; display:inline-block; vertical-align:middle; }
    .child-badge-more { margin-left:4px; cursor:pointer; }
    .role-badge { background:#ffd966; color:#6b4a00; padding:2px 6px; border-radius:10px; font-size:10px; margin-left:6px; display:inline-block; vertical-align:middle; }
    .room-badge { background:#ffd966; color:#6b4a00; padding:2px 6px; border-radius:10px; font-size:10px; margin-left:6px; display:inline-block; vertical-align:middle; }
    .badge-unread { background:#25d366; color:#fff; font-weight:700; padding:4px 8px; border-radius:12px; font-size:12px; }

    /* Chat header */
    #chatHeader { background:#ffffff; align-items:center; padding:12px 14px; border-bottom:1px solid #e9eef2; }
    #chatHeader .contact-avatar { width:44px; height:44px; }
    #chatHeader .chat-info { margin-left:8px; }
    #chatHeader .chat-info .name { font-weight:700; }
    #chatHeader .chat-info .status { font-size:12px; color:#6b6b6b; }
    #chatHeader .header-actions { margin-left:auto; display:flex; gap:8px; align-items:center; }

    /* Messages area */
    .messages { padding:18px; overflow:auto; flex:1; min-height:0; }
    .message-row { margin-bottom:12px; max-width:70%; display:flex; }
    .message-row.me { margin-left:auto; justify-content:flex-end; }
    .message-bubble { padding:10px 14px; border-radius:18px; background:#ffffff; color:#111; box-shadow:0 1px 0 rgba(0,0,0,0.04); display:inline-block; position:relative; }
    .message-bubble.me { background:#25d366; color:#fff; border-bottom-right-radius:4px; }
    .message-row .meta { font-size:11px; color:#777; margin-top:6px; display:flex; gap:6px; align-items:center; }
    .tick { font-size:12px; color:#b5b5b5; margin-left:6px; }
    .tick.read { color:#ffffff; }

    /* Date separator */
    .date-sep { text-align:center; color:#7b7b7b; font-size:12px; margin:16px 0; }

    /* Chat input (fixed to bottom of panel) */
    #chatInput {  border-top:1px solid #e8e8e8; display:flex; gap:8px; align-items:center; margin-top:10px;}
    #chatInput textarea { flex:1; resize:none; padding:10px 12px; border-radius:20px; border:1px solid #e6e6e6; }
    #chatInput .icon { width:36px; height:36px; display:inline-flex; align-items:center; justify-content:center; border-radius:50%; cursor:pointer; color:#4a4a4a; }

    #messagesPlaceholder { color:#201e1e; font-size:28px; font-weight:700; }
    .card {
        margin-bottom: -10px !important;
    }
    .view-tab-active { background:#25d366 !important; color:#fff !important; border-color:#25d366 !important; }
</style>
<div class="text-zero top-right-button-container d-flex justify-content-end"
            style="margin-right: 20px;margin-top: -45px;">
        <div class="dropdown mr-2">
            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="centerDropdown"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fab fa-centercode mr-2"></i>
                {{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? 'Select Center' }}
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown">
                @foreach ($centers as $center)
                    <a href="javascript:void(0);" card
                        class="dropdown-item center-option {{ session('user_center_id') == $center->id ? 'act ive font-weight-bold text-primary' : '' }}"
                        data-id="{{ $center->id }}">
                        {{ $center->centerName }}
                    </a>
                @endforeach
            </div>
        </div>
</div>
<div class="messaging-wrapper container-fluid">
    <div class="contacts-list">
        <div class="contacts-header">
            <div style="font-weight:700">Contacts</div>
            <div style="margin-left:auto; display:flex; gap:8px; align-items:center">
                <button id="openGroupBtn" class="btn btn-sm btn-outline-success">Group</button>
                <button id="showContactsBtn" class="btn btn-sm btn-outline-secondary" style="color:#555555;">Messages</button>
            </div>
        </div>
        <div class="contacts-search">
            <input id="contactsSearch" type="text" placeholder="Search or start new chat" />
        </div>
        <div id="contacts"></div>
    </div>

    <div class="chat-panel">
        <div id="chatHeader" style="display:none; align-items:center; gap:12px;">
            <img id="chatAvatar" src="{{ asset('assets/img/xs/avatar1.jpg') }}" class="contact-avatar" />
            <div class="chat-info">
                <div id="chatName" class="name"></div>
                <div id="chatSub" class="status small text-muted">&nbsp;</div>
            </div>
            <div class="header-actions">
                <div class="icon" title="Search">🔍</div>
                <div class="icon" title="More">⋮</div>
            </div>
        </div>

            <div class="card messages" id="messages">
                    <div id="messagesPlaceholder" class="text-center" style="margin-top:27%;">Select a contact</div>
            </div>

        <div id="chatInput" class="chat-input" style="display:none">
            <textarea id="messageText" rows="2" class="form-control" placeholder="Type a message..."></textarea>
            <button id="sendBtn" class="btn btn-primary">Send</button>
        </div>
    </div>
</div>

<script>
    const _csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = _csrfMeta ? _csrfMeta.getAttribute('content') : '';
    let pollInterval = null;
    const authId = {{ auth()->id() ?? 'null' }};
    let currentContactId = null;

    let contactsData = [];

    async function fetchContacts() {
        const res = await fetch('{{ url('/messaging/contacts') }}', { credentials: 'same-origin' });
        const data = await res.json();
        if (!data.success) return;
        contactsData = data.contacts || [];
        
        renderContacts(document.getElementById('contactsSearch') ? document.getElementById('contactsSearch').value : '');
    }

    function formatContactName(c) {
        if (!c) return '';
        const n = c.name;
        if (!n) return '';
        if (typeof n === 'string') return n;
        if (typeof n === 'object') {
            // try common properties
            if (n.full) return n.full;
            if (n.name) return typeof n.name === 'string' ? n.name : (Array.isArray(n.name) ? n.name.join(' ') : JSON.stringify(n.name));
            if (n.first || n.firstname || n.firstName) return [n.first || n.firstname || n.firstName, n.last || n.lastname || n.lastName].filter(Boolean).join(' ');
            // fallback: join object values
            try { return Object.values(n).filter(Boolean).join(' '); } catch (e) { return String(n); }
        }
        return String(n);
    }

    function renderContacts(filter = '') {
        const container = document.getElementById('contacts');
        container.innerHTML = '';
        const q = String(filter || '').trim().toLowerCase();
        contactsData.forEach(c => {
            const childrenText = (c.children || []).join(' ').toLowerCase();
            const roomsText = (c.rooms || []).join(' ').toLowerCase();
            const matchText = (String(c.name || '') + ' ' + childrenText + ' ' + roomsText).toLowerCase();
            if (q && matchText.indexOf(q) === -1) return;

            const el = document.createElement('div');
            el.className = 'contact-item' + (c.unread_count && c.unread_count > 0 ? ' unread' : '');
            const avatar = c.imageUrl ? ('/'+c.imageUrl) : '{{ asset('assets/img/xs/avatar1.jpg') }}';
            const lastMsg = c.last_message ? c.last_message.slice(0,60) : '';
            const unread = c.unread_count && c.unread_count > 0 ? `<span class="badge-unread">${c.unread_count}</span>` : '';

            let roleBadge = '';
            if (c.userType) {
                const raw = String(c.userType).trim().toLowerCase();
                if (raw === 'admin' || raw === 'superadmin' || raw === 'manager') {
                    const label = String(c.userType).trim();
                    roleBadge = `<span class="role-badge">${label}</span>`;
                }
            }

            let childBadges = '';
            if (c.children && c.children.length) {
                if (c.children.length === 1) {
                    childBadges = `<span class="child-badge">${c.children[0]}</span>`;
                } else {
                    const first = c.children[0];
                    const rest = c.children.length - 1;
                    // +N is clickable and stores the full children list in a data attribute
                    const kidsJson = JSON.stringify(c.children);
                    childBadges = `<span class="child-badge">${first}</span> <span class="child-badge child-badge-more" data-children='${kidsJson}' onclick="showChildList(event)">+${rest}</span>`;
                }
            }

            let roomBadges = '';
            if (c.rooms && c.rooms.length) {
                roomBadges = c.rooms.map(r => `<span class="room-badge">${r}</span>`).join(' ');
            }

            const displayName = formatContactName(c);
            el.innerHTML = `
                <img src="${avatar}" class="contact-avatar" />
                <div class="contact-meta">
                    <div style="display:flex; align-items:center; justify-content:space-between">
                        <div style="display:flex; align-items:center; gap:8px"><div class="name">${displayName}</div> ${roleBadge} ${childBadges} ${roomBadges}</div>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px; margin-top:6px">
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

        // close any open child list when clicking elsewhere
        document.addEventListener('click', function _closeChildLists(e) {
            const popup = document.getElementById('childListPopup');
            if (!popup) return;
            const target = e.target;
            if (!popup.contains(target) && !target.classList.contains('child-badge-more')) {
                popup.remove();
            }
        });
    }

    function showChildList(e) {
        e.stopPropagation();
        const el = e.currentTarget;
        const raw = el.getAttribute('data-children');
        let list = [];
        try { list = JSON.parse(raw || '[]'); } catch (err) { list = []; }

        // remove existing popup
        const existing = document.getElementById('childListPopup');
        if (existing) existing.remove();

        // build popup
        const popup = document.createElement('div');
        popup.id = 'childListPopup';
        popup.style.position = 'absolute';
        popup.style.background = '#fff';
        popup.style.border = '1px solid #e6e6e6';
        popup.style.boxShadow = '0 4px 12px rgba(0,0,0,0.08)';
        popup.style.padding = '8px 10px';
        popup.style.borderRadius = '6px';
        popup.style.zIndex = 9999;
        popup.style.minWidth = '140px';

        const ul = document.createElement('div');
        ul.style.display = 'flex';
        ul.style.flexDirection = 'column';
        ul.style.gap = '4px';
        list.forEach(name => {
            const item = document.createElement('div');
            item.style.fontSize = '13px';
            item.style.color = '#222';
            item.innerText = name;
            ul.appendChild(item);
        });
        popup.appendChild(ul);

        document.body.appendChild(popup);

        // position near clicked element
        const rect = el.getBoundingClientRect();
        const top = rect.bottom + window.scrollY + 6;
        const left = rect.left + window.scrollX;
        popup.style.top = top + 'px';
        popup.style.left = left + 'px';
    }

    async function openChat(id, name, avatar) {
        groupMode = false;
        currentContactId = id;
        // show header and input area when a contact is opened
        const header = document.getElementById('chatHeader');
        if (header) header.style.display = 'flex';
        const chatInput = document.getElementById('chatInput');
        if (chatInput) chatInput.style.display = 'flex';

        document.getElementById('chatName').innerText = name;
        // clear the subheader when a contact is opened
        document.getElementById('chatSub').innerText = '';
        document.getElementById('chatAvatar').src = avatar ? ('/'+avatar) : '{{ asset('assets/img/xs/avatar1.jpg') }}';
        // hide placeholder and enable sending
        const ph = document.getElementById('messagesPlaceholder');
        if (ph) ph.style.display = 'none';
        const sendBtn = document.getElementById('sendBtn');
        if (sendBtn) sendBtn.disabled = false;
        await loadThread();
        // refresh contacts to update unread counts and move this contact to top
        fetchContacts();
        setActiveView('messages');
        startPolling();
    }

    // --- Group support ---
    let groupMode = false;
    function showContactsList() {
        groupMode = false;
        document.getElementById('chatName').innerText = '';
        document.getElementById('chatSub').innerText = '';
        currentContactId = null;
        document.getElementById('contacts').style.display = '';
        // clear any existing messages (for example leftover group messages)
        const messagesContainer = document.getElementById('messages');
        if (messagesContainer) {
            messagesContainer.innerHTML = '<div id="messagesPlaceholder" class="text-center" style="margin-top:27%;">Select a contact</div>';
        }
        const ph = document.getElementById('messagesPlaceholder');
        if (ph) ph.style.display = 'block';
        const header = document.getElementById('chatHeader');
        if (header) header.style.display = 'none';
        const chatInput = document.getElementById('chatInput');
        if (chatInput) chatInput.style.display = 'none';
        // disable send button until a contact is selected
        const sendBtn = document.getElementById('sendBtn');
        if (sendBtn) sendBtn.disabled = true;
        // ensure the Messages tab is visually active
        try { setActiveView('messages'); } catch (e) { }
        // start polling so contacts refresh every 3s (and threads if opened)
        startPolling();
    }

    document.getElementById('showContactsBtn').addEventListener('click', function () {
        showContactsList();
    });

    document.getElementById('openGroupBtn').addEventListener('click', async function () {
        const centerName = '{{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? '' }}';
        groupMode = true;
        document.getElementById('contacts').style.display = 'none';
        const header = document.getElementById('chatHeader');
        if (header) header.style.display = 'flex';
        document.getElementById('chatName').innerText = (centerName ? centerName + ' — Group' : 'Center Group');
        document.getElementById('chatSub').innerText = 'Center-wide group chat';
        const chatInput = document.getElementById('chatInput');
        if (chatInput) chatInput.style.display = 'flex';
        const ph = document.getElementById('messagesPlaceholder');
        if (ph) ph.style.display = 'none';
        await fetchGroupThread();
        setActiveView('group');
        startPolling();
    });

    async function fetchGroupThread(silent = false) {
        const res = await fetch('{{ url('/messaging/group-thread') }}', { credentials: 'same-origin' });
        const data = await res.json();
        if (!data.success) return;
        const container = document.getElementById('messages');
        container.innerHTML = '';
        data.messages.forEach(m => {
            const row = document.createElement('div');
            const me = m.sender_id === {{ auth()->id() ?? 'null' }};
            row.className = 'message-row ' + (me ? 'me' : '');
            // optional sender header (name + role badge)
            if (!me) {
                const senderHeader = document.createElement('div');
                senderHeader.style.fontSize = '13px';
                senderHeader.style.fontWeight = '700';
                senderHeader.style.marginBottom = '6px';
                const senderName = m.sender_name || 'Unknown';
                let headerHtml = senderName;
                if (m.sender_userType) headerHtml += ' <span class="role-badge" style="font-size:10px; vertical-align:middle; margin-left:6px;">'+m.sender_userType+'</span>';
                senderHeader.innerHTML = headerHtml;
                row.appendChild(senderHeader);
            }

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

    function setActiveView(view) {
        const groupBtn = document.getElementById('openGroupBtn');
        const msgBtn = document.getElementById('showContactsBtn');
        if (groupBtn) groupBtn.classList.remove('view-tab-active');
        if (msgBtn) msgBtn.classList.remove('view-tab-active');
        if (view === 'group') {
            if (groupBtn) groupBtn.classList.add('view-tab-active');
        } else {
            if (msgBtn) msgBtn.classList.add('view-tab-active');
        }
    }

    // Centralized polling: refresh contacts every 3s and refresh the
    // currently-open thread (group or 1:1) as appropriate.
    function startPolling() {
        // clear any existing interval
        try { if (pollInterval) clearInterval(pollInterval); } catch (e) { }

        // fetch once immediately
        fetchContacts();

        pollInterval = setInterval(async () => {
            try {
                await fetchContacts();
                if (groupMode) {
                    // refresh group thread silently
                    await fetchGroupThread(true);
                } else if (currentContactId) {
                    // refresh current 1:1 thread silently
                    await loadThread(true);
                }
            } catch (err) {
                console.error('Messaging poll error', err);
            }
        }, 3000);
    }

    async function loadThread(silent = false) {
        const ph = document.getElementById('messagesPlaceholder');
        if (!currentContactId) {
            if (ph) ph.style.display = 'block';
            // hide header and input when no contact selected
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
            // message text
            const text = document.createElement('div');
            text.innerText = m.body;
            bubble.appendChild(text);

            // show single tick for sent messages, double tick when read_at is set
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
        if (!body) return;
        const sendBtn = document.getElementById('sendBtn');
        sendBtn.disabled = true;
        try {
            if (groupMode) {
                const res = await fetch('{{ url('/messaging/broadcast-center') }}', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ body })
                });
                const data = await res.json();
                if (data.success) {
                    textarea.value = '';
                    await fetchGroupThread();
                } else {
                    alert('Send failed');
                }
            } else {
                if (!currentContactId) { sendBtn.disabled = false; return; }
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
                    alert(data.message || 'Send failed');
                }
            }
        } catch (e) {
            console.error(e);
            alert('Send error');
        } finally {
            sendBtn.disabled = false;
            textarea.focus();
        }
    }

    // click sends
    document.getElementById('sendBtn').addEventListener('click', sendMessage);
    // send on Enter (without Shift) — Shift+Enter inserts newline
    document.getElementById('messageText').addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // initial: start polling (also performs an immediate fetch)
    startPolling();
    // handle center switch clicks: update session center on server, then reset messaging UI
    document.querySelectorAll('.center-option').forEach(el => {
        el.addEventListener('click', async function (e) {
            e.preventDefault();
            const centerId = this.getAttribute('data-id');
            const centerName = this.textContent.trim();
            try {
                const res = await fetch('{{ url('/change-center') }}', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ center_id: centerId })
                });
                const data = await res.json();
                if (data && (data.status === 'success' || data.success === true)) {
                    // update dropdown label
                    const btn = document.getElementById('centerDropdown');
                    if (btn) btn.innerHTML = '<i class="fab fa-centercode mr-2"></i> ' + centerName;
                    // reset UI: show contacts placeholder, clear messages and stop group mode
                    groupMode = false;
                    showContactsList();
                    // fetch new contacts for selected center
                    await fetchContacts();
                } else {
                    console.error('change-center failed', data);
                    alert('Unable to switch center');
                }
            } catch (err) {
                console.error('change-center error', err);
                alert('Unable to switch center');
            }
        });
    });
    // wire up search to filter by parent name and children names
    const contactsSearch = document.getElementById('contactsSearch');
    if (contactsSearch) {
        contactsSearch.addEventListener('input', function (e) {
            renderContacts(e.target.value || '');
        });
    }
    // ensure placeholder visible and send disabled until a contact is selected
    const phInit = document.getElementById('messagesPlaceholder');
    if (phInit) phInit.style.display = 'block';
    const sendBtnInit = document.getElementById('sendBtn');
    if (sendBtnInit) sendBtnInit.disabled = true;
    // ensure header and input are hidden initially
    const headerInit = document.getElementById('chatHeader');
    if (headerInit) headerInit.style.display = 'none';
    const chatInputInit = document.getElementById('chatInput');
    if (chatInputInit) chatInputInit.style.display = 'none';
    // highlight messages tab by default on initial load
    try { setActiveView('messages'); } catch (e) { }
</script>

@stop
