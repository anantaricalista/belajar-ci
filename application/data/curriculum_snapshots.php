<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$controller_views = <<<'CODE'
<?php
// Cegah file controller diakses langsung di luar CodeIgniter.
defined('BASEPATH') OR exit('No direct script access allowed');

// Controller menerima permintaan untuk daftar dan detail siswa.
class Siswa extends CI_Controller
{
    // Siapkan data contoh, lalu kirimkan ke view daftar.
    public function index()
    {
        $data['siswa'] = array(
            array('nis' => 'RPL001', 'nama' => 'Alya Contoh', 'kelas' => 'X RPL 1'),
            array('nis' => 'RPL002', 'nama' => 'Bima Contoh', 'kelas' => 'X RPL 1')
        );
        // Kunci 'siswa' tersedia sebagai variabel $siswa di view.
        $this->load->view('siswa/index', $data);
    }

    // Tampilkan nomor siswa hanya jika ID berupa angka.
    public function detail($id = NULL)
    {
        // ID kosong atau bukan digit menghasilkan halaman 404.
        if ($id === NULL || ! ctype_digit((string) $id))
        {
            show_404();
            return;
        }
        // Escape nilai sebelum menampilkannya dalam HTML.
        echo 'Data siswa nomor '.html_escape($id);
    }
}
CODE;

$view_static = <<<'CODE'
<!doctype html>
<!-- Atur bahasa dokumen dan metadata halaman daftar. -->
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data siswa</title>
</head>
<body>
    <h1>Data Siswa SMK</h1>
    <!-- Ulangi data dari controller; escape setiap nilai agar aman dalam HTML. -->
    <ul>
    <?php foreach ($siswa as $item): ?>
        <li><?= html_escape($item['nis']) ?> -
            <?= html_escape($item['nama']) ?>,
            <?= html_escape($item['kelas']) ?></li>
    <?php endforeach; ?>
    </ul>
</body>
</html>
CODE;

$controller_database = <<<'CODE'
<?php
// Cegah akses langsung ke file controller.
defined('BASEPATH') OR exit('No direct script access allowed');

// Controller menghubungkan model siswa dengan view.
class Siswa extends CI_Controller
{
    // Siapkan helper URL, koneksi database, dan model untuk setiap aksi.
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->model('Siswa_model');
    }

    // Ambil semua siswa dari model dan kirimkan ke view daftar.
    public function index()
    {
        $data['siswa'] = $this->Siswa_model->semua();
        $this->load->view('siswa/index', $data);
    }

    // Cari satu siswa berdasarkan ID pada URL.
    public function detail($id = NULL)
    {
        // Tolak ID yang kosong atau bukan angka.
        if ($id === NULL || ! ctype_digit((string) $id))
        {
            show_404();
            return;
        }
        // Tampilkan 404 jika ID valid tetapi siswa tidak ditemukan.
        $data['siswa'] = $this->Siswa_model->cari($id);
        if ( ! $data['siswa'])
        {
            show_404();
            return;
        }
        // Kirim data siswa yang ditemukan ke view detail.
        $this->load->view('siswa/detail', $data);
    }
}
CODE;

$model_database = <<<'CODE'
<?php
// Cegah akses langsung ke file model.
defined('BASEPATH') OR exit('No direct script access allowed');

// Model menyediakan operasi pembacaan tabel siswa.
class Siswa_model extends CI_Model
{
    // Ambil seluruh baris sebagai array, diurutkan menurut nama.
    public function semua()
    {
        return $this->db->order_by('nama', 'ASC')
            ->get('siswa')->result_array();
    }

    // Ambil satu baris berdasarkan ID, atau hasil kosong jika tidak ditemukan.
    public function cari($id)
    {
        return $this->db->get_where('siswa', array('id' => $id))
            ->row_array();
    }
}
CODE;

$view_database = <<<'CODE'
<!doctype html>
<!-- Atur bahasa dan metadata halaman daftar. -->
<html lang="id">
<head><meta charset="utf-8"><title>Data siswa</title></head>
<body>
    <h1>Data Siswa SMK</h1>
    <!-- Siapkan judul kolom untuk data dan tautan detail setiap siswa. -->
    <table border="1">
        <thead><tr><th>NIS</th><th>Nama</th><th>Kelas</th><th>Detail</th></tr></thead>
        <tbody>
        <!-- Ulangi setiap siswa yang dikirim controller. -->
        <?php foreach ($siswa as $item): ?>
            <!-- Escape nilai sel; URL detail dibuat dengan site_url dan di-escape untuk atribut href. -->
            <tr>
                <td><?= html_escape($item['nis']) ?></td>
                <td><?= html_escape($item['nama']) ?></td>
                <td><?= html_escape($item['kelas']) ?></td>
                <td><a href="<?= html_escape(site_url('siswa/'.$item['id'])) ?>">Lihat</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
CODE;

$view_detail = <<<'CODE'
<!doctype html>
<!-- Atur bahasa dan metadata halaman detail. -->
<html lang="id">
<head><meta charset="utf-8"><title>Detail siswa</title></head>
<body>
    <h1>Detail siswa</h1>
    <!-- Tampilkan field siswa dari controller dengan escaping untuk HTML. -->
    <p>NIS: <?= html_escape($siswa['nis']) ?></p>
    <p>Nama: <?= html_escape($siswa['nama']) ?></p>
    <p>Kelas: <?= html_escape($siswa['kelas']) ?></p>
    <p>Jenis kelamin: <?= html_escape($siswa['jenis_kelamin']) ?></p>
    <!-- Buat URL daftar dengan site_url dan escape sebelum memasukkannya ke atribut href. -->
    <a href="<?= html_escape(site_url('siswa')) ?>">Kembali ke daftar</a>
</body>
</html>
CODE;

return array(
	'views' => array(
		array('file' => 'application/controllers/Siswa.php', 'code' => $controller_views),
		array('file' => 'application/views/siswa/index.php', 'code' => $view_static)
	),
	'database-model' => array(
		array('file' => 'application/models/Siswa_model.php', 'code' => $model_database),
		array('file' => 'application/controllers/Siswa.php', 'code' => $controller_database),
		array('file' => 'application/views/siswa/index.php', 'code' => $view_database),
		array('file' => 'application/views/siswa/detail.php', 'code' => $view_detail)
	)
);
