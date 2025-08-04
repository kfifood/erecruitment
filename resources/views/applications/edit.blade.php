<div class="container-fluid">
    <div class="row">
        <!-- Kolom Foto (diperkecil) -->
        <div class="col-md-2 text-center">
            @if($application->photo)
                <img src="{{ asset($application->photo) }}" alt="Foto Pelamar" class="img-thumbnail mb-3" style="max-width: 150px;">
            @else
                <div class="text-muted mb-3">No photo</div>
            @endif
        </div>
        
        <!-- Kolom Form (diperlebar) -->
        <div class="col-md-10">
            <form method="POST" action="{{ route('applications.update', $application->id) }}" id="editStatusForm">
                @csrf
                @method('PUT')

                <!-- Baris 1: Nama dan Posisi -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Pelamar</label>
                        <input type="text" class="form-control p-2" value="{{ $application->full_name }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Posisi</label>
                        <input type="text" class="form-control p-2" value="{{ $application->job->position }}" readonly>
                    </div>
                </div>

                <!-- Baris 2: Pendidikan, Jurusan, Prodi -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Pendidikan</label>
                        <input type="text" class="form-control p-2" value="{{ $application->education }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Jurusan</label>
                        <input type="text" class="form-control p-2" value="{{ $application->major ?? '-' }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Prodi</label>
                        <input type="text" class="form-control p-2" value="{{ $application->study_program ?? '-' }}" readonly>
                    </div>
                </div>

                <!-- Baris 3: Status -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label for="status" class="form-label fw-bold">Status</label>
                        <select class="form-select p-2" id="status" name="status" required style="font-size: 1rem;">
                            <option value="submitted" {{ $application->status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="interview" {{ $application->status == 'interview' ? 'selected' : '' }}>Interview</option>
                            <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="row mt-4">
                    <div class="col-md-12 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>