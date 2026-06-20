<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .income { color: green; }
        .expense { color: red; }
    </style>
</head>
<body>
    <h2>Laporan Transaksi</h2>
    <p>Dicetak pada: {{ now()->format('Y-m-d H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Tipe</th>
                <th>Deskripsi</th>
                <th>Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $trx)
            <tr>
                <td>{{ $trx->transaction_date->format('Y-m-d') }}</td>
                <td>{{ $trx->category ? $trx->category->name : 'Lainnya' }}</td>
                <td>{{ $trx->category ? ucfirst($trx->category->type) : 'Expense' }}</td>
                <td>{{ $trx->description }}</td>
                <td class="{{ $trx->category && $trx->category->type == 'income' ? 'income' : 'expense' }}">
                    {{ number_format($trx->amount, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
