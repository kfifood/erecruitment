<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $job->position }} | {{ $job->location }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <style>
        body {
            font-family: 'Inter', sans-serif;

            background-color: #f8f9fa;
            color: #333;
        }
        .job-header {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 8px 8px 0 0;
            display: flex;
            gap: 2rem;
            align-items: flex-start;
        }
        .company-logo {
            height: 80px;
            width: 80px;
            object-fit: contain;
            border-radius: 8px;
        }
        .job-info {
            flex: 1;
        }
        .job-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.3rem;
            font-family: 'Inter', sans-serif;

        }
        .job-company {
            font-size: 1rem;
            font-weight: 600;
            color: #30318B;
            margin-bottom: 1rem;
            display: block;
        }
        .job-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }
        .job-meta-item {
            display: flex;
            align-items: center;
            color: #595959;
            gap: 0.5rem;
            font-size: 0.75rem;
        }
        .job-meta-item i {
            font-size: 1rem;
            color: #acbcce;
        }
        .apply-btn-container {
            margin-top: 0.5rem;
        }
        .apply-btn {
            background-color: #30318B;
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        .apply-btn:hover {
            background-color: #153280;
            transform: translateY(-2px);
        }
        .job-content {
            background-color: white;
            padding: 2rem;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 1.5rem 0 1rem;
            font-family: 'Inter', sans-serif;

        }
        .section-divider {
            border-top: 1px solid #e0e0e0;
            margin: 1.5rem 0;
        }
        .requirement-list, .description-list {
            padding-left: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .requirement-list li, .description-list li {
            margin-bottom: 0.5rem;
            list-style-type: '- ';
            padding-left: 0.5rem;
            color: #595959;
        }
        .job-footer {
            text-align: center;
            margin-top: 2rem;
            padding: 1.5rem;
            color: #6c757d;
            font-size: 0.9rem;
        }
        @media (max-width: 768px) {
            .job-header {
                flex-direction: column;
                padding: 1.5rem;
                gap: 1rem;
            }
            .company-logo {
                height: 60px;
                width: 60px;
            }
            .job-title {
                font-size: 1.5rem;
            }
            .job-meta {
                gap: 1rem;
            }
            .job-content {
                padding: 1.5rem;
            }
            .apply-btn-container {
                align-self: flex-end;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-4 mb-5">
        <div class="job-header">
            <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="company-logo">
            
            <div class="job-info">
                <h1 class="job-title">{{ $job->position }}</h1>
                <span class="job-company">{{ $job->location }}</span>
                
                <div class="job-meta">
                    <span class="job-meta-item">
                        <i class="fas fa-graduation-cap"></i> 
                        Pendidikan: 
                        @if($job->educations->isNotEmpty())
                            {{ $job->educations->pluck('level')->implode(', ') }}
                        @else
                            Tidak ditentukan
                        @endif
                    </span>
                    <span class="job-meta-item">
                        <i class="fas fa-briefcase"></i> 
                        Pengalaman: 
                        @if(!is_null($job->experience))
                            {{ $job->experience }} tahun
                        @else
                            Tidak ditentukan
                        @endif
                    </span>
                    <span class="job-meta-item">
                        <i class="fas fa-clock"></i> 
                        Batas Lamar: 
                        {{ $job->closing_date ? $job->closing_date->format('d M Y') : 'Tidak ada batas' }}
                    </span>
                </div>
            </div>
            
            <div class="apply-btn-container">
                <a href="{{ route('applications.create.job', $job->id) }}" class="apply-btn text-white">
                    <i class="fas fa-paper-plane me-2"></i> Lamar
    </a>
            </div>
        </div>

        <div class="job-content">
            

            <h3 class="section-title">Persyaratan</h3>
            <ul class="requirement-list">
                @foreach(explode("\n", $job->qualification) as $requirement)
                    @if(trim($requirement))
                        <li>{{ trim($requirement) }}</li>
                    @endif
                @endforeach
            </ul>
                <div class="section-divider"></div>

            <h3 class="section-title">Deskripsi Pekerjaan</h3>
            <ul class="description-list">
                @foreach(explode("\n", $job->description) as $desc)
                    @if(trim($desc))
                        <li>{{ trim($desc) }}</li>
                    @endif
                @endforeach
            </ul>

            <div class="section-divider"></div>
        </div>

        <div class="job-footer">
            © {{ date('Y') }} PT Kirana Food International. All Rights Reserved.
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>