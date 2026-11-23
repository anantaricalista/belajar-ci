<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Curriculum_model extends CI_Model {

	public function all()
	{
		$modules = require APPPATH.'data/curriculum.php';
		$project_modules = require APPPATH.'data/curriculum_project.php';
		$snapshots = require APPPATH.'data/curriculum_snapshots.php';

		$this->append_snapshot($modules['routing-controller'], array(array(
			'file' => 'application/controllers/Siswa.php',
			'code' => $modules['routing-controller']['sections'][0]['code'],
			'explanations' => array('Controller ini menerima URL daftar dan detail; model dan view baru dibuat di modul berikutnya.')
		)), 'Pada tahap ini belum ada model atau view siswa. Pertahankan konfigurasi URL, .htaccess, dan route dari bagian sebelumnya.');

		$this->append_snapshot($modules['views'], $snapshots['views'],
			'Ganti seluruh isi kedua file berikut. Data masih berupa array di controller; model dan database baru ditambahkan pada modul berikutnya.');
		$this->append_snapshot($modules['database-model'], $snapshots['database-model'],
			'Ganti controller dan view daftar dengan versi berikut, lalu buat model dan view detail. Pertahankan konfigurasi database, URL, .htaccess, dan route yang telah dibuat.');

		$project_sections = $project_modules['proyek-akhir-crud-siswa']['sections'];
		$project_files = array(
			'application/config/config.php (baris konfigurasi)',
			'application/config/routes.php (ganti default route, tambahkan endpoint)',
			'application/models/Siswa_model.php',
			'application/controllers/Siswa.php',
			'application/views/siswa/index.php',
			'assets/js/siswa.js'
		);
		$project_snapshot = array();
		foreach ($project_files as $file)
		{
			$found = FALSE;
			foreach ($project_sections as $section)
			{
				if (isset($section['file']) && $section['file'] === $file)
				{
					$project_snapshot[] = $section;
					$found = TRUE;
					break;
				}
			}
			if ( ! $found)
			{
				throw new LogicException('Kode proyek tidak ditemukan: '.$file);
			}
		}

		$project_note = 'Ini adalah versi lengkap proyek satu halaman setelah modul ini. Pertahankan database dan .htaccess dari modul sebelumnya; ubah baris config dan routes yang ditunjukkan, lalu ganti seluruh isi model, controller dan view serta buat file JavaScript. Hapus route siswa/(:num) lama karena aplikasi ini tidak lagi memakai halaman detail terpisah. Form create biasa dari tahap sebelumnya digantikan modal; view lama tidak lagi digunakan. Contoh hanya untuk data fiktif lokal dan belum memiliki login.';
		$offline_found = FALSE;
		foreach ($project_sections as $section)
		{
			if (isset($section['file']) && $section['file'] === 'application/views/siswa/index.php (pengganti URL CDN)')
			{
				$section['title'] = 'Pilihan offline: simpan library di proyek';
				$section['paragraphs'][3] = 'Pada view lengkap di akhir modul, ganti kedua link CDN di head dan keempat script CDN di akhir body dengan fragmen lokal berikut; pertahankan konfigurasi window.siswaConfig dan script siswa.js terakhir. Jangan menambahkan keduanya sekaligus. Pastikan base_url sesuai alamat aplikasi dan helper URL dimuat oleh controller.';
				$section['explanations'][2] = 'Script aplikasi tetap berasal dari assets/js/siswa.js yang tersedia pada kode lengkap akhir modul; jangan menggandakan tag yang sudah ada.';
				$project_modules['jquery-datatables']['sections'][] = $section;
				$offline_found = TRUE;
				break;
			}
		}
		if ( ! $offline_found)
		{
			throw new LogicException('Panduan library offline tidak ditemukan.');
		}
		$this->append_snapshot($project_modules['jquery-datatables'], $project_snapshot, $project_note);
		$this->append_snapshot($modules['crud'], $project_snapshot, 'Kode lengkap CRUD yang dibahas adalah versi yang sama dengan tahap jQuery & DataTables. '.$project_note);
		foreach (array('keamanan', 'pencarian-debugging') as $slug)
		{
			$this->append_snapshot($modules[$slug], $project_snapshot,
				'Potongan dalam modul ini adalah contoh pendalaman terpisah, bukan perubahan yang otomatis diterapkan pada proyek. Snapshot berikut mempertahankan CRUD terakhir; contoh login dan otorisasi belum diimplementasikan dalam kode ini. '.$project_note);
		}
		$this->append_snapshot($project_modules['proyek-akhir-crud-siswa'], $project_snapshot,
			'Berikut salinan lengkap terakhir model, controller, view, dan JavaScript beserta konfigurasi yang diperlukan. Jangan membuat tabel lagi jika database tahap sebelumnya sudah ada.');

		$before_crud = array_slice($modules, 0, array_search('crud', array_keys($modules), TRUE), TRUE);
		$from_crud = array_slice($modules, count($before_crud), NULL, TRUE);

		return $before_crud
			+ array('jquery-datatables' => $project_modules['jquery-datatables'])
			+ $from_crud
			+ array('proyek-akhir-crud-siswa' => $project_modules['proyek-akhir-crud-siswa']);
	}

	private function append_snapshot(&$module, $files, $note)
	{
		$module['sections'][] = array(
			'title' => 'Kode lengkap pada akhir modul',
			'paragraphs' => array($note)
		);
		foreach ($files as $file)
		{
			$module['sections'][] = array(
				'title' => 'Kode terbaru: '.$file['file'],
				'paragraphs' => array('Lokasi file: '.$file['file'].'.'),
				'file' => $file['file'],
				'code' => $file['code'],
				'explanations' => $file['explanations']
			);
		}
	}
}
