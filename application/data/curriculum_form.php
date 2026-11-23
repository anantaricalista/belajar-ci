<?php
defined('BASEPATH') OR exit('No direct script access allowed');

return array(
	'form-validasi' => array(
		'category' => 'MEMBANGUN FITUR',
		'title' => 'Form & validasi',
		'duration' => 5,
		'summary' => 'Buat form tambah siswa dengan POST biasa, validasi server, dan penyimpanan melalui model.',
		'goals' => array(
			'Membuat form POST dengan form helper dan mempertahankan isian saat validasi gagal.',
			'Memvalidasi semua field termasuk keunikan NIS di server.',
			'Menyimpan data valid melalui model dan menggunakan Post/Redirect/Get.'
		),
		'sections' => array(
			array(
				'title' => 'Mulai dari daftar dan detail yang sudah ada',
				'paragraphs' => array(
					'Lanjutkan proyek CI3 di XAMPP dengan Apache dan MySQL aktif. Proyek berada di htdocs/crud-siswa, base_url adalah http://localhost/crud-siswa/, index_page kosong, dan .htaccess serta mod_rewrite sudah bekerja. Koneksi database.php harus mengarah ke db_siswa.',
					'Tabel siswa tahap sebelumnya memiliki id otomatis, nis unik VARCHAR(20), nama VARCHAR(100), kelas VARCHAR(30), jenis_kelamin ENUM L/P, dan created_at dengan nilai default. Model sebelumnya memiliki semua() dan cari(); controller menyediakan index dan detail. Tahap ini mempertahankan keduanya dan menambahkan create.',
					'Form ini memakai halaman dan POST biasa, bukan AJAX atau modal. Gunakan hanya data fiktif: contoh belum memiliki autentikasi maupun otorisasi.'
				),
				'file' => 'application/config/routes.php — route yang sudah ada',
				'code' => <<<'CODE'
// Daftar memakai route ini; detail hanya cocok untuk ID numerik.
// create bukan angka sehingga ditangani routing default jika tidak ada route lain yang menangkapnya.
$route['siswa'] = 'siswa/index';
$route['siswa/(:num)'] = 'siswa/detail/$1';
CODE

			),
			array(
				'title' => 'Alur GET, POST, dan redirect',
				'paragraphs' => array(
					'Buka http://localhost/crud-siswa/siswa/create dengan GET untuk melihat form. Browser mengirim POST ke alamat yang sama. Controller menjalankan validasi sebelum insert: jika gagal, tampilkan lagi form beserta pesan dan nilai sebelumnya; jika berhasil, simpan lalu redirect ke daftar.',
					'Pola Post/Redirect/Get mengurangi pengiriman ulang ketika halaman daftar di-refresh. Tidak ada flashdata atau ketergantungan session untuk pesan sukses.'
				),
				'file' => 'Alur request',
				'code' => <<<'CODE'
// GET hanya menampilkan form tanpa mengubah database.
GET  siswa/create -> tampilkan form
// Validasi gagal tidak menyimpan data; form dan kesalahan ditampilkan lagi.
POST siswa/create -> validasi gagal -> tampilkan form dan kesalahan
// Insert sukses diikuti redirect agar browser meminta daftar dengan GET.
POST siswa/create -> validasi berhasil -> insert -> redirect siswa
CODE

			),
			array(
				'title' => 'Aturan validasi di server',
				'paragraphs' => array(
					'Library form_validation memeriksa input sekalipun atribut HTML diubah atau request dikirim langsung. Aturan panjang mengikuti kolom database; NIS harus unik dan jenis kelamin hanya boleh L atau P. Tiga field teks wajib memiliki karakter selain spasi, lalu trim menghapus spasi di tepinya.',
					'is_unique memerlukan koneksi database aktif. Indeks UNIQUE tetap diperlukan sebagai pertahanan terakhir, termasuk jika dua request bersamaan lolos pemeriksaan sebelum insert. Pada db_debug aktif CI3 dapat menampilkan error database saat insert gagal; matikan db_debug di lingkungan produksi dan jangan tampilkan detail SQL atau kredensial.'
				),
				'file' => 'Bagian dari Siswa::create()',
				'code' => <<<'CODE'
// required dan regex_match menolak input kosong atau hanya spasi; trim dan max_length menyesuaikan data.
// NIS juga harus unik; constraint UNIQUE tetap diperlukan di database.
$this->form_validation->set_rules('nis', 'NIS', 'required|regex_match[/\S/]|trim|max_length[20]|is_unique[siswa.nis]');
$this->form_validation->set_rules('nama', 'Nama', 'required|regex_match[/\S/]|trim|max_length[100]');
$this->form_validation->set_rules('kelas', 'Kelas', 'required|regex_match[/\S/]|trim|max_length[30]');
// Pilihan dibatasi ke nilai enum L dan P; validasi tidak menggantikan escaping output.
$this->form_validation->set_rules('jenis_kelamin', 'Jenis kelamin', 'required|in_list[L,P]');
CODE

			),
			array(
				'title' => 'Form helper, CSRF, dan nilai sebelumnya',
				'paragraphs' => array(
					'Muat helper form dan url serta library form_validation di controller. form_open membuat form POST ke siswa/create dan menyisipkan token CSRF ketika csrf_protection aktif di config CI3. Token diperiksa CI3 saat POST; untuk pengujian CSRF aktif, cookie browser harus diizinkan.',
					'Gunakan label dan name yang sesuai aturan. set_value mengembalikan isian setelah gagal; untuk input HTML di bawah, parameter ketiga FALSE menonaktifkan escaping bawaan set_value agar html_escape dilakukan tepat sekali pada output. Jangan meng-escape data sebelum disimpan.'
				),
				'file' => 'Cuplikan view siswa/create.php',
				'code' => <<<'CODE'
<!-- Pesan memakai delimiter HTML standar CI3 dan label aturan yang tetap. -->
<?= validation_errors() ?>
<!-- form_open membuat POST dan token CSRF jika aktif; form_close menutup form. -->
<?= form_open('siswa/create') ?>
<label for="nis">NIS</label>
<!-- set_value mempertahankan isian; html_escape mengamankan atribut tepat sekali. -->
<input id="nis" name="nis" value="<?= html_escape(set_value('nis', '', FALSE)) ?>">
<?= form_close() ?>
CODE

			),
			array(
				'title' => 'Simpan hanya field yang diizinkan',
				'paragraphs' => array(
					'Setelah run() berhasil, ambil empat field yang divalidasi secara eksplisit, bukan seluruh POST. ID dan created_at disediakan database. Query Builder memproses nilai sebagai data; escaping HTML tetap dilakukan di view.',
					'Jika insert mengembalikan FALSE dengan db_debug nonaktif, tampilkan pesan umum dan tetap pada form. Pesan ini tidak menggunakan session dan tidak membocorkan detail database.'
				),
				'file' => 'Bagian dari Siswa_model.php',
				'code' => <<<'CODE'
// Controller mengirim hanya nis, nama, kelas, dan jenis_kelamin; database menyediakan id dan created_at.
// Query Builder mengembalikan hasil insert untuk diperiksa controller.
public function tambah($data)
{
    return $this->db->insert('siswa', $data);
}
CODE

			),
			array(
				'title' => 'Menuju jQuery dan modal',
				'paragraphs' => array(
					'Pastikan alur form biasa bekerja dulu. Pada tahap berikutnya, form dapat dipindah ke modal Bootstrap dan dikirim memakai jQuery AJAX; server tetap wajib menjalankan validasi, mempertahankan constraint database, dan memeriksa CSRF.',
					'Endpoint AJAX nantinya memerlukan respons JSON dan penanganan token CSRF yang diregenerasi. Snapshot berikut sengaja menghasilkan HTML dan redirect, sehingga tidak langsung menjadi endpoint AJAX.'
				)
			),
			array(
				'title' => 'Snapshot lengkap: controller Siswa.php',
				'paragraphs' => array(
					'Mulai di sini hingga akhir sections, setiap blok adalah isi lengkap file yang disebutkan. Ganti isi file proyek latihan dengan blok yang sesuai, bukan menempelkannya di dalam class lama. Controller ini memuat sendiri database, model, helper, dan library yang dibutuhkan.'
				),
				'file' => 'application/controllers/Siswa.php',
				'code' => <<<'CODE'
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa extends CI_Controller {
    // Muat dependensi yang diperlukan daftar, detail, dan form.
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Siswa_model');
        $this->load->helper(array('url', 'form'));
        $this->load->library('form_validation');
    }

    // Tampilkan daftar dari model.
    public function index()
    {
        $data['siswa'] = $this->Siswa_model->semua();
        $this->load->view('siswa/index', $data);
    }

    // Periksa ID dan tampilkan 404 jika ID tidak valid atau siswa tidak ditemukan.
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

    // GET menampilkan form; selain GET dan POST ditolak.
    public function create()
    {
        $method = $this->input->method();
        if ($method !== 'get' && $method !== 'post')
        {
            show_error('Metode tidak diizinkan.', 405);
            return;
        }

        $data['simpan_error'] = '';
        if ($method === 'post')
        {
            // Validasi field di server; NIS unik dan jenis kelamin hanya L atau P.
            $this->form_validation->set_rules('nis', 'NIS', 'required|regex_match[/\S/]|trim|max_length[20]|is_unique[siswa.nis]');
            $this->form_validation->set_rules('nama', 'Nama', 'required|regex_match[/\S/]|trim|max_length[100]');
            $this->form_validation->set_rules('kelas', 'Kelas', 'required|regex_match[/\S/]|trim|max_length[30]');
            $this->form_validation->set_rules('jenis_kelamin', 'Jenis kelamin', 'required|in_list[L,P]');

            if ($this->form_validation->run() !== FALSE)
            {
                // Hanya empat field yang divalidasi dikirim ke model.
                $siswa = array(
                    'nis' => $this->input->post('nis'),
                    'nama' => $this->input->post('nama'),
                    'kelas' => $this->input->post('kelas'),
                    'jenis_kelamin' => $this->input->post('jenis_kelamin')
                );
                // Insert sukses diikuti redirect; kegagalan memakai pesan umum.
                if ($this->Siswa_model->tambah($siswa))
                {
                    redirect('siswa');
                    return;
                }
                $data['simpan_error'] = 'Data belum dapat disimpan. Silakan coba lagi.';
            }
        }

        // GET, validasi gagal, dan insert gagal menampilkan form.
        $this->load->view('siswa/create', $data);
    }
}
CODE

			),
			array(
				'title' => 'Snapshot lengkap: model Siswa_model.php',
				'paragraphs' => array('Ganti seluruh isi model. Metode semua() dan cari() tetap tersedia untuk daftar dan detail; tambah() dipakai oleh controller setelah validasi.'),
				'file' => 'application/models/Siswa_model.php',
				'code' => <<<'CODE'
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa_model extends CI_Model {
    // Ambil semua siswa menurut nama sebagai daftar array.
    public function semua()
    {
        return $this->db->order_by('nama', 'ASC')
            ->get('siswa')->result_array();
    }

    // Cari berdasarkan ID dan kembalikan satu baris atau NULL.
    public function cari($id)
    {
        return $this->db->get_where('siswa', array('id' => $id))
            ->row_array();
    }

    // Kembalikan hasil insert Query Builder kepada controller.
    public function tambah($data)
    {
        return $this->db->insert('siswa', $data);
    }
}
CODE

			),
			array(
				'title' => 'Snapshot lengkap: view daftar',
				'paragraphs' => array('Ganti seluruh isi view daftar. Tautan tambah membuka form, dan tautan detail menggunakan route numerik yang telah ada.'),
				'file' => 'application/views/siswa/index.php',
				'code' => <<<'CODE'
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar siswa</title>
</head>
<body>
    <h1>Daftar siswa</h1>
    <!-- site_url mengikuti konfigurasi proyek; tautan ini membuka form tambah. -->
    <p><a href="<?= html_escape(site_url('siswa/create')) ?>">Tambah siswa</a></p>
    <!-- Tampilkan pesan saat kosong atau tabel berisi nilai yang di-escape. -->
    <?php if (empty($siswa)): ?>
        <p>Belum ada siswa.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr><th>NIS</th><th>Nama</th><th>Kelas</th><th>Jenis kelamin</th><th>Detail</th></tr>
            </thead>
            <tbody>
            <?php foreach ($siswa as $item): ?>
                <tr>
                    <td><?= html_escape($item['nis']) ?></td>
                    <td><?= html_escape($item['nama']) ?></td>
                    <td><?= html_escape($item['kelas']) ?></td>
                    <td><?= html_escape($item['jenis_kelamin']) ?></td>
                    <!-- ID pada tautan detail cocok dengan route numerik. -->
                    <td><a href="<?= html_escape(site_url('siswa/'.$item['id'])) ?>">Lihat</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
CODE

			),
			array(
				'title' => 'Snapshot lengkap: view detail',
				'paragraphs' => array('Ganti seluruh isi view detail. Controller hanya memuatnya setelah siswa ditemukan.'),
				'file' => 'application/views/siswa/detail.php',
				'code' => <<<'CODE'
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Detail siswa</title>
</head>
<body>
    <h1>Detail siswa</h1>
    <!-- Controller memberikan satu siswa; escape setiap kolom saat ditampilkan. -->
    <dl>
        <dt>ID</dt><dd><?= html_escape($siswa['id']) ?></dd>
        <dt>NIS</dt><dd><?= html_escape($siswa['nis']) ?></dd>
        <dt>Nama</dt><dd><?= html_escape($siswa['nama']) ?></dd>
        <dt>Kelas</dt><dd><?= html_escape($siswa['kelas']) ?></dd>
        <dt>Jenis kelamin</dt><dd><?= html_escape($siswa['jenis_kelamin']) ?></dd>
        <dt>Dibuat</dt><dd><?= html_escape($siswa['created_at']) ?></dd>
    </dl>
    <!-- site_url mengikuti konfigurasi proyek dan URL di-escape untuk atribut HTML. -->
    <p><a href="<?= html_escape(site_url('siswa')) ?>">Kembali ke daftar</a></p>
</body>
</html>
CODE

			),
			array(
				'title' => 'Snapshot lengkap: view form tambah',
				'paragraphs' => array('Buat atau ganti seluruh isi view create. Form mengirim POST biasa dan menampilkan kesalahan validasi maupun kegagalan penyimpanan. Semua field memiliki label dan mempertahankan nilai saat gagal.'),
				'file' => 'application/views/siswa/create.php',
				'code' => <<<'CODE'
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Tambah siswa</title>
</head>
<body>
    <h1>Tambah siswa</h1>
    <!-- Tampilkan kesalahan validasi atau pesan umum ketika insert gagal. -->
    <?= validation_errors() ?>
    <?php if ($simpan_error !== ''): ?>
        <p><?= html_escape($simpan_error) ?></p>
    <?php endif; ?>
    <!-- form_open membuat POST dan token CSRF jika aktif. -->
    <?= form_open('siswa/create') ?>
        <!-- Label, id, dan name cocok dengan aturan; nilai input di-escape sekali setelah set_value. -->
        <!-- maxlength membantu pengguna; server tetap memeriksa panjang dan field wajib. -->
        <p>
            <label for="nis">NIS</label>
            <input id="nis" name="nis" type="text" maxlength="20" value="<?= html_escape(set_value('nis', '', FALSE)) ?>">
        </p>
        <p>
            <label for="nama">Nama</label>
            <input id="nama" name="nama" type="text" maxlength="100" value="<?= html_escape(set_value('nama', '', FALSE)) ?>">
        </p>
        <p>
            <label for="kelas">Kelas</label>
            <input id="kelas" name="kelas" type="text" maxlength="30" value="<?= html_escape(set_value('kelas', '', FALSE)) ?>">
        </p>
        <!-- Nilai select hanya dibandingkan dengan L atau P, bukan dicetak. -->
        <p>
            <label for="jenis_kelamin">Jenis kelamin</label>
            <select id="jenis_kelamin" name="jenis_kelamin">
                <option value="">Pilih jenis kelamin</option>
                <option value="L"<?= set_value('jenis_kelamin', '', FALSE) === 'L' ? ' selected' : '' ?>>Laki-laki</option>
                <option value="P"<?= set_value('jenis_kelamin', '', FALSE) === 'P' ? ' selected' : '' ?>>Perempuan</option>
            </select>
        </p>
        <!-- Tombol mengirim form; tautan kembali di bawah tidak mengubah data. -->
        <button type="submit">Simpan</button>
    <?= form_close() ?>
    <p><a href="<?= html_escape(site_url('siswa')) ?>">Kembali ke daftar</a></p>
</body>
</html>
CODE

			)
		),
		'exercise' => array(
			'title' => 'Uji tambah siswa tanpa AJAX',
			'intro' => 'Gunakan data fiktif dan jalankan proyek di XAMPP dengan database tahap sebelumnya.',
			'steps' => array(
				'Salin kelima snapshot ke file proyek masing-masing, lalu buka daftar dan form tambah.',
				'Kirim form kosong, NIS yang sudah ada, teks terlalu panjang, dan jenis kelamin selain L/P melalui request langsung; pastikan kesalahan tampil dan tidak ada baris baru.',
				'Kirim data valid, pastikan redirect ke daftar dan buka detail siswa baru. Refresh daftar dan pastikan tidak ada insert ulang.',
				'Aktifkan CSRF di konfigurasi lokal, muat ulang form dan uji POST dengan token; coba POST tanpa token dan pastikan ditolak.'
			),
			'criteria' => 'Empat field divalidasi di server, nilai dipertahankan saat gagal, hanya data valid tersimpan, daftar dan detail tetap berfungsi, serta POST sukses diikuti redirect.'
		),
		'review' => array(
			array('question' => 'Mengapa siswa/create tidak ditangkap route siswa/(:num)?', 'answer' => '(:num) hanya cocok untuk angka; create ditangani routing default CI3 selama tidak ada route lain yang menangkapnya.'),
			array('question' => 'Mengapa NIS diperiksa di validasi dan juga diberi UNIQUE di database?', 'answer' => 'Validasi memberi pesan yang mudah dipahami; constraint database tetap mencegah duplikat termasuk pada request bersamaan.'),
			array('question' => 'Mengapa redirect setelah POST sukses?', 'answer' => 'Post/Redirect/Get mengurangi risiko form dikirim ulang ketika halaman daftar di-refresh.'),
			array('question' => 'Apakah validasi menggantikan escaping atau CSRF?', 'answer' => 'Tidak. Escape sesuai konteks output, gunakan query yang aman, dan sertakan token CSRF ketika perlindungan aktif.')
		),
		'resources' => array(
			array('title' => 'Form validation CI3', 'url' => 'https://codeigniter.com/userguide3/libraries/form_validation.html'),
			array('title' => 'Form helper CI3', 'url' => 'https://codeigniter.com/userguide3/helpers/form_helper.html'),
			array('title' => 'Routing CI3', 'url' => 'https://codeigniter.com/userguide3/general/routing.html')
		)
	)
);
