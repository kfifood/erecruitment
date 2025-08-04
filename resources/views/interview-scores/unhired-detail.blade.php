<!-- resources/views/interview-scores/unhired-detail.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kandidat Ditolak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .card {
            margin-bottom: 20px;
            border-radius: 0;
            border: 1px solid #ddd;
        }
        .card-header {
            background-color: #f8f9fa;
            padding: 10px 15px;
        }
        .progress {
            background-color: #f0f0f0;
            border-radius: 3px;
        }
        .progress-bar {
            border-radius: 3px;
        }
        h2, h4, h5 {
            color: #000;
            font-weight: bold;
        }
        .bg-light {
            background-color: #f8f9fa !important;
        }
        .aspect-chart {
            font-size: 0.85rem;
        }
        .strength-header, .weakness-header {
            display: flex;
            align-items: center;
            margin: 5px 0;
            font-weight: bold;
        }
        .strength-header {
            color: #28a745;
        }
        .weakness-header {
            color: #dc3545;
        }
        .header-icon {
            margin-right: 5px;
        }
        .strength-bar, .weakness-bar {
            height: 20px;
            background-color: #f8f9fa;
            border-radius: 3px;
            margin-bottom: 8px;
            position: relative;
        }
        .strength-fill {
            height: 100%;
            background-color: rgba(40, 167, 69, 0.3);
            border-radius: 3px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 5px;
        }
        .weakness-fill {
            height: 100%;
            background-color: rgba(220, 53, 69, 0.3);
            border-radius: 3px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 5px;
        }
        .aspect-label {
            font-weight: bold;
        }
        .aspect-score {
            font-size: 0.75rem;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Header -->
    <div class="card" style="border:none;">
        <div class="card-body text-center py-3">
            <div class="d-flex justify-content-center align-items-center">
                @if(file_exists(public_path('logo.png')))
                    <img src="{{ asset('logo.png') }}" alt="Logo Perusahaan" style="height: 50px; margin-right: 20px;">
                @endif
                <h2 class="mb-0" style="color: #000; font-weight: bold; font-size:2rem;">DETAIL KANDIDAT DITOLAK</h2>
            </div>
        </div>
    </div>

    <!-- Informasi Kandidat -->
    <div class="card" style="border:none;">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nama Kandidat:</strong> {{ $score->interview->application->full_name }}</p>
                    <p><strong>Posisi:</strong> {{ $score->interview->application->job->position }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Interviewer:</strong> {{ $score->interview->interviewer->user->name ?? 'N/A' }}</p>
                    <p><strong>Tanggal Wawancara:</strong> {{ $score->interview->interview_date->format('d F Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Analisis -->
    <div class="card mb-4" style="border: 1px solid #ddd;">
        <div class="card-header bg-light">
            <h4 class="mb-0" style="color: #dc3545; font-weight: bold;">ANALISIS PENOLAKAN</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Bar Chart -->
                <div class="col-md-8">
                    <div style="height: 300px;">
                        <canvas id="unhiredBarChart"></canvas>
                    </div>
                </div>
                
                <!-- Donut Charts -->
                <div class="col-md-4">
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

    <!-- Tabel Penilaian -->
    <div class="card mb-4" style="border-top:1px solid #ddd;">
        <div class="card-header bg-white">
            <h4 class="mb-0" style="color: #000; font-weight: bold;">HASIL PENILAIAN ASPEK</h4>
        </div>
        <div class="card-body">
            <!-- Tabel penilaian aspek utama dan khusus -->
            <!-- ... (salin bagian tabel penilaian dari hired-detail.blade.php) ... -->
        </div>
    </div>

    <!-- Kesimpulan dan Keputusan -->
    <div class="card-body" style="background-color: #fff; padding: 20px; border: 1px solid #ddd;">
        <div class="form-notes mb-4">
            <label style="font-weight: bold;">Kesimpulan:</label>
            <p class="form-control-plaintext">{{ $score->notes ?? 'Tidak ada catatan' }}</p>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="form-group">
                    <label style="font-weight: bold;">Kategori Rekomendasi:</label>
                    <p>
                        <span class="badge bg-danger" style="font-size: 1rem;">
                            {{ $score->final_category }}
                        </span>
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label style="font-weight: bold;">Keputusan:</label>
                    <p>
                        <span class="badge bg-danger" style="font-size: 1rem;">
                            Ditolak (Unhired)
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-3">
        <a href="{{ route('interview-scores.unhired') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Ditolak
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data untuk grafik
    const mainAspects = {
        'Penampilan': {{ $score->appearance }},
        'Pengalaman': {{ $score->experience }},
        'Motivasi Kerja': {{ $score->work_motivation }},
        'Problem Solving': {{ $score->problem_solving }},
        'Leadership': {{ $score->leadership }},
        'Komunikasi': {{ $score->communication }},
        'Pengetahuan': {{ $score->job_knowledge }},
        'Kedisiplinan': {{ $score->discipline }},
        'Etika': {{ $score->attitude }}
    };

    @if($score->specialScores->count() > 0)
        @foreach($score->specialScores as $specialScore)
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
    
    // Warna
    const primaryColor = '#dc3545'; // Warna merah untuk unhired
    const successColor = '#28a745';
    const dangerColor = '#dc3545';
    const warningColor = '#ffc107';

    // 1. Bar Chart
    const barCtx = document.getElementById('unhiredBarChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(mainAspects),
            datasets: [{
                label: 'Skor Penilaian',
                data: Object.values(mainAspects),
                backgroundColor: Object.values(mainAspects).map(score => {
                    if(score >= 7) return successColor;
                    if(score <= 3) return dangerColor;
                    return warningColor;
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
                        minRotation: 45
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
                backgroundColor: [successColor, '#f8f9fa'],
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
                    color: successColor
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
                backgroundColor: [dangerColor, '#f8f9fa'],
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
                    color: dangerColor
                }
            },
            cutout: '70%',
        }
    });
});
</script>
</body>
</html>