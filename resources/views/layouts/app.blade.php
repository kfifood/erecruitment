<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>K-JOBS - @yield('title')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css')}}" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        .dt-buttons .btn {
            margin-right: 5px;
            margin-bottom: 5px;
        }
        .dt-buttons .btn i {
            margin-right: 5px;
        }
           /* Container untuk elemen kontrol DataTables */
    .dataTables-controls {
        width: 100%;
        margin-bottom: 15px;
    }
    
    /* Container untuk tabel yang bisa discroll */
    .table-responsive-container {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 30px;
    }

    /* Pastikan tabel memiliki lebar minimum */
    .table-responsive-container table {
        min-width: 1200px;
        margin-bottom: 0 !important;
    }

    /* Optional: styling untuk scrollbar */
    .table-responsive-container::-webkit-scrollbar {
        height: 8px;
    }
    
    .table-responsive-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .table-responsive-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    .table-responsive-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    /* Perbaikan untuk DataTables layout */
    .dataTables_wrapper .dt-buttons {
        float: left;
        margin-right: 10px;
    }
    
    .dataTables_wrapper .dataTables_filter {
        float: right;
    }
    
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 10px;
    }

        
        /* Style untuk search input */
        .dataTables_filter input {
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-left: 10px;
        }
        
        /* Style untuk pagination */
        .dataTables_paginate .paginate_button {
            padding: 5px 10px;
            margin-left: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .dataTables_paginate .paginate_button.current {
            background: #0d6efd;
            color: white !important;
            border-color: #0d6efd;
        }

        /* Perlebar dropdown length menu */
.dataTables_length select {
    width: 80px !important;  /* Sesuaikan lebar sesuai kebutuhan */
    padding-right: 20px !important; /* Beri ruang untuk panah dropdown */
    margin: 0 5px;
}

/* Style tambahan untuk mempercantik */
.dataTables_length label {
    display: flex;
    align-items: center;
    white-space: nowrap;
}

.dataTables_length {
    margin-right: 10px;
}
    </style>
</head>
<body>

    <div id="app">
    @include('layouts.partials.navbar')
       <div class="main-wrapper d-flex">
            @auth
                @if (!request()->routeIs('home'))
                    @include('layouts.partials.sidebar')
                @endif
            @endauth
            
            <main class="main-content flex-grow-1">
                @yield('content')
            </main>
        </div>
    </div>
   
    <!-- Pindahkan SEMUA script ke sebelum </body> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- DataTables dan plugins -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <!-- Excel/PDF dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

    @stack('scripts')
    <script>
        $(document).ready(function() {
            // Konfigurasi umum untuk semua DataTable
            const commonConfig = {
                dom: '<"d-flex justify-content-between mb-3"Bf>rt<"d-flex justify-content-between mt-3"lip>',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Export Excel',
                        className: 'btn btn-success btn-sm me-2'
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fas fa-file-csv"></i> Export CSV',
                        className: 'btn btn-secondary btn-sm me-2'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-info btn-sm me-2'
                    },
                    {
                        text: '<i class="fas fa-search"></i> Reset',
                        className: 'btn btn-warning btn-sm me-2',
                        action: function (e, dt, node, config) {
                            dt.search('').columns().search('').draw();
                        }
                    },
                    {
                        text: '<i class="fas fa-sync-alt"></i> Reload',
                        className: 'btn btn-primary btn-sm',
                        action: function (e, dt, node, config) {
                            dt.ajax.reload();
                        }
                    }
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "🔍 Search...",
                    lengthMenu: "Tampilkan _MENU_ baris",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: ">",
                        previous: "<"
                    }
                },
                responsive: true
            };
            
            // Inisialisasi untuk employeesTable
            $('#employeesTable').DataTable({
                ...commonConfig,
                // Konfigurasi khusus employeesTable jika ada
            });

            // Inisialisasi untuk divisionsTable
            $('#divisionsTable').DataTable({
                ...commonConfig,
                // Konfigurasi khusus divisionsTable jika ada
            });

            // Inisialisasi untuk jobsTable
            $('#jobsTable').DataTable({
                ...commonConfig,
                // Konfigurasi khusus jobsTable jika ada
            });

            // Inisialisasi untuk listUsersTable
            $('#listUsersTable').DataTable({
                ...commonConfig,
                // Konfigurasi khusus listUsersTable jika ada
            });

            // Inisialisasi untuk applicationsTable
           $('#applicationsTable').DataTable({
    dom: '<"dataTables-controls"<"d-flex justify-content-between"<"dt-buttons"B><"dataTables-search"f>>><"table-responsive-container"rt><"d-flex justify-content-between mt-3"<"dataTables-info"i><"dataTables-paginate"p>>',
    buttons: [
        {
            extend: 'excelHtml5',
            text: '<i class="fas fa-file-excel"></i> Export Excel',
            className: 'btn btn-success btn-sm me-2'
        },
        {
            extend: 'csvHtml5',
            text: '<i class="fas fa-file-csv"></i> Export CSV',
            className: 'btn btn-secondary btn-sm me-2'
        },
        {
            extend: 'print',
            text: '<i class="fas fa-print"></i> Print',
            className: 'btn btn-info btn-sm me-2'
        },
        {
            text: '<i class="fas fa-search"></i> Reset',
            className: 'btn btn-warning btn-sm me-2',
            action: function (e, dt, node, config) {
                dt.search('').columns().search('').draw();
            }
        },
        {
            text: '<i class="fas fa-sync-alt"></i> Reload',
            className: 'btn btn-primary btn-sm',
            action: function (e, dt, node, config) {
                dt.ajax.reload();
            }
        }
    ],
    language: {
        search: "_INPUT_",
        searchPlaceholder: "🔍 Search...",
        lengthMenu: "Tampilkan _MENU_ baris",
        paginate: {
            first: "Pertama",
            last: "Terakhir",
            next: ">",
            previous: "<"
        }
    },
    responsive: false,
    lengthMenu: [
        [5, 10, 25, 50, 100, -1],
        [5, 10, 25, 50, 100, 'All']
    ],
    pageLength: 5
});

            // Inisialisasi untuk interviewScheduleTable
            $('#interviewScheduleTable').DataTable({
                ...commonConfig,
                responsive: false,
                scrollX: true,
                columnDefs: [
                    { 
                        targets: 0,  // Kolom pertama (tanggal)
                        visible: false,  // Sembunyikan kolom dummy
                        orderable: false  // Nonaktifkan sorting
                    }
                ]
            });

            const divisionMap = {
                management: 'Management',
                finance_accounting: 'Finance & Accounting',
                human_resources: 'Human Resources',
                information_technology: 'Information Technology',
                quality_assurance: 'Quality Assurance',
                marketing: 'Marketing',
                technic: 'Technic',
                ppic: 'PPIC',
                production: 'Production'
            };

            $('#userSelect').change(function () {
                const selectedDivision = $(this).find(':selected').data('division');
                const displayName = divisionMap[selectedDivision] || 'No division assigned';
                $('#divisionDisplay').val(displayName);
            });
        });
    </script>
</body>
</html>