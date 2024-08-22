@extends('layouts.be')


@section('title', 'Galleri Product')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Galleri Product</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('galeri-product') }}">Galleri Product</a></div>
                    <div class="breadcrumb-item">List Galleri Product</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">List Galery Product</h2>



                <div class="card shadow">
                    <div class="card-header">
                        <h4></h4>
                        <div class="card-header-action">
                            <a href="{{ route('galeri-product.create') }}" class="btn btn-outline-primary">
                                Add Galleri Product
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="width:100%" id="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Product</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('script')
    <script>
        $('#table').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            responsive: true,
            pageLength: 10,
            lengthMenu: [
                [10, 20, 25, -1],
                [10, 20, 25, "50"]
            ],

            order: [],
            ajax: {
                url: "{{ route('galeri-product.data') }}",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    'orderable': false,
                    'searchable': false
                },
                {
                    data: 'product',
                    name: 'product'
                },
                {
                    data: 'image',
                    name: 'image'
                },

                {
                    data: 'aksi',
                    name: 'aksi'
                },
            ]
        });


        $(document).on('click', '.hapus', function() {
            let id = $(this).attr('data-id');
            Swal.fire({
                title: 'Delete Data?',
                text: "Data will deleted!",
                icon: 'warning',
                confirmButton: true,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('category.destroy', ['id' => ':id']) }}".replace(':id',
                            id),
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data.status == 'success') {
                                Swal.fire({
                                    icon: data.status,
                                    text: data.message,
                                    title: 'Success',
                                    toast: true,
                                    position: 'top-end',
                                    timer: 1500,
                                    showConfirmButton: false,
                                });
                                $('#table').DataTable().ajax.reload();
                            }
                        },
                    })
                }
            });
        });
    </script>
@endpush
