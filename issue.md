# [Feat]: Revamp UI/UX & Identitas Visual Bernuansa Pelindo (#58)

## Status
DONE

## Deskripsi
Melakukan penyesuaian antarmuka pengguna (UI) dan pengalaman pengguna (UX) aplikasi **SIGAP NET CONTROLL APP** secara menyeluruh agar selaras dengan identitas visual resmi korporasi **PT Pelabuhan Indonesia (Persero) / Pelindo**. Perbaikan ini meliputi penggantian logo standar bawaan Laravel, pembaruan palet warna sistem menjadi *Pelindo Corporate Blue* & *Turquoise*, serta perombakan tata letak halaman utama (Welcome Landing Page, Login, Header, dan Sidebar Navigation) agar tampil berkelas (*premium*), modern ala *Command Center*, dan menumbuhkan kebanggaan korporasi (*corporate pride*).

## Spesifikasi Teknis & Palet Warna Resmi
1. **Primary Color (*Pelindo Corporate Blue*)**: `#00549A` (atau kelas Tailwind `pelindo-blue`). Digunakan untuk latar belakang navigasi, header utama, dan tombol aksi utama (*Primary CTA*).
2. **Secondary Accent Color (*Pelindo Turquoise / Ocean Cyan*)**: `#00A3E0` (atau kelas Tailwind `pelindo-cyan`). Digunakan untuk sorotan menu aktif (*active glow*), *border focus*, indikator grafik visual, dan elemen interaktif.
3. **Warning & Highlight Color (*Industrial Equipment Gold/Orange*)**: `#F97316` / `#F59E0B`. Digunakan untuk *badge* peringatan (*Under-staffed*), penanda jam cadangan *breakdown*, dan tombol sekunder.
4. **Neutral Background**: Slate bersih (`#F8FAFC`) untuk latar konten dasbor agar kontras dan mudah dibaca.

## Tugas Kerja (Tasklist Terperinci)

### 1. Kustomisasi Desain Token & Konfigurasi Tailwind (`tailwind.config.js`)
- [x] Daftarkan palet warna khusus Pelindo (`pelindo-blue`: `#00549A`, `pelindo-cyan`: `#00A3E0`, `pelindo-orange`: `#F97316`) ke dalam `tailwind.config.js` agar dapat dipanggil dengan rapi di seluruh komponen Vue.

### 2. Transformasi Halaman Landing Page (`resources/js/Pages/Welcome.vue`)
- [x] Hapus tampilan *default* bawaan Laravel Breeze/Jetstream.
- [x] Rancang tata letak *Split-Screen* modern berunsur maritim pelabuhan:
  - [x] **Sisi Visual**: Menampilkan latar belakang bertema pelabuhan/kontainer dengan *overlay gradient* dari `pelindo-blue` ke `pelindo-cyan`. Tambahkan *headline* megah: *"Executive SDM Command Center — Mengoptimalkan Kinerja & Keandalan Alat Pelabuhan Nusantara"*.
  - [x] **Sisi Aksi**: Panel masuk (*Call to Action*) yang bersih dan elegan menuju halaman Login atau Dashboard.

### 3. Transformasi Halaman Autentikasi (`resources/js/Pages/Auth/Login.vue`)
- [x] Ganti logo standar Laravel merah dengan logo teks/ikonik resmi **SIGAP - Pelindo**.
- [x] Desain ulang kotak form login bergaya *Modern Card / Glassmorphism* yang rapi.
- [x] Sesuaikan warna input *focus ring* menjadi `pelindo-cyan` dan tombol *Submit* menjadi `pelindo-blue` dengan efek *hover* yang halus.

### 4. Penyesuaian Layout Utama & Navigasi (`resources/js/Layouts/AuthenticatedLayout.vue`)
- [x] **Sidebar / Header Branding**: Ganti logo di pojok kiri atas dengan branding resmi SIGAP bernuansa Pelindo.
- [x] **Menu Navigation**: Sesuaikan warna latar navigasi menjadi nuansa `pelindo-blue` atau slate elegan.
- [x] **Active & Hover State**: Berikan sorotan *border* atau *glow* berwarna `pelindo-cyan` pada menu navigasi yang sedang aktif (*active tab*) agar intuitif.

### 5. Kompilasi Aset & Verifikasi Pengujian
- [x] Jalankan kompilasi aset frontend dengan `npm run build` dan pastikan tidak ada kesalahan *build*.
- [x] Jalankan pengujian otomatis (`php artisan test`) untuk memastikan perombakan UI/UX tidak merusak logika *routing* maupun fungsionalitas autentikasi aplikasi.
