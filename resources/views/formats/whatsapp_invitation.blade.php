*UNDANGAN INTERVIEW PT KIRANA FOOD INTERNATIONAL*

Halo *{{ $interview->application->full_name }}*,
Kami dari {{ $interview->application->job->location }}, mengucapkan terima kasih atas ketertarikan Anda melamar untuk posisi *{{ $interview->application->job->position }}*.
Kami ingin mengundang Anda untuk mengikuti interview yang akan dilaksanakan pada:

📅 *Tanggal :* {{ $interview->interview_date->format('d F Y') }}
⏰ *Waktu   :* {{ $interview->interview_time->format('H:i') }}
📍 *Tempat  :* {{ $interview->method === 'online' ? 'Online (Link akan dikirim pada pesan selanjutnya)' : $interview->location . ', ' . $interview->application->job->full_address }}
📋 *Catatan :* {{ $interview->notes}}

Mohon konfirmasi kehadiran Anda dengan membalas pesan ini. 
Terima kasih.


HRD,
{{ $interview->application->job->location }}

*#pesan ini uji coba website e-rekrutmen*
