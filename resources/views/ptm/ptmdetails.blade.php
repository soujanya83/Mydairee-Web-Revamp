@extends('layout.master')

@section('title', 'View PTM')

@section('content')
<style>
    .hover-row.selected {
        background-color: #e8f2ff !important;
    }

    .hover-row {
        transition: all 0.2s ease-in-out;
    }

    .hover-row:hover {
        background-color: #f8fbff !important;
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .hover-btn {
        transition: all 0.3s ease;
    }

    .hover-btn:hover {
        background-color: #0d6efd;
        color: white !important;
        transform: scale(1.05);
    }

    #globalFilter {
        transition: all 0.2s ease-in-out;
    }

    #globalFilter:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 6px rgba(13, 110, 253, 0.3);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        font-weight: 600;
        font-size: 0.85rem;
        border-radius: 50px;
        padding: 6px 14px;
        color: white;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease-in-out;
    }

    .status-badge.default {
        background: linear-gradient(135deg, #28a745, #5dd39e);
    }

    .status-badge.rescheduled {
        background: linear-gradient(135deg, #ffc107, #ff8c00);
        color: #222;
    }

    .status-badge:hover {
        transform: scale(1.07);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .history-icon {
        cursor: pointer;
        transition: color 0.2s;
    }

    .history-icon:hover {
        color: #0d6efd;
    }

    /* Ensure date badges inside the PTM table are rendered with black text for readability */
    #ptmTable td .badge {
        color: #000 !important;
    }

    /* 🎨 Modern Pretty Checkbox */
    .childCheckbox,
    #selectAll {
        appearance: none;
        /* removes default checkbox */
        -webkit-appearance: none;
        -moz-appearance: none;
        width: 15px;
        height: 15px;
        border: 1px solid #0d6efd;
        border-radius: 6px;
        display: inline-block;
        position: relative;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    /* ✅ Checked State */
    .childCheckbox:checked,
    #selectAll:checked {
        background-color: #4d92fa;
        border-color: #4d92fa;
    }

    /* ✨ Checkmark */
    .childCheckbox:checked::after,
    #selectAll:checked::after {
        content: '✔';
        color: white;
        font-size: 13px;
        position: absolute;
        top: 1px;
        left: 3px;
    }

    /* 🖱️ Hover Effect */
    .childCheckbox:hover,
    #selectAll:hover {
        box-shadow: 0 0 6px rgba(13, 110, 253, 0.4);
        transform: scale(1.1);
    }
</style>
{{-- ✅ Flash Messages --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
    <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

{{-- ✅ Validation Errors (from $errors variable) --}}
@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
    <h6 class="mb-2"><i class="fas fa-times-circle mr-2"></i> Please fix the following errors:</h6>
    <ul class="mb-0 pl-3">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
<div class="ptm-wrapper">
    <div class="container ">
        <div class="card shadow-lg border-0 rounded">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                    <h3 class="font-weight-bold text-primary mb-2 mb-md-0">
                        <i class="fa fa-calendar-check mr-2 text-info"></i> PTM : {{ $ptm->title }}
                    </h3>

                    <div class="d-flex align-items-center position-relative">
                        <input type="text" id="globalFilter" class="form-control rounded-pill pl-5 shadow-sm"
                            placeholder="Search here..." style="min-width: 260px;">
                        <span class="position-absolute" style="left:15px; top:8px; color:#999;">🔍</span>
                    </div>
                </div>

                <hr>
                <div class="text-right mb-3">
                    <button id="bulkReschedule" class="btn btn-primary rounded-pill px-4 py-2">
                        <i class="fa fa-sync-alt mr-1"></i> Reschedule Selected
                    </button>
                </div>

                {{-- 🔹 Inline red error bar (hidden by default) --}}
                <div id="inlineError" class="text-center text-white bg-danger py-2 rounded d-none shadow-sm"
                    style="font-weight: 600;"> Please select at least one child to reschedule.
                </div>



                <div class="table-responsive">
                    <table id="ptmTable" class="table table-hover table-bordered text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>Child Name</th>
                                <th>Date</th>
                                <th>Slot</th>
                                <th>Status</th>
                                <th>
                                    <input type="checkbox" id="selectAll" class="mr-3 align-middle" title="Select All"
                                        style="transform: scale(1.2); cursor: pointer;">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($childrenData as $child)
                            <tr class="hover-row">
                                <td class="font-weight-bold text-dark">{{ $child['name'] }}</td>
                                <td>
                                    @if ($child['date'])
                                        <span class="badge badge-info text-white">
                                            {{ \Carbon\Carbon::parse($child['date'])->format('d-m-Y') }}
                                        </span>
                                    @else
                                        <em class="text-muted">No date set</em>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-info text-white">
                                        {{ $child['slot'] ?? 'No slot set' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($child['isRescheduled'])
                                    <span class="status-badge rescheduled">
                                        <i class="fa fa-sync-alt mr-1"></i> Rescheduled
                                    </span>
                                    @else
                                    <span class="status-badge default">
                                        <i class="fa fa-check-circle mr-1"></i> On Schedule
                                    </span>
                                    @endif

                                    @if (!empty($child['history']) && count($child['history']) > 0)
                                    <i class="fa fa-eye text-primary ml-2 history-icon open-history"
                                        data-history='@json($child["history"])' data-child="{{ $child['name'] }}"
                                        title="View History"></i>
                                    @endif
                                </td>
                                <td class="d-flex justify-content-center align-items-center gap-2">
                                    <!-- Checkbox next to the reschedule button -->
                                    <input type="checkbox" class="childCheckbox mr-2" value="{{ $child['id'] }}">

                                    <!-- Single Reschedule Button -->
                                    <a href="{{ route('ptm.reschedule-fstaff', ['ptm' => $ptm->id, 'child_id' => $child['id']]) }}"
                                        class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 hover-btn"
                                        style="border-radius: 50px">
                                        <i class="fa fa-sync-alt mr-1"></i> Reschedule
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Modal --}}
<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="historyModalLabel"><i class="fa fa-history mr-2"></i> PTM Change History
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6 id="historyChildName" class="font-weight-bold text-center mb-3"></h6>
                <div id="historyContent" class="table-responsive"></div>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script>
    $(document).ready(function() {
            // Global Filter
            $('#globalFilter').on('input', function() {
                var search = $(this).val().toLowerCase();
                $('#ptmTable tbody tr').each(function() {
                    var combinedText = $(this).text().toLowerCase();
                    $(this).toggle(combinedText.indexOf(search) > -1);
                });
            });

            // History Modal (open programmatically to ensure proper lifecycle)
            $(document).on('click', '.open-history', function(e) {
                e.preventDefault();
                var $icon = $(this);
                var history = $icon.data('history') || [];
                var childName = $icon.data('child') || '';

                $('#historyChildName').text('Change History for ' + childName);

                if (Array.isArray(history) && history.length > 0) {
                    var tableHtml =
                        '<table class="table table-striped table-bordered"><thead class="thead-light"><tr>' +
                        '<th>Changed On</th><th>Changed By</th><th>Previous Date</th><th>Previous Slot</th></tr></thead><tbody>';

                    history.forEach(function(item) {
                        var changedBy = 'Unknown';
                        if (item.changed_by && item.changed_by.name) {
                            changedBy = item.changed_by.name;
                            if (item.changed_by.userType) {
                                changedBy += ' (' + item.changed_by.userType + ')';
                            }
                        }
                        
                        tableHtml += '<tr>' +
                            '<td>' + (item.changed_at || 'N/A') + '</td>' +
                            '<td>' + changedBy + '</td>' +
                            '<td>' + (item.previous_date || '-') + '</td>' +
                            '<td>' + (item.previous_slot || '-') + '</td>' +
                            '</tr>';
                    });

                    tableHtml += '</tbody></table>';
                    $('#historyContent').html(tableHtml);
                } else {
                    $('#historyContent').html(
                        '<p class="text-muted text-center">No history available.</p>');
                }

                // Show modal programmatically and manage focus
                var $modal = $('#historyModal');
                $modal.modal('show');
                $modal.off('shown.bs.modal.historyFocus').on('shown.bs.modal.historyFocus', function() {
                    // move focus into modal for accessibility
                    $modal.find('.modal-content').attr('tabindex', -1).focus();
                });
            });

            // Robust cleanup on hide/hidden to prevent leftover backdrop/focus trap
            $('#historyModal').on('hide.bs.modal cleanup', function() {
                // remove any backdrops proactively
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css({
                    'padding-right': '',
                    'overflow': ''
                });
            });

            $('#historyModal').on('hidden.bs.modal', function() {
                // ensure the modal is hidden and remove show class
                var $modal = $(this);
                $modal.removeClass('show').attr('aria-hidden', 'true').hide();

                // blur active element to avoid aria-hidden focus blocking
                try {
                    if (document.activeElement && document.activeElement !== document.body) document
                        .activeElement.blur();
                } catch (e) {}

                // restore body state (defensive)
                $('body').removeClass('modal-open').css({
                    'padding-right': '',
                    'overflow': ''
                });
                $('.modal-backdrop').remove();
            });
        });
</script>
<script>
    $(document).ready(function() {
            // Hide the "Reschedule Selected" button initially
            $('#bulkReschedule').hide();

            // ✅ Handle individual checkbox selection
            $(document).on('change', '.childCheckbox', function() {
                $(this).closest('tr').toggleClass('selected', this.checked);

                const total = $('.childCheckbox').length;
                const checked = $('.childCheckbox:checked').length;

                // Update "Select All" checkbox automatically
                $('#selectAll').prop('checked', total === checked);

                // Show the button only if more than one box is selected
                toggleBulkButton(checked > 1);
            });

            // ✅ Handle "Select All" checkbox
            $('#selectAll').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('.childCheckbox').prop('checked', isChecked).trigger('change');
            });

            // ✅ Handle Bulk Reschedule button click
            $('#bulkReschedule').on('click', function() {
                $('#inlineError').addClass('d-none');

                const selected = $('.childCheckbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selected.length < 2) {
                    // Inline red error for invalid selection
                    $('#inlineError')
                        .removeClass('d-none')
                        .hide()
                        .slideDown(200);

                    setTimeout(() => {
                        $('#inlineError').slideUp(300, function() {
                            $(this).addClass('d-none');
                        });
                    }, 3000);
                    return;
                }

                const ptmId = "{{ $ptm->id }}";
                const query = selected.map(id => 'child_ids[]=' + id).join('&');
                const url = "{{ route('ptm.bulk-reschedule', ':ptmId') }}"
                    .replace(':ptmId', ptmId) + '?' + query;

                window.location.href = url;
            });

            // ✅ Helper function to toggle button visibility
            function toggleBulkButton(show) {
                if (show) {
                    $('#bulkReschedule').fadeIn(200);
                } else {
                    $('#bulkReschedule').fadeOut(200);
                }
            }
        });
</script>

{{-- Bootstrap 4 + jQuery + FontAwesome --}}
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection