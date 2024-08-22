@extends('layouts.be')


@section('title', 'Buku Besar')
@section('content')


    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Buku Besar</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Buku Besar</div>
                </div>
            </div>

            <div class="section-body">

                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Periode:</label>
                                    <input type="text" id="monthYearPicker" class="form-control"
                                        placeholder="Masukan periode">
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Akun:</label>
                                    <select name="coa" id="coa" class="form-control"></select>
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

        $(document).on('click', '#reset', function(e){
            e.preventDefault();

            $('#content').empty();
            $('#monthYearPicker').val('');
            $('#coa').val('').trigger('change');
        })

        $(document).ready(function() {
            $('#monthYearPicker').datepicker({
                format: "MM yyyy", // Format internal datepicker
                startView: "months",
                minViewMode: "months",
                language: 'id', // Set bahasa ke Indonesia
                autoclose: true
            })

            $('#coa').select2({
                multiple: false,
                placeholder: '--Pilih Akun--',
                allowClear: true,
                ajax: {
                    url: "{{ route('buku-besar.list_coa') }}",
                    dataType: 'json',
                    delay: 500,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.text,
                                    id: item.id
                                }
                            })
                        };
                    }
                }
            });

            $('#coa').on('change', function() {
                let coa_value = $(this).val();

                let periode = $('#monthYearPicker').val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('buku-besar.show') }}",
                    data: {
                        coa: coa_value,
                        periode: periode,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#content').html(data);

                    },
                })
            })
        });
    </script>
@endpush
