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
            <div class="row">
                <div class="col-lg-3 category-side col-md-4">
                    <div class="category-option">
                        <div class="button-close mb-3">
                            <button class="btn p-0"><i data-feather="arrow-left"></i> Close</button>
                        </div>
                        <div class="accordion category-name" id="accordionExample">
                            <div class="accordion-item category-rating">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo">
                                        Brand
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse show"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body category-scroll">
                                        <ul class="category-list">
                                            @foreach ($brand as $brand)
                                                <li>
                                                    <a href="{{ route('shopByBrand', $brand->id) }}" class="text-dark">
                                                        {{ $brand->name }}
                                                    </a>
                                                </li>
                                            @endforeach

                                        </ul>
                                    </div>
                                </div>
                            </div>



                            <div class="accordion-item category-rating">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseSix">
                                        Kategori
                                    </button>
                                </h2>
                                <div id="collapseSix" class="accordion-collapse collapse show" aria-labelledby="headingOne">
                                    <div class="accordion-body category-scroll">
                                        <ul class="category-list">
                                            @foreach ($categories as $category)
                                                <li>
                                                    <div class="form-check ps-0 custome-form-check">
                                                        {{-- <input class="checkbox_animated check-it" id="ct{{ $category->id }}" name="categories" type="checkbox" @if (in_array($category->id, explode(',', $q_categories))) checked="checked" @endif value="{{ $category->id }}" onchange="filterProductsByCategory(this)"> --}}
                                                        <label class="form-check-label">{{ $category->name }}</label>
                                                        {{-- <p class="font-light">({{ $category->products->count() }})</p> --}}
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="category-product col-lg-9 col-12 ratio_30">

                    <div class="row g-4">
                        <!-- label and featured section -->
                        <div class="col-md-12">
                            <ul class="short-name">


                            </ul>
                        </div>

                        {{-- <div class="col-12">
                            <div class="filter-options">
                                <div class="select-options">
                                    <div class="page-view-filter">
                                        <div class="dropdown select-featured">
                                            <select class="form-select" name="orderby" id="orderby">
                                                <option value="-1" {{ $order==-1? 'selected':'' }}>Default</option>
                                                <option value="1"{{ $order==1? 'selected':'' }}>Pembaruan, Terbaru</option>
                                                <option value="2"{{ $order==2? 'selected':'' }}>Pembaruan, Terlama</option>
                                                <option value="3"{{ $order==3? 'selected':'' }}>Harga, Terendah</option>
                                                <option value="4"{{ $order==4? 'selected':'' }}>Harga, Tertinggi</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="dropdown select-featured">
                                        <select class="form-select" name="size" id="pagesize">
                                            <option value="12" {{ $size == 12 ? 'selected':'' }}>12 Produk Per Halaman</option>
                                            <option value="24"{{ $size == 24 ? 'selected':'' }}>24 Produk Per Halaman</option>
                                            <option value="52"{{ $size == 52 ? 'selected':'' }}>52 Produk Per Halaman</option>
                                            <option value="100"{{ $size == 100 ? 'selected':'' }}>100 Produk Per Halaman</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid-options d-sm-inline-block d-none">
                                    <ul class="d-flex">
                                        <li class="two-grid">
                                            <a href="javascript:void(0)">
                                                <img src="assets/svg/grid-2.svg" class="img-fluid blur-up lazyload"
                                                    alt="">
                                            </a>
                                        </li>
                                        <li class="three-grid d-md-inline-block d-none">
                                            <a href="javascript:void(0)">
                                                <img src="assets/svg/grid-3.svg" class="img-fluid blur-up lazyload"
                                                    alt="">
                                            </a>
                                        </li>
                                        <li class="grid-btn active d-lg-inline-block d-none">
                                            <a href="javascript:void(0)">
                                                <img src="assets/svg/grid.svg" class="img-fluid blur-up lazyload"
                                                    alt="">
                                            </a>
                                        </li>
                                        <li class="list-btn">
                                            <a href="javascript:void(0)">
                                                <img src="assets/svg/list.svg" class="img-fluid blur-up lazyload"
                                                    alt="">
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <!-- label and featured section -->

                    <!-- Prodcut setion -->
                    <div
                        class="row g-sm-4 g-3 row-cols-lg-4 row-cols-md-3 row-cols-2 mt-1 custom-gy-5 product-style-2 ratio_asos product-list-section">
                        @foreach ($produk as $p)
                            <div class="col-xl-2 col-lg-2 col-6">
                                <div class="product-box">
                                    <div class="img-wrapper">
                                        <a href="product/details.html">

                                            @php
                                                $image = $p->galeri->first();
                                            @endphp

                                            <img src="{{ Storage::url($image->image) }}"
                                                class="w-100 bg-img blur-up lazyload" alt="">
                                            <div class="circle-shape"></div>
                                            <span class="background-text">Furniture</span>

                                            <div class="cart-wrap">
                                                <ul>
                                                    <li>
                                                        <a href="javascript:void(0)" class="addtocart-btn"
                                                            data-id="{{ $p->id }}">
                                                            <i data-feather="shopping-cart"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)" class="wishlist"
                                                            data-id={{ $p->id }}>
                                                            <i data-feather="heart"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                    </div>
                                    <div class="product-style-3 product-style-chair">
                                        <div class="product-title d-block mb-0">
                                            <div class="r-price">
                                                <div class="theme-color"> Rp {{ number_format($p->price, 0, '.', '.') }}
                                                </div>

                                            </div>
                                            <p class="font-light mb-sm-2 mb-0" style="font-size: 12px !important;">
                                                {{ $p->brand->name }}</p>
                                            <a href="product/details.html" class="font-default">
                                                <h6>{{ $p->name }}</h6>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>


                </div>
            </div>
        </div>
    </section>
    <!-- Shop Section end -->
    <!-- Subscribe Section Start -->
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
        })
    </script>
@endpush
