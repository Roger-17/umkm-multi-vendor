@extends('layouts.be')

@section('title', 'Order')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Order</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('category') }}">Order</a></div>
                    <div class="breadcrumb-item">List Order</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">List Order</h2>

                <div class="card shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="width:100%" id="table">
                                <thead>
                                    <tr>
                                        <<th>No</th>
                                            <th>Tanggal</th>
                                            <th>Pembeli</th>
                                            <th>Status</th>
                                            <th>Tagihan</th>
                                            <th>Bukti Pembayaran</th>
                                            <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- DataTable akan otomatis mengisi bagian ini --}}
                                </tbody>
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
                url: "{{ route('order.data') }}",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    'orderable': false,
                    'searchable': false
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'pembeli',
                    name: 'pembeli'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'total_price',
                    name: 'total_price'
                },
                {
                    data: 'bukti_pembayaran', // Tambahkan kolom bukti_pembayaran
                    name: 'bukti_pembayaran',
                    render: function(data, type, row) {
                        return data; // Data sudah berisi HTML tag img
                    }
                },
                {
                    data: 'aksi',
                    name: 'aksi'
                },
            ]
        });


        $(document).on('click', '#konfirmasi', function() {
            let id = $(this).attr('data-id');
            Swal.fire({
                title: 'Konfirmasi Order?',
                icon: 'warning',
                confirmButton: true,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('order.konfirmasi') }}",
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
