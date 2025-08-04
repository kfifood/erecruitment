@extends('layouts.app')

@section('title', 'Applicant List')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Daftar Pelamar</h3>

    <!-- Tambahkan di bagian atas content (setelah <h3>) -->
<div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> <!-- Ubah ke modal-xl untuk ukuran extra large -->
        <div class="modal-content">
            <div class="modal-header bg-white text-black">
                <h5 class="modal-title" id="editStatusModalLabel">Edit Status Lamaran</h5>
                <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBodyContent">
                <!-- Konten akan diisi via AJAX -->
            </div>
        </div>
    </div>
</div>
    
        <div class="card-body">
            <div class="table-responsive-container">
                <table id="applicationsTable" class="table table-bordered table-hover" style="margin-top: 30px;">
                    <thead class="thead-light">
                    <tr>
                        <th>Foto</th>
                        <th>Nama Lengkap</th>
                        <th>Posisi Dilamar</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Pendidikan</th>
                        <th>Jurusan</th>
                        <th>Prodi</th>
                        <th>Status</th>
                        <th>Interview Status</th>
                        <th>Tanggal Lamar</th>
                        <th>CV</th>
                        <th>Cover Letter</th>
                        <th>Edit Status</th>
                        <th>Aksi</th>
                        <th>Cetak</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                        <tr>
                            <td>
                                @if($application->photo)
                                    <img src="{{ Storage::url($application->photo) }}" alt="Foto Pelamar" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <span class="text-muted">No photo</span>
                                @endif
                            </td>
                            <td>{{ $application->full_name }}</td>
                            <td>{{ $application->job->position }}</td>
                            <td>{{ $application->email }}</td>
                            <td>{{ $application->phone }}</td>
                            <td>{{ $application->education }}</td>
                            <td>{{ $application->major ?? '-' }}</td>
                            <td>{{ $application->study_program ?? '-' }}</td>
                            <td>
                                <span class="badge 
                                    @if($application->status == 'submitted') bg-secondary
                                    @elseif($application->status == 'interview') bg-primary
                                    @else bg-danger @endif">
                                {{ ucfirst($application->status) }}
                                </span>
                            </td>
                            <td>
                                @if($application->interview_status)
                                    <span class="badge 
                                        @if($application->interview_status == 'interviewed') bg-success
                                        @else bg-warning text-dark @endif">
                                    {{ ucfirst(str_replace('_', ' ', $application->interview_status)) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">N/A</span>
                                @endif
                            </td>
                            <td>{{ $application->submitted_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ Storage::url($application->cv) }}" 
                                    target="_blank"
                                    title="Download CV"
                                    class="text-primary">
                                    {{ basename($application->cv) }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ Storage::url($application->cover_letter) }}" 
                                    target="_blank"
                                    title="Download Cover Letter"
                                    class="text-primary">
                                    {{ basename($application->cover_letter) }}
                                </a>
                            </td>
                            <td>
                                <!-- Ubah link edit menjadi button dengan class edit-status-btn -->
                                <button class="btn btn-sm btn-warning edit-status-btn" 
                                        data-id="{{ $application->id }}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editStatusModal">
                                    <i class="fas fa-edit"></i> Edit Status
                                </button>
                            </td>
                            <td>
                                <form action="{{ route('applications.destroy', $application->id) }}" 
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus aplikasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('applications.preview', $application->id) }}" 
                                        class="btn btn-sm btn-info"
                                                title="Preview PDF"
                                            target="_blank">
                                        <i class="fas fa-eye"></i> Preview
                                    </a>
                                    <a href="{{ route('applications.download', $application->id) }}" 
                                        class="btn btn-sm btn-primary"
                                                title="Download PDF">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>    
</div>
@endsection
@push('styles')
<style>
    /* Perbaikan tampilan modal */
    #editStatusModal .modal-dialog {
        max-width: 90%;
        margin: 1.75rem auto;
    }
    
    #editStatusModal .form-label {
        margin-bottom: 0.3rem;
    }
    
    #editStatusModal .form-control, 
    #editStatusModal .form-select {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
    
    /* Responsive untuk mobile */
    @media (max-width: 768px) {
        #editStatusModal .modal-dialog {
            max-width: 95%;
        }
        
        #editStatusModal .col-md-2,
        #editStatusModal .col-md-10 {
            width: 100%;
        }
    }
</style>
@endpush
@push('scripts')
<script>
$(document).ready(function() {
    // Ketika tombol edit diklik
    $('.edit-status-btn').click(function() {
        var applicationId = $(this).data('id');
        var url = "{{ route('applications.edit', ':id') }}".replace(':id', applicationId);
        
        // Tambahkan header AJAX untuk request modal
        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(data) {
                $('#modalBodyContent').html(data);
            },
            error: function(xhr) {
                $('#modalBodyContent').html(`
                    <div class="alert alert-danger">
                        <p>Gagal memuat form. Error ${xhr.status}: ${xhr.statusText}</p>
                        <a href="${url}" class="btn btn-warning">Coba buka di halaman baru</a>
                    </div>
                `);
                console.error('Error:', xhr.responseText);
            }
        });
    });


    // Handle submit form dalam modal
    $(document).on('submit', '#editStatusForm', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#editStatusModal').modal('hide');
                window.location.reload(); // Reload untuk melihat perubahan
            },
            error: function(xhr) {
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        });
    });
});
</script>
@endpush