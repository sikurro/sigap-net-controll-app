pedoman development

0. ceritakan issue dan buat issue plan dalam file issue.md
   Tuliskan spesifikasi teknis dan tahapan-tahapan perbaikan (_task list_) secara terperinci. Dokumen ini akan menjadi panduan utama bagi _junior programmer_ atau model AI dalam mengeksekusi tugas

1. submit issue dari file issue.md ke github lalu hapus file issue.md dari local
2. buat branch baru berdasarkan nomor issue + deskripsi singkat fitur atau perbaikan bug (format branch: feat/nomor_issue-deskripsi_singkat atau fix/nomor_issue-deskripsi_singkat)
3. implementasi fitur atau perbaikan bug
4. verifikasi sebelum commit
5. commit dengan pesan yang jelas (format commit: feat/fix: deskripsi singkat perubahan (closes #nomor_issue))
6. push branch baru ke github
7. buat pull request menggunakan github cli `gh pr create`, nanti saya bantu eksekusi via terminal
    - title: nama fitur atau perbaikan bug yang singkat dan jelas
    - body: deskripsi singkat fitur atau perbaikan bug
8. tunggu review dan konfirmasi
