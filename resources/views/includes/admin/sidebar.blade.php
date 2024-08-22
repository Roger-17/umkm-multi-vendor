<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">
                <img src="{{ asset('be/logo-sidebar-be.png') }}" alt="" style="width: 150px;">
            </a>
        </div>
        {{-- <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">St</a>
        </div> --}}
        <ul class="sidebar-menu my-5">
            {{-- <li class="menu-header">Dashboard</li> --}}
            <li><a class="nav-link" href="{{ route('dashboard') }}"><i class="fas fa-fire"></i>
                    <span>Dashboard</span></a></li>

            @if (Auth::user()->hasRole('super admin'))
                <li class="menu-header">Users Management</li>
                <li class="dropdown">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cog"></i>
                        <span>Users Management</span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('permission') }}">Permission</a></li>
                        <li><a class="nav-link" href="{{ route('role') }}">Role</a></li>
                        <li><a class="nav-link" href="{{ route('users') }}">Users</a></li>
                    </ul>
                </li>
            @endif


            <li class="menu-header">Main Menu</li>
            <li><a class="nav-link" href="{{ route('category') }}"><i class="fas fa-tag"></i>
                    <span>Category</span></a></li>

            @if (Auth::user()->hasRole('super admin'))
                <li><a class="nav-link" href="{{ route('brand') }}"><i class="fas fa-tag"></i>
                        <span>Brand</span></a></li>

                <li><a class="nav-link" href="{{ route('coa') }}"><i class="fas fa-tag"></i>
                        <span>Coa</span></a></li>
            @endif


            @if (Auth::user()->hasRole('brand'))
                <li><a class="nav-link" href="{{ route('kupon') }}"><i class="fas fa-briefcase"></i>
                        <span>Kupon</span></a></li>
            @endif



            @if (Auth::user()->hasRole('super admin') || Auth::user()->hasRole('brand'))
                <li><a class="nav-link" href="{{ route('product') }}"><i class="fas fa-briefcase"></i>
                        <span>Product</span></a></li>
            @endif


            @if (Auth::user()->hasRole('brand'))
                <li><a class="nav-link" href="{{ route('galeri-product') }}"><i class="fas fa-briefcase"></i>
                        <span>Galeri Product</span></a></li>

                <li><a class="nav-link" href="{{ route('saldo_awal_brand') }}"><i class="fas fa-money-check"></i>
                        <span>Saldo Awal</span></a></li>
            @endif

            @if (Auth::user()->hasRole('brand'))
                <li class="menu-header">Manage Transaction</li>
                <li><a class="nav-link" href="{{ route('order') }}"><i class="fas fa-cart-arrow-down"></i>
                        <span>Transaction</span></a></li>
            @endif


            @if (Auth::user()->hasRole('brand'))
                <li class="menu-header">Akutansi</li>
                <li><a class="nav-link" href="{{ route('buku_besar') }}"><i class="fas fa-book"></i>
                        <span>Buku Besar</span></a></li>
                <li><a class="nav-link" href="{{ route('jurnal_umum') }}"><i class="fas fa-book"></i>
                        <span>Jurnal Umum</span></a></li>
            @endif




        </ul>

        <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fas fa-home"></i> Home
            </a>
        </div>
    </aside>
</div>
