<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Halaman Login | BUMDES PRO</title>

  <!-- Favicons -->
  <link href="/assets/img/logo.png" rel="icon">
  <link href="/assets/img/logo.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="/assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="/assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="/assets/css/style.css" rel="stylesheet">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
    .margin-top {
      margin-top: 100px !important;
    }

    @media only screen and (max-width: 768px) {
      .margin-top {
        margin-top: 80px !important;
      }

      .welcome-wrapper {
        margin-top: 30px !important;
      }

      .kontak {
        position: block;
      }
    }

    .kontak {
      height: 60px;
      max-width: 300px;
      background-color: white;
      padding: 5px 10px;
      border-radius: 0px 20px 20px 0px;
      position: fixed;
    }

    .email-kontak {
      font-size: 12px
    }

    /* Welcome column styles */
    .welcome-col {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 6px;
      padding: 6px;
    }

    /* Smaller welcome image and keep it responsive */
    .welcome-img {
      max-width: 160px;
      width: 100%;
      height: auto;
      display: block;
    }

    /* Header welcome: text + image sejajar */
    .welcome-header {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 2px;
      flex-wrap: wrap;
    }

    .welcome-text h2 {
      margin: 0;
      line-height: 1.05;
    }

    /* portal-group and buttons */
    .portal-group {
      width: 100%;
      max-width: 520px;
      display: flex;
      flex-direction: column;
      gap: 3px;
      margin: 12px 0;
    }

    .portal-btn {
      width: 100%;
      text-align: left;
      padding: 14px 18px;
      border-radius: 12px;
      font-weight: 600;
      white-space: normal;
      box-shadow: 0 3px 0 rgba(0, 0, 0, 0.08);
      display: inline-flex;
      align-items: center;
      justify-content: space-between;
    }

    .portal-btn .label {
      flex: 1;
      text-align: left;
    }

    .portal-btn .arrow {
      width: 34px;
      height: 34px;
      background: #ff1a1a;
      color: #fff;
      border-radius: 6px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-left: 12px;
      font-weight: 700;
    }

    .portal-btn.blue {
      background: #1e73be;
      color: #fff;
      border: 2px solid #184f8a;
    }

    .portal-btn.green {
      background: #0f6b38;
      color: #fff;
      border: 2px solid #0a4a2b;
    }

    .portal-btn.outline {
      background: #fff;
      color: #0b1a2b;
      border: 2px solid #d1d7de;
    }

    /* caption/link 'Menuju halaman login' (nempel, no box) */
    .portal-caption {
      display: flex;
      justify-content: flex-end;
      margin-top: 0;
    }

    .portal-caption a.small {
      background: transparent;
      padding: 0;
      border: none;
      font-size: 13px;
      color: #0b1a2b;
      text-decoration: underline;
      font-weight: 500;
    }

    /* Templates modal content */
    .templates-panel {
      border: 2px solid #d0d5db;
      border-radius: 8px;
      padding: 14px;
      background: #fff;
    }

    .templates-title {
      text-align: center;
      border: 2px solid #222;
      padding: 8px 12px;
      font-weight: 700;
      margin-bottom: 12px;
      display: inline-block;
      width: 100%;
      box-sizing: border-box;
    }

    .templates-list {
      margin: 0;
      padding-left: 18px;
      list-style: decimal;
      font-weight: 600;
    }

    .templates-list li {
      margin-bottom: 10px;
    }

    .templates-list a.main-toggle {
      display: inline-block;
      width: 100%;
      text-decoration: none;
      color: #0b1a2b;
      padding: 6px 4px;
      font-weight: 700;
    }

    .templates-list a.main-toggle:hover {
      text-decoration: underline;
    }

    /* sublist that shows after clicking main li */
    .sub-list {
      margin-top: 8px;
      padding-left: 18px;
      list-style: lower-alpha;
      font-weight: 500;
    }

    .sub-list li {
      margin-bottom: 6px;
    }

    .sub-list a {
      text-decoration: none;
      color: #0b1a2b;
    }

    .sub-list a:hover {
      text-decoration: underline;
    }

    /* modal custom styling */
    .modal-body {
      max-height: 60vh;
      overflow: auto;
      padding: 18px;
    }

    .modal-header-blue {
      background: #1e73be;
      color: #fff;
      padding: 12px 16px;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
      position: relative;
    }

    .modal-header-green {
      background: #0f6b38;
      color: #fff;
      padding: 12px 16px;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
      position: relative;
    }

    .modal-header-plain {
      background: #ffffff;
      color: #0b1a2b;
      padding: 12px 16px;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
      position: relative;
      border-bottom: 1px solid #e6e6e6;
    }

    .modal-header-blue h5,
    .modal-header-green h5,
    .modal-header-plain h5 {
      margin: 0;
      font-weight: 700;
    }

    /* close circle (putting X di luar sudut header) */
  .modal-close-circle {
      position: absolute;
      right: -18px;
      top: -18px;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: #fff;

      display: flex;
      align-items: center;
      justify-content: center;

      border: 2px solid #222;
      cursor: pointer;

      color: #000;          /* ✅ WARNA TEKS HITAM */
      font-size: 18px;      /* opsional: biar proporsional */
      font-weight: 600;     /* opsional: biar tegas */
  }

    /* === PRICE SECTION === */
    
    .price-section {
    margin-top: 16px;
    }

    .price-title {
    font-weight: 700;
    margin-bottom: 8px;
    }

    /* container harga */
    .price-bar {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    border-radius: 6px;
    font-weight: 700;
    }

    /* warna background mengikuti modal */
    .price-blue {
    background: #1e73be;
    }

    .price-green {
    background: #0f6b38;
    }

    /* harga lama */
    .price-bar .old {
    background: #d90429;     /* merah */
    color: #fff;
    padding: 6px 10px;
    border-radius: 4px;
    text-decoration: line-through;
    font-weight: 600;
    }

    /* harga baru */
    .price-bar .new {
    color: #ffd60a;          /* kuning */
    font-size: 16px;
    font-weight: 800;
    }
    /* === PRICE BAR FULL WIDTH DI DALAM <li> === */
    /* PRICE BAR KELUAR DARI INDENT <ol> */
    .modal-list li .price-bar {
    width: calc(100% + 32px); /* kompensasi padding ol */
    margin-left: -32px;
    box-sizing: border-box;
    }

    /* === BLACK THEME (REPLACE GREEN) === */

    /* portal button black */
    .portal-btn.black {
    background: #000;
    color: #fff;
    border: 2px solid #000;
    }

    /* modal header black */
    .modal-header-black {
    background: #000;
    color: #fff;
    padding: 12px 16px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    position: relative;
    }

    /* price bar black */
    .price-black {
    background: #000;
    }

    /* === PRAKTIKUM MODE & PORTAL BUTTONS === */
    .praktikum-card-box {
      background: #ffffff;
      border: 1.5px solid #e2e8f0;
      border-radius: 16px;
      padding: 16px 18px;
      margin-bottom: 12px;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
      width: 100%;
    }
    .btn-praktikum-action {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 12px 18px;
      border-radius: 12px;
      font-weight: 800;
      font-size: 13.5px;
      text-decoration: none;
      transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
      border: none;
    }
    .btn-praktikum-bumdes {
      background: linear-gradient(135deg, #0284c7, #0369a1);
      color: #ffffff !important;
      box-shadow: 0 6px 16px rgba(2, 132, 199, 0.28);
    }
    .btn-praktikum-bumdes:hover {
      background: linear-gradient(135deg, #0369a1, #075985);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(2, 132, 199, 0.38);
    }
    .btn-praktikum-koperasi {
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      color: #ffffff !important;
      box-shadow: 0 6px 16px rgba(37, 99, 235, 0.28);
    }
    .btn-praktikum-koperasi:hover {
      background: linear-gradient(135deg, #1d4ed8, #1e40af);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(37, 99, 235, 0.38);
    }

    .portal-action-btn {
      width: 100%;
      text-align: left;
      padding: 12px 16px;
      border-radius: 14px;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: space-between;
      text-decoration: none;
      background: #ffffff;
      border: 1.5px solid #e2e8f0;
      color: #1e293b;
      margin-bottom: 10px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
      transition: all 0.25s ease;
    }
    .portal-action-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
      border-color: #cbd5e1;
      color: #0f172a;
    }
    .portal-action-btn .btn-icon-box {
      width: 42px;
      height: 42px;
      border-radius: 10px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 19px;
      margin-right: 12px;
      flex-shrink: 0;
    }
    .portal-action-btn .btn-arrow-box {
      width: 34px;
      height: 34px;
      border-radius: 8px;
      background: #f1f5f9;
      color: #64748b;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 13px;
      margin-left: 10px;
      flex-shrink: 0;
      transition: all 0.2s ease;
    }
    .portal-action-btn:hover .btn-arrow-box {
      background: #0284c7;
      color: #ffffff;
    }

    /* === ECOSYSTEM 4 CARDS SECTION AT BOTTOM === */
    .ecosystem-section {
      border-top: 1px solid #e2e8f0;
      padding-top: 40px;
      margin-top: 50px;
    }
    .app-card {
      background: #ffffff;
      border: 1.5px solid #e2e8f0;
      border-radius: 16px;
      padding: 22px 20px;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      position: relative;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }
    .app-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 16px 32px rgba(0, 0, 0, 0.09);
      border-color: #cbd5e1;
    }
    .app-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
    }
    .app-card.card-blue::before { background: #0284c7; }
    .app-card.card-green::before { background: #16a34a; }
    .app-card.card-dark::before { background: #1e293b; }
    .app-card.card-amber::before { background: #d97706; }

    .app-card-icon {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
    }
    .app-card.card-blue .app-card-icon { background: #e0f2fe; color: #0284c7; }
    .app-card.card-green .app-card-icon { background: #dcfce7; color: #16a34a; }
    .app-card.card-dark .app-card-icon { background: #f1f5f9; color: #1e293b; }
    .app-card.card-amber .app-card-icon { background: #fef3c7; color: #d97706; }

    .app-card-title {
      font-size: 15.5px;
      font-weight: 700;
      color: #0f172a;
      line-height: 1.35;
      margin-top: 12px;
      margin-bottom: 8px;
      min-height: 44px;
    }
    .app-card-title a {
      color: inherit;
      transition: color 0.2s ease;
    }
    .app-card-title a:hover {
      color: #0284c7 !important;
    }
    .app-card-desc {
      font-size: 13px;
      color: #64748b;
      line-height: 1.55;
      margin-bottom: 14px;
    }
    .app-card-features {
      list-style: none;
      padding: 0;
      margin: 0 0 16px 0;
      font-size: 12px;
      color: #475569;
    }
    .app-card-features li {
      display: flex;
      align-items: center;
      gap: 7px;
      margin-bottom: 6px;
    }
    .app-card-features li i {
      font-size: 11px;
    }
    .app-card.card-blue .app-card-features li i { color: #0284c7; }
    .app-card.card-green .app-card-features li i { color: #16a34a; }
    .app-card.card-dark .app-card-features li i { color: #475569; }
    .app-card.card-amber .app-card-features li i { color: #d97706; }

    .app-card-price {
      background: #f8fafc;
      border: 1px dashed #cbd5e1;
      border-radius: 10px;
      padding: 8px 12px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 14px;
      font-size: 12px;
    }
    .app-card-price .old-price {
      text-decoration: line-through;
      color: #ef4444;
      font-weight: 600;
    }
    .app-card-price .new-price {
      font-weight: 800;
      color: #0f172a;
    }
    .app-card-actions {
      display: flex;
      gap: 8px;
    }
    .app-card-actions .btn {
      font-size: 12px;
      font-weight: 700;
      border-radius: 9px;
      padding: 8px 12px;
    }

    


  </style>
</head>

<body>

  <main>
    <div class="kontak shadow-sm mt-3 fixed-top">
      <div class="d-flex justify-content-start">
        <img src="/assets/img/logo.png" alt="" width="40px" height="40px">
        <span class="email-kontak ms-3">Kontak Kami <br> bumdespro@gmail.com</span>
      </div>
    </div>

    <div class="container">
      <section class="">
        <div class="container">
          <div class="row align-items-center justify-content-center">

            <!-- LEFT on desktop (order-lg-1), BOTTOM on mobile (order-2): Welcome & Portals -->
            <div class="col-lg-7 col-xl-8 d-flex align-items-center justify-content-center welcome-wrapper order-2 order-lg-1" style="margin:auto; margin-top: 100px;">
              <div class="welcome-col text-center">

                <div class="welcome-header">
                  <div class="welcome-text text-center text-lg-start">
                    <h2 class="fw-bold">Selamat Datang</h2>
                    <h2 class="fw-bold">di BUMDES PRO</h2>
                  </div>

                  <img src="/assets/img/akuntansi.png" alt="akuntansi" class="welcome-img">
                </div>

                <!-- Portal & Praktikum Group -->
                <div class="portal-group">

                  <!-- 1. Mode Praktikum (BUMDesa & Koperasi) -->
                  <div class="praktikum-card-box text-start">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                      <span class="badge bg-primary-subtle text-primary fw-bold px-2 py-1 rounded-pill" style="font-size: 11px;">
                        <i class="fa-solid fa-flask me-1"></i> Mode Praktikum Gratis
                      </span>
                      <small class="text-muted fw-semibold" style="font-size: 11px;">Akses Demo 1 Jam</small>
                    </div>
                    <p class="text-muted small mb-3" style="font-size: 12px; line-height: 1.45;">
                      Coba langsung simulasi input pembukuan dan laporan keuangan tanpa perlu registrasi:
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                      <a href="{{ $bumdesproBumdesaUrl ?? ($bumdesproUrl ?? 'https://bumdespro.my.id/login?token=0g8fICqkVABCIgboebqLkuJnDcrdjXz833lg9uyo&referral=1') }}" target="_blank" class="btn-praktikum-action btn-praktikum-bumdes flex-fill">
                        <i class="fa-solid fa-rocket"></i> Coba Gratis BUMDesa
                      </a>
                      <a href="{{ $bumdesproKoperasiUrl ?? 'https://bumdespro.my.id/login?token=0g8fICqkVABCIgboebqLkuJnDcrdjXz833lg9uyo&referral=0' }}" target="_blank" class="btn-praktikum-action btn-praktikum-koperasi flex-fill">
                        <i class="fa-solid fa-rocket"></i> Coba Gratis Koperasi
                      </a>
                    </div>
                  </div>

                  <!-- 2. BUMDes Academy -->
                  <a href="https://portalbumdes.com/academy" target="_blank" class="portal-action-btn">
                    <div class="d-flex align-items-center">
                      <div class="btn-icon-box" style="background: #ecfdf5; color: #059669;">
                        <i class="fa-solid fa-graduation-cap"></i>
                      </div>
                      <div class="text-start">
                        <div class="fw-bold" style="font-size: 14px; color: #0f172a;">BUMDes Academy</div>
                        <div class="text-muted" style="font-size: 11px;">Lihat tutorial dan coba gratis</div>
                      </div>
                    </div>
                    <div class="btn-arrow-box">
                      <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </div>
                  </a>

                  <!-- 3. Halaman Tentang Aplikasi Keuangan BUMDes -->
                  <a href="https://bumdespro.com" target="_blank" class="portal-action-btn">
                    <div class="d-flex align-items-center">
                      <div class="btn-icon-box" style="background: #eff6ff; color: #2563eb;">
                        <i class="fa-solid fa-globe"></i>
                      </div>
                      <div class="text-start">
                        <div class="fw-bold" style="font-size: 14px; color: #0f172a;">Tentang Aplikasi BUMDES PRO</div>
                        <div class="text-muted" style="font-size: 11px;">Profil Fitur, Portofolio & Informasi bumdespro.com</div>
                      </div>
                    </div>
                    <div class="btn-arrow-box">
                      <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </div>
                  </a>

                </div>
              </div>
            </div>

            <!-- RIGHT on desktop (order-lg-2), TOP on mobile (order-1): Login Card -->
            <div class="col-lg-5 col-xl-4 mt-3 margin-top order-1 order-lg-2">
              <div class="card mb-3">
                <div class="card-body">
                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-6">Aplikasi Pembukuan dan Pelaporan Keuangan BUMDesa, Silahkan Login Akun Anda</h5>
                    <p class="text-center small">Masukan Email dan Password Anda</p>
                  </div>

                  <!-- Blade session and form unchanged -->
                  @if(session('success'))
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                  @endif

                  @if(session('error'))
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                  @endif

                  @if(session('status'))
                  <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle me-1"></i>
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                  @endif

                  <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="col-12 mt-3">
                      <label for="email" class="form-label">Email</label>
                      <div class="input-group has-validation">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                      </div>
                    </div>

                    <div class="col-12 mt-3">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                      @error('password')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>

                    <a href="/ganti-password">Lupa password</a>

                    <div class="col-12 mt-3">
                      <button class="btn btn-primary w-100" type="submit">Login</button>
                    </div>
                  </form>

                  <div class="col-12 mt-3">
                    <p class="small mb-0">Tidak punya akun? <a href="{{ url('/admin/data-user/create') }}">Buat akun baru</a></p>
                  </div>
                  <hr>
                  <div class="text-center mt-3">
                    <a href="https://portalbumdes.com/"><i class="bi bi-question-circle"></i> Klik disini</a> untuk pelajari bumdes
                  </div>

                </div>
              </div>
            </div>

          </div> <!-- End Login & Welcome Row -->

          <!-- BAGIAN BAWAH: 4 CARD EKOSISTEM APLIKASI & PRODUK DIGITAL -->
          <div class="ecosystem-section">
            <div class="text-center mb-4">
              <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold text-uppercase" style="letter-spacing: 0.8px; font-size: 11px;">
                <i class="fa-solid fa-layer-group me-1"></i> Ekosistem Layanan Terpadu
              </span>
              <h3 class="fw-bold mt-2 mb-2" style="color: #0f172a; font-weight: 800;">Aplikasi & Produk Pendukung BUMDesa</h3>
              <p class="text-muted mx-auto" style="max-width: 640px; font-size: 13.5px;">
                Tingkatkan transparansi dan profesionalisme tata kelola BUMDesa Anda dengan platform pembukuan, SPJ digital, website desa, dan template terstandar.
              </p>
            </div>

            <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-xl-4">
              
              <!-- Card 1: Aplikasi Pembukuan dan Pelaporan Keuangan BUMDesa -->
              <div class="col">
                <div class="app-card card-blue">
                  <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                      <div class="app-card-icon">
                        <i class="fa-solid fa-calculator"></i>
                      </div>
                      <span class="badge bg-primary-subtle text-primary fw-bold px-2 py-1 rounded-pill" style="font-size: 10px;">Aplikasi Keuangan</span>
                    </div>
                    <h5 class="app-card-title">
                      <a href="#" data-bs-toggle="modal" data-bs-target="#modalPembukuan" class="text-decoration-none text-dark">
                        Aplikasi Pembukuan & Pelaporan Keuangan BUMDesa
                      </a>
                    </h5>
                  </div>

                  <div class="app-card-actions mt-3">
                    <button type="button" class="btn btn-outline-primary flex-fill" data-bs-toggle="modal" data-bs-target="#modalPembukuan">
                      <i class="fa-solid fa-circle-info me-1"></i> Detail
                    </button>
                    <a href="https://bumdespro.my.id/login" target="_blank" class="btn btn-primary flex-fill">
                      <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Login
                    </a>
                  </div>
                </div>
              </div>

              <!-- Card 2: Aplikasi SPJ Digital dan Dokumen Audit -->
              <div class="col">
                <div class="app-card card-green">
                  <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                      <div class="app-card-icon">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                      </div>
                      <span class="badge bg-success-subtle text-success fw-bold px-2 py-1 rounded-pill" style="font-size: 10px;">Arsip & SPJ</span>
                    </div>
                    <h5 class="app-card-title">
                      <a href="#" data-bs-toggle="modal" data-bs-target="#modalSPJDigital" class="text-decoration-none text-dark">
                        Aplikasi SPJ Digital & Dokumen Audit
                      </a>
                    </h5>
                  </div>

                  <div class="app-card-actions mt-3">
                    <button type="button" class="btn btn-outline-success flex-fill" data-bs-toggle="modal" data-bs-target="#modalSPJDigital">
                      <i class="fa-solid fa-circle-info me-1"></i> Detail
                    </button>
                    <a href="https://bumdespro2.my.id/login" target="_blank" class="btn btn-success flex-fill">
                      <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Login
                    </a>
                  </div>
                </div>
              </div>

              <!-- Card 3: Aplikasi Pengelolaan Website BUMDES -->
              <div class="col">
                <div class="app-card card-dark">
                  <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                      <div class="app-card-icon">
                        <i class="fa-solid fa-globe"></i>
                      </div>
                      <span class="badge bg-secondary-subtle text-dark fw-bold px-2 py-1 rounded-pill" style="font-size: 10px;">Website Desa</span>
                    </div>
                    <h5 class="app-card-title">
                      <a href="#" data-bs-toggle="modal" data-bs-target="#modalTataAdmin" class="text-decoration-none text-dark">
                        Aplikasi Pengelolaan Website BUMDES
                      </a>
                    </h5>
                  </div>

                  <div class="app-card-actions mt-3">
                    <button type="button" class="btn btn-outline-dark flex-fill" data-bs-toggle="modal" data-bs-target="#modalTataAdmin">
                      <i class="fa-solid fa-circle-info me-1"></i> Detail
                    </button>
                    <a href="/login" class="btn btn-dark flex-fill">
                      <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Login
                    </a>
                  </div>
                </div>
              </div>

              <!-- Card 4: Kumpulan Template dan Produk Digital -->
              <div class="col">
                <div class="app-card card-amber">
                  <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                      <div class="app-card-icon">
                        <i class="fa-solid fa-folder-open"></i>
                      </div>
                      <span class="badge bg-warning-subtle text-warning-emphasis fw-bold px-2 py-1 rounded-pill" style="font-size: 10px;">Template & SOP</span>
                    </div>
                    <h5 class="app-card-title">
                      <a href="{{ url('/templates') }}" target="_blank" class="text-decoration-none text-dark">
                        Kumpulan Template & Produk Digital
                      </a>
                    </h5>
                  </div>

                  <div class="app-card-actions mt-3">
                    <button type="button" class="btn btn-outline-warning text-dark flex-fill" data-bs-toggle="modal" data-bs-target="#modalTemplates">
                      <i class="fa-solid fa-list me-1"></i> Kategori
                    </button>
                    <a href="{{ url('/templates') }}" target="_blank" class="btn btn-warning text-dark fw-bold flex-fill">
                      <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka
                    </a>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </section>
    </div>
  </main>


  <!-- Modal Pembukuan (blue) -->
  <div class="modal fade" id="modalPembukuan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header-blue">
            <h5>Aplikasi Pembukuan dan Pelaporan Keuangan BUMDesa</h5>
             <div class="modal-close-circle" data-bs-dismiss="modal">✕</div>
        </div>

        <div class="modal-body">
            <ol class="modal-list">

            <li>
                <strong>Pembukuan yang dapat digunakan</strong>
                <ul>
                  <li>Buku Kas Umum</li>
                  <li>Buku Inventaris</li>
                  <li>Buku Persediaan</li>
                  <li>Buku Piutang dan Hutang</li>
                  <li>Buku Riwayat Bagi Hasil</li>
                  <li>Lembar Laba Rugi bulanan</li>
                  <li>Laporan Neraca</li>
                  <li>Laporan Laba Rugi</li>
                  <li>Laporan Arus Kas</li>
                  <li>Menyusun Laporan Pertanggungjawaban (LPJ)</li>
                  <li>Menyusun Rencana Program Kerja (Proker)</li>
                  <li>Menyusun Analisa Kelayakan Usaha (Ketapang)</li>
                </ul>
            </li>

            <li>
                <strong>Fitur dan Kelebihan</strong>
                <ul>
                  <li>Double Entri Otomatis</li>
                  <li>Perhitungan Penyusutan Otomatis</li>
                  <li>Pencatatan Berkesinambungan</li>
                  <li>Reset Otomatis saat ganti tahun pembukuan</li>
                  <li>Komparasi Proyeksi dan Realisasi Otomatis</li>
                  <li>Dapat Langsung digunakan orang awam akuntansi</li>
                </ul>
            </li>

            <!-- ✅ LI KE-3: HARGA APLIKASI -->
            <li>
                <strong>Harga Aplikasi</strong>

                <div class="price-bar price-blue mt-2">
                <span class="old">Rp. 300.000 / bulan</span>
                <span class="new">Rp. 10.000 / bulan</span>
                </div>
            </li>

            </ol>

            <div class="text-center mt-4">
              <a href="https://bumdespro.my.id/login" target="_blank" class="btn btn-outline-dark">Menuju Halaman Login</a>
            </div>
        </div>
        </div>
    </div>
  </div>


  <!-- Modal SPJ Digital (green) -->
  <div class="modal fade" id="modalSPJDigital" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header-green">
          <h5 class="text-start">Aplikasi SPJ Digital dan Dokumen Audit</h5>
          <div class="modal-close-circle" data-bs-dismiss="modal">✕</div>
        </div>

        <div class="modal-body text-start">
          <ol class="modal-list">
            <li>
              <strong>Pembukuan yang dapat digunakan</strong>
              <ul>
                <li>Bukti Kas Masuk dan Keluar</li>
                <li>Bukti Bank Masuk dan Keluar</li>
                <li>Merekap dan Mencetak Dokumen Arsip Keuangan</li>
                <li>Rekap dan Kodefikasi Surat Masuk dan Keluar</li>
                <li>Arsip Standard Operasional Prosedur (SOP)</li>
                <li>Arsip Dokumen Berita Acara</li>
                <li>Arsip Dokumen Perjanjian Kerjasama</li>
                <li>Arsip Surat Perintah Perjalanan Tugas (SPPT)</li>
                <li>Arsip Dokumen Notulen Rapat</li>
                <li>Arsip Dokumentasi Berkas</li>
                <li>Arsip Dokumentasi Foto dan Video</li>
              </ul>
            </li>

            <li>
              <strong>Fitur dan Kelebihan</strong>
              <ul>
                <li>Kodefikasi Dokumen Otomatis</li>
                <li>Dapat langsung didownload template-template dokumen yang dibutuhkan</li>
                <li>Dapat Menyimpan dan Mengakses Otomatis Dokumen yang diarsipkan</li>
                <li>Terintegrasi dengan Google Drive</li>
              </ul>
            </li>

            <li>
              <strong>Harga Aplikasi</strong>
              <div class="price-bar price-green mt-2">
                <span class="old">Rp. 300.000 / bulan</span>
                <span class="new">Rp. 10.000 / bulan</span>
              </div>
            </li>
          </ol>

          <div class="text-center mt-4">
            <a href="https://bumdespro2.my.id/login" target="_blank" class="btn btn-outline-dark">Menuju Halaman Login</a>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal Website BUMDES (black) -->
    <div class="modal fade" id="modalTataAdmin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

            <div class="modal-header-black">
                <h5>Aplikasi Pengelolaan Website BUMDES</h5>
                <div class="modal-close-circle" data-bs-dismiss="modal">✕</div>
            </div>

            <div class="modal-body">
                <ol class="modal-list">

                <li>
                    <strong>Informasi yang dapat dipublikasikan:</strong>
                    <ul>
                      <li>Profil BUMDesa</li>
                      <li>Unit Usaha dan Kegiatan</li>
                      <li>Galeri Kegiatan</li>
                      <li>Papan Informasi, Berita dan Artikel</li>
                      <li>Papan Katalog BUMDesa/ Desa</li>
                      <li>Produk Ketahanan Pangan</li>
                      <li>Mitra Kerjasama BUMDesa</li>
                      <li>Kelengkapan Struktur Organisasi</li>
                      <li>Publikasi Kinerja dan Capaian</li>
                      <li>Transparansi dan Akuntabilitas</li>
                      <li>Map Kantor / Sekretariat BUMDesa</li>
                      <li>Kontak Person</li>
                    </ul>
                </li>

                <li>
                    <strong>Fitur dan Kelebihan</strong>
                    <ul>
                      <li>Template tersedia, tidak perlu coding</li>
                      <li>Mengisi Konten Mudah</li>
                      <li>Publikasi Cepat</li>
                      <li>Domain Gratis</li>
                      <li>Terintegrasi dengan akun Dinas</li>
                    </ul>
                </li>

                <li>
                    <strong>Harga Aplikasi</strong>
                    <div class="price-bar price-black mt-2">
                    <span class="old">Rp. 300.000 / bulan</span>
                    <span class="new">Rp. 10.000 / bulan</span>
                    </div>
                </li>

                </ol>

                <div class="text-center mt-4">
                <a href="/login" class="btn btn-outline-dark">Menuju Halaman Login</a>
                </div>
            </div>

            </div>
        </div>
        </div>

        <!-- Modal Templates (dynamic dari DB) -->
        <div class="modal fade" id="modalTemplates" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">

              <div class="modal-header-plain">
                  <h5>Kumpulan Template dan Produk Digital</h5>
                  <div class="modal-close-circle" data-bs-dismiss="modal">✕</div>
              </div>

              <div class="modal-body">
                  <div class="templates-panel">
                  <div class="templates-title">KATEGORI</div>

                  <ol class="templates-list">
                      @forelse($categories ?? [] as $category)
                      <li>
                          <a class="main-toggle"
                          data-bs-toggle="collapse"
                          href="#subCat{{ $category->id }}"
                          role="button">
                          {{ $category->kategori }}
                          </a>

                          <ol class="collapse sub-list" id="subCat{{ $category->id }}">
                          @forelse($category->subCategories as $sub)
                              <li>
                              <a href="{{ $sub->link ?? '#' }}" target="_blank">
                                  {{ $sub->sub_kategori }}
                              </a>
                              </li>
                          @empty
                              <li><em>Tidak ada sub kategori</em></li>
                          @endforelse
                          </ol>
                      </li>
                      @empty
                      <li>Tidak ada kategori</li>
                      @endforelse
                  </ol>

                  </div>
              </div>

              </div>
          </div>
        </div>


  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="/assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/vendor/chart.js/chart.umd.js"></script>
  <script src="/assets/vendor/echarts/echarts.min.js"></script>
  <script src="/assets/vendor/quill/quill.min.js"></script>
  <script src="/assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="/assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="/assets/vendor/php-email-form/validate.js"></script>

  <!-- Optional JS: close other opened sublists when opening one (UX improvement) -->
  <script>
    document.addEventListener('show.bs.collapse', function (e) {
      // when a sublist is opened, close other open sublists inside templates panel
      const opening = e.target;
      if (!opening) return;
      const allSub = document.querySelectorAll('#modalTemplates .sub-list.collapse');
      allSub.forEach(function (el) {
        if (el !== opening) {
          // hide other open ones
          const bs = bootstrap.Collapse.getInstance(el);
          if (bs) bs.hide();
        }
      });
    });

    // optional: if user clicks a sub-list link, close modal (optional) or you can let navigation happen
    document.querySelectorAll('#modalTemplates .sub-list a').forEach(function (el) {
      el.addEventListener('click', function () {
        // let link navigate; if you want to close modal before navigation uncomment:
        // bootstrap.Modal.getInstance(document.getElementById('modalTemplates'))?.hide();
      });
    });
  </script>

  <!-- Template Main JS File -->
  <script src="/assets/js/main.js"></script>

</body>

</html>
