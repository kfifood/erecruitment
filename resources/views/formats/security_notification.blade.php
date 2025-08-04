*🚨 INFORMASI INTERVIEW HARIAN*

📍 *Lokasi  :* {{ $location }}
📅 *Tanggal :* {{ $date }}
🕒 *Waktu   :* {{ $time }}

*📋 DAFTAR KANDIDAT:*
@foreach($interviews as $interview)
➡ *{{ $loop->iteration }}. {{ $interview->application->full_name }}*
- Posisi : {{ $interview->application->job->position }}
- Jam    : {{ $interview->interview_time->format('H:i') }}
- No. HP : {{ $interview->application->phone }}
@endforeach

*Total: {{ count($interviews) }} kandidat*

Terima kasih.