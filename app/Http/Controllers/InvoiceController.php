<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Rekam;
use App\Models\Pasien;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $r)
    {
        $query = Invoice::with('pasien', 'rekam')->orderBy('id', 'DESC');

        if ($r->status) {
            $query->where('status', $r->status);
        }

        $invoices = $query->paginate(15);
        return view('invoice.index', compact('invoices'));
    }

    public function show($id)
    {
        $invoice = Invoice::with('items', 'pasien', 'rekam')->findOrFail($id);
        return view('invoice.show', compact('invoice'));
    }

    public function create($rekam_id)
    {
        $rekam = Rekam::with('pasien', 'prescription.items.obat')->findOrFail($rekam_id);
        
        return view('invoice.create', compact('rekam'));
    }

    public function store(Request $request, $rekam_id)
    {
        $validated = $request->validate([
            'layanan' => 'nullable|string',
            'jenis_pembayaran' => 'nullable|string',
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.type' => 'required|string',
            'items.*.amount' => 'required|numeric'
        ]);

        $rekam = Rekam::findOrFail($rekam_id);
        
        // Filter only items that were included (checkbox "include" present)
        $toBill = [];
        foreach ($validated['items'] as $item) {
            // some items from the form may include an 'include' flag when checked
            if (array_key_exists('include', $item) && $item['include']) {
                $toBill[] = $item;
            }
        }

        if (count($toBill) === 0) {
            return back()->with('error', 'Tidak ada item yang dipilih untuk ditagihkan');
        }

        $subtotal = 0;
        foreach ($toBill as $item) {
            $subtotal += $item['amount'];
        }

        $invoice = Invoice::create([
            'rekam_id' => $rekam_id,
            'pasien_id' => $rekam->id_pasien,
            'layanan' => $validated['layanan'] ?? null,
            'jenis_pembayaran' => $validated['jenis_pembayaran'] ?? null,
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'status' => 'unpaid'
        ]);

        foreach ($toBill as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'name' => $item['name'],
                'type' => $item['type'],
                'amount' => $item['amount']
            ]);
        }

        return redirect()->route('invoice.show', $invoice->id)->with('success', 'Invoice dibuat');
    }

    public function markAsPaid($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update([
            'status' => 'paid',
            'paid_at' => now()
        ]);

        return back()->with('success', 'Invoice ditandai sebagai dibayar');
    }

    public function destroy($id)
    {
        Invoice::findOrFail($id)->delete();
        return back()->with('success', 'Invoice dihapus');
    }
}
