<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi – AI Expense Tracker</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', Helvetica, Arial, sans-serif;
            background-color: #f0f4ff;
            color: #374151;
            line-height: 1.6;
            padding: 40px 20px;
        }
        .wrapper {
            max-width: 560px;
            margin: 0 auto;
        }
        .card {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(37, 99, 235, 0.12);
        }
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%);
            padding: 40px 36px;
            text-align: center;
            position: relative;
        }
        .header-logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 12px;
            padding: 10px 18px;
            margin-bottom: 20px;
        }
        .header-logo span {
            color: white;
            font-weight: 700;
            font-size: 15px;
        }
        .header h1 {
            color: white;
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 6px;
        }
        .header p {
            color: #93c5fd;
            font-size: 13px;
        }
        .body {
            padding: 40px 36px;
        }
        .greeting {
            font-size: 16px;
            color: #111827;
            font-weight: 600;
            margin-bottom: 12px;
        }
        .desc {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 32px;
            line-height: 1.7;
        }
        .otp-box {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border: 2px solid #bfdbfe;
            border-radius: 16px;
            padding: 28px;
            text-align: center;
            margin-bottom: 28px;
        }
        .otp-label {
            font-size: 11px;
            font-weight: 600;
            color: #2563eb;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .otp-code {
            font-size: 48px;
            font-weight: 800;
            color: #1d4ed8;
            letter-spacing: 10px;
            font-family: 'Courier New', monospace;
            line-height: 1;
        }
        .otp-expire {
            margin-top: 14px;
            font-size: 12px;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .warning-box {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #92400e;
        }
        .footer-note {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.7;
            border-top: 1px solid #f1f5f9;
            padding-top: 24px;
            margin-top: 24px;
        }
        .footer {
            background: #f8fafc;
            padding: 24px 36px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <!-- Header -->
            <div class="header">
                <div class="header-logo">
                    <!-- coin icon -->
                    <svg width="18" height="18" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>AI Expense Tracker</span>
                </div>
                <h1>🔐 Verifikasi Akun Anda</h1>
                <p>Masukkan kode berikut untuk menyelesaikan pendaftaran</p>
            </div>

            <!-- Body -->
            <div class="body">
                <p class="greeting">Halo, {{ $userName ?: 'Pengguna Baru' }}! 👋</p>
                <p class="desc">
                    Terima kasih telah mendaftar di <strong>AI Expense Tracker</strong>.
                    Gunakan kode OTP di bawah ini untuk memverifikasi alamat email Anda.
                </p>

                <!-- OTP Code -->
                <div class="otp-box">
                    <p class="otp-label">Kode Verifikasi Anda</p>
                    <div class="otp-code">{{ $otp }}</div>
                    <div class="otp-expire">
                        <svg width="14" height="14" fill="none" stroke="#64748b" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Berlaku selama <strong>&nbsp;10 menit</strong>
                    </div>
                </div>

                <!-- Warning -->
                <div class="warning-box">
                    ⚠️ <strong>Jangan bagikan kode ini</strong> kepada siapapun, termasuk tim AI Expense Tracker.
                    Jika Anda tidak mendaftar, abaikan email ini.
                </div>

                <div class="footer-note">
                    <p>Kode ini hanya berlaku untuk satu kali penggunaan dan akan kedaluwarsa dalam 10 menit sejak email ini dikirim.</p>
                    <p style="margin-top: 8px;">Jika ada pertanyaan, hubungi kami di <a href="mailto:support@aiexpensetracker.com" style="color: #2563eb;">support@aiexpensetracker.com</a></p>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>© {{ date('Y') }} AI Expense Tracker. All rights reserved.</p>
                <p>Email otomatis — harap jangan balas email ini.</p>
            </div>
        </div>
    </div>
</body>
</html>
