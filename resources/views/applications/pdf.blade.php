<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>BIODATA PELAMAR - {{ $application->full_name }}</title>
    <style>
    body {
        font-family: Times,"Times New Roman", serif;
        font-size: 12px;
        line-height: 1.5;
    }

    .header {
        text-align: center;
        margin-bottom: 10px;
    }

    .header h1 {
        color: #000;
        margin-bottom: 5px;
        font-size: 16px;
        font-weight: bold;
    }

    .header p {
        color: #000;
        margin-top: 0;
        font-size: 12px;
    }

    .photo {
        float: right;
        margin: 0 0 15px 10px;
        text-align: center;
    }

    .photo img {
        width: 120px;
        height: 160px;
        border: 1px solid #ddd;
        object-fit: cover;
    }

    .section {
        margin-bottom: 15px;
        page-break-inside: avoid;
    }

    .section h2 {
        color: #000;
        font-size: 14px;
        border-bottom: 1px solid #000;
        padding-bottom: 3px;
        margin-bottom: 8px;
    }

    .info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
    }

    .info-table th,
    .info-table td {
        padding: 5px;
        border: 1px solid #ddd;
        vertical-align: top;
    }

    .info-table th {
        background-color: #f2f2f2;
        text-align: left;
    }

    .footer {
        margin-top: 30px;
        text-align: right;
        font-size: 10px;
        color: #666;
    }

    .page-break {
        page-break-after: always;
    }

    .form-title {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .form-value {
        margin-bottom: 10px;
        border-bottom: 1px dotted #000;
        min-height: 20px;
    }

    .form-group {
        margin-bottom: 8px;
    }

    .inline-group {
        display: inline-block;
        margin-right: 20px;
    }

    .clearfix {
        clear: both;
    }
    </style>
</head>

<body>
    <!-- Halaman 1: Data Pribadi dan Keluarga -->
    <div class="header">
        <h1>BIODATA PELAMAR</h1>
        <p>Curriculum Vitae of Employee</p>
    </div>

    <div class="photo">
@if($application->photo)
    @php
        // Gunakan method khusus untuk mendapatkan image data
        $imageData = App\Http\Controllers\ApplicationPdfController::getImageBase64($application->photo);
    @endphp
    
    @if($imageData)
        <img src="data:image/jpeg;base64,{{ $imageData }}" alt="Foto Pelamar" 
             style="width: 120px; height: 160px; border: 1px solid #ddd; object-fit: cover;">
    @else
        <p>[Foto tidak dapat diakses]</p>
    @endif
@else
    <p>[Tidak ada foto]</p>
@endif
</div><br>
    <div class="section">
        <table class="info-table" style="width: 80%;">
            <tr>
                <td width="30%">Pekerjaan Yang Dilamar</td>
                <td>{{ $application->job->position }}</td>
            </tr>
            <tr>
                <td>Nama Lengkap</td>
                <td>{{ $application->full_name }}</td>
            </tr>
            <tr>
                <td>Tempat/Tanggal Lahir</td>
                <td>{{ $application->birth_place }}, {{ $application->birth_date->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td>Alamat Rumah</td>
                <td>{{ $application->address }}</td>

            </tr>
        </table>

        <table class="info-table">
            <tr>
                <td width="25%">Telepon Rumah</td>
                <td width="25%">{{ $application->home_phone ?? '-' }}</td>
                <td width="25%">No. KTP / SIM</td>
                <td width="25%">{{ $application->id_number }}</td>
            </tr>
            <tr>
                <td>Tinggi / Berat</td>
                <td>{{ $application->height }} cm / {{ $application->weight }} kg</td>
                <td>Kepemilikan Rumah</td>
                <td>{{ ucfirst($application->house_ownership) }}</td>
            </tr>
            <tr>
                <td>Kepemilikan Kendaraan</td>
                <td>{{ ucfirst($application->vehicle_ownership) }}</td>
                <td>Status Perkawinan</td>
                <td>{{ ucfirst($application->marital_status) }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>{{ $application->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                <td>HP</td>
                <td>{{ $application->phone }}</td>
            </tr>
            <tr>
                <td>Agama</td>
                <td>{{ $application->religion }}</td>
                <td>-</td>
                <td>-</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Susunan Keluarga</h2>
        <table class="info-table">
            <thead>
                <tr>
                    <th width="15%">Keluarga</th>
                    <th width="20%">Nama</th>
                    <th width="10%">L/P</th>
                    <th width="15%">Tanggal Lahir</th>
                    <th width="20%">Pekerjaan Terakhir</th>
                    <th width="20%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($application->familyMembers ?? [] as $member)
                <tr>
                    <td>{{ ucfirst($member->family_role) }}</td>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->gender == 'L' ? 'L' : 'P' }}</td>
                    <td>{{ $member->birth_date ? \Carbon\Carbon::parse($member->birth_date)->format('d-m-Y') : '-' }}
                    </td>
                    <td>{{ $member->last_position ?? '-' }}</td>
                    <td>{{ $member->last_company ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Riwayat Pendidikan (Formal / Informal)</h2>
        <table class="info-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Tingkat</th>
                    <th width="25%">Nama Sekolah</th>
                    <th width="15%">Kota</th>
                    <th width="15%">Jurusan</th>
                    <th width="15%">Dari Thn. s/d Thn.</th>
                    <th width="10%">Lulus / Tidak Lulus</th>
                </tr>
            </thead>
            <tbody>
                @foreach($application->educations ?? [] as $index => $edu)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $edu['education_level'] }}</td>
                    <td>{{ $edu['school_name'] }}</td>
                    <td>{{ $edu['city'] }}</td>
                    <td>{{ $edu['major'] ?? '-' }}</td>
                    <td>{{ $edu['start_year'] }} - {{ $edu['end_year'] }}</td>
                    <td>Lulus</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Halaman 2: Kursus, Referensi, Bahasa, Komputer, Kegiatan Sosial -->

    <div class="section">
        <h2>Sertifikasi/Pelatihan yang Pernah Diikuti</h2>
        <table class="info-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Nama Pelatihan/Sertifikasi</th>
                    <th width="15%">Kota</th>
                    <th width="20%">Penyelenggara</th>
                    <th width="10%">Tahun</th>
                    <th width="20%">File Sertifikat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($application->certificates ?? [] as $index => $cert)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $cert->name }}</td>
                    <td>{{ $cert->city ?? '-' }}</td>
                    <td>{{ $cert->organizer ?? '-' }}</td>
                    <td>{{ $cert->year ?? '-' }}</td>
                    <td>
                        @if($cert->certificate_file)
                        [File tersedia]
                        @else
                        -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Referensi</h2>
        <p>(Kepada siapa kami dapat menanyakan diri Anda lebih lengkap)</p>
        <table class="info-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Nama</th>
                    <th width="30%">Alamat</th>
                    <th width="15%">Telepon</th>
                    <th width="15%">Pekerjaan</th>
                    <th width="15%">Hubungan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($application->references ?? [] as $index => $ref)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $ref->name }}</td>
                    <td>{{ $ref->address ?? '-' }}</td>
                    <td>{{ $ref->phone ?? '-' }}</td>
                    <td>{{ $ref->occupation ?? '-' }}</td>
                    <td>{{ $ref->relationship ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Orang yang dapat dihubungi segera dalam keadaan DARURAT</h2>
        <table class="info-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Nama</th>
                    <th width="30%">Alamat</th>
                    <th width="15%">Telepon</th>
                    <th width="15%">Pekerjaan</th>
                    <th width="15%">Hubungan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($application->emergencyContacts ?? [] as $index => $contact)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->address ?? '-' }}</td>
                    <td>{{ $contact->phone ?? '-' }}</td>
                    <td>{{ $contact->occupation ?? '-' }}</td>
                    <td>{{ $contact->relationship ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <!-- Halaman 3: Bahasa, Komputer, Kegiatan Sosial, Riwayat Pekerjaan, Pertanyaan -->

    <div class="section">
        <h2>Pengetahuan Bahasa (Diisi dengan : [ * ])</h2>
        <table class="info-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Macam Bahasa</th>
                    <th width="15%">Mahir</th>
                    <th width="15%">Menguasai</th>
                    <th width="15%">Pemula</th>
                    <th width="30%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($application->languageSkills ?? [] as $index => $lang)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $lang->language }}</td>
                    <td style="text-align: center; font-size:1.5rem;">{{ $lang->level == 'mahir' ? '*' : '' }}</td>
                    <td style="text-align: center; font-size:1.5rem;">{{ $lang->level == 'menguasai' ? '*' : '' }}</td>
                    <td style="text-align: center; font-size:1.5rem;">{{ $lang->level == 'pemula' ? '*' : '' }}</td>
                    <td>{{ $lang->description ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Pengetahuan Komputer (Diisi dengan : [ * ])</h2>
        <table class="info-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Jenis Program / Aplikasi</th>
                    <th width="15%">Mahir</th>
                    <th width="15%">Menguasai</th>
                    <th width="15%">Pemula</th>
                    <th width="20%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($application->computerSkills ?? [] as $index => $skill)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $skill->program }}</td>
                    <td style="text-align: center; font-size:1.5rem;">{{ $skill->level == 'mahir' ? '*' : '' }}</td>
                    <td style="text-align: center; font-size:1.5rem;">{{ $skill->level == 'menguasai' ? '*' : '' }}</td>
                    <td style="text-align: center; font-size:1.5rem;">{{ $skill->level == 'pemula' ? '*' : '' }}</td>
                    <td>{{ $skill->description ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Kegiatan Sosial / Keanggotaan Asosiasi</h2>
        <table class="info-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="25%">Nama Organisasi</th>
                    <th width="25%">Alamat</th>
                    <th width="15%">Jabatan</th>
                    <th width="15%">Tahun</th>
                    <th width="15%">Macam Kegiatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($application->socialActivities ?? [] as $index => $activity)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $activity->organization }}</td>
                    <td>{{ $activity->address ?? '-' }}</td>
                    <td>{{ $activity->position ?? '-' }}</td>
                    <td>{{ $activity->year ?? '-' }}</td>
                    <td>{{ $activity->activity_type ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Riwayat Pekerjaan (Dimulai dari pekerjaan Sekarang / Terakhir s/d Sebelumnya)</h2>
        @foreach($application->employmentHistories ?? [] as $index => $job)
        <div class="form-group">
            <div class="form-title">Perusahaan {{ $index + 1 }}</div>
            <table class="info-table">
                <tr>
                    <td width="30%">Nama Perusahaan</td>
                    <td>{{ $job->company_name }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>{{ $job->address ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Telepon</td>
                    <td>{{ $job->phone ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Periode Kerja</td>
                    <td>{{ $job->start_year }} - {{ $job->end_year ?? 'Sekarang' }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>{{ $job->position ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Jenis Usaha</td>
                    <td>{{ $job->business_type ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Gaji Terakhir</td>
                    <td>{{ $job->last_salary ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Alasan Berhenti</td>
                    <td>{{ $job->reason_for_leaving ?? '-' }}</td>
                </tr>
            </table>
            <div class="form-group">
                <div class="form-title">Uraian Tugas:</div>
                <div class="form-value">{{ $job->job_description ?? '-' }}</div>
            </div>
        </div>
        @endforeach
    </div>
    <!-- TAMBAHKAN BAGIAN INI - KELEBIHAN DAN KEKURANGAN -->
    <div class="section">
        <h2>Kelebihan dan Kekurangan</h2>

        <div class="form-group">
            <div class="form-title">Kelebihan / Kekuatan:</div>
            <div class="form-value" style="min-height: 60px; border: 1px solid #ddd; padding: 8px; border-radius: 4px;">
                {{ $application->strengths ?? '-' }}
            </div>
        </div>

        <div class="form-group">
            <div class="form-title">Kekurangan / Kelemahan:</div>
            <div class="form-value" style="min-height: 60px; border: 1px solid #ddd; padding: 8px; border-radius: 4px;">
                {{ $application->weaknesses ?? '-' }}
            </div>
        </div>
    </div>
</body>

</html>