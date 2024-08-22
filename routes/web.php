<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\BukuBesarController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardCustomerController;
use App\Http\Controllers\GalleryProductController;
use App\Http\Controllers\JurnalUmumController;
use App\Http\Controllers\KuponController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaldoAwalCoaController;
use App\Http\Controllers\SaldoAwalController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home');
});


Route::prefix('/internal')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        // permission
        Route::get('permission', [PermissionController::class, 'index'])
            ->name('permission');
        Route::get('permission/data', [PermissionController::class, 'data'])
            ->name('permission.data');
        Route::get('permission/create', [PermissionController::class, 'create'])
            ->name('permission.create');
        Route::post('permission/store', [PermissionController::class, 'store'])
            ->name('permission.store');
        Route::get('permission/edit/{id}', [PermissionController::class, 'edit'])
            ->name('permission.edit');
        Route::post('permission/update', [PermissionController::class, 'update'])
            ->name('permission.update');
        Route::delete('permission/destroy/{id}', [PermissionController::class, 'destroy'])
            ->name('permission.destroy');

        // role
        Route::get('role', [RoleController::class, 'index'])
            ->name('role');
        Route::get('role/data', [RoleController::class, 'data'])
            ->name('role.data');
        Route::get('role/create', [RoleController::class, 'create'])
            ->name('role.create');
        Route::post('role/store', [RoleController::class, 'store'])
            ->name('role.store');
        Route::get('role/edit/{id}', [RoleController::class, 'edit'])
            ->name('role.edit');
        Route::post('role/update', [RoleController::class, 'update'])
            ->name('role.update');
        Route::delete('role/destroy/{id}', [RoleController::class, 'destroy'])
            ->name('role.destroy');
        Route::get('role/permissionList', [RoleController::class, 'permissionList'])
            ->name('role.permissionList');
        Route::get('role/permissionByRole/{id}', [RoleController::class, 'permissionByRole'])
            ->name('role.permissionByRole');

        // users
        Route::get('users', [UserController::class, 'index'])
            ->name('users');
        Route::get('users/create', [UserController::class, 'users'])
            ->name('users.create');
        Route::get('users/data', [UserController::class, 'data'])
            ->name('users.data');
        Route::post('users/store', [UserController::class, 'data'])
            ->name('users.store');



        // category
        Route::get('category', [CategoryController::class, 'index'])
            ->name('category');
        Route::get('category/data', [CategoryController::class, 'data'])
            ->name('category.data');
        Route::get('category/create', [CategoryController::class, 'create'])
            ->name('category.create');
        Route::post('category/store', [CategoryController::class, 'store'])
            ->name('category.store');
        Route::get('category/edit/{id}', [CategoryController::class, 'edit'])
            ->name('category.edit');
        Route::post('category/update', [CategoryController::class, 'update'])
            ->name('category.update');
        Route::delete('category/destroy/{id}', [CategoryController::class, 'destroy'])
            ->name('category.destroy');

        // brand
        Route::get('brand', [BrandController::class, 'index'])
            ->name('brand');
        Route::get('brand/data', [BrandController::class, 'data'])
            ->name('brand.data');
        Route::get('brand/create', [BrandController::class, 'create'])
            ->name('brand.create');
        Route::post('brand/store', [BrandController::class, 'store'])
            ->name('brand.store');
        Route::get('brand/edit/{id}', [BrandController::class, 'edit'])
            ->name('brand.edit');
        Route::post('brand/update', [BrandController::class, 'update'])
            ->name('brand.update');
        Route::delete('brand/destroy/{id}', [BrandController::class, 'destroy'])
            ->name('brand.destroy');

        Route::get('saldo-awal-brand', [SaldoAwalController::class, 'index'])
            ->name('saldo_awal_brand');
        Route::get('saldo-awal-brand/data', [SaldoAwalController::class, 'data'])
            ->name('saldo_awal_brand.data');
        Route::get('saldo-awal-brand/tambah', [SaldoAwalController::class, 'tambah'])
            ->name('saldo_awal_brand.tambah');
        Route::post('saldo-awal-brand/simpan', [SaldoAwalController::class, 'simpan'])
            ->name('saldo_awal_brand.simpan');

        Route::get('kupon', [KuponController::class, 'index'])
            ->name('kupon');
        Route::get('kupon/data', [KuponController::class, 'data'])
            ->name('kupon.data');
        Route::get('kupon/tambah', [KuponController::class, 'tambah'])
            ->name('kupon.tambah');
        Route::post('kupon/simpan', [KuponController::class, 'simpan'])
            ->name('kupon.simpan');
        Route::get('kupon/edit/{id}', [KuponController::class, 'edit'])
            ->name('kupon.edit');
        Route::post('kupon/update', [KuponController::class, 'update'])
            ->name('kupon.update');
        Route::post('kupon.hapus', [KuponController::class, 'hapus'])
            ->name('kupon.hapus');



        // product
        Route::get('product', [ProductController::class, 'index'])
            ->name('product');
        Route::get('product/data', [ProductController::class, 'data'])
            ->name('product.data');
        Route::get('product/create', [ProductController::class, 'create'])
            ->name('product.create');
        Route::post('product/store', [ProductController::class, 'store'])
            ->name('product.store');
        Route::get('product/edit/{id}', [ProductController::class, 'edit'])
            ->name('product.edit');
        Route::post('product/update', [ProductController::class, 'update'])
            ->name('product.update');
        Route::delete('product/destroy', [ProductController::class, 'destroy'])
            ->name('product.destroy');
        Route::get('product/categoryList', [ProductController::class, 'categoryList'])
            ->name('product.categoryList');
        Route::get('product/brandList', [ProductController::class, 'brandList'])
            ->name('product.brandList');
        Route::get('product/brandByProduct', [ProductController::class, 'brandByProduct'])
            ->name('product.brandByProduct');
        Route::get('product/categoryByProduct', [ProductController::class, 'categoryByProduct'])
            ->name('product.categoryByProduct');


        // users
        Route::get('users', [UserController::class, 'index'])
            ->name('users');
        Route::get('users/data', [UserController::class, 'data'])
            ->name('users.data');
        Route::get('users/roleList', [UserController::class, 'roleList'])
            ->name('users.roleList');
        Route::get('users/listBrand', [UserController::class, 'listBrand'])
            ->name('users.listBrand');
        Route::get('users/create', [UserController::class, 'create'])
            ->name('users.create');
        Route::post('users/store', [UserController::class, 'store'])
            ->name('users.stroe');
        Route::get('users/edit/{id}', [UserController::class, 'edit'])
            ->name('users.edit');
        Route::post('users.update', [UserController::class, 'update'])
            ->name('users.update');
        Route::delete('users/destroy/{id}', [UserController::class, 'destroy'])
            ->name('users.destroy');


        // coa
        Route::get('coa', [CoaController::class, 'index'])
            ->name('coa');
        Route::get('coa/data', [CoaController::class, 'data'])
            ->name('coa.data');
        Route::get('coa/create', [CoaController::class, 'create'])
            ->name('coa.create');
        Route::post('coa/simpan', [CoaController::class, 'simpan'])
            ->name('coa.simpan');
        Route::get('coa/edit/{id}', [CoaController::class, 'edit'])
            ->name('coa.edit');
        Route::post('coa/update', [CoaController::class, 'update'])
            ->name('coa.update');
        Route::delete('coa/destroy/{id}', [CoaController::class, 'hapus'])
            ->name('coa.destroy');



        // galeri product
        Route::get('galeri-product', [GalleryProductController::class, 'index'])
            ->name('galeri-product');
        Route::get('galeri-product/produkList', [GalleryProductController::class, 'produkList'])
            ->name('galeri-product.produkList');
        Route::get('galeri-product/data', [GalleryProductController::class, 'data'])
            ->name('galeri-product.data');
        Route::get('galeri-product/create', [GalleryProductController::class, 'create'])
            ->name('galeri-product.create');
        Route::post('galeri-product/store', [GalleryProductController::class, 'store'])
            ->name('galeri-product.store');
        Route::get('galeri-product/edit/{id}', [GalleryProductController::class, 'edit'])
            ->name('galeri-product.edit');
        Route::post('galeri-product/update', [GalleryProductController::class, 'update'])
            ->name('galeri-product.update');
        Route::delete('galeri-product/destroy', [GalleryProductController::class, 'destroy'])
            ->name('galeri-product.destroy');
        Route::get('galeri-product/productByGallery', [GalleryProductController::class, 'productByGallery'])
            ->name('galeri-product.productByGallery');


        Route::get('order', [OrderController::class, 'index'])
            ->name('order');
        Route::get('order.data', [OrderController::class, 'data'])
            ->name('order.data');
        Route::post('order/konfirmasi', [OrderController::class, 'konfirmasi'])
            ->name('order.konfirmasi');

        Route::get('buku_besar', [BukuBesarController::class, 'index'])
            ->name('buku_besar');
        Route::get('buku-besar/list-coa', [BukuBesarController::class, 'listCoa'])
            ->name('buku-besar.list_coa');
        Route::post('buku-besar', [BukuBesarController::class, 'show'])
            ->name('buku-besar.show');

        Route::get('jurnal_umum', [JurnalUmumController::class, 'index'])
            ->name('jurnal_umum');

        Route::post('jurnal_umum', [JurnalUmumController::class, 'show'])
            ->name('jurnal_umum.show');
    });

Auth::routes();


Route::post('uploadBuktiPembayaran', [OrderController::class, 'uploadBuktiPembayaran'])
    ->name('uploadBuktiPembayaran');
Route::post('detailOrder', [OrderController::class, 'detailOrder'])
    ->name('detailOrder');
Route::get('dashboard-customer', [DashboardCustomerController::class, 'index'])
    ->name('dashboard_customer');
Route::get('wishlist', [WishlistController::class, 'index'])
    ->name('wishlist');
Route::get('shop', [ShopController::class, 'index'])
    ->name('shop');
Route::get('shop-by-brand/{id}', [ShopController::class, 'shopByBrand'])
    ->name('shopByBrand');
Route::get('cart', [CartController::class, 'index'])
    ->name('cart');
Route::post('/ubah-qty-cart', [CartController::class, 'ubahQtyCart'])->name('ubahQtyCart');
Route::post('keranjang/hapus', [CartController::class, 'hapus'])
    ->name('keranjang.hapus');
Route::post('wishlist/simpan', [WishlistController::class, 'simpan'])
    ->name('wishlist.simpan');
Route::post('wishlistToKeranjang', [WishlistController::class, 'wishlistToKeranjang'])
    ->name('wishlistToKeranjang');
Route::post('wishlist/simpan', [WishlistController::class, 'simpan'])
    ->name('wishlist.simpan');
Route::post('wishlist/updateQtyWishlist', [WishlistController::class, 'updateQtyWishlist'])
    ->name('wishlist.updateQtyWishlist');
Route::post('wishlist/hapus', [WishlistController::class, 'hapus'])
    ->name('wishlist.hapus');
Route::post('checkout', [CartController::class, 'checkout'])
    ->name('checkout');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
