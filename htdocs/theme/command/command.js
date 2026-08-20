/* COMMAND theme -- command palette.
 *
 * Loaded by Dolibarr as theme/<theme>/<theme>.js when ALLOW_THEME_JS=1.
 * The navigation tree is embedded in the page as JSON by command.lib.php, so
 * the palette needs no network round-trip.
 */
(function () {
	'use strict';

	var data = [];
	var palette, input, results, trigger;
	var matches = [];
	var cursor = 0;
	var lastFocus = null;

	function boot() {
		var node = document.getElementById('cmd-nav-data');
		palette = document.getElementById('cmd-palette');
		input = document.getElementById('cmd-palette-input');
		results = document.getElementById('cmd-palette-results');
		trigger = document.getElementById('cmd-trigger');

		if (!node || !palette || !input || !results) {
			return;
		}
		try {
			data = JSON.parse(node.textContent || '[]');
		} catch (e) {
			data = [];
		}

		if (trigger) {
			trigger.addEventListener('click', open);
		}
		input.addEventListener('input', function () {
			render(input.value);
		});
		palette.addEventListener('click', function (ev) {
			if (ev.target && ev.target.hasAttribute('data-cmd-close')) {
				close();
			}
		});
		document.addEventListener('keydown', onGlobalKey, true);
		input.addEventListener('keydown', onInputKey);

		relocateTools();
		initNav();
		initTree();
	}

	/* Foldable navigation tree. Open branches persist per user so a module you
	   work in daily does not need re-opening on every page load. */
	function initTree() {
		var KEY = 'thriveNavOpen';
		var open = {};
		try { open = JSON.parse(window.localStorage.getItem(KEY) || '{}'); } catch (e) { open = {}; }

		function keyFor(node) {
			var a = node.querySelector(':scope > .ts-row > a');
			return a ? a.getAttribute('href') : null;
		}

		// Restore remembered branches, without closing the one holding the
		// current page (the server already opened that path).
		var nodes = document.querySelectorAll('.ts-node.has-kids');
		for (var i = 0; i < nodes.length; i++) {
			var k = keyFor(nodes[i]);
			if (k && open[k]) {
				nodes[i].classList.add('is-open');
			}
		}

		document.addEventListener('click', function (ev) {
			var btn = ev.target.closest ? ev.target.closest('[data-ts-fold]') : null;
			if (!btn) {
				return;
			}
			ev.preventDefault();
			ev.stopPropagation();
			var node = btn.closest('.ts-node');
			if (!node) {
				return;
			}
			var on = node.classList.toggle('is-open');
			btn.setAttribute('aria-expanded', on ? 'true' : 'false');
			var k = keyFor(node);
			if (k) {
				if (on) { open[k] = 1; } else { delete open[k]; }
				try { window.localStorage.setItem(KEY, JSON.stringify(open)); } catch (e) {}
			}
		});
	}


	/* Collapse state is a per-user working preference, so it persists locally
	   and is applied before paint to avoid a visible jump. */
	function initNav() {
		var toggle = document.getElementById('cmd-nav-toggle');
		if (!toggle) {
			return;
		}
		var narrow = window.matchMedia('(max-width: 992px)');
		function closeDrawer() {
			document.body.classList.remove('cmd-nav-open');
		}
		toggle.addEventListener('click', function () {
			/* A narrow viewport already renders the nav as an icon rail with its
			   labels and sub-entries dropped, so collapsing it further has
			   nothing left to act on. There this control opens the nav over the
			   page instead, which is the only way back to the sub-entries. */
			if (narrow.matches) {
				document.body.classList.toggle('cmd-nav-open');
				return;
			}
			var on = document.body.classList.toggle('cmd-nav-collapsed');
			try {
				window.localStorage.setItem('cmdNavCollapsed', on ? '1' : '0');
			} catch (e) { /* private mode: fall back to per-page state */ }
		});
		/* The drawer covers the page, so it closes on anything that means the
		   reader is done with it: the backdrop, Escape, following a link, or the
		   viewport growing wide enough to show the nav in place again. */
		document.addEventListener('click', function (ev) {
			if (!document.body.classList.contains('cmd-nav-open')) { return; }
			if (!ev.target.closest) { return; }
			if (!ev.target.closest('aside.cmd-nav')) { closeDrawer(); return; }
			if (ev.target.closest('a[href]')) { closeDrawer(); }
		});
		document.addEventListener('keydown', function (ev) {
			if (ev.key === 'Escape' || ev.keyCode === 27) { closeDrawer(); }
		});
		if (narrow.addEventListener) {
			narrow.addEventListener('change', closeDrawer);
		}
	}

	/* Dolibarr prints its own tools/account block late in the document; move it
	   into the bar so the shell owns a single row. */
	function relocateTools() {
		var slot = document.getElementById('cmd-bar-tools');
		var block = document.querySelector('div.login_block');
		if (slot && block && block.parentNode !== slot) {
			slot.appendChild(block);
		}
	}

	function onGlobalKey(ev) {
		var key = ev.key;
		if ((ev.ctrlKey || ev.metaKey) && (key === 'k' || key === 'K')) {
			ev.preventDefault();
			ev.stopPropagation();
			if (palette.hasAttribute('hidden')) {
				open();
			} else {
				close();
			}
			return;
		}
		if (key === 'Escape' && !palette.hasAttribute('hidden')) {
			ev.preventDefault();
			close();
		}
	}

	function onInputKey(ev) {
		if (ev.key === 'ArrowDown') {
			ev.preventDefault();
			move(1);
		} else if (ev.key === 'ArrowUp') {
			ev.preventDefault();
			move(-1);
		} else if (ev.key === 'Enter') {
			ev.preventDefault();
			go(cursor);
		}
	}

	function open() {
		lastFocus = document.activeElement;
		palette.removeAttribute('hidden');
		document.body.classList.add('cmd-palette-open');
		input.value = '';
		render('');
		input.focus();
	}

	function close() {
		palette.setAttribute('hidden', '');
		document.body.classList.remove('cmd-palette-open');
		if (lastFocus && typeof lastFocus.focus === 'function') {
			lastFocus.focus();
		}
	}

	function move(delta) {
		if (!matches.length) {
			return;
		}
		cursor = (cursor + delta + matches.length) % matches.length;
		paint();
	}

	function go(i) {
		var hit = matches[i];
		if (hit && hit.u) {
			window.location.href = hit.u;
		}
	}

	/* Subsequence match, so "thpa" finds "Third parties". Scores prefix and
	   word-boundary hits above scattered ones. */
	function score(text, q) {
		if (!q) {
			return 1;
		}
		var t = text.toLowerCase();
		var idx = t.indexOf(q);
		if (idx === 0) {
			return 1000;
		}
		if (idx > 0) {
			return 700 - idx;
		}
		var ti = 0, qi = 0, hits = 0, boundary = 0;
		while (ti < t.length && qi < q.length) {
			if (t.charAt(ti) === q.charAt(qi)) {
				hits++;
				if (ti === 0 || t.charAt(ti - 1) === ' ') {
					boundary++;
				}
				qi++;
			}
			ti++;
		}
		return qi === q.length ? 100 + hits + boundary * 10 : -1;
	}

	function render(q) {
		var query = (q || '').trim().toLowerCase();
		var scored = [];
		for (var i = 0; i < data.length; i++) {
			var row = data[i];
			var s = score(row.t, query);
			if (s < 0 && query) {
				s = score(row.g + ' ' + row.t, query) - 200;
			}
			if (s >= 0) {
				scored.push({ row: row, s: s });
			}
		}
		scored.sort(function (a, b) {
			return b.s - a.s;
		});
		matches = scored.slice(0, 60).map(function (x) {
			return x.row;
		});
		cursor = 0;
		paint();
	}

	function paint() {
		if (!matches.length) {
			results.innerHTML = '<div class="cmd-palette-empty">No matches</div>';
			return;
		}
		var html = '';
		var lastGroup = null;
		for (var i = 0; i < matches.length; i++) {
			var m = matches[i];
			if (m.g !== lastGroup) {
				html += '<div class="cmd-res-group">' + esc(m.g) + '</div>';
				lastGroup = m.g;
			}
			html += '<a class="cmd-res' + (i === cursor ? ' is-active' : '') +
				'" role="option" data-i="' + i + '" href="' + esc(m.u) + '">' +
				'<span class="cmd-res-title">' + esc(m.t) + '</span>' +
				'<span class="cmd-res-group-tag">' + esc(m.g) + '</span></a>';
		}
		results.innerHTML = html;

		var active = results.querySelector('.cmd-res.is-active');
		if (active && active.scrollIntoView) {
			active.scrollIntoView({ block: 'nearest' });
		}
		var nodes = results.querySelectorAll('.cmd-res');
		for (var j = 0; j < nodes.length; j++) {
			nodes[j].addEventListener('mouseenter', function () {
				cursor = parseInt(this.getAttribute('data-i'), 10);
				paint();
			});
		}
	}

	function esc(s) {
		return String(s === undefined || s === null ? '' : s)
			.replace(/&/g, '&amp;').replace(/</g, '&lt;')
			.replace(/>/g, '&gt;').replace(/"/g, '&quot;');
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
})();

/* Load the shared thriveshell record-header layer. The path is resolved from this
   file's own <script src>, so it follows DOL_URL_ROOT wherever Dolibarr is mounted
   (subdirectory installs included) without needing a global. Failure to load is
   silent: the page keeps Dolibarr's own layout. */
(function () {
	try {
		var me = document.currentScript ||
			(function () { var s = document.getElementsByTagName('script'); return s[s.length - 1]; })();
		if (!me || !me.src) { return; }
		var url = me.src.split('?')[0].replace(/\/theme\/[^/]+\/[^/]+\.js$/, '/core/thriveshell/modern.js');
		if (url === me.src.split('?')[0]) { return; }   // unexpected path: do nothing
		var loadFallback = function () {
			/* command.lib.php emits a content-versioned modern.js tag. Wait until
			   parsing is complete so that later tag is visible; only old menu
			   managers without the direct loader need this compatibility request. */
			if (document.querySelector('script[src*="/core/thriveshell/modern.js"]')) { return; }
			var s = document.createElement('script');
			s.src = url;
			s.defer = true;
			document.head.appendChild(s);
		};
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', loadFallback, {once: true});
		} else {
			loadFallback();
		}
	} catch (e) { /* keep Dolibarr's layout */ }
})();
