<?php
defined('BASEPATH') OR exit('No direct script access allowed');

return array(
	'prasyarat' => array(
		'category' => 'FONDASI', 'title' => 'Bekal sebelum mulai', 'duration' => 3,
		'summary' => 'Siapkan dasar PHP, web, dan database agar perjalanan belajarmu lebih lancar.',
		'goals' => array('Memahami alur request dan response serta perbedaan GET dan POST.', 'Mengenali fungsi, array, class PHP, dan operasi SQL dasar.', 'Menyiapkan proyek latihan terpisah dari situs modul ini.'),
		'sections' => array(
			array('title' => 'Bagaimana web bekerja?', 'paragraphs' => array('Browser mengirim HTTP request ke server. Server memproses request lalu mengirim response berupa status, header, dan isi. GET umumnya digunakan untuk mengambil halaman; POST untuk mengirim perubahan. Jangan gunakan GET untuk menghapus data.', 'Form mengirim pasangan nama dan nilai. Semua input dari browser dapat diubah pengguna, sehingga harus divalidasi di server.')),
			array('title' => 'PHP yang perlu dikuasai', 'paragraphs' => array('Pastikan kamu dapat membaca variabel, array, kondisi, perulangan, fungsi, serta class dan method. Controller CI3 adalah class; method-nya menangani request. Contoh berikut menunjukkan array dan fungsi biasa, belum menggunakan framework.'), 'file' => 'PHP dasar', 'code' => <<<'CODE'
<?php
function judul_artikel($artikel)
{
    return $artikel['judul'];
}

$artikel = array('judul' => 'Belajar CI3');
echo htmlspecialchars(judul_artikel($artikel), ENT_QUOTES, 'UTF-8');
CODE
			),
			array('title' => 'Database dan ruang praktik', 'paragraphs' => array('Pelajari arti tabel, kolom, primary key, serta SELECT, INSERT, UPDATE, dan DELETE. Artikel pada proyek ini akan memiliki id, judul, dan isi. Gunakan database lokal khusus latihan tanpa data sensitif.', 'Siapkan salinan CI3 terpisah untuk praktik. Situs modul ini adalah referensi: contoh di pelajaran berikutnya ditujukan untuk proyek latihan, bukan untuk mengganti controller situs modul. Kamu boleh melewati modul ini jika sudah menguasai prasyarat.'))
		),
		'exercise' => array('title' => 'Periksa bekalmu', 'intro' => 'Sebelum masuk CI3, coba jelaskan alur sebuah form.', 'steps' => array('Buat form HTML dengan input ber-nama judul dan method POST.', 'Terangkan bagaimana server menerima nilai itu dan mengapa nilainya harus divalidasi.', 'Tuliskan query SELECT untuk mengambil artikel berdasarkan id menggunakan parameter, bukan menggabungkan input ke SQL.'), 'criteria' => 'Kamu dapat menjelaskan perjalanan data dari browser ke server dan menyebutkan fungsi primary key.'),
		'review' => array(array('question' => 'Mengapa input browser tidak dapat langsung dipercaya?', 'answer' => 'Pengguna dapat mengubah request di luar form; validasi dan pemeriksaan otorisasi harus dilakukan di server.'), array('question' => 'Apa perbedaan GET dan POST?', 'answer' => 'GET digunakan untuk mengambil representasi; POST mengirim data atau meminta perubahan. Operasi yang mengubah data tidak boleh dilakukan melalui GET.')),
		'resources' => array(array('title' => 'PHP: tutorial dasar', 'url' => 'https://www.php.net/manual/en/tutorial.php'), array('title' => 'MDN: gambaran HTTP', 'url' => 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Overview'))
	),
	'mengenal-ci' => array(
		'category' => 'FONDASI', 'title' => 'Mengenal CodeIgniter 3', 'duration' => 3,
		'summary' => 'Jalankan proyek dan pahami struktur folder serta alur kerja framework.',
		'goals' => array('Menjalankan salinan CI3 di lingkungan lokal.', 'Menjelaskan fungsi application, system, dan index.php.', 'Memahami hubungan model, view, dan controller.'),
		'sections' => array(
			array('title' => 'Siapkan lingkungan lokal', 'paragraphs' => array('Gunakan PHP yang masih didukung dan telah diuji dengan CI3, server web, dan database untuk modul berikutnya. CI3 adalah framework lama; kompatibilitas berbeda menurut versi PHP dan ekstensi. Jangan menggunakan PHP yang sudah tidak didukung hanya karena tercantum sebagai syarat minimum.', 'Untuk percobaan lokal jalankan perintah berikut dari root salinan CI3, lalu buka http://localhost:8000. Ini server pengembangan, bukan server produksi. Jika muncul peringatan kompatibilitas PHP, catat dan periksa sebelum melanjutkan; jangan menganggap aplikasi siap produksi.'), 'file' => 'Terminal', 'code' => 'php -S localhost:8000'),
			array('title' => 'Kenali bagian utama', 'paragraphs' => array('index.php adalah front controller: request masuk melalui file ini. Folder system berisi inti framework; hindari mengubahnya saat membuat fitur. Folder application berisi kode aplikasi: controllers, models, views, config, dan folder lainnya.', 'Di application/config/config.php, atur base_url sesuai alamat proyek. Nilai kosong hanya untuk kemudahan pengembangan dan tidak boleh diandalkan untuk produksi. routes.php menentukan pemetaan URL.')),
			array('title' => 'Pola MVC dan alur request', 'paragraphs' => array('Controller menerima request dan mengatur alur. Model menangani akses dan pengolahan data. View menyajikan data kepada pengguna. Pembagian ini membantu kita mengubah tampilan tanpa memindahkan query ke dalam view.', 'Alur umumnya: browser → index.php → route → controller → model bila diperlukan → view → response. Tidak semua request membutuhkan model, misalnya halaman statis.'))
		),
		'exercise' => array('title' => 'Petakan proyekmu', 'intro' => 'Buka salinan proyek CI3 dan telusuri halaman awal.', 'steps' => array('Temukan default_controller di application/config/routes.php.', 'Buka controller dan view yang dirujuk, lalu ubah teks view pada proyek latihan.', 'Gambarkan alur dari URL sampai teks baru tampil di browser.'), 'criteria' => 'Halaman tampil dan kamu bisa menunjukkan peran route, controller, dan view.'),
		'review' => array(array('question' => 'Mengapa kode aplikasi sebaiknya tidak ditaruh di system?', 'answer' => 'System berisi inti framework. Menjaga kode aplikasi di application memisahkan fitur dari framework dan memudahkan pemeliharaan.'), array('question' => 'Apakah setiap controller harus memanggil model?', 'answer' => 'Tidak. Halaman statis dapat langsung memuat view; gunakan model saat membutuhkan data atau pengolahan yang relevan.')),
		'resources' => array(array('title' => 'Instalasi CI3', 'url' => 'https://codeigniter.com/userguide3/installation/index.html'), array('title' => 'Model-View-Controller', 'url' => 'https://codeigniter.com/userguide3/overview/mvc.html'))
	),
	'routing-controller' => array(
		'category' => 'FONDASI', 'title' => 'Routing & controller', 'duration' => 4,
		'summary' => 'Ikuti perjalanan URL menuju controller dan buat halaman artikel pertamamu.',
		'goals' => array('Membuat controller dan method publik di CI3.', 'Memetakan URL menggunakan routes.php dan menghilangkan index.php dari URL.', 'Menangani parameter dan halaman tidak ditemukan.'),
		'sections' => array(
			array('title' => 'Controller menerima request', 'paragraphs' => array('Nama file dan class controller menggunakan huruf awal kapital, misalnya Artikel.php dan Artikel. Class mewarisi CI_Controller. Method publik dapat dipetakan ke URL; hindari menjadikan method internal sebagai endpoint publik.'), 'file' => 'application/controllers/Artikel.php', 'code' => <<<'CODE'
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Artikel extends CI_Controller {
    public function index()
    {
        echo 'Daftar artikel';
    }

    public function detail($id = NULL)
    {
        if ($id === NULL || ! ctype_digit((string) $id))
        {
            show_404();
            return;
        }
        echo 'Artikel nomor '.html_escape($id);
    }
}
CODE
			),
			array('title' => 'Petakan URL', 'paragraphs' => array('Tambahkan route berikut di proyek latihan. (:num) hanya cocok untuk segmen angka, lalu $1 diteruskan ke method. Urutan route penting: letakkan route khusus sebelum pola yang lebih umum. Setelah konfigurasi URL bersih berikut diterapkan, contoh URL lokal adalah /artikel/12.'), 'file' => 'application/config/routes.php', 'code' => <<<'CODE'
$route['artikel'] = 'artikel/index';
$route['artikel/(:num)'] = 'artikel/detail/$1';
CODE
			),
			array('title' => 'Hilangkan index.php dari URL', 'paragraphs' => array('CI3 tetap membutuhkan file index.php sebagai front controller. Yang dihilangkan hanya bagian URL-nya: server meneruskan request ke file tersebut secara internal, lalu CI3 membaca URI dan memilih route. Mengubah routes.php saja tidak cukup.', 'Pada salinan proyek latihan, kosongkan index_page di application/config/config.php agar site_url() menghasilkan tautan tanpa index.php. Atur base_url sesuai lokasi proyek; untuk produksi nilainya harus eksplisit dan berakhir dengan garis miring.'), 'file' => 'application/config/config.php', 'code' => <<<'CODE'
$config['base_url'] = 'http://localhost:8000/';
$config['index_page'] = '';
CODE
			),
			array('title' => 'Konfigurasi server dan uji URL langsung', 'paragraphs' => array('Pada Apache, buat .htaccess di root proyek seperti berikut. Aktifkan mod_rewrite dan AllowOverride yang mengizinkan FileInfo. Aturan ini meneruskan URL yang bukan file atau direktori nyata ke index.php; CSS dan JavaScript tetap dilayani sebagai file statis. Query string tetap diteruskan.', 'Server pengembangan PHP dapat meneruskan URL yang tidak ditemukan ke index.php tanpa membaca .htaccess. Nginx tidak membaca .htaccess dan membutuhkan konfigurasi try_files yang setara serta pengaturan PHP dan pembatasan akses folder internal. Uji URL langsung dan refresh; jika muncul 404 dari server, periksa konfigurasi rewrite dan lokasi proyek, bukan hanya routes.php. Jangan menghapus file index.php.'), 'file' => '.htaccess di root proyek Apache', 'code' => <<<'CODE'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [L]
</IfModule>
CODE
			),
			array('title' => 'Tangani input dan 404', 'paragraphs' => array('Route bukan pengganti validasi pada controller: URL bawaan CI3 juga dapat mengakses method publik. Periksa parameter sebelum menggunakannya dan panggil show_404() untuk resource yang tidak ada. Pada modul database, keberadaan ID juga harus diperiksa.'))
		),
		'exercise' => array('title' => 'Buat dua endpoint', 'intro' => 'Gunakan controller Artikel dalam proyek latihan.', 'steps' => array('Tambahkan route daftar dan detail artikel, lalu konfigurasikan URL bersih untuk server latihan.', 'Buka /artikel dan /artikel/12 secara langsung, lalu refresh halaman detail.', 'Pastikan file statis masih dimuat dan coba parameter yang tidak valid, termasuk akses langsung ke method controller.'), 'criteria' => 'Daftar dan detail dapat diakses tanpa index.php, termasuk setelah refresh; file statis tetap tersedia dan parameter tidak valid tidak menghasilkan halaman detail yang berhasil.'),
		'review' => array(array('question' => 'Apa fungsi (:num) dan $1?', 'answer' => '(:num) mencocokkan segmen angka; $1 meneruskan segmen yang cocok ke tujuan route.'), array('question' => 'Apakah file index.php boleh dihapus setelah URL dibersihkan?', 'answer' => 'Tidak. Server tetap meneruskan request ke index.php sebagai front controller; hanya penulisannya pada URL yang dihilangkan.'), array('question' => 'Mengapa parameter tetap divalidasi di controller?', 'answer' => 'Method publik dapat diakses melalui routing lain; route tidak menjamin semua jalur masuk memiliki validasi yang sama.')),
		'resources' => array(array('title' => 'Controller CI3', 'url' => 'https://codeigniter.com/userguide3/general/controllers.html'), array('title' => 'URI routing', 'url' => 'https://codeigniter.com/userguide3/general/routing.html'), array('title' => 'URL CodeIgniter dan mod_rewrite', 'url' => 'https://codeigniter.com/userguide3/general/urls.html'))
	),
	'views' => array(
		'category' => 'FONDASI', 'title' => 'Menyajikan data dengan view', 'duration' => 3,
		'summary' => 'Pisahkan tampilan dari logika dan tampilkan data dengan aman.',
		'goals' => array('Memuat view dan mengirim data dari controller.', 'Menggunakan template sederhana dan perulangan.', 'Melakukan escaping data untuk konteks HTML.'),
		'sections' => array(
			array('title' => 'Kirim data ke view', 'paragraphs' => array('Controller mempersiapkan array data dan memuat view. Kunci array menjadi variabel di view. Gunakan data sementara terlebih dahulu, lalu ganti dengan model pada modul berikutnya.'), 'file' => 'Artikel::index()', 'code' => <<<'CODE'
$data['artikel'] = array(
    array('id' => 1, 'judul' => 'Halo CI3'),
    array('id' => 2, 'judul' => 'Mengenal MVC')
);
$this->load->view('artikel/index', $data);
CODE
			),
			array('title' => 'Tampilkan data dengan aman', 'paragraphs' => array('Buat folder views/artikel dan file index.php. Escape teks yang dimasukkan ke HTML dengan html_escape; data dari database pun tetap harus dianggap tidak tepercaya. HTML, atribut, URL, dan JavaScript memiliki konteks berbeda: gunakan encoding dan validasi yang sesuai konteks, bukan satu filter untuk semuanya.'), 'file' => 'application/views/artikel/index.php', 'code' => <<<'CODE'
<h1>Artikel</h1>
<ul>
<?php foreach ($artikel as $item): ?>
    <li><?= html_escape($item['judul']) ?></li>
<?php endforeach; ?>
</ul>
CODE
			),
			array('title' => 'Susun template', 'paragraphs' => array('Pecah struktur bersama menjadi view header dan footer. Controller dapat memanggil header, view isi, lalu footer; sertakan data yang diperlukan masing-masing. Jaga query dan aturan bisnis tetap di luar view.'))
		),
		'exercise' => array('title' => 'Tampilkan daftar artikel', 'intro' => 'Ubah endpoint daftar agar menggunakan view.', 'steps' => array('Kirim minimal dua artikel dari controller ke view.', 'Buat template header dan footer sederhana.', 'Masukkan judul yang mengandung tag HTML dan periksa bahwa tag ditampilkan sebagai teks.'), 'criteria' => 'Daftar muncul melalui view dan judul tidak dijalankan sebagai HTML.'),
		'review' => array(array('question' => 'Apa yang terjadi pada kunci array data saat view dimuat?', 'answer' => 'Kunci array tersedia sebagai nama variabel di view yang dimuat.'), array('question' => 'Apakah html_escape membuat data aman untuk semua konteks?', 'answer' => 'Tidak. Gunakan encoding sesuai konteks; jangan memasukkan data mentah ke JavaScript atau URL tanpa penanganan yang sesuai.')),
		'resources' => array(array('title' => 'Views CI3', 'url' => 'https://codeigniter.com/userguide3/general/views.html'), array('title' => 'Keamanan CI3', 'url' => 'https://codeigniter.com/userguide3/general/security.html'))
	),
	'database-model' => array(
		'category' => 'MEMBANGUN FITUR', 'title' => 'Database & model', 'duration' => 4,
		'summary' => 'Hubungkan aplikasi dengan database dan ambil artikel melalui model.',
		'goals' => array('Mengonfigurasi koneksi database lokal.', 'Membuat model dan mengambil daftar serta detail artikel.', 'Menangani data yang tidak ditemukan.'),
		'sections' => array(
			array('title' => 'Siapkan tabel dan koneksi', 'paragraphs' => array('Buat database lokal dan tabel artikel berikut. Sesuaikan application/config/database.php dengan host, database, pengguna, dan driver mysqli milik lingkunganmu. Jangan memasukkan kredensial produksi ke repository. Muat database dengan $this->load->database() atau melalui autoload.'), 'file' => 'MySQL / MariaDB', 'code' => <<<'CODE'
CREATE TABLE artikel (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    judul VARCHAR(200) NOT NULL,
    isi TEXT NOT NULL,
    PRIMARY KEY (id)
);
INSERT INTO artikel (judul, isi)
VALUES ('Artikel pertama', 'Halo dari database.');
CODE
			),
			array('title' => 'Buat model', 'paragraphs' => array('Model merangkum akses data agar controller tidak perlu mengetahui detail query. Query Builder memproses nilai sebagai data; jangan menggabungkan input pengguna menjadi potongan SQL mentah.'), 'file' => 'application/models/Artikel_model.php', 'code' => <<<'CODE'
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Artikel_model extends CI_Model {
    public function semua()
    {
        return $this->db->get('artikel')->result_array();
    }

    public function cari($id)
    {
        return $this->db->get_where('artikel', array('id' => $id))
            ->row_array();
    }
}
CODE
			),
			array('title' => 'Hubungkan controller', 'paragraphs' => array('Muat database dan model di constructor controller, lalu panggil semua() untuk daftar. Pada detail, validasi ID, panggil cari($id), dan gunakan show_404() bila hasilnya kosong. Kirim hasil yang ditemukan ke view. Jangan tampilkan pesan koneksi atau kredensial kepada pengunjung.')),
			array('title' => 'Contoh daftar dan detail', 'paragraphs' => array('Tambahkan constructor dan kedua method ini pada controller Artikel di proyek latihan. Contoh detail memakai view artikel/detail yang perlu kamu buat; gunakan html_escape untuk judul dan isi pada view tersebut.'), 'file' => 'Bagian dari application/controllers/Artikel.php', 'code' => <<<'CODE'
public function __construct()
{
    parent::__construct();
    $this->load->database();
    $this->load->model('Artikel_model');
}

public function index()
{
    $data['artikel'] = $this->Artikel_model->semua();
    $this->load->view('artikel/index', $data);
}

public function detail($id = NULL)
{
    if ($id === NULL || ! ctype_digit((string) $id))
    {
        show_404();
        return;
    }
    $data['artikel'] = $this->Artikel_model->cari($id);
    if ( ! $data['artikel'])
    {
        show_404();
        return;
    }
    $this->load->view('artikel/detail', $data);
}
CODE
			)
		),
		'exercise' => array('title' => 'Ambil artikel dari database', 'intro' => 'Ganti data sementara di proyek latihan dengan data yang persisten.', 'steps' => array('Buat tabel dan isi setidaknya dua artikel.', 'Muat database dan Artikel_model dari controller.', 'Tampilkan daftar dan detail, lalu coba ID yang tidak ada.'), 'criteria' => 'Daftar dan detail berasal dari database; ID tidak ditemukan menghasilkan 404.'),
		'review' => array(array('question' => 'Mengapa query ditempatkan di model?', 'answer' => 'Model memusatkan akses data sehingga controller fokus pada alur request dan view pada penyajian.'), array('question' => 'Apa yang harus dilakukan jika row_array() tidak menemukan data?', 'answer' => 'Periksa hasil sebelum mengaksesnya dan kembalikan 404 untuk artikel yang tidak ada.')),
		'resources' => array(array('title' => 'Model CI3', 'url' => 'https://codeigniter.com/userguide3/general/models.html'), array('title' => 'Query Builder', 'url' => 'https://codeigniter.com/userguide3/database/query_builder.html'))
	),
	'form-validasi' => array(
		'category' => 'MEMBANGUN FITUR', 'title' => 'Form & validasi', 'duration' => 5,
		'summary' => 'Terima data pengguna, validasi di server, dan simpan artikel baru.',
		'goals' => array('Membuat form POST dengan form helper.', 'Menggunakan library form validation dan menampilkan kesalahan.', 'Menyimpan data valid melalui model dan redirect setelah berhasil.'),
		'sections' => array(
			array('title' => 'Form yang mudah diperbaiki', 'paragraphs' => array('Muat helper form dan URL serta library form_validation. form_open membuat form yang terintegrasi dengan konfigurasi CI3, termasuk token CSRF ketika perlindungan tersebut diaktifkan. set_value menjaga input saat validasi gagal; escape nilai saat dimasukkan ke HTML.'), 'file' => 'application/views/artikel/create.php', 'code' => <<<'CODE'
<?= validation_errors() ?>
<?= form_open('artikel/create') ?>
    <label for="judul">Judul</label>
    <input id="judul" name="judul" value="<?= html_escape(set_value('judul')) ?>">
    <label for="isi">Isi</label>
    <textarea id="isi" name="isi"><?= html_escape(set_value('isi')) ?></textarea>
    <button type="submit">Simpan</button>
<?= form_close() ?>
CODE
			),
			array('title' => 'Validasi di controller', 'paragraphs' => array('Pada method create, tentukan aturan sebelum form_validation->run(). Bila belum dikirim atau validasi gagal, muat view. Bila valid, ambil field yang diizinkan secara eksplisit, panggil model untuk insert, dan redirect hanya jika penyimpanan berhasil. Tangani kegagalan database sesuai konfigurasi lingkungan.'), 'file' => 'Bagian dari Artikel::create()', 'code' => <<<'CODE'
$this->form_validation->set_rules('judul', 'Judul', 'trim|required|max_length[200]');
$this->form_validation->set_rules('isi', 'Isi', 'required');
if ($this->form_validation->run() === FALSE)
{
    $this->load->view('artikel/create');
    return;
}
$data = array(
    'judul' => $this->input->post('judul'),
    'isi' => $this->input->post('isi')
);
// Simpan melalui model, periksa hasilnya, lalu redirect.
CODE
			),
			array('title' => 'Validasi bukan escaping', 'paragraphs' => array('Validasi memeriksa apakah input memenuhi aturan bisnis. Escaping dilakukan ketika data disajikan sesuai konteks output. Query Builder atau query bindings melindungi nilai query; jangan menganggap validasi form cukup untuk mencegah SQL injection atau XSS.')),
			array('title' => 'Selesaikan penyimpanan', 'paragraphs' => array('Tambahkan method simpan ke Artikel_model. Contoh controller mengasumsikan database dan model sudah dimuat pada constructor modul sebelumnya. Jika ingin menangani kegagalan query sendiri, konfigurasikan db_debug agar tidak langsung menghentikan aplikasi dan jangan tampilkan detail error database kepada pengguna.'), 'file' => 'Artikel_model dan Artikel::create()', 'code' => <<<'CODE'
// Tambahkan pada Artikel_model:
public function simpan($data)
{
    return $this->db->insert('artikel', $data);
}

// Tambahkan pada controller Artikel:
public function create()
{
    $this->load->helper(array('form', 'url'));
    $this->load->library('form_validation');
    $this->form_validation->set_rules('judul', 'Judul', 'trim|required|max_length[200]');
    $this->form_validation->set_rules('isi', 'Isi', 'required');
    if ($this->form_validation->run() === FALSE)
    {
        $this->load->view('artikel/create');
        return;
    }
    $data = array(
        'judul' => $this->input->post('judul'),
        'isi' => $this->input->post('isi')
    );
    if ( ! $this->Artikel_model->simpan($data))
    {
        log_message('error', 'Gagal menyimpan artikel');
        show_error('Artikel tidak dapat disimpan.', 500);
        return;
    }
    redirect('artikel');
}
CODE
			)
		),
		'exercise' => array('title' => 'Tambahkan artikel', 'intro' => 'Hubungkan form, validasi, dan model.', 'steps' => array('Tambahkan method create dan route khusus sebelum route detail yang umum.', 'Tambahkan method insert di model dan periksa hasil penyimpanan.', 'Uji form kosong, judul terlalu panjang, dan data valid; redirect setelah berhasil.'), 'criteria' => 'Data tidak valid tidak disimpan; data valid tersimpan sekali dan terlihat pada daftar.'),
		'review' => array(array('question' => 'Mengapa perlu redirect setelah POST sukses?', 'answer' => 'Pola Post/Redirect/Get mengurangi risiko pengiriman ulang ketika halaman hasil diperbarui.'), array('question' => 'Apakah validasi required mencegah XSS?', 'answer' => 'Tidak. Escape data saat output sesuai konteks, sekalipun input telah divalidasi.')),
		'resources' => array(array('title' => 'Form validation', 'url' => 'https://codeigniter.com/userguide3/libraries/form_validation.html'), array('title' => 'Form helper', 'url' => 'https://codeigniter.com/userguide3/helpers/form_helper.html'))
	),
	'crud' => array(
		'category' => 'MEMBANGUN FITUR', 'title' => 'CRUD lengkap', 'duration' => 6,
		'summary' => 'Lengkapi alur membuat, membaca, mengubah, dan menghapus artikel.',
		'goals' => array('Membuat alur edit dan hapus dengan model.', 'Memeriksa keberadaan artikel dan hasil operasi.', 'Menggunakan POST untuk perubahan dan redirect setelah berhasil.'),
		'sections' => array(
			array('title' => 'Dari create ke update', 'paragraphs' => array('Edit dimulai dengan mengambil artikel berdasarkan ID. Tampilkan form berisi nilai lama; setelah POST valid, kirim hanya field yang diizinkan ke model. Tampilkan kembali input dan pesan kesalahan bila validasi gagal. Artikel yang tidak ada harus menghasilkan 404.', 'Di model, Query Builder membatasi baris yang diubah berdasarkan ID. Periksa hasil operasi dan jangan tampilkan pesan sukses jika database gagal.'), 'file' => 'Bagian dari Artikel_model', 'code' => <<<'CODE'
public function ubah($id, $data)
{
    return $this->db->where('id', $id)->update('artikel', $data);
}

public function hapus($id)
{
    return $this->db->where('id', $id)->delete('artikel');
}
CODE
			),
			array('title' => 'Hapus secara aman', 'paragraphs' => array('Gunakan form POST, bukan tautan GET, untuk menghapus. Periksa method request, token CSRF jika aktif, keberadaan data, dan hak akses sebelum memanggil model. Konfirmasi di browser boleh membantu pengguna, tetapi bukan pengganti pemeriksaan server.', 'Setelah berhasil, redirect ke daftar dan tampilkan pesan sekali pakai melalui flashdata. Bila gagal, catat kesalahan server dan beri pesan yang tidak membocorkan detail database.')),
			array('title' => 'Periksa method sebelum mengubah data', 'paragraphs' => array('Potongan berikut menunjukkan gerbang method untuk endpoint hapus. Pemeriksaan login, peran, ID, keberadaan artikel, hasil model, dan perlindungan CSRF tetap harus ditambahkan. Jangan menaruh operasi hapus di luar pemeriksaan tersebut.'), 'file' => 'Bagian dari Artikel::hapus($id)', 'code' => <<<'CODE'
if ($this->input->method() !== 'post')
{
    show_error('Method tidak diizinkan', 405);
    return;
}
// Periksa identitas, peran, ID dan keberadaan artikel.
// Panggil model hanya setelah semua pemeriksaan berhasil.
CODE
			),
			array('title' => 'Batas tahap ini', 'paragraphs' => array('CRUD yang baru dibuat belum memiliki login dan otorisasi. Gunakan hanya di lingkungan latihan tertutup. Jangan publikasikan pengelolaan artikel sebelum tahap keamanan dan akses selesai.'))
		),
		'exercise' => array('title' => 'Selesaikan CRUD', 'intro' => 'Tambahkan edit dan hapus pada aplikasi latihan.', 'steps' => array('Buat route dan form edit, validasi data, lalu perbarui artikel melalui model.', 'Buat form POST untuk hapus dan tolak request GET.', 'Uji ID tidak ada, input tidak valid, dan kegagalan penyimpanan; redirect hanya saat berhasil.'), 'criteria' => 'Artikel dapat dibuat, dibaca, diubah, dan dihapus; kesalahan ditangani tanpa pesan sukses palsu.'),
		'review' => array(array('question' => 'Mengapa hapus tidak menggunakan GET?', 'answer' => 'GET seharusnya aman untuk mengambil data; browser atau crawler dapat mengikuti tautan GET tanpa bermaksud mengubah data.'), array('question' => 'Apakah konfirmasi JavaScript cukup untuk melindungi penghapusan?', 'answer' => 'Tidak. Validasi request, CSRF, dan otorisasi harus diperiksa di server.')),
		'resources' => array(array('title' => 'Query Builder: update dan delete', 'url' => 'https://codeigniter.com/userguide3/database/query_builder.html'), array('title' => 'Session dan flashdata', 'url' => 'https://codeigniter.com/userguide3/libraries/sessions.html'))
	),
	'keamanan' => array(
		'category' => 'APLIKASI AMAN', 'title' => 'Keamanan aplikasi', 'duration' => 6,
		'summary' => 'Lindungi input, query, output, form, dan konfigurasi sejak awal.',
		'goals' => array('Membedakan validasi, query binding, dan escaping output.', 'Mengaktifkan dan menguji perlindungan CSRF CI3.', 'Mengenali praktik aman untuk password dan konfigurasi.'),
		'sections' => array(
			array('title' => 'Pertahanan berlapis', 'paragraphs' => array('Validasi memeriksa format dan aturan bisnis; ia bukan pengganti otorisasi. Untuk query gunakan Query Builder atau parameter binding pada nilai. Jangan membangun SQL dengan menggabungkan input; nama kolom dan urutan dinamis perlu daftar nilai yang diizinkan, karena identifier bukan nilai bind biasa.', 'Escape output HTML menggunakan html_escape. Jangan mengandalkan xss_clean sebagai pengganti encoding kontekstual; data yang masuk ke URL atau JavaScript membutuhkan perlakuan sesuai konteks.')),
			array('title' => 'Binding untuk nilai query', 'paragraphs' => array('Tanda tanya adalah placeholder untuk nilai; CI3 melakukan binding sesuai driver. Contoh ini mencari berdasarkan judul, bukan menyusun SQL dari string input. Untuk query umum, Query Builder juga menyediakan cara yang mudah dibaca.'), 'file' => 'Bagian dari model', 'code' => <<<'CODE'
$query = $this->db->query(
    'SELECT id, judul FROM artikel WHERE judul = ?',
    array($judul)
);
return $query->result_array();
CODE
			),
			array('title' => 'CSRF pada form perubahan', 'paragraphs' => array('Aktifkan csrf_protection di application/config/config.php pada proyek latihan. Form POST yang dibuat dengan form_open akan menyertakan token ketika fitur aktif. Uji semua form termasuk edit dan hapus; request tanpa token yang valid harus ditolak. Untuk AJAX atau integrasi lain, kirim token dengan cara yang sesuai dan perhatikan regenerasi token.', 'CSRF tidak menggantikan pemeriksaan login dan hak akses. Pastikan operasi perubahan tidak tersedia melalui GET.'), 'file' => 'application/config/config.php', 'code' => "\$config['csrf_protection'] = TRUE;"),
			array('title' => 'Password dan rahasia', 'paragraphs' => array('Gunakan password_hash dan password_verify, bukan hash cepat seperti MD5. Simpan hash, bukan password asli; jangan mengubah password dengan trim atau filter yang merusak nilainya. Pada login, gunakan pesan kegagalan umum dan regenerasi session ID setelah autentikasi.', 'Atur base_url eksplisit untuk lingkungan produksi, gunakan HTTPS, batasi akses file konfigurasi dan log, dan simpan kredensial di konfigurasi lingkungan yang tidak dikomit. Jangan tampilkan stack trace atau error database kepada pengunjung.'))
		),
		'exercise' => array('title' => 'Uji lapisan perlindungan', 'intro' => 'Tinjau setiap titik masuk dan keluaran aplikasi latihan.', 'steps' => array('Aktifkan CSRF dan uji create, edit, dan hapus menggunakan form helper.', 'Kirim POST tanpa token yang valid dan pastikan server menolaknya.', 'Simpan judul yang mengandung tag HTML dan pastikan tampil sebagai teks; periksa semua query yang menerima input.'), 'criteria' => 'Form valid tetap berfungsi, POST tanpa token ditolak, dan data tidak dijalankan sebagai HTML.'),
		'review' => array(array('question' => 'Apa perbedaan validasi input dan escaping output?', 'answer' => 'Validasi menegakkan aturan input; escaping menyiapkan data untuk konteks output tertentu. Keduanya memiliki tujuan berbeda.'), array('question' => 'Apakah token CSRF membuktikan pengguna boleh menghapus artikel?', 'answer' => 'Tidak. Token mengurangi risiko request lintas situs; hak akses harus diperiksa terpisah di server.')),
		'resources' => array(array('title' => 'Keamanan CI3', 'url' => 'https://codeigniter.com/userguide3/general/security.html'), array('title' => 'Security library dan CSRF', 'url' => 'https://codeigniter.com/userguide3/libraries/security.html'), array('title' => 'PHP password hashing', 'url' => 'https://www.php.net/manual/en/function.password-hash.php'))
	),
	'login-otorisasi' => array(
		'category' => 'APLIKASI AMAN', 'title' => 'Login & otorisasi', 'duration' => 5,
		'summary' => 'Kelola session dan pastikan hanya pengguna berhak yang mengubah artikel.',
		'goals' => array('Membedakan autentikasi dan otorisasi.', 'Memverifikasi password dan mengelola session.', 'Melindungi setiap endpoint pengelolaan di server.'),
		'sections' => array(
			array('title' => 'Identitas dan hak akses', 'paragraphs' => array('Autentikasi menjawab siapa pengguna; otorisasi menentukan apa yang boleh ia lakukan. Login saja tidak cukup: semua endpoint create, edit, dan hapus harus memeriksa identitas serta hak akses, termasuk request langsung tanpa melalui menu.', 'Buat tabel pengguna dengan ID unik, username unik, password_hash, dan peran. Sediakan administrator awal melalui proses lokal yang aman, bukan password default yang dikomit atau endpoint publik tanpa perlindungan.')),
			array('title' => 'Verifikasi dan session', 'paragraphs' => array('Muat session library, cari pengguna berdasarkan username dengan query terparameterisasi, lalu verifikasi hash dengan password_verify. Gunakan pesan kegagalan yang sama untuk username dan password yang salah. Contoh berikut hanya bagian sukses, bukan implementasi login lengkap.'), 'file' => 'Bagian dari proses login', 'code' => <<<'CODE'
if ($user && password_verify($password, $user['password_hash']))
{
    $this->session->sess_regenerate(TRUE);
    $this->session->set_userdata(array(
        'user_id' => $user['id'],
        'role' => $user['role']
    ));
    redirect('artikel');
    return;
}
// Tampilkan pesan login gagal yang umum.
CODE
			),
			array('title' => 'Lindungi seluruh operasi', 'paragraphs' => array('Periksa user_id dan peran di server sebelum menampilkan maupun memproses form pengelolaan. Tolak akses yang tidak berhak; jangan hanya menyembunyikan tombol. Saat logout, gunakan POST dengan CSRF lalu hapus session. Atur cookie aman dan HTTPS pada produksi.', 'Untuk aplikasi nyata, pertimbangkan pembatasan percobaan login, penguncian yang tidak memudahkan penolakan layanan, serta cara memulihkan akun yang aman.')),
			array('title' => 'Contoh pemeriksaan akses', 'paragraphs' => array('Muat session library terlebih dahulu. Pemeriksaan berikut adalah bagian awal setiap method pengelolaan; ekstraksi ke method privat atau class dasar dapat mengurangi duplikasi. Jika hak pengguna dapat berubah saat session masih aktif, ambil hak terbaru dari sumber yang tepercaya.'), 'file' => 'Awal method pengelolaan', 'code' => <<<'CODE'
if ( ! $this->session->userdata('user_id'))
{
    redirect('login');
    return;
}
if ($this->session->userdata('role') !== 'admin')
{
    show_error('Akses ditolak', 403);
    return;
}
CODE
			)
		),
		'exercise' => array('title' => 'Batasi akses pengelolaan', 'intro' => 'Tambahkan autentikasi ke proyek latihan.', 'steps' => array('Buat pengguna administrator awal dengan password_hash melalui proses lokal aman.', 'Implementasikan login, regenerasi session, dan logout POST.', 'Uji URL create, edit, dan hapus secara langsung tanpa login dan sebagai pengguna tanpa hak.'), 'criteria' => 'Hanya pengguna berhak dapat menjalankan perubahan; login gagal tidak mengungkap apakah username terdaftar.'),
		'review' => array(array('question' => 'Apa perbedaan autentikasi dan otorisasi?', 'answer' => 'Autentikasi memverifikasi identitas; otorisasi memutuskan tindakan yang diperbolehkan bagi identitas tersebut.'), array('question' => 'Mengapa session ID diregenerasi setelah login?', 'answer' => 'Untuk mengurangi risiko session fixation ketika status pengguna berubah menjadi terautentikasi.')),
		'resources' => array(array('title' => 'Session CI3', 'url' => 'https://codeigniter.com/userguide3/libraries/sessions.html'), array('title' => 'PHP password_verify', 'url' => 'https://www.php.net/manual/en/function.password-verify.php'))
	),
	'pencarian-debugging' => array(
		'category' => 'PENDALAMAN', 'title' => 'Pencarian & debugging', 'duration' => 4,
		'summary' => 'Buat daftar lebih mudah digunakan dan pelajari cara menelusuri masalah.',
		'goals' => array('Menambahkan pencarian dan pagination.', 'Menggunakan log serta menangani kesalahan dengan tepat.', 'Memahami batas input dan konsistensi hasil pencarian.'),
		'sections' => array(
			array('title' => 'Pencarian yang terarah', 'paragraphs' => array('Terima kata kunci melalui GET, batasi panjangnya, dan terapkan filter yang sama pada query data dan query jumlah hasil. Gunakan Query Builder untuk nilai pencarian. Jika pengguna dapat memilih urutan, petakan pilihan ke daftar kolom dan arah yang diperbolehkan; jangan langsung memasukkannya sebagai SQL.', 'Tampilkan kata kunci dengan escaping HTML dan sediakan keadaan kosong yang jelas ketika tidak ada hasil.')),
			array('title' => 'Pagination konsisten', 'paragraphs' => array('Pagination membutuhkan total hasil setelah filter, ukuran halaman, dan offset yang valid. Gunakan urutan yang stabil, misalnya ID sebagai pemecah seri, lalu pertahankan kata kunci pada tautan halaman. Uji halaman pertama, terakhir, halaman kosong, dan parameter yang tidak valid.', 'Library pagination CI3 membantu membuat tautan, tetapi query limit dan offset tetap harus diterapkan di model.')),
			array('title' => 'Filter dan batas hasil', 'paragraphs' => array('Contoh model berikut menerima kata kunci dan offset yang sudah divalidasi controller. Gunakan filter like yang sama untuk query count_all_results pada query terpisah. Query Builder akan mereset state setelah get, sehingga ulangi filter untuk setiap query.'), 'file' => 'Bagian dari model', 'code' => <<<'CODE'
$this->db->like('judul', $kata_kunci);
$jumlah = $this->db->count_all_results('artikel');

$this->db->like('judul', $kata_kunci);
$hasil = $this->db->order_by('id', 'DESC')
    ->limit(10, $offset)->get('artikel')->result_array();
CODE
			),
			array('title' => 'Debug tanpa membocorkan rahasia', 'paragraphs' => array('Reproduksi masalah, periksa status HTTP dan log, lalu telusuri route, controller, model, dan view. Gunakan log_message untuk informasi yang diperlukan tanpa mencatat password, token, atau data sensitif. Pastikan folder log dapat ditulis oleh server.', 'Di produksi, tampilkan pesan umum dan simpan detail di log terlindungi. Jangan menonaktifkan penanganan error atau menampilkan trace sebagai solusi.'))
		),
		'exercise' => array('title' => 'Cari dan telusuri', 'intro' => 'Perbaiki pengalaman daftar artikel.', 'steps' => array('Tambahkan pencarian judul dan jumlah hasil.', 'Tambahkan pagination dengan urutan stabil dan pertahankan kata kunci di tautan.', 'Uji pencarian kosong dan tanpa hasil, lalu catat kesalahan yang disimulasikan tanpa menampilkan detail kepada pengguna.'), 'criteria' => 'Jumlah hasil, isi halaman, dan tautan pagination memakai filter yang sama.'),
		'review' => array(array('question' => 'Mengapa query jumlah dan query daftar harus memakai filter yang sama?', 'answer' => 'Agar jumlah halaman sesuai dengan hasil yang benar-benar ditampilkan.'), array('question' => 'Apa yang tidak boleh dicatat di log?', 'answer' => 'Password, token, kredensial, dan data sensitif yang tidak diperlukan untuk diagnosis.')),
		'resources' => array(array('title' => 'Pagination CI3', 'url' => 'https://codeigniter.com/userguide3/libraries/pagination.html'), array('title' => 'Penanganan error CI3', 'url' => 'https://codeigniter.com/userguide3/general/errors.html'))
	),
	'migrasi-pengujian' => array(
		'category' => 'PENDALAMAN', 'title' => 'Migrasi & pengujian', 'duration' => 4,
		'summary' => 'Buat skema dapat diulang dan periksa alur aplikasi secara sistematis.',
		'goals' => array('Memahami migrasi skema dan rollback.', 'Membangun ulang database latihan di lingkungan bersih.', 'Menguji alur sukses, kegagalan, dan batas keamanan.'),
		'sections' => array(
			array('title' => 'Migrasi skema', 'paragraphs' => array('Migrasi mencatat perubahan skema sebagai langkah berurutan yang bisa diterapkan kembali. CI3 menyediakan migration library dan database forge. Gunakan nama file sesuai konfigurasi timestamp atau sequential dan implementasikan up() serta down() secara hati-hati.', 'Aktifkan migration_enabled hanya saat menjalankan migrasi sesuai dokumentasi dan lindungi mekanisme eksekusinya. Jangan membuat endpoint migrasi publik tanpa autentikasi. Backup sebelum migrasi produksi; rollback tidak selalu dapat memulihkan data yang terhapus.')),
			array('title' => 'Contoh migrasi tabel', 'paragraphs' => array('Untuk konfigurasi timestamp bawaan, buat folder application/migrations dan gunakan nama file bertimestamp. Contoh ini ditujukan untuk database latihan kosong; jangan menjalankannya di database yang sudah berisi tabel artikel. down() menghapus tabel beserta datanya.'), 'file' => 'application/migrations/20260101000000_create_artikel.php', 'code' => <<<'CODE'
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_artikel extends CI_Migration {
    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array('type' => 'INT', 'unsigned' => TRUE,
                          'auto_increment' => TRUE),
            'judul' => array('type' => 'VARCHAR', 'constraint' => 200),
            'isi' => array('type' => 'TEXT')
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('artikel');
    }

    public function down()
    {
        $this->dbforge->drop_table('artikel');
    }
}
CODE
			),
			array('title' => 'Lapisan pengujian', 'paragraphs' => array('Uji fungsi kecil secara terisolasi bila memungkinkan, lalu uji integrasi database dan alur HTTP. CI3 memiliki unit testing class sederhana; pengujian HTTP atau otomasi tambahan memerlukan pengaturan terpisah. Gunakan database uji khusus, tidak pernah database produksi.', 'Kasus penting: artikel ada/tidak ada, input kosong dan terlalu panjang, create/edit/hapus berhasil maupun gagal, POST tanpa CSRF, pengguna anonim dan tanpa hak, serta login gagal. Uji bahwa error tidak menghasilkan pesan sukses palsu.')),
			array('title' => 'Periksa hasil, bukan hanya kode', 'paragraphs' => array('Catat kondisi awal, tindakan, dan hasil yang diharapkan. Setelah mengubah aplikasi, jalankan kembali kasus yang terdampak. Bila tes manual dilakukan, tuliskan hasilnya agar bisa diulang dan dibedakan dari tes otomatis.'))
		),
		'exercise' => array('title' => 'Bangun ulang dan uji', 'intro' => 'Buktikan aplikasi dapat dipasang ulang dan perilaku utamanya benar.', 'steps' => array('Buat migrasi tabel latihan dan terapkan pada database kosong.', 'Siapkan data latihan yang tidak mengandung kredensial nyata.', 'Jalankan kasus CRUD, validasi, login, otorisasi, dan CSRF; catat hasil sukses dan gagal.'), 'criteria' => 'Skema dapat dibuat ulang dan setiap kasus memiliki hasil yang dapat diamati.'),
		'review' => array(array('question' => 'Mengapa database pengujian harus terpisah?', 'answer' => 'Pengujian dapat mengubah atau menghapus data; isolasi mencegah kerusakan data nyata.'), array('question' => 'Apakah rollback skema selalu mengembalikan data yang terhapus?', 'answer' => 'Tidak. Migrasi balik tidak menggantikan backup yang telah diverifikasi.')),
		'resources' => array(array('title' => 'Migrations CI3', 'url' => 'https://codeigniter.com/userguide3/libraries/migration.html'), array('title' => 'Unit testing CI3', 'url' => 'https://codeigniter.com/userguide3/libraries/unit_testing.html'))
	),
	'deployment' => array(
		'category' => 'PROYEK AKHIR', 'title' => 'Deployment & proyek akhir', 'duration' => 5,
		'summary' => 'Satukan hasil belajar dan siapkan aplikasi untuk lingkungan terpisah.',
		'goals' => array('Memisahkan konfigurasi pengembangan dan produksi.', 'Memeriksa keamanan dan kompatibilitas sebelum rilis.', 'Mendemonstrasikan aplikasi artikel dari instalasi hingga pengujian.'),
		'sections' => array(
			array('title' => 'Siapkan lingkungan', 'paragraphs' => array('Gunakan versi PHP yang masih didukung dan uji CI3 serta ekstensi yang dibutuhkan pada versi tersebut. Tetapkan base_url secara eksplisit, gunakan HTTPS dan konfigurasi database yang berbeda dari lokal. Jangan mengandalkan php -S sebagai server produksi.', 'Atur ENVIRONMENT untuk produksi sesuai cara deployment, nonaktifkan tampilan error kepada pengguna, dan lindungi log serta konfigurasi. Berikan izin tulis hanya pada folder yang memerlukannya.')),
			array('title' => 'Periksa sebelum rilis', 'paragraphs' => array('Jalankan migrasi yang telah diuji dan siapkan backup serta prosedur pemulihan. Pastikan tidak ada kredensial di repository, form perubahan memakai CSRF, semua endpoint pengelolaan memeriksa hak akses, dan output di-escape. Pastikan cookie session dan HTTPS dikonfigurasi sesuai lingkungan.', 'Uji login, logout, CRUD, pencarian, validasi, halaman tidak ada, serta kondisi gagal di lingkungan terpisah. Pantau log tanpa menyimpan rahasia. Siapkan cara rollback dan uji restore backup.')),
			array('title' => 'Proyek akhir', 'paragraphs' => array('Hasil akhirnya adalah aplikasi artikel dengan daftar, detail, pengelolaan CRUD, login, pembatasan akses, pencarian, dan pagination. Sertakan instruksi pemasangan, konfigurasi lingkungan, migrasi, dan catatan pengujian.', 'CI3 dan CI4 adalah framework berbeda. Setelah memahami proyek ini, pelajari strategi modernisasi atau migrasi secara terpisah; jangan mengasumsikan contoh CI3 dapat langsung berjalan di CI4.'))
		),
		'exercise' => array('title' => 'Demonstrasikan proyekmu', 'intro' => 'Lakukan simulasi serah terima pada lingkungan bersih.', 'steps' => array('Pasang proyek, konfigurasi lingkungan, dan terapkan migrasi pada database kosong.', 'Demonstrasikan daftar/detail publik dan pengelolaan artikel oleh pengguna berhak.', 'Uji penolakan akses, CSRF, input tidak valid, backup/restore, serta catat versi PHP dan hasil uji kompatibilitas.'), 'criteria' => 'Orang lain dapat mengikuti instruksi dan memverifikasi alur utama serta penanganan kesalahan tanpa kredensial yang tertanam di kode.'),
		'review' => array(array('question' => 'Mengapa base_url harus ditetapkan pada produksi?', 'answer' => 'Deteksi otomatis hanya untuk kemudahan pengembangan dan tidak boleh diandalkan pada produksi.'), array('question' => 'Apakah aplikasi siap produksi hanya karena berjalan di localhost?', 'answer' => 'Tidak. Kompatibilitas, konfigurasi, keamanan, backup, dan perilaku di lingkungan target harus diuji.')),
		'resources' => array(array('title' => 'Lingkungan CI3', 'url' => 'https://codeigniter.com/userguide3/general/environments.html'), array('title' => 'Versi PHP yang didukung', 'url' => 'https://www.php.net/supported-versions.php'))
	)
);
