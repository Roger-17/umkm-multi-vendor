@extends('layouts.be')


@section('title', 'Product')
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
                    <div class="breadcrumb-item"><a href="{{ route('galeri-product') }}">Product</a></div>
                    <div class="breadcrumb-item">Product</div>
                </div>
            </div>

            <div class="section-body">

                <div class="card shadow">
                    <div class="card-header">
                        <h4>
                            Form Edit <i class="fas fa-sm fa-edit"></i>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="#" method="POST" id="form_simpan" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="id" class="form-control" value="{{ $product->id }}"
                                id="product_id">



                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Name:</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ $product->name }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Sku:</label>
                                        <input type="text" name="sku" class="form-control"
                                            value="{{ $product->sku }}">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Price:</label>
                                        <input type="number" name="price" class="form-control"
                                            value="{{ $product->price }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Stock:</label>
                                        <input type="number" name="stock" class="form-control"
                                            value="{{ $product->stock }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="">Brand:</label>
                                <select name="brand" id="brand" class="form-control"></select>
                            </div>

                            <div class="form-group">
                                <label for="">Category:</label>
                                <select name="category" id="category" class="form-control"></select>
                            </div>


                            <div class="form-group">
                                <label for="">Deskripsi:</label>
                                <textarea name="" id="" cols="30" rows="10" class="form-control"
                                    style="height: 300px !important;">{{ $product->description }}</textarea>
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
                    url: '{{ route('product.update') }}',
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


            var produk_id = $('#product_id').val();
            $.ajax({
                type: 'GET',
                url: "{{ route('product.brandByProduct') }}",
                data: {
                    product_id: produk_id,
                    _token: '{{ csrf_token() }}'
                }
            }).then(function(data) {
                for (i = 0; i < data.length; i++) {

                    var newOption = new Option(data[i].name, data[i].id, true,
                        true);

                    $('#brand').append(newOption).trigger('change');
                }
            });


            $.ajax({
                type: 'GET',
                url: "{{ route('product.categoryByProduct') }}",
                data: {
                    product_id: produk_id,
                    _token: '{{ csrf_token() }}'
                }
            }).then(function(data) {
                for (i = 0; i < data.length; i++) {

                    var newOption = new Option(data[i].name, data[i].id, true,
                        true);

                    $('#category').append(newOption).trigger('change');
                }
            });

        })
    </script>
@endpush
