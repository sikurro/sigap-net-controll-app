# PRODUCT BRIEF: Sistem Kalkulasi Kebutuhan SDM Pemeliharaan Alat Pelabuhan

## 1. Ringkasan Eksekutif
Aplikasi berbasis web ini dikembangkan untuk mengoptimalkan efektivitas waktu perbaikan dan pemeliharaan alat pelabuhan. Fokus pada **Fase 1** adalah membangun sistem kalkulasi otomatis untuk menghitung standar kebutuhan personel (Teknis dan Non-Teknis) berdasarkan data inventaris alat di masing-masing *site*, standar *Job Plan* pemeliharaan rutin (*preventive*), serta parameter target ketersediaan alat (*target availability*) untuk penanganan gangguan tidak terencana (*breakdown maintenance*).

## 2. Tujuan Produk
* **Fase 1:** Mengotomatisasi perhitungan kebutuhan SDM (teknisi dan staf pendukung/non-teknis) secara komprehensif berdasarkan beban kerja aktual (Preventive & Breakdown) dari inventaris alat di setiap terminal pelabuhan.
* **Fase 2 (Mendatang):** Mengidentifikasi *task* pada *Job Plan* yang memakan waktu lama (*non-effective time*) saat pemeliharaan berlangsung.

## 3. Target Pengguna (User Persona)
* **Planner / System Admin:** Mengelola data master (Job Plan, Inventaris Alat) dan konfigurasi Global Settings, memantau kebutuhan SDM dan kelayakan operasional terminal, serta menggunakan data *output* untuk alokasi dan rekrutmen personel.

## 4. Arsitektur & Tech Stack
Sesuai dengan kebutuhan efisiensi, reaktivitas, dan skalabilitas aplikasi, berikut adalah *tech stack* yang digunakan:
* **Backend:** Laravel (PHP Framework)
* **Frontend:** Vue.js
* **Routing/Bridge:** Inertia.js (Menghubungkan Laravel dan Vue tanpa perlu membuat REST API terpisah, sangat cepat untuk pengembangan *Single Page Application*).
* **Styling:** Tailwind CSS (Utility-first CSS untuk UI yang *clean* dan responsif).

**Rekomendasi Library Tambahan:**
* **Pinia / Vuex:** Untuk *State Management* di Vue.js, berguna saat mengelola *state* kalkulasi alat yang kompleks di sisi *client*.
* **Maatwebsite / Laravel Excel:** Sangat esensial untuk fitur *Import/Export* data *Job Plan*, Inventaris Alat, dan Laporan Kebutuhan Personil (mengingat data sampel berbasis Excel/Spreadsheet).
* **Spatie / Laravel Permission:** Untuk manajemen *Role* dan *Permission* (membedakan akses Site Manager, Planner, dll).
* **ApexCharts / Chart.js:** Untuk visualisasi *Dashboard* (grafik batang/komposisi SDM Teknis vs Non-Teknis).

## 5. Struktur Modul & Fitur (Berdasarkan Sampel Data & Perkembangan Sistem)

### A. Modul Master Data & Pengaturan Global
Modul fundamental untuk mengatur parameter dan acuan kalkulasi sistem.
* **Global Settings (Parameter Kalkulasi SDM):**
  * *Matriks Man Hours:* Pengaturan jam kerja produktif tahunan (*Effective Working Hours*) berdasarkan kalender formal dan kebocoran waktu harian untuk skema *Shift* dan *Non-Shift*.
  * *Target Availability Alat (%):* Konfigurasi persentase target ketersediaan alat (misal 85%) yang otomatis menentukan rasio alokasi jam penanganan gangguan (*Breakdown Allowance* = $100\% - \text{Target Avail}$).
* **Master Jenis Alat:** Data kategori alat beserta bobotnya (Contoh: *Container Crane, Forklift Diesel, Genset, Head Truck, Rubber Tyre Gantry / RTG*, dll).
* **Master Job Plan:** Input acuan waktu perawatan rutin (*preventive*).
  * *Data tersimpan:* Jenis Alat, Kegiatan, Durasi (Menit/Jam), Frekuensi/Tahun, dan Total Jam/Tahun.
* **Master Kelas Site:** Acuan kelas terminal (Utama, A, B, C, D, E).
* **Master Komposisi Personil Non-Teknis:** Matriks kebutuhan jabatan pendukung berdasarkan Kelas Site.

### B. Modul Inventaris & Pemetaan Site
* **Manajemen Site:** Input lokasi terminal (Contoh: Terminal Petikemas Banjarmasin, Pelabuhan Tenau Kupang).
* **Inventarisir Jumlah Alat:** Fitur *grid-input* atau *import file* untuk memasukkan kuantitas alat per *site*. Dilengkapi antarmuka interaktif *Searchable Dropdown* dengan fitur *revert on cancel*.

### C. Modul Kalkulasi (Engine & Simulasi)
* **Generate Cluster/Kelas Site:** Algoritma penentuan kelas *site* (Utama s/d E) berdasarkan total bobot alat lapangan.
* **Kalkulator Kebutuhan Teknisi (3 Pilar Utama):**
  1. *Pilar Baseline:* Standar minimum teknisi dari unit alat dengan kategori bobot tertinggi.
  2. *Pilar Preventive:* Jam pemeliharaan tambahan dari *Job Plan* dibagi jam kerja produktif.
  3. *Pilar Breakdown:* Alokasi penanganan gangguan mendadak yang dihitung dari formula: $\frac{\text{Jam Tersedia 1 Tahun} \times \text{Breakdown Rate} \times \text{Total Unit Alat}}{\text{Jam Produktif Shift}}$.
* **Kalkulator Kebutuhan Staf (Non-Teknis):** Membaca hasil *Cluster Site*, lalu merujuk ke matriks master untuk memunculkan daftar jabatan secara otomatis.
* **Simulasi & Bedah Formula:** Fitur simulasi interaktif yang dilengkapi *Real-Time Subtotal*, *Banner Grand Total* live (Jam Preventive & Jam Breakdown), serta panel transparansi pemecahan rumus hitung.

### D. Modul Output & Laporan
* **Dashboard Rekapitulasi:** Visualisasi rasio jumlah alat vs kebutuhan SDM per *site*.
* **Export Report:** Cetak hasil kalkulasi $\Sigma$ *technician* + $\Sigma$ *non technician* per *site* dalam format PDF atau Excel.

## 6. Fase Pengembangan (Milestones)
* **Sprint 1:** Setup Database, Auth, dan pembuatan CRUD Modul Master Data.
* **Sprint 2:** Pembuatan *interface* Modul Inventaris Site, integrasi *Import* Excel (`Maatwebsite`), dan Global Settings.
* **Sprint 3:** Pengembangan *Engine* Kalkulasi 3 Pilar Teknisi & Staf Non-Teknis beserta UI Simulasi interaktif.
* **Sprint 4:** Pembuatan Laporan, Dashboard Chart, dan UAT (User Acceptance Testing) Fase 1.
