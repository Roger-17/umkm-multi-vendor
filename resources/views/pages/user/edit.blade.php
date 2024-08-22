@extends('layouts.be')


@section('title', 'User')
@section('content')


    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>User</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('users') }}">User</a></div>
                    <div class="breadcrumb-item">Edit User</div>
                </div>
            </div>

            <div class="section-body">

                <a href="{{ route('users') }}" class="btn btn-sm btn-outline-primary my-3">
                    <i class="fas fa-sm fa-arrow-left"></i> Kembali
                </a>

                <div class="card shadow">
                    <div class="card-header">
                        <h4></h4>
                        <div class="card-header-action">
                            <a href="{{ route('users.create') }}" class="btn btn-outline-primary">
                                Add User
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="#" method="POST" id="form_simpan">
                            @csrf

                            <div class="form-group">
                                <label for="">Name:</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}">
                            </div>

                            <div class="form-group">
                                <label for="">Email:</label>
                                <input type="email" class="form-control" name="email" value="{{ $user->email }}">
                            </div>

                            <div class="form-group">
                                <label for="">Password:</label>
                                <input type="password" class="form-control" name="password">
                            </div>


                            <div class="form-group">
                                <label for="">Role:</label>
                                <select name="role" class="form-control" id="role"></select>
                            </div>


                            <div class="form-group">
                                <label for="">Brand:</label>
                                <select name="brand" id="brand"></select>
                            </div>


                            <button class="btn btn-sm btn-primary" type="submit">
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
            $('#role').select2({
                multiple: false,
                placeholder: '--Pilih Role--',
                allowClear: true,
                ajax: {
                    url: "{{ route('users.roleList') }}",
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


            $('#brand').select2({
                multiple: false,
                placeholder: '--Pilih Brand--',
                allowClear: true,
                ajax: {
                    url: "{{ route('users.listBrand') }}",
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

            $("#form_simpan").submit(function(e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);
                $.ajax({
                    url: '{{ route('users.stroe') }}',
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
                                window.top.location = "{{ route('users') }}";
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
