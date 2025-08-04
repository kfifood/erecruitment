@extends('layouts.app')
@section('title', 'Kandidat Belum Dinilai')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-3 text-center" style="font-size:1.5rem; font-weight:700; color: var(--bluedark-color);">Kandidat Belum Dinilai</h3>
        </div>
        <div class="card-body">
            @if($interviews->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Tidak ada kandidat yang belum dinilai
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
                                    <a href="{{ route('interview-scores.create', $interview->id) }}" 
                                        target="_blank"   
                                        class="btn btn-sm"
                                       style="background-color:#30318B; color:white;">
                                        <i class="fas fa-plus"></i> Beri Nilai
                                    </a>
                                    
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
@endsection