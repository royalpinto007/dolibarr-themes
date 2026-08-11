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

	/* Keep the two frequent actions visible and collect the existing low-frequency
	   controls in one disclosure. The controls themselves are moved intact: Clone
	   and Delete are hook-driven spans on current Dolibarr, while Merge is a
	   tokenised anchor. Recreating any of them would lose behaviour. */
	function groupRecordActions() {
		var bar = document.querySelector('div.tabsAction[data-ts-moved="1"]');
		if (!bar || bar.querySelector('.ts-more-actions')) { return false; }

		var edit = bar.querySelector('a[href*="action=edit"], .butActionNew');
		var send = bar.querySelector('#btn-send-mail, a[href*="action=presend"], a[href*="action=send"]');
		if (edit) { edit.classList.add('ts-record-primary'); }
		if (send) { send.classList.add('ts-record-secondary'); }

		var overflow = [];
		['#action-clone', 'a[href*="action=clone"]', 'a[href*="action=merge"]',
		 '#action-delete', 'a[href*="action=delete"]'].forEach(function (selector) {
			var control = bar.querySelector(selector);
			if (control && overflow.indexOf(control) === -1) { overflow.push(control); }
		});
		if (!overflow.length) { return Boolean(edit || send); }

		var details = document.createElement('details');
		details.className = 'ts-more-actions';
		var summary = document.createElement('summary');
		summary.className = 'ts-more-actions-trigger';
		summary.setAttribute('aria-label', 'More actions');
		summary.appendChild(document.createTextNode('More actions'));
		var chevron = document.createElement('span');
		chevron.className = 'fas fa-chevron-down';
		chevron.setAttribute('aria-hidden', 'true');
		summary.appendChild(chevron);
		var menu = document.createElement('div');
		menu.className = 'ts-more-actions-menu';
		menu.setAttribute('role', 'menu');
		overflow.forEach(function (control) {
			control.classList.add('ts-more-action-item');
			control.setAttribute('role', 'menuitem');
			menu.appendChild(control);
		});
		details.appendChild(summary);
		details.appendChild(menu);
		bar.appendChild(details);
		return true;
	}

	/* Dolibarr emits the tab strip before the record card. Moving that same node
	   directly after the banner makes header and navigation one composition, with
	   all tab hrefs, counters and module-injected entries unchanged. */
	function placeTabsBelowHeader() {
		var banner = findBanner();
		var tabs = document.querySelector('div.tabs');
		if (!banner || !tabs || tabs.getAttribute('data-ts-placed') === '1') { return false; }
		var card = banner.closest('div.tabBar');
		if (!card || !banner.parentNode) { return false; }
		banner.parentNode.insertBefore(tabs, banner.nextSibling);
		tabs.setAttribute('data-ts-placed', '1');
		card.classList.add('ts-entity-card');
		return true;
	}

	/* The companion module publishes translated labels and canonical field keys as
	   JSON. Annotation is the only label-aware step; layout below consumes stable
	   data-field/data-group attributes and never guesses from visible text. */
	function applyThirdPartyFieldSchema() {
		var source = document.getElementById('ts-thirdparty-field-schema');
		var card = document.querySelector('div.tabBar.ts-entity-card');
		if (!source || !card || card.querySelector('.ts-thirdparty-groups')) { return false; }
		var schema = JSON.parse(source.textContent || '{}');
		if (!schema.groups) { return false; }
		var center = card.querySelector('div.fichecenter');
		if (!center) { return false; }

		function normalized(value) { return (value || '').replace(/\s+/g, ' ').trim(); }
		var labels = {};
		Object.keys(schema.groups).forEach(function (groupKey) {
			var group = schema.groups[groupKey];
			Object.keys(group.fields || {}).forEach(function (fieldKey) {
				(group.fields[fieldKey] || []).forEach(function (label) {
					labels[normalized(label)] = {field: fieldKey, group: groupKey};
				});
			});
		});

		var rows = Array.from(center.querySelectorAll('div.fichehalfleft > table.tableforfield > tbody > tr, div.fichehalfright > table.tableforfield > tbody > tr'));
		if (!rows.length) { return false; }
		rows.forEach(function (row) {
			var match = labels[normalized(row.cells[0] && row.cells[0].innerText)];
			if (!match) { return; }
			row.setAttribute('data-field', match.field);
			row.setAttribute('data-group', match.group);
		});
		/* Custom modules may add fields unknown to the companion schema. In that
		   case keep the native two-column structure rather than dropping or
		   misclassifying even one row. The stable attributes already applied remain
		   useful to future schemas and diagnostics. */
		if (rows.some(function (row) { return !row.hasAttribute('data-field'); })) { return false; }

		var groups = document.createElement('div');
		groups.className = 'ts-thirdparty-groups';
		Object.keys(schema.groups).forEach(function (groupKey) {
			var definition = schema.groups[groupKey];
			var groupRows = rows.filter(function (row) { return row.getAttribute('data-group') === groupKey; });
			if (!groupRows.length) { return; }
			var section = document.createElement('section');
			section.className = 'ts-field-group';
			section.setAttribute('data-group', groupKey);
			var heading = document.createElement('h2');
			heading.className = 'ts-field-group-title';
			heading.textContent = definition.title || groupKey;
			var table = document.createElement('table');
			table.className = 'border tableforfield centpercent';
			var body = document.createElement('tbody');
			groupRows.forEach(function (row) { body.appendChild(row); });
			table.appendChild(body);
			section.appendChild(heading);
			section.appendChild(table);
			groups.appendChild(section);
		});
		if (groups.children.length !== 3) { return false; }
		center.insertBefore(groups, center.firstChild);
		center.querySelectorAll(':scope > div.fichehalfleft, :scope > div.fichehalfright').forEach(function (half) {
			if (!half.querySelector('tr')) { half.remove(); }
		});
		center.classList.add('ts-thirdparty-grouped');
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

		/* The breadcrumb now carries this destination, so the in-banner copy is
		   redundant. Marking the source lets CSS hide that one anchor while the
		   pager's prev/next arrows beside it keep working. */
		back.classList.add('ts-crumb-source');
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

	/* Dolibarr already wraps a list's row count in its own span inside div.titre
	   ("(39)"), so nothing needs parsing out of the title: that span is tagged for
	   the badge styling and its parentheses dropped, since the badge shape now
	   carries that meaning. Only a span whose whole text is parenthesised digits is
	   touched, so a title without a count is left alone. */
	function markCount() {
		var titles = document.querySelectorAll('div.titre');
		if (!titles.length) { return false; }
		var done = false;
		titles.forEach(function (t) {
			t.querySelectorAll('span').forEach(function (sp) {
				if (sp.classList.contains('ts-count')) { return; }
				var m = (sp.textContent || '').trim().match(/^\((\d[\d\s.,]*)\)$/);
				if (!m) { return; }
				sp.textContent = m[1].trim();
				sp.classList.add('ts-count');
				done = true;
			});
		});
		return done;
	}

	/* List page header.
	   The target puts the title and count on the left and a labelled create action
	   on the right. Dolibarr prints the title in one cell and the create link in the
	   pager cell beside the view switches, where a label has nowhere to go.

	   The nodes are MOVED into a header row, never rebuilt: the create control is an
	   <a href> carrying its own token, so re-parenting it cannot affect any form's
	   submission -- no input, select or button is touched, and every form keeps its
	   own fields. The label is a real text node taken from the anchor's own title
	   attribute, which Dolibarr has already translated; nothing is invented and no
	   CSS content is used. */
	function buildPageHeader() {
		if (document.querySelector('.ts-pagehead')) { return false; }
		var title = document.querySelector('div.titre');
		/* Dolibarr uses the same td.col-title cell for a list's page title and for the
		   heading of a section under a record, so that class cannot tell them apart.
		   The section headings are the ones inside div.fichecenter; a page title is
		   not, which is the distinction used here. */
		if (!title || title.closest('div.fichecenter')) { return false; }
		var list = document.querySelector('div.div-table-responsive, div.div-table-responsive-no-min, table.liste');
		if (!list) { return false; }                                   // not a list page

		var head = document.createElement('div');
		head.className = 'ts-pagehead';
		var left = document.createElement('div');
		left.className = 'ts-pagehead-title';
		var right = document.createElement('div');
		right.className = 'ts-pagehead-actions';
		head.appendChild(left);
		head.appendChild(right);

		/* Dolibarr lays the title bar out as a table, so anchoring inside the title's
		   own cell would confine the header to that cell's width -- which is what
		   clipped the labelled action. The header is placed before the whole table
		   instead, where it can span the content width. */
		var mount = title.closest('table') || title.closest('div') || title;
		if (!mount.parentNode) { return false; }
		mount.parentNode.insertBefore(head, mount);
		left.appendChild(title);

		var create = null;
		document.querySelectorAll('a.btnTitle').forEach(function (a) {
			if (create) { return; }
			if (a.querySelector('.fa-plus-circle, .fa-plus')) { create = a; }
		});
		if (create) {
			right.appendChild(create);
			create.classList.add('ts-primary-action');
			/* Give it a visible label only if it has none: a real node, from the
			   anchor's own translated title. */
			var hasText = (create.textContent || '').trim().length > 0;
			var label = (create.getAttribute('title') || '').trim();
			if (!hasText && label) {
				var span = document.createElement('span');
				span.className = 'ts-action-label';
				span.textContent = label;
				create.appendChild(span);
			}
		}
		return true;
	}

	/* Compose the filters, table and result navigation as one list surface. All
	   form controls remain inside their original form and the real table wrapper
	   is moved rather than copied. Only the footer pager is a navigation-only copy
	   of hrefs already rendered by Dolibarr. */
	function composeListSurface() {
		if (document.querySelector('.ts-list-composition')) { return false; }
		var title = document.querySelector('.ts-pagehead div.titre');
		if (!title || title.closest('div.fichecenter')) { return false; }
		var listWrap = document.querySelector('div.div-table-responsive, div.div-table-responsive-no-min');
		var list = listWrap && listWrap.querySelector('table.liste');
		var form = listWrap && listWrap.closest('form');
		if (!list || !form) { return false; }

		var filter = form.querySelector('div.liste_titre.liste_titre_bydiv');
		var composition = document.createElement('section');
		composition.className = 'ts-list-composition';
		composition.setAttribute('aria-label', (title.textContent || '').replace(/\s+/g, ' ').trim());
		listWrap.parentNode.insertBefore(composition, filter || listWrap);
		if (filter) {
			filter.classList.add('ts-filter-surface');
			composition.appendChild(filter);
		}
		var card = document.createElement('div');
		card.className = 'ts-list-card';
		composition.appendChild(card);
		card.appendChild(listWrap);

		var totalNode = title.querySelector('.ts-count');
		var total = totalNode ? parseInt((totalNode.textContent || '').replace(/[^0-9]/g, ''), 10) : NaN;
		var limitSelect = form.querySelector('select[name="limit"], select.selectlimit');
		var limit = limitSelect ? parseInt(limitSelect.value, 10) : NaN;
		var pageInput = form.querySelector('input[name="pageplusone"], .pageplusone input');
		var current = pageInput ? parseInt(pageInput.value, 10) : 1;
		if (!current || current < 1) { current = 1; }
		var visibleRows = list.querySelectorAll('tbody tr.oddeven').length;
		if (!limit || limit < 1) { limit = visibleRows || total || 0; }
		var first = total === 0 ? 0 : ((current - 1) * limit) + 1;
		var last = Number.isNaN(total) ? first + Math.max(visibleRows - 1, 0) : Math.min(total, first + Math.max(visibleRows - 1, 0));

		var footer = document.createElement('footer');
		footer.className = 'ts-results-footer';
		var summary = document.createElement('span');
		summary.className = 'ts-results-summary';
		summary.textContent = Number.isNaN(total) ? (first + '\u2013' + last) : (first + '\u2013' + last + ' of ' + total);
		footer.appendChild(summary);
		var topPager = form.querySelector('table.table-fiche-title div.pagination');
		var pagerList = topPager && topPager.querySelector('ul');
		if (pagerList) {
			var nav = document.createElement('nav');
			nav.className = 'ts-results-nav';
			nav.setAttribute('aria-label', 'Results pages');
			var links = pagerList.cloneNode(true);
			/* The top pager nests its page-size select and current-page input inside
			   the same list. A literal clone would introduce duplicate named controls
			   into this form, so footer copies are inert text while navigation anchors
			   remain real links. */
			links.querySelectorAll('select, input, button').forEach(function (control) {
				var value = control.tagName === 'SELECT'
					? (control.options[control.selectedIndex] || {}).text
					: (control.value || control.textContent || '');
				var display = document.createElement('span');
				display.className = 'ts-pager-value';
				display.textContent = (value || '').trim();
				control.replaceWith(display);
			});
			links.querySelectorAll('[id], [accesskey]').forEach(function (n) {
				n.removeAttribute('id');
				n.removeAttribute('accesskey');
			});
			nav.appendChild(links);
			footer.appendChild(nav);
		}
		card.appendChild(footer);
		return true;
	}

	ready(function () {
		try { buildPageHeader(); } catch (e) { /* keep Dolibarr's header */ }
		try { markCount(); } catch (e) { /* leave the title as Dolibarr printed it */ }
		try { composeListSurface(); } catch (e) { /* leave the list's native structure */ }
		try { moveActionsIntoHeader(); } catch (e) { /* leave Dolibarr's layout alone */ }
		try { groupRecordActions(); } catch (e) { /* retain the original action row */ }
		try { placeTabsBelowHeader(); } catch (e) { /* retain Dolibarr's tab placement */ }
		try { applyThirdPartyFieldSchema(); } catch (e) { /* retain the native field layout */ }
		try { buildBreadcrumb(); } catch (e) { /* idem */ }
	});
})();
