<!-- resources/views/interview-scores/production-undecided.blade.php -->
@extends('layouts.app')
@section('title', 'Kandidat Produksi Belum Diputuskan')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-3 text-center" style="font-size:1.5rem; font-weight:700; color: var(--bluedark-color);">
                Kandidat Produksi Belum Diputuskan</h3>
        </div>
        <div class="card-body">
            @if($scores->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> Semua kandidat produksi telah diputuskan
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light fw-bold">
                        <tr>
                            <th>No</th>
                            <th>Kandidat</th>
                            <th>Tanggal Interview</th>
                            <th>Rekomendasi</th>
                            <th>Keputusan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($scores as $score)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $score->interview->application->full_name }}</td>
                            <td>{{ $score->interview->interview_date->format('d/m/Y') }}</td>
                            <td>
                                @php
                                // Definisikan array rekomendasi
                                $recommendationOptions = [
                                'recommended' => 'Disarankan',
                                'considered' => 'Cukup Disarankan',
                                'not_recommended' => 'Tidak Disarankan'
                                ];

                                // Definisikan array class badge
                                $badgeClasses = [
                                'recommended' => 'bg-success',
                                'considered' => 'bg-warning text-dark',
                                'not_recommended' => 'bg-danger'
                                ];

                                // Ambil nilai rekomendasi, default ke 'considered' jika kosong
                                $recommendationValue = $score->recommendation ?: 'considered';

                                // Dapatkan teks rekomendasi dan class badge
                                $recommendationText = $recommendationOptions[$recommendationValue] ?? 'Cukup
                                Disarankan';
                                $badgeClass = $badgeClasses[$recommendationValue] ?? 'bg-warning text-dark';
                                @endphp

                                <span class="badge {{ $badgeClass }}">{{ $recommendationText }}</span>

                            </td>
                            <td>
                                <span class="badge bg-secondary">Belum Diputuskan</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-warning bg-warning"
                                    style="color:#000;" data-bs-toggle="modal" data-bs-target="#editDecisionModal"
                                    data-score-id="{{ $score->id }}" data-current-decision="{{ $score->decision }}">
                                    <i class="fas fa-edit"></i> Putuskan
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3">
                    {{ $scores->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Edit Decision -->
<div class="modal fade" id="editDecisionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Keputusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="decisionForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Decision</label>
                        <select name="decision" class="form-select" required>
                            <option value="">-- Pilih Keputusan --</option>
                            <option value="hired">Diterima (Hired)</option>
                            <option value="unhired">Tidak Diterima (Unhired)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn" style="background-color:#30318B; color:white;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editDecisionModal = document.getElementById('editDecisionModal');

    if (editDecisionModal) {
        editDecisionModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const scoreId = button.getAttribute('data-score-id');
            const currentDecision = button.getAttribute('data-current-decision');

            const form = document.getElementById('decisionForm');
            form.action = `/interview-scores/${scoreId}/decision`;

            if (currentDecision) {
                form.querySelector(`select[name="decision"] option[value="${currentDecision}"]`)
                    .selected = true;
            }
        });
    }
});
</script>
@endsection