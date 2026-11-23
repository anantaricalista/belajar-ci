<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$form_modules = require APPPATH.'data/curriculum_form.php';
$login_modules = require APPPATH.'data/curriculum_login.php';

return array(
	'prasyarat' => array(
		'category' => 'FONDASI', 'title' => 'Bekal sebelum mulai', 'duration' => 3,
		'summary' => 'Siapkan PHP, XAMPP, MySQL, dan dasar web untuk membangun aplikasi data siswa SMK.',
		'goals' => array('Memahami request HTTP dan dasar PHP yang dipakai CodeIgniter.', 'Menjalankan Apache dan MySQL melalui XAMPP.', 'Menyiapkan proyek latihan data siswa RPL secara lokal.'),
		'sections' => array(
			array('title' => 'Jalankan XAMPP', 'paragraphs' => array('Instal XAMPP dari sumber resminya dan buka XAMPP Control Panel. Tekan Start pada Apache dan MySQL; keduanya harus menunjukkan status berjalan. Buka http://localhost/ untuk memeriksa Apache dan http://localhost/phpmyadmin/ untuk memastikan MySQL/phpMyAdmin tersedia.', 'Unduh CodeIgniter 3.1.13 dan ekstrak folder proyek ke C:\\xampp\\htdocs\\crud-siswa (Windows) atau folder htdocs pada instalasi XAMPP-mu. Buka http://localhost/crud-siswa/ untuk menjalankan proyek. Biarkan index.php tetap ada; URL bersih tanpa index.php dibahas di modul routing.', 'CI3 adalah framework lama. Periksa versi PHP yang disertakan XAMPP dan uji kompatibilitasnya. PHP terbaru dapat menampilkan deprecation dari CI3; jangan menyembunyikan error sebagai pengganti pengujian.'),
				'file' => 'XAMPP Control Panel', 'code' => <<<'CODE'
Apache menyajikan file PHP dari htdocs dan menerima request browser.
1. Klik Start pada Apache.
MySQL menyimpan data aplikasi; phpMyAdmin adalah antarmuka pengelola, bukan pengganti database.
2. Klik Start pada MySQL.
Localhost hanya menunjuk ke komputer sendiri; jangan mengekspos server latihan ke jaringan publik.
3. Buka http://localhost/
4. Buka http://localhost/phpmyadmin/
CODE
			),
			array('title' => 'Bagaimana web bekerja?', 'paragraphs' => array('Browser mengirim HTTP request ke Apache. CodeIgniter memproses request dan mengirim response berupa status, header, dan isi halaman. GET digunakan untuk membaca, sedangkan POST mengirim perubahan. Penghapusan data tidak boleh dijalankan melalui GET.', 'Form mengirim nama dan nilai. Pengguna dapat mengubah request di luar browser, jadi setiap input tetap divalidasi di server.')),
			array('title' => 'Bekal PHP dan SQL', 'paragraphs' => array('Pastikan kamu dapat membaca variabel, array, kondisi, perulangan, fungsi, serta class dan method. Controller CI3 adalah class; method-nya menangani request. Untuk proyek ini kita akan menyimpan data siswa contoh/fiktif: NIS, nama, kelas, dan jenis kelamin.'), 'file' => 'PHP dasar', 'code' => <<<'CODE'
<?php
// Terima array siswa dan kembalikan kolom nama.
function nama_siswa($siswa)
{
    return $siswa['nama'];
}

// Array asosiatif mengaitkan kunci nama dengan teks contoh.
$siswa = array('nama' => 'Contoh Siswa RPL');
// Escape teks untuk HTML. Helper ini tersedia setelah CI berjalan, bukan pada PHP mandiri.
echo html_escape(nama_siswa($siswa));
CODE
			),
			array('title' => 'Data dan ruang praktik', 'paragraphs' => array('Pelajari tabel, kolom, primary key, serta operasi SELECT, INSERT, UPDATE, dan DELETE. Database serta seluruh data di proyek latihan harus berupa data buatan, bukan data pribadi siswa sungguhan.', 'Gunakan satu salinan CI3 di htdocs/crud-siswa sebagai proyek latihan. Situs Ruang CI ini adalah panduan dan tidak mengubah datanya.'))
		),
		'exercise' => array('title' => 'Siapkan meja praktik', 'intro' => 'Pastikan lingkungan belajar berjalan sebelum menambah kode.', 'steps' => array('Jalankan Apache dan MySQL melalui XAMPP Control Panel.', 'Buka localhost dan phpMyAdmin.', 'Buat folder crud-siswa di htdocs, lalu buka URL lokalnya.'), 'criteria' => 'Apache dan MySQL aktif, halaman localhost terbuka, dan folder proyek CI3 bisa dijangkau di browser.'),
		'review' => array(array('question' => 'Mengapa input browser tidak dapat langsung dipercaya?', 'answer' => 'Pengguna dapat mengubah request di luar form; validasi dan pemeriksaan otorisasi harus dilakukan di server.'), array('question' => 'Apa perbedaan GET dan POST?', 'answer' => 'GET digunakan untuk mengambil representasi; POST mengirim data atau meminta perubahan. Operasi yang mengubah data tidak boleh dilakukan melalui GET.')),
		'resources' => array(array('title' => 'PHP: tutorial dasar', 'url' => 'https://www.php.net/manual/en/tutorial.php'), array('title' => 'MDN: gambaran HTTP', 'url' => 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Overview'))
	),
	'mengenal-ci' => array(
		'category' => 'FONDASI', 'title' => 'Mengenal CodeIgniter 3', 'duration' => 3,
		'summary' => 'Jalankan CI3 di XAMPP dan pahami struktur folder serta alur MVC.',
		'goals' => array('Menjalankan salinan CI3 di lingkungan lokal.', 'Menjelaskan fungsi application, system, dan index.php.', 'Memahami hubungan model, view, dan controller.'),
		'sections' => array(
			array('title' => 'Siapkan XAMPP', 'paragraphs' => array('Buka XAMPP Control Panel dan tekan Start pada Apache dan MySQL. Ekstrak proyek CI3 ke C:\\xampp\\htdocs\\crud-siswa (Windows) atau folder htdocs pada instalasi XAMPP-mu. Buka http://localhost/crud-siswa/ untuk menjalankan aplikasi dan http://localhost/phpmyadmin/ untuk mengelola MySQL.', 'XAMPP menyediakan PHP, Apache, dan MySQL/MariaDB dalam satu paket. Periksa versi PHP yang disertakan dan uji kompatibilitasnya dengan CI3 3.1.13. Versi PHP terbaru dapat menampilkan deprecation dari kode framework lama; jangan menganggap menyembunyikan pesan sebagai perbaikan.')),
			array('title' => 'Kenali bagian utama', 'paragraphs' => array('index.php adalah front controller yang menjalankan request. Folder system berisi inti framework; hindari mengubahnya. Folder application berisi controllers, models, views, config, dan kode aplikasi.', 'Di application/config/config.php, base_url harus sesuai lokasi proyek XAMPP, misalnya http://localhost/crud-siswa/. routes.php menentukan pemetaan URL.')),
			array('title' => 'Pola MVC dan alur request', 'paragraphs' => array('Controller menerima request dan mengatur alur. Model menangani akses dan pengolahan data. View menyajikan data kepada pengguna. Pembagian ini membantu kita mengubah tampilan tanpa memindahkan query ke dalam view.', 'Alur umumnya: browser → index.php → route → controller → model bila diperlukan → view → response. Tidak semua request membutuhkan model, misalnya halaman statis.'))
		),
		'exercise' => array('title' => 'Petakan proyekmu', 'intro' => 'Buka salinan proyek CI3 dan telusuri halaman awal.', 'steps' => array('Temukan default_controller di application/config/routes.php.', 'Buka controller dan view yang dirujuk, lalu ubah teks view pada proyek latihan.', 'Gambarkan alur dari URL sampai teks baru tampil di browser.'), 'criteria' => 'Halaman tampil dan kamu bisa menunjukkan peran route, controller, dan view.'),
		'review' => array(array('question' => 'Mengapa kode aplikasi sebaiknya tidak ditaruh di system?', 'answer' => 'System berisi inti framework. Menjaga kode aplikasi di application memisahkan fitur dari framework dan memudahkan pemeliharaan.'), array('question' => 'Apakah setiap controller harus memanggil model?', 'answer' => 'Tidak. Halaman statis dapat langsung memuat view; gunakan model saat membutuhkan data atau pengolahan yang relevan.')),
		'resources' => array(array('title' => 'Instalasi CI3', 'url' => 'https://codeigniter.com/userguide3/installation/index.html'), array('title' => 'Model-View-Controller', 'url' => 'https://codeigniter.com/userguide3/overview/mvc.html'))
	),
	'routing-controller' => array(
		'category' => 'FONDASI', 'title' => 'Routing & controller', 'duration' => 4,
		'summary' => 'Ikuti perjalanan URL menuju controller dan buat halaman data siswa pertamamu.',
		'goals' => array('Membuat controller dan method publik di CI3.', 'Memetakan URL menggunakan routes.php dan menghilangkan index.php dari URL.', 'Menangani parameter dan halaman tidak ditemukan.'),
		'sections' => array(
			array('title' => 'Controller menerima request', 'paragraphs' => array('Nama file dan class controller menggunakan huruf awal kapital, misalnya Siswa.php dan Siswa. Class mewarisi CI_Controller. Method publik dapat dipetakan ke URL; hindari menjadikan method internal sebagai endpoint publik.'), 'file' => 'application/controllers/Siswa.php', 'code' => <<<'CODE'
<?php
// Cegah file controller diakses langsung.
defined('BASEPATH') OR exit('No direct script access allowed');

// Siswa mewarisi CI_Controller; index menampilkan daftar.
class Siswa extends CI_Controller {
    public function index()
    {
        echo 'Daftar siswa';
    }

    public function detail($id = NULL)
    {
        // Periksa ID sebagai angka dan tampilkan 404 jika tidak valid.
        if ($id === NULL || ! ctype_digit((string) $id))
        {
            show_404();
            return;
        }
        // Escape ID sebelum ditampilkan sebagai teks HTML.
        echo 'Data siswa nomor '.html_escape($id);
    }
}
CODE
			),
			array('title' => 'Petakan URL', 'paragraphs' => array('Tambahkan route berikut di proyek latihan. (:num) hanya cocok untuk segmen angka, lalu $1 diteruskan ke method. Urutan route penting: letakkan route khusus sebelum pola yang lebih umum. Setelah konfigurasi URL bersih berikut diterapkan, contoh URL lokal adalah /siswa/12.'), 'file' => 'application/config/routes.php', 'code' => <<<'CODE'
// Arahkan route siswa ke method index controller Siswa.
$route['siswa'] = 'siswa/index';
// (:num) menangkap ID angka; $1 meneruskannya ke method detail.
$route['siswa/(:num)'] = 'siswa/detail/$1';
CODE
			),
			array('title' => 'Hilangkan index.php dari URL', 'paragraphs' => array('CI3 tetap membutuhkan file index.php sebagai front controller. Yang dihilangkan hanya bagian URL-nya: Apache XAMPP meneruskan request secara internal ke file tersebut. Mengubah routes.php saja tidak cukup.', 'Pada proyek latihan, kosongkan index_page agar helper URL tidak menghasilkan tautan dengan index.php. Sertakan folder proyek di base_url.'), 'file' => 'application/config/config.php', 'code' => <<<'CODE'
// URL lengkap untuk helper URL; ganti crud-siswa jika nama folder htdocs berbeda.
$config['base_url'] = 'http://localhost/crud-siswa/';
// Nilai kosong membuat helper CI3 tidak menyisipkan index.php ke tautan.
$config['index_page'] = '';
CODE
			),
			array('title' => 'Buat dan konfigurasikan .htaccess di XAMPP', 'paragraphs' => array('File .htaccess adalah konfigurasi Apache untuk direktori tempat file tersebut berada. Buat file bernama tepat .htaccess di root proyek C:\\xampp\\htdocs\\crud-siswa, sejajar dengan index.php dan folder application, bukan di dalam application. Pada editor, pilih Save As dan pastikan namanya bukan .htaccess.txt. Jika file sudah ada, edit aturan yang relevan tanpa menghapus konfigurasi lain yang masih dibutuhkan.', 'Salin aturan berikut. Apache meneruskan URL yang bukan file atau folder yang sudah ada ke index.php secara internal; file CSS dan JavaScript tetap dilayani langsung. Aturan ini tidak menghapus atau mengganti file index.php.'), 'file' => 'C:\\xampp\\htdocs\\crud-siswa\\.htaccess', 'code' => <<<'CODE'
# Aturan hanya berjalan jika mod_rewrite tersedia; URL bersih tidak bekerja jika tidak aktif.
<IfModule mod_rewrite.c>
    # Aktifkan penulisan ulang URL untuk direktori proyek.
    RewriteEngine On
    # Lewati file dan direktori yang sudah ada.
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    # Teruskan request yang lolos ke index.php; [L] menghentikan aturan rewrite berikutnya pada putaran ini.
    RewriteRule ^ index.php [L]
# Tutup kelompok aturan mod_rewrite.
</IfModule>
CODE
			),
			array('title' => 'Periksa Apache dan coba URL tanpa index.php', 'paragraphs' => array('Di XAMPP Control Panel, buka Config di baris Apache lalu pilih httpd.conf. Cari baris LoadModule rewrite_module modules/mod_rewrite.so; jika diawali #, hapus # untuk mengaktifkan mod_rewrite. Cari pengaturan <Directory> yang berlaku untuk folder htdocs dan pastikan AllowOverride mengizinkan FileInfo atau All. Simpan perubahan dan restart Apache; jangan menambahkan blok Directory baru jika pengaturan yang berlaku sudah ada.', 'Setelah base_url dan index_page diatur seperti bagian sebelumnya, buka http://localhost/crud-siswa/siswa dan http://localhost/crud-siswa/siswa/12, lalu refresh halaman detail. Pastikan route dan controller Siswa sudah dibuat serta file statis tetap dapat diakses.', 'Jika URL bersih 404, periksa nama dan lokasi .htaccess, mod_rewrite, AllowOverride, serta route. Jika muncul error 500 setelah mengubah konfigurasi, periksa log error Apache melalui tombol Logs pada XAMPP; jangan menebak penyebabnya. Aturan .htaccess berlaku untuk Apache, bukan server bawaan PHP atau Nginx.')),
			array('title' => 'Tangani input dan 404', 'paragraphs' => array('Route bukan pengganti validasi pada controller: URL bawaan CI3 juga dapat mengakses method publik. Periksa parameter sebelum menggunakannya dan panggil show_404() untuk resource yang tidak ada. Pada modul database, keberadaan ID juga harus diperiksa.'))
		),
		'exercise' => array('title' => 'Buat dua endpoint', 'intro' => 'Gunakan controller Siswa dalam proyek latihan.', 'steps' => array('Tambahkan route daftar dan detail siswa, lalu konfigurasikan URL bersih untuk server XAMPP.', 'Buka /crud-siswa/siswa dan /crud-siswa/siswa/12 secara langsung, lalu refresh halaman detail.', 'Pastikan file statis masih dimuat dan coba parameter yang tidak valid, termasuk akses langsung ke method controller.'), 'criteria' => 'Daftar dan detail siswa dapat diakses tanpa index.php, termasuk setelah refresh; file statis tetap tersedia dan parameter tidak valid ditolak.'),
		'review' => array(array('question' => 'Apa fungsi (:num) dan $1?', 'answer' => '(:num) mencocokkan segmen angka; $1 meneruskan segmen yang cocok ke tujuan route.'), array('question' => 'Apakah file index.php boleh dihapus setelah URL dibersihkan?', 'answer' => 'Tidak. Server tetap meneruskan request ke index.php sebagai front controller; hanya penulisannya pada URL yang dihilangkan.'), array('question' => 'Mengapa parameter tetap divalidasi di controller?', 'answer' => 'Method publik dapat diakses melalui routing lain; route tidak menjamin semua jalur masuk memiliki validasi yang sama.')),
		'resources' => array(array('title' => 'Controller CI3', 'url' => 'https://codeigniter.com/userguide3/general/controllers.html'), array('title' => 'URI routing', 'url' => 'https://codeigniter.com/userguide3/general/routing.html'), array('title' => 'URL CodeIgniter dan mod_rewrite', 'url' => 'https://codeigniter.com/userguide3/general/urls.html'))
	),
	'views' => array(
		'category' => 'FONDASI', 'title' => 'Menyajikan data dengan view', 'duration' => 3,
		'summary' => 'Pisahkan tampilan dari logika dan tampilkan data dengan aman.',
		'goals' => array('Memuat view dan mengirim data dari controller.', 'Menggunakan template sederhana dan perulangan.', 'Melakukan escaping data untuk konteks HTML.'),
		'sections' => array(
			array('title' => 'Kirim data ke view', 'paragraphs' => array('Controller mempersiapkan array data dan memuat view. Kunci array menjadi variabel di view. Gunakan data siswa sementara sebelum dihubungkan dengan database.'), 'file' => 'Siswa::index()', 'code' => <<<'CODE'
// Siapkan dua siswa fiktif untuk dicoba sebelum MySQL dipakai.
$data['siswa'] = array(
    array('nis' => 'RPL001', 'nama' => 'Alya Contoh', 'kelas' => 'X RPL 1'),
    array('nis' => 'RPL002', 'nama' => 'Bima Contoh', 'kelas' => 'X RPL 1')
);
// Muat application/views/siswa/index.php dan kirim data ke view.
$this->load->view('siswa/index', $data);
CODE
			),
			array('title' => 'Tampilkan data dengan aman', 'paragraphs' => array('Buat folder views/siswa dan file index.php. Escape teks dari database dengan html_escape; HTML, atribut, URL, dan JavaScript membutuhkan encoding sesuai konteks.'), 'file' => 'application/views/siswa/index.php', 'code' => <<<'CODE'
<h1>Data Siswa SMK</h1>
<ul>
<?php
// Ambil setiap baris siswa sebagai item.
foreach ($siswa as $item): ?>
    <li><?php /* Escape setiap nilai sebelum disisipkan ke HTML. */ ?><?= html_escape($item['nis']) ?> —
        <?= html_escape($item['nama']) ?>,
        <?= html_escape($item['kelas']) ?></li>
<?php
// Tutup perulangan; proses berlanjut ke markup berikutnya.
endforeach; ?>
</ul>
CODE
			),
			array('title' => 'Susun template', 'paragraphs' => array('Pecah struktur bersama menjadi view header dan footer. Controller dapat memanggil header, view isi, lalu footer; sertakan data yang diperlukan masing-masing. Jaga query dan aturan bisnis tetap di luar view.'))
		),
		'exercise' => array('title' => 'Tampilkan daftar siswa fiktif', 'intro' => 'Ubah endpoint daftar agar menggunakan view.', 'steps' => array('Kirim minimal dua siswa contoh dari controller ke view.', 'Buat tabel yang menampilkan NIS, nama, dan kelas.', 'Uji nama yang mengandung tag HTML dan pastikan ditampilkan sebagai teks.'), 'criteria' => 'Daftar siswa muncul melalui view dan nilai tidak dijalankan sebagai HTML.'),
		'review' => array(array('question' => 'Apa yang terjadi pada kunci array data saat view dimuat?', 'answer' => 'Kunci array tersedia sebagai nama variabel di view yang dimuat.'), array('question' => 'Apakah html_escape membuat data aman untuk semua konteks?', 'answer' => 'Tidak. Gunakan encoding sesuai konteks; jangan memasukkan data mentah ke JavaScript atau URL tanpa penanganan yang sesuai.')),
		'resources' => array(array('title' => 'Views CI3', 'url' => 'https://codeigniter.com/userguide3/general/views.html'), array('title' => 'Keamanan CI3', 'url' => 'https://codeigniter.com/userguide3/general/security.html'))
	),
	'database-model' => array(
		'category' => 'MEMBANGUN FITUR', 'title' => 'Database & model', 'duration' => 4,
		'summary' => 'Hubungkan aplikasi ke MySQL XAMPP dan ambil data siswa melalui model.',
		'goals' => array('Mengonfigurasi koneksi MySQL pada XAMPP.', 'Membuat model dan mengambil daftar serta detail siswa.', 'Menangani data yang tidak ditemukan.'),
		'sections' => array(
			array('title' => 'Buat database MySQL di phpMyAdmin', 'paragraphs' => array('Pastikan MySQL berjalan di XAMPP Control Panel, lalu buka http://localhost/phpmyadmin/. Pilih tab SQL dan jalankan skrip berikut. Semua nama dan NIS di sini adalah data buatan untuk latihan, bukan data pribadi siswa.'), 'file' => 'phpMyAdmin → SQL', 'code' => <<<'CODE'
-- Buat database baru dengan encoding utf8mb4.
CREATE DATABASE db_siswa
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE db_siswa;

CREATE TABLE siswa (
    -- ID adalah primary key otomatis untuk mengubah dan menghapus baris.
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    -- NIS wajib dan unik agar nomor yang sama tidak didaftarkan dua kali.
    nis VARCHAR(20) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    kelas VARCHAR(30) NOT NULL,
    -- ENUM membatasi nilai ke L atau P; sesuaikan dengan kebijakan data latihan.
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_siswa_nis (nis)
);

-- Masukkan data fiktif untuk menguji tampilan dan pagination.
INSERT INTO siswa (nis, nama, kelas, jenis_kelamin) VALUES
('RPL001', 'Alya Contoh', 'X RPL 1', 'P'),
('RPL002', 'Bima Contoh', 'X RPL 1', 'L'),
('RPL003', 'Citra Contoh', 'XI RPL 2', 'P');
CODE
			),
			array('title' => 'Atur koneksi CI3 ke MySQL', 'paragraphs' => array('Edit application/config/database.php pada proyek latihan. Pada XAMPP lokal standar, host biasanya localhost, user root, password kosong, dan database db_siswa. Jika kamu mengatur password MySQL, masukkan password lokal yang sesuai; jangan simpan kredensial produksi di Git.'), 'file' => 'application/config/database.php → $db[default]', 'code' => <<<'CODE'
// Pilih koneksi default; Query Builder membantu menyusun query dengan aman.
$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn'      => '',
    // Sesuaikan host, pengguna, password, dan database dengan XAMPP/MySQL lokal.
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'db_siswa',
    // Gunakan MySQLi; pastikan ekstensi PHP tersebut aktif di XAMPP.
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    // Debug membantu belajar, tetapi error database dan kredensial tidak boleh ditampilkan di produksi.
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    // utf8mb4 mendukung teks Unicode secara penuh.
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
			array('title' => 'Buat model siswa', 'paragraphs' => array('Model merangkum akses data agar controller tidak perlu mengetahui detail query. Query Builder memproses nilai sebagai data; jangan menggabungkan input pengguna ke SQL mentah.'), 'file' => 'application/models/Siswa_model.php', 'code' => <<<'CODE'
<?php
// Cegah file model dibuka langsung dari URL.
defined('BASEPATH') OR exit('No direct script access allowed');

// Nama class sesuai file Siswa_model.php dan mewarisi CI_Model.
class Siswa_model extends CI_Model {
    // Ambil siswa terurut dan kembalikan array hasil query.
    public function semua()
    {
        return $this->db->order_by('nama', 'ASC')
            ->get('siswa')->result_array();
    }

    // Query Builder membatasi hasil berdasarkan ID; row_array mengembalikan NULL jika tidak ada hasil.
    public function cari($id)
    {
        return $this->db->get_where('siswa', array('id' => $id))
            ->row_array();
    }
}
CODE
			),
			array('title' => 'Hubungkan controller', 'paragraphs' => array('Muat database dan model di constructor controller, lalu panggil semua() untuk daftar. Pada detail, validasi ID, panggil cari($id), dan gunakan show_404() bila hasilnya kosong. Kirim hasil yang ditemukan ke view. Jangan tampilkan pesan koneksi atau kredensial kepada pengunjung.')),
			array('title' => 'Contoh daftar dan detail', 'paragraphs' => array('Tambahkan constructor dan kedua method ini pada controller Siswa di proyek latihan. Contoh detail memakai view siswa/detail yang perlu kamu buat; gunakan html_escape untuk setiap field siswa di dalam view.'), 'file' => 'Bagian dari application/controllers/Siswa.php', 'code' => <<<'CODE'
// Muat database dan model sebelum controller melayani request.
public function __construct()
{
    parent::__construct();
    $this->load->database();
    $this->load->model('Siswa_model');
}

// Ambil semua siswa dari model dan kirim ke view.
public function index()
{
    $data['siswa'] = $this->Siswa_model->semua();
    $this->load->view('siswa/index', $data);
}

public function detail($id = NULL)
{
    // Tolak ID yang bukan angka dan hentikan request setelah show_404.
    if ($id === NULL || ! ctype_digit((string) $id))
    {
        show_404();
        return;
    }
    // Cari satu baris berdasarkan ID; hasil kosong menghasilkan 404.
    $data['siswa'] = $this->Siswa_model->cari($id);
    if ( ! $data['siswa'])
    {
        show_404();
        return;
    }
    // View menerima array siswa dan harus meng-escape setiap nilai sebelum HTML.
    $this->load->view('siswa/detail', $data);
}
CODE
			),
		),
		'exercise' => array('title' => 'Ambil data siswa dari MySQL', 'intro' => 'Jalankan XAMPP lalu hubungkan aplikasi latihan ke database lokal.', 'steps' => array('Buat database db_siswa dan tabel siswa di phpMyAdmin.', 'Atur application/config/database.php dengan driver mysqli.', 'Buat Siswa_model dan tampilkan daftar siswa fiktif.'), 'criteria' => 'Aplikasi terhubung ke MySQL, menampilkan data siswa buatan, dan query tetap berada di model.'),
		'review' => array(array('question' => 'Mengapa query ditempatkan di model?', 'answer' => 'Model memusatkan akses data sehingga controller fokus pada alur request dan view pada penyajian.'), array('question' => 'Apa yang harus dilakukan jika row_array() tidak menemukan data?', 'answer' => 'Periksa hasil sebelum mengaksesnya dan kembalikan 404 untuk siswa yang tidak ada.')),
		'resources' => array(array('title' => 'Model CI3', 'url' => 'https://codeigniter.com/userguide3/general/models.html'), array('title' => 'Query Builder', 'url' => 'https://codeigniter.com/userguide3/database/query_builder.html'))
	),
	'form-validasi' => $form_modules['form-validasi'],
	'crud' => array(
		'category' => 'MEMBANGUN FITUR', 'title' => 'CRUD lengkap', 'duration' => 6,
		'summary' => 'Lengkapi alur CRUD siswa dalam satu halaman dengan modal dan AJAX.',
		'goals' => array('Membuat alur edit dan hapus dengan model.', 'Memeriksa keberadaan siswa dan hasil operasi.', 'Menggunakan POST untuk perubahan dan memperbarui tabel setelah berhasil.'),
		'sections' => array(
			array('title' => 'Dari create ke update', 'paragraphs' => array('Setelah merakit aplikasi lengkap pada akhir modul jQuery & DataTables, pelajari kembali method save dan delete berikut dari model yang sama. Edit memakai modal dan ID; controller memvalidasi input dan memeriksa keberadaan siswa sebelum memanggil model.'), 'file' => 'Bagian dari application/models/Siswa_model.php', 'code' => <<<'CODE'
public function save($data, $id = 0)
{
    // Controller harus memvalidasi data; where membatasi perubahan berdasarkan ID.
    // Gunakan update untuk ID yang ada dan insert untuk siswa baru.
    if ($id > 0)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }
    return $this->db->insert($this->table, $data);
}

// Hapus berdasarkan ID; controller harus memastikan baris ada dan request memakai POST.
public function delete($id)
{
    return $this->db->where('id', $id)->delete($this->table);
}
CODE
			),
			array('title' => 'Hapus secara aman', 'paragraphs' => array('Gunakan POST, bukan GET, untuk menghapus. Periksa method request, token CSRF, keberadaan data, dan hak akses sebelum memanggil model. Konfirmasi di browser boleh membantu pengguna, tetapi bukan pengganti pemeriksaan server.', 'Setelah AJAX berhasil, muat ulang tabel dan tampilkan pesan sukses. Bila gagal, catat kesalahan server dan beri pesan yang tidak membocorkan detail database.')),
			array('title' => 'Periksa method sebelum mengubah data', 'paragraphs' => array('Potongan berikut adalah gerbang method pada Siswa::hapus() di aplikasi lengkap. Untuk aplikasi sekolah, autentikasi dan otorisasi juga perlu ditambahkan. Perlindungan CSRF tetap diperiksa oleh CI3 sebelum controller berjalan.'), 'file' => 'Bagian dari Siswa::hapus()', 'code' => <<<'CODE'
// method membaca jenis request HTTP; tolak selain POST dengan 405 dan hentikan proses.
// Sebelum penghapusan, periksa juga otorisasi dan keberadaan siswa.
if ($this->input->method(TRUE) !== 'POST')
{
    $this->json_response(array('message' => 'Gunakan POST.'), 405);
    return;
}
CODE
			),
			array('title' => 'Batas tahap ini', 'paragraphs' => array('CRUD latihan belum memiliki login dan otorisasi sekolah. Gunakan data buatan di lingkungan lokal saja. Jangan masukkan data pribadi atau NIS siswa sungguhan ke latihan, dan jangan publikasikan pengelolaan tanpa autentikasi.'))
		),
		'exercise' => array('title' => 'Selesaikan CRUD siswa', 'intro' => 'Bangun create, read, update, delete untuk siswa fiktif.', 'steps' => array('Buka form modal tambah siswa dan simpan melalui AJAX POST.', 'Edit siswa dari tombol tabel menggunakan modal yang sama.', 'Hapus melalui POST, lalu muat ulang tabel DataTables dan uji kesalahan.'), 'criteria' => 'CRUD siswa bekerja dari satu halaman; input divalidasi server dan request gagal tidak menampilkan notifikasi sukses.'),
		'review' => array(array('question' => 'Mengapa hapus tidak menggunakan GET?', 'answer' => 'GET seharusnya aman untuk mengambil data; browser atau crawler dapat mengikuti tautan GET tanpa bermaksud mengubah data.'), array('question' => 'Apakah konfirmasi JavaScript cukup untuk melindungi penghapusan?', 'answer' => 'Tidak. Validasi request, CSRF, dan otorisasi harus diperiksa di server.')),
		'resources' => array(array('title' => 'Query Builder: update dan delete', 'url' => 'https://codeigniter.com/userguide3/database/query_builder.html'), array('title' => 'Session dan flashdata', 'url' => 'https://codeigniter.com/userguide3/libraries/sessions.html'))
	),
	'keamanan' => array(
		'category' => 'APLIKASI AMAN', 'title' => 'Keamanan aplikasi', 'duration' => 6,
		'summary' => 'Lindungi input, query, output, form, dan konfigurasi sejak awal.',
		'goals' => array('Membedakan validasi, query binding, dan escaping output.', 'Mengaktifkan dan menguji perlindungan CSRF CI3.', 'Mengenali praktik aman untuk password dan konfigurasi.'),
		'sections' => array(
			array('title' => 'Pertahanan berlapis', 'paragraphs' => array('Validasi memeriksa format dan aturan bisnis; ia bukan pengganti otorisasi. Untuk query gunakan Query Builder atau parameter binding pada nilai. Jangan membangun SQL dengan menggabungkan input; nama kolom dan urutan dinamis perlu daftar nilai yang diizinkan, karena identifier bukan nilai bind biasa.', 'Escape output HTML menggunakan html_escape. Jangan mengandalkan xss_clean sebagai pengganti encoding kontekstual; data yang masuk ke URL atau JavaScript membutuhkan perlakuan sesuai konteks.')),
			array('title' => 'Binding untuk nilai query', 'paragraphs' => array('Tanda tanya adalah placeholder untuk nilai; CI3 melakukan binding sesuai driver. Contoh ini mencari berdasarkan NIS, bukan menyusun SQL dari string input. Untuk query umum, Query Builder juga menyediakan cara yang mudah dibaca.'), 'file' => 'Bagian dari Siswa_model', 'code' => <<<'CODE'
// Jalankan SQL melalui driver CI3; ? adalah placeholder nilai NIS.
// Nilai dikirim terpisah sebagai parameter, bukan ditempelkan ke string SQL.
$query = $this->db->query(
    'SELECT id, nis, nama FROM siswa WHERE nis = ?',
    array($nis)
);
// Ubah hasil query menjadi array untuk diolah aplikasi.
return $query->result_array();
CODE
			),
			array('title' => 'CSRF pada form perubahan', 'paragraphs' => array('Aktifkan csrf_protection di application/config/config.php pada proyek latihan. Form POST yang dibuat dengan form_open akan menyertakan token ketika fitur aktif. Uji semua form termasuk edit dan hapus; request tanpa token yang valid harus ditolak. Untuk AJAX atau integrasi lain, kirim token dengan cara yang sesuai dan perhatikan regenerasi token.', 'CSRF tidak menggantikan pemeriksaan login dan hak akses. Pastikan operasi perubahan tidak tersedia melalui GET.'), 'file' => 'application/config/config.php', 'code' => <<<'CODE'
// Aktifkan pemeriksaan token CI3 untuk POST; form dan AJAX harus mengirim token valid.
$config['csrf_protection'] = TRUE;
CODE
			),
			array('title' => 'Password dan rahasia', 'paragraphs' => array('Gunakan password_hash dan password_verify, bukan hash cepat seperti MD5. Simpan hash, bukan password asli; jangan mengubah password dengan trim atau filter yang merusak nilainya. Pada login, gunakan pesan kegagalan umum dan regenerasi session ID setelah autentikasi.', 'Atur base_url eksplisit untuk lingkungan produksi, gunakan HTTPS, batasi akses file konfigurasi dan log, dan simpan kredensial di konfigurasi lingkungan yang tidak dikomit. Jangan tampilkan stack trace atau error database kepada pengunjung.'))
		),
		'exercise' => array('title' => 'Uji lapisan perlindungan', 'intro' => 'Tinjau setiap titik masuk dan keluaran aplikasi latihan satu halaman.', 'steps' => array('Periksa token CSRF pada form modal dan uji tambah, edit, dan hapus melalui AJAX.', 'Kirim POST tanpa token yang valid dan pastikan server menolaknya.', 'Simpan nama siswa fiktif yang mengandung tag HTML dan pastikan tampil sebagai teks; periksa semua query yang menerima input.'), 'criteria' => 'Form valid tetap berfungsi, POST tanpa token ditolak, dan data tidak dijalankan sebagai HTML.'),
		'review' => array(array('question' => 'Apa perbedaan validasi input dan escaping output?', 'answer' => 'Validasi menegakkan aturan input; escaping menyiapkan data untuk konteks output tertentu. Keduanya memiliki tujuan berbeda.'), array('question' => 'Apakah token CSRF membuktikan pengguna boleh menghapus data siswa?', 'answer' => 'Tidak. Token mengurangi risiko request lintas situs; hak akses harus diperiksa terpisah di server.')),
		'resources' => array(array('title' => 'Keamanan CI3', 'url' => 'https://codeigniter.com/userguide3/general/security.html'), array('title' => 'Security library dan CSRF', 'url' => 'https://codeigniter.com/userguide3/libraries/security.html'), array('title' => 'PHP password hashing', 'url' => 'https://www.php.net/manual/en/function.password-hash.php'))
	),
	'login-otorisasi' => $login_modules['login-otorisasi'],
	'pencarian-debugging' => array(
		'category' => 'PENDALAMAN', 'title' => 'Pencarian & debugging', 'duration' => 4,
		'summary' => 'Buat daftar lebih mudah digunakan dan pelajari cara menelusuri masalah.',
		'goals' => array('Menambahkan pencarian dan pagination.', 'Menggunakan log serta menangani kesalahan dengan tepat.', 'Memahami batas input dan konsistensi hasil pencarian.'),
		'sections' => array(
			array('title' => 'Pencarian yang terarah', 'paragraphs' => array('Terima kata kunci melalui GET, batasi panjangnya, dan terapkan filter yang sama pada query data dan query jumlah hasil. Gunakan Query Builder untuk nilai pencarian. Jika pengguna dapat memilih urutan, petakan pilihan ke daftar kolom dan arah yang diperbolehkan; jangan langsung memasukkannya sebagai SQL.', 'Tampilkan kata kunci dengan escaping HTML dan sediakan keadaan kosong yang jelas ketika tidak ada hasil.')),
			array('title' => 'Pagination konsisten', 'paragraphs' => array('Pagination membutuhkan total hasil setelah filter, ukuran halaman, dan offset yang valid. Gunakan urutan yang stabil, misalnya ID sebagai pemecah seri, lalu pertahankan kata kunci pada tautan halaman. Uji halaman pertama, terakhir, halaman kosong, dan parameter yang tidak valid.', 'Library pagination CI3 membantu membuat tautan, tetapi query limit dan offset tetap harus diterapkan di model.')),
			array('title' => 'Filter dan batas hasil', 'paragraphs' => array('Contoh model berikut menerima kata kunci dan offset yang sudah divalidasi controller. Gunakan filter like yang sama untuk query count_all_results pada query terpisah. Query Builder akan mereset state setelah get, sehingga ulangi filter untuk setiap query.'), 'file' => 'Bagian dari Siswa_model', 'code' => <<<'CODE'
// Filter nama siswa dan hitung semua yang cocok; count_all_results mereset state Query Builder.
$this->db->like('nama', $kata_kunci);
$jumlah = $this->db->count_all_results('siswa');

// Ulangi filter karena query sebelumnya selesai.
$this->db->like('nama', $kata_kunci);
// Urutkan secara stabil dan ambil hanya halaman dengan limit dan offset yang diminta.
$hasil = $this->db->order_by('id', 'DESC')
    ->limit(10, $offset)->get('siswa')->result_array();
CODE
			),
			array('title' => 'Debug tanpa membocorkan rahasia', 'paragraphs' => array('Reproduksi masalah, periksa status HTTP dan log, lalu telusuri route, controller, model, dan view. Gunakan log_message untuk informasi yang diperlukan tanpa mencatat password, token, atau data sensitif. Pastikan folder log dapat ditulis oleh server.', 'Di produksi, tampilkan pesan umum dan simpan detail di log terlindungi. Jangan menonaktifkan penanganan error atau menampilkan trace sebagai solusi.'))
		),
		'exercise' => array('title' => 'Cari dan telusuri', 'intro' => 'Perbaiki pengalaman daftar siswa.', 'steps' => array('Tambahkan pencarian nama dan jumlah hasil.', 'Tambahkan pagination server-side DataTables dengan urutan stabil.', 'Uji pencarian kosong dan tanpa hasil, lalu catat kesalahan tanpa menampilkan detail kepada pengguna.'), 'criteria' => 'Jumlah hasil dan isi halaman memakai filter yang sama.'),
		'review' => array(array('question' => 'Mengapa query jumlah dan query daftar harus memakai filter yang sama?', 'answer' => 'Agar jumlah halaman sesuai dengan hasil yang benar-benar ditampilkan.'), array('question' => 'Apa yang tidak boleh dicatat di log?', 'answer' => 'Password, token, kredensial, dan data sensitif yang tidak diperlukan untuk diagnosis.')),
		'resources' => array(array('title' => 'Pagination CI3', 'url' => 'https://codeigniter.com/userguide3/libraries/pagination.html'), array('title' => 'Penanganan error CI3', 'url' => 'https://codeigniter.com/userguide3/general/errors.html'))
	),
	'migrasi-pengujian' => array(
		'category' => 'PENDALAMAN', 'title' => 'Migrasi & pengujian', 'duration' => 4,
		'summary' => 'Buat skema dapat diulang dan periksa alur aplikasi secara sistematis.',
		'goals' => array('Memahami migrasi skema dan rollback.', 'Membangun ulang database latihan di lingkungan bersih.', 'Menguji alur sukses, kegagalan, dan batas keamanan.'),
		'sections' => array(
			array('title' => 'Migrasi skema', 'paragraphs' => array('Migrasi mencatat perubahan skema sebagai langkah berurutan yang bisa diterapkan kembali. CI3 menyediakan migration library dan database forge. Gunakan nama file sesuai konfigurasi timestamp atau sequential dan implementasikan up() serta down() secara hati-hati.', 'Aktifkan migration_enabled hanya saat menjalankan migrasi sesuai dokumentasi dan lindungi mekanisme eksekusinya. Jangan membuat endpoint migrasi publik tanpa autentikasi. Backup sebelum migrasi produksi; rollback tidak selalu dapat memulihkan data yang terhapus.')),
			array('title' => 'Contoh migrasi tabel siswa', 'paragraphs' => array('Untuk konfigurasi timestamp bawaan, buat folder application/migrations dan gunakan nama file bertimestamp. Contoh ini ditujukan untuk database latihan kosong; jangan menjalankannya pada database yang sudah berisi tabel siswa. down() menghapus tabel beserta datanya.'), 'file' => 'application/migrations/20260101000000_create_siswa.php', 'code' => <<<'CODE'
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Warisi CI_Migration agar class dapat dijalankan migration library.
class Migration_Create_siswa extends CI_Migration {
    // Definisikan kolom tabel siswa.
    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array('type' => 'INT', 'unsigned' => TRUE,
                          'auto_increment' => TRUE),
            // NIS diberi batasan unique.
            'nis' => array('type' => 'VARCHAR', 'constraint' => 20, 'unique' => TRUE),
            'nama' => array('type' => 'VARCHAR', 'constraint' => 100),
            'kelas' => array('type' => 'VARCHAR', 'constraint' => 30),
            'jenis_kelamin' => array('type' => 'VARCHAR', 'constraint' => 1)
        ));
        // Tetapkan primary key, lalu buat tabel.
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('siswa');
    }

    // Rollback menghapus tabel dan seluruh datanya; lakukan backup sebelumnya.
    public function down()
    {
        $this->dbforge->drop_table('siswa');
    }
}
CODE
			),
			array('title' => 'Lapisan pengujian', 'paragraphs' => array('Uji fungsi kecil secara terisolasi bila memungkinkan, lalu uji integrasi database dan alur HTTP. CI3 memiliki unit testing class sederhana; pengujian HTTP atau otomasi tambahan memerlukan pengaturan terpisah. Gunakan database uji khusus, tidak pernah database produksi.', 'Kasus penting: siswa ada/tidak ada, input kosong dan terlalu panjang, create/edit/hapus berhasil maupun gagal, POST tanpa CSRF, pengguna anonim dan tanpa hak, serta login gagal. Uji bahwa error tidak menghasilkan pesan sukses palsu.')),
			array('title' => 'Periksa hasil, bukan hanya kode', 'paragraphs' => array('Catat kondisi awal, tindakan, dan hasil yang diharapkan. Setelah mengubah aplikasi, jalankan kembali kasus yang terdampak. Bila tes manual dilakukan, tuliskan hasilnya agar bisa diulang dan dibedakan dari tes otomatis.'))
		),
		'exercise' => array('title' => 'Bangun ulang dan uji', 'intro' => 'Buktikan aplikasi dapat dipasang ulang dan perilaku utamanya benar.', 'steps' => array('Buat migrasi tabel latihan dan terapkan pada database kosong.', 'Siapkan data latihan yang tidak mengandung kredensial nyata.', 'Jalankan kasus CRUD, validasi, login, otorisasi, dan CSRF; catat hasil sukses dan gagal.'), 'criteria' => 'Skema dapat dibuat ulang dan setiap kasus memiliki hasil yang dapat diamati.'),
		'review' => array(array('question' => 'Mengapa database pengujian harus terpisah?', 'answer' => 'Pengujian dapat mengubah atau menghapus data; isolasi mencegah kerusakan data nyata.'), array('question' => 'Apakah rollback skema selalu mengembalikan data yang terhapus?', 'answer' => 'Tidak. Migrasi balik tidak menggantikan backup yang telah diverifikasi.')),
		'resources' => array(array('title' => 'Migrations CI3', 'url' => 'https://codeigniter.com/userguide3/libraries/migration.html'), array('title' => 'Unit testing CI3', 'url' => 'https://codeigniter.com/userguide3/libraries/unit_testing.html'))
	),
	'deployment' => array(
		'category' => 'PROYEK AKHIR', 'title' => 'Deployment & proyek akhir', 'duration' => 5,
		'summary' => 'Satukan hasil belajar dan siapkan aplikasi untuk lingkungan terpisah.',
		'goals' => array('Memisahkan konfigurasi pengembangan dan produksi.', 'Memeriksa keamanan dan kompatibilitas sebelum rilis.', 'Mendemonstrasikan aplikasi siswa dari instalasi hingga pengujian.'),
		'sections' => array(
			array('title' => 'Siapkan lingkungan', 'paragraphs' => array('Gunakan versi PHP yang masih didukung dan uji CI3 serta ekstensi yang dibutuhkan pada versi tersebut. Tetapkan base_url secara eksplisit, gunakan HTTPS dan konfigurasi database yang berbeda dari lokal. Jangan mengandalkan php -S sebagai server produksi.', 'Atur ENVIRONMENT untuk produksi sesuai cara deployment, nonaktifkan tampilan error kepada pengguna, dan lindungi log serta konfigurasi. Berikan izin tulis hanya pada folder yang memerlukannya.')),
			array('title' => 'Periksa sebelum rilis', 'paragraphs' => array('Jalankan migrasi yang telah diuji dan siapkan backup serta prosedur pemulihan. Pastikan tidak ada kredensial di repository, form perubahan memakai CSRF, semua endpoint pengelolaan memeriksa hak akses, dan output di-escape. Pastikan cookie session dan HTTPS dikonfigurasi sesuai lingkungan.', 'Uji login, logout, CRUD, pencarian, validasi, halaman tidak ada, serta kondisi gagal di lingkungan terpisah. Pantau log tanpa menyimpan rahasia. Siapkan cara rollback dan uji restore backup.')),
			array('title' => 'Proyek akhir', 'paragraphs' => array('Hasil latihan berikutnya adalah aplikasi siswa dengan CRUD satu halaman, modal, pencarian, dan pagination DataTables server-side. Contoh akhir belum memiliki login atau pembatasan akses dan hanya untuk data fiktif lokal; tambahkan keduanya sebelum penggunaan sekolah.', 'CI3 dan CI4 adalah framework berbeda. Setelah memahami proyek ini, pelajari strategi modernisasi atau migrasi secara terpisah; jangan mengasumsikan contoh CI3 dapat langsung berjalan di CI4.'))
		),
		'exercise' => array('title' => 'Demonstrasikan proyekmu', 'intro' => 'Lakukan simulasi serah terima pada lingkungan bersih.', 'steps' => array('Pasang proyek, konfigurasi lingkungan, dan siapkan database kosong.', 'Demonstrasikan daftar dan pengelolaan siswa fiktif.', 'Uji CSRF dan input tidak valid, catat versi PHP, dan susun rencana autentikasi sebelum penggunaan nyata.'), 'criteria' => 'Orang lain dapat mengikuti instruksi dan memverifikasi alur utama serta penanganan kesalahan tanpa kredensial yang tertanam di kode.'),
		'review' => array(array('question' => 'Mengapa base_url harus ditetapkan pada produksi?', 'answer' => 'Deteksi otomatis hanya untuk kemudahan pengembangan dan tidak boleh diandalkan pada produksi.'), array('question' => 'Apakah aplikasi siap produksi hanya karena berjalan di localhost?', 'answer' => 'Tidak. Kompatibilitas, konfigurasi, keamanan, backup, dan perilaku di lingkungan target harus diuji.')),
		'resources' => array(array('title' => 'Lingkungan CI3', 'url' => 'https://codeigniter.com/userguide3/general/environments.html'), array('title' => 'Versi PHP yang didukung', 'url' => 'https://www.php.net/supported-versions.php'))
	)
);
