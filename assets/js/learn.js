(function () {
	'use strict';

	var key = 'ruang-ci-completed-v1';
	var notes = document.querySelectorAll('[data-storage-note]');
	var button = document.querySelector('[data-complete-button]');
	var page = document.querySelector('[data-current-module]');
	var storageAvailable = true;
	var completed = [];

	function notify(message) {
		notes.forEach(function (note) { note.textContent = message; });
	}

	function loadProgress() {
		try {
			var saved = localStorage.getItem(key);
			var parsed = saved === null ? [] : JSON.parse(saved);
			if (!Array.isArray(parsed) || !parsed.every(function (item) { return typeof item === 'string'; })) {
				throw new Error('Data progres tidak valid');
			}
			completed = parsed;
			storageAvailable = true;
		} catch (error) {
			storageAvailable = false;
			notify('Progres tidak tersedia di browser ini. Periksa pengaturan penyimpanan browser.');
		}
	}

	loadProgress();

	function render() {
		var rows = document.querySelectorAll('[data-module-slug]');
		var count = 0;
		rows.forEach(function (row) {
			var done = completed.indexOf(row.getAttribute('data-module-slug')) !== -1;
			var indicator = row.querySelector('.module-check');
			row.classList.toggle('is-complete', done);
			indicator.textContent = done ? '✓' : '↗';
			indicator.setAttribute('aria-label', done ? 'Ditandai selesai' : 'Belum ditandai selesai');
			if (done) count++;
		});
		document.querySelectorAll('[data-sidebar-slug]').forEach(function (link) {
			var done = completed.indexOf(link.getAttribute('data-sidebar-slug')) !== -1;
			link.classList.toggle('is-complete', done);
			link.querySelector('[data-sidebar-status]').textContent = done ? ' (selesai)' : '';
		});
		var modules = rows.length ? rows : document.querySelectorAll('[data-sidebar-slug]');
		var nextModule = null;
		modules.forEach(function (module) {
			var slug = module.getAttribute('data-module-slug') || module.getAttribute('data-sidebar-slug');
			if (!nextModule && completed.indexOf(slug) === -1) nextModule = module;
		});
		document.querySelectorAll('[data-start-learning]').forEach(function (link) {
			if (storageAvailable && modules.length) {
				link.href = nextModule ? nextModule.href : link.getAttribute('data-complete-url');
			}
		});
		var summary = document.querySelector('[data-progress-summary]');
		var bar = document.querySelector('[data-progress-bar]');
		if (summary) summary.textContent = count + ' dari ' + rows.length + ' selesai';
		if (bar && rows.length) bar.style.width = (count / rows.length * 100) + '%';
		if (button && page) {
			var done = completed.indexOf(page.getAttribute('data-current-module')) !== -1;
			button.textContent = done ? 'Batalkan tanda selesai' : 'Tandai selesai ✓';
			button.setAttribute('aria-pressed', done ? 'true' : 'false');
			button.disabled = !storageAvailable;
		}
	}

	if (button && page) {
		button.addEventListener('click', function () {
			loadProgress();
			if (!storageAvailable) {
				render();
				return;
			}
			var slug = page.getAttribute('data-current-module');
			var next = completed.slice();
			var index = next.indexOf(slug);
			if (index === -1) next.push(slug);
			else next.splice(index, 1);
			try {
				localStorage.setItem(key, JSON.stringify(next));
				completed = next;
				notify('Progres tersimpan di browser perangkat ini.');
			} catch (error) {
				notify('Progres tidak dapat disimpan. Periksa pengaturan penyimpanan browser.');
			}
			render();
		});
	}

	document.querySelectorAll('[data-start-learning]').forEach(function (link) {
		link.addEventListener('click', function (event) {
			loadProgress();
			if (!storageAvailable) {
				event.preventDefault();
				window.alert('Progres tidak tersedia. Periksa pengaturan penyimpanan browser.');
				return;
			}
			render();
		});
	});

	window.addEventListener('pageshow', function () {
		loadProgress();
		render();
	});
	window.addEventListener('storage', function (event) {
		if (event.key === key || event.key === null) {
			loadProgress();
			render();
		}
	});

	render();
}());
