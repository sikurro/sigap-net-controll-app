# PRODUCT BRIEF: Sistem Kalkulasi Kebutuhan SDM Pemeliharaan Alat Pelabuhan

## 1. Ringkasan Eksekutif
Aplikasi berbasis web ini dikembangkan untuk mengoptimalkan efektivitas waktu perbaikan dan pemeliharaan alat pelabuhan. Fokus pada **Fase 1** adalah membangun sistem kalkulasi otomatis untuk menghitung standar kebutuhan personel (Teknis dan Non-Teknis) berdasarkan data inventaris alat di masing-masing *site* dan standar *Job Plan* pemeliharaan.

## 2. Tujuan Produk
* **Fase 1:** Mengotomatisasi perhitungan kebutuhan SDM (teknisi dan staf pendukung/non-teknis) berdasarkan beban kerja aktual dari inventaris alat di setiap terminal pelabuhan.
* **Fase 2 (Mendatang):** Mengidentifikasi *task* pada *Job Plan* yang memakan waktu lama (*non-effective time*) saat pemeliharaan berlangsung.

## 3. Target Pengguna (User Persona)
* **Planner / System Admin:** Mengelola data master (Job Plan, Inventaris Alat) dan menganalisis laporan, Memantau kebutuhan SDM dan kelayakan operasional terminal, Menggunakan data *output* untuk alokasi dan rekrutmen personel.

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

## 5. Struktur Modul & Fitur (Berdasarkan Sampel Data)

### A. Modul Master Data
Modul fundamental untuk mengatur parameter acuan kalkulasi.
* **Master Jenis Alat:** Data kategori alat (Contoh: *Container Crane, Forklift Diesel, Genset, Head Truck, Rubber Tyre Gantry / RTG, Side Loader*, dll).
* **Master Job Plan:** Input acuan waktu perawatan.
  * *Data tersimpan:* Jenis Alat, Kegiatan (Contoh: *Daily Inspection, Service 250, Service 500, Change Tyre*), Durasi (Menit/Jam), Satuan/Kali, dan Total Jam/Tahun (Contoh acuan: RTG total 730 jam/tahun untuk *Daily Inspection*).
* **Master Kelas Site:** Acuan kelas terminal (Utama, A, B, C, D, E).
* **Master Komposisi Personil Non-Teknis:** Matriks kebutuhan jabatan berdasarkan Kelas Site.
  * *Data tersimpan:* Kategori (Fungsional: Site Manager, Leader, dll; Non-Fungsional: Safety Officer, Admin, Warehouse) dan jumlah orang per Kelas (Contoh: Site Utama butuh 41 orang, Kelas C butuh 12 orang).

### B. Modul Inventaris & Pemetaan Site
* **Manajemen Site:** Input lokasi terminal (Contoh: Terminal Petikemas Banjarmasin, Pelabuhan Tenau Kupang).
* **Inventarisir Jumlah Alat:** Fitur *grid-input* atau *import file* untuk memasukkan kuantitas alat per *site* (Contoh: TPK Banjarmasin memiliki 5 CC, 12 RTG, 25 Head Truck).

### C. Modul Kalkulasi (Engine)
* **Generate Cluster/Kelas Site:** Algoritma untuk menentukan *site* masuk ke kelas apa (Utama s/d E) berdasarkan parameter tertentu (bisa dari jumlah total alat atau bobot alat).
* **Kalkulator Kebutuhan Teknisi (Teknis):** * *Logika:* Menghitung total jam pemeliharaan setahun berdasarkan jumlah alat aktual dikali dengan standar *Job Plan* (Jam/Tahun), kemudian dibagi dengan standar jam kerja efektif teknisi per tahun.
* **Kalkulator Kebutuhan Staf (Non-Teknis):** * *Logika:* Membaca hasil *Cluster Site*, lalu merujuk ke 'Master Komposisi Personil Non-Teknis' untuk memunculkan daftar jabatan dan kuantitas orang yang dibutuhkan secara otomatis.

### D. Modul Output & Laporan
* **Dashboard Rekapitulasi:** Visualisasi rasio jumlah alat vs kebutuhan SDM per *site*.
* **Export Report:** Cetak hasil kalkulasi $\Sigma$ *technician* + $\Sigma$ *non technician* per *site* dalam format PDF atau Excel.

## 6. Fase Pengembangan (Milestones)
* **Sprint 1:** Setup Database, Auth, dan pembuatan CRUD Modul Master Data.
* **Sprint 2:** Pembuatan *interface* Modul Inventaris Site dan integrasi *Import* Excel (`Maatwebsite`).
* **Sprint 3:** Pengembangan *Engine* Kalkulasi (Logic Penentuan Kelas Site & Hitung SDM).
* **Sprint 4:** Pembuatan Laporan, Dashboard Chart, dan UAT (User Acceptance Testing) Fase 1.
