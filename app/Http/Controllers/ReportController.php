<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Rap2hpoutre\FastExcel\FastExcel;

class ReportController extends Controller
{
    public function exportPdf()
    {
        if (!auth()->user()->is_premium) {
            abort(403, 'Fitur Export hanya untuk pengguna Premium.');
        }

        $transactions = Transaction::with('category')->where('user_id', auth()->id())->get();

        $pdf = Pdf::loadView('reports.transactions_pdf', compact('transactions'));
        return $pdf->download('laporan_transaksi.pdf');
    }

    public function exportExcel()
    {
        if (!auth()->user()->is_premium) {
            abort(403, 'Fitur Export hanya untuk pengguna Premium.');
        }

        $transactions = Transaction::with('category')->where('user_id', auth()->id())->get();

        return (new FastExcel($transactions))->download('laporan_transaksi.xlsx', function ($transaction) {
            return [
                'Tanggal' => $transaction->transaction_date->format('Y-m-d'),
                'Kategori' => $transaction->category ? $transaction->category->name : 'Lainnya',
                'Tipe' => $transaction->category ? $transaction->category->type : 'expense',
                'Deskripsi' => $transaction->description,
                'Jumlah' => $transaction->amount,
            ];
        });
    }
}
