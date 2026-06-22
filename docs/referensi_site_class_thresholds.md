# Referensi Pengaturan Batas Kelas Site (*Site Class Thresholds*)

Dokumen ini memberikan panduan dan penjelasan mengenai logika parameter `site_class_thresholds` pada fitur Global Settings di aplikasi Sigap Net Controll.

## Latar Belakang

Tidak semua pelabuhan atau lokasi (*Site*) memiliki tingkat kesibukan, jumlah alat, dan jam pemeliharaan yang sama. Oleh karena itu, aplikasi mengelompokkan setiap *Site* ke dalam "Kelas" tertentu (misalnya Kelas A, B, C, dan D).

Pengelompokan kelas ini sangat krusial karena akan menentukan:
1. Skala prioritas operasional.
2. Formasi **Staf Non-Teknis** (seperti Supervisor, Administrator, SPV Safety, dll) yang secara standar ditugaskan untuk kelas tersebut, berdasarkan perhitungan Matriks SDM.

## Format Data (JSON)

Karena jumlah kelas dan batas (*threshold*) nilainya bisa berbeda-beda tergantung kebijakan manajemen, data ini disimpan dalam format **JSON** (JavaScript Object Notation).

Contoh nilai *default* yang digunakan:
```json
[
  {"class": "A", "min_hours": 50000},
  {"class": "B", "min_hours": 20000},
  {"class": "C", "min_hours": 5000},
  {"class": "D", "min_hours": 0}
]
```

**Penjelasan Atribut:**
- `class`: Nama atau kode dari Kelas Site (contoh: "A", "Utama", "B", dll). Nama ini harus sesuai dengan nama kelas yang sudah didaftarkan pada tabel Master *Site Class*.
- `min_hours`: Angka batas bawah minimum dari Total Jam Pemeliharaan (*Total Maintenance Hours*) dalam setahun yang harus dicapai oleh sebuah Site untuk bisa dikategorikan ke dalam kelas tersebut.

## Cara Kerja (*Core Engine Logic*)

Mesin kalkulasi (*SdmCalculationEngine*) akan mengevaluasi setiap *Site* dengan langkah-langkah berikut:

1. **Hitung Total Jam:** Mesin menghitung keseluruhan jam pemeliharaan (*maintenance hours*) untuk semua alat di sebuah *Site* dalam periode 1 tahun.
2. **Urutkan Ambang Batas:** Sistem akan memastikan data JSON diurutkan dari nilai `min_hours` yang **paling besar** ke yang paling kecil.
3. **Pencocokan Berjenjang:** 
   - Sistem akan membandingkan Total Jam *Site* dengan `min_hours` paling atas (Kelas A).
   - Jika *Total Jam $\ge$ 50.000*, maka masuk **Kelas A**. Selesai.
   - Jika tidak memenuhi, sistem turun ke nilai berikutnya (Kelas B).
   - Jika *Total Jam $\ge$ 20.000*, maka masuk **Kelas B**. Selesai.
   - Begitu seterusnya hingga kelas terbawah (Kelas D dengan `min_hours` 0).

**Catatan Penting:** 
Selalu pastikan kelas terbawah memiliki `min_hours: 0` sebagai jaring pengaman (*fallback*) agar *Site* yang memiliki alat sangat sedikit atau nihil tetap memiliki status kelas (misal: Kelas D).

## Pengaturan Ulang (Customization)

Admin atau pihak *Management* dapat secara bebas meracik ulang format ini. 
Sebagai contoh, jika perusahaan sepakat bahwa syarat untuk menjadi Kelas A diturunkan menjadi $40.000$ jam saja, admin cukup mengganti angka $50000$ menjadi $40000$ pada kolom input di halaman Global Settings. 

Begitu disimpan, *Core Engine* akan secara massal menghitung ulang seluruh *Site*. *Site* yang sebelumnya berada di Kelas B namun memiliki total jam $42.000$, akan langsung naik tingkat (*upgrade*) secara otomatis menjadi Kelas A dan memicu perubahan standar kebutuhan Staf Non-Teknisnya!
