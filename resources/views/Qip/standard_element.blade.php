<style>
    /* Theme accent for Standards & Elements h4 heading */
    .theme-purple h4.mb-3,
    .theme-blue h4.mb-3,
    .theme-cyan h4.mb-3,
    .theme-green h4.mb-3,
    .theme-orange h4.mb-3,
    .theme-blush h4.mb-3 {
        color: var(--sd-accent) !important;
    }
</style>
<!-- Theme accent color overrides for specific elements when a theme is active -->
<style>
    /* Dropdown button accent */
    .theme-purple .btn-outline-primary,
    .theme-blue .btn-outline-primary,
    .theme-cyan .btn-outline-primary,
    .theme-green .btn-outline-primary,
    .theme-orange .btn-outline-primary,
    .theme-blush .btn-outline-primary {
        border-color: var(--sd-accent) !important;
        color: var(--sd-accent) !important;
    }
    .theme-purple .btn-outline-primary:hover,
    .theme-blue .btn-outline-primary:hover,
    .theme-cyan .btn-outline-primary:hover,
    .theme-green .btn-outline-primary:hover,
    .theme-orange .btn-outline-primary:hover,
    .theme-blush .btn-outline-primary:hover {
        background: var(--sd-accent) !important;
        color: #fff !important;
    }
    /* Edit button accent */
    .theme-purple .btn-outline-primary.btn-sm,
    .theme-blue .btn-outline-primary.btn-sm,
    .theme-cyan .btn-outline-primary.btn-sm,
    .theme-green .btn-outline-primary.btn-sm,
    .theme-orange .btn-outline-primary.btn-sm,
    .theme-blush .btn-outline-primary.btn-sm {
        border-color: var(--sd-accent) !important;
        color: var(--sd-accent) !important;
    }
    .theme-purple .btn-outline-primary.btn-sm:hover,
    .theme-blue .btn-outline-primary.btn-sm:hover,
    .theme-cyan .btn-outline-primary.btn-sm:hover,
    .theme-green .btn-outline-primary.btn-sm:hover,
    .theme-orange .btn-outline-primary.btn-sm:hover,
    .theme-blush .btn-outline-primary.btn-sm:hover {
        background: var(--sd-accent) !important;
        color: #fff !important;
    }
    /* h5 accent (Discussion Board) */
    .theme-purple h5,
    .theme-blue h5,
    .theme-cyan h5,
    .theme-green h5,
    .theme-orange h5,
    .theme-blush h5 {
        color: var(--sd-accent) !important;
    }
    /* Standard title accent */
    .theme-purple .standard-title,
    .theme-blue .standard-title,
    .theme-cyan .standard-title,
    .theme-green .standard-title,
    .theme-orange .standard-title,
    .theme-blush .standard-title {
        color: var(--sd-accent) !important;
    }
    /* Element name accent */
    .theme-purple .element-name,
    .theme-blue .element-name,
    .theme-cyan .element-name,
    .theme-green .element-name,
    .theme-orange .element-name,
    .theme-blush .element-name {
        color: var(--sd-accent) !important;
    }
</style>
@extends('layout.master')
@section('title', 'Standard and Element')
@section('parentPageTitle', '')

<style>
    .discussion-board {
        height: 80%;
        display: flex;
        flex-direction: column;
        background: #f3f6fa;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    .discussion-body {
        flex-grow: 1;
        overflow-y: auto;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 5px;
        background: #fff;
    }

    .discussion-input {
        display: flex;
        gap: 10px;
    }

    .discussion-input input {
        flex-grow: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .standard-section {
        padding-left: 30px;
        padding-right: 10px;
    }

    .standard-card {
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-left: 5px solid #007bff;
        margin-bottom: 15px;
        border-radius: 5px;
        padding: 15px 20px;
        transition: all 0.3s ease;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    }

    .standard-card:hover {
        background: #f7faff;
    }

    .standard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .standard-title {
        font-weight: bold;
        color: #333;
    }

    .element-list {
        margin-top: 10px;
        padding-left: 10px;
    }

    .element-item {
        background: #f0f3f7;
        padding: 12px;
        border-radius: 5px;
        margin-bottom: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .element-name {
        font-weight: 500;
        color: #222;
    }

    .fade-toggle {
        display: none;
        animation: fadeIn 0.4s ease-in-out;
    }

    @keyframes fadeIn {
        from {opacity: 0;}
        to {opacity: 1;}
    }
</style>


<style>

.discussion-board {
    height: 100%;
    display: flex;
    flex-direction: column;
    background: #f3f6fa;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
}

.discussion-body {
    flex-grow: 1;
    overflow-y: auto;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 5px;
    background: #fff;
    max-height: 400px;
}

.chat-message {
    display: flex;
    margin-bottom: 10px;
    align-items: flex-start;
}

.chat-message.sent {
    flex-direction: row-reverse;
    text-align: right;
}

.chat-message .avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 10px;
}

.msg-content {
    max-width: 70%;
    background: #f1f3f7;
    border-radius: 10px;
    padding: 10px;
    position: relative;
}

.chat-message.sent .msg-content {
    background: #d1ecf1;
}

.msg-author {
    font-weight: bold;
    font-size: 0.85rem;
    color: #555;
    margin-bottom: 5px;
}

.msg-text {
    color: #222;
}

.discussion-input {
    display: flex;
    gap: 10px;
}

.discussion-input input {
    flex-grow: 1;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
    #discussionBox {
        max-height: 300px;
        overflow-y: auto;
        padding-right: 10px;
    }

    .chat-message {
        display: flex;
        margin-bottom: 10px;
        align-items: flex-start;
    }

    .chat-message.sent {
        flex-direction: row-reverse;
        text-align: right;
    }

    .chat-message .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 10px;
    }

    .msg-content {
        max-width: 70%;
        background: #f1f3f7;
        border-radius: 10px;
        padding: 10px;
        position: relative;
    }

    .chat-message.sent .msg-content {
        background: #d1ecf1;
    }

    .msg-author {
        font-weight: bold;
        font-size: 0.85rem;
        color: #555;
        margin-bottom: 5px;
    }

    .msg-text {
        color: #222;
    }

    .toast-popup {
        position: fixed;
        top: 15px;
        right: 15px;
        background: #28a745;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        display: none;
        z-index: 9999;
    }
</style>


@section('content')

<div class="text-zero top-right-button-container d-flex justify-content-end"
    style="margin-right: 20px;margin-top: -47px;">


    @php
    $currentArea = $Qip_area->first(); // the selected area
@endphp

<div class="dropdown mb-4">
    <button class="btn btn-outline-primary btn-lg dropdown-toggle" type="button" id="qipAreaDropdown"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-layer-group mr-2"></i>{{ $currentArea->title ?? 'Select Quality Area' }}
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="qipAreaDropdown"
        style="top:3% !important;left:13px !important;">
        @foreach($all_areas as $area)
            <a href="{{ route('qip.area.view', ['id' => $qip->id, 'area' => $area->id]) }}"
                class="dropdown-item {{ $currentArea->id == $area->id ? 'active font-weight-bold text-primary' : '' }}"
                style="background-color:white;">
                Quality Area {{ $loop->iteration }} - {{ $area->title }}
            </a>
        @endforeach
    </div>
</div>



</div>



<div class="row mt-4" style="min-height: 600px;">

    {{-- 🗣️ Chat Bot (30%) --}}
    <div class="col-md-4">
    <div class="discussion-board">
        <h5><strong>🗣️ Discussion Board</strong></h5>

        <div class="discussion-body" id="discussionBox">
            @foreach($QipDescussionBoard->reverse() as $chat)
                <div class="chat-message {{ $chat->added_by == auth()->id() ? 'sent' : 'received' }}">
                    <img src="{{ asset($chat->user->imageUrl) }}" class="avatar" />
                    <div class="msg-content">
                        <div class="msg-author">{{ $chat->user->name }}</div>
                        <div class="msg-text">{{ $chat->commentText }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="discussion-input">
            <input type="text" id="commentInput" placeholder="Write a comment..." />
            <button class="btn btn-primary" id="sendComment"><i class="fa fa-paper-plane"></i></button>
        </div>
    </div>
</div>

    {{-- 📋 Standards & Elements (70%) --}}
    <div class="col-md-8 standard-section">
        <h4 class="mb-3">Standards & Elements</h4>

        @foreach($qipStandard as $standard)
            <div class="standard-card" onclick="toggleElements({{ $standard->id }})">
                <div class="standard-header">
                    <div class="standard-title">{{ $standard->name }}</div>
                    <a href="{{ route('qip.standard.edit', ['qip' => $qip->id, 'standard' => $standard->id]) }}"
                       class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation();">
                        <i class="fa fa-pencil-alt"></i> Edit
                    </a>
                </div>
                <div id="elements-{{ $standard->id }}" class="fade-toggle element-list">
                    @foreach($standard->elements as $element)
                        <div class="element-item">
                            <div>
                                <span class="element-name">{{ $element->elementName }}</span> - {{ $element->name }}
                            </div>
                            <a href="{{ route('qip.element.view', ['qip' => $qip->id, 'element' => $element->id]) }}"
                               class="btn btn-sm btn-outline-success">
                                View
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

</div>


<script>


function toggleElements(id) {
        const el = document.getElementById('elements-' + id);
        $('.fade-toggle').not(el).slideUp(); // Close others
        $(el).slideToggle();
    }


    const chatBox = document.getElementById('discussionBox');
    const sendBtn = document.getElementById('sendComment');

    function showToast(msg) {
        const toast = document.createElement('div');
        toast.className = 'toast-popup';
        toast.innerText = msg;
        document.body.appendChild(toast);
        $(toast).fadeIn().delay(1500).fadeOut(() => toast.remove());
    }

    sendBtn.addEventListener('click', function () {
        const input = document.getElementById('commentInput');
        const message = input.value.trim();
        if (!message) return;

        $.post("{{ route('qip.discussion.send') }}", {
            _token: "{{ csrf_token() }}",
            qipid: {{ $qip->id }},
            areaid: {{ $Qip_area->first()->id }},
            commentText: message
        }).done(function (res) {
            if (res.status === 'success') {
                const c = res.comment;
                const html = `
                <div class="chat-message sent">
                    <img src="${c.user.imageUrl ? '{{ asset('') }}' + c.user.imageUrl : '#'}" class="avatar" />
                    <div class="msg-content">
                        <div class="msg-author">${c.user.name}</div>
                        <div class="msg-text">${c.commentText}</div>
                    </div>
                </div>
                `;
                $('#discussionBox').append(html);
                input.value = '';
                chatBox.scrollTop = chatBox.scrollHeight;
                showToast("Message sent");
            }
        }).fail(() => alert("Error sending message"));
    });
</script>



@include('layout.footer')
@stop