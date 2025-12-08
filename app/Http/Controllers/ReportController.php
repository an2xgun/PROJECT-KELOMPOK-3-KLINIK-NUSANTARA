<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Rekam;
use App\Models\Prescription;
use App\Models\Invoice;
use App\Models\Obat;
use App\Models\PrescriptionItem;
use App\Models\MasterDiagnosa;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Dashboard Laporan Utama
     */
    public function dashboard()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        
        // Statistik Hari Ini
        $kunjungan_hari_ini = Pendaftaran::whereDate('created_at', $today)->count();
        $pemeriksaan_selesai = Rekam::whereDate('tanggalperiksa', $today)->count();
        $resep_diberikan = Prescription::whereDate('created_at', $today)->count();
        $pendapatan_hari_ini = Invoice::whereDate('created_at', $today)
            ->where('status', 'Paid')
            ->sum('total');

        // Statistik Bulan Ini
        $total_kunjungan_bulan = Pendaftaran::whereBetween('created_at', [$thisMonth, now()])->count();
        $total_pendapatan_bulan = Invoice::whereBetween('created_at', [$thisMonth, now()])
            ->where('status', 'Paid')
            ->sum('total');

        // Top 5 Diagnosa
        $top_diagnosa = $this->getTopDiagnosa(5);

        // Stok Obat Kritis
        $obat_kritis = Obat::where('stok', '<', 10)->count();

        return view('reports.dashboard', compact(
            'kunjungan_hari_ini',
            'pemeriksaan_selesai',
            'resep_diberikan',
            'pendapatan_hari_ini',
            'total_kunjungan_bulan',
            'total_pendapatan_bulan',
            'top_diagnosa',
            'obat_kritis'
        ));
    }

    /**
     * Laporan Kunjungan Harian
     */
    public function kunjunganHarian(Request $request)
    {
        $dari = $request->get('dari') ? Carbon::parse($request->get('dari')) : Carbon::today();
        $sampai = $request->get('sampai') ? Carbon::parse($request->get('sampai')) : Carbon::today();

        $kunjungan = Pendaftaran::with(['pasien', 'poliklinik'])
            ->whereBetween('created_at', [$dari->startOfDay(), $sampai->endOfDay()])
            ->orderBy('created_at', 'DESC')
            ->get();

        $total_kunjungan = $kunjungan->count();
        $menunggu = $kunjungan->where('status_layanan', 'Menunggu')->count();
        $sedang_dilayani = $kunjungan->where('status_layanan', 'Sedang Dilayani')->count();
        $selesai = $kunjungan->where('status_layanan', 'Selesai')->count();

        return view('reports.kunjungan_harian', compact(
            'kunjungan',
            'total_kunjungan',
            'menunggu',
            'sedang_dilayani',
            'selesai',
            'dari',
            'sampai'
        ));
    }

    /**
     * Laporan Stok Obat
     */
    public function stokObat(Request $request)
    {
        $filter_status = $request->get('status', 'semua');
        
        $query = Obat::with('jenis');
        
        if ($filter_status === 'kritis') {
            $query->where('stok', '<', 10);
        } elseif ($filter_status === 'kosong') {
            $query->where('stok', '<=', 0);
        }

        $obat = $query->orderBy('stok', 'ASC')->get();

        $total_jenis = Obat::count();
        $stok_kritis = Obat::where('stok', '<', 10)->count();
        $stok_kosong = Obat::where('stok', '<=', 0)->count();

        return view('reports.stok_obat', compact(
            'obat',
            'total_jenis',
            'stok_kritis',
            'stok_kosong',
            'filter_status'
        ));
    }

    /**
     * Laporan Resep & Obat Keluar
     */
    public function resepObatKeluar(Request $request)
    {
        $dari = $request->get('dari') ? Carbon::parse($request->get('dari')) : Carbon::today();
        $sampai = $request->get('sampai') ? Carbon::parse($request->get('sampai')) : Carbon::today();

        $resep = Prescription::with(['rekam.pasien', 'dokter', 'items.obat'])
            ->whereBetween('created_at', [$dari->startOfDay(), $sampai->endOfDay()])
            ->orderBy('created_at', 'DESC')
            ->get();

        $total_resep = $resep->count();
        $total_obat_keluar = PrescriptionItem::whereBetween('created_at', [$dari->startOfDay(), $sampai->endOfDay()])->sum('jumlah');
        $total_nilai_obat = PrescriptionItem::whereBetween('created_at', [$dari->startOfDay(), $sampai->endOfDay()])
            ->sum('subtotal');

        return view('reports.resep_obat_keluar', compact(
            'resep',
            'total_resep',
            'total_obat_keluar',
            'total_nilai_obat',
            'dari',
            'sampai'
        ));
    }

    /**
     * Laporan Keuangan
     */
    public function keuangan(Request $request)
    {
        $dari = $request->get('dari') ? Carbon::parse($request->get('dari')) : Carbon::today();
        $sampai = $request->get('sampai') ? Carbon::parse($request->get('sampai')) : Carbon::today();

        $invoice = Invoice::with(['pasien', 'rekam'])
            ->whereBetween('created_at', [$dari->startOfDay(), $sampai->endOfDay()])
            ->orderBy('created_at', 'DESC')
            ->get();

        $total_layanan = $invoice->sum('total');
        $total_dibayar = $invoice->where('status', 'Paid')->sum('total');
        $total_pending = $invoice->where('status', 'Pending')->sum('total');
        $jumlah_transaksi = $invoice->count();

        // Breakdown per metode pembayaran
        $breakdown_pembayaran = $this->getBreakdownPembayaran($dari, $sampai);

        return view('reports.keuangan', compact(
            'invoice',
            'total_layanan',
            'total_dibayar',
            'total_pending',
            'jumlah_transaksi',
            'breakdown_pembayaran',
            'dari',
            'sampai'
        ));
    }

    /**
     * Laporan Diagnosa (ICD-10)
     */
    public function diagnosa(Request $request)
    {
        $dari = $request->get('dari') ? Carbon::parse($request->get('dari')) : Carbon::today()->subMonth();
        $sampai = $request->get('sampai') ? Carbon::parse($request->get('sampai')) : Carbon::today();

        // Top diagnosa
        $diagnosa_data = $this->getDiagnosaTrendPeriod($dari, $sampai);

        return view('reports.diagnosa', compact(
            'diagnosa_data',
            'dari',
            'sampai'
        ));
    }

    /**
     * Helper: Get Top Diagnosa
     */
    private function getTopDiagnosa($limit = 5)
    {
        $diagnosa = MasterDiagnosa::withCount('rekam')
            ->orderBy('rekam_count', 'DESC')
            ->limit($limit)
            ->get();

        return $diagnosa->map(function ($d) {
            return [
                'nama' => $d->nama,
                'jumlah' => $d->rekam_count,
            ];
        });
    }

    /**
     * Helper: Get Breakdown Pembayaran
     */
    private function getBreakdownPembayaran($dari, $sampai)
    {
        $breakdown = Pendaftaran::select('jenis_pembayaran')
            ->selectRaw('COUNT(*) as jumlah')
            ->whereBetween('created_at', [$dari->startOfDay(), $sampai->endOfDay()])
            ->groupBy('jenis_pembayaran')
            ->get()
            ->pluck('jumlah', 'jenis_pembayaran');

        return $breakdown;
    }

    /**
     * Helper: Get Diagnosa Trend Period
     */
    private function getDiagnosaTrendPeriod($dari, $sampai)
    {
        $rekam = Rekam::whereBetween('tanggalperiksa', [$dari->startOfDay(), $sampai->endOfDay()])
            ->get()
            ->groupBy('diagnosa_primer')
            ->map(function ($group) {
                return $group->count();
            })
            ->sortDesc();

        return $rekam;
    }
}
