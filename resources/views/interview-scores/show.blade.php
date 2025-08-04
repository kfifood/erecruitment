<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Hasil Wawancara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        
        .card {
            margin-bottom: 20px;
            border-radius: 0;
        }
        
        .card-header {
            padding: 10px 15px;
            font-size: 1.1rem;
        }
        
        .progress {
            background-color: #f0f0f0;
            border-radius: 3px;
        }
        
        .progress-bar {
            border-radius: 3px;
        }
        
        input[type="radio"] {
            transform: scale(1.2);
        }
        
        .form-control-plaintext {
            padding: 0.375rem 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            width: 80%;
            min-height: 100px;
            background-color: #fff;
        }
        
        h2, h4, h5 {
            color: #000;
            font-weight: bold;
        }
        
        .bg-light {
            background-color: #f8f9fa !important;
        }
        /* Style untuk radio button yang disabled */
input[type="radio"]:disabled {
    /* Warna lingkaran luar */
    border-color: #ddd;
    /* Ukuran radio button */
    width: 18px;
    height: 18px;
}

/* Style untuk radio button yang checked dan disabled */
input[type="radio"]:checked:disabled {
    /* Warna bagian dalam radio button */
    background-color: #fff;
    /* Warna lingkaran luar */
    border-color: #30318B;
}

/* Style untuk tampilan custom radio button */
input[type="radio"] {
    /* Hilangkan tampilan default */
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    /* Buat lingkaran */
    border: 2px solid #ccc;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    outline: none;
    cursor: pointer;
    vertical-align: middle;
    position: relative;
    margin: 0;
}

/* Style ketika radio button dipilih */
input[type="radio"]:checked {
    border-color: #30318B;
}

/* Style untuk titik tengah radio button yang dipilih */
input[type="radio"]:checked::before {
    content: "";
    display: block;
    width: 10px;
    height: 10px;
    background-color: #30318B;
    border-radius: 50%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
  /* Style tambahan untuk chart */
    .chart-container {
        position: relative;
        margin: auto;
    }
    .chart-legend {
        list-style: none;
        padding-left: 0;
    }
    .chart-legend li {
        display: inline-block;
        margin-right: 10px;
    }
    .chart-legend li span {
        display: inline-block;
        width: 12px;
        height: 12px;
        margin-right: 5px;
    }
    </style>
</head>
<body>
    <div class="container" style="font-family: Arial, sans-serif;">
        <!-- Header dengan logo dan judul dalam kotak -->
        <div class="card" style="border:none;">
            <div class="card-body text-center py-3">
                <div class="d-flex justify-content-center align-items-center">
                    @if(file_exists(public_path('logo.png')))
                        <img src="{{ asset('logo.png') }}" alt="Logo Perusahaan" style="height: 50px; margin-right: 20px;">
                    @endif
                    <h2 class="mb-0" style="color: #000; font-weight: bold; font-size:2rem;">HASIL WAWANCARA CALON STAFF</h2>
                </div>
            </div>
        </div>

        <!-- Kotak informasi kandidat -->
        <div class="card" style="border:none;">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama Kandidat:</strong> {{ $interviewScore->interview->application->full_name }}</p>
                        <p><strong>Posisi:</strong> {{ $interviewScore->interview->application->job->position }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Interviewer:</strong> {{ $interviewScore->interview->interviewer->user->name ?? 'N/A' }}</p>
                        <p><strong>Tanggal Wawancara:</strong> {{ $interviewScore->interview->interview_date->format('d F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel penilaian aspek -->
        <div class="card mb-4" style="border-top:1px solid #ddd;">
            <div class="card-header bg-white">
                <h4 class="mb-0" style="color: #000; font-weight: bold;">HASIL PENILAIAN ASPEK</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
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
                            @php
                                $aspects = [
                                    'appearance' => 'Penampilan',
                                    'experience' => 'Pengalaman Kerja',
                                    'work_motivation' => 'Kemauan Kerja',
                                    'problem_solving' => 'Problem Solving',
                                    'leadership' => 'Leadership',
                                    'communication' => 'Komunikasi',
                                    'job_knowledge' => 'Pengetahuan Pekerjaan'
                                ];
                            @endphp

                            @foreach($aspects as $field => $label)
                            <tr>
                                <td>{{ $label }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-between">
                                        @for($i = 1; $i <= 3; $i++)
                                        <div>
                                            <input type="radio" 
                                                   name="{{ $field }}" 
                                                   value="{{ $i }}"
                                                   {{ $interviewScore->$field == $i ? 'checked' : '' }}
                                                   disabled>
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
                                                   {{ $interviewScore->$field == $i ? 'checked' : '' }}
                                                   disabled>
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
                                                   {{ $interviewScore->$field == $i ? 'checked' : '' }}
                                                   disabled>
                                        </div>
                                        @endfor
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Tabel untuk aspek penting -->
                <div class="table-responsive mt-4">
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
                                                   {{ $interviewScore->discipline == $i ? 'checked' : '' }}
                                                   disabled>
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
                                                   {{ $interviewScore->discipline == $i ? 'checked' : '' }}
                                                   disabled>
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
                                                   {{ $interviewScore->discipline == $i ? 'checked' : '' }}
                                                   disabled>
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
                                                   {{ $interviewScore->attitude == $i ? 'checked' : '' }}
                                                   disabled>
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
                                                   {{ $interviewScore->attitude == $i ? 'checked' : '' }}
                                                   disabled>
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
                                                   {{ $interviewScore->attitude == $i ? 'checked' : '' }}
                                                   disabled>
                                        </div>
                                        @endfor
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tabel untuk aspek khusus -->
                @if($interviewScore->specialScores->count() > 0)
                <div class="mt-4">
                    <h5 class="mb-3" style="color: #30318B; font-weight: bold;">PENILAIAN KHUSUS</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Aspek Khusus</th>
                                    <th>Skor (1-9)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($interviewScore->specialScores as $specialScore)
                                <tr>
                                    <td>{{ $specialScore->criteria_name }}</td>
                                    <td>{{ $specialScore->score }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>

      <!-- Grafik penilaian -->
<div class="card mb-4" style="border: 1px solid #ddd;">
    <div class="card-header bg-light">
        <h4 class="mb-0" style="color: #30318B; font-weight: bold;">GRAFIK PENILAIAN</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Bar Chart dengan warna berbeda -->
            <div class="col-md-6">
                <div style="height: 300px;">
                    <canvas id="combinedBarChart"></canvas>
                </div>
            </div>
            
            <!-- Donut Charts terpisah -->
            <div class="col-md-6">
                <div class="row">
                    <!-- Kelebihan -->
                    <div class="col-md-6">
                        <div style="height: 140px;">
                            <canvas id="strengthChart"></canvas>
                        </div>
                        <h6 class="text-center mt-2" style="color: #28a745;">Kelebihan (≥7)</h6>
                    </div>
                    
                    <!-- Kekurangan -->
                    <div class="col-md-6">
                        <div style="height: 140px;">
                            <canvas id="weaknessChart"></canvas>
                        </div>
                        <h6 class="text-center mt-2" style="color: #dc3545;">Kekurangan (≤3)</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        <div class="card-body" style="background-color: #fff; padding: 20px; border: 1px solid #ddd;">
            <!-- Kesimpulan -->
            <div class="form-notes mb-4" style="background-color: #fff">
                <label style="font-weight: bold;">Kesimpulan:</label>
                <p class="form-control-plaintext">{{ $interviewScore->notes ?? 'Tidak ada catatan' }}</p>
            </div>

            <!-- Kategori dan Keputusan -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-group">
                        <label style="font-weight: bold;">Kategori Rekomendasi:</label>
                        @php
                            $categoryClass = '';
                            if($interviewScore->final_category == 'Disarankan') {
                                $categoryClass = 'bg-success';
                            } elseif($interviewScore->final_category == 'Cukup Disarankan') {
                                $categoryClass = 'bg-warning text-dark';
                            } else {
                                $categoryClass = 'bg-danger';
                            }
                        @endphp
                        <p>
                            <span class="badge {{ $categoryClass }}" style="font-size: 1rem;">
                                {{ $interviewScore->final_category }}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label style="font-weight: bold;">Keputusan:</label>
                        <p>
                            <span class="badge bg-success" style="font-size: 1rem;">
                                Diterima (Hired)
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tanda tangan -->
            <div class="row">
                <div class="col-md-6">
                    <!-- Kolom kosong untuk alignment -->
                </div>
                <div class="col-md-6 text-center">
                    <p style="margin-bottom: 70px;">Interviewer,</p>
                    <p style="border-bottom: 1px solid #000; width: 200px; margin: 0 auto;"></p>
                    <p style="font-weight: bold; margin-top: 5px;">
                        {{ $interviewScore->interview->interviewer->user->name ?? 'N/A' }}<br>
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('interview-scores.hired') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Hired
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Script untuk Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data untuk grafik
        const mainAspects = {
            'Penampilan': {{ $interviewScore->appearance }},
            'Pengalaman': {{ $interviewScore->experience }},
            'Motivasi Kerja': {{ $interviewScore->work_motivation }},
            'Problem Solving': {{ $interviewScore->problem_solving }},
            'Leadership': {{ $interviewScore->leadership }},
            'Komunikasi': {{ $interviewScore->communication }},
            'Pengetahuan': {{ $interviewScore->job_knowledge }},
            'Kedisiplinan': {{ $interviewScore->discipline }},
            'Etika': {{ $interviewScore->attitude }}
        };

        @if($interviewScore->specialScores->count() > 0)
            @foreach($interviewScore->specialScores as $specialScore)
                mainAspects['{{ $specialScore->criteria_name }}'] = {{ $specialScore->score }};
            @endforeach
        @endif

        // Hitung kelebihan dan kekurangan
        let strengths = 0;
        let weaknesses = 0;
        Object.values(mainAspects).forEach(score => {
            if(score >= 7) strengths++;
            if(score <= 3) weaknesses++;
        });
        const totalAspects = Object.keys(mainAspects).length;
        const strengthPercentage = Math.round((strengths / totalAspects) * 100);
        const weaknessPercentage = Math.round((weaknesses / totalAspects) * 100);
        
        // Warna berbeda untuk setiap batang
        const barColors = [
            '#30318B', '#4e54c8', '#6a5acd', '#7b68ee', '#9370db',
            '#8a2be2', '#9932cc', '#ba55d3', '#da70d6', '#d8bfd8',
            '#dda0dd', '#ee82ee', '#ff00ff', '#ff69b4', '#ff1493'
        ];

        // 1. Bar Chart dengan warna berbeda
        const barCtx = document.getElementById('combinedBarChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(mainAspects),
                datasets: [{
                    data: Object.values(mainAspects),
                    backgroundColor: Object.keys(mainAspects).map((_, index) => {
                        return barColors[index % barColors.length];
                    }),
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 9,
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 45,
                            font: {
                                size: 10
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Skor: ${context.raw}/9`;
                            },
                            afterLabel: function(context) {
                                const score = context.raw;
                                if(score >= 7) return 'Kategori: Baik';
                                if(score >= 4) return 'Kategori: Cukup';
                                return 'Kategori: Rendah';
                            }
                        }
                    }
                }
            }
        });

        // 2. Strength Donut Chart
        const strengthCtx = document.getElementById('strengthChart').getContext('2d');
        new Chart(strengthCtx, {
            type: 'doughnut',
            data: {
                labels: ['Kelebihan', 'Lainnya'],
                datasets: [{
                    data: [strengths, totalAspects - strengths],
                    backgroundColor: ['#28a745', '#f8f9fa'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw} aspek (${context.raw === strengths ? strengthPercentage : 100-strengthPercentage}%)`;
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: `${strengthPercentage}%`,
                        position: 'bottom',
                        font: {
                            size: 18,
                            weight: 'bold'
                        },
                        color: '#28a745'
                    }
                },
                cutout: '70%',
            }
        });

        // 3. Weakness Donut Chart
        const weaknessCtx = document.getElementById('weaknessChart').getContext('2d');
        new Chart(weaknessCtx, {
            type: 'doughnut',
            data: {
                labels: ['Kekurangan', 'Lainnya'],
                datasets: [{
                    data: [weaknesses, totalAspects - weaknesses],
                    backgroundColor: ['#dc3545', '#f8f9fa'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw} aspek (${context.raw === weaknesses ? weaknessPercentage : 100-weaknessPercentage}%)`;
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: `${weaknessPercentage}%`,
                        position: 'bottom',
                        font: {
                            size: 18,
                            weight: 'bold'
                        },
                        color: '#dc3545'
                    }
                },
                cutout: '70%',
            }
        });
    });
</script>
</body>
</html>

