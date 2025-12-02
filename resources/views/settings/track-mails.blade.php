@extends('layout.master')
@section('title', 'Track Mails')
@section('parentPageTitle', 'Settings')

<style>
    /* Gmail-style Container */
    .inbox-container {
        background: #ffffff;
        border-radius: 0;
        box-shadow: none;
        display: flex;
        flex-direction: column;
        height: calc(100vh - 180px);
        overflow: hidden;
        position: relative;
        border: 1px solid #e0e0e0;

    }

    /* Gmail-style Header */
    .inbox-header {
        background: #ffffff;
        color: #202124;
        padding: 16px 20px;
        border-bottom: 1px solid #e0e0e0;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .inbox-header h5 {
        font-size: 18px;
        font-weight: 500;
        color: #202124;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .inbox-header a {
        color: #5f6368;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        transition: background-color 0.2s;
    }

    .inbox-header a:hover {
        background-color: #f1f3f4;
    }

    .inbox-header a i {
        color: #5f6368 !important;
    }

    .inbox-body {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        background: #ffffff;
        position: relative;
    }

    .inbox-footer {
        border-top: 1px solid #e0e0e0;
        padding: 16px 20px;
        background: #ffffff;
        flex-shrink: 0;
        text-align: center;
        position: sticky;
        bottom: 0;
        z-index: 10;
    }

    /* Gmail-style Parent Row */
    .parent-row {
        border-bottom: 1px solid #e0e0e0;
        padding: 12px 16px;
        cursor: pointer;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
    }

    .parent-row:hover {
        box-shadow: inset 1px 0 0 #dadce0, inset -1px 0 0 #dadce0,
            0 1px 2px rgba(60, 64, 67, .3), 0 1px 3px rgba(60, 64, 67, .15);
        z-index: 1;
    }

    .parent-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #d93025;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        font-size: 16px;
    }

    /* Parent Emails Panel — **Updated to stay inside inbox-container**  */
    .parent-emails-fullpage {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        background: #ffffff;
        z-index: 30;
        overflow: hidden;
        animation: slideInRight 0.25s ease;
    }

    .parent-emails-fullpage.active {
        display: flex;
        flex-direction: column;
    }

    .email-fullpage-header {
        position: sticky;
        top: 0;
        background: linear-gradient(135deg, #1a73e8, #1557b0);
        color: #ffffff;

        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 50;
    }

    .email-back-btn {
        cursor: pointer;
        width: 36px;
        height: 36px;
        border: 0;
        background: transparent;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: 0.2s;
    }

    .email-back-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.05);
    }

    .parent-emails-split {
        display: grid;
        grid-template-columns: 0.35fr 1fr;
        height: 100%;
        overflow: hidden;
    }

    .emails-list-pane {
        border-right: 1px solid #e0e0e0;
        overflow-y: auto;
    }

    .email-detail-pane {
        overflow-y: auto;
        padding: 24px;
        background: #ffffff;
    }

    .email-item {
        background: #fff;
        border-bottom: 1px solid #e0e0e0;
        padding: 12px;
        cursor: pointer;
        position: relative;
        transition: background-color 0.15s ease;
    }

    .email-item:hover {
        background: #d9e8f7;
    }

    .email-item.selected {
        background: #eef3ff;
        border-left: 3px solid #1a73e8;
        padding-left: 9px;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Email item details */
    .email-subject {
        font-weight: 600;
        color: #202124;
        margin-bottom: 4px;
        font-size: 13px;
        line-height: 1.3;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .email-meta {
        font-size: 11px;
        color: #5f6368;
        margin-top: 4px;
        line-height: 1.4;
    }

    .email-date {
        font-size: 11px;
        color: #5f6368;
        white-space: nowrap;
    }

    .email-header-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 8px;
    }

    /* Email detail view */
    .email-detail {
        display: none;
    }

    .email-detail.active {
        display: block;
    }

    .email-detail-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #5f6368;
        text-align: center;
    }

    .email-full-subject {
        font-size: 19px;
        font-weight: 500;
        color: #202124;
        padding-left: 13px;
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 12px;
        line-height: 1.4;
    }

    .parent-details {
        margin-left: 10px;
    }

    .email-full-meta {
        display: grid;
        grid-template-columns: 120px 1fr;
        gap: 8px 16px;
        margin-bottom: 24px;
        padding: 6px;
        padding-left: 10px !important;
        background: #f8f9fa;
        border-radius: 8px;
        font-size: 14px;
    }

    .email-full-meta-label {
        font-weight: 600;
        color: #5f6368;
    }

    .email-full-meta-value {
        color: #202124;
    }

    .child-tag {
        display: inline-block;
        background: #e8f0fe;
        color: #1967d2;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 12px;
        margin-right: 4px;
        font-weight: 500;
    }

    .email-full-message {
        line-height: 1.6;
        color: #202124;
        font-size: 14px;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .email-full-message p {
        margin-bottom: 12px;
    }

    .email-full-message p:last-child {
        margin-bottom: 0;
    }

    /* Attachments */
    .attachment-section {
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px solid #e0e0e0;
    }

    .attachment-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
        gap: 8px;
        margin-top: 12px;
    }

    .attachment-preview-card {
        position: relative;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.2s;
        aspect-ratio: 1;
        max-width: 120px;
    }

    .attachment-preview-card:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .attachment-preview-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .attachment-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
        color: white;
        padding: 4px 6px;
        font-size: 9px;
    }

    .attachment-overlay .filename {
        display: block;
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 2px;
    }

    .attachment-overlay small {
        font-size: 8px;
    }

    .attachment-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        text-decoration: none;
        color: #202124;
        margin-right: 6px;
        margin-bottom: 6px;
        transition: all 0.2s;
        font-size: 12px;
    }

    .attachment-badge:hover {
        background: #e8eaed;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .attachment-badge i {
        color: #1a73e8;
        font-size: 14px;
    }

    .attachment-badge strong {
        font-size: 12px;
    }

    .attachment-badge small {
        font-size: 10px;
    }

    /* No emails state */
    .no-emails {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #5f6368;
        text-align: center;
        padding: 40px;
    }

    /* Image Modal */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        padding-top: 60px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.9);
    }

    .image-modal.show {
        display: block;
    }

    .modal-content-image {
        margin: auto;
        display: block;
        max-width: 90%;
        max-height: 80vh;
        animation: zoom 0.3s;
    }

    @keyframes zoom {
        from {
            transform: scale(0);
        }

        to {
            transform: scale(1);
        }
    }

    .modal-close {
        position: absolute;
        top: 20px;
        right: 40px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        cursor: pointer;
    }

    .modal-close:hover,
    .modal-close:focus {
        color: #bbb;
    }

    .modal-caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        height: 50px;
    }

    .parent-details h6 {
        margin: 0 0 4px 0;
        font-weight: 600;
        color: #202124;
    }

    .parent-details small {
        color: #5f6368;
        font-size: 12px;
    }

    .email-count {
        font-size: 13px;
        color: #5f6368;
        font-weight: 500;
    }

    .expand-icon {
        color: #5f6368;
        font-size: 14px;
    }
</style>

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="inbox-container">

                <div class="inbox-header">
                    <h5 class="mb-0">
                        <a href="{{ route('settings.parent_settings') }}" title="Back to Profile section">
                            <i class="fa-solid fa-person-walking-arrow-loop-left"></i>
                        </a>
                        <i class="fa-solid fa-envelope-open-text"></i> Email Inbox
                    </h5>
                </div>

                <div class="inbox-body"> @if ($parents->count() > 0)
                    @foreach ($parents as $parent)
                    @php
                    $parentEmails = $emails->where('parent_id', $parent->id);
                    $emailCount = $parentEmails->count();
                    $initials = strtoupper(substr($parent->name, 0, 1));

                    // Collect unique children using normalized relation `childrenRelation`
                    $allChildren = collect();
                    foreach ($parentEmails as $email) {
                        $list = $email->relationLoaded('childrenRelation') ? $email->childrenRelation : $email->childrenRelation()->get();
                        foreach ($list as $child) {
                            // normalize object -> array for display (handle Eloquent models properly)
                            $c = ($child instanceof \Illuminate\Database\Eloquent\Model) ? $child->toArray() : (is_array($child) ? $child : (array)$child);
                            $allChildren->push($c);
                        }
                    }
                    $uniqueChildren = $allChildren->unique('id')->values();
                    @endphp

                    <!-- Parent Row -->
                    <div class="parent-row" id="parent-row-{{ $parent->id }}"
                        onclick="openParentEmails({{ $parent->id }})">

                        <div class="d-flex align-items-center gap-3">
                            <div class="parent-avatar">{{ $initials }}</div>

                            <div class="parent-details">
                                <h6>{{ $parent->name }}</h6>
                                <small>
                                    <i class="fa fa-envelope"></i> {{ $parent->email }}

                                    @if ($uniqueChildren->count() > 0)
                                    <span class="ml-2">
                                        <i class="fa fa-child text-success"></i>
                                        @foreach ($uniqueChildren as $child)
                                        <span class="child-tag">{{ $child['name'] ?? 'N/A' }}</span>
                                        @endforeach
                                    </span>
                                    @endif
                                </small>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <span class="email-count">{{ $emailCount }} {{ $emailCount == 1 ? 'email' : 'emails'
                                }}</span>
                            <i class="fa fa-chevron-right expand-icon"></i>
                        </div>
                    </div>

                    <!-- Parent Emails Panel (inside container) -->
                    <div class="parent-emails-fullpage" id="parent-emails-{{ $parent->id }}">

                        <div class="email-fullpage-header">
                            <button class="email-back-btn" onclick="closeParentEmails({{ $parent->id }})">
                                <i class="fa fa-arrow-left"></i>
                            </button>

                            <div>
                                <h7 class="mb-0"><i class="fa fa-inbox"></i> Emails for {{ $parent->name }}</h7>
                                <small>{{ $emailCount }} {{ $emailCount == 1 ? 'email' : 'emails' }}</small>
                            </div>
                        </div>

                        <div class="parent-emails-split">

                            <!-- EMAIL LIST -->
                            <div class="emails-list-pane">
                                @if ($parentEmails->count() > 0)
                                @foreach ($parentEmails as $email)
                                <div class="email-item" id="email-item-{{ $email->id }}"
                                    onclick="openFullEmail({{ $email->id }}, event)">

                                    <div class="email-header-row d-flex justify-content-between">
                                        <div>
                                            <div class="email-subject">
                                                <i class="fa fa-envelope text-info"></i> {{ $email->subject }}
                                            </div>
                                            <div class="email-meta">
                                                <i class="fa fa-user"></i>
                                                From: <strong>{{ $email->sender ? $email->sender->name : 'System'
                                                    }}</strong>
                                            </div>
                                        </div>

                                        <div class="email-date text-right">
                                            <strong>{{ $email->sent_at->format('M d, Y') }}</strong>
                                            <div>{{ $email->sent_at->format('h:i A') }}</div>
                                        </div>
                                    </div>

                                </div>
                                @endforeach
                                @else
                                <div class="no-emails">
                                    <i class="fa fa-inbox fa-2x mb-2"></i>
                                    <p>No emails sent to this parent yet</p>
                                </div>
                                @endif
                            </div>

                            <!-- EMAIL DETAIL VIEW -->
                            <div class="email-detail-pane">

                                @if ($parentEmails->count() > 0)
                                @foreach ($parentEmails as $email)
                                <div class="email-detail" id="email-full-{{ $email->id }}">

                                    <h1 class="email-full-subject">{{ $email->subject }}</h1>

                                    <div class="email-full-meta">
                                        <div class="email-full-meta-label">
                                            <i class="fa fa-user"></i> From:
                                        </div>
                                        <div class="email-full-meta-value">
                                            {{ $email->sender ? $email->sender->name : 'System' }}
                                        </div>

                                        <div class="email-full-meta-label">
                                            <i class="fa fa-envelope"></i> To:
                                        </div>
                                        <div class="email-full-meta-value">
                                            {{ $email->parent_name }} ({{ $email->parent_email }})
                                        </div>

                                        @php
                                            $childrenList = $email->relationLoaded('childrenRelation') ? $email->childrenRelation : $email->childrenRelation()->get();
                                        @endphp
                                        @if ($childrenList->count() > 0)
                                        <div class="email-full-meta-label">
                                            <i class="fa fa-child"></i> Children:
                                        </div>
                                        <div class="email-full-meta-value">
                                            @foreach ($childrenList as $child)
                                            @php $c = ($child instanceof \Illuminate\Database\Eloquent\Model) ? $child->toArray() : (is_array($child) ? $child : (array)$child); @endphp
                                            <span class="child-tag">{{ $c['name'] ?? 'N/A' }}</span>
                                            @endforeach
                                        </div>
                                        @endif

                                        <div class="email-full-meta-label">
                                            <i class="fa fa-calendar"></i> Sent:
                                        </div>
                                        <div class="email-full-meta-value">
                                            {{ $email->sent_at->format('l, F d, Y \\a\\t h:i A') }}
                                        </div>
                                    </div>

                                    <div class="email-full-message">
                                        {!! $email->message !!}
                                    </div>
                                    @php
                                        $attachmentsList = $email->relationLoaded('attachmentsRelation') ? $email->attachmentsRelation : $email->attachmentsRelation()->get();
                                    @endphp
                                    @if ($attachmentsList->count() > 0)
                                    <div class="attachment-section">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fa fa-paperclip text-info mr-2"></i>
                                            <strong>{{ $attachmentsList->count() }}
                                                Attachment{{ $attachmentsList->count() > 1 ? 's' : '' }}</strong>
                                        </div>

                                        @php
                                        $imageExtensions = ['jpg','jpeg','png','gif','bmp','webp'];
                                        $images = [];
                                        $files = [];

                                        foreach ($attachmentsList as $attachment) {
                                            // attachments may be Eloquent models or arrays
                                            $att = ($attachment instanceof \Illuminate\Database\Eloquent\Model) ? $attachment->toArray() : (is_array($attachment) ? $attachment : (array)$attachment);
                                            $path = $att['path'] ?? '';
                                            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

                                            if (in_array($extension, $imageExtensions)) {
                                                $images[] = $att;
                                            } else {
                                                $files[] = $att;
                                            }
                                        }
                                        @endphp

                                        {{-- IMAGE ATTACHMENTS --}}
                                        @if (count($images) > 0)
                                        <div class="attachment-grid">
                                            @foreach ($images as $image)
                                            @php
                                            $rawPath = $image['path'] ?? '';
                                            $src = '';

                                            if (preg_match('#^https?://#i', $rawPath)) {
                                            $parts = parse_url($rawPath);
                                            $path = $parts['path'] ?? '';
                                            $query = isset($parts['query']) ? '?' . $parts['query'] : '';

                                            $segments = array_map('rawurlencode', explode('/', ltrim($path, '/')));
                                            $base = rtrim(url('/'), '/');
                                            $src = $base . '/' . implode('/', $segments) . $query;
                                            } else {
                                            $clean = str_replace('\\', '/', ltrim($rawPath, '/'));

                                            if (str_starts_with($clean, 'public/')) {
                                            $clean = 'storage/' . substr($clean, 7);
                                            }

                                            $segments = array_map('rawurlencode', explode('/', $clean));
                                            $src = asset(implode('/', $segments));
                                            }
                                            @endphp

                                            @if ($src)
                                            <div class="attachment-preview-card"
                                                onclick="openImageModal('{{ $src }}', '{{ $image['name'] ?? 'Image' }}', '{{ number_format(($image['size'] ?? 0)/1024, 2) }} KB')">

                                                <img src="{{ $src }}" alt="{{ $image['name'] ?? 'Image' }}"
                                                    onerror="this.closest('.attachment-preview-card').classList.add('broken'); this.remove();">

                                                <div class="attachment-overlay">
                                                    <span class="filename">{{ $image['name'] ?? 'Image' }}</span>
                                                    <small>{{ number_format(($image['size'] ?? 0)/1024, 2) }} KB</small>
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                        @endif

                                        {{-- FILE ATTACHMENTS --}}
                                        @if (count($files) > 0)
                                        <div class="mt-3">
                                            @foreach ($files as $file)
                                            @php
                                            $path = $file['path'] ?? '';
                                            if (preg_match('#^https?://#i', $path)) {
                                            $parts = parse_url($path);
                                            $ppath = $parts['path'] ?? '';
                                            $query = isset($parts['query']) ? '?' . $parts['query'] : '';

                                            $segments = array_map('rawurlencode', explode('/', ltrim($ppath, '/')));
                                            $base = rtrim(url('/'), '/');
                                            $fileUrl = $base . '/' . implode('/', $segments) . $query;
                                            } else {
                                            $clean = str_replace('\\', '/', ltrim($path, '/'));

                                            if (str_starts_with($clean, 'public/')) {
                                            $clean = 'storage/' . substr($clean, 7);
                                            }

                                            $segments = array_map('rawurlencode', explode('/', $clean));
                                            $fileUrl = asset(implode('/', $segments));
                                            }
                                            @endphp

                                            <a href="{{ $fileUrl }}" target="_blank" download class="attachment-badge">
                                                <i class="fa fa-file-alt"></i>
                                                <span>
                                                    <strong>{{ $file['name'] ?? 'Attachment' }}</strong>
                                                    <small>{{ number_format(($file['size'] ?? 0)/1024, 2) }} KB</small>
                                                </span>
                                            </a>
                                            @endforeach
                                        </div>
                                        @endif

                                    </div>
                                    @endif
                                </div>
                                @endforeach

                                <div class="email-detail-placeholder" id="email-detail-placeholder-{{ $parent->id }}">
                                    <i class="fa fa-envelope-open-text fa-2x mb-2 text-muted"></i>
                                    <div>Select an email to view its content</div>
                                </div>
                                @endif

                            </div> <!-- email-detail-pane -->
                        </div> <!-- parent-emails-split -->
                    </div> <!-- parent-emails-fullpage -->
                    @endforeach

                    @else
                    <div class="no-emails">
                        <i class="fa fa-inbox fa-3x mb-3"></i>
                        <h5>No parents selected</h5>
                        <p>Select parents from the list to view their email history.</p>
                    </div>
                    @endif
                </div>



            </div>
        </div>
    </div>
</div>

<!-- IMAGE MODAL -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <span class="modal-close" onclick="closeImageModal()">&times;</span>
    <img id="modalImage" class="modal-content-image">
    <div id="modalCaption" class="modal-caption"></div>
</div>

<script>
    let currentParentId = null;

    function openParentEmails(parentId) {
        // Close any other open panels first
        document.querySelectorAll('.parent-emails-fullpage.active').forEach(p => {
            if (p.id !== 'parent-emails-' + parentId) {
                p.classList.remove('active');
            }
        });

        // Hide all parent rows
        document.querySelectorAll('.parent-row').forEach(row => row.style.display = 'none');

        // Show panel
        const panel = document.getElementById('parent-emails-' + parentId);
        if (panel) panel.classList.add('active');

        currentParentId = parentId;

        // Reset inside panel
        panel.querySelectorAll('.email-detail').forEach(d => d.classList.remove('active'));
        panel.querySelectorAll('.email-item').forEach(i => i.classList.remove('selected'));

        const placeholder = document.getElementById('email-detail-placeholder-' + parentId);
        if (placeholder) placeholder.style.display = 'block';
    }

    function closeParentEmails(parentId) {
        // Hide email panel
        const panel = document.getElementById('parent-emails-' + parentId);
        if (panel) panel.classList.remove('active');

        // Show parent list again
        document.querySelectorAll('.parent-row').forEach(row => row.style.display = 'flex');

        currentParentId = null;
    }

    function openFullEmail(emailId, event) {
        event.stopPropagation();

        const parentId = currentParentId;
        if (!parentId) return;

        const parentPanel = document.getElementById('parent-emails-' + parentId);

        // Hide placeholder
        const placeholder = document.getElementById('email-detail-placeholder-' + parentId);
        if (placeholder) placeholder.style.display = 'none';

        parentPanel.querySelectorAll('.email-detail').forEach(d => d.classList.remove('active'));
        parentPanel.querySelectorAll('.email-item').forEach(i => i.classList.remove('selected'));

        document.getElementById('email-full-' + emailId).classList.add('active');
        document.getElementById('email-item-' + emailId).classList.add('selected');
    }

    function openImageModal(src, name, size) {
        document.getElementById('imageModal').classList.add('show');
        document.getElementById('modalImage').src = src;
        document.getElementById('modalCaption').innerHTML = name + ' - ' + size;
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('imageModal');
            if (modal.classList.contains('show')) return closeImageModal();

            const activeParent = document.querySelector('.parent-emails-fullpage.active');
            if (activeParent) return closeParentEmails(currentParentId);
        }
    });
</script>

@stop

@include('layout.footer')