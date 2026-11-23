<?php
defined('BASEPATH') OR exit('No direct script access allowed');

return array(
	'jquery-datatables' => array(
		'category' => 'MEMBANGUN FITUR',
		'title' => 'jQuery & DataTables server-side',
		'duration' => 5,
		'summary' => 'Gunakan jQuery, modal Bootstrap, dan DataTables server-side untuk mengelola daftar siswa.',
		'goals' => array(
			'Memahami selector, event, dan AJAX jQuery.',
			'Membuat form tambah/edit dalam modal dan mengirimnya melalui POST.',
			'Mengirim pagination, pencarian, dan sorting DataTables ke server.',
			'Mengamankan sorting dengan allowlist kolom serta mempertahankan CSRF.'
		),
		'sections' => array(
			array(
				'title' => 'Persiapan library dan hubungan MVC',
				'paragraphs' => array('Lanjutkan proyek XAMPP setelah modul form dan database. Pastikan tabel siswa dan data fiktif sudah dibuat. Pahami HTML form dan tabel, JavaScript dasar, HTTP GET/POST serta route, controller, model, view dan helper URL CI3.', 'Potongan pada modul ini menjelaskan konsep, bukan aplikasi yang dapat langsung dijalankan: selector memerlukan elemen view yang sesuai, URL memerlukan route dan controller, dan DataTables server-side memerlukan endpoint JSON serta model. Rakit semua file lengkap pada akhir modul ini sebelum mengerjakan latihan.', 'Di view CI3, letakkan dua link CSS di dalam head: Bootstrap dahulu, lalu integrasi DataTables Bootstrap 5. Menjelang penutup body, setelah elemen halaman, muat jQuery 3.7.1, Bootstrap 5.3.3 bundle, DataTables 1.13.8 core, integrasi Bootstrap 5 DataTables, dan terakhir JavaScript aplikasi, seperti contoh berikut.', 'CDN membutuhkan internet. Untuk offline, ikuti bagian pilihan offline sebelum kode lengkap di bawah. Jangan memuat salinan CDN dan lokal sekaligus.'),
				'file' => 'View CI3: head dan akhir body (fragmen)',
				'code' => <<<'CODE'
<!-- Di dalam <head>, Bootstrap sebelum integrasi DataTables -->
<!-- Bootstrap CSS menyediakan gaya dasar; CSS integrasi DataTables menyesuaikan tabel dengan
     Bootstrap 5. -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<!-- Di akhir <body>, setelah tabel dan modal -->
<!-- jQuery harus dimuat sebelum DataTables core; integrasi DataTables dimuat setelah core. -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap bundle menyediakan JavaScript modal beserta Popper; Bootstrap 5 tidak
     bergantung pada jQuery. -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<!-- load->view memuat application/views/siswa/js.php setelah semua library; file itu berisi
     tag script dan kode khusus halaman. -->
<!-- Subview dirender sebagai bagian HTML oleh CI3, bukan diakses langsung sebagai URL.
     Fragmen ini tidak menggantikan markup tabel dan modal. -->
<?php $this->load->view('siswa/js'); ?>
CODE

			),
			array(
				'title' => 'jQuery menghubungkan aksi pengguna dengan halaman',
				'paragraphs' => array('jQuery memudahkan pemilihan elemen dan penanganan event. Bungkus kode dalam ready handler agar elemen HTML sudah tersedia. Event delegation berguna untuk tombol yang dibuat ulang setiap kali DataTables menggambar ulang tabel.', 'Fragmen ini mengandaikan view memiliki #btn-tambah, #form-siswa, #siswaModal, dan #tabel-siswa. Ia hanya mencatat ID edit, belum memuat data atau menyimpan perubahan; implementasi lengkap ada pada proyek akhir.'),
				'file' => 'Contoh jQuery',
				'code' => <<<'CODE'
// $(function () { ... }) menunggu DOM siap sebelum selector dijalankan.
$(function () {
    const modal = bootstrap.Modal.getOrCreateInstance(
        document.getElementById('siswaModal')
    );
    // Selector #btn-tambah memilih tombol berdasarkan id; on(click, ...) mendaftarkan aksi
    // klik.
    $('#btn-tambah').on('click', function () {
        // reset mengosongkan input form agar modal siap untuk mode tambah.
        $('#form-siswa')[0].reset();
        modal.show();
    });

    // Event delegation memasang listener pada tabel yang tetap ada; tombol .btn-edit boleh
    // dibuat ulang oleh DataTables.
    $('#tabel-siswa').on('click', '.btn-edit', function () {
        // data(id) membaca ID dari atribut data-id tombol; console.log hanya alat belajar,
        // ganti dengan pemuatan data/edit.
        const id = $(this).data('id');
        console.log('Edit siswa:', id);
    });
});
CODE
),
			array(
				'title' => 'Modal dan AJAX untuk one-page CRUD',
				'paragraphs' => array('One-page CRUD berarti daftar dan form tambah/edit tampil pada satu halaman. Modal cocok untuk data siswa yang hanya memiliki empat field. Form dikirim ke controller dengan AJAX POST; server tetap wajib memvalidasi setiap nilai. Hapus juga harus POST, bukan GET.', 'Fragmen berikut mengandaikan simpanUrl menunjuk route controller, perbaruiToken adalah fungsi pembaru hidden input, dan tabel adalah instance DataTables; ketiganya harus disediakan aplikasi. Proyek akhir merakit padanannya bersama view, route, controller, dan model.', 'Proyek akhir menggunakan Bootstrap 5 untuk modal, jQuery untuk AJAX, dan token CSRF CI3 di dalam form. Ambil token berikutnya dari response JSON controller setelah POST berhasil maupun gagal validasi, karena CI3 dapat meregenerasi token.'),
				'file' => 'Pola pengiriman form dengan jQuery',
				'code' => <<<'CODE'
$('#form-siswa').on('submit', function (event) {
    // event.preventDefault mencegah browser memuat halaman baru saat form dikirim.
    event.preventDefault();
    // ajax mengirim request ke endpoint CI3; type POST sesuai operasi tulis.
    $.ajax({
        url: simpanUrl,
        type: 'POST',
        // serialize mengirim setiap input bernama, termasuk hidden ID dan token CSRF.
        data: $(this).serialize(),
        // dataType json memastikan response diurai sebagai JSON.
        dataType: 'json'
    // done dijalankan pada response sukses; token diperbarui dan DataTables memuat ulang
    // data tanpa kembali ke halaman pertama. Tambahkan penanganan fail untuk status validasi
    // 422 dan error server.
    }).done(function (response) {
        perbaruiToken(response.csrf);
        tabel.ajax.reload(null, false);
    });
});
CODE
),
			array(
				'title' => 'Bedakan DataTables client-side dan server-side',
				'paragraphs' => array('Pada client-side, browser mengunduh semua baris lalu mencari dan mengurutkannya. Pada server-side, DataTables mengirim draw, start, length, search, dan order ke endpoint setiap kali pengguna mencari atau berpindah halaman. Server mengembalikan recordsTotal, recordsFiltered, dan data untuk halaman itu saja.', 'Untuk dataset siswa yang terus bertambah, server-side mengurangi data yang dikirim per request. Controller memeriksa parameter dan memanggil model untuk menghitung total sebelum dan setelah pencarian serta mengambil halaman; ukuran halaman dan kolom sorting harus dibatasi.', 'Fragmen mengandaikan tabel lima kolom di view dan dataUrl menuju route endpoint JSON controller. Kolom aksi belum menghasilkan tombol: render tombol dan handler lengkap tersedia pada proyek akhir.'),
				'file' => 'Konfigurasi DataTables server-side',
				'code' => <<<'CODE'
const tabel = $('#tabel-siswa').DataTable({
    // processing menampilkan indikator saat server sedang memproses request.
    processing: true,
    // serverSide mengaktifkan protokol pagination, pencarian, dan sorting sisi server.
    serverSide: true,
    // GET hanya membaca data sehingga tidak mengubah state dan tidak membutuhkan token CSRF.
    ajax: { url: dataUrl, type: 'GET' },
    columns: [
        // Empat kolom data dipetakan ke field JSON yang dikirim server.
        { data: 'nis' },
        { data: 'nama' },
        { data: 'kelas' },
        { data: 'jenis_kelamin' },
        // Kolom kelima tidak dapat diurutkan atau dicari; fragmen ini belum merender tombol
        // aksi.
        { data: null, orderable: false, searchable: false }
    ]
});
CODE
),
			array(
				'title' => 'Server mengolah request secara aman',
				'paragraphs' => array('Jangan memakai nama kolom dari request langsung di order_by. Nomor indeks sorting harus dipetakan ke allowlist field pada kode server dan arah dibatasi ke ASC atau DESC. start dan length juga berasal dari browser: konversikan ke integer, batasi offset non-negatif, dan beri batas maksimum panjang halaman.', 'Model menjalankan query total, query count setelah filter, lalu query untuk halaman yang diminta. Semua nilai pencarian diproses lewat Query Builder; pengurutan memakai nama kolom tetap dari allowlist.'),
				'file' => 'Bentuk response server-side',
				'code' => <<<'CODE'
array(
    // draw dikembalikan sebagai integer agar request yang lebih lama tidak mengganti hasil
    // terbaru.
    'draw' => (int) $draw,
    // recordsTotal adalah jumlah baris sebelum filter pencarian.
    'recordsTotal' => $jumlah_semua,
    // recordsFiltered adalah jumlah baris setelah filter pencarian, bukan banyak baris di
    // halaman ini.
    'recordsFiltered' => $jumlah_cocok,
    // data hanya memuat baris untuk offset dan ukuran halaman yang diminta.
    'data' => $baris_halaman
);
CODE
),
			array(
				'title' => 'CSRF dan batas pemakaian',
				'paragraphs' => array('Aktifkan csrf_protection di config CI3. Token disertakan pada form tambah/edit dan request POST hapus. Ketika token diregenerasi, perbarui hidden input memakai token baru yang disertakan server. GET DataTables hanya membaca data. Pesan error server jangan membocorkan SQL atau kredensial.', 'Contoh CRUD di tutorial adalah latihan lokal dan belum memiliki login/otorisasi. Gunakan hanya data fiktif. Sebelum digunakan dengan data sekolah, tambahkan autentikasi dan pembatasan akses yang sesuai.')
			)
		),
		'exercise' => array(
			'title' => 'Ukur request DataTables',
			'intro' => 'Setelah merakit kode lengkap pada akhir modul ini, amati perbedaan kerja browser dan server.',
			'steps' => array('Aktifkan serverSide dan processing pada tabel siswa.', 'Gunakan Network tab browser untuk melihat parameter start, length, search, dan order.', 'Cari nama siswa, ubah urutan, pindah halaman, dan periksa struktur JSON response.', 'Uji request dengan length besar serta order column di luar rentang.'),
			'criteria' => 'Browser hanya menerima baris untuk halaman aktif, total hitung konsisten, ukuran halaman dibatasi server, dan input sorting tidak dapat menentukan identifier SQL.'
		),
		'review' => array(
			array('question' => 'Apa beda recordsTotal dan recordsFiltered?', 'answer' => 'recordsTotal adalah jumlah seluruh baris sebelum pencarian; recordsFiltered adalah jumlah yang cocok setelah pencarian.'),
			array('question' => 'Mengapa type GET cocok untuk endpoint DataTables yang hanya membaca?', 'answer' => 'GET digunakan untuk membaca dan tidak mengubah data; operasi tulis tetap memakai POST serta CSRF.'),
			array('question' => 'Mengapa nama kolom sorting harus di-allowlist?', 'answer' => 'Nama kolom adalah bagian identifier SQL dan tidak dapat diamankan hanya dengan binding nilai. Pilih nama kolom tetap dari daftar tepercaya.')
		),
		'resources' => array(
			array('title' => 'jQuery API', 'url' => 'https://api.jquery.com/'),
			array('title' => 'DataTables server-side processing', 'url' => 'https://datatables.net/manual/server-side'),
			array('title' => 'Bootstrap modal', 'url' => 'https://getbootstrap.com/docs/5.3/components/modal/')
		)
	),
	'proyek-akhir-crud-siswa' => array(
		'category' => 'PROYEK AKHIR',
		'title' => 'Urutan file & kode: CRUD data siswa',
		'duration' => 8,
		'summary' => 'Ikuti satu halaman dari database MySQL XAMPP sampai CRUD siswa dengan modal jQuery dan DataTables server-side.',
		'goals' => array(
			'Menyusun file proyek CI3 secara berurutan dari konfigurasi sampai view dan JavaScript.',
			'Mengimplementasikan CRUD siswa fiktif dengan validasi dan query terparameterisasi.',
			'Menggunakan modal Bootstrap, jQuery AJAX, CSRF, dan DataTables server-side.',
			'Memahami penjelasan setiap kelompok kode dan menguji alur lengkap di XAMPP.'
		),
		'sections' => array(
			array(
				'title' => 'Sebelum mulai: nyalakan XAMPP',
				'paragraphs' => array('Prasyarat: pahami HTML form dan tabel, JavaScript dasar, HTTP, serta alur route, controller, model, view, dan helper URL CI3. Pelajari modul jQuery & DataTables untuk konsep dan urutan library; fragmen di sana bukan aplikasi lengkap.', 'Di XAMPP Control Panel, jalankan Apache dan MySQL. Ekstrak salinan CodeIgniter 3.1.13 ke C:\\xampp\\htdocs\\crud-siswa. Buka http://localhost/phpmyadmin/ untuk database dan http://localhost/crud-siswa/ untuk aplikasi. Pastikan PHP pada instalasi XAMPP sesuai dengan versi yang sudah diuji terhadap CI3.', 'Kode di halaman ini merakit aplikasi latihan satu halaman. Buat setiap file pada salinan proyek CI3, bukan di repository panduan. View menampilkan antarmuka; JavaScript memanggil route endpoint controller, yang memvalidasi request dan memakai model untuk database. Isi database hanya dengan data fiktif; proyek contoh ini belum memiliki login dan tidak boleh digunakan untuk data pribadi siswa.')
			),
			array(
				'title' => '1. Siapkan database dan tabel siswa',
				'paragraphs' => array('Di phpMyAdmin, periksa dahulu apakah db_siswa dan tabel siswa dari modul Database & model sudah ada. Jika keduanya ada dengan struktur seperti di bawah, gunakan data fiktif yang sudah dibuat: jangan jalankan ulang CREATE DATABASE, CREATE TABLE, atau INSERT contoh karena NIS harus unik.', 'Untuk instalasi kosong tanpa db_siswa, pilih tab SQL dan jalankan seluruh skrip sekali. Jika database ada tetapi tabel siswa belum ada, pilih db_siswa lalu jalankan hanya CREATE TABLE dan, bila diperlukan, INSERT contoh sekali. Tiga record contoh bukan data orang sungguhan.', 'Jika tabel yang sudah ada berbeda strukturnya, jangan hapus atau ubah data sembarangan. Gunakan database latihan kosong dengan nama lain, sesuaikan nama database pada skrip dan konfigurasi tahap 3, lalu jalankan skrip sekali. Jangan gunakan DROP atau mengosongkan tabel untuk memulai proyek.'),
				'file' => 'phpMyAdmin → SQL',
				'code' => <<<'CODE'
-- CREATE DATABASE hanya diperlukan jika database latihan belum ada; utf8mb4 mendukung
-- beragam karakter.
CREATE DATABASE db_siswa
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
-- USE memilih database untuk perintah berikutnya; pilih database yang sesuai bila memakai
-- nama lain.
USE db_siswa;

-- CREATE TABLE hanya diperlukan jika tabel siswa belum ada. id adalah primary key otomatis;
-- NIS wajib dan unik.
CREATE TABLE siswa (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nis VARCHAR(20) NOT NULL,
    -- nama dan kelas dibatasi panjangnya agar sesuai validasi form dan ukuran kolom.
    nama VARCHAR(100) NOT NULL,
    kelas VARCHAR(30) NOT NULL,
    -- ENUM menjaga jenis_kelamin hanya berisi L atau P; sesuaikan dengan aturan data
    -- latihan.
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_siswa_nis (nis)
);

-- INSERT menambahkan record fiktif untuk pengujian pada tabel baru; jangan ulangi jika
-- record contoh sudah ada.
INSERT INTO siswa (nis, nama, kelas, jenis_kelamin) VALUES
('RPL001', 'Alya Contoh', 'X RPL 1', 'P'),
('RPL002', 'Bima Contoh', 'X RPL 1', 'L'),
('RPL003', 'Citra Contoh', 'XI RPL 2', 'P');
CODE

			),
			array(
				'title' => '2. Konfigurasikan URL dan perlindungan CSRF',
				'paragraphs' => array('Pada application/config/config.php, ganti nama folder pada base_url bila proyek tidak berada di htdocs/crud-siswa. Biarkan file index.php tetap ada, tetapi kosongkan index_page agar tautan yang dihasilkan helper CI3 tidak mencantumkannya. Pastikan aturan .htaccess tersedia di root seperti pada tahap berikutnya.', 'CSRF aktif untuk request yang mengubah data. Token dari hidden input dikirim saat AJAX POST. Contoh memakai regenerasi token bawaan, lalu memperbarui nilai hidden input dari setiap response JSON.'),
				'file' => 'application/config/config.php (baris konfigurasi)',
				'code' => <<<'CODE'
// base_url wajib cocok dengan URL folder aplikasi di htdocs dan diakhiri garis miring.
$config['base_url'] = 'http://localhost/crud-siswa/';
// index_page kosong menghilangkan index.php dari URL yang dihasilkan; front controller tetap
// ada.
$config['index_page'] = '';
// csrf_protection mengharuskan token pada POST seperti simpan dan hapus.
$config['csrf_protection'] = TRUE;
// csrf_regenerate meminta token baru setelah request; JavaScript proyek akan memperbarui
// hidden token dari response server.
$config['csrf_regenerate'] = TRUE;
CODE

			),
			array(
				'title' => '3. Hubungkan CodeIgniter dengan MySQL',
				'paragraphs' => array('Edit nilai koneksi pada application/config/database.php yang sudah disediakan CI3. Konfigurasi berikut menggunakan nilai standar untuk lingkungan XAMPP lokal. Jika root MySQL milikmu memiliki password, isi nilai lokal yang benar. Jangan menggunakan konfigurasi development ini sebagai kredensial server publik.'),
				'file' => 'application/config/database.php → $db[default]',
				'code' => <<<'CODE'
// active_group memilih konfigurasi koneksi default dan query_builder mengaktifkan Query
// Builder CI3.
$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn'      => '',
    // hostname, username, password, dan database harus cocok dengan layanan MySQL XAMPP.
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'db_siswa',
    // mysqli menghubungkan CI3 melalui ekstensi MySQLi PHP; aktifkan ekstensi tersebut jika
    // driver tidak ditemukan.
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    // db_debug membantu menemukan kesalahan lokal, tetapi detail error tidak boleh dikirim
    // ke pengguna produksi.
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    // utf8mb4 menjaga teks beragam karakter, termasuk nama dan tanda baca.
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
CODE

			),
			array(
				'title' => '4. Buat dan konfigurasikan .htaccess',
				'paragraphs' => array('Buat file bernama tepat .htaccess di root proyek, sejajar dengan index.php dan folder application; pastikan bukan .htaccess.txt. Jika file sudah ada, pertahankan konfigurasi lain yang masih diperlukan. Salin aturan berikut untuk meneruskan URL bersih ke index.php tanpa mengganggu file dan folder yang ada.', 'Di XAMPP, aktifkan mod_rewrite dan izinkan AllowOverride FileInfo atau All untuk folder htdocs, lalu restart Apache. Modul Routing & controller menjelaskan langkah pemeriksaan dan penanganan error secara lebih rinci.'),
				'file' => 'C:\\xampp\\htdocs\\crud-siswa\\.htaccess',
				'code' => <<<'CODE'
# IfModule menjalankan aturan hanya saat mod_rewrite tersedia.
<IfModule mod_rewrite.c>
    # RewriteEngine mengaktifkan penulisan ulang URL Apache.
    RewriteEngine On
    # Kondisi !-f dan !-d melewatkan file serta direktori yang sudah ada.
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    # RewriteRule meneruskan request lainnya ke index.php tanpa menampilkannya pada URL; [L]
    # menghentikan aturan rewrite berikutnya pada putaran itu.
    RewriteRule ^ index.php [L]
# </IfModule> menutup kelompok aturan.
</IfModule>
CODE

			),
			array(
				'title' => '4.1. Atur route URL siswa',
				'paragraphs' => array('Pada application/config/routes.php, ganti route siswa yang sudah ada dengan daftar berikut dan ganti nilai default_controller yang lama; jangan membuat definisi duplikat.', 'Hapus route tahap awal siswa/(:num) yang mengarah ke siswa/detail/$1 karena controller proyek pada tahap 6 tidak lagi memiliki method detail. Proyek menggunakan satu halaman dan endpoint JSON terpisah untuk DataTables, simpan, dan hapus. Jika kemudian menambahkan pola ID baru, letakkan route endpoint sebelum pola yang lebih umum.'),
				'file' => 'application/config/routes.php (ganti default route, tambahkan endpoint)',
				'code' => <<<'CODE'
// GET /siswa memuat halaman utama.
$route['siswa'] = 'siswa/index';
// GET /siswa/data mengirim baris dan hitungan server-side DataTables.
$route['siswa/data'] = 'siswa/data';
// POST /siswa/simpan menjalankan insert atau update berdasarkan ID.
$route['siswa/simpan'] = 'siswa/simpan';
// POST /siswa/hapus menghapus satu record setelah pemeriksaan CSRF.
$route['siswa/hapus'] = 'siswa/hapus';
// default_controller membuka controller Siswa ketika path root diakses; pastikan tidak ada
// route default duplikat yang tertinggal.
$route['default_controller'] = 'siswa'; // ganti nilai default_controller yang lama
CODE

			),
			array(
				'title' => '5. Buat model dan query server-side',
				'paragraphs' => array('Buat application/models/Siswa_model.php. Model menghitung total baris, total hasil setelah pencarian, dan mengambil satu halaman data. Urutan kolom ditentukan oleh array tepercaya di server, bukan oleh nama kolom dari request browser. Semua kondisi pencarian dibangun dengan Query Builder.'),
				'file' => 'application/models/Siswa_model.php',
				'code' => <<<'CODE'
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa_model extends CI_Model
{
    // table menyimpan nama tabel sebagai properti class agar query memakai sumber tabel yang
    // sama.
    private $table = 'siswa';

    // filter_keyword memakai grup OR: keyword dicocokkan terhadap NIS, nama, atau kelas.
    // Query Builder meng-escape nilai pencarian.
    private function filter_keyword($keyword)
    {
        if ($keyword === '')
        {
            return;
        }

        $this->db->group_start()
            ->like('nis', $keyword)
            ->or_like('nama', $keyword)
            ->or_like('kelas', $keyword)
            ->group_end();
    }

    public function datatables($start, $length, $keyword, $column, $direction)
    {
        // count_all menghitung seluruh siswa; count_all_results menghitung hasil setelah
        // filter.
        $total = $this->db->count_all($this->table);

        $this->db->from($this->table);
        $this->filter_keyword($keyword);
        $filtered = $this->db->count_all_results();

        $this->db->from($this->table);
        $this->filter_keyword($keyword);
        // columns adalah allowlist: indeks sorting dari browser hanya dapat memilih empat
        // kolom ini.
        $columns = array('nis', 'nama', 'kelas', 'jenis_kelamin');
        $order_by = isset($columns[$column]) ? $columns[$column] : 'nama';
        // direction dibatasi ke ASC atau DESC. limit membatasi jumlah record dan offset
        // untuk halaman aktif.
        $direction = ($direction === 'DESC') ? 'DESC' : 'ASC';

        $rows = $this->db->order_by($order_by, $direction)
            ->limit($length, $start)
            ->get()->result_array();

        return array($total, $filtered, $rows);
    }

    // find mengambil satu siswa berdasarkan ID untuk mengisi modal edit.
    public function find($id)
    {
        return $this->db->get_where($this->table, array('id' => $id))
            ->row_array();
    }

    // nis_exists mengecualikan ID saat ini ketika memeriksa perubahan NIS.
    public function nis_exists($nis, $except_id = 0)
    {
        $this->db->where('nis', $nis);
        if ($except_id > 0)
        {
            $this->db->where('id !=', $except_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    // save melakukan INSERT untuk siswa baru atau UPDATE untuk ID yang ada; hanya field yang
    // diberikan controller ditulis.
    public function save($data, $id = 0)
    {
        if ($id > 0)
        {
            return $this->db->where('id', $id)
                ->update($this->table, $data);
        }
        return $this->db->insert($this->table, $data);
    }

    // delete membatasi penghapusan ke ID tertentu. Controller harus memeriksa request POST
    // dan keberadaan baris sebelum memanggilnya.
    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }
}
CODE

			),
			array(
				'title' => '6. Buat controller halaman, JSON, validasi, dan CRUD',
				'paragraphs' => array('Buat application/controllers/Siswa.php. Controller memeriksa HTTP method, mengambil parameter yang dibutuhkan DataTables, membatasi ukuran halaman, memvalidasi semua field, dan mengirim JSON. Urutan kolom DataTables ditentukan server; jangan meneruskan input browser langsung ke SQL.', 'Contoh ini belum menerapkan login. Sebelum aplikasi dipakai oleh sekolah sungguhan, tambahkan autentikasi serta otorisasi pada index, data, simpan, dan hapus, bukan hanya menyembunyikan tombol.')
				,'file' => 'application/controllers/Siswa.php',
				'code' => <<<'CODE'
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa extends CI_Controller
{
    // __construct memuat helper URL, koneksi MySQL, dan model untuk semua endpoint
    // controller.
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->model('Siswa_model');
    }

    // index menyiapkan URL endpoint dan token CSRF lalu memuat satu view.
    public function index()
    {
        $data = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'data_url' => site_url('siswa/data'),
            'save_url' => site_url('siswa/simpan'),
            'delete_url' => site_url('siswa/hapus')
        );
        $this->load->view('siswa/index', $data);
    }

    // data membaca parameter DataTables; draw, offset, dan indeks sorting dikonversi ke
    // integer.
    public function data()
    {
        $draw = (int) $this->input->get('draw');
        $start = max(0, (int) $this->input->get('start'));
        $length = (int) $this->input->get('length');
        // length dibatasi paling banyak 100 agar request tidak dapat meminta seluruh tabel.
        $length = ($length < 1) ? 10 : min($length, 100);
        $search = $this->input->get('search');
        $keyword = (is_array($search) && isset($search['value']))
            ? trim($search['value']) : '';
        $order = $this->input->get('order');
        $column = (isset($order[0]['column']))
            ? max(0, (int) $order[0]['column']) : 1;
        // arah urutan hanya menerima DESC; nilai lain menjadi ASC.
        $direction = (isset($order[0]['dir'])
            && strtoupper($order[0]['dir']) === 'DESC') ? 'DESC' : 'ASC';

        // Model mengembalikan total, hasil filter, dan halaman; JSON mengikuti protokol
        // DataTables server-side.
        list($total, $filtered, $rows) = $this->Siswa_model->datatables(
            $start, $length, $keyword, $column, $direction
        );
        $this->json_response(array(
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $rows
        ));
    }

    // simpan hanya menerima POST dan menetapkan validasi server untuk setiap field.
    public function simpan()
    {
        if ($this->input->method(TRUE) !== 'POST')
        {
            $this->json_response(array('message' => 'Gunakan POST.'), 405);
            return;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules(
            'nis', 'NIS', 'required|regex_match[/\S/]|trim|max_length[20]'
        );
        $this->form_validation->set_rules(
            'nama', 'Nama siswa', 'required|regex_match[/\S/]|trim|max_length[100]'
        );
        $this->form_validation->set_rules(
            'kelas', 'Kelas', 'required|regex_match[/\S/]|trim|max_length[30]'
        );
        $this->form_validation->set_rules(
            'jenis_kelamin', 'Jenis kelamin', 'required|in_list[L,P]'
        );

        // ID diperiksa sebelum update. Validasi gagal menghasilkan HTTP 422 dan tidak
        // menyentuh database.
        $id_input = trim((string) $this->input->post('id'));
        if ($id_input !== '' && ! ctype_digit($id_input))
        {
            $this->json_response(array('message' => 'ID siswa tidak valid.'), 422);
            return;
        }
        $id = ($id_input === '') ? 0 : (int) $id_input;
        if ($id > 0 && ! $this->Siswa_model->find($id))
        {
            $this->json_response(array('message' => 'Data siswa tidak ditemukan.'), 404);
            return;
        }

        if ($this->form_validation->run() === FALSE)
        {
            $this->json_response(array(
                'message' => 'Periksa kembali isian form.',
                'errors' => $this->form_validation->error_array()
            ), 422);
            return;
        }

        // NIS dicek unik selain record yang sedang diedit; daftar field simpan ditentukan
        // eksplisit.
        $nis = trim($this->input->post('nis'));
        if ($this->Siswa_model->nis_exists($nis, $id))
        {
            $this->json_response(array(
                'message' => 'NIS sudah digunakan.',
                'errors' => array('nis' => 'Gunakan NIS lain.')
            ), 422);
            return;
        }

        $data = array(
            'nis' => $nis,
            'nama' => trim($this->input->post('nama')),
            'kelas' => trim($this->input->post('kelas')),
            'jenis_kelamin' => $this->input->post('jenis_kelamin')
        );
        // Kegagalan tulis dicatat tanpa mengirim SQL atau rahasia ke browser.
        if ( ! $this->Siswa_model->save($data, $id))
        {
            log_message('error', 'Gagal menyimpan data siswa.');
            $this->json_response(array('message' => 'Data belum berhasil disimpan.'), 500);
            return;
        }

        $this->json_response(array(
            'ok' => TRUE,
            'message' => ($id > 0) ? 'Data siswa diperbarui.' : 'Siswa ditambahkan.'
        ));
    }

    // hapus hanya menerima POST dan memastikan ID masih ada sebelum penghapusan.
    public function hapus()
    {
        if ($this->input->method(TRUE) !== 'POST')
        {
            $this->json_response(array('message' => 'Gunakan POST.'), 405);
            return;
        }
        $id = (int) $this->input->post('id');
        if ($id < 1 || ! $this->Siswa_model->find($id))
        {
            $this->json_response(array('message' => 'Data siswa tidak ditemukan.'), 404);
            return;
        }
        if ( ! $this->Siswa_model->delete($id))
        {
            log_message('error', 'Gagal menghapus data siswa.');
            $this->json_response(array('message' => 'Data belum berhasil dihapus.'), 500);
            return;
        }
        $this->json_response(array('ok' => TRUE, 'message' => 'Data siswa dihapus.'));
    }

    // json_response selalu mengirim token CSRF terbaru bersama pesan JSON agar browser dapat
    // memperbarui form.
    private function json_response($data, $status = 200)
    {
        $data['csrf'] = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );
        $this->output->set_status_header($status)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data));
    }
}
CODE

			),
			array(
				'title' => '7. Buat view satu halaman dengan modal',
				'paragraphs' => array('Buat application/views/siswa/index.php. View ini berisi tabel, hidden input CSRF, dan satu modal yang dipakai untuk tambah maupun edit. Controller index memuat view dan memberikan URL route serta token; view tidak mengakses model atau database secara langsung.', 'Di head, muat CSS Bootstrap 5.3.3 lebih dahulu, lalu CSS integrasi DataTables 1.13.8 untuk Bootstrap 5. Setelah markup dan konfigurasi URL, muat jQuery, Bootstrap bundle, DataTables core, dan integrasi Bootstrap. Terakhir, muat view siswa/js sebelum penutup body. Buat file js.php pada tahap 8; CDN memerlukan internet.')
				,'file' => 'application/views/siswa/index.php',
				'code' => <<<'CODE'
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Siswa SMK</title>
    <!-- Bootstrap CSS dimuat sebelum CSS integrasi DataTables Bootstrap 5; keduanya mengatur
         tampilan modal dan tabel. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
<main class="container py-4">
    <h1>Data Siswa SMK</h1>
    <button id="btn-tambah" class="btn btn-primary mb-3">Tambah siswa</button>
    <div id="pesan" role="status" aria-live="polite"></div>
    <!-- Tabel menyediakan lima kolom yang urutannya harus cocok dengan konfigurasi
         DataTables. -->
    <table id="tabel-siswa" class="table table-striped w-100">
        <thead><tr>
            <th>NIS</th><th>Nama</th><th>Kelas</th>
            <th>Jenis kelamin</th><th>Aksi</th>
        </tr></thead>
    </table>
</main>

<div class="modal fade" id="siswaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-siswa">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="judul-modal">Tambah siswa</h2>
                    <!-- Tombol close dan data-bs-dismiss mengendalikan modal Bootstrap 5. -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <!-- ID tersembunyi membedakan create dari update; token CSRF dikirim
                         pada setiap POST. -->
                    <input type="hidden" name="id" id="siswa-id">
                    <input type="hidden" id="csrf-token"
                        name="<?= html_escape($csrf_name) ?>"
                        value="<?= html_escape($csrf_hash) ?>">
                    <!-- Setiap input mempunyai name sesuai nama field yang divalidasi
                         controller. -->
                    <div class="mb-3">
                        <label class="form-label" for="nis">NIS</label>
                        <input class="form-control" id="nis" name="nis" maxlength="20" required>
                        <!-- invalid-feedback menyediakan tempat bagi pesan validasi yang
                             ditambahkan JavaScript. -->
                        <div class="invalid-feedback" data-error="nis"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="nama">Nama siswa</label>
                        <input class="form-control" id="nama" name="nama" maxlength="100" required>
                        <div class="invalid-feedback" data-error="nama"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="kelas">Kelas</label>
                        <input class="form-control" id="kelas" name="kelas" maxlength="30" placeholder="X RPL 1" required>
                        <div class="invalid-feedback" data-error="kelas"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="jenis_kelamin">Jenis kelamin</label>
                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">Pilih</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        <div class="invalid-feedback" data-error="jenis_kelamin"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// json_encode meneruskan URL route endpoint dari controller sebagai konfigurasi JavaScript;
// JSON_HEX_* mencegah karakter HTML menutup elemen script.
window.siswaConfig = <?= json_encode(array(
    'dataUrl' => $data_url,
    'saveUrl' => $save_url,
    'deleteUrl' => $delete_url
), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
</script>
<!-- jQuery harus mendahului DataTables core, lalu integrasi Bootstrap DataTables; Bootstrap
     bundle menyediakan modal dan Popper tanpa bergantung pada jQuery. -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<!-- load->view menyertakan siswa/js.php terakhir setelah seluruh library dan konfigurasi
     tersedia; file itu berisi tag script. -->
<?php $this->load->view('siswa/js'); ?>
</body>
</html>
CODE

			),
			array(
				'title' => '7.1. Pilihan offline: simpan library di proyek',
				'paragraphs' => array('Dengan koneksi internet, unduh keenam file langsung dari URL CDN pada view di atas. Buat folder assets/vendor di root proyek, sejajar dengan application dan index.php. Simpan setiap file dengan nama aslinya sesuai pasangan URL dan lokasi berikut; struktur ini sengaja dibuat untuk unduhan per file, bukan asumsi isi arsip unduhan.', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css → assets/vendor/bootstrap-5.3.3/css/bootstrap.min.css; https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css → assets/vendor/datatables-1.13.8/css/dataTables.bootstrap5.min.css.', 'https://code.jquery.com/jquery-3.7.1.min.js → assets/vendor/jquery-3.7.1/jquery-3.7.1.min.js; https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js → assets/vendor/bootstrap-5.3.3/js/bootstrap.bundle.min.js; https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js → assets/vendor/datatables-1.13.8/js/jquery.dataTables.min.js; https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js → assets/vendor/datatables-1.13.8/js/dataTables.bootstrap5.min.js.', 'Pada view tahap 7, ganti kedua link CSS dan keempat script CDN dengan URL lokal berikut. Pertahankan window.siswaConfig dan satu pemanggilan view siswa/js setelah semua script vendor. Jangan memuat vendor CDN dan lokal sekaligus. Pastikan base_url sesuai aplikasi dan helper URL dimuat oleh controller.'),
				'file' => 'application/views/siswa/index.php (pengganti URL CDN)',
				'code' => <<<'CODE'
<!-- Di dalam <head> -->
<!-- Kedua stylesheet lokal tetap mengikuti urutan Bootstrap lalu integrasi DataTables. -->
<!-- base_url menghasilkan URL melalui Apache, bukan path disk; periksa Network browser bila
     ada file yang tidak ditemukan. Mode offline tetap membutuhkan Apache dan MySQL lokal. -->
<link href="<?= html_escape(base_url('assets/vendor/bootstrap-5.3.3/css/bootstrap.min.css')) ?>" rel="stylesheet">
<link href="<?= html_escape(base_url('assets/vendor/datatables-1.13.8/css/dataTables.bootstrap5.min.css')) ?>" rel="stylesheet">

<!-- Di akhir <body>, setelah window.siswaConfig -->
<!-- Empat script vendor lokal mempertahankan urutan contoh CDN; bundle sudah mencakup
     Popper. -->
<script src="<?= html_escape(base_url('assets/vendor/jquery-3.7.1/jquery-3.7.1.min.js')) ?>"></script>
<script src="<?= html_escape(base_url('assets/vendor/bootstrap-5.3.3/js/bootstrap.bundle.min.js')) ?>"></script>
<script src="<?= html_escape(base_url('assets/vendor/datatables-1.13.8/js/jquery.dataTables.min.js')) ?>"></script>
<script src="<?= html_escape(base_url('assets/vendor/datatables-1.13.8/js/dataTables.bootstrap5.min.js')) ?>"></script>
<!-- View siswa/js tetap dimuat sekali setelah library; jangan menggandakan pemanggilannya
     yang sudah ada di index.php. -->
<?php $this->load->view('siswa/js'); ?>
CODE

			),
			array(
				'title' => '8. Buat jQuery, modal, AJAX, dan DataTables',
				'paragraphs' => array('Buat application/views/siswa/js.php dalam folder yang sama dengan index.php. Salin seluruh kode termasuk tag script. index.php memuatnya dengan $this->load->view setelah library; CI3 menyisipkan hasil view ini ke HTML halaman, bukan mengakses file PHP sebagai URL atau menyimpannya di assets.', 'Script mengatur server-side DataTables, memakai modal yang sama untuk tambah dan edit, menampilkan error sebagai teks, serta melakukan delete melalui POST. Ketika controller mengembalikan CSRF baru, hidden input segera diperbarui.'),
				'file' => 'application/views/siswa/js.php',
				'code' => <<<'CODE'
<!-- Tag script membungkus JavaScript karena js.php dirender sebagai bagian dari HTML. -->
<script>
// Ready handler berjalan setelah DOM siap; config berasal dari URL yang disiapkan
// controller.
$(function () {
    const config = window.siswaConfig;
    // Bootstrap Modal mengendalikan satu modal yang dipakai bersama untuk tambah dan edit.
    const modal = bootstrap.Modal.getOrCreateInstance(
        document.getElementById('siswaModal')
    );
    const $form = $('#form-siswa');
    const $token = $('#csrf-token');
    const $message = $('#pesan');

    // updateToken mengganti nama dan nilai hidden input setelah token CSRF diregenerasi
    // server.
    function updateToken(csrf) {
        if (csrf) {
            $token.attr('name', csrf.name).val(csrf.hash);
        }
    }

    // text() menampilkan pesan sebagai teks sehingga nilai siswa/error tidak ditafsirkan
    // sebagai markup HTML.
    function showMessage(text, isError) {
        $message.text(text).attr('class',
            isError ? 'alert alert-danger' : 'alert alert-success');
    }

    // showErrors menghapus keadaan validasi lama lalu menambahkan pesan baru ke field yang
    // cocok.
    function showErrors(errors) {
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('[data-error]').text('');
        $.each(errors || {}, function (field, message) {
            $form.find('[name="' + field + '"]').addClass('is-invalid');
            $form.find('[data-error="' + field + '"]').text(message);
        });
    }

    // DataTables mengirim pagination, pencarian, dan sorting via GET ke endpoint controller;
    // controller memanggil model untuk mengambil data.
    const table = $('#tabel-siswa').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 10,
        order: [[1, 'asc']],
        ajax: { url: config.dataUrl, type: 'GET' },
        columns: [
            // render.text meng-escape field siswa sebelum ditampilkan. Tombol aksi memakai
            // ID numerik, bukan teks bebas dari database.
            { data: 'nis', render: $.fn.dataTable.render.text() },
            { data: 'nama', render: $.fn.dataTable.render.text() },
            { data: 'kelas', render: $.fn.dataTable.render.text() },
            { data: 'jenis_kelamin', render: $.fn.dataTable.render.text() },
            {
                data: null, orderable: false, searchable: false,
                render: function (data, type, row) {
                    const id = Number(row.id);
                    return '<button class="btn btn-sm btn-warning btn-edit" data-id="' +
                        id + '">Edit</button> ' +
                        '<button class="btn btn-sm btn-danger btn-hapus" data-id="' +
                        id + '">Hapus</button>';
                }
            }
        ],
        language: {
            processing: 'Memuat data...',
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ siswa',
            info: 'Menampilkan _START_–_END_ dari _TOTAL_ siswa',
            emptyTable: 'Belum ada data siswa'
        }
    });

    // btn-tambah mereset form dan membuka modal dalam mode create.
    $('#btn-tambah').on('click', function () {
        $form[0].reset();
        $('#siswa-id').val('');
        showErrors({});
        $('#judul-modal').text('Tambah siswa');
        modal.show();
    });

    // Delegated btn-edit mencari row aktif dan mengisi form modal dari data baris terpilih.
    $('#tabel-siswa').on('click', '.btn-edit', function () {
        const row = table.row($(this).closest('tr')).data();
        $form[0].reset();
        showErrors({});
        $('#siswa-id').val(row.id);
        $('#nis').val(row.nis);
        $('#nama').val(row.nama);
        $('#kelas').val(row.kelas);
        $('#jenis_kelamin').val(row.jenis_kelamin);
        $('#judul-modal').text('Edit siswa');
        modal.show();
    });

    $form.on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: config.saveUrl,
            type: 'POST',
            // serialize mengirim hidden ID serta CSRF dan seluruh nilai form melalui POST.
            data: $form.serialize(),
            dataType: 'json'
        // done hanya menampilkan sukses setelah server berhasil; tabel dimuat ulang tanpa
        // mengubah halaman aktif.
        }).done(function (response) {
            updateToken(response.csrf);
            showMessage(response.message, false);
            modal.hide();
            table.ajax.reload(null, false);
        // fail menangani status 422 dengan error per field dan memperbarui token bila
        // response JSON menyediakannya.
        }).fail(function (xhr) {
            const response = xhr.responseJSON || {};
            updateToken(response.csrf);
            if (xhr.status === 422) {
                showErrors(response.errors);
            }
            showMessage(response.message || 'Penyimpanan gagal.', true);
        });
    });

    // Hapus menggunakan dialog konfirmasi untuk UX; keamanan sebenarnya tetap berasal dari
    // POST, CSRF, dan validasi controller.
    $('#tabel-siswa').on('click', '.btn-hapus', function () {
        const id = Number($(this).data('id'));
        if (!window.confirm('Hapus data siswa ini?')) {
            return;
        }
        // tokenData memasukkan CSRF ke payload hapus. Request DELETE tidak digunakan karena
        // contoh CI3 menerima POST.
        const tokenData = {};
        tokenData[$token.attr('name')] = $token.val();
        $.ajax({
            url: config.deleteUrl,
            type: 'POST',
            data: $.extend({ id: id }, tokenData),
            dataType: 'json'
        }).done(function (response) {
            updateToken(response.csrf);
            showMessage(response.message, false);
            // Setelah hapus berhasil, DataTables mengambil ulang data server tanpa
            // kehilangan halaman saat ini.
            table.ajax.reload(null, false);
        }).fail(function (xhr) {
            const response = xhr.responseJSON || {};
            updateToken(response.csrf);
            showMessage(response.message || 'Penghapusan gagal.', true);
        });
    });
});
</script>
CODE

			),
			array(
				'title' => '9. Jalankan dan uji CRUD di browser',
				'paragraphs' => array('Simpan file, pastikan Apache dan MySQL tetap menyala, lalu buka http://localhost/crud-siswa/siswa. CDN memerlukan koneksi internet; untuk offline siapkan dan gunakan semua file lokal sesuai tahap 7.1. Periksa Network browser jika aset atau endpoint gagal dimuat.', 'Urutan tahap: (1) database db_siswa, (2) application/config/config.php, (3) application/config/database.php, (4) .htaccess, (4.1) application/config/routes.php, (5) application/models/Siswa_model.php, (6) application/controllers/Siswa.php, (7) application/views/siswa/index.php, (7.1) aset vendor lokal jika offline, (8) application/views/siswa/js.php, lalu (9) uji browser. File saling merujuk; jalankan pengujian setelah seluruh tahap yang diperlukan selesai.'),
				'file' => 'Daftar cek browser',
				'code' => <<<'CODE'
Catatan: Langkah pertama menguji koneksi MySQL, route, model, controller, dan DataTables sekaligus.
1. Muat halaman dan pastikan data siswa fiktif yang tersedia tampil.
Catatan: Pencarian, sorting, dan pagination harus menghasilkan query server-side yang benar.
2. Cari NIS/nama dan ubah urutan kolom.
Catatan: NIS ganda, field kosong, dan field terlalu panjang harus ditolak server tanpa baris baru.
3. Tambah siswa melalui modal; cek NIS duplikat dan form kosong.
Catatan: Perubahan harus memakai modal yang sama dan hanya memperbarui siswa ber-ID tersebut.
4. Edit siswa dari tombol pada tabel.
Catatan: Hapus hanya boleh dilakukan setelah konfirmasi browser dan request POST berhasil
         melewati CSRF.
5. Hapus siswa melalui tombol dan konfirmasi.
Catatan: Refresh membuktikan data tersimpan di database, bukan hanya berubah di DOM.
6. Refresh halaman, lalu pastikan perubahan tersimpan di MySQL.
Catatan: GET ke endpoint simpan tidak boleh memodifikasi database.
7. Uji URL /siswa/simpan dengan GET; server harus menolak.
CODE

			),
			array(
				'title' => 'Batas penggunaan dan pengembangan lanjut',
				'paragraphs' => array('Contoh ini sengaja fokus ke satu fitur dan satu halaman untuk praktik. Ia belum menyertakan login, role, audit perubahan, pagination berbasis kebijakan sekolah, atau perlindungan data pribadi. Gunakan data fiktif saja. Untuk sistem sekolah nyata, tambahkan login/otorisasi server-side, backup, kontrol akses, validasi kebijakan, HTTPS, dan uji kompatibilitas PHP; jangan menganggap proyek latihan siap produksi.')
			)
		),
		'exercise' => array(
			'title' => 'Tuntaskan proyek satu halaman',
			'intro' => 'Buat seluruh file sesuai urutan di atas dan demonstrasikan CRUD pada XAMPP lokal.',
			'steps' => array('Jalankan database dan gunakan data fiktif yang sudah ada, atau masukkan contoh sekali pada tabel latihan baru.', 'Ikuti konfigurasi, model, controller, view, dan JavaScript tanpa melompati urutannya.', 'Buktikan pencarian dan pagination terjadi di server.', 'Uji tambah, validasi duplikat, edit, hapus, CSRF, dan request method.', 'Jelaskan peran setiap file kepada teman sekelas tanpa membaca salinan kode.'),
			'criteria' => 'CRUD persisten bekerja di MySQL; daftar tetap satu halaman; browser hanya menerima satu halaman baris; field di-escape; query sorting di-allowlist; write hanya POST dan token CSRF diperbarui.'
		),
		'review' => array(
			array('question' => 'Mengapa CRUD dibuat pada model, controller, view, dan JavaScript yang berbeda?', 'answer' => 'Model mengelola query, controller mengatur HTTP/validasi, view menyusun antarmuka, dan JavaScript menangani interaksi browser; pemisahan menjaga tanggung jawab setiap file jelas.'),
			array('question' => 'Apa tiga angka/data utama yang harus dikirim server ke DataTables?', 'answer' => 'draw, recordsTotal, recordsFiltered, serta data untuk halaman aktif.'),
			array('question' => 'Mengapa tombol hapus dari modal/tabel tetap memerlukan validasi controller?', 'answer' => 'Pengguna dapat mengirim request langsung tanpa menekan tombol; server harus mengecek method, CSRF, ID, serta hak akses.'),
			array('question' => 'Bolehkah contoh ini langsung dipakai untuk data siswa sungguhan?', 'answer' => 'Tidak. Ini latihan lokal tanpa autentikasi dan otorisasi. Tambahkan kontrol keamanan dan perlindungan data sebelum penggunaan nyata.')
		),
		'resources' => array(
			array('title' => 'Dokumentasi CodeIgniter 3', 'url' => 'https://codeigniter.com/userguide3/'),
			array('title' => 'DataTables server-side', 'url' => 'https://datatables.net/manual/server-side'),
			array('title' => 'jQuery API', 'url' => 'https://api.jquery.com/'),
			array('title' => 'XAMPP', 'url' => 'https://www.apachefriends.org/')
		)
	)
);
