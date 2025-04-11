# TODO Aplikasi Playlist Nonton Film

## Persiapan Proyek

- [x] Membuat struktur folder proyek
- [x] Membuat database `db_playlist` (ada di mysql)
- [x] Membuat tabel `film` dengan kolom yang dibutuhkan
- [x] Membuat file koneksi database (`koneksi.php`)

## Implementasi Fitur CRUD

### Create (Tambah Film)

- [x] Membuat halaman form input (`create.php`)
- [x] Implementasi validasi untuk setiap field input
- [x] Implementasi proses penyimpanan data ke database

### Read (Lihat Playlist)

- [x] Membuat halaman utama untuk menampilkan daftar film (`index.php`)
- [x] Implementasi fitur pencarian film (OPSIONAL)
- [ ] Implementasi fitur filter berdasarkan genre, rating, atau status nonton (OPSIONAL)

### Update (Edit Film)

- [x] Membuat halaman form edit (`update.php`)
- [x] Implementasi pengambilan data film yang akan diedit
- [x] Implementasi proses update data di database
- [x] Memastikan nilai default pada form sesuai dengan data yang ada

### Delete (Hapus Film)

- [x] Membuat fungsionalitas hapus film (`delete.php`)
- [x] Implementasi konfirmasi penghapusan data
- [x] Implementasi proses penghapusan dari database

## Keamanan dan Validasi

- [x] Implementasi validasi untuk Film ID (unik/auto-increment)
- [x] Implementasi validasi untuk judul film (tidak boleh kosong)
- [x] Implementasi validasi untuk tahun rilis (harus angka)
- [x] Implementasi validasi untuk select option dan radio button
- [x] Implementasi prepared statements untuk mencegah SQL injection (OPSIONAL)

## Desain dan User Interface

- [x] Membuat stylesheet CSS untuk mempercantik tampilan
- [x] Implementasi responsive design (OPSIONAL)
- [x] Memastikan UX yang baik pada form dan tabel

## Pengujian

- [x] Uji fungsionalitas Create
- [x] Uji fungsionalitas Read
- [x] Uji fungsionalitas Update
- [x] Uji fungsionalitas Delete
- [x] Uji validasi input
- [x] Uji keamanan dasar

## Finalisasi

- [x] Review dan refactoring kode
- [x] Verifikasi seluruh fitur berfungsi dengan baik
