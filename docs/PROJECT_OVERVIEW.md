# PROJECT OVERVIEW
## Sistem Pendukung Keputusan (SPK) Penerimaan Santri Baru Menggunakan Metode Fuzzy Associative Memory (FAM) Berbasis Web pada Pesantren Ell Firdaus Labusel

### 1. Deskripsi Sistem
Aplikasi ini dikembangkan untuk mendukung panitia seleksi dan pimpinan Pesantren Ell Firdaus Labusel dalam menentukan kelayakan penerimaan calon santri baru jenjang Madrasah Aliyah (MA). Penilaian didasarkan pada 4 kriteria utama:
1. **Nilai Akademik / Rapor**
2. **Tes Baca Tulis Al-Qur'an (BTQ)**
3. **Tes Hafalan Al-Qur'an / Tahfidz**
4. **Tes Wawancara**

Metode yang digunakan adalah **Fuzzy Associative Memory (FAM)**, yaitu jaringan saraf tiruan fuzzy yang memetakan himpunan fuzzy input ke himpunan fuzzy output menggunakan matriks asosiasi fuzzy.

---

### 2. Arsitektur Teknis
- **Framework Backend**: Laravel 11.57.0 (PHP 8.3.16)
- **Database**: MySQL (`fuzzy` database)
- **Autentikasi**: Laravel Breeze (Livewire / Volt stack)
- **Frontend**: Blade Components, Tailwind CSS, Alpine.js, Vite
- **Testing**: Pest PHP 3.7 & PHPUnit 11
- **Pola Desain**:
  - Service Layer untuk pemisahan logika komputasi FAM (`App\Services\FAM\*`).
  - Form Requests untuk validasi input.
  - Policies & Middleware untuk kontrol hak akses peran (Role-Based Access Control).
  - Database Transactions untuk menjaga integritas data saat eksekusi kalkulasi batch.

---

### 3. Peran Pengguna (RBAC)
- **Admin / Panitia**:
  - Mengelola data calon santri dan input nilai seleksi.
  - Mengelola periode seleksi.
  - Mengatur fungsi keanggotaan dan aturan FAM.
  - Menjalankan proses komputasi FAM.
  - Melihat hasil seleksi dan mencetak laporan.
- **Pimpinan**:
  - Melihat dashboard ringkasan eksekutif.
  - Melihat data calon santri, nilai, dan peringkat hasil seleksi.
  - Melihat detail penelusuran komputasi FAM.
  - Mencetak laporan keputusan penerimaan santri.
  - *Read-only*: Tidak memiliki akses mengubah data nilai atau konfigurasi metode.

---

### 4. Status Keputusan Metodologis FAM (*Pending Academic Confirmation*)
Beberapa rincian rumus dan parameter matematis menunggu konfirmasi dari proposal skripsi / pembimbing agar tidak terjadi asumsi sepihak:
1. **Rentang Nilai Mentah**: Standar rentang tiap kriteria (skala 0–100 atau khusus).
2. **Fungsi Keanggotaan**: Titik parameter kurva ($a, b, c, d$) untuk Rendah, Sedang, Tinggi.
3. **Himpunan Output**: Label kategori keluaran (misal: "TIDAK DITERIMA", "DITERIMA") beserta representasi nilainya.
4. **Metode Encoding**: Correlation-Minimum vs Correlation-Product.
5. **Metode Komposisi**: Max-Min vs Max-Product.
6. **Metode Defuzzifikasi**: Centroid vs Weighted Average vs Winner-Take-All.
7. **Passing Grade**: Nilai ambang batas akhir penentu kelulusan.

*Catatan: Sistem didesain fleksibel dan parameter disimpan di database/antarmuka sehingga dapat disesuaikan tanpa perlu merombak struktur kode program.*
