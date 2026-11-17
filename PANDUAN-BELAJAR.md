# Ruang CI

Situs pembelajaran CodeIgniter 3 berisi 12 modul, contoh, latihan, evaluasi mandiri, dan rujukan. Progres ditandai secara manual dan disimpan di `localStorage` browser; progres tidak tersinkronisasi antarperangkat dan dapat hilang jika data browser dihapus.

## Menjalankan secara lokal

Gunakan PHP yang masih didukung dan uji kompatibilitasnya dengan CI 3.1.13 dan ekstensi yang digunakan. Dari root repository:

```sh
php -S localhost:8000
```

Buka `http://localhost:8000`. Situs modul tidak memerlukan database atau instalasi Composer. Untuk deployment, gunakan server web yang sesuai dan atur `base_url` secara eksplisit di `application/config/config.php`; server bawaan PHP hanya untuk pengembangan.

URL situs tidak menampilkan `index.php`, misalnya `http://localhost:8000/modul/prasyarat`. Server bawaan PHP meneruskan URL yang bukan file ke `index.php` secara otomatis. File `index.php` tetap diperlukan sebagai front controller.

Pada Apache, aktifkan `mod_rewrite` dan izinkan `AllowOverride FileInfo` (atau `AllowOverride All`) agar `.htaccess` di root dapat digunakan. Pastikan akses ke folder `application` dan `system` tetap dibatasi. Jika menggunakan Nginx, `.htaccess` tidak berlaku: konfigurasikan `try_files` agar request selain file/direktori diteruskan ke `index.php` dengan query string, serta konfigurasikan PHP dan larangan akses ke folder internal sesuai server. Server bawaan PHP tidak membaca `.htaccess` dan tidak boleh digunakan sebagai server publik.

Saat memasang dalam subdirektori, sertakan subdirektori tersebut di `base_url` dan tujuan fallback Nginx. `base_url` dapat diatur di `application/config/config.php` atau melalui variabel lingkungan `CI_BASE_URL`, misalnya `http://localhost:8000/belajar-ci/`. Nilai ini harus sesuai alamat pemasangan dan berakhir dengan `/`; terutama saat menggunakan server bawaan PHP dalam subdirektori, jangan mengandalkan deteksi otomatis. Uji URL modul secara langsung dan setelah refresh, serta pastikan aset tetap bisa diakses.

CI3 adalah framework lama. Beberapa versi PHP terbaru dapat menampilkan peringatan deprecation dari kode framework bawaan; peringatan tersebut harus ditinjau sebelum aplikasi digunakan di lingkungan target. Jangan menggunakan versi PHP yang tidak lagi didukung sebagai solusi produksi.

Jika peringatan CI3 memenuhi halaman saat mencoba situs pada PHP terbaru, pratinjau lokal dapat dijalankan dalam mode testing:

```sh
CI_ENV=testing php -S localhost:8000
```

Mode testing menyembunyikan error dari halaman dan mengurangi pelaporan deprecation; ini **bukan** perbaikan kompatibilitas atau bukti kesiapan produksi. Gunakan mode development dan log untuk menyelidiki masalah.

## Mengikuti modul

Gunakan **salinan proyek CI3 yang terpisah** untuk latihan aplikasi artikel. Contoh kode dalam modul menjelaskan bagian tertentu dan instruksi untuk melengkapinya; kode tidak otomatis mengubah situs pembelajaran ini. Modul database dan seterusnya memerlukan database latihan. CRUD awal hanya untuk lingkungan lokal sampai keamanan dan otorisasi selesai.
