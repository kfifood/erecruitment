@extends('layouts.app')

@section('title', 'Review List Production')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Review List - Production</h3>
    @include('applications.partials.table')
</div>
@endsection

@push('styles')
<style>
/* Perbaikan tampilan modal */
#editStatusModal .modal-dialog {
    max-width: 90%;
    margin: 1.75rem auto;
}

#editStatusModal .form-label {
    margin-bottom: 0.3rem;
}

#editStatusModal .form-control,
#editStatusModal .form-select {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}

/* Responsive untuk mobile */
@media (max-width: 768px) {
    #editStatusModal .modal-dialog {
        max-width: 95%;
    }

    #editStatusModal .col-md-2,
    #editStatusModal .col-md-10 {
        width: 100%;
    }
}
</style>
@endpush
@push('scripts')
<script>
$(document).ready(function() {
    // Ketika tombol edit diklik
    $('.edit-status-btn').click(function() {
        var applicationId = $(this).data('id');
        var url = "{{ route('applications.edit', ':id') }}".replace(':id', applicationId);

        // Tambahkan header AJAX untuk request modal
        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(data) {
                $('#modalBodyContent').html(data);
            },
            error: function(xhr) {
                $('#modalBodyContent').html(`
                    <div class="alert alert-danger">
                        <p>Gagal memuat form. Error ${xhr.status}: ${xhr.statusText}</p>
                        <a href="${url}" class="btn btn-warning">Coba buka di halaman baru</a>
                    </div>
                `);
                console.error('Error:', xhr.responseText);
            }
        });
    });


    // Handle submit form dalam modal
    $(document).on('submit', '#editStatusForm', function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#editStatusModal').modal('hide');
                window.location.reload(); // Reload untuk melihat perubahan
            },
            error: function(xhr) {
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        });
    });
});
</script>
@endpush