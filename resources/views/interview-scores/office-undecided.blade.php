@extends('layouts.app')
@section('title', 'Kandidat Belum Diputuskan')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-3 text-center" style="font-size:1.5rem; font-weight:700; color: var(--bluedark-color);">Kandidat Belum Diputuskan</h3>
        </div>
        <div class="card-body">
            @if($scores->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Semua kandidat telah diputuskan
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light fw-bold">
                            <tr>
                                <th>No</th>
                                <th>Kandidat</th>
                                <th>Tanggal Interview</th>
                                <th>Score</th>
                                <th>Rate</th>
                                <th>Kategori</th>
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
                                    <span class="badge" style="background-color: #30318B; color:white;">{{ $score->final_score }}</span>
                                </td>
                                <td>
                                    @php
                                        // Ambil hanya 1 kelebihan dan 1 kekurangan
                                        $highest = $score->getHighestScoringAspects(1);
                                        $lowest = $score->getLowestScoringAspects(1);
                                    @endphp
                                    <div class="aspect-chart">
                                        <div class="strength-header">
                                            <i class="fas fa-bolt header-icon"></i>
                                            <span>Kelebihan</span>
                                        </div>
                                        @foreach($highest as $aspect => $value)
                                            <div class="strength-bar">
                                                <div class="strength-fill" style="width: {{ ($value/9)*100 }}%">
                                                    <span class="aspect-label">{{ $aspect }}</span>
                                                    
                                                </div>
                                                <span class="aspect-score">{{ $value }}/9</span>
                                            </div>
                                        @endforeach
                                        
                                        <div class="weakness-header">
                                            <i class="fas fa-exclamation-triangle header-icon"></i>
                                            <span>Perlu Perbaikan</span>
                                        </div>
                                        @foreach($lowest as $aspect => $value)
                                            <div class="weakness-bar">
                                                <div class="weakness-fill" style="width: {{ ($value/9)*100 }}%">
                                                    <span class="aspect-label">{{ $aspect }}</span>
                                                    
                                                </div>
                                                <span class="aspect-score">{{ $value }}/9</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        {{ $score->final_category }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">Belum Diputuskan</span>
                                </td>
                                <td>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-warning bg-warning"
                                            style="color:#000;" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editDecisionModal"
                                            data-score-id="{{ $score->id }}"
                                            data-current-decision="{{ $score->decision }}">
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
                form.querySelector(`select[name="decision"] option[value="${currentDecision}"]`).selected = true;
            }
        });
    }
});
</script>

<style>
.aspect-chart {
    font-size: 0.85rem;
}

.strength-header, .weakness-header {
    display: flex;
    align-items: center;
    margin: 5px 0;
    font-weight: bold;
}

.strength-header {
    color: #28a745;
}

.weakness-header {
    color: #dc3545;
}

.header-icon {
    margin-right: 5px;
}

.strength-bar, .weakness-bar {
    height: 20px;
    background-color: #f8f9fa;
    border-radius: 3px;
    margin-bottom: 8px;
    position: relative;
}

.strength-fill {
    height: 100%;
    background-color: rgba(40, 167, 69, 0.3);
    border-radius: 3px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 5px;
}

.weakness-fill {
    height: 100%;
    background-color: rgba(220, 53, 69, 0.3);
    border-radius: 3px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 5px;
}

.aspect-label {
    font-weight: bold;
}

.aspect-score {
    font-size: 0.75rem;
}
</style>
@endsection