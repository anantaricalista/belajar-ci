<?php
defined('BASEPATH') OR exit('No direct script access allowed');

return array(
	'login-otorisasi' => array(
		'category' => 'APLIKASI AMAN',
		'title' => 'Login & otorisasi',
		'duration' => 5,
		'summary' => 'Membuat contoh login MVC terisolasi dan membatasi dashboard untuk admin.',
		'goals' => array(
			'Membedakan autentikasi dan otorisasi pada server.',
			'Memverifikasi hash password dan meregenerasi session setelah login.',
			'Melindungi dashboard admin dan logout POST dengan CSRF.'
		),
		'sections' => array(
			array(
				'title' => 'Ruang lingkup dan persiapan',
				'paragraphs' => array(
					'Contoh ini adalah aplikasi login CI3 terisolasi untuk dashboard admin, bukan tambahan otomatis ke proyek CRUD Siswa AJAX. Kode lengkap latihan CRUD tetap belum memiliki login atau otorisasi: endpoint daftar, data, tambah, edit, dan hapus Siswa tidak dilindungi contoh ini. Jangan memakai data nyata atau mempublikasikan latihan ini.',
					'Siapkan proyek CI3 lokal dengan PHP 7.2 atau lebih baru, ekstensi mysqli, Apache dan MySQL. Contoh URL menggunakan http://localhost/login-demo/; sesuaikan dengan folder proyek. Gunakan proyek terpisah agar perubahan konfigurasi dan route tidak mengganggu CRUD. Pastikan PHP CLI yang dipakai untuk membuat hash kompatibel dengan PHP server.',
					'Autentikasi memverifikasi identitas. Otorisasi memeriksa hak setelahnya: dashboard membaca ulang peran dari database pada setiap request, sehingga perubahan peran atau penghapusan akun berlaku pada request berikutnya. Session hanya menyimpan ID, bukan password atau peran sebagai sumber kebenaran.'
				)
			),
			array(
				'title' => 'Database dan tabel pengguna',
				'paragraphs' => array(
					'Buat database lokal login_demo lewat phpMyAdmin, pilih database tersebut, lalu jalankan SQL berikut satu kali. Konfigurasi application/config/database.php agar koneksinya menuju database ini, menggunakan akun database lokal dengan hak minimum. Jangan menyimpan kredensial database dalam source yang dibagikan atau dikomit; sediakan melalui konfigurasi lingkungan di luar version control.',
					'Atur db_debug menjadi FALSE agar detail query tidak ditampilkan. Pada lingkungan nyata nonaktifkan display_errors, gunakan ENVIRONMENT production, lindungi log, dan tangani gangguan database tanpa menampilkan detail kepada pengunjung. Kolom password_hash menampung hash, bukan sandi asli.'
				),
				'file' => 'SQL — jalankan pada database login_demo',
				'code' => <<<'CODE'
CREATE TABLE pengguna (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    PRIMARY KEY (id),
    UNIQUE KEY pengguna_username_unique (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CODE
				, 'explanations' => array(
					'id adalah identitas unik; username unik dan peka huruf besar/kecil.',
					'password_hash menyediakan ruang untuk perubahan algoritma; role membatasi nilai peran.'
				)
			),
			array(
				'title' => 'Konfigurasikan koneksi database login',
				'paragraphs' => array('Di application/config/database.php proyek login-demo, ubah nilai di dalam array $db[default] yang sudah ada. Contoh berikut hanya untuk XAMPP lokal; sesuaikan akun dan password jika berbeda. Pastikan ekstensi mysqli aktif.'),
				'file' => 'application/config/database.php — nilai $db[default]',
				'code' => <<<'CODE'
'hostname' => 'localhost',
'username' => 'root',
'password' => '',
'database' => 'login_demo',
'dbdriver' => 'mysqli',
'db_debug' => FALSE,
CODE
				, 'explanations' => array('Ganti nilai pada array yang sudah ada, bukan menambahkan array baru.', 'hostname, username, password dan database harus sesuai MySQL lokal; db_debug FALSE mencegah detail query ditampilkan.')
			),
			array(
				'title' => 'Buat akun dummy hanya di komputer lokal',
				'paragraphs' => array(
					'Jalankan perintah berikut di terminal lokal untuk menghasilkan hash dari sandi dummy eksplisit. Sandi ini hanya untuk latihan lokal, diketahui publik, dan tidak boleh digunakan di produksi. Jangan menggunakan sandi nyata pada perintah karena argumen dapat tersimpan di riwayat shell atau terlihat oleh proses lain. Tidak ada endpoint pendaftaran publik.',
					'Di phpMyAdmin lokal, buka tabel pengguna dan pilih Insert. Isi username admin_demo, password_hash dengan seluruh output hash yang baru dihasilkan, dan role admin; biarkan id otomatis. Simpan langsung ke database lokal, jangan memasukkan hash atau sandi ke file proyek, log, tangkapan layar publik, atau repository. Untuk akun user dummy gunakan username berbeda dan role user. Hapus data latihan setelah selesai. Contoh membatasi password hingga 72 byte agar sesuai batas bcrypt yang mungkin dipakai PASSWORD_DEFAULT; jangan membuat akun latihan dengan sandi lebih panjang.',
					'Untuk sistem nyata, buat akun awal melalui proses provisioning privat yang diaudit dan gunakan sandi unik yang kuat, bukan perintah atau sandi dummy ini.'
				),
				'file' => 'Terminal lokal — contoh dummy, bukan produksi',
				'code' => <<<'CODE'
php -r 'echo password_hash("LatihanDummy123!", PASSWORD_DEFAULT), PHP_EOL;'
CODE
				, 'explanations' => array(
					'password_hash menghasilkan hash dengan salt acak; password_verify nanti memverifikasinya.',
					'Hanya hash hasilnya yang dimasukkan ke database lokal. Perintah ini sengaja memakai sandi dummy yang tidak rahasia.'
				)
			),
			array(
				'title' => 'Konfigurasi dan route',
				'paragraphs' => array(
					'Pada proyek login terpisah, sesuaikan baris berikut di application/config/config.php, bukan menambahkan file config baru. Bila URL memakai index.php, pertahankan index_page bawaan; contoh kosong memerlukan mod_rewrite dan .htaccess yang berfungsi. Pastikan session files memakai direktori yang dapat ditulis PHP dan tidak dapat diakses melalui web; gunakan sess_save_path yang sesuai bila default PHP tidak memenuhi syarat.',
					'CSRF CI3 memeriksa POST sebelum controller berjalan; jangan mengecualikan login atau logout. Di HTTP localhost cookie_secure harus FALSE. Pada deployment yang benar-benar memakai HTTPS, ubah menjadi TRUE, gunakan HTTPS untuk semua request dan lindungi aplikasi dengan konfigurasi server yang sesuai. Cookie SameSite dan HttpOnly bukan pengganti CSRF.',
					'Tambahkan route berikut di application/config/routes.php tanpa menghapus route lain. Route bukan batas keamanan: URL langsung auth/dashboard dan auth/logout tetap diperiksa di controller. Jangan mengaktifkan CSRF pada proyek AJAX yang sudah ada tanpa menyesuaikan pengiriman dan regenerasi token pada semua request POST-nya.'
				),
				'file' => 'application/config/config.php — baris yang disesuaikan',
				'code' => <<<'CODE'
$config['base_url'] = 'http://localhost/login-demo/';
$config['index_page'] = '';
$config['csrf_protection'] = TRUE;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();
$config['cookie_httponly'] = TRUE;
$config['cookie_samesite'] = 'Lax';
$config['sess_samesite'] = 'Lax';
$config['cookie_secure'] = FALSE; // TRUE jika seluruh aplikasi memakai HTTPS
CODE
				, 'explanations' => array(
					'base_url dan index_page harus sesuai pemasangan lokal dan konfigurasi server.',
					'CSRF aktif dan token diregenerasi; form helper menyisipkan token untuk form POST lokal.',
					'Pengaturan cookie mengurangi risiko pencurian dan pengiriman lintas situs; HTTPS tetap diperlukan di produksi.'
				)
			),
			array(
				'title' => 'Tambahkan route contoh',
				'paragraphs' => array('Tempatkan route sebelum route yang lebih umum agar tidak tertangkap lebih dahulu. Ketiganya menunjuk controller yang sama.'),
				'file' => 'application/config/routes.php — tambahkan',
				'code' => <<<'CODE'
$route['login'] = 'auth/login';
$route['dashboard'] = 'auth/dashboard';
$route['logout'] = 'auth/logout';
CODE
				, 'explanations' => array(
					'login menampilkan dan memproses form; dashboard memeriksa session dan peran.',
					'logout menerima hanya POST; route sendiri tidak membatasi metode HTTP.'
				)
			),
			array(
				'title' => 'Alur, error, dan pengujian',
				'paragraphs' => array(
					'GET login menampilkan form. POST divalidasi di server; username atau password salah menghasilkan pesan umum yang sama, tanpa menampilkan password. Data tidak valid juga tidak menjalankan query. Setelah password_verify berhasil, session ID diregenerasi dan ID pengguna disimpan, lalu browser di-redirect ke dashboard.',
					'Dashboard tanpa session diarahkan ke login. Akun selain admin mendapat 403; akun yang dihapus tidak lagi memiliki akses. Logout hanya menerima POST dan menghancurkan session; GET logout menghasilkan 405 dan tidak mengubah session. POST tanpa token CSRF valid ditolak CI3 sebelum controller, biasanya dengan 403. Token juga memerlukan cookie dan dapat kedaluwarsa; muat ulang halaman jika token lama ditolak.',
					'Kegagalan koneksi atau query database menghasilkan pesan layanan umum 503, bukan pesan sandi salah atau detail SQL. Logout tetap dapat dilakukan saat database tidak tersedia. Controller tidak mencatat sandi. Batasi akses log server dan jangan memasang logging request body yang merekam password. Untuk aplikasi nyata tambahkan pembatasan percobaan login, monitoring, pemulihan akun yang aman, dan perlindungan terhadap enumerasi berbasis waktu; contoh ini bukan sistem produksi.'
				)
			),
			array(
				'title' => 'Kode lengkap MVC — controller Auth',
				'paragraphs' => array(
					'Buat application/controllers/Auth.php pada proyek login. File ini lengkap; helper URL dan form, library session dan form_validation, serta model dan database dimuat sendiri. Jangan menggabungkannya dengan controller Siswa.'
				),
				'file' => 'application/controllers/Auth.php',
				'code' => <<<'CODE'
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
        header('Cache-Control: no-store, private');
    }

    private function load_users()
    {
        try {
            $this->load->database();
            if ( ! $this->db->conn_id) {
                show_error('Layanan sementara tidak tersedia.', 503);
            }
        } catch (Throwable $e) {
            log_message('error', 'Koneksi database login gagal.');
            show_error('Layanan sementara tidak tersedia.', 503);
        }
        $this->load->model('User_model');
    }

    public function login()
    {
        $method = $this->input->method(TRUE);
        if ($method !== 'GET' && $method !== 'POST') {
            header('Allow: GET, POST');
            show_error('Metode tidak diizinkan.', 405);
        }

        $data = array('username' => '', 'error' => '');
        if ($method === 'POST') {
            $username = $this->input->post('username', FALSE);
            $password = $this->input->post('password', FALSE);
            if (is_string($username)) {
                $data['username'] = $username;
            }
            if ( ! is_string($username) || ! is_string($password) || strlen($password) > 72) {
                $data['error'] = 'Username atau password tidak valid.';
            } else {
                $this->form_validation->set_rules(
                    'username', 'Username',
                    'required|max_length[50]|regex_match[/\A[a-zA-Z0-9_]+\z/]'
                );
                $this->form_validation->set_rules(
                    'password', 'Password', 'required|max_length[255]'
                );
                if ($this->form_validation->run() === FALSE) {
                    $data['error'] = 'Username atau password tidak valid.';
                } else {
                    $this->load_users();
                    $user = $this->User_model->find_by_username($username);
                    if ($user === FALSE) {
                        show_error('Layanan sementara tidak tersedia.', 503);
                    }
                    if ($user === NULL || ! password_verify($password, $user['password_hash'])) {
                        $data['error'] = 'Username atau password tidak valid.';
                    } else {
                        $this->session->sess_regenerate(TRUE);
                        $this->session->set_userdata('user_id', (int) $user['id']);
                        redirect('dashboard');
                        return;
                    }
                }
            }
        }
        $this->load->view('auth/login', $data);
    }

    public function dashboard()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            header('Allow: GET');
            show_error('Metode tidak diizinkan.', 405);
        }
        $id = $this->session->userdata('user_id');
        if ( ! $id) {
            redirect('login');
            return;
        }
        $this->load_users();
        $user = $this->User_model->find_by_id((int) $id);
        if ($user === FALSE) {
            show_error('Layanan sementara tidak tersedia.', 503);
        }
        if ($user === NULL) {
            $this->session->sess_destroy();
            redirect('login');
            return;
        }
        if ($user['role'] !== 'admin') {
            show_error('Akses ditolak.', 403);
        }
        $this->load->view('auth/dashboard', array('username' => $user['username']));
    }

    public function logout()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            header('Allow: POST');
            show_error('Metode tidak diizinkan.', 405);
        }
        $this->session->sess_destroy();
        redirect('login');
    }
}
CODE
				, 'explanations' => array(
					'Constructor memuat helper dan library; load_users memuat database dan model hanya saat diperlukan. db_debug FALSE wajib dikonfigurasi agar gangguan database tidak membocorkan detail.',
					'Login hanya menerima GET dan POST, menolak input array dan password lebih dari 72 byte, memvalidasi panjang dan format, serta tidak mengubah atau menyimpan password di session.',
					'Model membedakan query gagal dari akun tidak ditemukan; kegagalan login biasa memakai pesan yang sama. Regenerasi ID session dilakukan sebelum menyimpan identitas.',
					'Dashboard memeriksa ID dan mengambil peran terbaru dari database setiap request, termasuk lewat URL controller langsung.',
					'Logout menolak metode selain POST dan menghancurkan session tanpa bergantung pada database. CSRF diperiksa framework sebelum method dipanggil; no-store membatasi penyimpanan respons di cache.'
				)
			),
			array(
				'title' => 'Kode lengkap MVC — model pengguna',
				'paragraphs' => array('Buat application/models/User_model.php. Query Builder memproses nilai sebagai data; FALSE menandakan query gagal, sedangkan NULL menandakan akun tidak ditemukan.'),
				'file' => 'application/models/User_model.php',
				'code' => <<<'CODE'
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function find_by_username($username)
    {
        try {
            $query = $this->db->select('id, username, password_hash, role')
                ->where('username', $username)->limit(1)->get('pengguna');
            return $query === FALSE ? FALSE : $query->row_array();
        } catch (Throwable $e) {
            log_message('error', 'Query pengguna berdasarkan username gagal.');
            return FALSE;
        }
    }

    public function find_by_id($id)
    {
        try {
            $query = $this->db->select('id, username, role')
                ->where('id', $id)->limit(1)->get('pengguna');
            return $query === FALSE ? FALSE : $query->row_array();
        } catch (Throwable $e) {
            log_message('error', 'Query pengguna berdasarkan ID gagal.');
            return FALSE;
        }
    }
}
CODE
				, 'explanations' => array(
					'find_by_username mengambil hash untuk verifikasi tanpa mencari berdasarkan password.',
					'find_by_id tidak mengambil hash dan menyediakan peran terbaru untuk otorisasi.',
					'Kegagalan query atau exception diteruskan sebagai FALSE; row_array menghasilkan NULL jika tidak ada baris.'
				)
			),
			array(
				'title' => 'Kode lengkap MVC — view login',
				'paragraphs' => array('Buat direktori application/views/auth dan file login.php berikut. CSS sederhana disertakan agar contoh dapat berjalan tanpa aset eksternal.'),
				'file' => 'application/views/auth/login.php',
				'code' => <<<'CODE'
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login latihan</title>
    <style>
        body { font: 16px sans-serif; background: #f1f5f9; padding: 2rem; }
        main { max-width: 24rem; margin: auto; background: white; padding: 2rem; }
        label, input { display: block; margin: .5rem 0; }
        input { box-sizing: border-box; width: 100%; padding: .6rem; }
        button { padding: .6rem 1rem; cursor: pointer; }
        .error { color: #a00000; }
    </style>
</head>
<body>
<main>
    <h1>Login latihan</h1>
    <?php if ($error !== ''): ?>
        <p class="error" role="alert"><?= html_escape($error) ?></p>
    <?php endif; ?>
    <?= form_open('login') ?>
        <label for="username">Username</label>
        <input id="username" name="username" maxlength="50" required
               autocomplete="username" value="<?= html_escape($username) ?>">
        <label for="password">Password</label>
        <input id="password" name="password" type="password" maxlength="255"
               required autocomplete="current-password">
        <button type="submit">Masuk</button>
    <?= form_close() ?>
    <?php if ($this->session->userdata('user_id')): ?>
        <?= form_open('logout') ?>
            <button type="submit">Keluar dari session saat ini</button>
        <?= form_close() ?>
    <?php endif; ?>
</main>
</body>
</html>
CODE
				, 'explanations' => array(
					'form_open menghasilkan form POST beserta hidden token CSRF ketika perlindungan aktif.',
					'Username dan pesan di-escape saat ditampilkan; password tidak diisi kembali setelah gagal.',
					'Form logout tambahan memungkinkan pengguna non-admin mengakhiri session dari halaman login.'
				)
			),
			array(
				'title' => 'Kode lengkap MVC — view dashboard',
				'paragraphs' => array('Buat application/views/auth/dashboard.php. View ini hanya dipanggil setelah pemeriksaan admin di controller, bukan sebagai pengganti pemeriksaan tersebut.'),
				'file' => 'application/views/auth/dashboard.php',
				'code' => <<<'CODE'
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard admin</title>
    <style>
        body { font: 16px sans-serif; background: #f1f5f9; padding: 2rem; }
        main { max-width: 30rem; margin: auto; background: white; padding: 2rem; }
        button { padding: .6rem 1rem; cursor: pointer; }
    </style>
</head>
<body>
<main>
    <h1>Dashboard admin</h1>
    <p>Selamat datang, <?= html_escape($username) ?>.</p>
    <p>Ini hanya contoh lokal, bukan akses ke CRUD siswa.</p>
    <?= form_open('logout') ?>
        <button type="submit">Keluar</button>
    <?= form_close() ?>
</main>
</body>
</html>
CODE
				, 'explanations' => array(
					'Username dari database di-escape sebagai teks HTML.',
					'Logout memakai form POST dengan token CSRF, bukan tautan GET.'
				)
			)
		),
		'exercise' => array(
			'title' => 'Uji login dan batas dashboard',
			'intro' => 'Gunakan hanya akun dan data dummy pada proyek lokal terpisah.',
			'steps' => array(
				'Buka dashboard tanpa session dan pastikan diarahkan ke login; masuk sebagai admin dummy dan pastikan dashboard tampil.',
				'Coba username salah dan password salah, input kosong dan input array; pastikan gagal tanpa menampilkan sandi atau detail database.',
				'Masuk sebagai user dummy dan pastikan dashboard maupun auth/dashboard mengembalikan 403; ubah peran atau hapus akun saat session aktif lalu uji kembali.',
				'Coba GET logout dan POST tanpa token atau dengan token salah: session tidak boleh berakhir. Kirim form logout yang valid, lalu pastikan dashboard kembali meminta login.',
				'Uji gangguan database dengan db_debug FALSE dan display_errors nonaktif; respons harus berupa pesan umum. Pastikan CRUD Siswa tetap terpisah dan belum terlindungi.'
			),
			'criteria' => 'Hanya admin terautentikasi dapat membuka dashboard; logout hanya berhasil melalui POST dengan token valid, dan error tidak membocorkan rahasia.'
		),
		'review' => array(
			array('question' => 'Mengapa dashboard membaca peran dari database lagi?', 'answer' => 'Agar perubahan peran atau penghapusan akun berlaku pada request berikutnya, bukan menunggu session kedaluwarsa.'),
			array('question' => 'Apakah contoh ini melindungi endpoint Siswa AJAX?', 'answer' => 'Tidak. Setiap endpoint Siswa harus diberi autentikasi dan otorisasi di server serta penanganan CSRF AJAX yang sesuai secara terpisah.')
		),
		'resources' => array(
			array('title' => 'Session CI3', 'url' => 'https://codeigniter.com/userguide3/libraries/sessions.html'),
			array('title' => 'CSRF CI3', 'url' => 'https://codeigniter.com/userguide3/libraries/security.html'),
			array('title' => 'PHP password hashing', 'url' => 'https://www.php.net/manual/en/function.password-hash.php')
		)
	)
);
