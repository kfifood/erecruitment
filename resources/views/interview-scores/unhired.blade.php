@extends('layouts.app')
@section('title', 'Kandidat Ditolak')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-3 text-center" style="font-size:1.5rem; font-weight:700; color: var(--bluedark-color);">Kandidat Ditolak (Unhired)</h3>
        </div>
        <div class="card-body">
            @if($scores->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Tidak ada kandidat yang ditolak
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
                                <th>Hasil</th>
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
                                                    <span class="aspect-score">{{ $value }}/9</span>
                                                </div>
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
                                                    <span class="aspect-score">{{ $value }}/9</span>
                                                </div>
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
                                    <span class="badge bg-danger">
                                        Tidak Diterima
                                    </span>
                                </td>
                                <td>
                                    @if($score->is_result_sent)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Terkirim
                                            <br>
                                            <small>{{ $score->result_sent_at->format('d/m/Y H:i') }}</small>
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Belum dikirim</span>
                                    @endif
                                </td>
<td>
    @if(!$score->is_result_sent)
        <button type="button" 
                class="btn btn-sm btn-success"
                onclick="confirmSendResult({{ $score->id }}, '{{ $score->decision }}')">
            <i class="fab fa-whatsapp"></i> Kirim
        </button>
    @endif
    <a href="{{ route('interview-scores.unhired-detail', $score->id) }}" target="_blank" class="btn btn-sm btn-info">
        <i class="fas fa-eye"></i> Detail
    </a>
    <form action="{{ route('interview-scores.destroy', $score->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data penilaian ini?')">
            <i class="fas fa-trash"></i>
        </button>
    </form>
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

<form id="sendResultForm" method="POST" action="">
    @csrf
</form>

<script>
function confirmSendResult(scoreId, decision) {
    if (confirm(`Anda akan mengirim hasil TIDAK DITERIMA ke kandidat. Lanjutkan?`)) {
        document.getElementById('sendResultForm').action = `/interview-scores/${scoreId}/send-result`;
        document.getElementById('sendResultForm').submit();
    }
}
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