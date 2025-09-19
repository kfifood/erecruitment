<!-- Tambahkan di bagian atas content (setelah <h3>) -->
<div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <!-- Ubah ke modal-xl untuk ukuran extra large -->
        <div class="modal-content">
            <div class="modal-header bg-white text-black">
                <h5 class="modal-title" id="editStatusModalLabel">Edit Status Lamaran</h5>
                <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBodyContent">
                <!-- Konten akan diisi via AJAX -->
            </div>
        </div>
    </div>
</div>

<div class="card-body">
    <div class="table-responsive-container">
        <table id="applicationsTable" class="table table-bordered table-hover" style="margin-top: 30px;">
            <thead class="thead-light">
                <tr>
                    <th>Foto</th>
                    <th>Nama Lengkap</th>
                    <th>Posisi Dilamar</th>
                    <th>Tipe Lowongan</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Pendidikan</th>
                    <th>Jurusan</th>
                    <th>Application Status</th>
                    <th>Interview Status</th>
                    <th>Tanggal Lamar</th>
                    <th>CV</th>
                    <th>Cover Letter</th>
                    <th>Edit Status</th>
                    <th>Aksi</th>
                    <th>Cetak</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $application)
                <tr>
                    <td>
                        @if($application->photo)
                        @if(strpos($application->photo, '/remote.php/dav/files/') === 0)
                        <!-- File dari Nextcloud -->
                        <img src="{{ App\Http\Controllers\ApplicationController::getFileUrl($application->photo) }}"
                            alt="Foto Pelamar" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                        <!-- File lokal -->
                        <img src="{{ asset($application->photo) }}" alt="Foto Pelamar"
                            style="width: 50px; height: 50px; object-fit: cover;">
                        @endif
                        @else
                        <span class="text-muted">No photo</span>
                        @endif
                    </td>
                    <td>{{ $application->full_name }}</td>
                    <td>{{ $application->job->position }}</td>
                    <td>{{ $application->job->recruitment_type }}</td>
                    <td>{{ $application->email }}</td>
                    <td>{{ $application->phone }}</td>
                    <td>{{ $application->educations->first()->education_level ?? '-' }}</td>
                    <td>{{ $application->educations->first()->major ?? '-' }}</td>
                    <td>
                        <span class="badge 
                                @if($application->status == 'not-reviewed') bg-secondary
                                @elseif($application->status == 'interview') bg-primary
                                @elseif($application->status == 'review-list') bg-warning
                                @else bg-danger @endif">
                            {{ ucfirst($application->status) }}
                        </span>
                    </td>
                    <td>
                        @if($application->interview_status)
                        <span class="badge 
                                    @if($application->interview_status == 'interviewed') bg-success
                                    @else bg-warning text-dark @endif">
                            {{ ucfirst(str_replace('_', ' ', $application->interview_status)) }}
                        </span>
                        @else
                        <span class="badge bg-secondary">N/A</span>
                        @endif
                    </td>
                    <td>{{ $application->created_at->format('d M Y') }}</td>
                    <td>
                        @if($application->cv)
                        @if(strpos($application->cv, '/remote.php/dav/files/') === 0)
                        <!-- File dari Nextcloud -->
                        <a href="{{ App\Http\Controllers\ApplicationController::getFileUrl($application->cv) }}"
                            target="_blank" title="Download CV" class="text-primary">
                            {{ basename($application->cv) }}
                        </a>
                        @else
                        <!-- File lokal -->
                        <a href="{{ asset($application->cv) }}" target="_blank" title="Download CV"
                            class="text-primary">
                            {{ basename($application->cv) }}
                        </a>
                        @endif
                        @else
                        <span class="text-muted">No CV</span>
                        @endif
                    </td>
                    <td>
                        @if($application->cover_letter)
                        @if(strpos($application->cover_letter, '/remote.php/dav/files/') === 0)
                        <!-- File dari Nextcloud -->
                        <a href="{{ App\Http\Controllers\ApplicationController::getFileUrl($application->cover_letter) }}"
                            target="_blank" title="Download Cover Letter" class="text-primary">
                            {{ basename($application->cover_letter) }}
                        </a>
                        @else
                        <!-- File lokal -->
                        <a href="{{ asset($application->cover_letter) }}" target="_blank" title="Download Cover Letter"
                            class="text-primary">
                            {{ basename($application->cover_letter) }}
                        </a>
                        @endif
                        @else
                        <span class="text-muted">No Cover Letter</span>
                        @endif
                    </td>
                    <td>
                        <!-- Ubah link edit menjadi button dengan class edit-status-btn -->
                        <button class="btn btn-sm btn-warning edit-status-btn" data-id="{{ $application->id }}"
                            data-bs-toggle="modal" data-bs-target="#editStatusModal">
                            <i class="fas fa-edit"></i> Edit Status
                        </button>
                    </td>
                    <td>
                        <form action="{{ route('applications.destroy', $application->id) }}" method="POST"
                            class="d-inline" onsubmit="return confirm('Hapus aplikasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('applications.preview', $application->id) }}" class="btn btn-sm btn-info"
                                title="Preview PDF" target="_blank">
                                <i class="fas fa-eye"></i> Preview
                            </a>
                            <a href="{{ route('applications.download', $application->id) }}"
                                class="btn btn-sm btn-primary" title="Download PDF">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>