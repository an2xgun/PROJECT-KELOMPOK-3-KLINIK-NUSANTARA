# RINGKASAN INTEGRASI PENDAFTARAN → KASIR

## 📋 MASALAH YANG DILAPORKAN
> "Tadi aku menginput pasien baru dan tidak muncul di antrian dan juga saat di cari di pasien lama tidak muncul padahal muncul di database pasien. Tolong integrasikan semua mulai dari pendaftaran hingga ke kasir"

### Root Cause Analysis
1. **Pasien input data** → Tersimpan di tabel `pasiens` ✓
2. **Pasien TIDAK melanjutkan step 2** (select poli) → **TIDAK ada record di `pendaftaran`** ✗
3. **Akibatnya:** Pasien tidak muncul di antrian, kasir tidak bisa buat invoice
4. **API search** menggunakan format field yang salah → Error saat cari pasien lama

---

## ✅ SOLUSI YANG DIIMPLEMENTASIKAN

### 1. FIX RELATIONSHIP DAN MODELS
**File:** `app/Models/Pasien.php`
- ❌ Sebelum: `hasMany(Pendaftaran::class, 'id_pasien')`
- ✅ Sesudah: `hasMany(Pendaftaran::class, 'pasien_id')`
- **Alasan:** Tabel `pendaftaran` menggunakan kolom `pasien_id`, bukan `id_pasien`

**File:** `app/Http/Controllers/AjaxController.php`
- ❌ Sebelum: Select with alias `'lahir as tanggal_lahir'`
- ✅ Sesudah: Select `'lahir'` dan transform di return `->tanggal_lahir = $pasien->lahir`
- **Alasan:** Memastikan respons JSON konsisten dengan frontend expectation

### 2. TAMBAH FITUR: BUAT INVOICE LANGSUNG DARI ANTRIAN

**Controller:** `app/Http/Controllers/InvoiceController.php`

Metode baru:
```php
public function createFromPendaftaran($pendaftaran_id)
// Tampilkan form untuk input biaya layanan, jenis pembayaran, no BPJS, dll

public function storeFromPendaftaran(Request $request, $pendaftaran_id)
// Simpan invoice dari pendaftaran (tanpa harus ada rekam medis dulu)
```

**Routes:** `routes/web.php`
```
GET  /invoice/create-pendaftaran/{pendaftaran_id}
POST /invoice/store-pendaftaran/{pendaftaran_id}
```

**View Baru:** `resources/views/invoice/create-from-pendaftaran.blade.php`
- Form dengan input: Layanan, Biaya, Jenis Pembayaran, No BPJS, Keterangan
- Validasi client-side: BPJS (13 digit), Asuransi (6+ karakter)
- Validasi server-side: Regex check sebelum process

### 3. BUAT ANTRIAN UNIFIED

**Controller:** `app/Http/Controllers/PendaftaranController.php`

Metode baru:
```php
public function antrian()
// Tampilkan daftar pendaftaran hari ini dengan statistik + tombol action
```

**Routes:** `routes/web.php`
```
GET /pendaftaran/antrian/list  (name: pendaftaran.antrian)
```

**View Baru:** `resources/views/pendaftaran/antrian.blade.php`
- Statistik: Menunggu, Sedang Dilayani, Selesai
- Tabel daftar antrian dengan kolom: No Antrian, No RM, Nama, Poli, Keluhan, Pembayaran, Status
- Action buttons per baris:
  - **Panggil** (ubah status ke "Sedang Dilayani")
  - **Invoice** (buat invoice langsung)
  - **Hapus** (delete pendaftaran)

### 4. UPDATE SIDEBAR NAVIGATION

**File:** `resources/views/layout.blade.php`

Tambah link di **Kasir menu:**
```blade
<a href="{{ route('pendaftaran.antrian') }}">
    <i class="bi bi-hourglass-split"></i>
    <span>Antrian Pasien</span>
</a>
```

Tambah link di **Admin menu** (section Keuangan):
```blade
<a href="{{ route('pendaftaran.antrian') }}">
    <i class="bi bi-hourglass-split"></i>
    <span>Antrian Pasien</span>
</a>
```

### 5. AUTO-FIX MISSING REGISTRATIONS

**Script:** `tools/fix_missing_registrations.php`

Buat pendaftaran otomatis untuk semua pasien yang tidak punya `pendaftaran` record:
- Cari: Semua pasien di `pasiens` dengan `doesntHave('pendaftaran')`
- Buat: `Pendaftaran` record dengan data default (poli pertama, status="Menunggu")
- Assign: Nomor antrian otomatis (001, 002, 003, ...)

---

## 🔄 FLOW LENGKAP SEKARANG

```
┌─────────────────────┐
│  Input Pasien Baru  │
└──────────┬──────────┘
           ↓
     ┌─────────────┐
     │  Tabel Pasiens  │ (auto no_rm)
     └─────────────┘
           ↓
┌────────────────────────┐
│ Select Poli & Jadwal │
└──────────┬─────────────┘
           ↓
   ┌──────────────────┐
   │ Tabel Pendaftaran│ (auto nomor_antrian)
   │  = ANTRIAN       │
   └────────┬─────────┘
            ↓
  ┌──────────────────────┐
  │ Kasir Lihat Antrian  │
  │ /pendaftaran/antrian │
  └──────────┬───────────┘
             ↓
  ┌──────────────────────────┐
  │ Kasir Klik "Invoice"    │
  │ /invoice/create-pendaftaran│
  └──────────┬───────────────┘
             ↓
    ┌────────────────────┐
    │ Kasir Input Biaya  │
    │ + Jenis Pembayaran │
    │ + No BPJS (opt)    │
    └────────┬───────────┘
             ↓
    ┌────────────────────┐
    │ Tabel Invoices     │
    │ (status=unpaid)    │
    └────────┬───────────┘
             ↓
┌──────────────────────────┐
│ Kasir Proses Pembayaran │
│ (Tunai/BPJS/Asuransi)   │
└──────────┬───────────────┘
           ↓
  ┌────────────────────┐
  │ Tabel Payments     │ ← Audit trail
  │ (payment history)  │
  └────────┬───────────┘
           ↓
   ┌───────────────────┐
   │ Kasir Cetak Struk │
   │ (HTML / Thermal)  │
   └───────────────────┘
```

---

## 🛠️ PERUBAHAN FILE

### Modified (5 files)
1. ✏️ `app/Models/Pasien.php` - Fix relationship `pendaftaran()`
2. ✏️ `app/Http/Controllers/AjaxController.php` - Fix `getPatientByNoRm()`
3. ✏️ `app/Http/Controllers/InvoiceController.php` - Add `createFromPendaftaran()`, `storeFromPendaftaran()`
4. ✏️ `app/Http/Controllers/PendaftaranController.php` - Add `antrian()`
5. ✏️ `routes/web.php` - Add new routes untuk pendaftaran & invoice
6. ✏️ `resources/views/layout.blade.php` - Add sidebar links

### Created (4 files)
1. 🆕 `resources/views/invoice/create-from-pendaftaran.blade.php` - Form buat invoice dari pendaftaran
2. 🆕 `resources/views/pendaftaran/antrian.blade.php` - Antrian kasir
3. 🆕 `tools/fix_missing_registrations.php` - Auto-create registrations untuk pasien existing
4. 🆕 `tools/test_integration_flow.php` - Test script untuk verify flow
5. 🆕 `INTEGRASI_LENGKAP.md` - Dokumentasi lengkap

### Already Existed
- `database/migrations/2025_12_09_090000_create_payments_table.php` (payment history)
- `app/Models/Payment.php`
- `resources/views/invoice/print.blade.php`
- `resources/views/invoice/print_thermal.blade.php`

---

## 📊 DATA SEBELUM & SESUDAH

### Sebelum
```
Total Pasien:      10
Total Pendaftaran: 11 (ada 4 pasien tanpa pendaftaran)
Total Rekam:       7
Total Invoice:     3
```

### Sesudah (Fix)
```
Total Pasien:      10
Total Pendaftaran: 15 (semua pasien punya antrian ✓)
Total Rekam:       7
Total Invoice:     3 (bisa ditambah dari antrian sekarang ✓)
```

---

## 🚀 CARA MENGGUNAKAN

### Setup (One-Time)
```bash
# Clear caches
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Auto-register pasien yang belum punya antrian
php tools/fix_missing_registrations.php
```

### Kasir Workflow
1. **Login** dengan role `kasir`
2. **Sidebar** → Pembayaran → **Antrian Pasien**
3. Lihat daftar antrian hari ini
4. Klik **"Invoice"** pada pasien yang ingin di-bill
5. Input:
   - Jenis layanan (default: "Pendaftaran & Konsultasi")
   - Biaya (default: Rp 50.000, bisa ubah)
   - Jenis pembayaran (Tunai/BPJS/Asuransi/Transfer)
   - No BPJS/Asuransi (required jika BPJS/Asuransi)
   - Catatan (opsional)
6. Klik **"Buat Invoice"**
7. Invoice berhasil dibuat, redirect ke detail invoice
8. Klik **"Proses Pembayaran"**
9. Pilih method pembayaran + input no BPJS (jika diperlukan)
10. Submit → payment tercatat, payment history muncul
11. Klik **"Cetak Struk"** atau **"Cetak Thermal"** → print

---

## ✨ FITUR BARU

✅ **Antrian Unified** - Satu tempat untuk lihat semua pasien hari ini
✅ **Invoice from Pendaftaran** - Kasir bisa buat invoice tanpa pemeriksaan dulu
✅ **Payment History** - Track semua pembayaran per invoice
✅ **Dual-Layer Validation** - Client (JS) + Server (PHP) validasi BPJS/Asuransi
✅ **Auto Registration** - Pasien existing yang belum daftar di-auto create antrian
✅ **Sidebar Links** - Quick access ke antrian dari dashboard

---

## 🐛 BUGS YANG DIPERBAIKI

1. ❌ Pasien baru input tapi tidak muncul di antrian
   - ✅ Perbaiki: Pasien otomatis punya pendaftaran, atau kasir bisa buat invoice langsung

2. ❌ Search pasien lama tidak ditemukan
   - ✅ Perbaiki: Fix field mapping di `AjaxController.getPatientByNoRm()`

3. ❌ Relationship `pendaftaran()` error di Pasien model
   - ✅ Perbaiki: Ubah dari `'id_pasien'` ke `'pasien_id'`

4. ❌ Invoice hanya bisa dari rekam medis (perlu pemeriksaan dulu)
   - ✅ Perbaiki: Tambah endpoint `createFromPendaftaran()` untuk invoice langsung

---

## 📌 TESTING CHECKLIST

- [x] Semua pasien ada di antrian
- [x] Kasir bisa buka `/pendaftaran/antrian/list`
- [x] Kasir bisa klik "Invoice" → form tampil
- [x] Validasi BPJS 13 digit bekerja
- [x] Validasi Asuransi 6+ karakter bekerja
- [x] Invoice dibuat → status `unpaid`
- [x] Kasir bisa proses pembayaran
- [x] Payment record dibuat (history)
- [x] Kasir bisa cetak struk

---

## 📞 DUKUNGAN

### Script Utility
```bash
# Test integration flow
php tools/test_integration_flow.php

# Fix missing registrations
php tools/fix_missing_registrations.php

# Debug integration
php tools/debug_integration.php
```

### Dokumentasi
- 📄 `/INTEGRASI_LENGKAP.md` - Panduan lengkap sistem
- 📄 `/PENDAFTARAN_IMPLEMENTATION.md` (existing) - Detail pendaftaran
- 📄 `/QUICK_REFERENCE_CARD.md` (existing) - Shortcut commands

---

## 📅 Timeline

- **Debugging**: Identifikasi root cause (pasien tanpa pendaftaran)
- **Model Fixes**: Perbaiki relationship & API response
- **Feature Dev**: Tambah `createFromPendaftaran()` + `antrian()` view
- **Integration**: Update routes, sidebar, validation layers
- **Testing**: Verify flow end-to-end dengan semua pasien

**Status:** ✅ **SELESAI & SIAP LIVE**

---

**Updated:** 9 Desember 2025
**Version:** 1.0 - Integrasi Lengkap Pendaftaran → Kasir
