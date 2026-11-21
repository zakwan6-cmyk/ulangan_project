# KlinikZakwan — Paket 1 (Tampilan Daftar Pasien)

Tampilan daftar pasien disesuaikan dengan contoh gambar: header KlinikZakwan, tombol Tambah Pasien, tabel foto/nama/username/usia/jenis kelamin/no. HP, serta tombol Detail, Edit, dan Hapus.

## Menjalankan di Laragon
1. Ekstrak folder `Paket1_KlinikZakwan` ke `C:\laragon\www\`.
2. Jalankan Apache dan MySQL melalui Laragon.
3. Buka `http://localhost/Paket1_KlinikZakwan/`.
4. Database `klinikzakwan_paket1` dan tabel `pasien` dibuat otomatis. File `database.sql` juga tersedia jika ingin import manual.

## Fitur
- Tambah pasien beserta foto (JPG/PNG/WEBP maksimal 2 MB).
- Menampilkan daftar pasien seperti contoh.
- Detail, edit, dan hapus pasien.
- Foto tersimpan di folder `uploads`.

Database memakai satu tabel `pasien` sesuai Paket 1. Jika sebelumnya sudah memakai database Paket 1, kolom tambahan akan dibuat otomatis tanpa menghapus data lama.
