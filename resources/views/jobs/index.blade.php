@extends('layouts.app')

@section('title', 'Job Positions')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Job Positions</h3>
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createJobModal">
            <i class="fas fa-plus"></i> Add New Job Post
        </a>
    </div>

    <table id="jobsTable" class="table table-bordered table-hover">
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Position</th>
                <th>Division</th>
                <th>Location</th>
                <th>Address</th>
                <th>Experience</th>
                <th>Education</th>
                <th>Status Job Post</th>
                <th>Max. Age</th>
                <th>Gender</th>
                <th>Recruitment Type</th>
                <th>Posted Date</th>
                <th>Closing Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jobs as $job)
            <tr>
                <td>{{ $job->id }}</td>
                <td>{{ $job->position }}</td>
                <td>{{ $job->division->name ?? '-' }}</td>
                <td>{{ $job->location }}</td>
                <td>{{ $job->address ? Str::limit($job->address, 30) : '-' }}</td>
                <td>
                    @if(!is_null($job->experience))
                    {{ $job->experience }} tahun
                    @else
                    -
                    @endif
                </td>
                <td>
                    @if($job->educations->isNotEmpty())
                    {{ $job->educations->pluck('level')->implode(', ') }}
                    @else
                    -
                    @endif
                </td>
                <td>
                    <span class="badge bg-{{ $job->is_active ? 'success' : 'secondary' }}">
                        {{ $job->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>{{ $job->usia ?? '-' }}</td>
                <td>
                    @foreach(explode(',', $job->gender) as $gender)
                    {{ ucfirst($gender) }}@if(!$loop->last), @endif
                    @endforeach
                </td>
                <td>
                    @foreach(explode(',', $job->recruitment_type) as $type)
                    {{ ucfirst($type) }}@if(!$loop->last), @endif
                    @endforeach
                </td>
                <td>{{ $job->posted_date ? $job->posted_date->format('d M Y') : '-' }}</td>
                <td>{{ $job->closing_date ? $job->closing_date->format('d M Y') : '-' }}</td>
                <td>
                    <a href="{{ route('jobs.show.public', $job->id) }}" class="btn btn-sm btn-info text-white"
                        title="View Details" target="_blank">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                        data-bs-target="#editJobModal{{ $job->id }}" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" class="d-inline"
                        onsubmit="confirmDelete(event, this)">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>



            <!-- Edit Modal -->
            <div class="modal fade" id="editJobModal{{ $job->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ route('jobs.update', $job->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Job Position</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body row">
                                <div class="mb-3 col-md-6">
                                    <label for="position{{ $job->id }}" class="form-label">Position</label>
                                    <input type="text" class="form-control" name="position" value="{{ $job->position }}"
                                        required>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="division_id{{ $job->id }}" class="form-label">Division</label>
                                    <select class="form-select" name="division_id">
                                        <option value="">Select Division</option>
                                        @foreach($divisions as $division)
                                        <option value="{{ $division->id }}"
                                            {{ $job->division_id == $division->id ? 'selected' : '' }}>
                                            {{ $division->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="location{{ $job->id }}" class="form-label">Location</label>
                                    <input type="text" class="form-control" name="location"
                                        value="PT Kirana Food International" readonly>
                                </div>
                                <div class="mb-3 col-12">
                                    <label for="address{{ $job->id }}" class="form-label">Address</label>
                                    <textarea class="form-control" id="address{{ $job->id }}" name="address" rows="2"
                                        readonly>Kab. Tuban, Jawa Timur</textarea>
                                    <small class="text-muted">Regency of Company address</small>
                                </div>

                                <div class="mb-3 col-12">
                                    <label for="full_address{{$job->id}}" class="form-label">Address</label>
                                    <textarea class="form-control" id="full_address{{ $job->id }}" name="full_address"
                                        rows="2"
                                        readonly>Jalan Raya Pakah-Ponco Km. 15+700, Sumberagung, Kec. Plumpang, Kab. Tuban </textarea>
                                    <small class="text-muted">Detailed address of the job location</small>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="is_active{{ $job->id }}" class="form-label">Status</label>
                                    <select class="form-select" name="is_active" required>
                                        <option value="1" {{ $job->is_active ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ !$job->is_active ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <!-- Field baru untuk edit -->
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Maximum Age</label>
                                    <input type="number" class="form-control" name="usia" min="18" max="60"
                                        value="{{ $job->usia ?? old('usia') }}">
                                    <small class="text-muted">Maximum age are allowed</small>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Gender</label>
                                    <div>
                                        @php
                                        $selectedGenders = explode(',', $job->gender);
                                        @endphp
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="gender[]"
                                                id="edit_gender_pria_{{ $job->id }}" value="pria"
                                                {{ in_array('pria', $selectedGenders) ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="edit_gender_pria_{{ $job->id }}">Pria</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="gender[]"
                                                id="edit_gender_wanita_{{ $job->id }}" value="wanita"
                                                {{ in_array('wanita', $selectedGenders) ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="edit_gender_wanita_{{ $job->id }}">Wanita</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Tipe Rekrutmen</label>
                                    <div>
                                        @php
                                        $selectedTypes = explode(',', $job->recruitment_type);
                                        @endphp
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="recruitment_type[]"
                                                id="edit_type_production_{{ $job->id }}" value="production"
                                                {{ in_array('production', $selectedTypes) ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="edit_type_production_{{ $job->id }}">Production</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="recruitment_type[]"
                                                id="edit_type_office_{{ $job->id }}" value="office"
                                                {{ in_array('office', $selectedTypes) ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="edit_type_office_{{ $job->id }}">Office</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 col-12">
                                    <label for="experience{{ $job->id }}" class="form-label">Experience</label>
                                    <input type="number" class="form-control" name="experience" min="0" max="50"
                                        step="1" value="{{ $job->experience ?? old('experience') }}">
                                    <small class="text-muted">Number of years required</small>
                                </div>

                                <div class="mb-3 col-12">
                                    <label class="form-label">Education Requirements</label>
                                    <div class="education-checkboxes">
                                        @foreach(['SMP', 'SMA', 'SMK', 'D3', 'D4', 'S1', 'S2'] as $level)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="education_levels[]"
                                                id="edu_{{ $level }}{{ $job->id }}" value="{{ $level }}"
                                                {{ $job->educations->contains('level', $level) ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="edu_{{ $level }}{{ $job->id }}">{{ $level }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="posted_date{{ $job->id }}" class="form-label">Posted Date</label>
                                    <input type="date" class="form-control" name="posted_date"
                                        value="{{ $job->posted_date ? $job->posted_date->format('Y-m-d') : '' }}">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="closing_date{{ $job->id }}" class="form-label">Closing Date</label>
                                    <input type="date" class="form-control" name="closing_date"
                                        value="{{ $job->closing_date ? $job->closing_date->format('Y-m-d') : '' }}">
                                </div>
                                <div class="mb-3 col-12">
                                    <label for="qualification{{ $job->id }}" class="form-label">Qualification &
                                        Requirements</label>
                                    <textarea class="form-control rich-text-editor" id="qualification{{ $job->id }}"
                                        name="qualification" rows="5">{{ $job->qualification }}</textarea>
                                    <small class="text-muted">Use bullet points or numbering for requirements</small>
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

<!-- Create Job Modal -->
<div class="modal fade" id="createJobModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('jobs.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Job Position</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row">
                    <div class="mb-3 col-md-6">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" class="form-control" name="position" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="division_id" class="form-label">Division</label>
                        <select class="form-select" name="division_id">
                            <option value="">Select Division</option>
                            @foreach($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" name="location" value="PT Kirana Food International"
                            readonly>
                    </div>

                    <div class="mb-3 col-12">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2"
                            readonly>Kab. Tuban, Jawa Timur</textarea>
                        <small class="text-muted">Regency of Company address</small>
                    </div>
                    <div class="mb-3 col-12">
                        <label for="full_address" class="form-label">Full Address</label>
                        <textarea class="form-control" id="full_address" name="full_address" rows="2"
                            readonly>Jalan Raya Pakah-Ponco Km. 15+700, Sumberagung, Kec. Plumpang, Kab. Tuban  </textarea>
                        <small class="text-muted">Detailed address of the job location</small>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="is_active" class="form-label">Status</label>
                        <select class="form-select" name="is_active" required>
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class="form-label">Maximum Age</label>
                        <input type="number" class="form-control" name="usia" min="18" max="60"
                            value="{{ old('usia') }}">
                        <small class="text-muted">Maximum Age are allowed</small>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label">Gender</label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="gender[]" id="create_gender_pria"
                                    value="pria">
                                <label class="form-check-label" for="create_gender_pria">Pria</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="gender[]"
                                    id="create_gender_wanita" value="wanita">
                                <label class="form-check-label" for="create_gender_wanita">Wanita</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label">Tipe Rekrutmen</label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="recruitment_type[]"
                                    id="create_type_production" value="production">
                                <label class="form-check-label" for="create_type_production">Production</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="recruitment_type[]"
                                    id="create_type_office" value="office">
                                <label class="form-check-label" for="create_type_office">Office</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 col-12">
                        <label for="experience" class="form-label">Experience</label>
                        <input type="number" class="form-control" name="experience" min="0" max="50" step="1"
                            value="{{ $job->experience ?? old('experience') }}">
                        <small class="text-muted">Number of years required</small>
                    </div>

                    <div class="mb-3 col-12">
                        <label class="form-label">Education Requirements</label>
                        <div class="education-checkboxes">
                            @foreach(['SMP', 'SMA', 'SMK', 'D3', 'D4', 'S1', 'S2'] as $level)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="education_levels[]"
                                    id="edu_{{ $level }}" value="{{ $level }}">
                                <label class="form-check-label" for="edu_{{ $level }}">{{ $level }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="posted_date" class="form-label">Posted Date</label>
                        <input type="date" class="form-control" name="posted_date">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="closing_date" class="form-label">Closing Date</label>
                        <input type="date" class="form-control" name="closing_date">
                    </div>
                    <div class="mb-3 col-12">
                        <label for="qualification" class="form-label">Qualification & Requirements</label>
                        <textarea class="form-control rich-text-editor" id="qualification" name="qualification"
                            rows="5"></textarea>
                        <small class="text-muted">Use bullet points or numbering for requirements</small>
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
<link href="https://cdn.jsdelivr.net/npm/tinymce@5.10.0/dist/skin/ui/oxide/content.min.css" rel="stylesheet">
<style>
.tox-tinymce {
    border-radius: 0.375rem !important;
    border: 1px solid #ced4da !important;
}

.tox .tox-editor-header {
    background-color: #f8f9fa !important;
    border-bottom: 1px solid #ced4da !important;
}

.card-header h5 {
    font-weight: bold;
}

.text-muted {
    font-size: 0.85rem;
    color: #6c757d !important;
}

.modal-body .card {
    border: 1px solid rgba(0, 0, 0, .125);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, .125);
}
</style>
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

<!-- TinyMCE -->
<script src="https://cdn.jsdelivr.net/npm/tinymce@5.10.0/tinymce.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    // SweetAlert for success messages
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('
        success ') }}',
        timer: 3000,
        showConfirmButton: false
    });
    @endif

    // SweetAlert for error messages
    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session('
        error ') }}'
    });
    @endif

    // Delete confirmation with SweetAlert
    function confirmDelete(event, form) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this job position deletion!",
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

    // Initialize TinyMCE for create modal
    tinymce.init({
        selector: '#createJobModal .rich-text-editor',
        plugins: 'lists link paste help wordcount',
        toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        menubar: false,
        height: 250,
        paste_as_text: true,
        content_style: "body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 14px; }",
        setup: function(editor) {
            editor.on('change', function() {
                editor.save();
            });
        }
    });

    // Initialize TinyMCE when edit modals are shown
    $('.modal').on('shown.bs.modal', function() {
        tinymce.init({
            selector: '.rich-text-editor',
            plugins: 'lists link paste help wordcount',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            menubar: false,
            height: 250,
            paste_as_text: true,
            content_style: "body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 14px; }",
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            }
        });
    });

    // Remove TinyMCE instances when modals are hidden
    $('.modal').on('hidden.bs.modal', function() {
        tinymce.remove();
    });
});
</script>
@endpush