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
$route['siswa'] = 'siswa/index';
$route['siswa/(:num)'] = 'siswa/detail/$1';
CODE
				, 'explanations' => array(
					'Route pertama membuka daftar, sedangkan (:num) hanya menerima angka untuk detail.',
					'siswa/create bukan angka, sehingga tidak cocok dengan route detail; routing default CI3 memanggil method create pada controller Siswa. Tidak perlu menambahkan route create jika tidak ada route lain yang menangkapnya.'
				)
			),
			array(
				'title' => 'Alur GET, POST, dan redirect',
				'paragraphs' => array(
					'Buka http://localhost/crud-siswa/siswa/create dengan GET untuk melihat form. Browser mengirim POST ke alamat yang sama. Controller menjalankan validasi sebelum insert: jika gagal, tampilkan lagi form beserta pesan dan nilai sebelumnya; jika berhasil, simpan lalu redirect ke daftar.',
					'Pola Post/Redirect/Get mengurangi pengiriman ulang ketika halaman daftar di-refresh. Tidak ada flashdata atau ketergantungan session untuk pesan sukses.'
				),
				'file' => 'Alur request',
				'code' => <<<'CODE'
GET  siswa/create -> tampilkan form
POST siswa/create -> validasi gagal -> tampilkan form dan kesalahan
POST siswa/create -> validasi berhasil -> insert -> redirect siswa
CODE
				, 'explanations' => array(
					'GET hanya menampilkan form dan tidak mengubah database.',
					'POST yang gagal tidak memanggil model untuk menyimpan.',
					'Setelah insert sukses, browser menerima redirect dan meminta halaman daftar dengan GET.'
				)
			),
			array(
				'title' => 'Aturan validasi di server',
				'paragraphs' => array(
					'Library form_validation memeriksa input sekalipun atribut HTML diubah atau request dikirim langsung. Aturan panjang mengikuti kolom database; NIS harus unik dan jenis kelamin hanya boleh L atau P. Tiga field teks wajib memiliki karakter selain spasi, lalu trim menghapus spasi di tepinya.',
					'is_unique memerlukan koneksi database aktif. Indeks UNIQUE tetap diperlukan sebagai pertahanan terakhir, termasuk jika dua request bersamaan lolos pemeriksaan sebelum insert. Pada db_debug aktif CI3 dapat menampilkan error database saat insert gagal; matikan db_debug di lingkungan produksi dan jangan tampilkan detail SQL atau kredensial.'
				),
				'file' => 'Bagian dari Siswa::create()',
				'code' => <<<'CODE'
$this->form_validation->set_rules('nis', 'NIS', 'required|regex_match[/\S/]|trim|max_length[20]|is_unique[siswa.nis]');
$this->form_validation->set_rules('nama', 'Nama', 'required|regex_match[/\S/]|trim|max_length[100]');
$this->form_validation->set_rules('kelas', 'Kelas', 'required|regex_match[/\S/]|trim|max_length[30]');
$this->form_validation->set_rules('jenis_kelamin', 'Jenis kelamin', 'required|in_list[L,P]');
CODE
				, 'explanations' => array(
					'required menolak nilai kosong; regex_match menolak teks yang hanya berisi whitespace sebelum trim, dan max_length menyesuaikan batas kolom.',
					'is_unique[siswa.nis] menolak NIS yang sudah tersimpan; constraint UNIQUE juga melindungi database.',
					'in_list membatasi jenis_kelamin ke nilai enum L dan P.',
					'Aturan diterapkan di server; validasi tidak menggantikan escaping output.'
				)
			),
			array(
				'title' => 'Form helper, CSRF, dan nilai sebelumnya',
				'paragraphs' => array(
					'Muat helper form dan url serta library form_validation di controller. form_open membuat form POST ke siswa/create dan menyisipkan token CSRF ketika csrf_protection aktif di config CI3. Token diperiksa CI3 saat POST; untuk pengujian CSRF aktif, cookie browser harus diizinkan.',
					'Gunakan label dan name yang sesuai aturan. set_value mengembalikan isian setelah gagal; untuk input HTML di bawah, parameter ketiga FALSE menonaktifkan escaping bawaan set_value agar html_escape dilakukan tepat sekali pada output. Jangan meng-escape data sebelum disimpan.'
				),
				'file' => 'Cuplikan view siswa/create.php',
				'code' => <<<'CODE'
<?= validation_errors() ?>
<?= form_open('siswa/create') ?>
<label for="nis">NIS</label>
<input id="nis" name="nis" value="<?= html_escape(set_value('nis', '', FALSE)) ?>">
<?= form_close() ?>
CODE
				, 'explanations' => array(
					'validation_errors menampilkan pesan aturan dengan delimiter HTML standar CI3; nama field pada aturan adalah label tetap, bukan input pengguna.',
					'form_open membuat form POST dan hidden token jika CSRF aktif; form_close menutupnya.',
					'set_value mengambil kembali input; html_escape mengamankannya dalam atribut HTML. Snapshot lengkap di bawah juga memiliki nama, kelas, dan jenis kelamin.'
				)
			),
			array(
				'title' => 'Simpan hanya field yang diizinkan',
				'paragraphs' => array(
					'Setelah run() berhasil, ambil empat field yang divalidasi secara eksplisit, bukan seluruh POST. ID dan created_at disediakan database. Query Builder memproses nilai sebagai data; escaping HTML tetap dilakukan di view.',
					'Jika insert mengembalikan FALSE dengan db_debug nonaktif, tampilkan pesan umum dan tetap pada form. Pesan ini tidak menggunakan session dan tidak membocorkan detail database.'
				),
				'file' => 'Bagian dari Siswa_model.php',
				'code' => <<<'CODE'
public function tambah($data)
{
    return $this->db->insert('siswa', $data);
}
CODE
				, 'explanations' => array(
					'insert menggunakan Query Builder dan mengembalikan hasil operasi untuk diperiksa controller.',
					'Data yang dikirim hanya berisi nis, nama, kelas, dan jenis_kelamin; id serta created_at tidak perlu diisi manual.'
				)
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
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Siswa_model');
        $this->load->helper(array('url', 'form'));
        $this->load->library('form_validation');
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
            $this->form_validation->set_rules('nis', 'NIS', 'required|regex_match[/\S/]|trim|max_length[20]|is_unique[siswa.nis]');
            $this->form_validation->set_rules('nama', 'Nama', 'required|regex_match[/\S/]|trim|max_length[100]');
            $this->form_validation->set_rules('kelas', 'Kelas', 'required|regex_match[/\S/]|trim|max_length[30]');
            $this->form_validation->set_rules('jenis_kelamin', 'Jenis kelamin', 'required|in_list[L,P]');

            if ($this->form_validation->run() !== FALSE)
            {
                $siswa = array(
                    'nis' => $this->input->post('nis'),
                    'nama' => $this->input->post('nama'),
                    'kelas' => $this->input->post('kelas'),
                    'jenis_kelamin' => $this->input->post('jenis_kelamin')
                );
                if ($this->Siswa_model->tambah($siswa))
                {
                    redirect('siswa');
                    return;
                }
                $data['simpan_error'] = 'Data belum dapat disimpan. Silakan coba lagi.';
            }
        }

        $this->load->view('siswa/create', $data);
    }
}
CODE
				, 'explanations' => array(
					'Constructor memuat semua dependensi; index dan detail tetap menggunakan model tahap sebelumnya.',
					'detail memeriksa ID dan menampilkan 404 jika siswa tidak ada.',
					'create hanya menerima GET dan POST; GET langsung menampilkan form.',
					'POST hanya memanggil tambah setelah semua aturan lolos, dan mengambil empat field secara eksplisit.',
					'Insert sukses redirect ke daftar; kegagalan validasi atau insert menampilkan form kembali.'
				)
			),
			array(
				'title' => 'Snapshot lengkap: model Siswa_model.php',
				'paragraphs' => array('Ganti seluruh isi model. Metode semua() dan cari() tetap tersedia untuk daftar dan detail; tambah() dipakai oleh controller setelah validasi.'),
				'file' => 'application/models/Siswa_model.php',
				'code' => <<<'CODE'
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa_model extends CI_Model {
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

    public function tambah($data)
    {
        return $this->db->insert('siswa', $data);
    }
}
CODE
				, 'explanations' => array(
					'semua mengambil seluruh baris menurut nama; result_array menghasilkan daftar array.',
					'cari memakai ID sebagai kondisi dan row_array menghasilkan satu baris atau NULL.',
					'tambah menjalankan insert melalui Query Builder dan mengembalikan hasilnya.'
				)
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
    <p><a href="<?= html_escape(site_url('siswa/create')) ?>">Tambah siswa</a></p>
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
                    <td><a href="<?= html_escape(site_url('siswa/'.$item['id'])) ?>">Lihat</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
CODE
				, 'explanations' => array(
					'site_url membuat tautan sesuai base_url dan index_page proyek.',
					'Daftar kosong memiliki pesan; setiap nilai siswa dan URL di-escape untuk konteks HTML.',
					'Tautan detail memakai ID pada route siswa/(:num).'
				)
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
    <dl>
        <dt>ID</dt><dd><?= html_escape($siswa['id']) ?></dd>
        <dt>NIS</dt><dd><?= html_escape($siswa['nis']) ?></dd>
        <dt>Nama</dt><dd><?= html_escape($siswa['nama']) ?></dd>
        <dt>Kelas</dt><dd><?= html_escape($siswa['kelas']) ?></dd>
        <dt>Jenis kelamin</dt><dd><?= html_escape($siswa['jenis_kelamin']) ?></dd>
        <dt>Dibuat</dt><dd><?= html_escape($siswa['created_at']) ?></dd>
    </dl>
    <p><a href="<?= html_escape(site_url('siswa')) ?>">Kembali ke daftar</a></p>
</body>
</html>
CODE
				, 'explanations' => array(
					'View menerima satu array siswa dari controller dan menampilkan semua kolom.',
					'html_escape melindungi output teks dan atribut URL; tautan kembali menuju daftar.'
				)
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
    <?= validation_errors() ?>
    <?php if ($simpan_error !== ''): ?>
        <p><?= html_escape($simpan_error) ?></p>
    <?php endif; ?>
    <?= form_open('siswa/create') ?>
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
        <p>
            <label for="jenis_kelamin">Jenis kelamin</label>
            <select id="jenis_kelamin" name="jenis_kelamin">
                <option value="">Pilih jenis kelamin</option>
                <option value="L"<?= set_value('jenis_kelamin', '', FALSE) === 'L' ? ' selected' : '' ?>>Laki-laki</option>
                <option value="P"<?= set_value('jenis_kelamin', '', FALSE) === 'P' ? ' selected' : '' ?>>Perempuan</option>
            </select>
        </p>
        <button type="submit">Simpan</button>
    <?= form_close() ?>
    <p><a href="<?= html_escape(site_url('siswa')) ?>">Kembali ke daftar</a></p>
</body>
</html>
CODE
				, 'explanations' => array(
					'validation_errors menampilkan kesalahan server; simpan_error menampilkan pesan umum jika insert gagal.',
					'form_open menghasilkan POST dan token CSRF jika aktif. Setiap field memiliki label, id, dan name yang cocok dengan aturan.',
					'set_value dengan escaping bawaan dinonaktifkan kemudian di-escape sekali pada atribut input; pada select nilainya hanya dibandingkan dengan L atau P dan tidak dicetak.',
					'maxlength membantu pengguna, tetapi batas panjang, required, dan pilihan tetap diperiksa di server.',
					'Tombol mengirim form; tautan kembali tidak mengubah data.'
				)
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
