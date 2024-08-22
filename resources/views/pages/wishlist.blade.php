@extends('layouts.base')

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
                    <h3>Wishlist</h3>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- Cart Section Start -->
    <section class="wish-list-section section-b-space">
        <div class="container">
            <div class="row mb-3">
                <div class="col-sm-12 table-responsive">
                    <table class="table table-bordered cart-table wishlist-table">
                        <thead>
                            <tr class="table-head">
                                <th scope="col">Produk</th>
                                <th scope="col">Harga Satuan</th>
                                <th scope="col">Stok</th>
                                {{-- <th scope="col">Qty Beli</th> --}}
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($wishlist as $item)
                                <tr>
                                    <td>{{ $item->product }}</td>
                                    <td>{{ number_format($item->harga_satuan, 0, '.', '.') }}</td>
                                    <td>{{ $item->stok }}</td>
                                    {{-- <td>


                                        <a href="#" id="increase" data-id="{{ $item->id }}">
                                            <i class="fas  fa-sm fa-plus"></i>
                                        </a>
                                        <span class="mx-2" id="default_qty_{{ $item->id }}">0</span>
                                        <a href="#" id="decrease" data-id="{{ $item->id }}">
                                            <i class="fas  fa-sm fa-minus"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#" data-id="{{ $item->id }}" class="btn btn-sm btn-danger"
                                            id="hapus">
                                            <i class="fas fa-sm fa-trash"></i> Hapus
                                        </a>
                                    </td> --}}
                                    <td>
                                        <a href="javascript:void(0)" class="btn btns-sm btn-outline-danger" id="hapus"
                                            data-id="{{ $item->id }}">
                                            <i class="fas fa-sm fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Data Kosong</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="left-side-button float-start">
                <a href="{{ route('home') }}" class="btn btn-solid-default btn fw-bold mb-0 mt-4 ms-0">
                    <i class="fas fa-arrow-left"></i>Lanjut Berbelanja</a>
            </div>

            <div class="left-side-button float-end">
                <a href="javascript:void(0)" class="btn btn-solid-default btn fw-bold mb-0 mt-4 ms-0"
                    id="masukan_keranjang">
                    <i class="fas fa-cart-plus"></i>Masukan Keranjang</a>
            </div>
        </div>
    </section>
@endsection


@push('script')


    <script>
        $(document).on('click', '#masukan_keranjang', function() {
            $.ajax({
                type: "POST",
                url: "{{ route('wishlistToKeranjang') }}",
                data: {
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
                },
            })
        });



        $(document).on('click', '#hapus', function() {
            let id = $(this).attr('data-id');
            Swal.fire({
                title: 'Hapus data?',
                text: "Anda Akan Hapus Data",
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
                        url: "{{ route('wishlist.hapus') }}",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data.status == 'success') {
                                Swal.fire({
                                    icon: data.status,
                                    text: data.message,
                                    title: 'Berhasil',

                                    timer: 1500,
                                    showConfirmButton: false,
                                });
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1500);
                            }
                        },
                    })
                }
            });
        });
    </script>
@endpush
