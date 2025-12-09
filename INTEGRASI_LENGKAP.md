# PANDUAN INTEGRASI SISTEM PENDAFTARAN HINGGA KASIR

## 1. FLOW LENGKAP SISTEM

### A. ALUR PENDAFTARAN PASIEN
```
Pasien Baru / Lama
    ↓
Pendaftaran → Pilih Poliklinik/Jadwal → Nomor Antrian
    ↓
Tabel: pasiens, pendaftaran
```

**File Terkait:**
- Controller: `PendaftaranController`
- Routes: `/pendaftaran/*`
- Views: `/resources/views/pendaftaran/*`

### B. ALUR KASIR (PEMBAYARAN)
```
Antrian Pasien (Pendaftaran)
    ↓
Lihat → Buat Invoice → Proses Pembayaran → Cetak Struk
    ↓
Tabel: pendaftaran, invoices, payments
```

**File Terkait:**
- Controller: `InvoiceController`
- Routes: `/invoice/*`
- Views: `/resources/views/invoice/*`

---

## 2. ENDPOINTS DAN ROUTES

### Pendaftaran Routes

| Method | Route | Name | Deskripsi |
|--------|-------|------|-----------|
| GET | `/pendaftaran` | `pendaftaran.index` | Daftar pendaftaran |
| GET | `/pendaftaran/choice` | `pendaftaran.choice` | Pilih pasien baru/lama |
| GET | `/pendaftaran/create-new` | `pendaftaran.create-new-patient` | Form input pasien baru |
| POST | `/pendaftaran/store-new` | `pendaftaran.store-new-patient` | Simpan pasien baru |
| GET | `/pendaftaran/select-poli/{pasien}` | `pendaftaran.select-poli` | Pilih poli & jadwal |
| POST | `/pendaftaran/store-poli/{pasien}` | `pendaftaran.store-poli` | Simpan pendaftaran (buat antrian) |
| GET | `/pendaftaran/antrian/list` | `pendaftaran.antrian` | **BARU: Lihat antrian pasien** |
| POST | `/pendaftaran/{id}/serve` | `pendaftaran.serve` | Panggil pasien (ubah status) |
| DELETE | `/pendaftaran/{id}` | `pendaftaran.destroy` | Hapus pendaftaran |

### Invoice Routes

| Method | Route | Name | Deskripsi |
|--------|-------|------|-----------|
| GET | `/invoice` | `invoice.index` | Daftar invoice |
| GET | `/invoice/{id}` | `invoice.show` | Detail invoice |
| GET | `/invoice/create/{rekam_id}` | `invoice.create` | Buat invoice dari rekam medis |
| POST | `/invoice/store/{rekam_id}` | `invoice.store` | Simpan invoice (dari rekam) |
| GET | `/invoice/create-pendaftaran/{pendaftaran_id}` | `invoice.create-pendaftaran` | **BARU: Buat invoice dari pendaftaran** |
| POST | `/invoice/store-pendaftaran/{pendaftaran_id}` | `invoice.store-pendaftaran` | **BARU: Simpan invoice dari pendaftaran** |
| PUT | `/invoice/{id}/paid` | `invoice.markAsPaid` | Proses pembayaran + buat payment record |
| GET | `/invoice/{id}/print` | `invoice.print` | Cetak struk (HTML) |
| GET | `/invoice/{id}/print-thermal` | `invoice.printThermal` | Cetak thermal 58mm |
| DELETE | `/invoice/{id}` | `invoice.destroy` | Hapus invoice |

### API Routes

| Method | Route | Deskripsi |
|--------|-------|-----------|
| GET | `/api/pasien/suggest-no-rm?q=...` | Saran No RM saat ketik |
| GET | `/api/patient/{no_rm}` | Cari pasien by No RM |
| GET | `/api/jadwal-by-poli/{poliId}` | Jadwal dokter by poli |

---

## 3. DATABASE SCHEMA

### Tabel: `pendaftaran`
```
id                  - bigint (PK)
pasien_id           - bigint (FK -> pasiens)
poliklinik_id       - bigint (FK -> polikliniks)
jadwal_poli_id      - int (FK -> jadwal_polis)
nomor_antrian       - varchar (misal: "001", "002")
keluhan             - text
jenis_pembayaran    - varchar (Umum, BPJS, Asuransi)
no_bpjs             - varchar (nomor polis, opsional)
tanggal_kunjungan   - date
status_layanan      - varchar (default: "Menunggu", pilihan: Sedang Dilayani, Selesai)
created_at          - timestamp
updated_at          - timestamp
```

### Tabel: `invoices`
```
id                  - bigint (PK)
rekam_id            - bigint (FK -> rekams, nullable)
pasien_id           - bigint (FK -> pasiens)
layanan             - varchar
jenis_pembayaran    - varchar
no_bpjs             - varchar
keterangan_pembayaran - varchar
subtotal            - decimal(12,2)
total               - decimal(12,2)
status              - varchar (default: "unpaid", pilihan: paid, paid_by_bpjs, paid_by_asuransi)
paid_at             - timestamp (nullable)
created_at          - timestamp
updated_at          - timestamp
```

### Tabel: `payments` (BARU)
```
id                  - bigint (PK)
invoice_id          - bigint (FK -> invoices, cascade delete)
user_id             - bigint (FK -> users)
method              - varchar (Tunai, BPJS, Asuransi, Transfer)
no_bpjs             - varchar (nullable)
note                - text (nullable)
amount              - decimal(14,2)
paid_at             - timestamp
created_at          - timestamp
updated_at          - timestamp
```

---

## 4. MODELS & RELATIONSHIPS

### Pasien Model
```php
public function pendaftaran() -> hasMany(Pendaftaran)
public function invoices() -> hasMany(Invoice)
```

### Pendaftaran Model
```php
public function pasien() -> belongsTo(Pasien)
public function poliklinik() -> belongsTo(Poliklinik)
public function jadwalPoli() -> belongsTo(JadwalPoli)
public function rekam() -> hasMany(Rekam)
```

### Invoice Model
```php
public function pasien() -> belongsTo(Pasien)
public function rekam() -> belongsTo(Rekam)
public function items() -> hasMany(InvoiceItem)
public function payments() -> hasMany(Payment)
```

### Payment Model (BARU)
```php
public function invoice() -> belongsTo(Invoice)
public function user() -> belongsTo(User)
```

---

## 5. STEP-BY-STEP: ALUR KASIR LENGKAP

### Step 1: Login Kasir
- Login dengan role `kasir`
- Sidebar akan menampilkan menu "Pembayaran" dengan sub-menu:
  - "Antrian Pasien" → `/pendaftaran/antrian/list`
  - "Daftar Invoice" → `/invoice`

### Step 2: Lihat Antrian
```
GET /pendaftaran/antrian/list
```
- Tampil daftar semua pendaftaran hari ini
- Statistik: Menunggu, Sedang Dilayani, Selesai
- Setiap pasien ada tombol "Invoice"

### Step 3: Buat Invoice dari Antrian
```
GET /invoice/create-pendaftaran/{pendaftaran_id}
```
- Form untuk input:
  - Nama Layanan (default: "Pendaftaran & Konsultasi")
  - Biaya Layanan (default: Rp 50.000)
  - Jenis Pembayaran (Tunai, BPJS, Asuransi, Transfer)
  - No BPJS / Asuransi (conditional, required if BPJS/Asuransi)
  - Keterangan Pembayaran (opsional)
  
- Validasi Client-Side (JavaScript):
  - BPJS: 13 digit angka (`/^\d{13}$/`)
  - Asuransi: min 6 karakter (`/^[a-zA-Z0-9]{6,}$/`)

### Step 4: Simpan Invoice
```
POST /invoice/store-pendaftaran/{pendaftaran_id}
```
- Validasi Server-Side (Laravel):
  - Check format BPJS & Asuransi
  - Return error dengan Indonesian message jika invalid
  
- Buat record di tabel `invoices`
- Buat line item di tabel `invoice_items`
- Redirect ke `/invoice/{id}` (detail invoice)

### Step 5: Lihat Detail Invoice & Proses Pembayaran
```
GET /invoice/{id}
```
- Tampil:
  - Info pasien
  - Detail layanan & amount
  - Form pembayaran (jika status unpaid)
  - History pembayaran (jika sudah paid)
  - Tombol cetak struk

### Step 6: Proses Pembayaran
```
PUT /invoice/{id}/paid
```
- Form dengan field:
  - Payment Method (Tunai, BPJS, Asuransi, Transfer)
  - No BPJS / Asuransi (conditional)
  - Catatan Pembayaran
  
- Validasi:
  - Check format no BPJS/Asuransi
  - Return error jika invalid
  
- Proses:
  - Update invoice status (`paid` atau `paid_by_bpjs` atau `paid_by_asuransi`)
  - Update `paid_at` timestamp
  - **Buat record di tabel `payments`** (audit trail)
  - Save `no_bpjs` dan `keterangan_pembayaran` ke invoice

### Step 7: Lihat History Pembayaran
- Di invoice show page, tampil tabel payment history:
  - Metode pembayaran
  - No BPJS (jika ada)
  - Catatan
  - Jumlah
  - Tanggal pembayaran

### Step 8: Cetak Struk
Dua pilihan cetak:
1. **Cetak Struk (HTML)** → `/invoice/{id}/print`
   - Format standar A4
   - Auto-trigger `window.print()`
   
2. **Cetak Thermal (58mm)** → `/invoice/{id}/print-thermal`
   - Format thermal printer 58mm width
   - Auto-trigger `window.print()`

---

## 6. VALIDASI & ERROR HANDLING

### Client-Side (JavaScript)
**File:** `/resources/views/invoice/create-from-pendaftaran.blade.php`

```javascript
function validatePaymentNumber() {
    const method = document.getElementById('jenisPembayaran').value;
    const value = document.querySelector('input[name="no_bpjs"]').value;
    
    // BPJS: 13 digit
    if (method === 'BPJS' && !/^\d{13}$/.test(value)) {
        return false; // Error: "Nomor BPJS harus 13 digit angka"
    }
    
    // Asuransi: min 6 karakter
    if (method === 'Asuransi' && !/^[a-zA-Z0-9]{6,}$/.test(value)) {
        return false; // Error: "Nomor Asuransi minimal 6 karakter"
    }
    
    return true;
}
```

### Server-Side (Laravel)
**File:** `/app/Http/Controllers/InvoiceController.php`

```php
// storeFromPendaftaran() & markAsPaid()
if (!empty($validated['no_bpjs'])) {
    $method = $validated['jenis_pembayaran'] ?? '';
    if ($method === 'BPJS' && !preg_match('/^\d{13}$/', $validated['no_bpjs'])) {
        return back()->withErrors(['no_bpjs' => 'Nomor BPJS harus tepat 13 digit angka'])->withInput();
    }
    if ($method === 'Asuransi' && !preg_match('/^[a-zA-Z0-9]{6,}$/', $validated['no_bpjs'])) {
        return back()->withErrors(['no_bpjs' => 'Nomor Asuransi minimal 6 karakter (huruf/angka)'])->withInput();
    }
}
```

---

## 7. TROUBLESHOOTING

### Masalah: Pasien baru tidak muncul di antrian

**Penyebab:**
- Pasien sudah input data & create account
- TAPI pasien tidak melanjutkan ke step 2 (select poli)
- Sehingga tidak ada record di tabel `pendaftaran`

**Solusi:**
- Kasir dapat langsung buat invoice dari form `/invoice/create-pendaftaran/{pasien_id}` TANPA harus ada `pendaftaran` record
- Atau admin/kasir dapat help pasien melanjutkan step 2 (select poli)

### Masalah: Pasien lama tidak ditemukan di search

**Penyebab:**
- Search menggunakan `/api/patient/{no_rm}` (exact match)
- Pastikan input No RM benar (case-sensitive)

**Solusi:**
- Gunakan autocomplete suggestion (ketik No RM, akan muncul opsi)
- Jika pasien belum ada, buat pasien baru

### Masalah: Invoice tidak bisa dibuat dari pendaftaran

**Penyebab:**
- Route mungkin tidak terdaftar
- Cache Laravel tidak di-clear

**Solusi:**
```bash
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

---

## 8. TESTING CHECKLIST

- [ ] Pasien baru: input data → select poli → lihat di antrian
- [ ] Kasir: buka `/pendaftaran/antrian/list` → lihat daftar antrian hari ini
- [ ] Kasir: klik "Invoice" → form buat invoice tampil
- [ ] Kasir: input biaya + jenis pembayaran + no BPJS → validasi client-side bekerja
- [ ] Kasir: submit → invoice dibuat, redirect ke detail invoice
- [ ] Kasir: klik "Proses Pembayaran" → form pembayaran tampil
- [ ] Kasir: input payment method + no BPJS → validasi bekerja
- [ ] Kasir: submit → payment tercatat, payment history tampil
- [ ] Kasir: klik "Cetak Struk" → browser print dialog buka
- [ ] Kasir: klik "Cetak Thermal" → format 58mm tampil

---

## 9. FILE YANG BERUBAH/BARU

### Modified Files
- `app/Http/Controllers/InvoiceController.php` - added `createFromPendaftaran()`, `storeFromPendaftaran()`
- `app/Http/Controllers/PendaftaranController.php` - added `antrian()`
- `app/Http/Controllers/AjaxController.php` - fixed `getPatientByNoRm()`
- `routes/web.php` - added new invoice routes + antrian route
- `resources/views/layout.blade.php` - added antrian link di sidebar kasir & admin

### New Files
- `resources/views/invoice/create-from-pendaftaran.blade.php` - form buat invoice dari pendaftaran
- `resources/views/pendaftaran/antrian.blade.php` - daftar antrian + statistics

### Already Existed
- `database/migrations/2025_12_09_090000_create_payments_table.php` - payment history
- `app/Models/Payment.php` - payment model
- `resources/views/invoice/print.blade.php` - struk HTML
- `resources/views/invoice/print_thermal.blade.php` - struk thermal 58mm

---

## 10. QUICK START COMMAND

```bash
# Clear all caches
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run test integration flow
php tools/test_integration_flow.php
```

---

**Terakhir Update:** 9 Desember 2025
**Status:** ✓ Integrasi Lengkap - Pendaftaran → Antrian → Kasir
