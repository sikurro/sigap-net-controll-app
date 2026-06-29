Berikut adalah dokumen **Pedoman Development Antigravity IDE** yang dilengkapi dengan langkah-langkah pencegahan *error* serta standardisasi tim.
# Pedoman Development: Antigravity IDE
### Prasyarat Sebelum Memulai (Pre-requisites)
Pastikan GitHub CLI (gh) sudah terinstal dan terautentikasi di perangkat Anda. Jika belum pernah melakukan login, jalankan perintah berikut sekali di terminal Anda:
```bash
gh auth login

```
### 0. Fase Perencanaan (Issue Planning)
*Semua pengembangan berawal dari diskusi dan perencanaan yang matang. Jangan melakukan implementasi kode pada tahap ini.*
 * **Identifikasi Masalah/Ide:** Tentukan ruang lingkup ide atau *bug* yang perlu diperbaiki.
 * **Buat Dokumen Perencanaan:** Buat file bernama issue.md di direktori proyek Anda.
 * **Isi Dokumen:** Tuliskan spesifikasi teknis dan tahapan-tahapan perbaikan (*task list*) secara terperinci. Dokumen ini akan menjadi panduan utama bagi *junior programmer* atau model AI dalam mengeksekusi tugas.
 * **Diskusi & Tunggu Instruksi:** Diskusikan poin perencanaan ini terlebih dahulu bersama tim. Jangan langsung melakukan eksekusi/coding sebelum mendapat instruksi atau persetujuan lanjutan.
### 1. Submit Issue ke GitHub
*Ubah dokumen perencanaan menjadi issue resmi di repositori untuk pelacakan.*
 * Submit isi dari issue.md ke GitHub sebagai Issue baru menggunakan GitHub CLI.
 * Jika pengiriman berhasil, segera hapus file issue.md dari direktori lokal agar tidak mengotori riwayat repositori.
 * **Perintah Terminal:**
   ```bash
   # Membuat issue baru dari file issue.md
   gh issue create --title "Jenis: Nama Fitur atau Masalah" --body-file issue.md
   
   # Menghapus file lokal setelah berhasil disubmit
   rm issue.md
   
   ```
### 2. Persiapan & Pembuatan Branch Baru
*Pastikan ruang kerja lokal Anda sinkron dengan kode terbaru untuk menghindari merge conflict.*
 * **Sinkronisasi Kode Utama:** Pindah ke branch main dan tarik pembaruan terbaru sebelum membuat cabang baru.
 * **Buat Branch Baru:** Buat cabang baru dengan format penamaan standar yang menyertakan nomor issue terkait (contoh format: feat/nomorissue-deskripsi atau fix/nomorissue-deskripsi).
 * **Perintah Terminal:**
   ```bash
   # Pindah ke branch utama dan ambil kode terbaru
   git checkout main
   git pull origin main
   
   # Membuat dan berpindah ke branch baru (contoh: feat/12-sistem-login)
   git checkout -b [tipe]/[nomor-issue]-[deskripsi-singkat]
   
   ```
### 3. Implementasi & Verifikasi Kode
*Lakukan pengerjaan fitur dan pastikan kualitas kode tetap terjaga sebelum disimpan.*
 * Mulai implementasi kode pada branch baru tersebut sesuai dengan rencana kerja yang telah disetujui.
 * **Penting:** Jangan langsung melakukan *commit*.
 * **Verifikasi Otomatis:** Sebelum menyimpan perubahan, jalankan proses pengujian (*testing*) atau pengecekan format (*linting*) yang berlaku di proyek untuk memastikan tidak ada kode rusak yang lolos.
   ```bash
   # Jalankan script testing/linting sesuai teknologi proyek Anda (contoh)
   npm run lint
   npm run test
   
   ```
### 4. Commit & Buat Pull Request (PR)
*Setelah aplikasi dipastikan berjalan sesuai kebutuhan teknis dan lolos verifikasi, ajukan penggabungan kode.*
 * Tambahkan seluruh perubahan, lakukan *commit* dengan pesan yang jelas, dan *push* branch ke repositori remote.
 * Gunakan kata kunci seperti Closes #NomorIssue pada pesan *commit* atau deskripsi PR agar issue di GitHub otomatis tertutup saat kode digabungkan.
 * **Perintah Terminal:**
   ```bash
   # Menambahkan seluruh perubahan kode
   git add .
   
   # Melakukan commit dengan pesan terstandardisasi
   git commit -m "feat/fix: deskripsi singkat perubahan (Closes #NomorIssue)"
   
   # Push branch lokal ke repositori remote
   git push -u origin nama-branch-Anda
   
   # Membuat Pull Request menggunakan GitHub CLI
   gh pr create --title "Menyelesaikan Fitur/Bug X" --body "PR ini menyelesaikan issue #NomorIssue"
   
   ```
### 5. Menunggu Peninjauan (Review & Merge)
*Fase peninjauan kode oleh tim senior/maintainer.*
 * Tunggu hingga PR direview, di-*approve*, dan di-*merge* ke branch utama oleh *maintainer* atau *senior developer*.
 * Jangan melakukan perubahan tambahan di branch tersebut kecuali diminta oleh *reviewer* melalui kolom komentar PR.
### 6. Sinkronisasi Akhir (Pull Perubahan Terbaru)
*Bersihkan ruang kerja lokal dan siapkan environment untuk tugas berikutnya.*
 * Setelah PR berhasil di-merge, kembali ke branch utama dan tarik perubahan terbaru agar repositori lokal Anda tetap mutakhir.
 * **Perintah Terminal:**
   ```bash
   # Pindah ke branch utama
   git checkout main
   
   # Menarik pembaruan terbaru yang sudah digabungkan di remote
   git pull origin main
   
   # (Opsional) Hapus branch fitur lokal yang sudah selesai dan di-merge
   git branch -d nama-branch-Anda
   
   ```
