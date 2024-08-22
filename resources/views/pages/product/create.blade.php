@extends('layouts.be')


@section('title', 'Product')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Product</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('product') }}">Product</a></div>
                    <div class="breadcrumb-item">Create Product</div>
                </div>
            </div>

            <div class="section-body">


                <a href="{{ route('product') }}" class="btn btn-sm btn-outline-primary my-3">
                    <i class="fas fa-sm fa-arrow-left"></i> Kembali
                </a>

                <div class="card shadow">
                    <div class="card-header">
                        <h4>
                            Form Create <i class="fas fa-sm fa-edit"></i>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="#" method="POST" id="form_simpan" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Name:</label>
                                        <input type="text" name="name" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Sku:</label>
                                        <input type="text" name="sku" class="form-control">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Price:</label>
                                        <input type="number" name="price" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Stock:</label>
                                        <input type="number" name="stock" class="form-control">
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="form-group">
                                <label for="">Brand:</label>
                                <select name="brand" id="brand" class="form-control"></select>
                            </div> --}}

                            <div class="form-group">
                                <label for="">Category:</label>
                                <select name="category" id="category" class="form-control"></select>
                            </div>


                            <div class="form-group">
                                <label for="">Deskripsi:</label>
                                <textarea name="" id="" cols="30" rows="10" class="form-control"
                                    style="height: 300px !important;"></textarea>
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
            $('#brand').select2({
                multiple: false,
                placeholder: '--Pilih Brand--',
                allowClear: true,
                ajax: {
                    url: "{{ route('product.brandList') }}",
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

            $('#category').select2({
                multiple: false,
                placeholder: '--Pilih Category--',
                allowClear: true,
                ajax: {
                    url: "{{ route('product.categoryList') }}",
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
        })

        $(document).ready(function() {
            $("#form_simpan").submit(function(e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);
                $.ajax({
                    url: '{{ route('product.store') }}',
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
                                window.top.location = "{{ route('product') }}";
                            }, 1500);
                        } else {
                            $.each(data.error, function(prefix, val) {
                                $('span.' + prefix + '_error').text(val[0]);
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush
