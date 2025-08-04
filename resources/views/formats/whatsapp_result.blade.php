*HASIL INTERVIEW PT KIRANA FOOD INTERNATIONAL*

Halo *{{ $score->interview->application->full_name }}*,

Berikut hasil interview untuk posisi *{{ $score->interview->application->job->position }}*:

📅 Tanggal Interview: {{ $score->interview->interview_date->format('d F Y') }}
📋 Keputusan: {{ $score->decision === 'hired' ? 'LOLOS' : 'TIDAK LOLOS' }}

@if($score->decision === 'hired')
Selamat! Anda telah memenuhi kriteria kami. Tim HRD akan menghubungi Anda segera dalam waktu 1-3 hari kerja untuk proses selanjutnya.
@else
Terima kasih telah berpartisipasi dalam proses seleksi kami. Hasil ini tidak mengurangi potensi Anda untuk kesempatan lain di masa depan.
@endif

HRD,
{{ $score->interview->application->job->location }}

*#pesan ini dikirim otomatis melalui sistem e-rekrutmen*