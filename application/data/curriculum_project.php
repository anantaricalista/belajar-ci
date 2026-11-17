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
				'title' => 'jQuery menghubungkan aksi pengguna dengan halaman',
				'paragraphs' => array('jQuery memudahkan pemilihan elemen dan penanganan event. Bungkus kode dalam ready handler agar elemen HTML sudah tersedia. Event delegation berguna untuk tombol yang dibuat ulang setiap kali DataTables menggambar ulang tabel.'),
				'file' => 'Contoh jQuery',
				'code' => <<<'CODE'
$(function () {
    const modal = bootstrap.Modal.getOrCreateInstance(
        document.getElementById('siswaModal')
    );
    $('#btn-tambah').on('click', function () {
        $('#form-siswa')[0].reset();
        modal.show();
    });

    $('#tabel-siswa').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        console.log('Edit siswa:', id);
    });
});
CODE
, 'explanations' => array('$(function () { ... }) menunggu DOM siap sebelum selector dijalankan.', 'Selector #btn-tambah memilih tombol berdasarkan id; on(click, ...) mendaftarkan aksi klik.', 'reset mengosongkan input form agar modal siap untuk mode tambah.', 'Event delegation memasang listener pada tabel yang tetap ada; tombol .btn-edit boleh dibuat ulang oleh DataTables.', 'data(id) membaca ID dari atribut data-id tombol; console.log hanya alat belajar, ganti dengan pemuatan data/edit.')),
			array(
				'title' => 'Modal dan AJAX untuk one-page CRUD',
				'paragraphs' => array('One-page CRUD berarti daftar dan form tambah/edit tampil pada satu halaman. Modal cocok untuk data siswa yang hanya memiliki empat field. Form dikirim ke controller dengan AJAX POST; server tetap wajib memvalidasi setiap nilai. Hapus juga harus POST, bukan GET.', 'Proyek akhir menggunakan Bootstrap 5 untuk modal, jQuery untuk AJAX, dan token CSRF CI3 di dalam form. Ambil token berikutnya dari response server setelah POST berhasil maupun gagal validasi, karena CI3 dapat meregenerasi token.'),
				'file' => 'Pola pengiriman form dengan jQuery',
				'code' => <<<'CODE'
$('#form-siswa').on('submit', function (event) {
    event.preventDefault();
    $.ajax({
        url: simpanUrl,
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json'
    }).done(function (response) {
        perbaruiToken(response.csrf);
        tabel.ajax.reload(null, false);
    });
});
CODE
, 'explanations' => array('event.preventDefault mencegah browser memuat halaman baru saat form dikirim.', 'ajax mengirim request ke endpoint CI3; type POST sesuai operasi tulis.', 'serialize mengirim setiap input bernama, termasuk hidden ID dan token CSRF.', 'dataType json memastikan response diurai sebagai JSON.', 'done dijalankan pada response sukses; token diperbarui dan DataTables memuat ulang data tanpa kembali ke halaman pertama. Tambahkan penanganan fail untuk status validasi 422 dan error server.')),
			array(
				'title' => 'Bedakan DataTables client-side dan server-side',
				'paragraphs' => array('Pada client-side, browser mengunduh semua baris lalu mencari dan mengurutkannya. Pada server-side, DataTables mengirim draw, start, length, search, dan order ke endpoint setiap kali pengguna mencari atau berpindah halaman. Server mengembalikan recordsTotal, recordsFiltered, dan data untuk halaman itu saja.', 'Untuk dataset siswa yang terus bertambah, server-side mengurangi data yang dikirim per request. Ia menambah kompleksitas: model harus menghitung total sebelum pencarian dan total setelah pencarian, membatasi ukuran halaman, serta membatasi kolom sorting ke daftar yang diizinkan.'),
				'file' => 'Konfigurasi DataTables server-side',
				'code' => <<<'CODE'
const tabel = $('#tabel-siswa').DataTable({
    processing: true,
    serverSide: true,
    ajax: { url: dataUrl, type: 'GET' },
    columns: [
        { data: 'nis' },
        { data: 'nama' },
        { data: 'kelas' },
        { data: 'jenis_kelamin' },
        { data: null, orderable: false, searchable: false }
    ]
});
CODE
, 'explanations' => array('processing menampilkan indikator saat server sedang memproses request.', 'serverSide mengaktifkan protokol pagination, pencarian, dan sorting sisi server.', 'GET hanya membaca data sehingga tidak mengubah state dan tidak membutuhkan token CSRF.', 'Empat kolom data dipetakan ke field JSON yang dikirim server.', 'Kolom tombol tidak dapat diurutkan atau dicari; handler-nya dipasang dengan event delegation.')),
			array(
				'title' => 'Server mengolah request secara aman',
				'paragraphs' => array('Jangan memakai nama kolom dari request langsung di order_by. Nomor indeks sorting harus dipetakan ke allowlist field pada kode server dan arah dibatasi ke ASC atau DESC. start dan length juga berasal dari browser: konversikan ke integer, batasi offset non-negatif, dan beri batas maksimum panjang halaman.', 'Model menjalankan query total, query count setelah filter, lalu query untuk halaman yang diminta. Semua nilai pencarian diproses lewat Query Builder; pengurutan memakai nama kolom tetap dari allowlist.'),
				'file' => 'Bentuk response server-side',
				'code' => <<<'CODE'
array(
    'draw' => (int) $draw,
    'recordsTotal' => $jumlah_semua,
    'recordsFiltered' => $jumlah_cocok,
    'data' => $baris_halaman
);
CODE
, 'explanations' => array('draw dikembalikan sebagai integer agar request yang lebih lama tidak mengganti hasil terbaru.', 'recordsTotal adalah jumlah baris sebelum filter pencarian.', 'recordsFiltered adalah jumlah baris setelah filter pencarian, bukan banyak baris di halaman ini.', 'data hanya memuat baris untuk offset dan ukuran halaman yang diminta.')),
			array(
				'title' => 'CSRF dan batas pemakaian',
				'paragraphs' => array('Aktifkan csrf_protection di config CI3. Token disertakan pada form tambah/edit dan request POST hapus. Ketika token diregenerasi, perbarui hidden input memakai token baru yang disertakan server. GET DataTables hanya membaca data. Pesan error server jangan membocorkan SQL atau kredensial.', 'Contoh CRUD di tutorial adalah latihan lokal dan belum memiliki login/otorisasi. Gunakan hanya data fiktif. Sebelum digunakan dengan data sekolah, tambahkan autentikasi dan pembatasan akses yang sesuai.')
			)
		),
		'exercise' => array(
			'title' => 'Ukur request DataTables',
			'intro' => 'Amati perbedaan kerja browser dan server.',
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
				'paragraphs' => array('Di XAMPP Control Panel, jalankan Apache dan MySQL. Ekstrak salinan CodeIgniter 3.1.13 ke C:\\xampp\\htdocs\\crud-siswa. Buka http://localhost/phpmyadmin/ untuk database dan http://localhost/crud-siswa/ untuk aplikasi. Pastikan PHP pada instalasi XAMPP sesuai dengan versi yang sudah diuji terhadap CI3.', 'Kode di halaman ini membentuk aplikasi latihan satu halaman. Buat setiap file pada salinan proyek CI3, bukan di repository panduan. Isi database hanya dengan data fiktif; proyek contoh ini belum memiliki login dan tidak boleh digunakan untuk data pribadi siswa.')
			),
			array(
				'title' => '1. Buat database dan tabel siswa',
				'paragraphs' => array('Di phpMyAdmin, pilih tab SQL lalu jalankan seluruh skrip. NIS dibuat unik; ID internal digunakan untuk operasi CRUD. Tiga record contoh di bawah ini bukan data orang sungguhan.'),
				'file' => 'phpMyAdmin → SQL',
				'code' => <<<'CODE'
CREATE DATABASE db_siswa
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE db_siswa;

CREATE TABLE siswa (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nis VARCHAR(20) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    kelas VARCHAR(30) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_siswa_nis (nis)
);

INSERT INTO siswa (nis, nama, kelas, jenis_kelamin) VALUES
('RPL001', 'Alya Contoh', 'X RPL 1', 'P'),
('RPL002', 'Bima Contoh', 'X RPL 1', 'L'),
('RPL003', 'Citra Contoh', 'XI RPL 2', 'P');
CODE
, 'explanations' => array('CREATE DATABASE membuat db_siswa dengan encoding utf8mb4 agar berbagai karakter dapat disimpan.', 'id adalah primary key otomatis; NIS adalah identitas sekolah yang wajib dan unik.', 'nama dan kelas dibatasi panjangnya agar sesuai validasi form dan ukuran kolom.', 'ENUM menjaga jenis_kelamin hanya berisi L atau P; sesuaikan dengan aturan data latihan.', 'INSERT menambahkan record fiktif untuk memeriksa tabel, pencarian, dan pagination.')
			),
			array(
				'title' => '2. Konfigurasikan URL dan perlindungan CSRF',
				'paragraphs' => array('Pada application/config/config.php, ganti nama folder pada base_url bila proyek tidak berada di htdocs/crud-siswa. Biarkan file index.php tetap ada, tetapi kosongkan index_page agar tautan yang dihasilkan helper CI3 tidak mencantumkannya. Pastikan aturan .htaccess tersedia di root seperti pada tahap berikutnya.', 'CSRF aktif untuk request yang mengubah data. Token dari hidden input dikirim saat AJAX POST. Contoh memakai regenerasi token bawaan, lalu memperbarui nilai hidden input dari setiap response JSON.'),
				'file' => 'application/config/config.php (baris konfigurasi)',
				'code' => <<<'CODE'
$config['base_url'] = 'http://localhost/crud-siswa/';
$config['index_page'] = '';
$config['csrf_protection'] = TRUE;
$config['csrf_regenerate'] = TRUE;
CODE
, 'explanations' => array('base_url wajib cocok dengan URL folder aplikasi di htdocs dan diakhiri garis miring.', 'index_page kosong menghilangkan index.php dari URL yang dihasilkan; front controller tetap ada.', 'csrf_protection mengharuskan token pada POST seperti simpan dan hapus.', 'csrf_regenerate meminta token baru setelah request; JavaScript proyek akan memperbarui hidden token dari response server.')
			),
			array(
				'title' => '3. Hubungkan CodeIgniter dengan MySQL',
				'paragraphs' => array('Edit nilai koneksi pada application/config/database.php yang sudah disediakan CI3. Konfigurasi berikut menggunakan nilai standar untuk lingkungan XAMPP lokal. Jika root MySQL milikmu memiliki password, isi nilai lokal yang benar. Jangan menggunakan konfigurasi development ini sebagai kredensial server publik.'),
				'file' => 'application/config/database.php → $db[default]',
				'code' => <<<'CODE'
$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn'      => '',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'db_siswa',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
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
, 'explanations' => array('active_group memilih konfigurasi koneksi default dan query_builder mengaktifkan Query Builder CI3.', 'hostname, username, password, dan database harus cocok dengan layanan MySQL XAMPP.', 'mysqli menghubungkan CI3 melalui ekstensi MySQLi PHP; aktifkan ekstensi tersebut jika driver tidak ditemukan.', 'db_debug membantu menemukan kesalahan lokal, tetapi detail error tidak boleh dikirim ke pengguna produksi.', 'utf8mb4 menjaga teks beragam karakter, termasuk nama dan tanda baca.')
			),
			array(
				'title' => '4. Buat dan konfigurasikan .htaccess',
				'paragraphs' => array('Buat file bernama tepat .htaccess di root proyek, sejajar dengan index.php dan folder application; pastikan bukan .htaccess.txt. Jika file sudah ada, pertahankan konfigurasi lain yang masih diperlukan. Salin aturan berikut untuk meneruskan URL bersih ke index.php tanpa mengganggu file dan folder yang ada.', 'Di XAMPP, aktifkan mod_rewrite dan izinkan AllowOverride FileInfo atau All untuk folder htdocs, lalu restart Apache. Modul Routing & controller menjelaskan langkah pemeriksaan dan penanganan error secara lebih rinci.'),
				'file' => 'C:\\xampp\\htdocs\\crud-siswa\\.htaccess',
				'code' => <<<'CODE'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [L]
</IfModule>
CODE
				, 'explanations' => array('IfModule menjalankan aturan hanya saat mod_rewrite tersedia.', 'RewriteEngine mengaktifkan penulisan ulang URL Apache.', 'Kondisi !-f dan !-d melewatkan file serta direktori yang sudah ada.', 'RewriteRule meneruskan request lainnya ke index.php tanpa menampilkannya pada URL; [L] menghentikan aturan rewrite berikutnya pada putaran itu.', '</IfModule> menutup kelompok aturan.')
			),
			array(
				'title' => '4.1. Atur route URL siswa',
				'paragraphs' => array('Tambahkan route khusus pada application/config/routes.php. Letakkan route endpoint sebelum pola yang lebih umum jika nanti menambahkan pola ID siswa. Route ini membuat aplikasi mempunyai satu halaman dan endpoint JSON terpisah untuk DataTables, simpan, dan hapus.'),
				'file' => 'application/config/routes.php (ganti default route, tambahkan endpoint)',
				'code' => <<<'CODE'
$route['siswa'] = 'siswa/index';
$route['siswa/data'] = 'siswa/data';
$route['siswa/simpan'] = 'siswa/simpan';
$route['siswa/hapus'] = 'siswa/hapus';
$route['default_controller'] = 'siswa'; // ganti nilai default_controller yang lama
CODE
, 'explanations' => array('GET /siswa memuat halaman utama.', 'GET /siswa/data mengirim baris dan hitungan server-side DataTables.', 'POST /siswa/simpan menjalankan insert atau update berdasarkan ID.', 'POST /siswa/hapus menghapus satu record setelah pemeriksaan CSRF.', 'default_controller membuka controller Siswa ketika path root diakses; pastikan tidak ada route default duplikat yang tertinggal.')
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
    private $table = 'siswa';

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
        $total = $this->db->count_all($this->table);

        $this->db->from($this->table);
        $this->filter_keyword($keyword);
        $filtered = $this->db->count_all_results();

        $this->db->from($this->table);
        $this->filter_keyword($keyword);
        $columns = array('nis', 'nama', 'kelas', 'jenis_kelamin');
        $order_by = isset($columns[$column]) ? $columns[$column] : 'nama';
        $direction = ($direction === 'DESC') ? 'DESC' : 'ASC';

        $rows = $this->db->order_by($order_by, $direction)
            ->limit($length, $start)
            ->get()->result_array();

        return array($total, $filtered, $rows);
    }

    public function find($id)
    {
        return $this->db->get_where($this->table, array('id' => $id))
            ->row_array();
    }

    public function nis_exists($nis, $except_id = 0)
    {
        $this->db->where('nis', $nis);
        if ($except_id > 0)
        {
            $this->db->where('id !=', $except_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    public function save($data, $id = 0)
    {
        if ($id > 0)
        {
            return $this->db->where('id', $id)
                ->update($this->table, $data);
        }
        return $this->db->insert($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }
}
CODE
, 'explanations' => array('table menyimpan nama tabel sebagai konstanta class agar query memakai sumber tabel yang sama.', 'filter_keyword memakai grup OR: keyword dicocokkan terhadap NIS, nama, atau kelas. Query Builder meng-escape nilai pencarian.', 'count_all menghitung seluruh siswa; count_all_results menghitung hasil setelah filter.', 'columns adalah allowlist: indeks sorting dari browser hanya dapat memilih empat kolom ini.', 'direction dibatasi ke ASC atau DESC. limit membatasi jumlah record dan offset untuk halaman aktif.', 'find mengambil satu siswa berdasarkan ID untuk mengisi modal edit.', 'nis_exists mengecualikan ID saat ini ketika memeriksa perubahan NIS.', 'save melakukan INSERT untuk siswa baru atau UPDATE untuk ID yang ada; hanya field yang diberikan controller ditulis.', 'delete membatasi penghapusan ke ID tertentu. Controller harus memeriksa request POST dan keberadaan baris sebelum memanggilnya.')
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
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->model('Siswa_model');
    }

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

    public function data()
    {
        $draw = (int) $this->input->get('draw');
        $start = max(0, (int) $this->input->get('start'));
        $length = (int) $this->input->get('length');
        $length = ($length < 1) ? 10 : min($length, 100);
        $search = $this->input->get('search');
        $keyword = (is_array($search) && isset($search['value']))
            ? trim($search['value']) : '';
        $order = $this->input->get('order');
        $column = (isset($order[0]['column']))
            ? max(0, (int) $order[0]['column']) : 1;
        $direction = (isset($order[0]['dir'])
            && strtoupper($order[0]['dir']) === 'DESC') ? 'DESC' : 'ASC';

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

    public function simpan()
    {
        if ($this->input->method(TRUE) !== 'POST')
        {
            $this->json_response(array('message' => 'Gunakan POST.'), 405);
            return;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules(
            'nis', 'NIS', 'trim|required|max_length[20]'
        );
        $this->form_validation->set_rules(
            'nama', 'Nama siswa', 'trim|required|max_length[100]'
        );
        $this->form_validation->set_rules(
            'kelas', 'Kelas', 'trim|required|max_length[30]'
        );
        $this->form_validation->set_rules(
            'jenis_kelamin', 'Jenis kelamin', 'required|in_list[L,P]'
        );

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
, 'explanations' => array('__construct memuat helper URL, koneksi MySQL, dan model untuk semua endpoint controller.', 'index menyiapkan URL endpoint dan token CSRF lalu memuat satu view.', 'data membaca parameter DataTables; draw, offset, dan indeks sorting dikonversi ke integer.', 'length dibatasi paling banyak 100 agar request tidak dapat meminta seluruh tabel.', 'arah urutan hanya menerima DESC; nilai lain menjadi ASC.', 'Model mengembalikan total, hasil filter, dan halaman; JSON mengikuti protokol DataTables server-side.', 'simpan hanya menerima POST dan menetapkan validasi server untuk setiap field.', 'ID diperiksa sebelum update. Validasi gagal menghasilkan HTTP 422 dan tidak menyentuh database.', 'NIS dicek unik selain record yang sedang diedit; daftar field simpan ditentukan eksplisit.', 'Kegagalan tulis dicatat tanpa mengirim SQL atau rahasia ke browser.', 'hapus hanya menerima POST dan memastikan ID masih ada sebelum penghapusan.', 'json_response selalu mengirim token CSRF terbaru bersama pesan JSON agar browser dapat memperbarui form.')
			),
			array(
				'title' => '7. Buat view satu halaman dengan modal',
				'paragraphs' => array('Buat application/views/siswa/index.php. View ini berisi tabel, hidden input CSRF, dan satu modal yang dipakai untuk tambah maupun edit. CDN di bawah memerlukan koneksi internet; untuk kelas tanpa internet unduh aset vendor dan sajikan dari folder assets lokal.')
				,'file' => 'application/views/siswa/index.php',
				'code' => <<<'CODE'
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Siswa SMK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
<main class="container py-4">
    <h1>Data Siswa SMK</h1>
    <button id="btn-tambah" class="btn btn-primary mb-3">Tambah siswa</button>
    <div id="pesan" role="status" aria-live="polite"></div>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="siswa-id">
                    <input type="hidden" id="csrf-token"
                        name="<?= html_escape($csrf_name) ?>"
                        value="<?= html_escape($csrf_hash) ?>">
                    <div class="mb-3">
                        <label class="form-label" for="nis">NIS</label>
                        <input class="form-control" id="nis" name="nis" maxlength="20" required>
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
window.siswaConfig = <?= json_encode(array(
    'dataUrl' => $data_url,
    'saveUrl' => $save_url,
    'deleteUrl' => $delete_url
), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="<?= html_escape(base_url('assets/js/siswa.js')) ?>"></script>
</body>
</html>
CODE
, 'explanations' => array('Bootstrap dan DataTables CSS mengatur tampilan modal dan tabel; CDN membutuhkan internet.', 'Tabel menyediakan lima kolom yang urutannya harus cocok dengan konfigurasi DataTables.', 'Tombol close dan data-bs-dismiss mengendalikan modal Bootstrap 5.', 'ID tersembunyi membedakan create dari update; token CSRF dikirim pada setiap POST.', 'Setiap input mempunyai name sesuai nama field yang divalidasi controller.', 'invalid-feedback menyediakan tempat bagi pesan validasi yang ditambahkan JavaScript.', 'json_encode meneruskan URL endpoint sebagai konfigurasi JavaScript; JSON_HEX_* mencegah karakter HTML menutup elemen script.', 'Urutan script penting: jQuery dimuat sebelum Bootstrap, DataTables, lalu kode aplikasi.', 'base_url assets mengarah ke file JavaScript lokal proyek.')
			),
			array(
				'title' => '8. Buat jQuery, modal, AJAX, dan DataTables',
				'paragraphs' => array('Buat assets/js/siswa.js. Script mengatur server-side DataTables, memakai modal yang sama untuk tambah dan edit, menampilkan error sebagai teks, serta melakukan delete melalui POST. Ketika controller mengembalikan CSRF baru, hidden input segera diperbarui.'),
				'file' => 'assets/js/siswa.js',
				'code' => <<<'CODE'
$(function () {
    const config = window.siswaConfig;
    const modal = bootstrap.Modal.getOrCreateInstance(
        document.getElementById('siswaModal')
    );
    const $form = $('#form-siswa');
    const $token = $('#csrf-token');
    const $message = $('#pesan');

    function updateToken(csrf) {
        if (csrf) {
            $token.attr('name', csrf.name).val(csrf.hash);
        }
    }

    function showMessage(text, isError) {
        $message.text(text).attr('class',
            isError ? 'alert alert-danger' : 'alert alert-success');
    }

    function showErrors(errors) {
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('[data-error]').text('');
        $.each(errors || {}, function (field, message) {
            $form.find('[name="' + field + '"]').addClass('is-invalid');
            $form.find('[data-error="' + field + '"]').text(message);
        });
    }

    const table = $('#tabel-siswa').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 10,
        order: [[1, 'asc']],
        ajax: { url: config.dataUrl, type: 'GET' },
        columns: [
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

    $('#btn-tambah').on('click', function () {
        $form[0].reset();
        $('#siswa-id').val('');
        showErrors({});
        $('#judul-modal').text('Tambah siswa');
        modal.show();
    });

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
            data: $form.serialize(),
            dataType: 'json'
        }).done(function (response) {
            updateToken(response.csrf);
            showMessage(response.message, false);
            modal.hide();
            table.ajax.reload(null, false);
        }).fail(function (xhr) {
            const response = xhr.responseJSON || {};
            updateToken(response.csrf);
            if (xhr.status === 422) {
                showErrors(response.errors);
            }
            showMessage(response.message || 'Penyimpanan gagal.', true);
        });
    });

    $('#tabel-siswa').on('click', '.btn-hapus', function () {
        const id = Number($(this).data('id'));
        if (!window.confirm('Hapus data siswa ini?')) {
            return;
        }
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
            table.ajax.reload(null, false);
        }).fail(function (xhr) {
            const response = xhr.responseJSON || {};
            updateToken(response.csrf);
            showMessage(response.message || 'Penghapusan gagal.', true);
        });
    });
});
CODE
, 'explanations' => array('Ready handler berjalan setelah DOM siap; config berasal dari URL yang disiapkan controller.', 'Bootstrap Modal mengendalikan satu modal yang dipakai bersama untuk tambah dan edit.', 'updateToken mengganti nama dan nilai hidden input setelah token CSRF diregenerasi server.', 'text() menampilkan pesan sebagai teks sehingga nilai siswa/error tidak ditafsirkan sebagai markup HTML.', 'showErrors menghapus keadaan validasi lama lalu menambahkan pesan baru ke field yang cocok.', 'DataTables meminta data via GET; serverSide mengirim pagination, pencarian, dan sorting ke model.', 'render.text meng-escape field siswa sebelum ditampilkan. Tombol aksi memakai ID numerik, bukan teks bebas dari database.', 'btn-tambah mereset form dan membuka modal dalam mode create.', 'Delegated btn-edit mencari row aktif dan mengisi form modal dari data baris terpilih.', 'serialize mengirim hidden ID serta CSRF dan seluruh nilai form melalui POST.', 'done hanya menampilkan sukses setelah server berhasil; tabel dimuat ulang tanpa mengubah halaman aktif.', 'fail menangani status 422 dengan error per field dan memperbarui token bila response JSON menyediakannya.', 'Hapus menggunakan dialog konfirmasi untuk UX; keamanan sebenarnya tetap berasal dari POST, CSRF, dan validasi controller.', 'tokenData memasukkan CSRF ke payload hapus. Request DELETE tidak digunakan karena contoh CI3 menerima POST.', 'Setelah hapus berhasil, DataTables mengambil ulang data server tanpa kehilangan halaman saat ini.')
			),
			array(
				'title' => '9. Jalankan dan uji CRUD di browser',
				'paragraphs' => array('Simpan file, pastikan Apache dan MySQL tetap menyala, lalu buka http://localhost/crud-siswa/siswa. CDN Bootstrap, jQuery, dan DataTables memerlukan koneksi internet. Jika ingin belajar tanpa internet, unduh file vendor dan ubah URL script menjadi path lokal.', 'Urutan file yang sudah dibuat: (1) database db_siswa di phpMyAdmin, (2) application/config/config.php dan database.php, (3) .htaccess, (4) application/config/routes.php, (5) application/models/Siswa_model.php, (6) application/controllers/Siswa.php, (7) application/views/siswa/index.php, (8) assets/js/siswa.js. Masing-masing tahap menyiapkan kebutuhan tahap sesudahnya.'),
				'file' => 'Daftar cek browser',
				'code' => <<<'CODE'
1. Muat halaman dan pastikan tiga siswa fiktif tampil.
2. Cari NIS/nama dan ubah urutan kolom.
3. Tambah siswa melalui modal; cek NIS duplikat dan form kosong.
4. Edit siswa dari tombol pada tabel.
5. Hapus siswa melalui tombol dan konfirmasi.
6. Refresh halaman, lalu pastikan perubahan tersimpan di MySQL.
7. Uji URL /siswa/simpan dengan GET; server harus menolak.
CODE
, 'explanations' => array('Langkah pertama menguji koneksi MySQL, route, model, controller, dan DataTables sekaligus.', 'Pencarian, sorting, dan pagination harus menghasilkan query server-side yang benar.', 'NIS ganda, field kosong, dan field terlalu panjang harus ditolak server tanpa baris baru.', 'Perubahan harus memakai modal yang sama dan hanya memperbarui siswa ber-ID tersebut.', 'Hapus hanya boleh dilakukan setelah konfirmasi browser dan request POST berhasil melewati CSRF.', 'Refresh membuktikan data tersimpan di database, bukan hanya berubah di DOM.', 'GET ke endpoint simpan tidak boleh memodifikasi database.')
			),
			array(
				'title' => 'Batas penggunaan dan pengembangan lanjut',
				'paragraphs' => array('Contoh ini sengaja fokus ke satu fitur dan satu halaman untuk praktik. Ia belum menyertakan login, role, audit perubahan, pagination berbasis kebijakan sekolah, atau perlindungan data pribadi. Gunakan data fiktif saja. Untuk sistem sekolah nyata, tambahkan login/otorisasi server-side, backup, kontrol akses, validasi kebijakan, HTTPS, dan uji kompatibilitas PHP; jangan menganggap proyek latihan siap produksi.')
			)
		),
		'exercise' => array(
			'title' => 'Tuntaskan proyek satu halaman',
			'intro' => 'Buat seluruh file sesuai urutan di atas dan demonstrasikan CRUD pada XAMPP lokal.',
			'steps' => array('Jalankan database dan masukkan data contoh fiktif.', 'Ikuti konfigurasi, model, controller, view, dan JavaScript tanpa melompati urutannya.', 'Buktikan pencarian dan pagination terjadi di server.', 'Uji tambah, validasi duplikat, edit, hapus, CSRF, dan request method.', 'Jelaskan peran setiap file kepada teman sekelas tanpa membaca salinan kode.'),
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
