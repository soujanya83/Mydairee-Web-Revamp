@extends('layout.master')
@section('title', 'Ingredients List')
@section('parentPageTitle', '')
<!-- Bootstrap 5 CSS -->
{{--
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


@section('content')




<div class="d-flex justify-content-end" style="margin-top: -47px;">
    <button class="btn btn-outline-info" type="button" data-bs-toggle="modal" data-bs-target="#ingredientModal">
        Add Ingredient
    </button>


</div>
<hr>

<style>
/* ===================== THEME SUPPORT (GLOBAL) ===================== */
body[class*='theme-'] .btn-outline-info {
    border-color: var(--sd-accent, #176ba6) !important;
    color: var(--sd-accent, #176ba6) !important;
}
body[class*='theme-'] .btn-outline-info:hover, body[class*='theme-'] .btn-outline-info:focus {
    background-color: var(--sd-accent, #176ba6) !important;
    color: #fff !important;
}
body[class*='theme-'] .btn-info {
    background-color: var(--sd-accent, #176ba6) !important;
    border-color: var(--sd-accent, #176ba6) !important;
    color: #fff !important;
}
body[class*='theme-'] .modal-header {
    background: var(--sd-accent, #176ba6) !important;
    color: #fff !important;
}
/* =================== END THEME SUPPORT =================== */
</style>

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:-22px">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

@endif

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:-22px">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">

            <div class="body table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ingredient Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ingredients as $index => $ingredient)
                        <tr class="{{ $ingredient->colorClass ?? 'xl-default' }}">
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{ \Illuminate\Support\Str::title($ingredient->name) }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-primary edit-ingredient-btn"
                                    data-id="{{ $ingredient->id }}" data-name="{{ $ingredient->name }}"
                                    data-action="{{ route('ingredients.update', $ingredient->id) }}">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('ingredients.destroy', $ingredient->id) }}" method="POST"
                                    style="display:inline-block;" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal" id="ingredientModal" tabindex="-1" aria-labelledby="ingredientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="ingredientForm" method="POST">
            @csrf
            <input type="hidden" name="_method" value="POST" id="formMethod">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ingredientModalLabel">Add New Ingredient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ingredientName" class="form-label">Ingredient Name</label>
                        <input type="text" class="form-control" id="ingredientName" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info" id="ingredientSaveBtn">Save</button>
                </div>
            </div>
        </form>

    </div>
</div>
<script>
    $(document).ready(function () {
        $('.edit-ingredient-btn').click(function () {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const action = $(this).data('action');

            $('#ingredientName').val(name);
            $('#ingredientForm').attr('action', action);
            $('#formMethod').val('PUT'); // Set method to PUT
            $('#ingredientModalLabel').text('Edit Ingredient');
            $('#ingredientSaveBtn').text('Update');

            $('#ingredientModal').modal('show');
        });

        // Optional: Reset modal when it's closed
        $('#ingredientModal').on('hidden.bs.modal', function () {
            $('#ingredientForm').attr('action', "{{ route('ingredients.store') }}");
            $('#formMethod').val('POST');
            $('#ingredientName').val('');
            $('#ingredientModalLabel').text('Add New Ingredient');
            $('#ingredientSaveBtn').text('Save');
        });
    });
</script>

@stop
