@extends('layouts.be')


@section('title', 'Permission')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Role</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('role') }}">Role</a></div>
                    <div class="breadcrumb-item">Create Role</div>
                </div>
            </div>

            <div class="section-body">

                <a href="{{ route('role') }}" class="btn btn-sm btn-outline-primary my-3">
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

                            <input type="hidden" name="id" class="form-control" id="role_id"
                                value="{{ $role->id }}">

                            <div class="form-group">
                                <label for="">Role:</label>
                                <input type="text" name="role" class="form-control" value="{{ $role->name }}">
                            </div>


                            <div class="form-group">
                                <label for="">Permission:</label>
                                <select name="permission[]" id="permission" class="form-control"></select>
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


            let role_id = $('#role_id').val();
            $.ajax({
                type: 'GET',
                url: "{{ route('role.permissionByRole', ['id' => ':id']) }}".replace(':id', role_id),
                data: {
                    role_id: role_id,
                    _token: '{{ csrf_token() }}'
                }
            }).then(function(data) {
                for (var i = 0; i < data.length; i++) {

                    // mengakses value array dari permission
                    let permissions = data[i].permissions;

                    // loooping permission hasil relasi dan append ke select2
                    for (let p = 0; p < permissions.length; p++) {
                        const newOption = new Option(permissions[p].name, permissions[p].id, true,
                            true);

                        $('#permission').append(newOption).trigger('change');
                    }
                }
            });

            $('#permission').select2({
                multiple: true,
                placeholder: '--Pilih Permission--',
                allowClear: true,
                ajax: {
                    url: "{{ route('role.permissionList') }}",
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
                    url: '{{ route('role.update') }}',
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
                                window.top.location = "{{ route('role') }}";
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
