@extends('layouts.be')


@section('title', 'Jurnal Umum')
@section('content')


    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Jurnal Umum</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Jurnal Umum</div>
                </div>
            </div>

            <div class="section-body">

                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Periode:</label>
                                    <input type="text" id="monthYearPicker" class="form-control"
                                        placeholder="Masukan periode">
                                </div>
                            </div>
                        </div>

                        <a href="" class="btn btn-sm btn-danger" id="reset">
                            <i class="fas fa-sm fa-undo"></i> Reset
                        </a>
                    </div>
                </div>


                <div id="content">

                </div>
            </div>
        </section>
    </div>
@endsection

@push('css')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
@endpush

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.id.min.js">
    </script>
    <script>
        $(document).on('click', '#reset', function(e) {
            e.preventDefault();

            $('#content').empty();
            $('#monthYearPicker').val('');
        })

        $(document).ready(function() {
            $('#monthYearPicker').datepicker({
                format: "MM yyyy", // Format internal datepicker
                startView: "months",
                minViewMode: "months",
                language: 'id', // Set bahasa ke Indonesia
                autoclose: true
            }).on('changeDate', function(e) {
                // Mendapatkan nilai periode dari datepicker
                var periode = $('#monthYearPicker').datepicker('getFormattedDate', 'mm-yyyy');

                // Jalankan AJAX saat tanggal dipilih
                $.ajax({
                    type: "POST",
                    url: "{{ route('jurnal_umum.show') }}",
                    data: {
                        periode: periode,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#content').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.log("Error: " + error);
                    }
                });
            });
        });
    </script>
@endpush
