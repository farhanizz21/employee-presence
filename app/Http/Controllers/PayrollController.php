<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gajian;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollController extends Controller
{
    // Lihat slip gaji di browser
    public function showSlip($uuid)
    {
        $gajian = Gajian::with(['pegawai', 'details'])->where('uuid', $uuid)->firstOrFail();

        return view('payroll.slip', compact('gajian'));
    }

    // Export slip gaji ke PDF
    public function exportSlip($uuid)
    {
        $gajian = Gajian::with(['pegawai', 'details'])->where('uuid', $uuid)->firstOrFail();

        $pdf = Pdf::loadView('payroll.slip', compact('gajian'));
        return $pdf->download('slip-gaji-' . $gajian->pegawai->nama . '.pdf');
    }

    public function report(Request $request)
    {
        // Ambil filter bulan & tahun dari query (default: bulan sekarang)
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // Ambil data payroll dalam bulan-tahun tertentu
        $gajians = Gajian::with('pegawai')
            ->whereMonth('periode_mulai', $bulan)
            ->whereYear('periode_mulai', $tahun)
            ->get();

        return view('payroll.report', compact('gajians', 'bulan', 'tahun'));
    }
}
