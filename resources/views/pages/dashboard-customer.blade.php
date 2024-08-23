@extends('layouts.base')
@push('styles')
    <link id="color-link" rel="stylesheet" type="text/css" href="assets/css/demo2.css">
    <style>
        nav svg {
            height: 20px;
        }

        .product-box .product-details h5 {
            width: 100%;
        }
    </style>
@endpush

@section('content')
    <section class="breadcrumb-section section-b-space" style="padding-top:20px;padding-bottom:20px;">
        <ul class="circles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3>Shop</h3>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Shop</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Section start -->
    <section class="section-b-space">
        <div class="container">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="width: 20%; margin-left: auto; margin-right: auto;">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">QR Yumeuli</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align: center;">
                                        <img src="{{ asset('assets/images/payment-icon/Yumeuli.png') }}"
                                            alt="Deskripsi Gambar" width="300">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered" style="width: 100%">
                            <thead>
                                <tr>
                                    {{-- <th>No</th> --}}
                                    <th>Tanggal</th>
                                    {{-- <th>Produk</th> --}}
                                    <th>Biaya Tagihan</th>
                                    <th>Status</th>
                                    <th>Bukti Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order as $item)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                        {{-- <td>{{ $item->product }}</td> --}}
                                        <td>{{ number_format($item->total_price, 0, '.', '.') }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>
                                            @if ($item->status == 'pending')
                                                <a href="javascript:void(0)" class="btn btn-sm btn-primary"
                                                    data-id="{{ $item->id }}" id="uploadGambar">
                                                    <i class="fas fa-sm fa-image"></i> Upload Bukti Pembayaran
                                                </a>
                                            @else
                                                Sudah upload bukti
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->status != 'pending' || $item->status != 'Pending' || $item->status != 'PENDING')
                                                <a href="javascript:void(0)" class="btn btn-sm btn-primary"
                                                    data-id="{{ $item->id }}" id="detail_order">
                                                    <i class="fas fa-sm fa-eye"></i> Detail Order
                                                </a>
                                            @else
                                                <a href="#" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-sm fa-times"></i> Cancel
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="my-3" id="content">

            </div>
        </div>
    </section>
    <!-- Shop Section end -->
    <!-- Subscribe Section Start -->

    <div class="modal fade" id="modalUploadBukit" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title px-2 my-3" id="staticBackdropLabel">Form Bukti Pembayaran</h5>
                </div>
                <div class="modal-body">
                    <form id="form_simpan" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" class="form-control" id="id_order">
                        <div class="form-group">
                            <label for="">Bukti Pembayaran:</label>
                            <input type="file" class="form-control" name="foto">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).on('click', '.wishlist', function() {
            // console.log();
            let produk_id = $(this).attr('data-id');

            $.ajax({
                url: "wishlist/simpan",
                type: "POST",
                data: {
                    produk_id: produk_id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    if (data.status == 'success') {
                        Swal.fire({
                            icon: data.status,
                            text: data.message,
                            title: data.title,
                            timer: 1500,
                            showConfirmButton: false,
                        });
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    }
                }
            })
        })
        $(document).on('click', '.addtocart-btn', function() {
            let produk_id = $(this).attr('data-id');

            console.log(produk_id);
        });

        $(document).on('click', '#uploadGambar', function(e) {
            e.preventDefault();

            $('#modalUploadBukit').modal('show');

            let id = $(this).attr('data-id');

            $('#id_order').val(id);
        });

        $(document).on('click', '#detail_order', function(e) {
            e.preventDefault();

            let id = $(this).attr('data-id');

            $.ajax({
                url: "{{ route('detailOrder') }}",
                type: "POST",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {

                    $('#content').html(data);

                }
            })

        });

        $(document).on('click', '#tutupDetail', function() {
            $('#content').empty();
        });


        $(document).ready(function() {
            $("#form_simpan").submit(function(e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);
                $.ajax({
                    url: '{{ route('uploadBuktiPembayaran') }}',
                    method: 'POST',
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
                                window.location.reload();
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
