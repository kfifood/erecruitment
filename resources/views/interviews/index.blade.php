@extends('layouts.app')

@section('title', 'Interview Schedules')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="title-page">Interview Schedules</h3>
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#interviewModal">
            <i class="fas fa-plus"></i> Add New Interview
        </a>
    </div>


    <table class="table table-bordered table-hover">
        <thead class="thead-light">
            <tr>
                <th style="display: none;">Dummy</th>
                <th >Date</th>
                <th>Candidate</th>
                <th>Position</th>
                <th>Time</th>
                <th>Send Invitation</th>
                <th>Security Notif</th>
                <th>Action</th>
                <th>Interview Status</th>
            </tr>
        </thead>
<tbody>
    @foreach($interviews as $date => $items)
        @foreach($items as $index => $interview)
        <tr>
            <td style="display: none;">{{ $interview->id }}</td>
            <!-- Kolom 1: Tanggal (rowspan hanya di baris pertama) -->
            @if($index === 0)
                <td rowspan="{{ $items->count() }}" class="align-middle bg-white">
                    <div class="fw-bold">{{ $interview->interview_date->format('d M Y') }}</div>
                    @if($items->contains('method', 'offsite'))
                        <form action="{{ route('interviews.notify-security', $date) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-info w-100" 
                                {{ $items[0]->isSecurityNotified() ? 'disabled' : '' }}>
                                <i class="fas fa-bell"></i>
                                {{ $items[0]->isSecurityNotified() ? 'Terkirim' : 'Kirim Satpam' }}
                            </button>
                        </form>
                    @endif
                </td>
            @endif
            
            <!-- Kolom 2: Kandidat -->
            <td>{{ $interview->application->full_name }}</td>
            
            <!-- Kolom 3: Posisi -->
            <td>{{ $interview->application->job->position }}</td>
            
            <!-- Kolom 4: Waktu Interview -->
            <td>{{ $interview->interview_time->format('H:i') }}</td>
            
            <!-- Kolom 5: Kirim Undangan -->
            <td>
                <form action="{{ route('interviews.send-invitation', $interview->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm {{ $interview->invitation_sent_at ? 'btn-success' : 'btn-outline-success' }}"
                        {{ !$interview->application->phone ? 'disabled' : '' }}>
                        <i class="fab fa-whatsapp"></i>
                        {{ $interview->invitation_sent_at ? 'Terkirim' : 'Kirim' }}
                    </button>
                </form>
            </td>
            
            <!-- Kolom 6: Notifikasi Security -->
            <td class="text-center">
                @if($index === 0 && $items->contains('method', 'offsite'))
                    @if($items[0]->isSecurityNotified())
                        <span class="badge bg-success p-2 text-white">
                            <i class="fas fa-check-circle"></i> Terkirim
                        </span>
                    @else
                        <span class="badge bg-warning p-2 text-dark">
                            <i class="fas fa-clock"></i> Pending
                        </span>
                    @endif
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            
            <!-- Kolom 7: Aksi -->
            <td>
                <button class="btn btn-sm btn-warning edit-btn" 
                    data-bs-toggle="modal" 
                    data-bs-target="#editInterviewModal"
                    data-id="{{ $interview->id }}"
                    title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                <form action="{{ route('interviews.destroy', $interview->id) }}" 
                      method="POST" 
                      class="d-inline" 
                      onsubmit="return confirm('Yakin ingin menghapus?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </td>
            
            <!-- Kolom 8: Status Interview -->
            <td id="interview-status-{{ $interview->id }}">
                @if($interview->interview_status === 'interviewed')
                    <span class="badge bg-success">Interviewed</span>
                @else
                    <button class="btn btn-sm btn-primary mark-interviewed-btn" 
                        data-interview-id="{{ $interview->id }}"
                        id="mark-btn-{{ $interview->id }}">
                        <i class="fas fa-check"></i> Tandai Selesai
                    </button>
                @endif
            </td>
        </tr>
        @endforeach
    @endforeach
</tbody>
    </table>
</div>

<!-- Create Interview Modal -->
<div class="modal fade" id="interviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Interview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="interviewForm" method="POST" action="{{ route('interviews.store') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="application_id" class="form-label">Candidate</label>
                                <select name="application_id" id="application_id" class="form-select" required>
                                    <option value="">Select Candidate</option>
                                    @foreach($applications as $app)
                                    <option value="{{ $app->id }}">
                                        {{ $app->full_name }} - {{ $app->job->position }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="interviewer_id" class="form-label">Interviewer</label>
                                <select name="interviewer_id" id="interviewer_id" class="form-select" required>
                                    <option value="">Select Interviewer</option>
                                    @foreach($interviewers as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->user->name }} ({{ $employee->position }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="interview_date" class="form-label">Interview Date</label>
                                <input type="date" name="interview_date" id="interview_date" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="interview_time" class="form-label">Time</label>
                                <input type="time" name="interview_time" id="interview_time" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="method" class="form-label">Method</label>
                                <select name="method" id="method" class="form-select" required>
                                    <option value="offsite">On-site</option>
                                    <option value="online">Online</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Interview Modal -->
<div class="modal fade" id="editInterviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Interview Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editInterviewForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editInterviewId" name="id">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Candidate</label>
                                <p id="editCandidateName" class="form-control-plaintext"></p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editInterviewerId" class="form-label">Interviewer</label>
                                <select name="interviewer_id" id="editInterviewerId" class="form-select" required>
                                    <option value="">Select Interviewer</option>
                                    @foreach($interviewers as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->user->name }} ({{ $employee->position }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="editInterviewDate" class="form-label">Interview Date</label>
                                <input type="date" name="interview_date" id="editInterviewDate" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="editInterviewTime" class="form-label">Time</label>
                                <input type="time" name="interview_time" id="editInterviewTime" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="editMethod" class="form-label">Method</label>
                                <select name="method" id="editMethod" class="form-select" required>
                                    <option value="offsite">On-site</option>
                                    <option value="online">Online</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editInterviewStatus" class="form-label">Interview Status</label>
                        <select name="interview_status" id="editInterviewStatus" class="form-select">
                            <option value="not yet">Not Yet</option>
                            <option value="interviewed">Interviewed</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editNotes" class="form-label">Notes</label>
                        <textarea name="notes" id="editNotes" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@endsection
@push('scripts')

<script>
    $(document).ready(function() {
        // Initialize modals
        const interviewModal = new bootstrap.Modal(document.getElementById('interviewModal'));
        const editInterviewModal = new bootstrap.Modal(document.getElementById('editInterviewModal'));

        // Reset form when create modal is closed
        document.getElementById('interviewModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('interviewForm').reset();
        });

        
        // Handle create form submission
        $('#interviewForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const formData = new FormData(this);
            const submitBtn = form.find('button[type="submit"]');
            const originalBtnText = submitBtn.html();

            // Validate required fields
            if (!$('#application_id').val() || !$('#interviewer_id').val() || 
                !$('#interview_date').val() || !$('#interview_time').val()) {
                Swal.fire('Error', 'Harap isi semua field yang wajib diisi!', 'error');
                return;
            }

            // Show loading
            submitBtn.prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Menyimpan...
            `);

            // Submit form via AJAX
            $.ajax({
                url: '/interviews',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        interviewModal.hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Jadwal interview berhasil dibuat',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error', response.message || 'Gagal membuat jadwal', 'error');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan saat menyimpan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire('Error', errorMessage, 'error');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalBtnText);
                }
            });
        });

        // Handle edit modal show event
       // Handle edit modal show event
document.getElementById('editInterviewModal').addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    const interviewId = button.getAttribute('data-id');
    
    // Show loading
    $(this).find('.modal-body').prepend(`
        <div class="loading-overlay">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat data interview...</p>
        </div>
    `);
    
    // Fetch interview data
    $.ajax({
        url: `/interviews/${interviewId}/edit`,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success && response.data) {
                const data = response.data;
                
                // Fill form
                $('#editInterviewId').val(data.id);
                $('#editCandidateName').text(
                    `${data.application.full_name} - ${data.application.job.position}`
                );
                $('#editInterviewerId').val(data.interviewer_id).trigger('change');
                $('#editInterviewDate').val(data.interview_date);
                $('#editInterviewTime').val(data.interview_time);
                $('#editMethod').val(data.method);
                $('#editInterviewStatus').val(data.interview_status);
                $('#editNotes').val(data.notes);
                
                // Set form action
                $('#editInterviewForm').attr('action', `/interviews/${interviewId}`);
            } else {
                editInterviewModal.hide();
                Swal.fire('Error', response.message || 'Gagal memuat data', 'error');
            }
        },
        error: function(xhr) {
            editInterviewModal.hide();
            Swal.fire('Error', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
        },
        complete: function() {
            $('.loading-overlay').remove();
        }
    });
});

        // Handle edit form submission
        $('#editInterviewForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const formData = new FormData(this);
            const url = form.attr('action');
            const submitBtn = form.find('button[type="submit"]');
            const originalBtnText = submitBtn.html();
            
            // Show loading
            submitBtn.prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Menyimpan...
            `);
            
            // Submit via AJAX
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        editInterviewModal.hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data interview berhasil diperbarui',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error', response.message || 'Gagal memperbarui', 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalBtnText);
                }
            });
        });

$(document).on('click', '.mark-interviewed-btn', function() {
    const interviewId = $(this).data('interview-id');
    const button = $(this);
    const statusCell = $(`#interview-status-${interviewId}`);
    
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Tandai interview ini sebagai sudah selesai?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Tandai',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Tampilkan loading
            button.html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
            button.prop('disabled', true);
            
            // Kirim request AJAX
            $.ajax({
                url: `/interviews/${interviewId}/mark-interviewed`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT'
                },
                success: function(response) {
                    if (response.success) {
                        // Update tampilan tanpa reload
                        statusCell.html('<span class="badge bg-success">Interviewed</span>');
                        
                        // Tampilkan notifikasi sukses
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Status interview telah diperbarui',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        button.html('<i class="fas fa-check"></i> Mark as Interviewed');
                        button.prop('disabled', false);
                        Swal.fire('Error', response.message || 'Gagal memperbarui status', 'error');
                    }
                },
                error: function(xhr) {
                    button.html('<i class="fas fa-check"></i> Mark as Interviewed');
                    button.prop('disabled', false);
                    
                    let errorMessage = 'Terjadi kesalahan saat memperbarui status';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    Swal.fire('Error', errorMessage, 'error');
                }
            });
        }
    });
});

        // SweetAlert for flash messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @elseif(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        // Confirmation for sending invitation
        $(document).on('submit', 'form[action*="send-invitation"]', function(e) {
            e.preventDefault();
            const form = this;
            
            Swal.fire({
                title: 'Kirim Undangan?',
                text: 'Anda yakin ingin mengirim undangan interview?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kirim',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Confirmation for notifying security
        $(document).on('submit', 'form[action*="notify-security"]', function(e) {
            e.preventDefault();
            const form = this;
            
            Swal.fire({
                title: 'Kirim Notifikasi?',
                text: 'Anda yakin ingin mengirim notifikasi ke satpam?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kirim',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
