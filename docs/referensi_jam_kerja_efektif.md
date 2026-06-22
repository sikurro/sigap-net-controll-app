# Referensi Perhitungan Jam Kerja Efektif (FTE)

Dokumen ini menjelaskan asal mula dan dasar perhitungan angka **1800 jam/tahun** yang digunakan sebagai nilai *default* untuk parameter `effective_working_hours_per_year` pada fitur Global Settings aplikasi Sigap Net Controll.

## Latar Belakang

Angka **1800 jam/tahun** adalah standar *baseline* yang lazim digunakan di dunia industri dan pengelolaan Sumber Daya Manusia (HR) untuk menghitung *Full Time Equivalent* (FTE), yaitu estimasi total jam kerja produktif atau efektif seorang karyawan penuh waktu dalam kurun waktu satu tahun.

## Rincian Perhitungan Logis

Berikut adalah rincian estimasi standar dari mana angka 1800 jam tersebut didapatkan:

1. **Total Hari dalam Setahun:** 365 hari
2. **Dikurangi Akhir Pekan (Sabtu & Minggu):** ~104 hari libur
   - Sisa hari kerja normal: 365 - 104 = **261 hari**
3. **Dikurangi Cuti Tahunan & Libur Nasional:**
   - Asumsi cuti tahunan karyawan: ~12 hari
   - Asumsi libur nasional / cuti bersama: ~15 hari
   - Sisa hari kerja efektif: 261 - 27 = **234 hari**
4. **Dikali Jam Kerja Per Hari:**
   - Standar jam kerja per hari (di luar jam istirahat): 8 jam
   - Total jam kerja kotor: 234 hari × 8 jam = **1872 jam**
5. **Faktor Inefisiensi / *Allowance*:**
   - Dari 1872 jam tersebut, biasanya diberikan kelonggaran untuk mengakomodir waktu yang terbuang akibat:
     - Rapat rutin atau *briefing* pagi (*toolbox meeting*)
     - Izin sakit pendek atau urusan mendadak
     - Waktu transisi atau persiapan kerja (mengenakan APD, mengambil alat, dll)
   - Sebagai nilai yang konservatif dan aman untuk menghitung beban kerja teknisi lapangan, angka 1872 jam dibulatkan ke bawah menjadi **1800 jam efektif/tahun**.

## Pengaturan Ulang (Customization)

Nilai FTE ini secara praktis akan sangat bergantung pada kebijakan internal HRD, pola *shift* kerja, struktur organisasi, dan ketetapan hari libur di masing-masing perusahaan/pelabuhan.

Oleh sebab itu, nilai **1800** ini sengaja tidak dibuat kaku (*hardcoded*) di dalam rumus kalkulasi (*source code*). Nilai ini diimplementasikan sebagai variabel dinamis yang dapat diatur melalui menu **Global Settings**. 

Jika manajemen perusahaan memutuskan standar jam kerja efektif yang berbeda (misalnya: 1920 jam untuk pekerja operasional dengan *shift* penuh, atau 1750 jam untuk menyesuaikan kondisi lokal tertentu), *Super Administrator* dapat langsung memasukkan angka yang baru di layar *Global Settings*. Setelah disimpan, *Core Engine* kalkulasi aplikasi akan secara otomatis menghitung ulang seluruh kebutuhan tenaga teknisi di semua pelabuhan/site tanpa perlu campur tangan tim IT.
