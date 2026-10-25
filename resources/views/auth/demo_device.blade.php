<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Menyiapkan Sesi Praktikum...</title>
    <noscript>
        <meta http-equiv="refresh" content="0;url={{ $fallbackUrl }}">
    </noscript>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            color: #fff;
        }

        .wrap {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
            text-align: center;
            padding: 0 20px;
        }

        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid rgba(255, 255, 255, .3);
            border-top-color: #ffc107;
            border-radius: 50%;
            animation: spin .8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="spinner"></div>
        <h1 style="font-size:1.25rem;margin:0;">Menyiapkan Sesi Praktikum...</h1>
        <p style="margin:0;opacity:.85;font-size:.9rem;">Data praktikum disimpan terpisah untuk setiap perangkat.</p>
    </div>

    <script>
        (function() {
            var STORAGE_KEY = 'sibudi_demo_device_id';
            var deviceId = null;

            function generateId() {
                if (window.crypto && typeof window.crypto.randomUUID === 'function') {
                    return window.crypto.randomUUID();
                }
                var s = '';
                for (var i = 0; i < 32; i++) {
                    s += Math.floor(Math.random() * 16).toString(16);
                }
                return s.substr(0, 8) + '-' + s.substr(8, 4) + '-' + s.substr(12, 4) + '-' + s.substr(16, 4) + '-' + s.substr(20, 12);
            }

            try {
                deviceId = window.localStorage.getItem(STORAGE_KEY);
                if (!deviceId || !/^[A-Za-z0-9\-]{8,64}$/.test(deviceId)) {
                    deviceId = generateId();
                    window.localStorage.setItem(STORAGE_KEY, deviceId);
                }
            } catch (e) {
                // localStorage tidak tersedia (mis. mode privat ketat) -> pakai ID sementara
                deviceId = generateId();
            }

            var url = new URL(window.location.href);
            url.searchParams.set('device', deviceId);
            window.location.replace(url.toString());
        })();
    </script>
</body>

</html>
