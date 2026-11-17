<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$page_title = 'Belajar CodeIgniter 3 dari dasar';
$this->load->view('learn/header', array('page_title' => $page_title));
?>
<main>
	<section class="hero">
		<div class="hero-inner">
			<div class="hero-copy">
				<div class="eyebrow"><span class="status-dot"></span> PANDUAN PRAKTIS CODEIGNITER 3</div>
				<h1>Belajar lebih terarah.<br><em>Bangun lebih percaya diri.</em></h1>
				<p>Dari konsep dasar sampai aplikasi siap dijalankan. Pelajari CodeIgniter 3 selangkah demi selangkah lewat satu proyek nyata.</p>
				<div class="hero-actions">
					<a class="button button-dark" href="<?= html_escape(learn_site_url('modul/prasyarat')) ?>">Mulai perjalanan <span aria-hidden="true">↗</span></a>
					<a class="text-link" href="#kurikulum">Lihat kurikulum <span aria-hidden="true">↓</span></a>
				</div>
				<div class="hero-meta"><span><b>12</b> modul terarah</span><span><b>±52</b> jam belajar</span><span><b>1</b> proyek nyata</span></div>
			</div>
			<div class="hero-visual" aria-hidden="true">
				<div class="orbit orbit-one"></div><div class="orbit orbit-two"></div>
				<div class="visual-card">
					<div class="visual-top"><span class="mini-mark">&lt;/&gt;</span><span>YOUR LEARNING PATH</span><span>✳</span></div>
					<div class="visual-title">Dari ide<br>jadi aplikasi<span>.</span></div>
					<div class="visual-line"><span>01</span><i></i><b>Dasar &amp; MVC</b><em>✓</em></div>
					<div class="visual-line"><span>02</span><i></i><b>Bangun fitur</b><em>↗</em></div>
					<div class="visual-line"><span>03</span><i></i><b>Siap meluncur</b><em>✳</em></div>
					<div class="visual-bottom"><span>THE BEST WAY TO LEARN</span><strong>is to build.</strong></div>
				</div>
				<div class="floating-note"><span>✦</span> PROGRESS, NOT PERFECTION</div>
			</div>
		</div>
	</section>
	<div class="marquee" aria-hidden="true"><div>MEMAHAMI KONSEP <span>✳</span> LANGSUNG PRAKTIK <span>✳</span> MEMBANGUN PROYEK <span>✳</span> BELAJAR BERTAHAP <span>✳</span> MEMAHAMI KONSEP <span>✳</span> LANGSUNG PRAKTIK <span>✳</span></div></div>
	<section class="section intro-section" id="cara-belajar">
		<div class="section-heading"><div><div class="eyebrow muted">01 / CARA BELAJAR</div><h2>Bukan cuma membaca.<br><em>Benar-benar memahami.</em></h2></div><p>Setiap modul dirancang untuk membantumu memahami alasan di balik kode, lalu menerapkannya sendiri.</p></div>
		<div class="steps">
			<article><div class="step-top"><span>01</span><span>↗</span></div><h3>Pahami konsepnya</h3><p>Kenali alur dan peran setiap bagian sebelum mulai menulis kode.</p></article>
			<article><div class="step-top"><span>02</span><span>⌘</span></div><h3>Bangun bertahap</h3><p>Ikuti contoh dan kembangkan satu aplikasi artikel dari awal.</p></article>
			<article><div class="step-top"><span>03</span><span>✳</span></div><h3>Uji pemahaman</h3><p>Kerjakan latihan dan evaluasi sebelum melangkah ke materi berikutnya.</p></article>
		</div>
	</section>
	<section class="curriculum-wrap" id="kurikulum">
		<div class="section">
			<div class="section-heading curriculum-heading"><div><div class="eyebrow muted">02 / JALUR PEMBELAJARAN</div><h2>Perjalananmu<br><em>dimulai di sini.</em></h2></div><p>Ikuti urutannya, sesuaikan dengan ritmemu, dan rayakan setiap langkah kecil.</p></div>
			<div class="curriculum-toolbar"><div><span class="small-label">KURIKULUM LENGKAP</span><strong>12 modul <span>/ dari dasar hingga deployment</span></strong></div><div class="progress-summary" data-progress-summary>0 dari 12 selesai</div></div>
			<div class="progress-track"><span data-progress-bar></span></div>
			<div class="module-list">
			<?php $number = 0; foreach ($modules as $slug => $module): ?>
				<a class="module-row" href="<?= html_escape(learn_site_url('modul/'.$slug)) ?>" data-module-slug="<?= html_escape($slug) ?>">
					<span class="module-number"><?= sprintf('%02d', $number++) ?></span>
					<span class="module-main"><span class="module-category"><?= html_escape($module['category']) ?></span><strong><?= html_escape($module['title']) ?></strong><span><?= html_escape($module['summary']) ?></span></span>
					<span class="module-duration"><?= html_escape($module['duration']) ?> jam</span><span class="module-check" aria-label="Belum ditandai selesai">↗</span>
				</a>
			<?php endforeach; ?>
			</div>
			<p class="progress-note" data-storage-note>Progres disimpan di browser perangkat ini, bukan di akun.</p>
		</div>
	</section>
	<section class="section final-section"><div><div class="eyebrow">SELANGKAH DEMI SELANGKAH</div><h2>Siap membuat<br><em>sesuatu yang nyata?</em></h2><p>Tidak perlu tahu semuanya hari ini. Mulai dari langkah pertama.</p><a class="button button-light" href="<?= html_escape(learn_site_url('modul/prasyarat')) ?>">Mulai belajar sekarang <span aria-hidden="true">↗</span></a></div><div class="final-art" aria-hidden="true">✳</div></section>
</main>
<?php $this->load->view('learn/footer'); ?>
