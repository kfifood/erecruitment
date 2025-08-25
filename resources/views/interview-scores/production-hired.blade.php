<!-- resources/views/interview-scores/production-hired.blade.php -->
@extends('layouts.app')
@section('title', 'Kandidat Produksi Diterima')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-3 text-center" style="font-size:1.5rem; font-weight:700; color: var(--bluedark-color);">
                Kandidat Produksi Diterima (Hired)</h3>
        </div>
        <div class="card-body">
            @if($scores->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> Tidak ada kandidat produksi yang diterima
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
                                <span class="badge bg-success">Diterima</span>
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
                                <button type="button" class="btn btn-sm btn-success"
                                    onclick="confirmSendResult({{ $score->id }}, '{{ $score->decision }}')">
                                    <i class="fab fa-whatsapp"></i> Kirim
                                </button>
                                @endif
                                <form action="{{ route('interview-scores.destroy', $score->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Hapus data penilaian ini?')">
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
    if (confirm(`Anda akan mengirim hasil DITERIMA ke kandidat produksi. Lanjutkan?`)) {
        document.getElementById('sendResultForm').action = `/interview-scores/${scoreId}/send-result`;
        document.getElementById('sendResultForm').submit();
    }
}
</script>
@endsection