<!-- resources/views/interview-scores/production-unscored.blade.php -->
@extends('layouts.app')
@section('title', 'Kandidat Produksi Belum Dinilai')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-3 text-center" style="font-size:1.5rem; font-weight:700; color: var(--bluedark-color);">
                Kandidat Produksi Belum Dinilai
            </h3>
        </div>
        <div class="card-body">
            @if($interviews->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Tidak ada kandidat produksi yang belum dinilai
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th>No</th>
                                <th>Kandidat</th>
                                <th>Posisi</th>
                                <th>Interviewer</th>
                                <th>Tanggal Interview</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($interviews as $interview)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $interview->application->full_name }}</td>
                                <td>{{ $interview->application->job->position }}</td>
                                <td>
                                    @if($interview->interviewer && $interview->interviewer->user)
                                        {{ $interview->interviewer->user->name }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $interview->interview_date->format('d/m/Y') }}</td>
                                <td><span class="badge bg-warning text-dark">Belum Dinilai</span></td>
                                <td>
                                    <button class="btn btn-sm btn-primary score-production-btn" 
                                            data-interview-id="{{ $interview->id }}"
                                            data-candidate="{{ $interview->application->full_name }}"
                                            data-position="{{ $interview->application->job->position }}">
                                        <i class="fas fa-plus"></i> Beri Nilai
                                    </button>
                                    
                                    <form action="{{ route('interviews.destroy', $interview->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data interview ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">
                        {{ $interviews->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal for Production Scoring -->
<div class="modal fade" id="productionScoreModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Penilaian Kandidat Produksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="productionScoreForm" method="POST">
                @csrf
                <input type="hidden" name="interview_id" id="modalInterviewId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kandidat</label>
                        <p id="modalCandidateName" class="form-control-plaintext"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Posisi</label>
                        <p id="modalPosition" class="form-control-plaintext"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rekomendasi</label>
                        <select name="recommendation" class="form-select" required>
                            <option value="">-- Pilih Rekomendasi --</option>
                            <option value="recommended">Disarankan</option>
                            <option value="considered">Cukup Disarankan</option>
                            <option value="not_recommended">Tidak Disarankan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Handle production scoring button click
    $('.score-production-btn').click(function() {
        const interviewId = $(this).data('interview-id');
        const candidateName = $(this).data('candidate');
        const position = $(this).data('position');
        
        $('#modalInterviewId').val(interviewId);
        $('#modalCandidateName').text(candidateName);
        $('#modalPosition').text(position);
        $('#productionScoreForm').attr('action', '{{ route("interview-scores.store-production") }}');
        
        $('#productionScoreModal').modal('show');
    });

    // Handle form submission
    $('#productionScoreForm').submit(function(e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalBtnText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Menyimpan...
        `);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#productionScoreModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Penilaian berhasil disimpan',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Error', response.message || 'Gagal menyimpan', 'error');
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
});
</script>
@endpush
@endsection