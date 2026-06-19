# ROADMAP PENGEMBANGAN: Sistem Kalkulasi Kebutuhan SDM Pemeliharaan Alat Pelabuhan (Berbasis Milestone)

## Milestone 1: Inisiasi Proyek & Fondasi Arsitektur
Fokus pada penyiapan infrastruktur dasar *software* dan pemastian keamanan akses sistem.
* **Target Capaian:** *Environment* pengembangan siap pakai dan sistem autentikasi dasar berjalan.
* **Item Pekerjaan:**
  * Inisialisasi *project* Laravel, Vue.js dengan Inertia.js, dan Tailwind CSS.
  * Perancangan struktur *database* awal (*schema design*).
  * Implementasi sistem manajemen pengguna, *role*, dan *permission* menggunakan `Laravel Permission`.

## Milestone 2: Digitalisasi Data Master (Parameter Acuan)
Fokus pada penyediaan antarmuka untuk memasukkan semua parameter baku pelabuhan yang menjadi dasar rumus kalkulasi.
* **Target Capaian:** Seluruh data regulasi, matriks jabatan, dan standar durasi *Job Plan* terdokumentasi di sistem.
* **Item Pekerjaan:**
  * CRUD Master Jenis Alat Pelabuhan (CC, RTG, RS, dll).
  * CRUD Master Job Plan (memasukkan detail kegiatan, durasi per menit/jam, dan frekuensi per tahun).
  * CRUD Master Kelas Site dan Matriks Standar Kebutuhan Personil Non-Teknis (Fungsional & Non-Fungsional).

## Milestone 3: Modul Operasional & Inventarisir Lapangan
Fokus pada pendataan kondisi riil dan jumlah alat yang beroperasi di masing-masing terminal pelabuhan.
* **Target Capaian:** Pengguna dapat memetakan aset alat berat per terminal secara fleksibel, baik input manual maupun massal.
* **Item Pekerjaan:**
  * Manajemen Profil Site/Terminal Pelabuhan (contoh: TPK Banjarmasin, Tenau Kupang).
  * Pembuatan modul Inventarisir Alat per *site*.
  * Integrasi *library* `Laravel Excel` untuk fitur *import* data inventaris dari *spreadsheet* agar mempercepat input operasional.

## Milestone 4: Pengembangan Core Engine Kalkulasi SDM
Fokus pada perakitan algoritma komputasi otomatis yang mempertemukan data inventaris lapangan dengan matriks master data.
* **Target Capaian:** Sistem mampu mengeluarkan angka rekomendasi jumlah kebutuhan orang (teknik & non-teknik) secara instan dan akurat.
* **Item Pekerjaan:**
  * Pembuatan logika *Auto-Generate Cluster Site* berdasarkan beban kerja atau jumlah alat.
  * Pengembangan kalkulator kebutuhan Teknisi (Total jam kerja per tahun dari *Job Plan* vs Jam kerja efektif teknisi).
  * Pengembangan kalkulator kebutuhan Staf Non-Teknis (pencocokan otomatis kelas *site* ke matriks SDM pendukung).

## Milestone 5: Dashboard Visualisasi & Sistem Pelaporan (Output)
Fokus pada penyajian data kalkulasi ke dalam bentuk yang mudah dipahami oleh pengambil keputusan dan manajemen puncak.
* **Target Capaian:** Ringkasan kebutuhan SDM dapat dianalisa secara visual dan diunduh untuk keperluan birokrasi kantor.
* **Item Pekerjaan:**
  * Pembuatan *Dashboard* rekapitulasi interaktif dengan komponen grafik batang/lingkaran (rasio kecukupan personel).
  * Fitur pencetakan laporan hasil kalkulasi ($\Sigma$ *technician* + $\Sigma$ *non technician*) ke dalam format PDF dan Excel.

## Milestone 6: UAT, Deployment & Peluncuran Fase 1
Fokus pada pengujian validitas performa aplikasi sebelum dilepas secara resmi ke lingkungan kerja operasional.
* **Target Capaian:** Aplikasi *go-live* tanpa kendala *bug* kalkulasi dan pengguna memahami cara pengoperasiannya.
* **Item Pekerjaan:**
  * *User Acceptance Testing* (UAT) bersama tim Planner dan Manajemen untuk validasi rumus hitung.
  * Proses pembersihan kutu (*bug fixing*) dan optimasi performa *loading* halaman.
  * *Deployment* ke *production server* dan pelaksanaan sesi *training* pengguna.

## Milestone 7: Evaluasi & Persiapan Fase 2 (Analisis Non-Effective Time)
Fokus pada pengembangan berkelanjutan setelah implementasi perhitungan SDM standar dinilai stabil.
* **Target Capaian:** Fondasi sistem siap untuk melacak efisiensi waktu pemeliharaan secara riil di lapangan.
* **Item Pekerjaan:**
  * Review performa aplikasi Fase 1 setelah beberapa bulan berjalan.
  * Perancangan modul pelacakan waktu *real-time* per *task* pada *Job Plan* saat eksekusi pemeliharaan di lapangan guna mendeteksi *bottleneck* (*non-effective time*).
