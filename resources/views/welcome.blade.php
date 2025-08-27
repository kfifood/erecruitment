<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>K-CRAB</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css')}}" rel="stylesheet">

</head>
<body>
    @include('layouts.partials.navbar')
    <!-- Welcome Section -->
<section class="welcome-section">
    <div class="container h-100" style="max-width: 1400px;">
        <div class="row align-items-center h-100">
            <div class="col-lg-4 col-md-6">
                <h2 class="welcome_title animate__animated animate__fadeInUp">
                    Selamat Datang di <span class="highlight-text">K-CRAB</span><br><br>
                    <p style="color: #4e4e4e; font-size: 1.5rem;">
                        Kirana Cyber Recruitment
</p>
                    <p style="color: #4e4e4e; font-size: 1.5rem;">
                        Automated Benchmark</>
                </h2>
                <p class="lead mb-4 animate__animated animate__fadeInUp animate__delay-1s" style="color: #4e4e4e;">
                    Bergabunglah bersama kami dan kembangkan karier Anda di lingkungan kerja yang inovatif, kolaboratif, dan dinamis.
                </p>
                <div class="animate__animated animate__fadeInUp animate__delay-2s">
                    <a href="#open-positions" class="btn-rekrutmen me-3">
                        Lihat Posisi yang Dibuka <i class="fas fa-arrow-down ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


    <!-- Open Positions Section -->
<section id="open-positions" class="open-section py-5">
    <div class="container">
        <h2 class="section-title animate__animated animate__fadeIn">Posisi Terbuka</h2>
        
        <div class="row g-4">
            @foreach($jobs as $job)
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4 animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.1 }}s">
                <div class="job-card h-100 p-3">
                    <div class="d-flex align-items-start mb-3">
                        <i class="fas fa-briefcase me-3 mt-1" style="color: #30318B; font-size: 1.1rem;"></i>
                        <div>
                            <h5 class="mb-2 fw-bold text-primary">{{ $job->position }}</h5>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-map-marker-alt me-2 text-muted" style="font-size: 0.9rem;"></i>
                                <span class="text-secondary" style="font-size: 0.9rem;">{{ Str::limit($job->address, 35) }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-graduation-cap me-2 text-muted" style="font-size: 0.9rem;"></i>
                                <span class="text-secondary" style="font-size: 0.9rem;">
                                    @foreach($job->educations as $education)
                                        {{ $education->level }}@if(!$loop->last), @endif
                                    @endforeach
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('jobs.show.public', $job->id) }}" target="_blank" class="btn btn-lamar-outline w-100 py-2" style="font-size: 0.9rem;">
                        Lihat dan Lamar<i class="fas fa-chevron-right ms-2"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        
        @if($jobs->isEmpty())
            <div class="text-center py-4 animate__animated animate__fadeIn">
                <div class="py-5">
                    <i class="fas fa-search fa-4x mb-4" style="color: #30318b;"></i>
                    <h4 class="mb-3">Tidak ada posisi terbuka saat ini</h4>
                    <p class="text-muted">Silakan cek kembali nanti untuk kesempatan bergabung dengan kami</p>
                </div>
            </div>
        @endif
    </div>
</section>
    
    <footer class="footer py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="mb-0">&copy; 2025. PT Kirana Food International. All rights reserved.</p>
                    <div class="icon-sosmed">
                        <a href="https://kfifood.com/" class="mx-2" target="_blank" style="color: #4e4e4e;"><i class="fas fa-globe"></i></a>
                        <a href="https://www.linkedin.com/company/kirana-food-international/" target="_blank" class="mx-2" style="color: #4e4e4e;"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://www.instagram.com/kiranafood.international/" class="mx-2" target="_blank" style="color: #4e4e4e;"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery, Popper.js, dan Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>