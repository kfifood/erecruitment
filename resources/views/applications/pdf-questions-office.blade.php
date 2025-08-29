<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PERTANYAAN OFFICE - {{ $application->full_name }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
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
        .question { 
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ddd;
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

    <div class="section">
        <h1>JAWABAN PERTANYAAN OFFICE</h1><br>
        <h2>Daftar Pertanyaan dan Jawaban</h2>
        
        @if($application->questions)
        <div class="question">
            <div class="question-title">1. Apakah Anda pernah melamar di Group / Perusahaan ini sebelumnya?</div>
            <div class="answer">
                <strong>Jawaban:</strong> {{ $application->questions->question_1 }}<br>
                @if($application->questions->question_1_explanation)
                <strong>Penjelasan:</strong> {{ $application->questions->question_1_explanation }}
                @endif
            </div>
        </div>

        <div class="question">
            <div class="question-title">2. Selain perusahaan ini, perusahaan mana lagi Anda melamar kerja saat ini?</div>
            <div class="answer">
                <strong>Jawaban:</strong> {{ $application->questions->question_2 }}
            </div>
        </div>

        <div class="question">
            <div class="question-title">3. Apakah Anda terikat kontrak dengan perusahaan tempat kerja sekarang?</div>
            <div class="answer">
                <strong>Jawaban:</strong> {{ $application->questions->question_3 }}<br>
                @if($application->questions->question_3_explanation)
                <strong>Penjelasan:</strong> {{ $application->questions->question_3_explanation }}
                @endif
            </div>
        </div>

        <div class="question">
            <div class="question-title">4. Apakah anda mempunyai pekerjaan sampingan/part time? Dimana? Sebagai apa?</div>
            <div class="answer">
                <strong>Jawaban:</strong> {{ $application->questions->question_4 }}
            </div>
        </div>

        <div class="question">
            <div class="question-title">5. Keberatankah Anda bila kami minta referensi pada perusahaan tempat lama Anda bekerja?</div>
            <div class="answer">
                <strong>Jawaban:</strong> {{ $application->questions->question_5 }}<br>
                @if($application->questions->question_5_explanation)
                <strong>Penjelasan:</strong> {{ $application->questions->question_5_explanation }}
                @endif
            </div>
        </div>

        <div class="question">
            <div class="question-title">6. Apakah Anda mempunyai teman/sanak saudara yang bekerja di Group perusahaan ini?</div>
            <div class="answer">
                <strong>Jawaban:</strong> {{ $application->questions->question_6 }}<br>
                @if($application->questions->question_6_explanation)
                <strong>Penjelasan:</strong> {{ $application->questions->question_6_explanation }}
                @endif
            </div>
        </div>

        <div class="question">
            <div class="question-title">7. Apakah Anda pernah menderita sakit keras / kronis / kecelakaan berat / operasi?</div>
            <div class="answer">
                <strong>Jawaban:</strong> {{ $application->questions->question_7 }}<br>
                @if($application->questions->question_7_explanation)
                <strong>Penjelasan:</strong> {{ $application->questions->question_7_explanation }}
                @endif
            </div>
        </div>

        <div class="question">
            <div class="question-title">8. Apakah Anda buta warna? Apakah Anda mempunyai penyakit menular?</div>
            <div class="answer">
                <strong>Jawaban:</strong> {{ $application->questions->question_8 }}<br>
                @if($application->questions->question_8_explanation)
                <strong>Penjelasan:</strong> {{ $application->questions->question_8_explanation }}
                @endif
            </div>
        </div>

        <div class="question">
            <div class="question-title">9. Apakah Anda pernah menjalani psikologis / psikotes? Kapan? Dimana?</div>
            <div class="answer">
                <strong>Jawaban:</strong> {{ $application->questions->question_9 }}<br>
                @if($application->questions->question_9_explanation)
                <strong>Penjelasan:</strong> {{ $application->questions->question_9_explanation }}
                @endif
            </div>
        </div>

        <div class="question">
            <div class="question-title">10. Apakah Anda pernah berurusan dengan Polisi karena tindakan kejahatan atau Narkoba?</div>
            <div class="answer">
                <strong>Jawaban:</strong> {{ $application->questions->question_10 }}<br>
                @if($application->questions->question_10_explanation)
                <strong>Penjelasan:</strong> {{ $application->questions->question_10_explanation }}
                @endif
            </div>
        </div>

        <div class="question">
            <div class="question-title">11. Bila diterima, bersediakah Anda bertugas ke luar kota?</div>
            <div class="answer">
                <strong>Jawaban:</strong> {{ $application->questions->question_11 }}<br>
                @if($application->questions->question_11_explanation)
                <strong>Penjelasan:</strong> {{ $application->questions->question_11_explanation }}
                @endif
            </div>
        </div>

        <div class="question">
            <div class="question-title">12. Bila diterima, bersediakah Anda ditempatkan di luar kota?</div>
            <div class="answer">
                <strong>Jawaban:</strong> {{ $application->questions->question_12 }}<br>
                @if($application->questions->question_12_explanation)
                <strong>Penjelasan:</strong> {{ $application->questions->question_12_explanation }}
                @endif
            </div>
        </div>

        <div class="question">
            <div class="question-title">13. Macam pekerjaan / jabatan apakah yang sesuai dengan cita-cita / harapan Anda?</div>
            <div class="answer">
                <strong>Jawaban:</strong> {{ $application->questions->question_13 }}
            </div>
        </div>

        <div class="question">
            <div class="question-title">14. Macam pekerjaan yang bagaimana yang Anda tidak sukai?</div>
            <div class="answer">
                <strong>Jawaban:</strong> {{ $application->questions->question_14 }}
            </div>
        </div>

        <div class="question">
            <div class="question-title">15. Berapa besar penghasilan dan apa saja fasilitas yang Anda harapkan?</div>
            <div class="answer">
                <strong>Jawaban:</strong> {{ $application->questions->question_15 }}
            </div>
        </div>

        <div class="question">
            <div class="question-title">16. Bila diterima, kapankah Anda dapat mulai bekerja?</div>
            <div class="answer">
                <strong>Jawaban:</strong> {{ \Carbon\Carbon::parse($application->questions->question_16)->format('d-m-Y') }}
            </div>
        </div>
        @else
        <p>Tidak ada data pertanyaan yang tersedia.</p>
        @endif
    </div>

    <div class="footer">
        <div>No. Dokumen : FF/KFI/VII-01.01</div>
        <div>Edisi/Revisi : 1/0</div>
        <div>Tanggal : 1-02-2021</div>
        <div>Halaman : Pertanyaan Office</div>
    </div>
</body>
</html>