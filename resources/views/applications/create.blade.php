<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Apply for {{ $job->position }}</title>
    <!-- Di bagian head -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }
        .application-form {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-header h2 {
            color: #30318B;
            font-weight: 700;
        }
        .form-header p {
            color: #495057;
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 2rem;
        }
        .job-title {
            font-size: 1.2rem;
            color: #495057;
            margin-bottom: 20px;
        }
        .required-field::after {
            content: " *";
            color: red;
        }
        .file-upload {
            border: 2px dashed #dee2e6;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            margin-bottom: 15px;
        }
        .file-upload:hover {
            border-color: #30318B;
        }
        .submit-btn {
            background-color: #30318B;
            border: none;
            padding: 10px 25px;
            font-weight: 600;
        }
        .submit-btn:hover {
            background-color: #1a237e;
        }
        .conditional-field {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="application-form">
                    <div class="form-header">
                        <h2>Formulir Lamaran Kerja</h2>
                        <p>PT Kirana Food International </p>
                        <div class="job-title">
                            Posisi: <strong>{{ $job->position }}</strong>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('applications.store.public') }}" enctype="multipart/form-data" id="applicationForm">
                        @csrf
                        <input type="hidden" name="job_id" value="{{ $job->id }}">

                        <div class="mb-3">
                            <label for="full_name" class="form-label required-field">Nama Lengkap</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required autocomplete="off">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label required-field">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required autocomplete="off">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label required-field">Telepon</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required autocomplete="off">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label required-field">Alamat</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="education" class="form-label required-field">Pendidikan Terakhir</label>
                                <select class="form-select" id="education" name="education" required>
                                    <option value="">Pilih Pendidikan</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA">SMA</option>
                                    <option value="SMK">SMK</option>
                                    <option value="D3">D3</option>
                                    <option value="D4">D4</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="birth_date" class="form-label required-field">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                            </div>
                        </div>

                        <!-- Jurusan dan Prodi (akan ditampilkan berdasarkan pendidikan) -->
                        <div class="row" id="majorField" style="display: none;">
                            <div class="col-md-6 mb-3">
                                <label for="major" class="form-label">Jurusan</label>
                                <input type="text" class="form-control" id="major" name="major">
                            </div>
                            <div class="col-md-6 mb-3" id="studyProgramField" style="display: none;">
                                <label for="study_program" class="form-label">Program Studi</label>
                                <input type="text" class="form-control" id="study_program" name="study_program">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required-field">Foto Profil</label>
                            <br><small class="text-danger">format file: Foto_nama lengkap.jpg/png</small>
                            <div class="file-upload">
                                <input type="file" id="photo" name="photo" accept="image/jpeg,image/png" required>
                                <p class="mt-2">Upload file JPG/PNG (maks. 2MB)</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required-field">Curriculum Vitae (CV)</label>
                            <br><small class="text-danger">format file: CV_nama lengkap.pdf</small>
                            <div class="file-upload">
                                <input type="file" id="cv" name="cv" accept=".pdf" required>
                                <p class="mt-2">Upload file PDF (maks. 2MB)</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label required-field">Surat Lamaran (Cover Letter)</label>
                            <br><small class="text-danger">format file: SuratLamaran_nama lengkap.pdf</small>
                            <div class="file-upload">
                                <input type="file" id="cover_letter" name="cover_letter" accept=".pdf" required>
                                <p class="mt-2">Upload file PDF (maks. 2MB)</p>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary submit-btn">
                                <i class="fas fa-paper-plane me-2"></i> Kirim Lamaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Fungsi untuk menangani perubahan pendidikan
    document.getElementById('education').addEventListener('change', function() {
        const education = this.value;
        const majorField = document.getElementById('majorField');
        const studyProgramField = document.getElementById('studyProgramField');
        
        // Sembunyikan semua dulu
        majorField.style.display = 'none';
        studyProgramField.style.display = 'none';
        
        // Reset nilai
        document.getElementById('major').value = '';
        document.getElementById('study_program').value = '';
        
        // Tampilkan sesuai kebutuhan
        if (education === 'SMA' || education === 'SMK') {
            majorField.style.display = 'flex';
        } else if (education === 'D3' || education === 'D4' || education === 'S1' || education === 'S2') {
            majorField.style.display = 'flex';
            studyProgramField.style.display = 'block';
        }
    });

    document.getElementById('applicationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    
    Swal.fire({
        title: 'Kirim Lamaran?',
        text: "Pastikan data yang diisi sudah benar",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#30318B',
        cancelButtonColor: '#6c757d',
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
                        body: new FormData(form),
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Sukses!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#30318B'
                            }).then(() => {
                                window.location.href = data.redirect;
                            });
                        } else {
                            throw new Error(data.message || 'Terjadi kesalahan');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Peringatan!',
                            html: `Lamaran berhasil dikirim, tetapi ada masalah dengan notifikasi WhatsApp.<br><br>${error.message}`,
                            icon: 'warning',
                            confirmButtonColor: '#30318B'
                        }).then(() => {
                            window.location.href = form.querySelector('input[name="job_id"]').value;
                        });
                    });
                }
            });
        }
    });
});
    </script>
</body>
</html>