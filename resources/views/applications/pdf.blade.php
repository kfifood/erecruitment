<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Pelamar - {{ $application->full_name }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { color: #30318B; margin-bottom: 5px; }
        .header p { color: #666; margin-top: 0; }
        .photo { text-align: center; margin-bottom: 20px; }
        .photo img { max-width: 150px; border: 1px solid #ddd; }
        .section { margin-bottom: 15px; page-break-inside: avoid; }
        .section h2 { color: #30318B; font-size: 18px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .info-table td { padding: 8px; border-bottom: 1px solid #eee; }
        .info-table td:first-child { width: 30%; font-weight: bold; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PT Kirana Food International</h1>
        <p>Data Pelamar Pekerjaan</p>
    </div>

    <div class="photo">
        @if($application->photo)
            <img src="{{ public_path($application->photo) }}" alt="Foto Pelamar">
        @else
            <p>[Tidak ada foto]</p>
        @endif
    </div>

    <div class="section">
        <h2>Informasi Pribadi</h2>
        <table class="info-table">
            <tr>
                <td>Nama Lengkap</td>
                <td>{{ $application->full_name }}</td>
            </tr>
            <tr>
                <td>Posisi Dilamar</td>
                <td>{{ $application->job->position }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ $application->email }}</td>
            </tr>
            <tr>
                <td>Telepon</td>
                <td>{{ $application->phone }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>{{ $application->address }}</td>
            </tr>
            <tr>
                <td>Tanggal Lahir</td>
                <td>{{ $application->birth_date->format('d F Y') }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Informasi Pendidikan</h2>
        <table class="info-table">
            <tr>
                <td>Pendidikan Terakhir</td>
                <td>{{ $application->education }}</td>
            </tr>
            @if($application->major)
            <tr>
                <td>Jurusan</td>
                <td>{{ $application->major }}</td>
            </tr>
            @endif
            @if($application->study_program)
            <tr>
                <td>Program Studi</td>
                <td>{{ $application->study_program }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="section">
        <h2>Informasi Lamaran</h2>
        <table class="info-table">
            <tr>
                <td>Status</td>
                <td>
                    <span style="color: 
                        @if($application->status == 'submitted') #6c757d
                        @elseif($application->status == 'interview') #0d6efd
                        @else #dc3545 @endif">
                        {{ ucfirst($application->status) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td>Tanggal Melamar</td>
                <td>{{ $application->submitted_at->format('d F Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Dokumen Lampiran</h2>
        <table class="info-table">
            <tr>
                <td>Curriculum Vitae (CV)</td>
                <td>{{ basename($application->cv) }}</td>
            </tr>
            <tr>
                <td>Surat Lamaran</td>
                <td>{{ basename($application->cover_letter) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dicetak pada {{ now()->format('d F Y H:i') }}
    </div>
</body>
</html>