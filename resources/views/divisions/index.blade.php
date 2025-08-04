@extends('layouts.app')

@section('title', 'Divisions')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Divisions</h3>
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDivisionModal">
            <i class="fas fa-plus"></i> Add New
        </a>
    </div>

    <table id="divisionsTable" class="table table-bordered table-hover">
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($divisions as $division)
            <tr>
                <td>{{ $division->id }}</td>
                <td>{{ $division->name }}</td>
                <td>{{ $division->description }}</td>
                <td>
                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                       data-bs-target="#editDivisionModal{{ $division->id }}" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('divisions.destroy', $division->id) }}" method="POST" 
                        class="d-inline" onsubmit="confirmDelete(event, this)">
                            @csrf
                            @method('DELETE')
                        <button class="btn btn-sm btn-danger" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>

            <!-- Edit Modal for each division -->
            <div class="modal fade" id="editDivisionModal{{ $division->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('divisions.update', $division->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Division</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" 
                                           value="{{ $division->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3">{{ $division->description }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Create Division Modal -->
<div class="modal fade" id="createDivisionModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('divisions.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Division</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
@endpush

@push('scripts')
<!-- DataTables & Buttons -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script>
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

// SweetAlert for error messages
@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session('error') }}'
    });
@endif

// Delete confirmation with SweetAlert
function confirmDelete(event, form) {
    event.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}
</script>
@endpush