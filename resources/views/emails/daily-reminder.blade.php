<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat Harian AI Expense Tracker</title>
    <style>
        body { font-family: 'Inter', Helvetica, Arial, sans-serif; background-color: #f3f4f6; color: #374151; line-height: 1.6; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { background: #4f46e5; padding: 30px; text-align: center; color: white; }
        .content { padding: 30px; }
        .btn { display: inline-block; background: #4f46e5; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; }
        .footer { background: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 24px;">AI Expense Tracker 🤖</h1>
        </div>
        <div class="content">
            <h2 style="color: #111827;">Halo, {{ $user->name }}! 👋</h2>
            <p>Sepertinya kamu belum mencatat transaksi apapun hari ini. Jangan biarkan pengeluaran kecil lolos dari pantauan!</p>
            <p>Mencatat pengeluaran secara rutin adalah kunci kebebasan finansial. Biarkan AI kami yang menganalisis, kamu cukup catat saja.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/transactions') }}" class="btn">📝 Catat Pengeluaran Sekarang</a>
            </div>

            <p style="margin-top: 30px; font-size: 14px; color: #6b7280;">
                <em>"Kekayaan bukanlah tentang berapa banyak yang kamu hasilkan, tapi berapa banyak yang kamu simpan."</em>
            </p>
        </div>
        <div class="footer">
            <p>Email ini dikirim otomatis oleh AI Expense Tracker.</p>
            <p>© {{ date('Y') }} AI Expense Tracker. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
