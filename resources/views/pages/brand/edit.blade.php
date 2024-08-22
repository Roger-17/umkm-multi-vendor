@extends('layouts.be')


@section('title', 'Brand')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Brand</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('brand') }}">Brand</a></div>
                    <div class="breadcrumb-item">Edit Brand</div>
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

                            <input type="hidden" name="id" class="form-control" value="{{ $brand->id }}">

                            <div class="form-group">
                                <label for="">Brand:</label>
                                <input type="text" name="name" class="form-control" value="{{ $brand->name }}">
                            </div>

                            <div class="form-group">
                                <label for="">Image: <sup>(isi jika dibuah)</sup></label>
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
                    url: '{{ route('brand.update') }}',
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
                                window.top.location = "{{ route('brand') }}";
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
