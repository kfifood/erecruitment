<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PERTANYAAN PRODUCTION - {{ $application->full_name }}</title>
    <style>
        body { 
            font-family: Times,"Times New Roman", serif; 
            font-size: 12px;
            line-height: 1.5;
        }
        .section h1 { 
            color: #000; 
            margin-bottom: 5px;
            font-size: 16px;
            font-weight: bold;
            text-align: center; 
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
        .question-group {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .section-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            padding-bottom: 3px;
            border-bottom: 1px solid #eee;
        }
        .question { 
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #eee;
            border-radius: 4px;
        }
        .question-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .answer {
            padding-left: 20px;
        }
        .footer { 
            margin-top: 30px; 
            text-align: right; 
            font-size: 10px; 
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        
    </div>

    <div class="section">
        <h1>JAWABAN PERTANYAAN PRODUCTION</h1><br>
        
        <h2>Daftar Pertanyaan dan Jawaban</h2>
        
        @if($application->productionQuestions)
        <!-- Kelompok Pertanyaan Kesiapan Kerja -->
        <div class="question-group">
            <h3 class="section-title">Kesiapan Kerja</h3>

            <div class="question">
                <div class="question-title">1. Apakah Anda Bersedia Untuk Kerja Shift?</div>
                <div class="answer">
                    <strong>Jawaban:</strong> {{ $application->productionQuestions->shift_work }}
                </div>
            </div>

            <div class="question">
                <div class="question-title">2. Apakah Anda Bersedia Kerja Dengan Sistem Borongan?</div>
                <div class="answer">
                    <strong>Jawaban:</strong> {{ $application->productionQuestions->piecework_system }}
                </div>
            </div>

            <div class="question">
                <div class="question-title">3. Apakah Anda bersedia jika dipindah posisikan di PT Kirana Food International?</div>
                <div class="answer">
                    <strong>Jawaban:</strong> {{ $application->productionQuestions->position_transfer }}
                </div>
            </div>
        </div>

        <!-- Kelompok Pertanyaan Pengalaman -->
        <div class="question-group">
            <h3 class="section-title">Pengalaman</h3>

            <div class="question">
                <div class="question-title">4. Apakah memiliki pengalaman Organisasi? Jika ya, Jelaskan posisi dan tugas anda</div>
                <div class="answer">
                    <strong>Jawaban:</strong> {{ $application->productionQuestions->organization_experience }}
                </div>
            </div>
        </div>

        <!-- Kelompok Pertanyaan Kesehatan -->
        <div class="question-group">
            <h3 class="section-title">Kesehatan</h3>

            <div class="question">
                <div class="question-title">5. Apakah saat ini anda sedang sakit?</div>
                <div class="answer">
                    <strong>Jawaban:</strong> {{ $application->productionQuestions->current_sickness }}
                </div>
            </div>

            <div class="question">
                <div class="question-title">6. Apakah dalam 6 bulan terakhir anda pernah sakit?</div>
                <div class="answer">
                    <strong>Jawaban:</strong> {{ $application->productionQuestions->recent_sickness }}<br>
                    @if($application->productionQuestions->recent_sickness == 'Ya')
                    <strong>Jenis Penyakit:</strong><br>
                    @if($application->productionQuestions->typhoid == 'Ya') - Typus<br> @endif
                    @if($application->productionQuestions->hepatitis == 'Ya') - Hepatitis<br> @endif
                    @if($application->productionQuestions->tuberculosis == 'Ya') - TBC<br> @endif
                    @if($application->productionQuestions->cyst == 'Ya') - Kista<br> @endif
                    @endif
                </div>
            </div>

            <div class="question">
                <div class="question-title">7. Apakah anda buta warna atau mempunyai penyakit menular?</div>
                <div class="answer">
                    <strong>Buta Warna:</strong> {{ $application->productionQuestions->color_blind }}<br>
                    <strong>Penyakit Menular:</strong> {{ $application->productionQuestions->contagious_disease }}
                </div>
            </div>
        </div>

        <!-- Kelompok Pertanyaan Lainnya -->
        <div class="question-group">
            <h3 class="section-title">Lainnya</h3>

            <div class="question">
                <div class="question-title">8. Apakah anda pernah berurusan dengan Polisi karena tindak kejahatan / Narkoba?</div>
                <div class="answer">
                    <strong>Jawaban:</strong> {{ $application->productionQuestions->police_record }}
                </div>
            </div>

            <div class="question">
                <div class="question-title">9. Apakah anda terikat kontrak kerja dengan perusahaan ditempat kerja sekarang?</div>
                <div class="answer">
                    <strong>Jawaban:</strong> {{ $application->productionQuestions->current_contract }}
                </div>
            </div>

            <div class="question">
                <div class="question-title">10. Macam pekerjaan yang bagaimana anda tidak sukai?</div>
                <div class="answer">
                    <strong>Jawaban:</strong> {{ $application->productionQuestions->disliked_job_types }}
                </div>
            </div>

            <div class="question">
                <div class="question-title">11. Apakah anda bisa mengaplikasikan komputer / mesin produksi?</div>
                <div class="answer">
                    <strong>Jawaban:</strong> {{ $application->productionQuestions->computer_machine_skills }}
                </div>
            </div>
        </div>
        @else
        <p>Tidak ada data pertanyaan production yang tersedia.</p>
        @endif
    </div>

</body>
</html>