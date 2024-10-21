<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIBUDI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>


    <div class="container">
        @if (auth()->user()->status == 1)
            <a href="/" class="ms-3 fw-bold text-dark">Home</a>
            <div class="text-end">
                <p class="fw-bold">Waktu Tersisa untuk Langganan Anda:</p>
                <div id="countdown" class="countdown"></div>
            </div>
        @endif
        @if (auth()->user()->status == 0)
            <form action="/logout" method="POST" class="text-end mt-3">
                @csrf
                <button type="submit" class="btn btn-primary">Keluar <i class="bi bi-box-arrow-in-right"></i></button>
            </form>
        @endif
        <div class="row cols-1 cols-lg-2">
            <div class="col d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <h2 class="fw-bold">Selamat Datang</h2>
                    <h2 class="fw-bold"> di Aplikasi SIBUDI</h2>
                    <img src="/assets/img/akuntansi.png" alt="">
                </div>
            </div>
            <div class="col">
                <div class="card" style="margin: 130px 0px">

                    <div class="card-body">
                        <div class="card-title">
                            <h3>Langganan</h3>
                        </div>
                        <div class="col-lg-8">
                            @if (count($langganans) > 0)
                                <label class="card p-2 mb-3" style="cursor: pointer"
                                    onclick="return alert('Pilihan tidak tersedia !!!')">
                                    <div class="form-check" for="1bulan">
                                        <input class="form-check-input" type="radio" name="langganan" id="1bulan"
                                            value=" {{ $langganans->first()->jumlah_bulan }}"
                                            @disabled(true)>
                                        <span class=" text-decoration-line-through">
                                            {{ formatRupiah($langganans->first()->harga) }}/{{ $langganans->first()->waktu }}
                                        </span>
                                    </div>
                                </label>
                            @else
                                <label class="card p-2 mb-3" style="cursor: pointer"
                                    onclick="return alert('Pilihan tidak tersedia !!!')">
                                    <div class="form-check" for="1bulan">
                                        <input class="form-check-input" type="radio" name="langganan" id="1bulan"
                                            value="1" @disabled(true)>
                                        <span class="text-decoration-line-through">
                                            Rp12.900/1 bulan
                                        </span>
                                    </div>
                                </label>
                            @endif
                            @foreach ($langganans->skip(1) as $langganan)
                                <label class="card p-2 mb-3" style="cursor: pointer">
                                    <div class="form-check" for="{{ $langganan->id }}bulan">
                                        <input class="form-check-input" type="radio" name="langganan"
                                            id="{{ $langganan->id }}bulan" value=" {{ $langganan->jumlah_bulan }}"
                                            {{ $langganan->jumlah_bulan == 6 ? 'checked' : '' }}>
                                        <span>
                                            {{ formatRupiah($langganan->harga) }}/{{ $langganan->waktu }}
                                        </span>
                                    </div>
                                </label>
                            @endforeach

                            <p>Biaya langganan Sever +Rp 6.500</p>

                            <form id="payment-form" action="/langganan" method="POST">
                                @csrf
                                <input type="hidden" name="subscription_duration" id="subscription_duration">
                                <button type="submit" class="btn bg-primary bg-gradient fw-bold text-white">Langganan
                                    <span id="langganan"></span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Midtrans Snap.js -->
    <script type="text/javascript" src="{{ config('midtrans.url_midtrans') }}"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        $(document).ready(function() {
            // Update langganan harga saat radio button diubah
            function updateLangganan() {
                var harga = $('input[name="langganan"]:checked').closest('label').find('span').text();
                $('#langganan').text(harga + ' + Rp 6.500');

                // Set value untuk dikirim melalui form
                var duration = $('input[name="langganan"]:checked').val();
                $('#subscription_duration').val(duration);
            }

            // Jalankan fungsi saat halaman dimuat
            updateLangganan();

            // Jalankan fungsi saat salah satu radio button di-klik
            $('input[name="langganan"]').on('change', function() {
                updateLangganan();
            });

            $('#payment-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        // Menggunakan token dari respons untuk memulai pembayaran
                        window.snap.pay(response.snapToken, {
                            onSuccess: function(result) {
                                alert("Pembayaran berhasil");

                                // Ambil durasi dari radio yang dipilih
                                var selectedDays = $('#subscription_duration')
                                    .val();

                                console.log(selectedDays);


                                // Kirim data pembayaran ke server setelah pembayaran berhasil
                                $.post('/langganan/berhasil', {
                                    _token: '{{ csrf_token() }}',
                                    transaction_id: result.transaction_id,
                                    days: selectedDays
                                }).done(function(response) {
                                    console.log("Success:",
                                        response); // Log respons
                                    window.location.href =
                                        "/"; // Redirect setelah berhasil
                                }).fail(function(jqXHR, textStatus,
                                    errorThrown) {
                                    console.error("Error:", textStatus,
                                        errorThrown); // Log kesalahan
                                    alert(
                                        "Terjadi kesalahan saat memproses pembayaran."
                                    );
                                });

                            },
                            onPending: function(result) {
                                alert("Menunggu pembayaran");
                                console.log(result);
                            },
                            onError: function(result) {
                                alert("Pembayaran gagal");
                                console.log(result);
                            },
                            onClose: function() {
                                alert(
                                    "Anda menutup pop-up tanpa menyelesaikan pembayaran"
                                );
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan saat memproses pembayaran.');
                        console.error(error);
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Tanggal akhir langganan (format YYYY-MM-DD)
            var endDate = new Date("{{ auth()->user()->tgl_langganan }}T00:00:00").getTime();

            // Update countdown setiap 1 detik
            var countdownInterval = setInterval(function() {
                // Waktu saat ini
                var now = new Date().getTime();

                // Hitung selisih waktu
                var distance = endDate - now;

                // Hitung waktu untuk hari, jam, menit, dan detik
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Tampilkan hasil hitung mundur
                $('#countdown').html(days + " Hari " + hours + " Jam " +
                    minutes + " Menit " + seconds + " Detik ");

                // Jika hitungan selesai, tampilkan pesan
                if (distance < 0) {
                    clearInterval(countdownInterval);
                    $('#countdown').html("Langganan telah berakhir");
                }
            }, 1000);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
