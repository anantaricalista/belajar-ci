# Ruang CI

Situs pembelajaran CodeIgniter 3 berisi modul, contoh, latihan, evaluasi mandiri, dan rujukan. Proyek praktik memakai XAMPP, MySQL, data siswa SMK fiktif, jQuery, Bootstrap modal, dan DataTables server-side. Progres ditandai secara manual dan disimpan di `localStorage` browser; progres tidak tersinkronisasi antarperangkat dan dapat hilang jika data browser dihapus.

## Situs panduan dan proyek praktik XAMPP

Untuk situs panduan ini, gunakan PHP yang telah diuji dengan CI 3.1.13. Dari root repository, buka XAMPP Control Panel dan jalankan Apache. Jika repository disimpan di `C:\xampp\htdocs\belajar-ci`, buka `http://localhost/belajar-ci/`. Pastikan konfigurasi `base_url` sesuai lokasi instalasi.

```sh
CI_ENV=testing php -S localhost:8000
```

Perintah PHP built-in server di atas hanya alternatif pratinjau lokal jika CLI PHP tersedia; untuk pembelajaran sesuai kebutuhan kelas, gunakan Apache XAMPP. Situs panduan tidak memerlukan koneksi database.

Untuk praktik CRUD di modul akhir, gunakan **salinan CodeIgniter 3.1.13 terpisah** di `C:\xampp\htdocs\crud-siswa`. Jalankan Apache dan MySQL dari XAMPP Control Panel, buat database lewat `http://localhost/phpmyadmin/`, lalu ikuti modul terakhir. Instalasi MySQL XAMPP lokal sering menggunakan `root` dengan password kosong, tetapi sesuaikan dengan konfigurasi mesin. Gunakan hanya data siswa fiktif.

URL situs tidak menampilkan `index.php`, misalnya `http://localhost:8000/modul/prasyarat`. Server bawaan PHP meneruskan URL yang bukan file ke `index.php` secara otomatis. File `index.php` tetap diperlukan sebagai front controller.

Pada Apache XAMPP, aktifkan `mod_rewrite` dan izinkan `AllowOverride FileInfo` (atau `AllowOverride All`) agar `.htaccess` di root dapat digunakan. Pastikan akses ke folder `application` dan `system` tetap dibatasi. Jika menggunakan Nginx, `.htaccess` tidak berlaku: konfigurasikan `try_files` agar request selain file/direktori diteruskan ke `index.php` dengan query string, serta konfigurasikan PHP dan larangan akses ke folder internal sesuai server. Server bawaan PHP tidak membaca `.htaccess` dan tidak boleh digunakan sebagai server publik.

Saat memasang dalam subdirektori, sertakan subdirektori tersebut di `base_url` dan tujuan fallback Nginx. `base_url` dapat diatur di `application/config/config.php` atau melalui variabel lingkungan `CI_BASE_URL`, misalnya `http://localhost:8000/belajar-ci/`. Nilai ini harus sesuai alamat pemasangan dan berakhir dengan `/`; terutama saat menggunakan server bawaan PHP dalam subdirektori, jangan mengandalkan deteksi otomatis. Uji URL modul secara langsung dan setelah refresh, serta pastikan aset tetap bisa diakses.

Situs menggunakan mode `production` secara bawaan, termasuk ketika diunggah ke hosting DirectAdmin, sehingga error tidak ditampilkan kepada pengunjung. Untuk pengembangan lokal, atur variabel lingkungan `CI_ENV=development` pada server; jangan gunakan mode development di situs publik. Atur juga `CI_BASE_URL` atau `base_url` sesuai URL hosting, termasuk garis miring di akhir.

CI3 adalah framework lama. Beberapa versi PHP terbaru dapat menampilkan peringatan deprecation dari kode framework bawaan; peringatan tersebut harus ditinjau sebelum aplikasi digunakan di lingkungan target. Mode production menyembunyikan peringatan, bukan menjamin kompatibilitas PHP. Jika hosting menampilkan error 500, periksa Error Log domain di DirectAdmin untuk menemukan penyebabnya. Jangan menggunakan versi PHP yang tidak lagi didukung sebagai solusi produksi.

Jika peringatan CI3 memenuhi halaman saat mencoba situs pada PHP terbaru, pratinjau lokal dapat dijalankan dalam mode testing:

```sh
CI_ENV=testing php -S localhost:8000
```

Mode testing menyembunyikan error dari halaman dan mengurangi pelaporan deprecation; ini **bukan** perbaikan kompatibilitas atau bukti kesiapan produksi. Gunakan mode development dan log untuk menyelidiki masalah.

## Mengikuti modul

Modul terakhir **Urutan file & kode: CRUD data siswa** merangkum urutan database, konfigurasi, model, controller, view, dan JavaScript dalam satu halaman. CDN jQuery, Bootstrap, dan DataTables pada contoh memerlukan internet; untuk kelas offline, unduh aset vendor dan simpan secara lokal. CRUD siswa adalah latihan lokal tanpa autentikasi/otorisasi—jangan gunakan data pribadi atau publikasikan sebelum perlindungan yang dibutuhkan ditambahkan.
