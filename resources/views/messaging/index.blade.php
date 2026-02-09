@extends('layout.master')
@section('title', 'Messaging')
@section('parentPageTitle', "Chat's")
@section('content')

@php
    $permissions = app('userPermissions');
    $user = auth()->user();
    $isParent = strtolower($user->userType ?? '') === 'parent';
    $isAdminLike = in_array($user->userType ?? '', ['Admin', 'Superadmin', 'Manager']);
    $canSendGroup =   $isParent || $isAdminLike || (!empty($permissions['sendGroupMessage']) && $permissions['sendGroupMessage']);
    $canSendDirect = $isAdminLike || (!empty($permissions['sendMessage']) && $permissions['sendMessage']);
@endphp

<style>
    :root {
        --sd-bg: #f3f4f6;
        --sd-surface: #ffffff;
        --sd-border: #e5e7eb;
        --sd-text: #111827;
        --sd-muted: #6b7280;
        --sd-radius-lg: 14px;
        --sd-radius-md: 10px;
        --sd-shadow-soft: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    .theme-purple {
        --sd-accent: #a27ce6;
        --sd-accent-soft: #f3e8ff;
    }

    .theme-blue {
        --sd-accent: #3eacff;
        --sd-accent-soft: #dbeafe;
    }

    .theme-cyan {
        --sd-accent: #49c5b6;
        --sd-accent-soft: #ccfbf1;
    }

    .theme-green {
        --sd-accent: #50d38a;
        --sd-accent-soft: #d1fae5;
    }

    .theme-orange {
        --sd-accent: #ffce4b;
        --sd-accent-soft: #fef3c7;
    }

    .theme-blush {
        --sd-accent: #e47297;
        --sd-accent-soft: #fce7f3;
    }

    .messaging-wrapper {
        background: var(--sd-bg);
    }

    /* Layout */
    .messaging-wrapper { display:flex; flex-direction:column; gap:12px; height:78vh; margin-top: 10px; }
    .messaging-body { display:flex;  align-items:stretch; flex:1 1 auto; min-height:0; }
    /* contacts column: auto-fit for name/badges, cap width, clamp minimum */
    .contacts-list { flex: 0 0 auto; width:fit-content; min-width:200px; max-width:340px; height:100%; overflow-y:auto; overflow-x:hidden; border-radius:var(--sd-radius-md); background:var(--sd-surface); box-shadow:var(--sd-shadow-soft); position:relative; display:flex; flex-direction:column; max-height:100%; }
    .contacts-list .contacts-search { padding:13px 12px; border-bottom:1px solid var(--sd-border); background:var(--sd-surface); position:sticky; top:0; z-index:4; margin-top:0; }
    .chat-panel { flex:1 1 auto; display:flex; flex-direction:column; height:100%; border-radius:var(--sd-radius-md); overflow:hidden; background:var(--sd-bg); min-width:420px; }
    .contacts-list .contacts-search input { width:100%; padding:8px 10px; border-radius:20px; border:1px solid var(--sd-border); background:var(--sd-surface); color:var(--sd-text); }

    /* Contact items */
    .contact-item { padding:12px; border-bottom:1px solid var(--sd-border); cursor:pointer; display:flex; gap:12px; align-items:center; transition:background .12s; background:var(--sd-surface); }
    .contact-item:hover { background:var(--sd-accent-soft, #d4edda); }
    .contact-item.unread { background:var(--sd-accent-soft, #d4edda); }
    .contact-item.active { background:var(--sd-accent-soft, #d4edda); border-left:4px solid var(--sd-accent, #25d366); }
    .contact-avatar { width:52px; height:52px; border-radius:50%; object-fit:cover; }
    .contact-meta { flex:1; display:flex; flex-direction:column; min-width:0; }
    .contact-meta .name { font-weight:600; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width: 200px; }
    .badges-line { display:flex; align-items:center; gap:8px; min-width:0; }
    .badges { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:0; }
    .contact-meta .snippet { color:var(--sd-muted); font-size:13px; margin-top:4px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; flex:1; min-width:0; }
    /* smaller badges (about half-size) shown inline after the name */
    /* Defaults without theme */
    .child-badge { background:#fff3cd; color:#856404; padding:2px 6px; border-radius:10px; font-size:10px; font-weight:700; margin-left:6px; display:inline-block; vertical-align:middle; border:1px solid #ffeaa7; }
    .child-badge-more { margin-left:4px; cursor:pointer; }
    .role-badge { background:#e9ecef; color:#6c757d; padding:2px 6px; border-radius:10px; font-size:10px; margin-left:6px; display:inline-block; vertical-align:middle; border:1px solid #cfd3d7; }
    .room-badge { background:#cfe2ff; color:#084298; padding:2px 6px; border-radius:10px; font-size:10px; font-weight:700; margin-left:6px; display:inline-block; vertical-align:middle; border:1px solid #b6d4fe; }
    .superadmin-badge { background:#e9ecef; color:#6c757d; padding:2px 6px; border-radius:10px; font-size:10px; margin-left:6px; display:inline-block; vertical-align:middle; border:1px solid #cfd3d7; }
    .assoc-count-badge { background:#e9ecef; color:#6c757d; padding:1px 6px; border-radius:10px; font-size:10px; font-weight:700; display:inline-block; vertical-align:middle; flex-shrink:0; border:1px solid #cfd3d7; }
    .badge-unread { background:#dc3545; color:#fff; font-weight:700; padding:4px 8px; border-radius:12px; font-size:12px; }

    /* Chat header */
    #chatHeader { background:var(--sd-surface); align-items:center; padding:12px 14px; border-bottom:1px solid var(--sd-border); }
    #chatHeader .contact-avatar { width:44px; height:44px; }
    #chatHeader .chat-info { margin-left:8px; }
    #chatHeader .chat-info .name { font-weight:700; }
    #chatHeader .chat-info .status { font-size:12px; color:var(--sd-muted); }
    #chatHeader .header-actions { margin-left:auto; display:flex; gap:8px; align-items:center; flex-wrap:wrap; justify-content:flex-end; }

    /* Messages area */
    .messages { padding:18px; overflow:auto; flex:1; min-height:0; }
    .message-row { margin-bottom:12px; display:flex; flex-direction:column; align-items:flex-start; max-width:fit-content; }
    .message-row.me { margin-left:auto; justify-content:flex-end; align-items:flex-end; }
    .message-bubble { padding:10px 14px; border-radius:18px; background:var(--sd-accent-soft, #d4edda); color:var(--sd-text); border:1px solid var(--sd-accent, #c3e6cb); box-shadow:0 1px 0 rgba(0,0,0,0.04); display:inline-block; position:relative; max-width:100%; word-wrap:break-word; }
    .message-bubble.me { background:var(--sd-accent, #25d366); color:#fff; border-bottom-right-radius:4px; border:1px solid var(--sd-accent, #25d366); }
    .message-sender-header { padding:2px 0; background:transparent; color:var(--sd-text); font-size:13px; font-weight:700; margin-bottom:2px; }
    .tick { font-size:12px; color:#b5b5b5; margin-left:6px; }
    .tick.read { color:#ffffff; }

    /* Chat input (fixed to bottom of panel) */
    #chatInput {  border-top:1px solid var(--sd-border); display:flex; gap:8px; align-items:center; margin-top:10px; background:var(--sd-surface); padding:10px; }
    #chatInput textarea { flex:1; resize:none; padding:10px 12px; border-radius:20px; border:1px solid var(--sd-border); background:var(--sd-surface); color:var(--sd-text); }
    #chatInput .icon { width:36px; height:36px; display:inline-flex; align-items:center; justify-content:center; border-radius:50%; cursor:pointer; color:var(--sd-muted); }

    #chatInput .btn-primary {
        background:var(--sd-accent, #0dcaf0);
        border-color:var(--sd-accent, #0dcaf0);
        color:#fff;
    }
    #chatInput .btn-primary:hover {
        background:var(--sd-accent);
        filter:brightness(0.92);
    }

    #messagesPlaceholder { color:var(--sd-muted); font-size:28px; font-weight:700; }
    .card {
        margin-bottom: -10px !important;
    }
    .btn-outline-success {
        color:var(--sd-accent, #28a745);
        border-color:var(--sd-accent, #28a745);
    }
    .btn-outline-success:hover,
    .btn-outline-success.view-tab-active {
        background:var(--sd-accent, #28a745) !important;
        color:#fff !important;
        border-color:var(--sd-accent, #28a745) !important;
    }

    .view-tab-active { background:var(--sd-accent, #28a745) !important; color:#fff !important; border-color:var(--sd-accent, #28a745) !important; }

    .dropdown .btn-outline-primary {
        color:var(--sd-accent, #0d6efd);
        border-color:var(--sd-accent, #0d6efd);
    }
    .dropdown .btn-outline-primary:hover,
    .dropdown .btn-outline-primary:focus {
        background:var(--sd-accent, #0d6efd);
        color:#fff;
    }
</style>
<div class="text-zero top-right-button-container d-flex justify-content-end"
            style="margin-right: 20px;margin-top: -45px;">
            
            <div class="d-flex align-items-center" id="messageToggleBar" style="gap:8px;">
                
                <div style="display:flex; ">
                    @if($canSendGroup)
                    <button id="openGroupBtn" class="btn btn-sm btn-outline-success mr-2">Group</button>
                    @endif
                    <button id="showContactsBtn" class="btn btn-sm btn-outline-success mr-2">Messages</button>
                </div>
            </div>
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
    
    <div class="messaging-body">
        <div class="contacts-list">
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
            <div class="header-actions" id="chatHeaderActions"></div>
        </div>

        <div class="card messages" id="messages">
                    <div id="messagesPlaceholder" class="text-center" style="margin-top:27%;">Select a contact</div>
    </div>
        
        @if(!$isParent)
        <div id="chatInput" class="chat-input" style="display:none">
            <textarea id="messageText" rows="2" class="form-control" placeholder="Type a message..."></textarea>
            <button id="sendBtn" class="btn btn-primary">Send</button>
        </div>
        @endif
    
        </div>
    </div>
</div>

<script>
    const _csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = _csrfMeta ? _csrfMeta.getAttribute('content') : '';
    let pollInterval = null;
    let currentContactId = null;
    let childListCloseBound = false;
    const canSendGroup = {{ $canSendGroup ? 'true' : 'false' }};
    const canSendDirect = {{ $canSendDirect ? 'true' : 'false' }};

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
            if (n.full) return n.full;
            if (n.name) return typeof n.name === 'string' ? n.name : (Array.isArray(n.name) ? n.name.join(' ') : JSON.stringify(n.name));
            if (n.first || n.firstname || n.firstName) return [n.first || n.firstname || n.firstName, n.last || n.lastname || n.lastName].filter(Boolean).join(' ');
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

            const displayName = formatContactName(c);

            const userTypeRaw = String(c.userType || '').trim().toLowerCase();
            const childrenArr = Array.isArray(c.children) ? c.children : [];
            const roomsArr = Array.isArray(c.rooms) ? c.rooms : [];
            const assocList = userTypeRaw === 'parent' ? childrenArr : (roomsArr.length > 0 ? roomsArr : childrenArr);

            let roleBadge = '';
            if (userTypeRaw === 'admin' || userTypeRaw === 'superadmin' || userTypeRaw === 'manager') {
                const label = String(c.userType).trim();
                roleBadge = `<span class="role-badge">${label}</span>`;
            }

            let assocBadge = '';
            if (assocList.length > 0) {
                const firstAssoc = assocList[0];
                const extraCount = assocList.length - 1;
                const kidsJson = JSON.stringify(childrenArr);
                const roomsJson = JSON.stringify(roomsArr);
                let badgeClass = 'room-badge';
                if (userTypeRaw === 'parent') badgeClass = 'child-badge';
                else if (userTypeRaw === 'superadmin' || userTypeRaw === 'admin' || userTypeRaw === 'manager') badgeClass = 'superadmin-badge';
                const assocBase = `<span class="${badgeClass}" data-children='${kidsJson}' data-rooms='${roomsJson}' onclick="showAssociations(event)" tabindex="0">${firstAssoc}</span>`;
                const assocCount = extraCount > 0 ? ` <span class="assoc-count-badge" data-children='${kidsJson}' data-rooms='${roomsJson}' onclick="showAssociations(event)" tabindex="0">+${extraCount}</span>` : '';
                assocBadge = `${assocBase}${assocCount}`;
            }

            el.innerHTML = `
                <img src="${avatar}" class="contact-avatar" />
                <div class="contact-meta">
                    <div style="display:flex; align-items:center; justify-content:space-between">
                        <div style="display:flex; align-items:center; gap:8px"><div class="name">${displayName}</div></div>
                        <div class="badges">${roleBadge}</div>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px; margin-top:6px">
                        <div style="display:flex; align-items:center; gap:8px; flex:1; min-width:0; overflow:hidden">${assocBadge}<div class="snippet">${lastMsg}</div></div>
                        <div>${unread}</div>
                    </div>
                    
                </div>
            `;
            el.dataset.contactId = c.id;
            if (currentContactId && c.id === currentContactId) el.classList.add('active');
            el.addEventListener('click', function() { openChat(c.id, c.name, c.imageUrl); });
            el.addEventListener('keydown', function(ev) { if (ev.key === 'Enter' || ev.key === ' ') { ev.preventDefault(); openChat(c.id, c.name, c.imageUrl); } });
            container.appendChild(el);
        });

        if (!childListCloseBound) {
            document.addEventListener('click', function _closeAssocPopup(e) {
                const popup = document.getElementById('assocListPopup');
                if (!popup) return;
                const target = e.target;
                if (!popup.contains(target) && !target.classList.contains('child-badge')) {
                    popup.remove();
                }
            });
            childListCloseBound = true;
        }
    }

    function showAssociations(e) {
        e.stopPropagation();
        const el = e.currentTarget;
        let children = [];
        let rooms = [];
        try { children = JSON.parse(el.getAttribute('data-children') || '[]'); } catch (err) { children = []; }
        try { rooms = JSON.parse(el.getAttribute('data-rooms') || '[]'); } catch (err) { rooms = []; }

        const existing = document.getElementById('assocListPopup');
        if (existing) existing.remove();

        const popup = document.createElement('div');
        popup.id = 'assocListPopup';
        popup.style.position = 'absolute';
        popup.style.background = '#fff';
        popup.style.border = '1px solid #e6e6e6';
        popup.style.boxShadow = '0 4px 12px rgba(0,0,0,0.08)';
        popup.style.padding = '8px 10px';
        popup.style.borderRadius = '6px';
        popup.style.zIndex = 9999;
        popup.style.minWidth = '160px';

        const content = document.createElement('div');
        content.style.display = 'flex';
        content.style.flexDirection = 'column';
        content.style.gap = '6px';

        const addSection = (title, items) => {
            if (!items || !items.length) return;
            const header = document.createElement('div');
            header.style.fontSize = '11px';
            header.style.color = '#6b6b6b';
            header.style.textTransform = 'uppercase';
            header.style.marginTop = content.children.length ? '6px' : '0';
            header.innerText = title;
            content.appendChild(header);

            items.forEach(name => {
                const item = document.createElement('div');
                item.style.fontSize = '13px';
                item.style.color = '#222';
                item.innerText = name;
                content.appendChild(item);
            });
        };

        addSection('Children', children);
        addSection('Rooms', rooms);

        if (!children.length && !rooms.length) {
            const empty = document.createElement('div');
            empty.style.fontSize = '12px';
            empty.style.color = '#777';
            empty.innerText = 'No associations';
            content.appendChild(empty);
        }

        popup.appendChild(content);
        document.body.appendChild(popup);

        const rect = el.getBoundingClientRect();
        const top = rect.bottom + window.scrollY + 6;
        const left = rect.left + window.scrollX;
        popup.style.top = top + 'px';
        popup.style.left = left + 'px';
    }

    async function openChat(id, name, avatar) {
        groupMode = false;
        currentContactId = id;
        const header = document.getElementById('chatHeader');
        if (header) header.style.display = 'flex';
        const chatInput = document.getElementById('chatInput');
        if (chatInput) chatInput.style.display = canSendDirect ? 'flex' : 'none';

        document.getElementById('chatName').innerText = name;
        document.getElementById('chatSub').innerText = '';
        document.getElementById('chatAvatar').src = avatar ? ('/'+avatar) : '{{ asset('assets/img/xs/avatar1.jpg') }}';

       
        try {
            const contact = (contactsData || []).find(ct => ct.id === id);
            const actions = document.getElementById('chatHeaderActions');
            if (actions) {
                if (contact) {
                    const userTypeRaw = String(contact.userType || '').trim().toLowerCase();
                    const childrenArr = Array.isArray(contact.children) ? contact.children : [];
                    const roomsArr = Array.isArray(contact.rooms) ? contact.rooms : [];
                    const assocList = userTypeRaw === 'parent' ? childrenArr : (roomsArr.length > 0 ? roomsArr : childrenArr);
                    let badgeClass = 'room-badge';
                    if (userTypeRaw === 'parent') badgeClass = 'child-badge';
                    else if (userTypeRaw === 'superadmin' || userTypeRaw === 'admin' || userTypeRaw === 'manager') badgeClass = 'superadmin-badge';
                    const badges = assocList.map(n => `<span class="${badgeClass}" style="margin-left:0;">${n}</span>`).join(' ');
                    actions.innerHTML = badges || '';
                } else {
                    actions.innerHTML = '';
                }
            }
        } catch (e) { /* ignore */ }

        const ph = document.getElementById('messagesPlaceholder');
        if (ph) ph.style.display = 'none';
        const sendBtn = document.getElementById('sendBtn');
        if (sendBtn) sendBtn.disabled = !canSendDirect;
        await loadThread();
        fetchContacts();
        setActiveView('messages');
        startPolling();
    }

    let groupMode = false;
    function showContactsList() {
        groupMode = false;
        currentContactId = null;


        const contactsEl = document.getElementById('contacts');
        if (contactsEl) contactsEl.style.display = '';
        const contactsList = document.querySelector('.contacts-list');
        if (contactsList) contactsList.style.display = '';
        const contactsSearch = document.querySelector('.contacts-search');
        if (contactsSearch) contactsSearch.style.display = '';

        // restore chat panel sizing
        const chatPanel = document.querySelector('.chat-panel');
        if (chatPanel) {
            chatPanel.style.flex = '';
            chatPanel.style.maxWidth = '';
        }

        // reset header and avatar
        const header = document.getElementById('chatHeader');
        if (header) header.style.display = 'none';
        document.getElementById('chatName').innerText = '';
        document.getElementById('chatSub').innerText = '';
        const avatar = document.getElementById('chatAvatar');
        if (avatar) avatar.src = '{{ asset('assets/img/xs/avatar1.jpg') }}';

        // clear messages and show placeholder
        const messagesContainer = document.getElementById('messages');
        if (messagesContainer) {
            messagesContainer.innerHTML = '<div id="messagesPlaceholder" class="text-center" style="margin-top:27%;">Select a contact</div>';
        }
        const ph = document.getElementById('messagesPlaceholder');
        if (ph) ph.style.display = 'block';

        const chatInput = document.getElementById('chatInput');
        if (chatInput) chatInput.style.display = 'none';
        const textarea = document.getElementById('messageText');
        if (textarea) textarea.value = '';
        const sendBtn = document.getElementById('sendBtn');
        if (sendBtn) sendBtn.disabled = true;

        try { setActiveView('messages'); } catch (e) { }
        startPolling();
    }

    document.getElementById('showContactsBtn').addEventListener('click', function () {
        showContactsList();
    });

    const groupBtnEl = document.getElementById('openGroupBtn');
    if (groupBtnEl) {
    groupBtnEl.addEventListener('click', async function () {
        const centerName = '{{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? '' }}';
        groupMode = true;
        document.getElementById('contacts').style.display = 'none';
        const contactsList = document.querySelector('.contacts-list');
        if (contactsList) contactsList.style.display = 'none';
        const chatPanel = document.querySelector('.chat-panel');
        if (chatPanel) {
            chatPanel.style.flex = '1 1 100%';
            chatPanel.style.maxWidth = '100%';
        }
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
    }

    async function fetchGroupThread() {
        const res = await fetch('{{ url('/messaging/group-thread') }}', { credentials: 'same-origin' });
        const data = await res.json();
        if (!data.success) return;
        const container = document.getElementById('messages');
        container.innerHTML = '';
        data.messages.forEach(m => {
            const row = document.createElement('div');
            const me = m.sender_id === {{ auth()->id() ?? 'null' }};
            row.className = 'message-row ' + (me ? 'me' : '');
            
   
            if (!me) {
                const senderHeader = document.createElement('div');
                senderHeader.className = 'message-sender-header';
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

    function startPolling() {
        try { if (pollInterval) clearInterval(pollInterval); } catch (e) { }

        fetchContacts();

        pollInterval = setInterval(async () => {
            try {
                await fetchContacts();
                if (groupMode) {
                    await fetchGroupThread();
                } else if (currentContactId) {
                    await loadThread();
                }
            } catch (err) {
                console.error('Messaging poll error', err);
            }
        }, 3000);
    }

    async function loadThread() {
        const ph = document.getElementById('messagesPlaceholder');
        if (!currentContactId) {
            if (ph) ph.style.display = 'block';
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

        const chatInput = document.getElementById('chatInput');
        if (chatInput) chatInput.style.display = canSendDirect ? 'flex' : 'none';
        const sendBtn = document.getElementById('sendBtn');
        if (sendBtn) sendBtn.disabled = !canSendDirect;
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

    document.getElementById('sendBtn').addEventListener('click', sendMessage);
    document.getElementById('messageText').addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    startPolling();
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
                    const btn = document.getElementById('centerDropdown');
                    if (btn) btn.innerHTML = '<i class="fab fa-centercode mr-2"></i> ' + centerName;
                    groupMode = false;
                    showContactsList();
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
    const contactsSearch = document.getElementById('contactsSearch');
    if (contactsSearch) {
        contactsSearch.addEventListener('input', function (e) {
            renderContacts(e.target.value || '');
        });
    }
    const phInit = document.getElementById('messagesPlaceholder');
    if (phInit) phInit.style.display = 'block';
    const sendBtnInit = document.getElementById('sendBtn');
    if (sendBtnInit) sendBtnInit.disabled = true;
    const headerInit = document.getElementById('chatHeader');
    if (headerInit) headerInit.style.display = 'none';
    const chatInputInit = document.getElementById('chatInput');
    if (chatInputInit) chatInputInit.style.display = 'none';
    try { setActiveView('messages'); } catch (e) { }
</script>

@stop
