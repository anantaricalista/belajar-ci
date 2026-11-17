<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$page_title = $module['title'];
$this->load->view('learn/header', array('page_title' => $page_title));
?>
<main class="lesson-page" data-current-module="<?= html_escape($slug) ?>">
	<div class="lesson-shell">
		<div class="breadcrumbs"><a href="<?= html_escape(learn_site_url()) ?>">Beranda</a><span>/</span><a href="<?= html_escape(learn_site_url()) ?>#kurikulum">Kurikulum</a><span>/</span><span>Modul <?= sprintf('%02d', $number) ?></span></div>
		<div class="lesson-layout">
			<aside class="lesson-sidebar" aria-label="Daftar modul">
				<div class="sidebar-title">JALUR BELAJAR <span><?= $total ?> MODUL</span></div>
				<?php $i = 0; foreach ($modules as $item_slug => $item): ?>
					<a class="sidebar-link<?= $item_slug === $slug ? ' active' : '' ?>" href="<?= html_escape(learn_site_url('modul/'.$item_slug)) ?>"<?= $item_slug === $slug ? ' aria-current="page"' : '' ?>><span><?= sprintf('%02d', $i++) ?></span><?= html_escape($item['title']) ?></a>
				<?php endforeach; ?>
			</aside>
			<article class="lesson-content">
				<div class="lesson-heading"><div class="eyebrow muted">MODUL <?= sprintf('%02d', $number) ?> / <?= html_escape($module['category']) ?></div><h1><?= html_escape($module['title']) ?><span>.</span></h1><p><?= html_escape($module['summary']) ?></p><div class="lesson-meta"><span>◷ &nbsp;± <?= html_escape($module['duration']) ?> jam</span><span>✳ &nbsp;Teori + praktik</span></div></div>
				<div class="lesson-callout"><span class="callout-icon">↗</span><div><h2>Yang akan kamu pelajari</h2><ul><?php foreach ($module['goals'] as $goal): ?><li><?= html_escape($goal) ?></li><?php endforeach; ?></ul></div></div>
				<?php foreach ($module['sections'] as $section): ?>
				<section class="lesson-section">
					<h2><?= html_escape($section['title']) ?></h2>
					<?php foreach ($section['paragraphs'] as $paragraph): ?><p><?= html_escape($paragraph) ?></p><?php endforeach; ?>
					<?php if ( ! empty($section['code'])): ?><div class="code-block"><div class="code-label"><span>CONTOH</span><span><?= html_escape($section['file']) ?></span></div><pre><code><?= html_escape($section['code']) ?></code></pre></div><?php endif; ?>
				</section>
				<?php endforeach; ?>
				<section class="exercise"><div class="eyebrow">SAATNYA PRAKTIK <span>✳</span></div><h2><?= html_escape($module['exercise']['title']) ?></h2><p><?= html_escape($module['exercise']['intro']) ?></p><ol><?php foreach ($module['exercise']['steps'] as $step): ?><li><?= html_escape($step) ?></li><?php endforeach; ?></ol><div class="done-criteria"><strong>Kriteria selesai</strong><p><?= html_escape($module['exercise']['criteria']) ?></p></div></section>
				<section class="lesson-section review-section"><div class="eyebrow muted">CEK PEMAHAMAN</div><h2>Sudah paham?</h2><p>Coba jawab tanpa melihat materi, lalu buka petunjuknya untuk memeriksa jawabanmu.</p><?php foreach ($module['review'] as $review): ?><details><summary><?= html_escape($review['question']) ?></summary><p><?= html_escape($review['answer']) ?></p></details><?php endforeach; ?></section>
				<section class="resources"><strong>Pelajari lebih lanjut ↗</strong><div><?php foreach ($module['resources'] as $resource): ?><a href="<?= html_escape($resource['url']) ?>" target="_blank" rel="noopener noreferrer"><?= html_escape($resource['title']) ?> ↗</a><?php endforeach; ?></div></section>
				<div class="complete-box"><div><strong>Sudah menyelesaikan modul ini?</strong><p>Tandai untuk menyimpan progres di browser ini.</p><small data-storage-note></small></div><button type="button" class="button button-dark" data-complete-button>Tandai selesai <span aria-hidden="true">✓</span></button></div>
				<nav class="lesson-pagination" aria-label="Navigasi modul">
					<?php if ($previous !== NULL): ?><a href="<?= html_escape(learn_site_url('modul/'.$previous)) ?>"><small>← SEBELUMNYA</small><strong><?= html_escape($modules[$previous]['title']) ?></strong></a><?php else: ?><span></span><?php endif; ?>
					<?php if ($next !== NULL): ?><a class="pagination-next" href="<?= html_escape(learn_site_url('modul/'.$next)) ?>"><small>SELANJUTNYA →</small><strong><?= html_escape($modules[$next]['title']) ?></strong></a><?php else: ?><a class="pagination-next" href="<?= html_escape(learn_site_url()) ?>#kurikulum"><small>SELESAI →</small><strong>Kembali ke kurikulum</strong></a><?php endif; ?>
				</nav>
			</article>
		</div>
	</div>
</main>
<?php $this->load->view('learn/footer'); ?>
