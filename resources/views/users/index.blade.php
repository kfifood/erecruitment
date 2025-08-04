@extends('layouts.app')
@section('title', 'Users')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Users</h3>
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">+ Add New</a>
    </div>

    <table id="listUsersTable" class="table table-bordered table-hover">
        <thead class="thead-light">
            <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Role</th>
                <th>Division</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->username }}</td>
                <td>
                    <span class="badge bg-primary text-uppercase">{{ $user->role }}</span>
                </td>
                <td>
                    @php
                        $divisionNames = [
                            'management' => 'Management',
                            'finance_accounting' => 'Finance & Accounting',
                            'human_resources' => 'Human Resources',
                            'information_technology' => 'Information Technology',
                            'quality_assurance' => 'Quality Assurance',
                            'marketing' => 'Marketing',
                            'technic' => 'Technic',
                            'ppic' => 'PPIC',
                            'export_import' => 'EXIM',
                            'production' => 'Production'
                        ];
                    @endphp
                    <span style="color: black">{{ $divisionNames[$user->division] ?? $user->division }}</span>
                </td>
                <td>
                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}" title="Edit"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="confirmDelete(event, this)">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            <!-----------------Modal Edit form----------------->
<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="{{ route('users.update', $user->id) }}">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row">
          <div class="mb-3 col-md-6">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" value="{{ $user->username }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="password" class="form-label">Password (Kosongkan jika tidak diubah)</label>
            <input type="password" class="form-control" name="password" placeholder="Masukkan password baru">
          </div>
          <div class="mb-3 col-md-6">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" name="role" required>
              @foreach(['admin', 'employee'] as $role)
                <option value="{{ $role }}" {{ $user->role == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3 col-md-6">
            <label for="division" class="form-label">Division</label>
            <select class="form-select" name="division" required>
              @php
                  $divisions = [
                      'management' => 'Management',
                      'finance_accounting' => 'Finance & Accounting',
                      'human_resources' => 'Human Resources',
                      'information_technology' => 'Information Technology',
                      'quality_assurance' => 'Quality Assurance',
                      'marketing' => 'Marketing',
                      'technic' => 'Technic',
                      'ppic' => 'PPIC',
                      'export_import' => 'EXIM',
                      'production' => 'Production'
                  ];
              @endphp
              @foreach($divisions as $key => $value)
                <option value="{{ $key }}" {{ $user->division == $key ? 'selected' : '' }}>{{ $value }}</option>
              @endforeach
            </select>
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


<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="{{ route('users.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createUserModalLabel">Add New User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body row">
          <div class="mb-3 col-md-6">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          
          <div class="mb-3 col-md-6">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
              <option value="admin">Admin</option>
              <option value="employee">Employee</option>
            </select>
          </div>
          <div class="mb-3 col-md-6">
            <label for="division" class="form-label">Division</label>
            <select class="form-select" id="division" name="division" required>
              <option value="management">Management</option>
              <option value="finance_accounting">Finance & Accounting</option>
              <option value="human_resources">Human Resources</option>
              <option value="information_technology">Information Technology</option>
              <option value="quality_assurance">Quality Assurance</option>
              <option value="marketing">Marketing</option>
              <option value="technic">Technic</option>
              <option value="ppic">PPIC</option>
              <option value="export_import">EXIM</option>
              <option value="production">Production</option>
            </select>
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
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
@endpush

@push('scripts')
<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
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
$(document).ready(function () {
    $('#usersTable').DataTable({
        dom: '<"d-flex justify-content-between mb-3"<"d-flex"l><"d-flex"fB>>rt<"d-flex justify-content-between mt-3"<"d-flex"i><"d-flex"p>>',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm me-2'
            },
            {
                extend: 'csv',
                text: '<i class="fas fa-file-csv"></i> CSV',
                className: 'btn btn-secondary btn-sm me-2'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-info btn-sm me-2'
            },
            {
                text: '<i class="fas fa-search"></i> Reset',
                className: 'btn btn-warning btn-sm me-2',
                action: function (e, dt, node, config) {
                    dt.search('').columns().search('').draw();
                }
            },
            {
                text: '<i class="fas fa-sync-alt"></i> Reload',
                className: 'btn btn-primary btn-sm',
                action: function (e, dt, node, config) {
                    location.reload();
                }
            }
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "🔍 Search users..."
        }
    });
});
</script>
@endpush