@extends('layouts.be')


@section('title', 'Galeri Product')
@section('content')

    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Galeri Product</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('galeri-product') }}">Galeri Product</a></div>
                    <div class="breadcrumb-item">Galeri Product</div>
                </div>
            </div>

            <div class="section-body">

                <div class="card shadow">
                    <div class="card-header">
                        <h4>
                            Form Create <i class="fas fa-sm fa-edit"></i>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="#" method="POST" id="form_simpan" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="">Product:</label>
                                <select name="product" id="product" class="form-control"></select>
                            </div>

                            <div class="form-group">
                                <label for="">Image:</label>
                                <input type="file" name="image" class="form-control">
                            </div>

                            <button class="btn btn-sm btn-outline-primary" type="submit">
                                Simpan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $("#form_simpan").submit(function(e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);
                $.ajax({
                    url: '{{ route('galeri-product.store') }}',
                    method: 'post',
                    data: formData,
                    cache: false,
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function() {
                        $(document).find('span.error-text').text('');
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            Swal.fire({
                                icon: data.status,
                                text: data.message,
                                title: 'Berhasil',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                            });

                            setTimeout(function() {
                                window.top.location = "{{ route('galeri-product') }}";
                            }, 1500);
                        } else {
                            $.each(data.error, function(prefix, val) {
                                $('span.' + prefix + '_error').text(val[0]);
                            });
                        }
                    }
                });
            });


            $('#product').select2({
                multiple: false,
                placeholder: '--Pilih Product--',
                allowClear: true,
                ajax: {
                    url: "{{ route('galeri-product.produkList') }}",
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

        });
    </script>
@endpush
