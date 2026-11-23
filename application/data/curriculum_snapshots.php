<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$controller_views = <<<'CODE'
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa extends CI_Controller
{
    public function index()
    {
        $data['siswa'] = array(
            array('nis' => 'RPL001', 'nama' => 'Alya Contoh', 'kelas' => 'X RPL 1'),
            array('nis' => 'RPL002', 'nama' => 'Bima Contoh', 'kelas' => 'X RPL 1')
        );
        $this->load->view('siswa/index', $data);
    }

    public function detail($id = NULL)
    {
        if ($id === NULL || ! ctype_digit((string) $id))
        {
            show_404();
            return;
        }
        echo 'Data siswa nomor '.html_escape($id);
    }
}
CODE;

$view_static = <<<'CODE'
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data siswa</title>
</head>
<body>
    <h1>Data Siswa SMK</h1>
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
        $data['siswa'] = $this->Siswa_model->semua();
        $this->load->view('siswa/index', $data);
    }

    public function detail($id = NULL)
    {
        if ($id === NULL || ! ctype_digit((string) $id))
        {
            show_404();
            return;
        }
        $data['siswa'] = $this->Siswa_model->cari($id);
        if ( ! $data['siswa'])
        {
            show_404();
            return;
        }
        $this->load->view('siswa/detail', $data);
    }
}
CODE;

$model_database = <<<'CODE'
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa_model extends CI_Model
{
    public function semua()
    {
        return $this->db->order_by('nama', 'ASC')
            ->get('siswa')->result_array();
    }

    public function cari($id)
    {
        return $this->db->get_where('siswa', array('id' => $id))
            ->row_array();
    }
}
CODE;

$view_database = <<<'CODE'
<!doctype html>
<html lang="id">
<head><meta charset="utf-8"><title>Data siswa</title></head>
<body>
    <h1>Data Siswa SMK</h1>
    <table border="1">
        <thead><tr><th>NIS</th><th>Nama</th><th>Kelas</th><th>Detail</th></tr></thead>
        <tbody>
        <?php foreach ($siswa as $item): ?>
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
<html lang="id">
<head><meta charset="utf-8"><title>Detail siswa</title></head>
<body>
    <h1>Detail siswa</h1>
    <p>NIS: <?= html_escape($siswa['nis']) ?></p>
    <p>Nama: <?= html_escape($siswa['nama']) ?></p>
    <p>Kelas: <?= html_escape($siswa['kelas']) ?></p>
    <p>Jenis kelamin: <?= html_escape($siswa['jenis_kelamin']) ?></p>
    <a href="<?= html_escape(site_url('siswa')) ?>">Kembali ke daftar</a>
</body>
</html>
CODE;

return array(
	'views' => array(
		array('file' => 'application/controllers/Siswa.php', 'code' => $controller_views, 'explanations' => array('index menyiapkan data sementara lalu memuat view; detail tetap seperti modul sebelumnya.')),
		array('file' => 'application/views/siswa/index.php', 'code' => $view_static, 'explanations' => array('Dokumen HTML menampilkan setiap siswa dalam daftar; html_escape mengamankan nilai untuk HTML.'))
	),
	'database-model' => array(
		array('file' => 'application/models/Siswa_model.php', 'code' => $model_database, 'explanations' => array('semua mengambil daftar dari MySQL; cari mengambil satu siswa berdasarkan ID.')),
		array('file' => 'application/controllers/Siswa.php', 'code' => $controller_database, 'explanations' => array('Constructor memuat helper, database dan model. index memuat daftar; detail memeriksa ID dan menampilkan 404 jika data tidak ada.')),
		array('file' => 'application/views/siswa/index.php', 'code' => $view_database, 'explanations' => array('Tabel mengulang data dari model; tautan detail menggunakan ID dan site_url.')),
		array('file' => 'application/views/siswa/detail.php', 'code' => $view_detail, 'explanations' => array('View detail menampilkan field dengan escaping dan menyediakan tautan kembali.'))
	)
);
