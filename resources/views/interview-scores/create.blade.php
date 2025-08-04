<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>K-JOBS - Buat Penilaian Interview</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        .label-rapi {
            display: inline-block;
            min-width: 120px;
        }
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }
        .card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .card-header {
            background-color: #FEFEFE;
            font-weight: bold;
        }
        .table th {
            background-color: #f1f1f1;
        }
        .btn-primary {
            background-color: #30318B;
            border-color: #30318B;
        }
        .card-header h3 {
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="card">
            <div class="card-header" style="display: flex; align-items: center; justify-content: center; height: 10vh; color: #30318B; text-align: center; font-size: 1.5rem;">
                <h3>FORM WAWANCARA CALON STAFF</h3>
            </div>
            <div class="card-body">
                <!-- Data Interview -->
                <div class="row mb-4">
                    <div class="data-interview">
                        <p><strong class="label-rapi">Kandidat   :</strong> {{ $interview->application->full_name }}</p>
                        <p><strong class="label-rapi">Interviewer:</strong> {{ $interview->interviewer->user->name ?? 'N/A' }}</p>
                        <p><strong class="label-rapi">Tanggal    :</strong> {{ $interview->interview_date->format('d/m/Y') }}</p>
                    </div>
                </div>

                <!-- Form Penilaian -->
                <form method="POST" action="{{ route('interview-scores.store', $interview->id) }}" novalidate>
                    @csrf
                    <input type="hidden" name="interview_id" value="{{ $interview->id }}">

                    <!-- Penilaian Utama -->
                    <div class="mb-5">
                        <h5 class="mb-3 fw-bold" style="font-size: 1.2rem; border-bottom: 2px solid #30318B; padding-bottom: 5px; color: #30318B;">
                            <i class="fas fa-star me-2"></i>ASPEK PENILAIAN
                        </h5>
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th style="text-align:center; vertical-align:middle; font-size:1rem;">Aspek Penilaian Utama</th>
                                    <th width="150px">
                                        <div class="text-center">Rendah</div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <span>1</span>
                                            <span>2</span>
                                            <span>3</span>
                                        </div>
                                    </th>
                                    <th width="150px">
                                        <div class="text-center">Cukup</div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <span>4</span>
                                            <span>5</span>
                                            <span>6</span>
                                        </div>
                                    </th>
                                    <th width="150px">
                                        <div class="text-center">Baik</div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <span>7</span>
                                            <span>8</span>
                                            <span>9</span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach([
                                    'appearance' => 'Penampilan',
                                    'experience' => 'Pengalaman Kerja',
                                    'work_motivation' => 'Kemauan Kerja',
                                    'problem_solving' => 'Problem Solving',
                                    'leadership' => 'Leadership',
                                    'communication' => 'Komunikasi',
                                    'job_knowledge' => 'Pengetahuan Pekerjaan'
                                ] as $field => $label)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-between">
                                            @for($i = 1; $i <= 3; $i++)
                                            <div>
                                                <input type="radio" 
                                                       name="{{ $field }}" 
                                                       value="{{ $i }}"
                                                       class="score-radio"
                                                       data-field="{{ $field }}"
                                                       required>
                                            </div>
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-between">
                                            @for($i = 4; $i <= 6; $i++)
                                            <div>
                                                <input type="radio" 
                                                       name="{{ $field }}" 
                                                       value="{{ $i }}"
                                                       class="score-radio"
                                                       data-field="{{ $field }}">
                                            </div>
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-between">
                                            @for($i = 7; $i <= 9; $i++)
                                            <div>
                                                <input type="radio" 
                                                       name="{{ $field }}" 
                                                       value="{{ $i }}"
                                                       class="score-radio"
                                                       data-field="{{ $field }}">
                                            </div>
                                            @endfor
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Penilaian Penting -->
                    <div class="mb-5">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th style="text-align:center; vertical-align:middle; font-size:1rem;">Aspek Penilaian Penting</th>
                                    <th width="150px">
                                        <div class="text-center">Rendah</div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <span>1</span>
                                            <span>2</span>
                                            <span>3</span>
                                        </div>
                                    </th>
                                    <th width="150px">
                                        <div class="text-center">Cukup</div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <span>4</span>
                                            <span>5</span>
                                            <span>6</span>
                                        </div>
                                    </th>
                                    <th width="150px">
                                        <div class="text-center">Baik</div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <span>7</span>
                                            <span>8</span>
                                            <span>9</span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Kedisiplinan</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-between">
                                            @for($i = 1; $i <= 3; $i++)
                                            <div>
                                                <input type="radio" 
                                                       name="discipline" 
                                                       value="{{ $i }}"
                                                       class="score-radio"
                                                       data-field="discipline">
                                            </div>
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-between">
                                            @for($i = 4; $i <= 6; $i++)
                                            <div>
                                                <input type="radio" 
                                                       name="discipline" 
                                                       value="{{ $i }}"
                                                       class="score-radio"
                                                       data-field="discipline">
                                            </div>
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-between">
                                            @for($i = 7; $i <= 9; $i++)
                                            <div>
                                                <input type="radio" 
                                                       name="discipline" 
                                                       value="{{ $i }}"
                                                       class="score-radio"
                                                       data-field="discipline">
                                            </div>
                                            @endfor
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Etika</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-between">
                                            @for($i = 1; $i <= 3; $i++)
                                            <div>
                                                <input type="radio" 
                                                       name="attitude" 
                                                       value="{{ $i }}"
                                                       class="score-radio"
                                                       data-field="attitude">
                                            </div>
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-between">
                                            @for($i = 4; $i <= 6; $i++)
                                            <div>
                                                <input type="radio" 
                                                       name="attitude" 
                                                       value="{{ $i }}"
                                                       class="score-radio"
                                                       data-field="attitude">
                                            </div>
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-between">
                                            @for($i = 7; $i <= 9; $i++)
                                            <div>
                                                <input type="radio" 
                                                       name="attitude" 
                                                       value="{{ $i }}"
                                                       class="score-radio"
                                                       data-field="attitude">
                                            </div>
                                            @endfor
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Penilaian Khusus -->
                    <div class="mb-5">
                        <h5 class="mb-3 fw-bold" style="font-size: 1.2rem; border-bottom: 2px solid #30318b; padding-bottom: 5px; color:#30318b;">
                            <i class="fas fa-star-half-alt me-2"></i>PENILAIAN KHUSUS
                        </h5>
                        
                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-secondary" id="addSpecialCriteria">
                                <i class="fas fa-plus"></i> Tambah Penilaian
                            </button>
                        </div>
                        
                        <div id="specialCriteriaContainer" class="border rounded p-3 bg-light">
                            <!-- Penilaian khusus akan muncul di sini -->
                        </div>
                    </div>

                    <!-- Template untuk Penilaian Khusus -->
                    <template id="specialCriteriaTemplate">
                        <div class="special-criteria-item mb-3 border-bottom pb-3">
                            <div class="row align-items-center">
                                <div class="col-md-5 mb-2 mb-md-0">
                                    <input type="text" 
                                           class="form-control special-criteria-name" 
                                           placeholder="Nama Penilaian (contoh: Bahasa Asing)"
                                           required>
                                </div>
                                <div class="col-md-4 mb-2 mb-md-0">
                                    <select class="form-select special-criteria-score" required>
                                        <option value="">Pilih Nilai (1-9)</option>
                                        @for($i = 1; $i <= 9; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-3 text-md-center">
                                    <button type="button" class="btn btn-sm btn-danger remove-criteria">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Kesimpulan -->
                    <div class="mb-4">
                        <h5 class="mb-3 fw-bold" style="font-size: 1.2rem; border-bottom: 2px solid #30318b; padding-bottom: 5px; color: #30318B;">
                            <i class="fas fa-clipboard-list me-2"></i>KESIMPULAN
                        </h5>
                        <textarea name="notes" class="form-control" rows="4" placeholder="Masukkan kesimpulan hasil wawancara..."></textarea>
                    </div>

                    <!-- Hasil Perhitungan -->
                    <div class="mb-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-dark" style="font-size: 1.2rem;">
                                    <i class="fas fa-calculator me-2"></i>HASIL AKHIR
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>Total Skor: <strong id="finalScore" class="fs-4">0</strong></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p>Kategori: 
                                            <span id="finalCategory" class="badge bg-secondary fs-6">Belum dinilai</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn" style="background-color:#30318B; color:white;">
                            <i class="fas fa-save"></i> Simpan Penilaian
                        </button>
                        <a href="{{ route('interview-scores.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Fungsi untuk menghitung total skor
    function calculateScore() {
        const mainFields = [
            'appearance', 'experience', 'work_motivation',
            'problem_solving', 'leadership', 'communication', 'job_knowledge',
            'discipline', 'attitude'
        ];
        
        let total = 0;
        
        mainFields.forEach(field => {
            const selected = document.querySelector(`input[name="${field}"]:checked`);
            if (selected) total += parseInt(selected.value);
        });
        
        const specialScores = document.querySelectorAll('.special-criteria-score');
        specialScores.forEach(select => {
            if (select.value) total += parseInt(select.value);
        });

        document.getElementById('finalScore').textContent = total;

        // Tentukan kategori
        let category, badgeClass;
        if (total >= 7 && total <= 35) {
            category = 'Tidak Disarankan';
            badgeClass = 'bg-danger';
        } else if (total >= 36 && total <= 70) {
            category = 'Cukup Disarankan';
            badgeClass = 'bg-warning';
        } else {
            category = 'Disarankan';
            badgeClass = 'bg-success';
        }

        const categoryElement = document.getElementById('finalCategory');
        categoryElement.textContent = category;
        categoryElement.className = `badge ${badgeClass} fs-6`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('specialCriteriaContainer');
        const template = document.getElementById('specialCriteriaTemplate');
        const addButton = document.getElementById('addSpecialCriteria');
        
        // Pastikan semua elemen ada
        if (!container || !template || !addButton) {
            console.error('Elemen penting tidak ditemukan!');
            return;
        }
        
        let criteriaCount = 0;
        const maxCriteria = 5;

        // Fungsi untuk menambahkan kriteria khusus
        function addSpecialCriteria() {
            if (criteriaCount >= maxCriteria) {
                alert('Maksimal ' + maxCriteria + ' penilaian khusus');
                return;
            }
            
            // Clone template
            const newItem = template.content.cloneNode(true);
            const criteriaItem = newItem.querySelector('.special-criteria-item');
            
            // Update nama field dengan index
            const nameInput = criteriaItem.querySelector('.special-criteria-name');
            const scoreSelect = criteriaItem.querySelector('.special-criteria-score');
            
            nameInput.name = `special_criteria[${criteriaCount}][name]`;
            scoreSelect.name = `special_criteria[${criteriaCount}][score]`;
            
            // Tambahkan event listener untuk tombol hapus
            criteriaItem.querySelector('.remove-criteria').addEventListener('click', function() {
                criteriaItem.remove();
                criteriaCount--;
                calculateScore();
            });
            
            // Tambahkan ke container
            container.appendChild(criteriaItem);
            criteriaCount++;
            
            // Hitung ulang skor saat nilai diubah
            scoreSelect.addEventListener('change', calculateScore);
        }

        // Event listener untuk tombol tambah
        addButton.addEventListener('click', addSpecialCriteria);

        // Event listener untuk radio button
        const radioButtons = document.querySelectorAll('.score-radio');
        radioButtons.forEach(radio => {
            radio.addEventListener('change', calculateScore);
        });

        // Validasi form sebelum submit
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Validasi penilaian utama
                const mainFields = [
                    'appearance', 'experience', 'work_motivation',
                    'problem_solving', 'leadership', 'communication', 'job_knowledge'
                ];
                
                let isValid = true;
                
                mainFields.forEach(field => {
                    if (!document.querySelector(`input[name="${field}"]:checked`)) {
                        isValid = false;
                        alert(`Harap isi penilaian untuk ${field}`);
                        return;
                    }
                });
                
                // Validasi penilaian khusus
                const specialItems = container.querySelectorAll('.special-criteria-item');
                specialItems.forEach(item => {
                    const name = item.querySelector('.special-criteria-name');
                    const score = item.querySelector('.special-criteria-score');
                    
                    if (!name.value || !score.value) {
                        isValid = false;
                        alert('Harap lengkapi semua penilaian khusus');
                        name.focus();
                        return;
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                }
            });
        }
    });
    </script>
</body>
</html>