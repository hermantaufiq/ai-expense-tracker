<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Undangan Grup</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f3f4f6; color: #1f2937; line-height: 1.6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { background-color: #2563eb; color: #ffffff; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; text-align: center; }
        .content p { font-size: 16px; margin-bottom: 20px; color: #4b5563; }
        .btn { display: inline-block; background-color: #2563eb; color: #ffffff !important; text-decoration: none; font-weight: bold; padding: 14px 28px; border-radius: 8px; margin-top: 10px; font-size: 16px; }
        .footer { background-color: #f9fafb; padding: 20px; text-align: center; font-size: 13px; color: #6b7280; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>AI Expense Tracker</h1>
        </div>
        <div class="content">
            <h2>Anda diundang!</h2>
            <p>Halo! <strong>{{ $inviterName }}</strong> mengundang Anda untuk bergabung ke dalam grup finansial bersama bernama <strong>"{{ $group->name }}"</strong>.</p>
            
            <p>Di grup ini, Anda bisa berkolaborasi mencatat dan memantau pemasukan serta pengeluaran secara transparan bersama anggota lainnya.</p>
            
            <a href="{{ $inviteLink }}" class="btn">Gabung ke Grup Sekarang</a>
            
            <p style="margin-top: 30px; font-size: 14px;">Jika tombol di atas tidak berfungsi, Anda juga bisa menyalin link berikut:<br>
            <a href="{{ $inviteLink }}">{{ $inviteLink }}</a></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} AI Expense Tracker. Hak cipta dilindungi.
        </div>
    </div>
</body>
</html>
