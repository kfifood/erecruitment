<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Apply for {{ $job->position }} | PT Kirana Food International</title>

    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
    :root {
        --primary-color: #30318B;
        --secondary-color: #6c757d;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --warning-color: #ffc107;
        --light-bg: #f8f9fa;
        --dark-text: #212529;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--light-bg);
        color: var(--dark-text);
        line-height: 1.6;
    }

    .application-form {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin: 30px auto;
        max-width: 1000px;
    }

    .application-header {

        padding-bottom: 15px;
        margin-bottom: 30px;
    }

    .header-logo img.company-logo {
        height: 60px;
        max-width: 100%;
        object-fit: contain;
    }

    .vertical-divider {
        border-left: 2px solid #ddd;
        height: 100px;
    }

    .header-text {
        flex: 1;
    }

    .header-title {
        color: #30318B;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
        text-transform: uppercase;
    }

    .position-applied {
        font-size: 1rem;
        color: #333;
    }

    .header-divider {
        border-top: 2px solid #ddd;

    }

    @media (max-width: 768px) {
        .header-title {
            font-size: 1.4rem;
        }

        .position-applied {
            font-size: 1rem;
        }

        .header-logo img.company-logo {
            height: 60px;
        }

        .vertical-divider {
            height: 60px;
        }
    }

    .required-field::after {
        content: " *";
        color: var(--danger-color);
    }

    .step {
        display: none;
        animation: fadeIn 0.5s ease-in-out;
    }

    .step.active {
        display: block;
    }

    .step-header {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #dee2e6;
        position: relative;
    }

    .step-header::after {
        content: "";
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 50px;
        height: 2px;
        background-color: var(--primary-color);
    }

    .section-title {
        font-size: 1.1rem;
        color: var(--primary-color);
        margin: 25px 0 15px;
        padding-bottom: 5px;
        border-bottom: 1px dashed #dee2e6;
    }

    .form-photo-preview {
        width: 120px;
        height: 160px;
        object-fit: cover;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        display: none;
        margin-bottom: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .btn-nav {
        background-color: var(--primary-color);
        color: white;
        font-weight: 600;
        padding: 8px 20px;
        border-radius: 5px;
        transition: all 0.3s;
    }

    .btn-nav:hover {
        background-color: #1a237e;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-submit {
        background-color: var(--success-color);
        color: white;
        font-weight: 600;
        padding: 10px 25px;
        border-radius: 5px;
        transition: all 0.3s;
    }

    .btn-submit:hover {
        background-color: #218838;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .family-member-form,
    .education-form,
    .employment-form {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        border-left: 3px solid var(--primary-color);
    }

    .add-btn {
        margin-bottom: 20px;
        background-color: var(--secondary-color);
        color: white;
    }

    .add-btn:hover {
        background-color: #5a6268;
        color: white;
    }

    .remove-btn {
        background-color: var(--danger-color);
        color: white;
    }

    .remove-btn:hover {
        background-color: #c82333;
        color: white;
    }

    .file-upload-box {
        border: 2px dashed #dee2e6;
        border-radius: 5px;
        padding: 20px;
        text-align: center;
        margin-bottom: 15px;
        transition: all 0.3s;
    }

    .file-upload-box:hover {
        border-color: var(--primary-color);
        background-color: rgba(48, 49, 139, 0.05);
    }

    .form-note {
        font-size: 0.85rem;
        color: var(--secondary-color);
        margin-top: 5px;
    }

    .progress-container {
        margin-bottom: 80px;
    }

    .progress-step {
        display: flex;
        justify-content: space-between;
        position: relative;
    }

    .progress-step::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #e0e0e0;
        z-index: 1;
    }

    .progress-bar {
        position: absolute;
        top: 50%;
        left: 0;
        height: 2px;
        background-color: var(--primary-color);
        z-index: 2;
        transition: width 0.3s;
    }

    .step-indicator {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
        font-weight: bold;
        position: relative;
        z-index: 3;
    }

    .step-indicator.active {
        background-color: var(--primary-color);
        color: white;
    }

    .step-label {
        position: absolute;
        top: 40px;
        font-size: 0.8rem;
        color: #666;
        width: 100px;
        text-align: center;
        left: 50%;
        transform: translateX(-50%);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .application-form {
            padding: 20px;
            margin: 15px;
        }

        .section-title {
            font-size: 1rem;
        }

        .step-indicator {
            width: 25px;
            height: 25px;
            font-size: 0.8rem;
        }

        .step-label {
            font-size: 0.7rem;
            top: 35px;
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="application-form">
                    <!-- Header dengan logo -->
                    <div class="application-header mb-4">
                        <div class="d-flex align-items-center">
                            <!-- Logo di sebelah kiri -->
                            <div class="header-logo me-4">
                                <img src="{{ asset('logo.png') }}" alt="Logo PT Kirana Food International"
                                    class="company-logo">
                            </div>

                            <!-- Garis vertikal pembatas -->
                            <div class="vertical-divider"></div>

                            <!-- Teks di sebelah kanan -->
                            <div class="header-text ms-4">
                                <h1 class="header-title">FORMULIR LAMARAN KERJA</h1>
                                <div class="position-applied">
                                    <strong>Posisi yang Dilamar:</strong> {{ $job->position }}
                                </div>
                            </div>
                        </div>

                        <!-- Garis horizontal -->
                        <div class="header-divider"></div>
                    </div>


                    <!-- Progress Indicator -->
                    <div class="progress-container">
                        <div class="progress-step">
                            <div class="progress-bar" id="progressBar" style="width: 0%"></div>
                            <div class="step-indicator active" id="step1Indicator">
                                1
                                <span class="step-label">Data Pribadi</span>
                            </div>
                            <div class="step-indicator" id="step2Indicator">
                                2
                                <span class="step-label">Pendidikan</span>
                            </div>
                            <div class="step-indicator" id="step3Indicator">
                                3
                                <span class="step-label">Pengalaman</span>
                            </div>
                            <div class="step-indicator" id="step4Indicator">
                                4
                                <span class="step-label">Dokumen</span>
                            </div>
                            <div class="step-indicator" id="step5Indicator">
                                5
                                <span class="step-label">Pertanyaan</span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Multi Step -->
                    <form method="POST" action="{{ route('applications.store.public') }}" enctype="multipart/form-data"
                        id="applicationForm">
                        @csrf
                        <input type="hidden" name="job_id" value="{{ $job->id }}">

                        <!-- Step 1: Data Pribadi -->
                        <div class="step active" id="step1">
                            <h4 class="step-header">1. Data Pribadi</h4>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="full_name" class="form-label required-field">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="birth_place" class="form-label required-field">Tempat Lahir</label>
                                    <input type="text" class="form-control" id="birth_place" name="birth_place"
                                        required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="birth_date" class="form-label required-field">Tanggal Lahir</label>
                                    <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                                </div>
                                <input type="hidden" id="job_gender" value="{{ $job->gender }}">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required-field">Jenis Kelamin</label>
                                    <div>
                                        @if($job->gender == 'pria')
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="gender_male"
                                                value="L" checked required>
                                            <label class="form-check-label" for="gender_male">Laki-laki</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender"
                                                id="gender_female" value="P" disabled>
                                            <label class="form-check-label" for="gender_female"
                                                style="color: #ccc;">Perempuan</label>
                                        </div>
                                        @elseif($job->gender == 'wanita')
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="gender_male"
                                                value="L" disabled>
                                            <label class="form-check-label" for="gender_male"
                                                style="color: #ccc;">Laki-laki</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender"
                                                id="gender_female" value="P" checked required>
                                            <label class="form-check-label" for="gender_female">Perempuan</label>
                                        </div>
                                        @else
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="gender_male"
                                                value="L" required>
                                            <label class="form-check-label" for="gender_male">Laki-laki</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender"
                                                id="gender_female" value="P">
                                            <label class="form-check-label" for="gender_female">Perempuan</label>
                                        </div>
                                        @endif
                                    </div>
                                    <small class="text-muted">Lowongan ini untuk:
                                        @if($job->gender == 'pria') Pria
                                        @elseif($job->gender == 'wanita') Wanita
                                        @else Pria/Wanita
                                        @endif
                                    </small>
                                </div>
                            </div>

                            <!-- Upload Foto -->
                            <div class="mb-3">
                                <label class="form-label required-field">Pas Foto 3x4</label>
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        <img id="photoPreview" class="form-photo-preview" src="#" alt="Preview Foto">
                                        <input type="file" id="photo" name="photo" accept="image/jpeg,image/png"
                                            required onchange="previewImage(this, 'photoPreview')">
                                    </div>
                                    <div>
                                        <div class="form-note">Format: JPG/PNG (maks. 2MB)</div>
                                        <div class="form-note">Ukuran: 3x4 cm</div>
                                        <div class="form-note">Background: Bebas (disarankan merah)</div>
                                    </div>
                                </div>
                            </div>

                           <!-- Data Alamat dan Kontak -->
                            <div class="mb-3">
                                <label for="address" class="form-label required-field">Alamat Lengkap</label>

                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="province"
                                            class="form-label required-field"><small>Provinsi</small></label>
                                        <select class="form-select" id="province" name="province" required>
                                            <option value="">Pilih Provinsi</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="city"
                                            class="form-label required-field"><small>Kabupaten/Kota</small></label>
                                        <select class="form-select" id="city" name="city" required>
                                            <option value="">Pilih Kabupaten/Kota</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    
                                    <div class="col-md-6">
                                        <label for="district"
                                            class="form-label required-field"><small>Kecamatan</small></label>
                                        <select class="form-select" id="district" name="district" required>
                                            <option value="">Pilih Kecamatan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="village"
                                            class="form-label required-field"><small>Desa/Kelurahan</small></label>
                                        <select class="form-select" id="village" name="village" required>
                                            <option value="">Pilih Desa/Kelurahan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <label for="street" class="form-label required-field"><small>Jalan/Alamat
                                                Spesifik</small></label>
                                        <input type="text" class="form-control" id="street" name="street" required>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <label for="rt" class="form-label"><small>RT</small></label>
                                        <input type="text" class="form-control" id="rt" name="rt" maxlength="3">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="rw" class="form-label"><small>RW</small></label>
                                        <input type="text" class="form-control" id="rw" name="rw" maxlength="3">
                                    </div>
                                </div>
                                <!-- Input hidden untuk menyimpan alamat lengkap -->
                                <input type="hidden" id="address" name="address">
                            </div>

                            <div class="row" style="margin-top:3rem;">
                                <div class="col-md-4 mb-3">
                                    <label for="phone" class="form-label required-field">No. HP/WhatsApp</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="home_phone" class="form-label">Telp. Rumah</label>
                                    <input type="tel" class="form-control" id="home_phone" name="home_phone">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="email" class="form-label required-field">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>

                            <!-- Data Identitas -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="id_number" class="form-label required-field">No. KTP/SIM</label>
                                    <input type="text" class="form-control" id="id_number" name="id_number" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="religion" class="form-label required-field">Agama</label>
                                    <select class="form-select" id="religion" name="religion" required>
                                        <option value="">Pilih Agama</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Buddha">Buddha</option>
                                        <option value="Konghucu">Konghucu</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="ethnicity" class="form-label required-field">Suku Bangsa</label>
                                    <input type="text" class="form-control" id="ethnicity" name="ethnicity" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="height" class="form-label required-field">Tinggi Badan (cm)</label>
                                    <input type="number" class="form-control" id="height" name="height" min="100"
                                        max="250" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="weight" class="form-label required-field">Berat Badan (kg)</label>
                                    <input type="number" class="form-control" id="weight" name="weight" min="30"
                                        max="200" required>
                                </div>
                            </div>

                            <!-- Kepemilikan dan Status -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required-field">Kepemilikan Rumah</label>
                                    <select class="form-select" id="house_ownership" name="house_ownership" required>
                                        <option value="">Pilih Status Kepemilikan</option>
                                        <option value="sendiri">Milik Sendiri</option>
                                        <option value="orangtua">Milik Orangtua</option>
                                        <option value="sewa">Sewa/Kontrak</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required-field">Kepemilikan Kendaraan</label>
                                    <select class="form-select" id="vehicle_ownership" name="vehicle_ownership"
                                        required>
                                        <option value="">Pilih Kendaraan</option>
                                        <option value="mobil">Mobil</option>
                                        <option value="motor">Motor</option>
                                        <option value="keduanya">Mobil dan Motor</option>
                                        <option value="tidak ada">Tidak Ada</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required-field">Status Perkawinan</label>
                                    <select class="form-select" id="marital_status" name="marital_status" required
                                        onchange="toggleFamilyForm(this.value)">
                                        <option value="">Pilih Status</option>
                                        <option value="belum menikah">Belum Menikah</option>
                                        <option value="menikah">Menikah</option>
                                        <option value="duda/janda">Duda/Janda</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="family_members" class="form-label required-field">Jumlah Anggota
                                        Keluarga</label>
                                    <input type="number" class="form-control" id="family_members" name="family_members"
                                        min="1" required>
                                </div>
                            </div>

                            <!-- Form Keluarga (akan muncul jika status menikah/duda/janda) -->
                            <div id="familyFormContainer" style="display: none;">
                                <h5 class="section-title">Susunan Keluarga</h5>
                                <div id="familyMembersContainer">
                                    <!-- Form anggota keluarga akan ditambahkan disini -->
                                </div>
                                <button type="button" class="btn btn-sm add-btn" onclick="addFamilyMember()">
                                    <i class="fas fa-plus me-1"></i> Tambah Anggota Keluarga
                                </button>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-nav" onclick="nextStep(1, 2)">
                                    Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Data Pendidikan -->
                        <div class="step" id="step2">
                            <h4 class="step-header">2. Data Pendidikan</h4>

                            <!-- Riwayat Pendidikan -->
                            <h5 class="section-title">Riwayat Pendidikan <small class="text-muted">(Pendidikan Terakhir
                                    Anda)</small></h5>
                            <div id="educationsContainer">
                                <div class="education-form">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label required-field">Pendidikan</label>
                                            <select class="form-select" name="educations[0][education_level]" required>
                                                <option value="">Pilih Pendidikan</option>
                                                <option value="SD">SD</option>
                                                <option value="SMP">SMP</option>
                                                <option value="SMA">SMA</option>
                                                <option value="SMK">SMK</option>
                                                <option value="D1">D1</option>
                                                <option value="D2">D2</option>
                                                <option value="D3">D3</option>
                                                <option value="D4">D4</option>
                                                <option value="S1">S1</option>
                                                <option value="S2">S2</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label required-field">Nama Sekolah/Universitas</label>
                                            <input type="text" class="form-control" name="educations[0][school_name]"
                                                required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label required-field">Kota</label>
                                            <input type="text" class="form-control" name="educations[0][city]" required>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label">Jurusan</label>
                                            <input type="text" class="form-control" name="educations[0][major]">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label required-field">Tahun Masuk</label>
                                            <input type="number" class="form-control" name="educations[0][start_year]"
                                                min="1900" max="2099" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label required-field">Tahun Lulus</label>
                                            <input type="number" class="form-control" name="educations[0][end_year]"
                                                min="1900" max="2099" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm add-btn mb-4" onclick="addEducation()">
                                <i class="fas fa-plus me-1"></i> Tambah Pendidikan
                            </button>

                            <!-- Sertifikat dan Pelatihan -->
                            <h5 class="section-title">Sertifikat & Pelatihan <small class="text-muted"> (kosongi bila
                                    tidak ada)</small></h5>
                            <div id="certificatesContainer">
                                <div class="education-form">
                                    <div class="row">
                                        <div class="col-md-5 mb-3">
                                            <label class="form-label">Nama Sertifikat/Pelatihan</label>
                                            <input type="text" class="form-control" name="certificates[0][name]">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Kota</label>
                                            <input type="text" class="form-control" name="certificates[0][city]">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Penyelenggara</label>
                                            <input type="text" class="form-control" name="certificates[0][organizer]">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Tahun</label>
                                            <input type="number" class="form-control" name="certificates[0][year]"
                                                min="1900" max="2099">
                                        </div>
                                        <div class="col-md-9 mb-3">
                                            <label class="form-label">File Sertifikat (Opsional)</label>
                                            <input type="file" class="form-control"
                                                name="certificates[0][certificate_file]"
                                                accept="image/jpeg,image/png,application/pdf">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm add-btn mb-4" onclick="addCertificate()">
                                <i class="fas fa-plus me-1"></i> Tambah Sertifikat
                            </button>

                            <!-- Tombol Navigasi -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-nav" onclick="prevStep(2, 1)">
                                    <i class="fas fa-arrow-left me-2"></i> Sebelumnya
                                </button>
                                <button type="button" class="btn btn-nav" onclick="nextStep(2, 3)">
                                    Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Data Pengalaman -->
                        <div class="step" id="step3">
                            <h4 class="step-header">3. Data Pengalaman</h4>

                            <!-- Riwayat Pekerjaan -->
                            <h5 class="section-title">Riwayat Pekerjaan <small class="text-muted"> (kosongi bila tidak
                                    ada)</small></h5>
                            <div id="employmentHistoriesContainer">
                                <div class="employment-form">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nama Perusahaan</label>
                                            <input type="text" class="form-control"
                                                name="employment_histories[0][company_name]">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Alamat Perusahaan</label>
                                            <input type="text" class="form-control"
                                                name="employment_histories[0][address]">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Telepon</label>
                                            <input type="text" class="form-control"
                                                name="employment_histories[0][phone]">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Tahun Masuk</label>
                                            <input type="number" class="form-control"
                                                name="employment_histories[0][start_year]" min="1900" max="2099">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Tahun Keluar</label>
                                            <input type="number" class="form-control"
                                                name="employment_histories[0][end_year]" min="1900" max="2099">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Jabatan</label>
                                            <input type="text" class="form-control"
                                                name="employment_histories[0][position]">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Jenis Usaha</label>
                                            <input type="text" class="form-control"
                                                name="employment_histories[0][business_type]">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Jumlah Karyawan</label>
                                            <input type="text" class="form-control"
                                                name="employment_histories[0][employee_count]">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Gaji Terakhir</label>
                                            <input type="text" class="form-control"
                                                name="employment_histories[0][last_salary]">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Alasan Berhenti</label>
                                            <input type="text" class="form-control"
                                                name="employment_histories[0][reason_for_leaving]">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Uraian Tugas & Tanggung Jawab</label>
                                        <textarea class="form-control" name="employment_histories[0][job_description]"
                                            rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm add-btn mb-4" onclick="addEmploymentHistory()">
                                <i class="fas fa-plus me-1"></i> Tambah Pekerjaan
                            </button>

                            <!--Kelebihan dan Kekurangan-->
                            <div class="mb-4">
                                <label for="strengths" class="form-label required-field">Kelebihan/Kekuatan Anda</label>
                                <textarea class="form-control" id="strengths" name="strengths" rows="3"
                                    required></textarea>
                                <div class="form-note">Sebutkan 3-5 kelebihan atau kekuatan yang Anda miliki yang
                                    relevan dengan pekerjaan ini</div>
                            </div>

                            <div class="mb-4">
                                <label for="weaknesses" class="form-label required-field">Kelemahan/Kekurangan
                                    Anda</label>
                                <textarea class="form-control" id="weaknesses" name="weaknesses" rows="3"
                                    required></textarea>
                                <div class="form-note">Sebutkan 2-3 kelemahan dan bagaimana Anda mengatasinya</div>
                            </div>

                            <!-- Referensi -->
                            <h5 class="section-title">Referensi <small class="text-muted"> (kosongi bila tidak
                                    ada)</small> </h5>
                            <div id="referencesContainer">
                                <div class="education-form">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nama</label>
                                            <input type="text" class="form-control" name="references[0][name]">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Alamat</label>
                                            <input type="text" class="form-control" name="references[0][address]">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Telepon</label>
                                            <input type="text" class="form-control" name="references[0][phone]">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Pekerjaan</label>
                                            <input type="text" class="form-control" name="references[0][occupation]">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Hubungan</label>
                                            <input type="text" class="form-control" name="references[0][relationship]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm add-btn mb-4" onclick="addReference()">
                                <i class="fas fa-plus me-1"></i> Tambah Referensi
                            </button>

                            <!-- Kontak Darurat -->
                            <h5 class="section-title">Orang yang Dapat Dihubungi (Darurat)</h5>
                            <div id="emergencyContactsContainer">
                                <div class="education-form">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nama</label>
                                            <input type="text" class="form-control" name="emergency_contacts[0][name]">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Alamat</label>
                                            <input type="text" class="form-control"
                                                name="emergency_contacts[0][address]">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Telepon</label>
                                            <input type="text" class="form-control" name="emergency_contacts[0][phone]">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Pekerjaan</label>
                                            <input type="text" class="form-control"
                                                name="emergency_contacts[0][occupation]">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Hubungan</label>
                                            <input type="text" class="form-control"
                                                name="emergency_contacts[0][relationship]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm add-btn mb-4" onclick="addEmergencyContact()">
                                <i class="fas fa-plus me-1"></i> Tambah Kontak Darurat
                            </button>

                            <!-- Kemampuan Bahasa -->
                            <h5 class="section-title">Kemampuan Bahasa <small class="text-muted"> (kosongi bila tidak
                                    ada)</small></h5>
                            <div id="languageSkillsContainer">
                                <div class="education-form">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Bahasa</label>
                                            <input type="text" class="form-control" name="language_skills[0][language]">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Tingkat Kemampuan</label>
                                            <select class="form-select" name="language_skills[0][level]">
                                                <option value="mahir">Mahir</option>
                                                <option value="menguasai">Menguasai</option>
                                                <option value="pemula">Pemula</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Keterangan</label>
                                            <input type="text" class="form-control"
                                                name="language_skills[0][description]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm add-btn mb-4" onclick="addLanguageSkill()">
                                <i class="fas fa-plus me-1"></i> Tambah Bahasa
                            </button>

                            <!-- Kemampuan Komputer -->
                            <h5 class="section-title">Kemampuan Komputer <small class="text-muted"> (kosongi bila tidak
                                    ada)</small></h5>
                            <div id="computerSkillsContainer">
                                <div class="education-form">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Program</label>
                                            <input type="text" class="form-control" name="computer_skills[0][program]">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Tingkat Kemampuan</label>
                                            <select class="form-select" name="computer_skills[0][level]">
                                                <option value="mahir">Mahir</option>
                                                <option value="menguasai">Menguasai</option>
                                                <option value="pemula">Pemula</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Keterangan</label>
                                            <input type="text" class="form-control"
                                                name="computer_skills[0][description]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm add-btn mb-4" onclick="addComputerSkill()">
                                <i class="fas fa-plus me-1"></i> Tambah Program
                            </button>

                            <!-- Kegiatan Sosial -->
                            <h5 class="section-title">Kegiatan Sosial <small class="text-muted"> (kosongi bila tidak
                                    ada)</small></h5>
                            <div id="socialActivitiesContainer">
                                <div class="education-form">
                                    <div class="row">
                                        <div class="col-md-5 mb-3">
                                            <label class="form-label">Nama Organisasi</label>
                                            <input type="text" class="form-control"
                                                name="social_activities[0][organization]">
                                        </div>
                                        <div class="col-md-5 mb-3">
                                            <label class="form-label">Alamat</label>
                                            <input type="text" class="form-control"
                                                name="social_activities[0][address]">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label">Tahun</label>
                                            <input type="number" class="form-control" name="social_activities[0][year]"
                                                min="1900" max="2099">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Jabatan</label>
                                            <input type="text" class="form-control"
                                                name="social_activities[0][position]">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Macam Kegiatan</label>
                                            <input type="text" class="form-control"
                                                name="social_activities[0][activity_type]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm add-btn mb-4" onclick="addSocialActivity()">
                                <i class="fas fa-plus me-1"></i> Tambah Kegiatan
                            </button>

                            <!-- Tombol Navigasi -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-nav" onclick="prevStep(3, 2)">
                                    <i class="fas fa-arrow-left me-2"></i> Sebelumnya
                                </button>
                                <button type="button" class="btn btn-nav" onclick="nextStep(3, 4)">
                                    Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 4: Upload Dokumen -->
                        <div class="step" id="step4">
                            <h4 class="step-header">4. Upload Dokumen</h4>

                            <div class="mb-3">
                                <label class="form-label required-field">Curriculum Vitae (CV)</label>
                                <div class="file-upload-box">
                                    <input type="file" id="cv" name="cv" accept=".pdf" required>
                                    <p class="mt-2">Format: PDF (maks. 5MB)</p>
                                    <small class="text-muted">Contoh: CV_NamaLengkap.pdf</small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label required-field">Surat Lamaran</label>
                                <div class="file-upload-box">
                                    <input type="file" id="cover_letter" name="cover_letter" accept=".pdf" required>
                                    <p class="mt-2">Format: PDF (maks. 5MB)</p>
                                    <small class="text-muted">Contoh: SuratLamaran_NamaLengkap.pdf</small>
                                </div>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="declaration" required>
                                <label class="form-check-label" for="declaration">
                                    Saya menyatakan bahwa semua informasi yang saya berikan adalah benar dan dapat
                                    dipertanggungjawabkan.
                                </label>
                            </div>

                            <!-- Di step 4, ubah tombol submit -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-nav" onclick="prevStep(4, 3)">
                                    <i class="fas fa-arrow-left me-2"></i> Sebelumnya
                                </button>
                                <button type="button" class="btn btn-nav" onclick="nextStep(4, 5)">
                                    Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Setelah step 4 -->
                        @include('applications.partials.questions')
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Script untuk dropdown alamat -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    // Fungsi untuk memuat data wilayah
    function loadRegions(type, parentId = null) {
        let url = '';

        switch (type) {
            case 'province':
                url = '/api/provinces';
                break;
            case 'regency':
                url = `/api/regencies/${parentId}`;
                break;
            case 'district':
                url = `/api/districts/${parentId}`;
                break;
            case 'village':
                url = `/api/villages/${parentId}`;
                break;
        }

        return fetch(url)
            .then(response => response.json())
            .then(data => {
                return data;
            });
    }

    // Inisialisasi dropdown alamat
    document.addEventListener('DOMContentLoaded', function() {
        // Load provinsi
        loadRegions('province').then(provinces => {
            const provinceSelect = document.getElementById('province');
            provinceSelect.innerHTML = '<option value="">Pilih Provinsi</option>';

            provinces.forEach(province => {
                const option = document.createElement('option');
                option.value = province.id;
                option.textContent = province.name;
                provinceSelect.appendChild(option);
            });
        });

        // Event listener untuk perubahan provinsi
        document.getElementById('province').addEventListener('change', function() {
            const provinceId = this.value;
            if (provinceId) {
                loadRegions('regency', provinceId).then(regencies => {
                    const regencySelect = document.getElementById('city');
                    regencySelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';

                    regencies.forEach(regency => {
                        const option = document.createElement('option');
                        option.value = regency.id;
                        option.textContent = regency.name;
                        regencySelect.appendChild(option);
                    });

                    // Reset dropdown kecamatan dan desa
                    document.getElementById('district').innerHTML =
                        '<option value="">Pilih Kecamatan</option>';
                    document.getElementById('village').innerHTML =
                        '<option value="">Pilih Desa/Kelurahan</option>';
                });
            }
        });

        // Event listener untuk perubahan kabupaten
        document.getElementById('city').addEventListener('change', function() {
            const regencyId = this.value;
            if (regencyId) {
                loadRegions('district', regencyId).then(districts => {
                    const districtSelect = document.getElementById('district');
                    districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';

                    districts.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.id;
                        option.textContent = district.name;
                        districtSelect.appendChild(option);
                    });

                    // Reset dropdown desa
                    document.getElementById('village').innerHTML =
                        '<option value="">Pilih Desa/Kelurahan</option>';
                });
            }
        });

        // Event listener untuk perubahan kecamatan
        document.getElementById('district').addEventListener('change', function() {
            const districtId = this.value;
            if (districtId) {
                loadRegions('village', districtId).then(villages => {
                    const villageSelect = document.getElementById('village');
                    villageSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';

                    villages.forEach(village => {
                        const option = document.createElement('option');
                        option.value = village.id;
                        option.textContent = village.name;
                        villageSelect.appendChild(option);
                    });
                });
            }
        });
    });
    </script>
    <script>
    // Variabel global untuk counter
    let familyMemberCount = 0;
    let educationCount = 1;
    let certificateCount = 1;
    let employmentHistoryCount = 1;
    let referenceCount = 1;
    let emergencyContactCount = 1;
    let languageSkillCount = 1;
    let computerSkillCount = 1;
    let socialActivityCount = 1;

    // Fungsi untuk preview gambar
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const file = input.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }

            reader.readAsDataURL(file);
        }
    }

   // Fungsi untuk menampilkan alert konfirmasi gender
function showGenderConfirmationAlert() {
    const jobGender = document.getElementById('job_gender').value;
    let genderMessage = '';
    
    if (jobGender === 'pria') {
        genderMessage = 'Pastikan jenis kelamin Anda adalah Pria untuk menyesuaikan kebutuhan kami';
    } else if (jobGender === 'wanita') {
        genderMessage = 'Pastikan jenis kelamin Anda adalah Wanita untuk menyesuaikan kebutuhan kami';
    } else {
        genderMessage = 'Pastikan jenis kelamin Anda adalah Pria/Wanita untuk menyesuaikan kebutuhan kami';
    }
    
    return Swal.fire({
        title: 'Konfirmasi Jenis Kelamin',
        text: genderMessage,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: 'var(--primary-color)',
        cancelButtonColor: 'var(--secondary-color)',
        confirmButtonText: 'Ya, Sudah Benar',
        cancelButtonText: 'Periksa Kembali'
    });
}

// Modifikasi fungsi nextStep untuk menambahkan validasi gender
function nextStep(current, next) {
    // Validasi step saat ini sebelum lanjut
    if (!validateStep(current)) {
        return;
    }
    
    // Tampilkan konfirmasi gender sebelum melanjutkan ke step 2
    if (current === 1 && next === 2) {
        showGenderConfirmationAlert().then((result) => {
            if (result.isConfirmed) {
                proceedToNextStep(current, next);
            }
        });
    } else {
        proceedToNextStep(current, next);
    }
}
    // Fungsi untuk melanjutkan ke step berikutnya
function proceedToNextStep(current, next) {
    document.getElementById(`step${current}`).classList.remove('active');
    document.getElementById(`step${next}`).classList.add('active');

    // Update progress bar
    updateProgressBar(next);

    // Scroll ke atas
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

    function prevStep(current, prev) {
        document.getElementById(`step${current}`).classList.remove('active');
        document.getElementById(`step${prev}`).classList.add('active');

        // Update progress bar
        updateProgressBar(prev);

        // Scroll ke atas
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    // Update fungsi updateProgressBar
    function updateProgressBar(currentStep) {
        const progressPercentage = ((currentStep - 1) / 4) * 100; // Ubah pembagi menjadi 4 karena ada 5 step
        document.getElementById('progressBar').style.width = `${progressPercentage}%`;

        // Update step indicator
        for (let i = 1; i <= 5; i++) { // Ubah menjadi 5
            const indicator = document.getElementById(`step${i}Indicator`);
            if (i < currentStep) {
                indicator.classList.add('active');
            } else if (i === currentStep) {
                indicator.classList.add('active');
            } else {
                indicator.classList.remove('active');
            }
        }
    }
    const addressFields = ['street', 'rt', 'rw', 'village', 'district', 'city', 'province'];
    addressFields.forEach(field => {
        const element = document.getElementById(field);
        if (element) {
            element.addEventListener('change', buildFullAddress);
            element.addEventListener('keyup', buildFullAddress);
        }
    });
    // Fungsi untuk menggabungkan alamat
function buildFullAddress() {
    const street = document.getElementById('street').value;
    const rt = document.getElementById('rt').value;
    const rw = document.getElementById('rw').value;
    
    // Dapatkan teks yang dipilih dari dropdown
    const villageSelect = document.getElementById('village');
    const village = villageSelect.options[villageSelect.selectedIndex]?.text || '';
    
    const districtSelect = document.getElementById('district');
    const district = districtSelect.options[districtSelect.selectedIndex]?.text || '';
    
    const citySelect = document.getElementById('city');
    const city = citySelect.options[citySelect.selectedIndex]?.text || '';
    
    const provinceSelect = document.getElementById('province');
    const province = provinceSelect.options[provinceSelect.selectedIndex]?.text || '';

    let addressParts = [];

    if (street) addressParts.push(street);
    if (rt || rw) {
        addressParts.push(`RT ${rt || '00'}/RW ${rw || '00'}`);
    }
    if (village) addressParts.push(`Desa/Kel. ${village}`);
    if (district) addressParts.push(`Kec. ${district}`);
    if (city) addressParts.push(`Kab./Kota ${city}`);
    if (province) addressParts.push(`Prov. ${province}`);

    const fullAddress = addressParts.join(', ');
    document.getElementById('address').value = fullAddress;

    return fullAddress;
}
// Tambahkan event listener untuk dropdown alamat
const addressDropdowns = ['province', 'city', 'district', 'village'];
addressDropdowns.forEach(dropdown => {
    const element = document.getElementById(dropdown);
    if (element) {
        element.addEventListener('change', buildFullAddress);
    }
});
    // Fungsi validasi step
    // Fungsi validasi step
    function validateStep(step) {
        let isValid = true;

        if (step === 1) {
            // Bangun alamat lengkap terlebih dahulu
            buildFullAddress();

            // Validasi data pribadi (tetap wajib)
            const requiredFields = [
                'full_name', 'birth_place', 'birth_date', 'gender',
                'phone', 'email', 'id_number', 'religion',
                'ethnicity', 'height', 'weight', 'house_ownership',
                'vehicle_ownership', 'marital_status', 'family_members',
                'address' // Tambahkan address ke required fields
            ];

            // Validasi field alamat individual
            const requiredAddressFields = [
                'street', 'village', 'district', 'city', 'province'
            ];

            requiredAddressFields.forEach(field => {
                const element = document.getElementById(field);
                if (element && !element.value.trim()) {
                    element.classList.add('is-invalid');
                    isValid = false;
                } else if (element) {
                    element.classList.remove('is-invalid');
                }
            });

            requiredFields.forEach(field => {
                const element = document.querySelector(`[name="${field}"]`);
                if (element && !element.value) {
                    element.classList.add('is-invalid');
                    isValid = false;
                } else if (element) {
                    element.classList.remove('is-invalid');
                }
            });

            // Validasi foto (tetap wajib)
            const photo = document.getElementById('photo');
            if (!photo.files || photo.files.length === 0) {
                photo.classList.add('is-invalid');
                isValid = false;
            } else {
                photo.classList.remove('is-invalid');
            }
        }


        if (step === 4) {
            // Validasi dokumen (tetap wajib)
            const requiredFiles = ['cv', 'cover_letter'];
            requiredFiles.forEach(field => {
                const element = document.getElementById(field);
                if (!element.files || element.files.length === 0) {
                    element.classList.add('is-invalid');
                    isValid = false;
                } else {
                    element.classList.remove('is-invalid');
                }
            });

            // Validasi checkbox deklarasi (tetap wajib)
            const declaration = document.getElementById('declaration');
            if (!declaration.checked) {
                declaration.classList.add('is-invalid');
                isValid = false;
            } else {
                declaration.classList.remove('is-invalid');
            }
        }

        if (!isValid) {
            Swal.fire({
                title: 'Perhatian!',
                text: 'Harap lengkapi semua field yang wajib diisi',
                icon: 'warning',
                confirmButtonColor: 'var(--primary-color)'
            });
        }

        return isValid;
    }


    function toggleFamilyForm(status) {
        const container = document.getElementById('familyFormContainer');
        const maritalStatus = document.getElementById('marital_status').value;

        if (maritalStatus === 'belum menikah' || maritalStatus === 'menikah' || maritalStatus === 'duda/janda') {
            container.style.display = 'block';
            document.getElementById('familyMembersContainer').innerHTML = '';
            familyMemberCount = 0;

            // Auto-generate fields sesuai status
            if (maritalStatus === 'belum menikah') {
                addFamilyMember('ayah', true); // Ayah - tidak bisa dihapus
                addFamilyMember('ibu', true); // Ibu - tidak bisa dihapus
            } else if (maritalStatus === 'menikah') {
                addFamilyMember('suami/istri', true); // Pasangan - tidak bisa dihapus
                addFamilyMember('ayah', true); // Ayah - tidak bisa dihapus
                addFamilyMember('ibu', true); // Ibu - tidak bisa dihapus
            } else if (maritalStatus === 'duda/janda') {
                addFamilyMember('duda/janda', true); // Duda/Janda - tidak bisa dihapus
                addFamilyMember('ayah', true); // Ayah - tidak bisa dihapus
                addFamilyMember('ibu', true); // Ibu - tidak bisa dihapus
            }
        } else {
            container.style.display = 'none';
        }
    }


    // GANTI fungsi addFamilyMember dengan yang baru
    function addFamilyMember(role = null, isFixed = false) {
        const container = document.getElementById('familyMembersContainer');
        const memberId = familyMemberCount++;

        const roles = {
            'ayah': 'Ayah',
            'ibu': 'Ibu',
            'suami/istri': 'Suami/Istri',
            'duda/janda': 'Duda/Janda',
            'anak': 'Anak',
            'saudara': 'Saudara',
        };

        // Gunakan parameter role sebagai selectedRole jika ada
        const selectedRole = role || '';

        // Tentukan apakah tombol hapus harus ditampilkan
        const showRemoveButton = !isFixed;

        const html = `
    <div class="family-member-form" id="familyMember_${memberId}">
        <div class="row">
            <div class="col-md-3 mb-2">
                <label class="form-label">Hubungan</label>
                <select class="form-select" name="family_members_data[${memberId}][family_role]" ${isFixed ? 'readonly' : ''}>
                    ${Object.entries(roles).map(([value, text]) => 
                        `<option value="${value}" ${selectedRole === value ? 'selected' : ''}>${text}</option>`).join('')}
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" name="family_members_data[${memberId}][name]" required>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label">Jenis Kelamin</label>
                <select class="form-select" name="family_members_data[${memberId}][gender]" required>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" class="form-control" name="family_members_data[${memberId}][birth_date]" required>
            </div>
            <div class="col-md-2 mb-2 d-flex align-items-end">
                ${showRemoveButton ? 
                    `<button type="button" class="btn btn-sm remove-btn w-100" onclick="removeFamilyMember(${memberId})">
                        <i class="fas fa-trash"></i> Hapus
                    </button>` : 
                    `<button type="button" class="btn btn-sm btn-secondary w-100" disabled>
                        <i class="fas fa-lock"></i> Tetap
                    </button>`
                }
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2">
                <label class="form-label">Pekerjaan Terakhir</label>
                <input type="text" class="form-control" name="family_members_data[${memberId}][last_position]">
            </div>
            <div class="col-md-6 mb-2">
                <label class="form-label">Perusahaan Terakhir</label>
                <input type="text" class="form-control" name="family_members_data[${memberId}][last_company]">
            </div>
        </div>
    </div>
    `;

        container.insertAdjacentHTML('beforeend', html);

        // Untuk field yang fixed, set nilai default untuk jenis kelamin berdasarkan peran
        if (isFixed) {
            const genderSelect = document.querySelector(
                `#familyMember_${memberId} select[name="family_members_data[${memberId}][gender]"]`);
            if (selectedRole === 'ayah' || selectedRole === 'suami/istri' || selectedRole === 'duda/janda') {
                genderSelect.value = 'L';
            } else if (selectedRole === 'ibu') {
                genderSelect.value = 'P';
            }
        }
    }
    // TAMBAHKAN fungsi ini untuk memastikan field tetap tidak bisa dihapus
    function removeFamilyMember(id) {
        const element = document.getElementById(`familyMember_${id}`);
        const removeButton = element.querySelector('.remove-btn');

        // Hanya hapus jika tombol remove ada (bukan field tetap)
        if (removeButton && !removeButton.disabled) {
            element.remove();
            familyMemberCount--;
        }
    }

    function updateFamilyRole(id, value) {
        // Implementasi jika perlu update role tertentu
    }

    // Fungsi untuk menambahkan form pendidikan
    function addEducation() {
        const container = document.getElementById('educationsContainer');
        const html = `
                <div class="education-form" id="education_${educationCount}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6>Pendidikan Tambahan</h6>
                        <button type="button" class="btn btn-sm remove-btn" onclick="removeEducation(${educationCount})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <select class="form-select" name="educations[${educationCount}][education_level]" required>
                                <option value="">Pilih Pendidikan</option>
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                                <option value="SMK">SMK</option>
                                <option value="D1">D1</option>
                                <option value="D2">D2</option>
                                <option value="D3">D3</option>
                                <option value="D4">D4</option>
                                <option value="S1">S1</option>
                                <option value="S2">S2</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control" name="educations[${educationCount}][school_name]" placeholder="Nama Sekolah/Universitas" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <input type="text" class="form-control" name="educations[${educationCount}][city]" placeholder="Kota" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <input type="text" class="form-control" name="educations[${educationCount}][major]" placeholder="Jurusan">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <input type="number" class="form-control" name="educations[${educationCount}][start_year]" placeholder="Tahun Masuk" min="1900" max="2099" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <input type="number" class="form-control" name="educations[${educationCount}][end_year]" placeholder="Tahun Lulus" min="1900" max="2099" required>
                        </div>
                    </div>
                </div>
            `;
        container.insertAdjacentHTML('beforeend', html);
        educationCount++;
    }

    function removeEducation(id) {
        document.getElementById(`education_${id}`).remove();
    }

    // Fungsi untuk menambahkan form sertifikat
    function addCertificate() {
        const container = document.getElementById('certificatesContainer');
        const html = `
                <div class="education-form" id="certificate_${certificateCount}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6>Sertifikat Tambahan</h6>
                        <button type="button" class="btn btn-sm remove-btn" onclick="removeCertificate(${certificateCount})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <input type="text" class="form-control" name="certificates[${certificateCount}][name]" placeholder="Nama Sertifikat/Pelatihan">
                        </div>
                        <div class="col-md-3 mb-3">
                            <input type="text" class="form-control" name="certificates[${certificateCount}][city]" placeholder="Kota">
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control" name="certificates[${certificateCount}][organizer]" placeholder="Penyelenggara">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <input type="number" class="form-control" name="certificates[${certificateCount}][year]" placeholder="Tahun" min="1900" max="2099">
                        </div>
                        <div class="col-md-9 mb-3">
                            <input type="file" class="form-control" name="certificates[${certificateCount}][certificate_file]" accept="image/jpeg,image/png,application/pdf">
                        </div>
                    </div>
                </div>
            `;
        container.insertAdjacentHTML('beforeend', html);
        certificateCount++;
    }

    function removeCertificate(id) {
        document.getElementById(`certificate_${id}`).remove();
    }

    // Fungsi untuk menambahkan form riwayat pekerjaan
    function addEmploymentHistory() {
        const container = document.getElementById('employmentHistoriesContainer');
        const html = `
                <div class="employment-form" id="employmentHistory_${employmentHistoryCount}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6>Pekerjaan Tambahan</h6>
                        <button type="button" class="btn btn-sm remove-btn" onclick="removeEmploymentHistory(${employmentHistoryCount})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="employment_histories[${employmentHistoryCount}][company_name]" placeholder="Nama Perusahaan">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="employment_histories[${employmentHistoryCount}][address]" placeholder="Alamat Perusahaan">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control" name="employment_histories[${employmentHistoryCount}][phone]" placeholder="Telepon">
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="number" class="form-control" name="employment_histories[${employmentHistoryCount}][start_year]" placeholder="Tahun Masuk" min="1900" max="2099">
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="number" class="form-control" name="employment_histories[${employmentHistoryCount}][end_year]" placeholder="Tahun Keluar" min="1900" max="2099">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control" name="employment_histories[${employmentHistoryCount}][position]" placeholder="Jabatan">
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control" name="employment_histories[${employmentHistoryCount}][business_type]" placeholder="Jenis Usaha">
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control" name="employment_histories[${employmentHistoryCount}][employee_count]" placeholder="Jumlah Karyawan">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="employment_histories[${employmentHistoryCount}][last_salary]" placeholder="Gaji Terakhir">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="employment_histories[${employmentHistoryCount}][reason_for_leaving]" placeholder="Alasan Berhenti">
                        </div>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" name="employment_histories[${employmentHistoryCount}][job_description]" rows="3" placeholder="Uraian Tugas & Tanggung Jawab"></textarea>
                    </div>
                </div>
            `;
        container.insertAdjacentHTML('beforeend', html);
        employmentHistoryCount++;
    }

    function removeEmploymentHistory(id) {
        document.getElementById(`employmentHistory_${id}`).remove();
    }

    // Fungsi untuk menambahkan form referensi
    function addReference() {
        const container = document.getElementById('referencesContainer');
        const html = `
                <div class="education-form" id="reference_${referenceCount}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6>Referensi Tambahan</h6>
                        <button type="button" class="btn btn-sm remove-btn" onclick="removeReference(${referenceCount})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="references[${referenceCount}][name]" placeholder="Nama">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="references[${referenceCount}][address]" placeholder="Alamat">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control" name="references[${referenceCount}][phone]" placeholder="Telepon">
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control" name="references[${referenceCount}][occupation]" placeholder="Pekerjaan">
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control" name="references[${referenceCount}][relationship]" placeholder="Hubungan">
                        </div>
                    </div>
                </div>
            `;
        container.insertAdjacentHTML('beforeend', html);
        referenceCount++;
    }

    function removeReference(id) {
        document.getElementById(`reference_${id}`).remove();
    }

    // Fungsi untuk menambahkan form kontak darurat
    function addEmergencyContact() {
        const container = document.getElementById('emergencyContactsContainer');
        const html = `
                <div class="education-form" id="emergencyContact_${emergencyContactCount}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6>Kontak Darurat Tambahan</h6>
                        <button type="button" class="btn btn-sm remove-btn" onclick="removeEmergencyContact(${emergencyContactCount})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="emergency_contacts[${emergencyContactCount}][name]" placeholder="Nama">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="emergency_contacts[${emergencyContactCount}][address]" placeholder="Alamat">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control" name="emergency_contacts[${emergencyContactCount}][phone]" placeholder="Telepon">
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control" name="emergency_contacts[${emergencyContactCount}][occupation]" placeholder="Pekerjaan">
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control" name="emergency_contacts[${emergencyContactCount}][relationship]" placeholder="Hubungan">
                        </div>
                    </div>
                </div>
            `;
        container.insertAdjacentHTML('beforeend', html);
        emergencyContactCount++;
    }

    function removeEmergencyContact(id) {
        document.getElementById(`emergencyContact_${id}`).remove();
    }

    // Fungsi untuk menambahkan form kemampuan bahasa
    function addLanguageSkill() {
        const container = document.getElementById('languageSkillsContainer');
        const html = `
                <div class="education-form" id="languageSkill_${languageSkillCount}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6>Kemampuan Bahasa Tambahan</h6>
                        <button type="button" class="btn btn-sm remove-btn" onclick="removeLanguageSkill(${languageSkillCount})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control" name="language_skills[${languageSkillCount}][language]" placeholder="Bahasa">
                        </div>
                        <div class="col-md-4 mb-3">
                            <select class="form-select" name="language_skills[${languageSkillCount}][level]">
                                <option value="mahir">Mahir</option>
                                <option value="menguasai">Menguasai</option>
                                <option value="pemula">Pemula</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control" name="language_skills[${languageSkillCount}][description]" placeholder="Keterangan">
                        </div>
                    </div>
                </div>
            `;
        container.insertAdjacentHTML('beforeend', html);
        languageSkillCount++;
    }

    function removeLanguageSkill(id) {
        document.getElementById(`languageSkill_${id}`).remove();
    }

    // Fungsi untuk menambahkan form kemampuan komputer
    function addComputerSkill() {
        const container = document.getElementById('computerSkillsContainer');
        const html = `
                <div class="education-form" id="computerSkill_${computerSkillCount}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6>Kemampuan Komputer Tambahan</h6>
                        <button type="button" class="btn btn-sm remove-btn" onclick="removeComputerSkill(${computerSkillCount})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control" name="computer_skills[${computerSkillCount}][program]" placeholder="Program">
                        </div>
                        <div class="col-md-4 mb-3">
                            <select class="form-select" name="computer_skills[${computerSkillCount}][level]">
                                <option value="mahir">Mahir</option>
                                <option value="menguasai">Menguasai</option>
                                <option value="pemula">Pemula</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control" name="computer_skills[${computerSkillCount}][description]" placeholder="Keterangan">
                        </div>
                    </div>
                </div>
            `;
        container.insertAdjacentHTML('beforeend', html);
        computerSkillCount++;
    }

    function removeComputerSkill(id) {
        document.getElementById(`computerSkill_${id}`).remove();
    }

    // Fungsi untuk menambahkan form kegiatan sosial
    function addSocialActivity() {
        const container = document.getElementById('socialActivitiesContainer');
        const html = `
                <div class="education-form" id="socialActivity_${socialActivityCount}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6>Kegiatan Sosial Tambahan</h6>
                        <button type="button" class="btn btn-sm remove-btn" onclick="removeSocialActivity(${socialActivityCount})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <input type="text" class="form-control" name="social_activities[${socialActivityCount}][organization]" placeholder="Nama Organisasi">
                        </div>
                        <div class="col-md-5 mb-3">
                            <input type="text" class="form-control" name="social_activities[${socialActivityCount}][address]" placeholder="Alamat">
                        </div>
                        <div class="col-md-2 mb-3">
                            <input type="number" class="form-control" name="social_activities[${socialActivityCount}][year]" placeholder="Tahun" min="1900" max="2099">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="social_activities[${socialActivityCount}][position]" placeholder="Jabatan">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="social_activities[${socialActivityCount}][activity_type]" placeholder="Macam Kegiatan">
                        </div>
                    </div>
                </div>
            `;
        container.insertAdjacentHTML('beforeend', html);
        socialActivityCount++;
    }

    function removeSocialActivity(id) {
        document.getElementById(`socialActivity_${id}`).remove();
    }

    function buildFullAddress() {
        const street = document.getElementById('street').value;
        const rt = document.getElementById('rt').value;
        const rw = document.getElementById('rw').value;
        const village = document.getElementById('village').value;
        const district = document.getElementById('district').value;
        const city = document.getElementById('city').value;
        const province = document.getElementById('province').value;

        let addressParts = [];

        if (street) addressParts.push(street);
        if (rt || rw) {
            addressParts.push(`RT ${rt || '00'}/RW ${rw || '00'}`);
        }
        if (village) addressParts.push(`Desa/Kel. ${village}`);
        if (district) addressParts.push(`Kec. ${district}`);
        if (city) addressParts.push(`Kab./Kota ${city}`);
        if (province) addressParts.push(`Prov. ${province}`);

        const fullAddress = addressParts.join(', ');
        document.getElementById('address').value = fullAddress;

        return fullAddress;
    }

    // Fungsi untuk menampilkan alert konfirmasi gender
function showGenderConfirmationAlert() {
    const jobGender = document.getElementById('job_gender').value;
    let genderMessage = '';
    
    if (jobGender === 'pria') {
        genderMessage = 'Pastikan jenis kelamin Anda adalah Pria untuk menyesuaikan kebutuhan kami';
    } else if (jobGender === 'wanita') {
        genderMessage = 'Pastikan jenis kelamin Anda adalah Wanita untuk menyesuaikan kebutuhan kami';
    } else {
        genderMessage = 'Pastikan jenis kelamin Anda adalah Pria/Wanita untuk menyesuaikan kebutuhan kami';
    }
    
    return Swal.fire({
        title: 'Konfirmasi Jenis Kelamin',
        text: genderMessage,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: 'var(--primary-color)',
        cancelButtonColor: 'var(--secondary-color)',
        confirmButtonText: 'Ya, Sudah Benar',
        cancelButtonText: 'Periksa Kembali'
    });
}

    // Validasi sebelum submit
    document.getElementById('applicationForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        Swal.fire({
            title: 'Kirim Lamaran?',
            text: "Pastikan semua data yang diisi sudah benar",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: 'var(--primary-color)',
            cancelButtonColor: 'var(--secondary-color)',
            confirmButtonText: 'Ya, Kirim Sekarang!',
            cancelButtonText: 'Periksa Lagi'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Sedang Mengirim...',
                    html: 'Lamaran Anda sedang diproses',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();

                        fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content
                                },
                                credentials: 'include'
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Sukses!',
                                        text: data.message,
                                        icon: 'success',
                                        confirmButtonColor: 'var(--primary-color)'
                                    }).then(() => {
                                        window.location.href = data.redirect;
                                    });
                                } else {
                                    throw new Error(data.message ||
                                        'Terjadi kesalahan');
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error!',
                                    text: error.message,
                                    icon: 'error'
                                });
                            });
                    }
                });
            }
        });

    });

    // TAMBAHKAN event listener untuk status perkawinan saat form dimuat
    document.addEventListener('DOMContentLoaded', function() {
        // Update progress bar untuk step pertama
        updateProgressBar(1);

        // Tambahkan event listener untuk foto
        document.getElementById('photo').addEventListener('change', function() {
            previewImage(this, 'photoPreview');
        });

        // Inisialisasi status perkawinan saat pertama kali load
        const maritalStatus = document.getElementById('marital_status');
        if (maritalStatus) {
            // Panggil toggleFamilyForm dengan nilai awal
            toggleFamilyForm(maritalStatus.value);

            // Tambahkan event listener untuk perubahan
            maritalStatus.addEventListener('change', function() {
                toggleFamilyForm(this.value);
            });
        }

        // Juga panggil saat form pertama kali dimuat
        toggleFamilyForm(maritalStatus.value);
    });
    </script>
</body>

</html>