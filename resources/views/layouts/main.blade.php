<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>SIBUDI</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="/assets/img/Brebes.jpg" rel="icon">
    <link href="/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="/assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="/assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="/assets/css/style.css" rel="stylesheet">

    {{-- icone bootstrap --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    {{-- Jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Nov 17 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
    ========================================================
    Projek by : Eri Hidayat
    =========================================================
   -->
</head>

<body>

    @include('sweetalert::alert')
    @include('layouts.header')

    @include('layouts.sidebar')
    <main id="main" class="main">

        <section class="section dashboard">
            @include('layouts.alert')
            @yield('container')
        </section>

    </main><!-- End #main -->
    @include('layouts.footer')





    <script>
        function onlyNumberAmount(input) {
            let v = input.value.replace(/[^0-9\-\+]/g, ''); // Hapus semua karakter selain angka
            if (v.length > 14) v = v.slice(0, 14); // Batasi hingga 14 digit
            input.value = v.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Tambahkan titik sebagai pemisah ribuan
        }

        // Hapus format titik sebelum form disubmit
        $('form').on('submit', function() {
            // Cari semua input di dalam form ini yang memiliki type="text"
            $(this).find('input[type="text"]').each(function() {
                var inputVal = $(this).val();
                // Hapus titik pemisah ribuan
                $(this).val(inputVal.replace(/\./g, ''));
            });
        });
    </script>

</body>

</html>
