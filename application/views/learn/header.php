<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Belajar CodeIgniter 3 melalui modul berurutan dan proyek artikel.">
	<title><?= html_escape($page_title) ?> | Ruang CI</title>
	<link rel="stylesheet" href="<?= html_escape(learn_asset_url('assets/css/learn.css')) ?>">
	<script src="<?= html_escape(learn_asset_url('assets/js/learn.js')) ?>" defer></script>
</head>
<body>
<header class="topbar">
	<div class="topbar-inner">
		<a class="brand" href="<?= html_escape(learn_site_url()) ?>" aria-label="Ruang CI, beranda">
			<span class="brand-mark">&lt;/&gt;</span><span>ruang<span class="brand-accent">ci</span><small>LEARN BY BUILDING</small></span>
		</a>
		<nav aria-label="Navigasi utama">
			<a href="<?= html_escape(learn_site_url()) ?>#kurikulum">Kurikulum</a>
			<a href="<?= html_escape(learn_site_url()) ?>#cara-belajar">Cara belajar</a>
			<a class="nav-cta" href="<?= html_escape(learn_site_url('modul/prasyarat')) ?>" data-start-learning data-complete-url="<?= html_escape(learn_site_url()) ?>#kurikulum">Mulai belajar <span aria-hidden="true">↗</span></a>
		</nav>
	</div>
</header>
