/* Modern record header — thriveshell.
 *
 * Dolibarr prints a record's action bar (Modify / Clone / Delete ...) *after* the
 * content, and its breadcrumb only as a "Back to list" link. The mockup wants the
 * primary actions beside the title. That is a markup ordering problem, so CSS
 * cannot do it; this moves the existing nodes instead of re-rendering them, which
 * keeps every href, id, onclick, confirm dialog and module hook intact.
 *
 * Defensive by construction:
 *   - every step is independently guarded; a missing node skips that step only
 *   - nodes are MOVED, never cloned or rebuilt, so no handler is lost
 *   - it runs once, marks what it touched, and never re-parents the same node twice
 *   - anything unexpected is caught and left exactly as Dolibarr rendered it
 * A third-party module that lays its card out differently simply keeps its own
 * layout rather than being reshaped into one it did not ask for.
 */
(function () {
	'use strict';

	function ready(fn) {
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', fn, {once: true});
		} else {
			fn();
		}
	}

	/* The banner Dolibarr puts a record's title in. Both spellings exist across
	   versions and modules, so accept either and bail if neither is present. */
	function findBanner() {
		return document.querySelector('div.arearef, div.tabBar > div.arearef, .arearefnoborder');
	}

	function moveActionsIntoHeader() {
		var bar = document.querySelector('div.tabsAction');
		var banner = findBanner();
		if (!bar || !banner) { return false; }               // not a record page
		if (bar.getAttribute('data-ts-moved') === '1') { return false; }
		/* Only relocate a bar that actually holds actions. An empty one on a
		   read-only record would otherwise leave a stray flex row in the header. */
		if (!bar.querySelector('a, input, button, div.inline-block')) { return false; }

		var host = document.createElement('div');
		host.className = 'ts-header-actions';
		/* The bar keeps its own class list, so existing CSS and any module rule
		   that targets div.tabsAction still applies after the move. */
		host.appendChild(bar);
		banner.appendChild(host);
		bar.setAttribute('data-ts-moved', '1');
		banner.classList.add('ts-has-actions');
		return true;
	}

	/* "Back to list" is Dolibarr's own breadcrumb; the mockup shows it as a trail
	   above the card. Re-using that anchor keeps the URL the module chose. */
	function buildBreadcrumb() {
		var banner = findBanner();
		if (!banner || document.querySelector('.ts-breadcrumb')) { return false; }
		var back = document.querySelector('.pagination.paginationref a, a.paginationref');
		if (!back || !back.getAttribute('href')) { return false; }

		var title = banner.querySelector('.refid, .refidno, h1');
		var crumb = document.createElement('nav');
		crumb.className = 'ts-breadcrumb';
		crumb.setAttribute('aria-label', 'Breadcrumb');

		var parent = document.createElement('a');
		parent.href = back.getAttribute('href');
		parent.textContent = (back.textContent || '').trim();
		crumb.appendChild(parent);

		if (title) {
			var sep = document.createElement('span');
			sep.className = 'ts-breadcrumb-sep';
			sep.setAttribute('aria-hidden', 'true');
			sep.textContent = '/';
			crumb.appendChild(sep);
			var here = document.createElement('span');
			here.className = 'ts-breadcrumb-current';
			/* The banner holds more than the name: a location line, status badges and
			   -- on some cards -- Dolibarr's hidden dialog host, whose text content is
			   still readable even though it never paints. Read a detached copy with
			   that furniture removed, then keep the first line only. */
			var probe = title.cloneNode(true);
			probe.querySelectorAll(
				'.hidden, .hideobject, [id^="idfordialog"], script, style, .badge, .refidno, .ts-breadcrumb'
			).forEach(function (n) { n.remove(); });
			var name = (probe.textContent || '').replace(/\s+/g, ' ').trim();
			here.textContent = name.slice(0, 80);
			if (!name) { return false; }
			crumb.appendChild(here);
		}
		/* Placed before the card the banner belongs to, falling back to the banner
		   itself when the card wrapper is not where this version puts it. */
		var card = banner.closest('div.fichecenter, div.tabBar') || banner;
		if (card.parentNode) { card.parentNode.insertBefore(crumb, card); }
		return true;
	}

	ready(function () {
		try { moveActionsIntoHeader(); } catch (e) { /* leave Dolibarr's layout alone */ }
		try { buildBreadcrumb(); } catch (e) { /* idem */ }
	});
})();
