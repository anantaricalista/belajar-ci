# Ruang CI

Situs pembelajaran CodeIgniter 3 berisi 12 modul, contoh, latihan, evaluasi mandiri, dan rujukan. Progres ditandai secara manual dan disimpan di `localStorage` browser; progres tidak tersinkronisasi antarperangkat dan dapat hilang jika data browser dihapus.

## Menjalankan secara lokal

Gunakan PHP yang masih didukung dan uji kompatibilitasnya dengan CI 3.1.13 dan ekstensi yang digunakan. Dari root repository:

```sh
php -S localhost:8000
```

Buka `http://localhost:8000`. Situs modul tidak memerlukan database atau instalasi Composer. Untuk deployment, gunakan server web yang sesuai dan atur `base_url` secara eksplisit di `application/config/config.php`; server bawaan PHP hanya untuk pengembangan.

CI3 adalah framework lama. Beberapa versi PHP terbaru dapat menampilkan peringatan deprecation dari kode framework bawaan; peringatan tersebut harus ditinjau sebelum aplikasi digunakan di lingkungan target. Jangan menggunakan versi PHP yang tidak lagi didukung sebagai solusi produksi.

Jika peringatan CI3 memenuhi halaman saat mencoba situs pada PHP terbaru, pratinjau lokal dapat dijalankan dalam mode testing:

```sh
CI_ENV=testing php -S localhost:8000
```

Mode testing menyembunyikan error dari halaman dan mengurangi pelaporan deprecation; ini **bukan** perbaikan kompatibilitas atau bukti kesiapan produksi. Gunakan mode development dan log untuk menyelidiki masalah.

## Mengikuti modul

Gunakan **salinan proyek CI3 yang terpisah** untuk latihan aplikasi artikel. Contoh kode dalam modul menjelaskan bagian tertentu dan instruksi untuk melengkapinya; kode tidak otomatis mengubah situs pembelajaran ini. Modul database dan seterusnya memerlukan database latihan. CRUD awal hanya untuk lingkungan lokal sampai keamanan dan otorisasi selesai.
