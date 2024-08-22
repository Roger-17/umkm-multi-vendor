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
                    <h3>Keranjang</h3>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Keranjang</li>
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
                                <th scope="col">Harga Produk</th>
                                <th scope="col">Qty Beli</th>
                                {{-- <th scope="col">Biaya</th> --}}
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cart as $item)
                                <tr>
                                    <td>{{ $item->product }}</td>
                                    <td>{{ number_format($item->harga_produk, 0, '.', '.') }}</td>
                                    <td>
                                        <form action="{{ route('ubahQtyCart') }}" method="POST"
                                            id="qty-form-{{ $item->id }}">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                                            <input type="hidden" name="quantity" id="quantity_{{ $item->id }}"
                                                value="{{ $item->qty ? $item->qty : 0 }}">

                                            <a href="#" class="increase" data-id="{{ $item->id }}">
                                                <i class="fas fa-sm fa-plus"></i>
                                            </a>
                                            <span class="mx-2"
                                                id="default_qty_{{ $item->id }}">{{ $item->qty ? $item->qty : 0 }}</span>
                                            <a href="#" class="decrease" data-id="{{ $item->id }}">
                                                <i class="fas fa-sm fa-minus"></i>
                                            </a>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger" id="hapus"
                                            data-id="{{ $item->id }}">
                                            <i class="fas fa-sm fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card shadow mt-4">
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Alamat:</label>
                        <input type="text" name="alamat" class="form-control" id="alamat">
                    </div>
                </div>


                <div class="card-body">
                    <div class="form-group">
                        <label for="">No Telepon:</label>
                        <input type="text" name="no_telepon" class="form-control" id="no_telepon">
                    </div>
                </div>
            </div>

            <div class="left-side-button float-start">
                <a href="{{ route('home') }}" class="btn btn-solid-default btn fw-bold mb-0 mt-4 ms-0">
                    <i class="fas fa-arrow-left"></i>Lanjut Berbelanja</a>
            </div>

            <div class="left-side-button float-end">
                <a href="javascript:void(0)" class="btn btn-solid-default btn fw-bold mb-0 mt-4 ms-0" id="checkout">
                    <i class="fas fa-cart-plus"></i>Checkout</a>
            </div>
        </div>
    </section>
@endsection


@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.increase').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const itemId = this.dataset.id;
                    const quantityInput = document.getElementById(`quantity_${itemId}`);
                    const currentQty = parseInt(quantityInput.value, 10) || 0;
                    quantityInput.value = currentQty + 1;
                    document.getElementById(`default_qty_${itemId}`).textContent = quantityInput
                        .value;

                    const form = document.getElementById(`qty-form-${itemId}`);
                    if (form) {
                        form.submit();
                    }
                });
            });

            document.querySelectorAll('.decrease').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const itemId = this.dataset.id;
                    const quantityInput = document.getElementById(`quantity_${itemId}`);
                    let currentQty = parseInt(quantityInput.value, 10) || 0;
                    if (currentQty > 0) {
                        quantityInput.value = currentQty - 1;
                        document.getElementById(`default_qty_${itemId}`).textContent = quantityInput
                            .value;

                        const form = document.getElementById(`qty-form-${itemId}`);
                        if (form) {
                            form.submit();
                        }
                    }
                });
            });
        });
    </script>
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

        $(document).on('click', '#checkout', function(e) {
            e.preventDefault();

            let alamat = $('#alamat').val();

            let no_telepon = $('#no_telepon').val();

            $.ajax({
                type: "POST",
                url: "{{ route('checkout') }}",
                data: {
                    alamat: alamat,
                    no_telepon: no_telepon,
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
        })


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
                        url: "{{ route('keranjang.hapus') }}",
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
