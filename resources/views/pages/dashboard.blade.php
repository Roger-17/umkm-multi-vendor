@extends('layouts.be')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
            </div>
            <div class="row">
                <!-- Statistik lainnya -->
            </div>

            <!-- Tambahkan div untuk grafik penjualan -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Grafik Penjualan</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="salesChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('salesChart').getContext('2d');
            var salesData = @json($salesData); // Mengirim data dari controller ke JavaScript

            console.log(salesData); // Tambahkan ini untuk memeriksa data

            var monthNames = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            var months = Array.from(new Set([
                ...Object.keys(salesData.pending),
                ...Object.keys(salesData.confirmed),
            ])); // Mengambil semua bulan yang ada

            // Mengonversi nomor bulan menjadi nama bulan
            var monthLabels = months.map(month => monthNames[parseInt(month) - 1] || month);

            var pendingData = months.map(month => salesData.pending[month] || 0);
            var confirmedData = months.map(month => salesData.confirmed[month] || 0);

            new Chart(ctx, {
                type: 'line', // Jenis grafik (line, bar, pie, dll.)
                data: {
                    labels: monthLabels, // Label sumbu X (bulan dalam teks)
                    datasets: [{
                        label: 'Pending',
                        data: pendingData, // Data penjualan Pending
                        backgroundColor: 'rgba(255, 99, 132, 0.2)', // Warna latar belakang
                        borderColor: 'rgba(255, 99, 132, 1)', // Warna garis
                        borderWidth: 1
                    }, {
                        label: 'Sudah Dikonfirmasi',
                        data: confirmedData, // Data penjualan Sudah Dikonfirmasi
                        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Warna latar belakang
                        borderColor: 'rgba(75, 192, 192, 1)', // Warna garis
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endpush
