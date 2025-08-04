@extends('layouts.app')

@section('title', 'Employees')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Employees</h3>
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEmployeeModal">
            <i class="fas fa-plus"></i> Add New
        </a>
    </div>

    <table id="employeesTable" class="table table-bordered table-hover">
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Position</th>
                <th>Division</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td>{{ $employee->id }}</td>
                <td>{{ $employee->user->name }}</td>
                <td>{{ $employee->position }}</td>
                <td>
                @if($employee->user->division)
                    {{ ucwords(str_replace('_', ' ', $employee->user->division)) }}
                @else
                    -
                @endif
            </td>
                <td>{{ $employee->phone ?? '-' }}</td>
                <td>
                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                       data-bs-target="#editEmployeeModal{{ $employee->id }}" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" 
      class="d-inline" onsubmit="confirmDelete(event, this)">
    @csrf
    @method('DELETE')
    <button class="btn btn-sm btn-danger" title="Delete">
        <i class="fas fa-trash"></i>
    </button>
</form>
                </td>
            </tr>
            <!-- Edit Modal for each employee -->
@foreach($employees as $employee)
<div class="modal fade" id="editEmployeeModal{{ $employee->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('employees.update', $employee->id) }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" value="{{ $employee->user->name }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Division</label>
                        <input type="text" class="form-control" id="editDivisionDisplay{{ $employee->id }}" 
                               value="{{ $employee->user->division }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" class="form-control" name="position" 
                               value="{{ $employee->position }}" required>
                    </div>
                    <!-- Field lainnya -->
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
            @endforeach
        </tbody>
    </table>
</div>

<!-- Create Employee Modal -->
<div class="modal fade" id="createEmployeeModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('employees.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Employee</label>
                        <select class="form-select" name="user_id" id="userSelect" required>
                            <option value="">Select Employee</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" data-division="{{ $user->division }}">
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Division</label>
                        <input type="text" class="form-control" id="divisionDisplay" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" class="form-control" name="position" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" name="address" rows="3"></textarea>
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
// SweetAlert for success messages
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

// Show division when selecting user
document.getElementById('userSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const division = selectedOption.getAttribute('data-division');
    document.getElementById('divisionDisplay').value = division ? 
        division.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) : '';
});

</script>

@endpush