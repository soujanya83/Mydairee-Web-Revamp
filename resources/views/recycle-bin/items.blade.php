@extends('layout.master')
@section('title', 'Recycle Bin')
@section('parentPageTitle', 'Dashboard')

@section('content')
    @include('recycle-bin.partials.items')
@stop
@include('layout.footer')
@stop

@push('scripts')
<script>
    $(function(){
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        function showToast(type, title){
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1800,
                icon: type,
                title: title
            });
        }

        // Restore
        $('.recycle-restore-form').on('submit', function(e){
            e.preventDefault();
            const form = $(this);
            Swal.fire({
                title: 'Restore item?',
                text: "This will restore the item to its original location.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Restore',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: form.serialize(),
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                        success: function(resp){
                            showToast('success', 'Restored');
                            const id = form.data('id');
                            $('#recycle-card-' + id).fadeOut(300, function(){ $(this).remove(); });
                        },
                        error: function(){
                            showToast('error', 'Restore failed');
                        }
                    });
                }
            });
        });

        // Permanent delete
        $('.recycle-delete-form').on('submit', function(e){
            e.preventDefault();
            const form = $(this);
            Swal.fire({
                title: 'Permanently delete?',
                text: "This cannot be undone. Files and related data will be removed.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    // send DELETE via AJAX
                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: form.serialize(),
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                        success: function(resp){
                            showToast('success', 'Deleted permanently');
                            const id = form.data('id');
                            $('#recycle-card-' + id).fadeOut(300, function(){ $(this).remove(); });
                        },
                        error: function(){
                            showToast('error', 'Delete failed');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
