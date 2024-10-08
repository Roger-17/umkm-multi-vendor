<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title')</title>

    @include('includes.admin.style')

    {{-- Grafik --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Start GA -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-94034622-3');
    </script>
    <!-- /END GA -->
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">


            @include('includes.admin.navbar')


            @include('includes.admin.sidebar')

           @yield('content')

            @include('includes.admin.footer')
        </div>
    </div>


    @include('includes.admin.script')
</body>

</html>
