@php
    $companyName = "PT Kirana Food International";
@endphp

Halo *{{ $application->full_name }}*,

Terima kasih telah mengirimkan lamaran kerja untuk posisi *{{ $application->job->position }}* di {{ $companyName }}.

Lamaran Anda telah kami terima dan sedang dalam proses review. Kami akan menghubungi Anda melalui email atau WhatsApp jika lamaran Anda lolos ke tahap selanjutnya.

Mohon menunggu kabar dari kami dalam waktu 1-2 minggu kerja. Apabila belum ada pemberitahuan setelah waktu tersebut, lamaran anda belum memenuhi kualifikasi yang diinginkan.

Hormat kami,
Tim Rekrutmen {{ $companyName }}