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
		return document.querySelector('div.arearef, div.tabBar > div.arearef, .arearefnoborder, div.tabBar > .arearefnobottom');
	}

	/* Every third-party tab carries the canonical overview tab, even when the
	   active page belongs to another module (Commerce, Tickets, Margins, etc.).
	   That tab is a more reliable record-context signal than pathname or mainmenu.
	   Mark the shared nodes once so header, breadcrumb and tabs can be normalized
	   without maintaining a list of module URLs. */
	function normalizeThirdPartyRecordContext() {
		var tabs = document.querySelector('div.tabs');
		var overview = tabs && Array.from(tabs.querySelectorAll('a.tab[href]')).find(function (link) {
			return /\/societe\/card\.php(?:\?|$)/.test(link.getAttribute('href') || '');
		});
		var banner = findBanner();
		if (!tabs || !overview || !banner) { return false; }
		document.body.classList.add('ts-thirdparty-record-context');
		banner.classList.add('ts-entity-banner');
		var card = banner.closest('div.tabBar');
		if (card) { card.classList.add('ts-thirdparty-record-shell'); }

		/* Banner variants float the photo, status and reference in different source
		   wrappers. Move those exact nodes into one identity group; their links and
		   tooltip handlers remain intact, while empty barcode/photo placeholders no
		   longer create phantom icon columns. */
		if (!banner.querySelector(':scope > .ts-entity-identity')) {
			var title = Array.from(banner.querySelectorAll('div.refid')).find(function (node) {
				return !node.closest('a.refid');
			});
			var photos = Array.from(banner.querySelectorAll('.divphotoref'));
			var photo = photos.find(function (node) {
				return Boolean(node.querySelector('img, [class*="fa-"]'));
			});
			if (title) {
				var identity = document.createElement('div');
				identity.className = 'ts-entity-identity';
				var photoSource = photo && photo.parentElement;
				if (photo) { identity.appendChild(photo); }
				identity.appendChild(title);
				banner.insertBefore(identity, banner.firstChild);
				photos.forEach(function (node) {
					if (node !== photo) { node.classList.add('ts-entity-photo-unused'); }
				});
				if (photoSource && photoSource !== banner) { photoSource.classList.add('ts-legacy-identity-source'); }
			}
		}
		return true;
	}

	function moveActionsIntoHeader() {
		var bar = document.querySelector('div.tabsAction');
		var banner = findBanner();
		if (!bar || !banner) { return false; }               // not a record page
		if (bar.getAttribute('data-ts-moved') === '1') { return false; }
		/* Only relocate a bar that actually holds actions. An empty one on a
		   read-only record would otherwise leave a stray flex row in the header. */
		if (!bar.querySelector('a, input, button, div.inline-block')) { return false; }
		/* Module tabs often print a second action row containing only tab-specific
		   creation actions. Those belong with the active content and must not change
		   the permanent entity header's height. Move only a bar that contains a real
		   record-level edit/send/clone/merge/delete control. */
		if (document.body.classList.contains('ts-thirdparty-record-context') && !bar.querySelector(
			'a[href*="action=edit"], #btn-send-mail, a[href*="action=presend"], #action-clone, a[href*="action=clone"], a[href*="action=merge"], #action-delete, a[href*="action=delete"]'
		)) { return false; }

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
		bindMoreActionsDismissal();
		return true;
	}

	/* A native <details> stays open until its own summary is clicked again, so
	   the menu sat there while the page was used behind it. Close it the way a
	   menu is expected to close -- clicking away, or Escape -- and return focus
	   to the trigger so keyboard use is not stranded. Bound once for the
	   document, however many menus a page ends up with. */
	var tsMoreActionsBound = false;
	function bindMoreActionsDismissal() {
		if (tsMoreActionsBound) { return; }
		tsMoreActionsBound = true;
		var openMenus = function () {
			return Array.prototype.slice.call(document.querySelectorAll('details.ts-more-actions[open]'));
		};
		document.addEventListener('click', function (event) {
			openMenus().forEach(function (details) {
				if (details.contains(event.target)) { return; }
				details.removeAttribute('open');
			});
		});
		document.addEventListener('keydown', function (event) {
			if (event.key !== 'Escape') { return; }
			openMenus().forEach(function (details) {
				details.removeAttribute('open');
				var trigger = details.querySelector('summary');
				if (trigger) { trigger.focus(); }
			});
		});
	}

	function polishEntityHeader() {
		var banner = findBanner();
		if (!banner || banner.getAttribute('data-ts-identity') === '1') { return false; }
		var title = banner.querySelector('.refid');
		var status = banner.querySelector('.statusref');
		if (title && status && !title.contains(status)) { title.appendChild(status); }
		var pager = banner.querySelector('.pagination.paginationref');
		if (pager && pager.parentNode !== banner) { banner.appendChild(pager); }
		banner.setAttribute('data-ts-identity', '1');
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
		document.body.classList.add('ts-command-record-page');
		return true;
	}

	/* Secondary record pages often use the same banner and tabs but swap the
	   overview content for a native form/table. Mark only that content boundary so
	   it receives the same surface language without changing module semantics. */
	function polishSharedRecordTabContent() {
		if (!document.body.classList.contains('ts-command-record-page')) { return false; }
		var tabs = document.querySelector('div.tabs[data-ts-placed="1"]');
		var active = tabs && tabs.querySelector('.tabsElemActive, .tabactive');
		if (!tabs || !active) { return false; }
		var entries = Array.from(tabs.querySelectorAll(':scope > .tabsElem'));
		if (entries.length && entries[0].contains(active)) { return false; }
		document.body.classList.add('ts-command-record-secondary');
		var card = tabs.closest('.tabBar');
		var node = card && card.nextElementSibling;
		while (node) {
			if (node.matches('br, .underbanner, .underrefbanner') || !(node.textContent || '').trim()) {
				node = node.nextElementSibling;
				continue;
			}
			if (node.matches('form, .div-table-responsive, .div-table-responsive-no-min, table.border, table.noborder')) {
				node.classList.add('ts-record-tab-native-surface');
			}
			node = node.nextElementSibling;
		}
		return true;
	}

	/* A select followed by a second visible control in the same cell is one field
	   in two parts -- a lead status and its win probability, for instance. Marked
	   so the stylesheet can size the select to a share of the cell instead of
	   stretching it and pushing its partner onto the next line. */
	function markPairedSelectCells(root) {
		Array.prototype.forEach.call((root || document).querySelectorAll('div.tabBar table.border:not(.liste) td'), function (cell) {
			if (cell.classList.contains('ts-measure-cell') || cell.classList.contains('ts-pair-cell')) { return; }
			var select = cell.querySelector(':scope > .select2-container');
			if (!select) { return; }
			var partner = Array.prototype.some.call(cell.children, function (node) {
				if (node === select || !node.matches('input, select, .select2-container')) { return false; }
				if (node.matches('[type="hidden"]')) { return false; }
				return node.getBoundingClientRect().width > 12;
			});
			if (partner) { cell.classList.add('ts-pair-cell'); }
		});
	}

	/* Widget columns sized from what they actually hold. The header is usually a
	   single title cell spanning every column, so fixed layout has nothing to size
	   from and splits them evenly -- a customer name ends up as wide as a status
	   dot. Guessing at column roles and pinning pixel widths starved the
	   neighbours on some pages; letting content size them overflowed the card on
	   others. So measure what each column wants, then express those as percentages
	   of the table: a share cannot overflow the table, and a floor keeps any
	   column from collapsing. */
	function sizeModuleIndexColumns(table) {
		var columnCount = Array.prototype.reduce.call(table.rows || [], function (count, row) {
			return row.classList.contains('liste_titre') ? count : Math.max(count, row.cells.length);
		}, 0);
		if (columnCount < 2) { return; }
		var rows = Array.prototype.filter.call(table.rows || [], function (row) {
			return !row.classList.contains('liste_titre')
				&& !row.classList.contains('ts-module-index-empty-row')
				&& row.cells.length === columnCount
				&& !Array.prototype.some.call(row.cells, function (cell) { return (cell.colSpan || 1) > 1; });
		});
		if (!rows.length) { return; }
		var existing = table.querySelector(':scope > colgroup.ts-module-index-cols');
		if (existing) { existing.remove(); }

		/* Measure against auto layout, which sizes each column to its content. The
		   stylesheet pins fixed layout with !important, so the override has to carry
		   the same weight to take effect for the measurement. */
		table.style.setProperty('table-layout', 'auto', 'important');
		var want = [];
		for (var index = 0; index < columnCount; index++) {
			var widest = 0;
			rows.forEach(function (row) {
				var cell = row.cells[index];
				if (!cell) { return; }
				widest = Math.max(widest, cell.scrollWidth, Math.ceil(cell.getBoundingClientRect().width));
			});
			want.push(Math.max(widest, 20));
		}
		table.style.removeProperty('table-layout');

		var total = want.reduce(function (sum, value) { return sum + value; }, 0);
		if (!total) { return; }
		var floor = Math.min(100 / (columnCount * 2.5), 12);
		var shares = want.map(function (value) { return Math.max((value / total) * 100, floor); });
		var scale = 100 / shares.reduce(function (sum, value) { return sum + value; }, 0);
		var group = document.createElement('colgroup');
		group.className = 'ts-module-index-cols';
		shares.forEach(function (value) {
			var col = document.createElement('col');
			col.style.width = (value * scale).toFixed(3) + '%';
			group.appendChild(col);
		});
		table.insertBefore(group, table.firstChild);
	}

	/* Standard module index pages reuse the same two-column dashboard boxes. The
	   Home and Third Party dashboards have richer adapters and remain excluded. */
	function polishSharedModuleIndex() {
		if (!/(^|\/)(index\.php)?$/.test(window.location.pathname)) { return false; }
		if (document.body.classList.contains('ts-command-dashboard') || document.body.classList.contains('ts-thirdparty-dashboard')) { return false; }
		var layout = document.querySelector('.fiche .twocolumns');
		if (!layout) {
			/* Several module dashboards -- shipments and the module-scoped index
			   pages such as comm/propal -- lay their two columns straight inside
			   .fichecenter with no .twocolumns wrapper, so matching that wrapper
			   alone left them on the legacy surface. Accept the outer container
			   instead, but only when it actually holds dashboard columns, so a page
			   that merely happens to have a .fichecenter is still left alone. */
			layout = Array.prototype.find.call(
				document.querySelectorAll('.fiche > .fichecenter, .fiche'),
				function (node) {
					return node.querySelector(':scope > .fichehalfleft, :scope > .fichehalfright, :scope > .boxhalfleft, :scope > .boxhalfright, :scope > .fichethirdleft, :scope > .fichetwothirdright');
				}
			) || null;
		}
		if (!layout) { return false; }
		document.body.classList.add('ts-command-module-index');
		if (/\/product\/(index\.php)?$/.test(window.location.pathname)) { document.body.classList.add('ts-products-module-index'); }
		if (/\/expedition\/(index\.php)?$/.test(window.location.pathname)) { document.body.classList.add('ts-shipments-module-index'); }
		layout.classList.add('ts-module-index-grid');
		layout.querySelectorAll(':scope > .fichehalfleft, :scope > .fichehalfright, :scope > .boxhalfleft, :scope > .boxhalfright, :scope > .fichethirdleft, :scope > .fichetwothirdright').forEach(function (column) {
			column.classList.add('ts-module-index-column');
			if (!(column.textContent || '').replace(/\s+/g, '').length) { column.classList.add('ts-module-index-empty'); }
			column.querySelectorAll('table.noborder, table.border').forEach(function (table) {
				table.classList.add('ts-module-index-card');
				var widestRow = Array.from(table.rows || []).reduce(function (count, row) { return Math.max(count, row.cells.length); }, 0);
				table.classList.toggle('ts-module-index-data-card', widestRow >= 4);
				/* Many index widgets render one title cell followed by structural empty
				   cells. Collapse only that exact pattern so the header remains one flat
				   surface without altering the table's data-column geometry. */
				var header = table.querySelector('tr.liste_titre');
				if (!header) { return; }
				header.classList.add('ts-module-index-card-header');
				var headingCell = header.querySelector('th, td');
				if (headingCell && !headingCell.querySelector('.ts-module-index-heading-icon')) {
					var sourceIcon = table.querySelector('tr:not(.liste_titre) [class*="fa-"]');
					var headingIcon = document.createElement('span');
					headingIcon.className = 'ts-module-index-heading-icon';
					var glyph = sourceIcon ? sourceIcon.cloneNode(true) : document.createElement('span');
					if (!sourceIcon) { glyph.className = 'fas fa-chart-pie'; }
					glyph.removeAttribute('title');
					glyph.removeAttribute('style');
					headingIcon.appendChild(glyph);
					headingCell.insertBefore(headingIcon, headingCell.firstChild);
				}
				var cells = Array.from(header.cells || []);
				var meaningful = cells.filter(function (cell) { return (cell.textContent || '').replace(/\s+/g, '').length; });
				if (meaningful.length !== 1 || cells.length < 2) { return; }
				var dataColumns = Array.from(table.rows || []).reduce(function (widest, row) {
					if (row === header) { return widest; }
					return Math.max(widest, Array.from(row.cells || []).reduce(function (sum, cell) { return sum + (cell.colSpan || 1); }, 0));
				}, 0);
				if (!dataColumns) { return; }
				meaningful[0].colSpan = dataColumns;
				cells.filter(function (cell) { return cell !== meaningful[0]; }).forEach(function (cell) { cell.remove(); });
			});
		});
		var pagehead = document.querySelector('.fiche > .ts-pagehead');
		if (pagehead && !pagehead.querySelector('.ts-module-index-subtitle')) {
			var subtitle = document.createElement('div');
			subtitle.className = 'ts-module-index-subtitle';
			subtitle.textContent = document.body.classList.contains('ts-products-module-index')
				? 'Overview of products, services, warehouses and stock activity.'
				: 'Overview of current activity and recent transactions.';
			pagehead.appendChild(subtitle);
		}
		layout.querySelectorAll('.ts-module-index-card tr:not(.liste_titre)').forEach(function (row) {
			var cells = Array.from(row.cells || []);
			if (!cells.length || cells.some(function (cell) { return (cell.textContent || '').trim().toLowerCase() !== 'none' && (cell.textContent || '').trim(); })) { return; }
			row.classList.add('ts-module-index-empty-row');
			cells[0].colSpan = cells.reduce(function (sum, cell) { return sum + (cell.colSpan || 1); }, 0);
			cells.slice(1).forEach(function (cell) { cell.remove(); });
			var message = cells[0].textContent.trim();
			cells[0].textContent = '';
			var emptyIcon = document.createElement('span');
			emptyIcon.className = 'ts-module-index-empty-icon fas fa-inbox';
			var emptyText = document.createElement('span');
			emptyText.textContent = message;
			/* Centre the icon and message inside their own flex column rather than
			   relying on the cell. The icon carried two competing display rules --
			   block from the cell-scoped rule and inline-flex from its own -- and the
			   loser left it 23px wide against the left edge with the glyph cut off. A
			   wrapper takes the cell's display out of the question entirely. */
			var emptyInner = document.createElement('div');
			emptyInner.className = 'ts-module-index-empty-inner';
			emptyInner.append(emptyIcon, emptyText);
			cells[0].appendChild(emptyInner);
		});
		layout.querySelectorAll('.ts-module-index-card .dolgraph').forEach(function (chartNode) {
			var chartCard = chartNode.closest('.ts-module-index-card');
			var canvas = chartCard && chartCard.querySelector('canvas');
			var chart = canvas && window.Chart && typeof window.Chart.getChart === 'function' ? window.Chart.getChart(canvas) : null;
			var values = chart && chart.data && chart.data.datasets && chart.data.datasets[0] && chart.data.datasets[0].data;
			var labels = chart && chart.data && chart.data.labels;
			if (chartCard && labels && values && !chartCard.nextElementSibling?.classList.contains('ts-module-stat-summary')) {
				var summary = document.createElement('section');
				summary.className = 'ts-module-stat-summary';
				var total = values.reduce(function (sum, value) { return sum + (Number(value) || 0); }, 0);
				var totalRow = document.createElement('div');
				totalRow.className = 'ts-module-stat-total';
				var moduleTitle = (document.querySelector('.fiche > .ts-pagehead .titre')?.textContent || 'Records').trim().replace(/\s+area$/i, '');
				totalRow.innerHTML = '<span class="ts-module-index-heading-icon"><span class="fas fa-chart-bar"></span></span><strong></strong><b></b>';
				totalRow.querySelector('strong').textContent = 'Total ' + moduleTitle.toLowerCase();
				totalRow.querySelector('b').textContent = String(total);
				var tiles = document.createElement('div');
				tiles.className = 'ts-module-stat-tiles';
				labels.forEach(function (label, index) {
					var tile = document.createElement('div');
					tile.className = 'ts-module-stat-tile ts-module-stat-tile-' + ((index % 4) + 1);
					var icon = document.createElement('span'); icon.className = 'fas fa-cube';
					var copy = document.createElement('span'); copy.textContent = label;
					var count = document.createElement('b'); count.textContent = String(values[index] || 0);
					tile.append(icon, copy, count); tiles.appendChild(tile);
				});
				summary.append(totalRow, tiles);
				chartCard.insertAdjacentElement('afterend', summary);
			}
		});
		return true;
	}

	/* Standard title tables reserve one icon cell. Legacy icon rules collapse that
	   cell into the text, which makes glyphs touch titles on create/empty pages. */
	function normalizePageTitleIcons() {
		var changed = false;
		document.querySelectorAll('.fiche > table.table-fiche-title:not(.ts-empty-title)').forEach(function (table) {
			var iconCell = table.querySelector('td.col-picto');
			var title = table.querySelector('div.titre');
			if (!iconCell || !title || !(title.textContent || '').trim()) { return; }
			table.classList.add('ts-command-title-with-icon');
			changed = true;
		});
		return changed;
	}

	/* Normalize the three remaining Third Party information/detail tabs without
	   changing their forms, links or record data. */
	function polishThirdPartyAuxiliaryTabs() {
		if (!document.body.classList.contains('ts-thirdparty-record-context')) { return false; }
		var path = window.location.pathname;
		var shell = document.querySelector('.ts-thirdparty-record-shell');
		if (!shell) { return false; }
		var summary = Array.from(shell.children).find(function (node) {
			return node.matches && node.matches('.fichecenter') && node.querySelector('table.tableforfield');
		});
		if (summary) { summary.classList.add('ts-record-context-summary'); }
		if (/\/societe\/note\.php$/.test(path)) {
			document.body.classList.add('ts-thirdparty-notes-tab');
			var notes = Array.from(shell.children).find(function (node) { return node.matches && node.matches('.tagtable.tableforfield'); });
			if (notes) { notes.classList.add('ts-notes-card'); }
		}
		if (/\/thirdpartyMargins\.php$/.test(path)) {
			document.body.classList.add('ts-thirdparty-margins-tab');
			var marginForm = Array.from(document.querySelectorAll('.fiche > form')).find(function (form) { return form.querySelector('table.liste'); });
			if (marginForm) { marginForm.classList.add('ts-margin-card'); }
		}
		if (/\/societe\/document\.php$/.test(path)) {
			document.body.classList.add('ts-thirdparty-documents-tab');
			document.querySelectorAll('.fiche > .ts-pagehead, .fiche > table.table-list-of-links').forEach(function (heading) { heading.classList.add('ts-documents-heading'); });
			document.querySelectorAll('.fiche > .div-table-responsive-no-min, .fiche > form#formaddlink').forEach(function (surface) { surface.classList.add('ts-documents-card'); });
		}
		return Boolean(summary || document.body.matches('.ts-thirdparty-notes-tab,.ts-thirdparty-margins-tab,.ts-thirdparty-documents-tab'));
	}

	function polishThirdPartyCustomerTab() {
		if (!/\/comm\/card\.php$/.test(window.location.pathname) || !document.body.classList.contains('ts-thirdparty-record-context')) { return false; }
		var shell = document.querySelector('.ts-thirdparty-record-shell');
		var content = shell && Array.from(shell.children).find(function (node) {
			return node.matches && node.matches('.fichecenter') && node.querySelector('.fichehalfleft, .fichehalfright');
		});
		if (!content) { return false; }
		document.body.classList.add('ts-thirdparty-customer-tab');
		content.classList.remove('ts-record-context-summary');
		content.classList.add('ts-customer-overview-grid');
		content.querySelectorAll(':scope > .fichehalfleft, :scope > .fichehalfright').forEach(function (column) {
			column.classList.add('ts-customer-overview-card');
		});
		var actions = shell.nextElementSibling;
		if (actions && actions.matches('.tabsAction')) { actions.classList.add('ts-customer-actions'); }
		return true;
	}

	function polishShipmentStatistics() {
		if (!/\/expedition\/stats\/(index\.php)?$/.test(window.location.pathname)) { return false; }
		var card = document.querySelector('.fiche > .tabBar');
		if (!card) { return false; }
		document.body.classList.add('ts-shipment-statistics');
		card.querySelectorAll('.fichethirdleft, .fichetwothirdright').forEach(function (column) { column.classList.add('ts-statistics-column'); });
		return true;
	}

	function polishSharedEmptyStates() {
		var changed = false;
		document.querySelectorAll('table.liste td[colspan], table.noborder td[colspan], table.border td[colspan]').forEach(function (cell) {
			var text = (cell.textContent || '').replace(/\s+/g, ' ').trim();
			if (!/^(None|No record found|No records found|No invoice)$/i.test(text)) { return; }
			cell.classList.add('ts-command-empty-state');
			changed = true;
		});
		return changed;
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
			if (definition.icon) {
				var groupIcon = document.createElement('span');
				groupIcon.className = 'ts-field-group-icon fas ' + definition.icon;
				groupIcon.setAttribute('aria-hidden', 'true');
				heading.appendChild(groupIcon);
			}
			heading.appendChild(document.createTextNode(definition.title || groupKey));
			var table = document.createElement('table');
			table.className = 'border tableforfield centpercent';
			var body = document.createElement('tbody');
			groupRows.forEach(function (row) { body.appendChild(row); });
			groupRows.forEach(function (row) {
				var value = row.cells[row.cells.length - 1];
				if (value && !(value.textContent || '').replace(/\s+/g, '').length) { value.classList.add('ts-empty-value'); }
			});
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
		var activeNav = document.querySelector('.cmd-nav-group.is-active .cmd-nav-label');
		if (document.body.classList.contains('ts-thirdparty-record-context')) {
			var thirdPartyNav = Array.from(document.querySelectorAll('a.cmd-nav-label[href], a.cmd-nav-item[href]')).find(function (link) {
				return /^Third parties$/i.test((link.textContent || '').replace(/\s+/g, ' ').trim());
			});
			parent.textContent = thirdPartyNav ? (thirdPartyNav.textContent || '').trim() : 'Third parties';
			if (thirdPartyNav && thirdPartyNav.getAttribute('href')) { parent.href = thirdPartyNav.getAttribute('href'); }
		} else {
			parent.textContent = activeNav ? (activeNav.textContent || '').trim() : (back.textContent || '').trim();
		}
		crumb.appendChild(parent);

		if (title) {
			var sep = document.createElement('span');
			sep.className = 'ts-breadcrumb-sep';
			sep.setAttribute('aria-hidden', 'true');
			sep.textContent = '›';
			crumb.appendChild(sep);
			var here = document.createElement('span');
			here.className = 'ts-breadcrumb-current';
			/* The banner holds more than the name: a location line, status badges and
			   -- on some cards -- Dolibarr's hidden dialog host, whose text content is
			   still readable even though it never paints. Read a detached copy with
			   that furniture removed, then keep the first line only. */
			var probe = title.cloneNode(true);
			probe.querySelectorAll(
				'.hidden, .hideobject, [id^="idfordialog"], script, style, .badge, .statusref, .refidno, .ts-breadcrumb'
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

	function polishRecordSections() {
		if (!document.querySelector('div.tabBar.ts-entity-card')) { return false; }
		var changed = false;
		document.querySelectorAll('div.fichecenter > div.fichehalfleft, div.fichecenter > div.fichehalfright').forEach(function (half) {
			var titleCell = half.querySelector('table.table-fiche-title td.col-title, .titre');
			if (!titleCell) { return; }
			var label = (titleCell.textContent || '').replace(/\s+/g, ' ').trim();
			var kind = /^Linked files$/i.test(label) ? 'files' : (/events/i.test(label) ? 'events' : '');
			if (!kind) { return; }
			half.classList.add('ts-record-section-card', 'ts-record-section-' + kind);
			if (kind === 'events') { titleCell.textContent = 'Recent events'; }
			var icon = titleCell.querySelector('.fas, .far');
			if (icon) {
				icon.classList.add('ts-section-icon');
			} else {
				icon = document.createElement('span');
				icon.className = 'ts-section-icon fas ' + (kind === 'files' ? 'fa-paperclip' : 'fa-history');
				icon.setAttribute('aria-hidden', 'true');
				titleCell.insertBefore(icon, titleCell.firstChild);
			}
			if (kind === 'files') {
				var none = Array.from(half.querySelectorAll('.opacitymedium')).find(function (node) {
					return /^None$/i.test((node.textContent || '').trim());
				});
				if (none && !half.querySelector('.ts-emptybox')) {
					var empty = document.createElement('div');
					empty.className = 'ts-emptybox';
					empty.innerHTML = '<span class="far fa-file-alt" aria-hidden="true"></span><strong>No files linked</strong><span>Files attached to this third party will appear here.</span>';
					var responsive = none.closest('.div-table-responsive') || none.closest('table');
					if (responsive) { responsive.classList.add('ts-native-empty-source'); responsive.insertAdjacentElement('afterend', empty); }
				}
			}
			if (kind === 'events') {
				var fullList = Array.from(half.querySelectorAll('a.btnTitle')).find(function (link) {
					return /Full list/i.test(link.getAttribute('title') || '');
				});
				if (fullList && !fullList.querySelector('.ts-section-action-label')) {
					var actionLabel = document.createElement('span');
					actionLabel.className = 'ts-section-action-label';
					actionLabel.textContent = 'View all events';
					fullList.appendChild(actionLabel);
					fullList.classList.add('ts-section-labelled-action');
				}
			}
			changed = true;
		});
		return changed;
	}

	/* The overview is the visual reference for the Third Party record. Keep this
	   final pass route-specific: other Third Party tabs retain the permanent shell
	   normalization above, while only /societe/card.php receives the compact
	   identity, prioritized tabs and overview-card refinements. Existing links and
	   controls are always moved or annotated, never recreated. */
	function polishThirdPartyOverview() {
		if (!document.body.classList.contains('ts-thirdparty-record-context')) { return false; }
		var banner = document.querySelector('.ts-thirdparty-record-shell > .ts-entity-banner');
		var groups = document.querySelector('.ts-thirdparty-groups');
		if (!banner || document.body.classList.contains('ts-thirdparty-overview')) { return false; }
		document.body.classList.add('ts-thirdparty-overview');

		var identity = banner.querySelector('.ts-entity-identity');
		var title = identity && identity.querySelector(':scope > .refid');
		var photo = identity && identity.querySelector(':scope > .divphotoref');
		if (photo) {
			photo.classList.add('ts-overview-photo-source');
			if (photo.querySelector('img')) { identity.classList.add('ts-overview-has-logo'); }
		}
		if (identity && !identity.querySelector('.ts-overview-icon-tile')) {
			var tile = document.createElement('span');
			tile.className = 'ts-overview-icon-tile fas fa-building';
			tile.setAttribute('aria-hidden', 'true');
			identity.insertBefore(tile, identity.firstChild);
		}
		if (title) {
			if (!title.querySelector(':scope > .ts-overview-name')) {
				var name = document.createElement('span');
				name.className = 'ts-overview-name';
				Array.from(title.childNodes).filter(function (node) {
					return node.nodeType === Node.TEXT_NODE && (node.textContent || '').trim();
				}).forEach(function (node) { name.appendChild(node); });
				title.insertBefore(name, title.firstChild);
			}
			var status = title.querySelector(':scope > .statusref');
			if (status && !title.querySelector('.ts-overview-info')) {
				var info = document.createElement('span');
				info.className = 'ts-overview-info fas fa-info-circle';
				info.setAttribute('aria-label', 'Third party information');
				status.insertAdjacentElement('afterend', info);
			}
			var addressBlock = title.querySelector('.refaddress');
			var address = addressBlock && addressBlock.querySelector('.address');
			if (address) {
				address.classList.add('ts-overview-location');
				title.appendChild(address);
			}
			var email = addressBlock && addressBlock.querySelector('a[href^="mailto:"]');
			if (email) {
				email.classList.add('ts-overview-email');
				title.appendChild(email);
			}
			title.querySelectorAll(':scope > .refidno:not(.ts-overview-location)').forEach(function (node) {
				node.classList.add('ts-overview-secondary-hidden');
			});
		}

		/* Make the existing disclosure behave like a conventional menu and give the
		   original controls a stable icon slot without changing their handlers. */
		var actions = banner.querySelector('.ts-more-actions');
		var editAction = banner.querySelector('.ts-record-primary');
		var sendAction = banner.querySelector('.ts-record-secondary');
		[[sendAction, 'far fa-envelope'], [editAction, 'fas fa-pencil-alt']].forEach(function (entry) {
			var control = entry[0];
			if (!control || control.querySelector('.ts-overview-button-icon')) { return; }
			var icon = document.createElement('span');
			icon.className = 'ts-overview-button-icon ' + entry[1];
			icon.setAttribute('aria-hidden', 'true');
			control.insertBefore(icon, control.firstChild);
		});
		if (actions) {
			var actionIcons = {
				'action-clone': 'fa-copy',
				'action-delete': 'fa-trash-alt'
			};
			actions.querySelectorAll('.ts-more-action-item').forEach(function (control) {
				if (control.querySelector('.ts-overview-action-icon')) { return; }
				var icon = document.createElement('span');
				icon.className = 'ts-overview-action-icon far ' + (actionIcons[control.id] || (/merge/.test(control.getAttribute('href') || '') ? 'fa-object-group' : 'fa-circle'));
				icon.setAttribute('aria-hidden', 'true');
				control.insertBefore(icon, control.firstChild);
			});
			document.addEventListener('click', function (event) {
				if (actions.open && !actions.contains(event.target)) { actions.removeAttribute('open'); }
			});
			document.addEventListener('keydown', function (event) {
				if (event.key === 'Escape' && actions.open) {
					actions.removeAttribute('open');
					var trigger = actions.querySelector('summary');
					if (trigger) { trigger.focus(); }
				}
			});
		}

		var tabs = document.querySelector('.ts-thirdparty-record-shell > .tabs[data-ts-placed="1"]');
		var overviewTab = tabs && tabs.querySelector('a#card');
		if (overviewTab) {
			Array.from(overviewTab.childNodes).forEach(function (node) {
				if (node.nodeType === Node.TEXT_NODE && (node.textContent || '').trim()) { node.textContent = 'Overview'; }
			});
			overviewTab.setAttribute('title', 'Overview');
			var tabIcon = overviewTab.querySelector('[title="Third party"]');
			if (tabIcon) { tabIcon.setAttribute('title', 'Overview'); }
		}

		/* Secondary tabs share the permanent identity/header/tab shell but do not
		   publish the overview's semantic field groups. Stop after shell refinement;
		   their content adapter remains authoritative below the tabs. */
		if (!groups) { return true; }

		/* Surface the translated nature already carried by Dolibarr's title instead
		   of the terse C/P/V glyph, without changing the destination anchor. */
		groups.querySelectorAll('tr[data-field="nature"] a[title]').forEach(function (link) {
			var label = (link.getAttribute('title') || '').trim();
			if (label && /^[CPV]$/.test((link.textContent || '').trim())) { link.textContent = label; }
		});
		groups.querySelectorAll('.clipboardCPButton').forEach(function (button) { button.classList.add('ts-overview-copy'); });

		var lower = document.querySelector('.fiche > .fichecenter:has(.ts-record-section-card)');
		if (lower) { lower.classList.add('ts-overview-lower-grid'); }
		var events = document.querySelector('.ts-record-section-events');
		if (events) {
			events.querySelectorAll('tr.oddeven td:nth-child(4)').forEach(function (cell) { cell.classList.add('ts-overview-event-type'); });
			var conversation = events.querySelector('a.btnTitle[title="Full conversation"]');
			if (conversation) { conversation.classList.add('ts-overview-redundant-event-action'); }
			var createEvent = events.querySelector('a.btnTitle[title="Create event"]');
			if (createEvent) { createEvent.classList.add('ts-overview-redundant-event-action'); }
		}
		return true;
	}

	/* Events/Agenda has stable native data but legacy table geometry. This exact
	   route pass moves those native nodes into summary, toolbar and timeline
	   presentation hooks. It does not invent filter fields or event links. */
	function polishThirdPartyEvents() {
		var params = new URLSearchParams(window.location.search);
		/* The filter form POSTs to this same path, which moves socid out of the
		   query string and into the request body. Reading the record only from
		   location.search therefore failed on every filtered view and the page
		   dropped back to Dolibarr's native rendering. Fall back to the id the
		   filter form carries, so the filtered states compose like the default. */
		var recordId = params.get('socid') || params.get('id')
			|| (document.querySelector('form.listactionsfilter input[name="socid"], form.listactionsfilter input[name="id"]') || {}).value
			|| '';
		if (!/\/societe\/messaging\.php$/.test(window.location.pathname) || !recordId) { return false; }
		if (!document.body.classList.contains('ts-thirdparty-record-context') || document.body.classList.contains('ts-thirdparty-events')) { return false; }
		var shell = document.querySelector('.ts-thirdparty-record-shell');
		var timeline = document.querySelector('.fiche ul.timeline');
		if (!shell || !timeline) { return false; }
		document.body.classList.add('ts-thirdparty-events');

		var activeTab = shell.querySelector('.tabsElemActive a#agenda');
		if (activeTab) {
			activeTab.setAttribute('aria-current', 'page');
			var tabStrip = activeTab.closest('.tabs');
			if (tabStrip) { tabStrip.scrollLeft = Math.max(0, activeTab.offsetLeft - tabStrip.clientWidth + activeTab.offsetWidth + 12); }
		}

		var fiche = timeline.closest('.fiche');
		var center = timeline.closest('.fichecenter') || timeline.parentElement;
		var metadataTable = fiche && Array.from(fiche.querySelectorAll('table.tableforfield')).find(function (table) {
			return /Created by/i.test(table.textContent || '') && /Creation date/i.test(table.textContent || '');
		});
		if (metadataTable) {
			var summary = document.createElement('section');
			summary.className = 'ts-events-summary';
			metadataTable.querySelectorAll(':scope > tbody > tr').forEach(function (row, index) {
				var label = row.cells[0];
				var value = row.cells[1];
				if (!label || !value) { return; }
				var item = document.createElement('div');
				item.className = 'ts-events-summary-item';
				var icon = document.createElement('span');
				icon.className = 'ts-events-summary-icon fas ' + (index % 2 ? 'fa-calendar-alt' : 'fa-user');
				icon.setAttribute('aria-hidden', 'true');
				var copy = document.createElement('div');
				copy.className = 'ts-events-summary-copy';
				label.classList.add('ts-events-summary-label');
				value.classList.add('ts-events-summary-value');
				copy.appendChild(label);
				copy.appendChild(value);
				item.appendChild(icon);
				item.appendChild(copy);
				summary.appendChild(item);
			});
			metadataTable.insertAdjacentElement('beforebegin', summary);
			metadataTable.remove();
		}

		var pageHead = fiche && Array.from(fiche.querySelectorAll('.ts-pagehead')).find(function (head) {
			return /Events for this third party/i.test(head.textContent || '');
		});
		if (pageHead) { pageHead.classList.add('ts-events-pagehead'); }
		var create = pageHead && pageHead.querySelector('a[href*="comm/action/card.php"][href*="action=create"]');
		if (create) { create.classList.add('ts-events-create'); }

		var filterHost = fiche && fiche.querySelector('.filters-container:has(form.listactionsfilter)');
		var form = filterHost && filterHost.querySelector('form.listactionsfilter');
		var titleTable = pageHead && pageHead.nextElementSibling && pageHead.nextElementSibling.matches('table.table-fiche-title') ? pageHead.nextElementSibling : null;
		var viewSwitch = titleTable && titleTable.querySelector('.paginationafterarrows');
		if (filterHost && form) {
			filterHost.classList.add('ts-events-toolbar');
			form.classList.add('ts-events-filter-form');
			if (viewSwitch) {
				var switcher = document.createElement('div');
				switcher.className = 'ts-events-view-switch';
				viewSwitch.querySelectorAll(':scope > a').forEach(function (link) {
					link.classList.add('ts-events-view-option');
					link.setAttribute('aria-label', link.getAttribute('title') || 'Change event view');
					switcher.appendChild(link);
				});
				filterHost.insertBefore(switcher, form);
			}
			if (titleTable) { titleTable.classList.add('ts-events-native-title-source'); }
			var labelInput = form.querySelector('input[name="search_agenda_label"]');
			if (labelInput) {
				labelInput.classList.add('ts-events-search-input');
				labelInput.setAttribute('placeholder', 'Search events…');
				var searchWrap = document.createElement('div');
				searchWrap.className = 'ts-events-search-control';
				var searchIcon = document.createElement('span');
				searchIcon.className = 'fas fa-search';
				searchIcon.setAttribute('aria-hidden', 'true');
				labelInput.parentNode.insertBefore(searchWrap, labelInput);
				searchWrap.appendChild(searchIcon);
				searchWrap.appendChild(labelInput);
			}
			/* This control is a sort toggle -- it flips sortorder on a.datep -- not a
			   date filter, which is how a bare calendar icon beside Search and Type
			   reads. State that it sorts, and show which way it will go. */
			var dateLink = form.querySelector('a[href*="sortfield=a.datep"]');
			if (dateLink) {
				dateLink.classList.add('ts-events-date-control');
				var nextOrder = /sortorder=asc/i.test(dateLink.getAttribute('href') || '') ? 'asc' : 'desc';
				dateLink.setAttribute('title', nextOrder === 'asc' ? 'Sort by date, oldest first' : 'Sort by date, newest first');
				dateLink.setAttribute('aria-label', dateLink.getAttribute('title'));
				var dateIcon = document.createElement('span');
				dateIcon.className = 'far fa-calendar-alt';
				dateIcon.setAttribute('aria-hidden', 'true');
				dateLink.insertBefore(dateIcon, dateLink.firstChild);
				var dateCaret = document.createElement('span');
				dateCaret.className = 'fas ' + (nextOrder === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down') + ' ts-events-date-order';
				dateCaret.setAttribute('aria-hidden', 'true');
				dateLink.appendChild(dateCaret);
			}
			var type = form.querySelector('select#actioncode');
			if (type) { type.setAttribute('data-ts-events-select', 'compact'); }
			/* This button submits the filter form; it expands nothing, so labelling it
			   "Filters" promised a panel that does not exist. Say what it does. */
			var submit = form.querySelector('button.button_search');
			if (submit && !submit.querySelector('.ts-events-filter-label')) {
				var submitLabel = document.createElement('span');
				submitLabel.className = 'ts-events-filter-label';
				submitLabel.textContent = 'Apply';
				submit.appendChild(submitLabel);
				submit.setAttribute('title', 'Apply the search and type filters');
			}

			/* The reset button posts button_removefilter_x, which only clears the
			   filters. With nothing filtered it is a permanent, mysterious X, so
			   show it only when there is something to clear. */
			var reset = form.querySelector('button.button_removefilter');
			if (reset) {
				var labelField = form.querySelector('input[name="search_agenda_label"]');
				var typeField = form.querySelector('select#actioncode');
				var searchParams = new URLSearchParams(window.location.search);
				var hasActiveFilter = Boolean(
					(labelField && (labelField.value || '').trim())
					|| (typeField && typeField.value && typeField.value !== '-1' && typeField.value !== '')
					|| searchParams.get('search_agenda_label')
					|| (searchParams.get('actioncode') && searchParams.get('actioncode') !== '-1')
				);
				reset.hidden = !hasActiveFilter;
				reset.classList.add('ts-events-reset');
				reset.setAttribute('title', 'Clear filters');
				reset.setAttribute('aria-label', 'Clear filters');
				if (!reset.querySelector('.ts-events-reset-label')) {
					var resetLabel = document.createElement('span');
					resetLabel.className = 'ts-events-reset-label';
					resetLabel.textContent = 'Clear';
					reset.appendChild(resetLabel);
				}
				/* Reveal it as soon as a filter is set, without waiting for a submit. */
				var refreshReset = function () {
					reset.hidden = !(
						(labelField && (labelField.value || '').trim())
						|| (typeField && typeField.value && typeField.value !== '-1' && typeField.value !== '')
					);
				};
				if (labelField) { labelField.addEventListener('input', refreshReset); }
				if (typeField) { typeField.addEventListener('change', refreshReset); }
			}
			if (window.jQuery && type) {
				window.jQuery(type).on('select2:open', function () {
					window.requestAnimationFrame(function () {
						var dropdown = document.querySelector('.select2-container--open .select2-dropdown');
						var trigger = type.parentElement.querySelector('.select2-container');
						if (!dropdown || !trigger) { return; }
						dropdown.classList.add('ts-events-select-dropdown');
						dropdown.style.setProperty('width', trigger.getBoundingClientRect().width + 'px', 'important');
						dropdown.querySelectorAll('.select2-results__option').forEach(function (option) {
							if (!(option.textContent || '').replace(/\u00a0/g, ' ').trim()) { option.style.display = 'none'; }
						});
					});
				});
			}
		}
		/* Dolibarr nests the result timeline in filters-container after the form.
		   Once that container becomes a flex toolbar, the timeline must return to
		   normal document flow or it becomes an accidental toolbar column. */
		if (filterHost && filterHost.contains(timeline)) { filterHost.insertAdjacentElement('afterend', timeline); }

		timeline.classList.add('ts-events-timeline');
		timeline.querySelectorAll(':scope > li:not(.time-label)').forEach(function (event) {
			event.classList.add('ts-events-entry');
			var outerIcon = event.querySelector(':scope > [class*="fa-"]');
			if (outerIcon) {
				outerIcon.classList.remove('fa-cog');
				outerIcon.classList.add('fa-calendar-alt', 'ts-events-entry-icon');
			}
			var item = event.querySelector('.timeline-item');
			var header = item && item.querySelector('.timeline-header');
			var author = header && header.querySelector('.messaging-author');
			var eventTitle = header && header.querySelector('.messaging-title');
			if (header && eventTitle) {
				eventTitle.classList.add('ts-events-entry-title');
				header.insertBefore(eventTitle, header.firstChild);
			}
			if (author) { author.classList.add('ts-events-entry-author'); }
			var reference = item && item.querySelector('.timeline-header-action2');
			if (reference) { reference.classList.add('ts-events-entry-reference'); }
			var time = item && Array.from(item.querySelectorAll(':scope > .time')).find(function (node) { return node !== reference && node.querySelector('.fa-clock-o'); });
			if (time) { time.classList.add('ts-events-entry-time'); }
			var badge = item && item.querySelector(':scope > .time .badge-status');
			if (badge && badge.parentElement) { badge.parentElement.classList.add('ts-events-entry-status'); }
		});
		return true;
	}

	/* Active-tab content varies by module, but list/table wrappers are stable.
	   Add presentation hooks only after the permanent Third Party shell exists;
	   native forms, controls, tables and pagination remain exactly where their
	   module rendered them. */
	function polishThirdPartyTabContent() {
		if (!document.body.classList.contains('ts-thirdparty-record-context')) { return false; }
		var shell = document.querySelector('.ts-thirdparty-record-shell');
		var changed = false;
		document.querySelectorAll('.fiche .div-table-responsive, .fiche .div-table-responsive-no-min').forEach(function (surface) {
			if (shell && shell.contains(surface)) { return; }
			surface.classList.add('ts-record-tab-surface');
			changed = true;
		});
		document.querySelectorAll('.fiche form').forEach(function (form) {
			if (shell && shell.contains(form)) { return; }
			if (form.querySelector('table.liste, .div-table-responsive, .div-table-responsive-no-min')) {
				form.classList.add('ts-record-tab-list');
			}
		});
		document.querySelectorAll('.fiche table.liste td, .fiche .ts-record-tab-surface td').forEach(function (cell) {
			if (cell.children.length > 1) { return; }
			var text = (cell.textContent || '').replace(/\u00a0/g, ' ').replace(/\s+/g, ' ').trim();
			if (/^(None|No .+)$/i.test(text) && (cell.colSpan > 1 || cell.closest('tr').children.length === 1)) {
				cell.classList.add('ts-record-tab-empty');
			}
		});
		return changed;
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

	/* Some landing pages print an empty title table (often a non-breaking-space
	   placeholder), and list-page composition leaves that native table behind
	   after moving its real title. Mark only inert, textless tables so buttons,
	   icons and module hooks can never be hidden accidentally. */
	function markEmptyTitleTables() {
		var done = false;
		document.querySelectorAll('table.table-fiche-title').forEach(function (table) {
			var title = table.querySelector('div.titre');
			var text = ((title && title.innerText) || '').replace(/\u00a0/g, ' ').trim();
			var visibleAction = Array.from(table.querySelectorAll('a, button, input, select, textarea')).some(function (control) {
				var style = window.getComputedStyle(control);
				return style.display !== 'none' && style.visibility !== 'hidden' && control.getClientRects().length > 0;
			});
			if (!text && !visibleAction) {
				table.classList.add('ts-empty-title');
				done = true;
			}
		});
		return done;
	}

	/* Standard Dolibarr create/edit pages share one top-level form containing a
	   tableforfield/border field table and a final submit row. Mark that structure
	   for the global COMMAND form language without re-parenting fields. Specialized
	   adapters remain authoritative where their body class is already present. */
	function enhanceSharedFormPage() {
		var params = new URLSearchParams(window.location.search);
		var action = (params.get('action') || '').toLowerCase();
		if (!/^(create|edit)$/.test(action)) { return false; }
		/* The Third Party and Partnership forms have richer declarative adapters.
		   Route guards keep those reference implementations authoritative even when
		   optional fields/hooks mean their body marker is not produced on one load. */
		if (/^\/societe\/card\.php$/.test(window.location.pathname) || /^\/partnership\/partnership_card\.php$/.test(window.location.pathname)) { return false; }
		if (document.body.classList.contains('ts-thirdparty-form-page') || document.body.classList.contains('ts-partnership-form-page')) { return false; }
		var candidates = Array.from(document.querySelectorAll('.fiche > form, .fiche > div > form, .fiche .tabBar > form')).filter(function (form) {
			return form.querySelector('table.tableforfield, table.border') && form.querySelector('input[type="submit"], button[type="submit"]');
		});
		var form = candidates.sort(function (a, b) {
			return b.querySelectorAll('input, select, textarea').length - a.querySelectorAll('input, select, textarea').length;
		})[0];
		if (!form || form.getAttribute('data-ts-shared-form') === '1') { return false; }
		document.body.classList.add('ts-command-form-page');
		form.classList.add('ts-command-form');
		form.setAttribute('data-ts-shared-form', '1');
		var fieldTable = Array.from(form.querySelectorAll('table.tableforfield, table.border')).find(function (table) {
			return table.querySelectorAll('input, select, textarea').length >= 2;
		});
		if (fieldTable) { fieldTable.classList.add('ts-command-form-fields'); }
		form.querySelectorAll('input[type="text"], input[type="email"], input[type="url"], input[type="number"], input[type="password"], textarea').forEach(function (control) {
			control.classList.add('ts-command-control');
		});
		function sourceSelectFor(container) {
			var select = container.previousElementSibling && container.previousElementSibling.matches('select') ? container.previousElementSibling : null;
			if (select) { return select; }
			var selection = container.querySelector('.select2-selection[aria-labelledby]');
			var labelId = selection && selection.getAttribute('aria-labelledby');
			var rendered = labelId && document.getElementById(labelId);
			var renderedId = rendered && rendered.id ? rendered.id.replace(/^select2-/, '').replace(/-container$/, '') : '';
			return renderedId ? document.getElementById(renderedId) : null;
		}
		form.querySelectorAll('.select2-container').forEach(function (control) {
			control.classList.add('ts-command-select');
			var select = sourceSelectFor(control);
			if (!select) { return; }
			var options = Array.from(select.options || []).filter(function (option) { return (option.textContent || '').replace(/\s+/g, '').length; });
			control.classList.toggle('ts-command-select-compact', !select.multiple && options.length > 0 && options.length <= 12);
		});
		function polishOpenFormDropdown() {
			window.requestAnimationFrame(function () {
				var open = form.querySelector('.select2-selection[aria-expanded="true"]');
				var container = open && open.closest('.select2-container');
				var dropdown = document.querySelector('.select2-container--open .select2-dropdown');
				if (!container || !dropdown) { return; }
				var rect = container.getBoundingClientRect();
				var width = Math.min(rect.width, window.innerWidth - 24);
				dropdown.classList.add('ts-command-form-dropdown');
				dropdown.classList.toggle('ts-command-form-dropdown-compact', container.classList.contains('ts-command-select-compact'));
				dropdown.style.setProperty('width', width + 'px', 'important');
				dropdown.style.setProperty('min-width', width + 'px', 'important');
				dropdown.querySelectorAll('.select2-results__option').forEach(function (option) {
					if (!(option.textContent || '').replace(/\s+/g, '').length) { option.classList.add('ts-command-empty-option'); }
				});
			});
		}
		/* Select2 emits a jQuery event and mounts its popup under body. Observe that
		   portal as well as the native event so every bundled Select2 version gets
		   the same trigger-sized COMMAND treatment. */
		form.addEventListener('select2:open', polishOpenFormDropdown);
		if (window.jQuery) { window.jQuery(form).on('select2:open.tsCommandForm', polishOpenFormDropdown); }
		var dropdownObserver = new MutationObserver(function (mutations) {
			if (mutations.some(function (mutation) { return mutation.addedNodes.length; })) { polishOpenFormDropdown(); }
		});
		dropdownObserver.observe(document.body, { childList: true, subtree: true });
		var actionHost = Array.from(form.querySelectorAll('.center, .centered, .tabsAction')).reverse().find(function (node) {
			return node.querySelector('input[type="submit"], button[type="submit"]');
		});
		if (actionHost) {
			actionHost.classList.add('ts-command-form-actions');
			var submits = actionHost.querySelectorAll('input[type="submit"], button[type="submit"]');
			if (submits[0]) { submits[0].classList.add('ts-command-submit-primary'); }
			if (submits[1]) { submits[1].classList.add('ts-command-submit-secondary'); }
		}
		return true;
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

	function structureAjaxTooltip(root) {
		(root || document).querySelectorAll('.ui-tooltip.mytooltip .centpercent:not([data-ts-structured])').forEach(function (content) {
			document.querySelectorAll('a.classforajaxtooltip:hover[title]').forEach(function (target) {
				if ((target.getAttribute('title') || '').trim().toLowerCase() === 'tocomplete') { target.removeAttribute('title'); }
			});
			var lines = [[]];
			Array.from(content.childNodes).forEach(function (node) {
				if (node.nodeName === 'BR') { lines.push([]); return; }
				lines[lines.length - 1].push(node);
			});
			var headerLine = lines.shift() || [];
			var header = document.createElement('div');
			header.className = 'ts-tooltip-header';
			var headerElements = headerLine.filter(function (node) { return node.nodeType === Node.ELEMENT_NODE; });
			var icon = headerElements.find(function (node) { return node.matches('[class*="fa-"]'); });
			var heading = headerElements.find(function (node) { return node.matches('u'); });
			var status = headerElements.find(function (node) { return node.matches('.badge-status'); });
			var metadata = headerElements.find(function (node) { return node.matches('.customer-back, .vendor-back, .prospect-back'); });
			var headingText = heading && (heading.textContent || '').trim().toLowerCase();
			if (headingText !== 'third party' && headingText !== 'user') {
				content.setAttribute('data-ts-structured', 'native');
				return;
			}
			if (icon) { icon.classList.add('ts-tooltip-icon'); header.appendChild(icon); }
			if (heading) {
				heading.classList.add('ts-tooltip-kind');
				header.appendChild(heading);
			}
			if (metadata) { metadata.classList.add('ts-tooltip-meta'); header.appendChild(metadata); }
			if (status) { status.classList.add('ts-tooltip-status'); header.appendChild(status); }

			var details = document.createElement('div');
			details.className = 'ts-tooltip-details';
			lines.forEach(function (line) {
				var text = line.map(function (node) { return node.textContent || ''; }).join('').replace(/\u00a0/g, ' ').trim();
				if (!text) { return; }
				var labelNode = line.find(function (node) { return node.nodeType === Node.ELEMENT_NODE && node.matches('b, strong'); });
				var iconNode = line.find(function (node) {
					return node.nodeType === Node.ELEMENT_NODE && (node.matches('[class*="fa-"]') || node.querySelector('[class*="fa-"]'));
				});
				var label = '';
				var value = text;
				var labelIcon = null;
				if (labelNode) {
					label = (labelNode.textContent || '').replace(/:\s*$/, '').trim();
					value = text.slice((labelNode.textContent || '').trim().length).replace(/^:\s*/, '').trim();
				} else if (iconNode) {
					var glyph = iconNode.matches('[class*="fa-"]') ? iconNode : iconNode.querySelector('[class*="fa-"]');
					labelIcon = glyph.cloneNode(true);
					labelIcon.removeAttribute('title');
					labelIcon.removeAttribute('style');
					label = glyph.className.indexOf('fa-at') !== -1 ? 'Email' : (glyph.className.indexOf('fa-phone') !== -1 ? 'Phone' : 'Detail');
				}
				var row = document.createElement('div');
				row.className = 'ts-tooltip-row';
				var labelCell = document.createElement('span');
				labelCell.className = 'ts-tooltip-label';
				if (labelIcon) { labelCell.appendChild(labelIcon); }
				labelCell.appendChild(document.createTextNode(label));
				var valueCell = document.createElement('span');
				valueCell.className = 'ts-tooltip-value';
				valueCell.textContent = value || '\u2014';
				row.appendChild(labelCell);
				row.appendChild(valueCell);
				details.appendChild(row);
			});
			content.replaceChildren(header, details);
			content.setAttribute('data-ts-structured', '1');
		});
	}

	/* Dolibarr writes tooltip labels inconsistently: some carry their own colon
	   ("Product ref.:"), others leave it loose in the text that follows
	   ("Use lot/serial number" + " : Yes"). Rendered against a fixed label width
	   the loose ones pushed their colon far from the word it belongs to. Move
	   the colon onto the label and leave exactly one space before the value. */
	function normalizeTooltipLabels(root) {
		Array.prototype.slice.call(root.querySelectorAll('.ui-tooltip.mytooltip b, .ui-tooltip.mytooltip strong')).forEach(function (label) {
			if (label.getAttribute('data-ts-colon') === '1') { return; }
			var text = (label.textContent || '').replace(/\s+/g, ' ').replace(/\s*:\s*$/, '').trim();
			if (!text) { return; }
			label.textContent = text + ':';
			label.setAttribute('data-ts-colon', '1');
			/* Drop the colon and padding Dolibarr left at the start of the value. */
			var next = label.nextSibling;
			while (next && next.nodeType === 3 && !(next.nodeValue || '').trim()) { next = next.nextSibling; }
			if (next && next.nodeType === 3) {
				next.nodeValue = ' ' + (next.nodeValue || '').replace(/^\s*:?\s*/, '');
			} else if (next) {
				var gap = label.nextSibling;
				if (!(gap && gap.nodeType === 3 && gap.nodeValue === ' ')) {
					label.parentNode.insertBefore(document.createTextNode(' '), next);
				}
			}
		});
	}

	function watchAjaxTooltips() {
		structureAjaxTooltip(document);
		try { normalizeTooltipLabels(document); } catch (e) { /* leave Dolibarr's own wording */ }
		var observer = new MutationObserver(function (mutations) {
			if (mutations.some(function (mutation) { return mutation.addedNodes.length; })) {
				structureAjaxTooltip(document);
				try { normalizeTooltipLabels(document); } catch (e) { /* leave Dolibarr's own wording */ }
			}
		});
		observer.observe(document.body, {childList: true, subtree: true});
	}

	/* Dolibarr's Kanban mode emits a real card container, but only a subset of the
	   list fields. The matching normal-mode result set already contains the exact
	   authorised records and selected columns for the current filters/page. Read
	   that markup once and import only the requested metadata; the visible List
	   view and its styling remain untouched. */
	function enhanceThirdPartyKanban() {
		var grid = document.querySelector('.ts-list-composition div.box-flex-container.kanban');
		if (!grid || grid.getAttribute('data-ts-kanban') === 'thirdparty') { return false; }
		var cards = Array.from(grid.querySelectorAll(':scope > .box-flex-item'));
		if (!cards.length || !cards.every(function (item) {
			return Boolean(item.querySelector('a[data-params*="societe"][href*="/societe/card.php"]'));
		})) { return false; }

		var table = grid.closest('table.liste');
		var listCard = table && table.closest('.ts-list-card');
		grid.setAttribute('data-ts-kanban', 'thirdparty');
		grid.classList.add('ts-command-kanban', 'ts-thirdparty-kanban');
		if (table) { table.classList.add('ts-kanban-table'); }
		if (listCard) { listCard.classList.add('ts-kanban-card-surface'); }
		/* The shared list composition already moves Dolibarr's native reset button.
		   Its initial active-state check covers quick and advanced fields; Kanban's
		   promoted category/representative selects also need to reveal that same
		   button when they alone carry a value. Operator helper selects are excluded. */
		var clearFilters = document.querySelector('.ts-filter-surface .ts-clear-all-filters');
		var hasActiveToolbarFilter = Array.from(document.querySelectorAll('.ts-filter-surface .ts-toolbar-filter select[name$="[]"]')).some(function (field) {
			var value = window.jQuery && window.jQuery(field).val ? window.jQuery(field).val() : field.value;
			return Array.isArray(value) ? value.length > 0 : value !== null && String(value).trim() !== '' && String(value) !== '-1';
		});
		if (clearFilters && hasActiveToolbarFilter) { clearFilters.hidden = false; }

		function element(tag, className, text) {
			var node = document.createElement(tag);
			if (className) { node.className = className; }
			if (text) { node.textContent = text; }
			return node;
		}
		function icon(className) {
			var node = element('span', className);
			node.setAttribute('aria-hidden', 'true');
			return node;
		}
		function addDetail(details, className, iconClass, value) {
			if (!value) { return; }
			var row = element('div', 'ts-kanban-detail ' + className);
			row.appendChild(icon('fas ' + iconClass));
			var valueNode = element('span', 'ts-kanban-detail-value', value);
			valueNode.setAttribute('title', value);
			row.appendChild(valueNode);
			details.appendChild(row);
		}
		function addRepresentatives(details, representatives) {
			if (!representatives.length) { return; }
			var row = element('div', 'ts-kanban-detail ts-kanban-representatives');
			var iconSlot = element('span', 'ts-kanban-detail-icon');
			var avatar = representatives[0].querySelector('img');
			if (avatar) {
				avatar.removeAttribute('style');
				iconSlot.appendChild(avatar);
			} else {
				iconSlot.appendChild(icon('fas fa-user'));
			}
			var values = element('span', 'ts-kanban-detail-value ts-kanban-representative-links');
			representatives.forEach(function (representative, index) {
				representative.querySelectorAll('.userimg, [class*="fa-"]').forEach(function (glyph) { glyph.remove(); });
				representative.className = 'ts-kanban-representative';
				representative.removeAttribute('title');
				if (index) { values.appendChild(document.createTextNode(', ')); }
				values.appendChild(representative);
			});
			row.appendChild(iconSlot);
			row.appendChild(values);
			details.appendChild(row);
		}

		var entries = new Map();
		cards.forEach(function (item) {
			var card = item.querySelector('.info-box');
			var content = card && card.querySelector('.info-box-content');
			var nameWrap = content && content.querySelector('.info-box-ref');
			var nameLink = nameWrap && nameWrap.querySelector('a[data-params]');
			if (!card || !content || !nameWrap || !nameLink) { return; }

			item.classList.add('ts-kanban-item');
			card.classList.add('ts-kanban-card');
			var tile = card.querySelector('.info-box-icon');
			if (tile) { tile.classList.add('ts-kanban-icon-tile'); }
			var duplicateIcon = nameLink.querySelector('[class*="fa-"]');
			if (duplicateIcon) { duplicateIcon.remove(); }

			var rawName = (nameLink.textContent || '').replace(/\s+/g, ' ').trim();
			nameLink.replaceChildren(document.createTextNode(rawName));
			nameLink.classList.remove('valignmiddle');
			nameLink.classList.add('ts-kanban-name');
			nameWrap.className = 'ts-kanban-identity';

			var head = element('div', 'ts-kanban-head');
			var identity = element('div', 'ts-kanban-title-block');
			identity.appendChild(nameWrap);
			var badges = element('div', 'ts-kanban-natures');
			identity.appendChild(badges);
			head.appendChild(identity);

			var barcode = element('span', 'ts-kanban-barcode');
			barcode.hidden = true;
			identity.appendChild(barcode);

			var details = element('div', 'ts-kanban-details');
			var phoneLink = content.querySelector('a[href^="tel:"]');
			if (phoneLink) {
				var phoneGlyph = phoneLink.querySelector('[class*="fa-"]');
				var phone = phoneGlyph && (phoneGlyph.getAttribute('title') || '').trim();
				phoneLink.className = 'ts-kanban-detail ts-kanban-phone';
				if (phoneGlyph) { phoneGlyph.removeAttribute('title'); phoneGlyph.setAttribute('aria-hidden', 'true'); }
				if (phone) {
					var phoneValue = element('span', 'ts-kanban-detail-value', phone);
					phoneValue.setAttribute('title', phone);
					phoneLink.appendChild(phoneValue);
					details.appendChild(phoneLink);
				}
			}

			var status = content.querySelector('.info-box-status');
			var statusBadge = status && status.querySelector('.badge-status');
			if (statusBadge) {
				var statusText = (statusBadge.getAttribute('aria-label') || statusBadge.getAttribute('title') || '').trim();
				statusBadge.classList.remove('badge-dot');
				if (!(statusBadge.textContent || '').trim()) { statusBadge.textContent = statusText; }
				status.classList.add('ts-kanban-status');
			}

			var selection = content.querySelector('input.checkforselect');
			if (selection) { selection.classList.add('ts-kanban-bulk-selection'); selection.setAttribute('tabindex', '-1'); }
			Array.from(content.children).forEach(function (child) {
				if (child.tagName === 'BR' || child.classList.contains('inline-block')) { child.remove(); }
			});
			content.replaceChildren(head, details);
			if (status) { content.appendChild(status); }
			if (selection) { content.appendChild(selection); }

			var params;
			try { params = JSON.parse(nameLink.getAttribute('data-params') || '{}'); } catch (error) { params = null; }
			if (params && params.id) {
				entries.set(String(params.id), {nameLink: nameLink, badges: badges, barcode: barcode, details: details, phone: details.querySelector('.ts-kanban-phone')});
			}
		});

		var listUrl = new URL(window.location.href);
		listUrl.searchParams.set('mode', 'common');
		window.fetch(listUrl.toString(), {credentials: 'same-origin'}).then(function (response) {
			return response.ok ? response.text() : '';
		}).then(function (html) {
			var listDocument = new DOMParser().parseFromString(html, 'text/html');
			var listTable = listDocument.querySelector('table.liste');
			var headingRow = listTable && listTable.querySelector('tr.liste_titre');
			if (!headingRow) { return; }
			var headings = Array.from(headingRow.cells);
			function headingIndex(sortField, fallbackLabel) {
				var index = headings.findIndex(function (heading) {
					var link = heading.querySelector('a[href*="sortfield="]');
					if (!link) { return false; }
					return new URL(link.getAttribute('href'), window.location.href).searchParams.get('sortfield') === sortField;
				});
				if (index >= 0) { return index; }
				return headings.findIndex(function (heading) { return (heading.textContent || '').replace(/\s+/g, ' ').trim().toLowerCase() === fallbackLabel; });
			}
			var columns = {
				barcode: headingIndex('s.barcode', 'barcode'),
				address: headingIndex('s.address', 'address'),
				zip: headingIndex('s.zip', 'zip code'),
				phone: headingIndex('s.phone', 'phone'),
				representatives: headings.findIndex(function (heading) { return (heading.textContent || '').replace(/\s+/g, ' ').trim().toLowerCase() === 'sales representatives'; }),
				nature: headings.findIndex(function (heading) { return (heading.textContent || '').replace(/\s+/g, ' ').trim().toLowerCase() === 'nature of third party'; })
			};
			listTable.querySelectorAll('tr[data-rowid]').forEach(function (row) {
				var entry = entries.get(String(row.getAttribute('data-rowid')));
				if (!entry) { return; }
				var cells = row.cells;
				var sourceName = row.querySelector('[data-key="ref"]');
				if (sourceName) { entry.nameLink.textContent = (sourceName.textContent || '').replace(/\s+/g, ' ').trim(); }
				var barcodeValue = columns.barcode >= 0 && cells[columns.barcode] ? (cells[columns.barcode].textContent || '').trim() : '';
				entry.barcode.textContent = barcodeValue;
				entry.barcode.hidden = !barcodeValue;
				entry.badges.replaceChildren();
				var natureCell = columns.nature >= 0 && cells[columns.nature];
				if (natureCell) {
					natureCell.querySelectorAll('.customer-back, .prospect-back, .vendor-back').forEach(function (sourceBadge) {
						var nature = (sourceBadge.getAttribute('title') || sourceBadge.textContent || '').replace(/\s+/g, ' ').trim();
						if (!nature) { return; }
						entry.badges.appendChild(element('span', 'ts-kanban-nature ts-kanban-nature-' + nature.toLowerCase().replace(/[^a-z]+/g, '-'), nature));
					});
				}
				entry.badges.hidden = !entry.badges.children.length;
				var representativeCell = columns.representatives >= 0 && cells[columns.representatives];
				var representatives = representativeCell ? Array.from(representativeCell.querySelectorAll('a[data-params*="user"]')).map(function (node) { return document.importNode(node, true); }) : [];
				addRepresentatives(entry.details, representatives);
				var address = columns.address >= 0 && cells[columns.address] ? (cells[columns.address].textContent || '').replace(/\s+/g, ' ').trim() : '';
				var zip = columns.zip >= 0 && cells[columns.zip] ? (cells[columns.zip].textContent || '').replace(/\s+/g, ' ').trim() : '';
				addDetail(entry.details, 'ts-kanban-address', 'fa-map-marker-alt', address);
				addDetail(entry.details, 'ts-kanban-zip', 'fa-map-pin', zip);
				if (entry.phone) { entry.details.appendChild(entry.phone); }
			});
		}).catch(function () { /* native name, phone and status remain usable */ }).then(function () {
			grid.setAttribute('data-ts-enriched', '1');
		});
		return true;
	}

	/* Turn Dolibarr's native create/edit table into the shared COMMAND form grid.
	   Controls never leave their form or their row, so names, values, hooks,
	   validation and dependent-field handlers remain attached. The field-name map
	   only describes visual span: unknown/module-provided rows deliberately fall
	   back to full width instead of being guessed into a fragile pairing. */
	function enhanceThirdPartyForm() {
		var forms = Array.from(document.querySelectorAll('form'));
		var form = forms.find(function (candidate) {
			return candidate.querySelector('input[name="name"]') &&
				candidate.querySelector('input[name="customer_code"]') &&
				candidate.querySelector('input[name="supplier_code"]') &&
				candidate.querySelector('select[name="country_id"]');
		});
		if (!form || form.getAttribute('data-ts-form') === 'thirdparty') { return false; }
		var card = form.querySelector('div.tabBar');
		var table = card && card.querySelector('table.border:not(.liste)');
		var body = table && table.tBodies[0];
		if (!card || !table || !body) { return false; }
		var surface = card;

		form.classList.add('ts-modern-form', 'ts-thirdparty-form');
		form.setAttribute('data-ts-form', 'thirdparty');
		surface.classList.add('ts-modern-form-card');
		table.classList.add('ts-modern-form-table');

		/* The translated Dolibarr page title, icon and hook output are moved intact
		   into the card. This removes the detached duplicate-looking title surface
		   without synthesising another heading. */
		var titleTable = Array.from(form.parentElement.querySelectorAll(':scope > table.table-fiche-title')).find(function (candidate) {
			return candidate.querySelector('div.titre');
		});
		if (titleTable) {
			titleTable.classList.add('ts-modern-form-header');
			surface.insertBefore(titleTable, table);
		}

		var halfWidthFields = new Set([
			'status', 'barcode', 'fax', 'url'
		]);
		var fullWidthFields = new Set([
			'name', 'name_alias', 'address', 'country_id', 'state_id', 'email',
			'cond_reglement_id', 'mode_reglement_id', 'incoterm_id', 'custcats[]',
			'suppcats[]', 'parent_company_id', 'commercial[]', 'photo', 'capital',
			'forme_juridique_code'
		]);
		var compactControlFields = new Set([
			'status', 'typent_id', 'forme_juridique_code', 'effectif_id',
			'cond_reglement_id', 'mode_reglement_id'
		]);
		var largeControlFields = new Set([
			'name', 'name_alias', 'country_id', 'state_id', 'email'
		]);
		var fullControlFields = new Set([
			'address', 'custcats[]', 'suppcats[]', 'parent_company_id', 'commercial[]', 'photo'
		]);

		Array.from(body.rows).forEach(function (row) {
			/* Some edit templates leave a third, completely empty cell after an
			   optional professional ID. It creates a phantom grid track and a 40px
			   blank band, but carries no field, hook output or accessible content. */
			while (row.cells.length > 2) {
				var trailingCell = row.cells[row.cells.length - 1];
				if ((trailingCell.textContent || '').trim() || trailingCell.children.length) { break; }
				trailingCell.remove();
			}
			var controls = Array.from(row.querySelectorAll('input, select, textarea')).filter(function (control) {
				return control.type !== 'hidden' && !control.classList.contains('select2-search__field');
			});
			var names = controls.map(function (control) { return control.name || control.id || ''; }).filter(Boolean);
			row.classList.add('ts-form-row');
			row.setAttribute('data-ts-fields', names.join(' '));

			if (!controls.length && !(row.textContent || '').trim()) {
				row.classList.add('ts-form-row-empty');
				return;
			}
			if (row.cells.length >= 4) {
				row.classList.add('ts-form-row-paired', 'ts-form-row-full');
			} else if (names.some(function (name) { return halfWidthFields.has(name); })) {
				row.classList.add('ts-form-row-half');
			} else if (names.some(function (name) { return fullWidthFields.has(name); }) || controls.length > 1) {
				row.classList.add('ts-form-row-full');
			} else {
				/* Safe extension point for hook/module fields. */
				row.classList.add('ts-form-row-full');
			}

			Array.from(row.cells).forEach(function (cell, index) {
				cell.classList.add(index % 2 === 0 ? 'ts-form-label' : 'ts-form-value');
				if (index % 2 === 1) {
					var cellControls = Array.from(cell.querySelectorAll('input, select, textarea')).filter(function (control) {
						return control.type !== 'hidden' && !control.classList.contains('select2-search__field');
					});
					var cellNames = cellControls.map(function (control) { return control.name || control.id || ''; });
					var widthRole = cellNames.some(function (name) { return compactControlFields.has(name); }) ? 'compact' :
						(cellNames.some(function (name) { return largeControlFields.has(name); }) ? 'large' :
							(cellNames.some(function (name) { return fullControlFields.has(name); }) ? 'full' : 'medium'));
					cell.classList.add('ts-form-width-' + widthRole);
					if (cellNames.indexOf('email') !== -1) { cell.classList.add('ts-form-control--large-email'); }
					Array.from(cell.children).forEach(function (child) {
						if (!child.matches('span[class*="fa-"], img.pictofixedwidth')) { return; }
						var isHelp = child.classList.contains('fa-info-circle');
						child.classList.add(isHelp ? 'ts-form-help' : 'ts-form-leading-icon');
						cell.classList.add(isHelp ? 'ts-form-value-has-help' : 'ts-form-value-has-leading');
					});
					/* Generated reference codes place their existing tooltip in a
					   nested two-cell table. Promote that same node to the shared help
					   slot so the input is not uniquely narrowed by legacy table cells. */
					var nestedHelp = cell.querySelector(':scope > table.nobordernopadding .classfortooltip:has(.fa-info-circle)');
					if (nestedHelp) {
						var nestedHelpCell = nestedHelp.closest('td');
						nestedHelp.classList.add('ts-form-help');
						cell.appendChild(nestedHelp);
						cell.classList.add('ts-form-value-has-help');
						if (nestedHelpCell && !(nestedHelpCell.textContent || '').trim() && !nestedHelpCell.children.length) { nestedHelpCell.remove(); }
					}
					/* A few Dolibarr selectors keep their info icon inside the same
					   wrapper as Select2. Promote that existing icon to the shared help
					   slot so it follows the control instead of an absolute row edge. */
					var wrappedHelp = cell.querySelector(':scope > div > .fa-info-circle, :scope > span:not(.select2-container) > .fa-info-circle');
					if (wrappedHelp) {
						wrappedHelp.classList.add('ts-form-help');
						cell.appendChild(wrappedHelp);
						cell.classList.add('ts-form-value-has-help');
					}
				}
			});

			/* Dolibarr leaves the nature-choice label cell blank. The controls and
			   their bound labels remain untouched; only the missing visual caption is
			   supplied for the English UI used by this install. */
			if (names.indexOf('prospect') !== -1 && names.indexOf('customer') !== -1 && names.indexOf('supplier') !== -1) {
				row.classList.add('ts-form-choice-row', 'ts-form-row-full');
				if (row.cells[0] && !(row.cells[0].textContent || '').trim()) {
					row.cells[0].textContent = 'Third-party type';
				}
				controls.forEach(function (control) {
					var label = control.closest('label');
					if (label && label.firstChild !== control) { label.insertBefore(control, label.firstChild); }
				});
			}

			/* Existing labels provide useful, locale-aware placeholders. Do not add
			   one to populated/generated codes or non-text controls. */
			for (var pair = 0; pair + 1 < row.cells.length; pair += 2) {
				var label = (row.cells[pair].textContent || '').replace(/\s+/g, ' ').trim().replace(/\s*\*$/, '');
				if (!label) { continue; }
				row.cells[pair + 1].querySelectorAll('input:not([type]), input[type="text"], input[type="email"], textarea').forEach(function (control) {
					if (!control.value && !control.getAttribute('placeholder')) { control.setAttribute('placeholder', label); }
				});
			}
		});

		/* Create and edit templates do not emit every field in the same order.
		   Normalise the two requested visual relationships by moving their existing
		   rows, which keeps every control and handler intact. */
		function rowFor(fieldName) {
			return Array.from(body.rows).find(function (row) {
				return (row.getAttribute('data-ts-fields') || '').split(' ').indexOf(fieldName) !== -1;
			});
		}
		function setRowFields(row, fields) {
			row.setAttribute('data-ts-fields', fields.join(' '));
		}
		function pairSeparateRows(firstField, secondField) {
			var firstRow = rowFor(firstField);
			var secondRow = rowFor(secondField);
			if (!firstRow || !secondRow || firstRow === secondRow || firstRow.cells.length !== 2 || secondRow.cells.length !== 2) { return firstRow; }
			while (secondRow.cells.length) { firstRow.appendChild(secondRow.cells[0]); }
			setRowFields(firstRow, [firstField, secondField]);
			firstRow.classList.remove('ts-form-row-half');
			firstRow.classList.add('ts-form-row-paired', 'ts-form-row-full');
			secondRow.remove();
			return firstRow;
		}
		/* Create/edit templates disagree about which visual pairs arrive as one
		   four-cell row. Normalise every requested pair through the same primitive,
		   which is a no-op when Dolibarr already emitted the relationship. */
		[
			['customer_code', 'supplier_code'],
			['status', 'barcode'],
			['zipcode', 'town'],
			['phone', 'phone_mobile'],
			['fax', 'url'],
			['idprof1', 'idprof2']
		].forEach(function (fields) { pairSeparateRows(fields[0], fields[1]); });

		/* Create already prints this relationship as four cells, while edit prints
		   two independent rows. Pair the edit nodes to the same native structure. */
		pairSeparateRows('assujtva_value', 'tva_intra');

		/* Width is a property of the control's meaning, not of whether its row
		   spans one or two form tracks. Keep the established row axes intact and
		   expose only the exceptional visual proportions requested by the form
		   system. */
		function markControlProportion(fieldName, modifier) {
			var control = body.querySelector('[name="' + fieldName + '"]');
			var valueCell = control && control.closest('td.ts-form-value');
			if (valueCell) { valueCell.classList.add('ts-form-control--' + modifier); }
		}
		markControlProportion('zipcode', 'short');
		markControlProportion('tva_intra', 'medium-intent');

		/* Workforce arrives as the second half of the generic Third-party type row.
		   The intended business composition pairs it with Business entity type. Move
		   those two existing cells, leaving the extra Third-party type as a safe full
		   row instead of dropping a configured field. */
		var typeRow = rowFor('typent_id');
		var businessRow = rowFor('forme_juridique_code');
		if (typeRow && businessRow && typeRow !== businessRow && typeRow.cells.length >= 4 && businessRow.cells.length === 2) {
			businessRow.appendChild(typeRow.cells[2]);
			businessRow.appendChild(typeRow.cells[2]);
			setRowFields(typeRow, ['typent_id']);
			setRowFields(businessRow, ['forme_juridique_code', 'effectif_id']);
			typeRow.classList.remove('ts-form-row-paired');
			typeRow.classList.add('ts-form-row-full');
			businessRow.classList.remove('ts-form-row-half');
			businessRow.classList.add('ts-form-row-paired', 'ts-form-row-full');
		}

		var capitalRow = rowFor('capital');
		if (capitalRow) {
			capitalRow.classList.remove('ts-form-row-half');
			capitalRow.classList.add('ts-form-row-full', 'ts-form-compound-capital');
			if (capitalRow.cells[1]) { capitalRow.cells[1].classList.add('ts-form-control--compound'); }
		}
		var incotermsRow = rowFor('incoterm_id');
		if (incotermsRow) {
			incotermsRow.classList.add('ts-form-compound-incoterms');
			if (incotermsRow.cells[1]) { incotermsRow.cells[1].classList.add('ts-form-control--paired'); }
			var incotermsSelect = incotermsRow.querySelector('select[name="incoterm_id"]');
			var emptyIncoterm = incotermsSelect && Array.from(incotermsSelect.options).find(function (option) { return !option.value || option.value === '0'; });
			if (emptyIncoterm && !(emptyIncoterm.textContent || '').replace(/\u00a0/g, ' ').trim()) {
				emptyIncoterm.textContent = 'Select Incoterms';
				var renderedIncoterm = incotermsRow.querySelector('.select2-selection__rendered');
				if (renderedIncoterm && incotermsSelect.value === emptyIncoterm.value) {
					renderedIncoterm.textContent = emptyIncoterm.textContent;
					renderedIncoterm.setAttribute('title', emptyIncoterm.textContent);
				}
			}
			var incotermLocation = incotermsRow.querySelector('input[name="location_incoterms"]');
			if (incotermLocation) { incotermLocation.setAttribute('placeholder', 'Location (optional)'); }
		}
		if (capitalRow) {
			var capitalInput = capitalRow.querySelector('input[name="capital"]');
			if (capitalInput) { capitalInput.setAttribute('placeholder', '0.00'); }
		}
		var photoRow = rowFor('photo');
		if (photoRow) { photoRow.classList.add('ts-form-file-row'); }
		var commercialRow = rowFor('commercial[]');
		var logoRow = rowFor('photo');
		if (commercialRow && logoRow && commercialRow.nextElementSibling !== logoRow) {
			body.insertBefore(commercialRow, logoRow);
		}

		var actions = Array.from(form.children).find(function (child) {
			return child.matches('div.center') && child.querySelector('input[type="submit"]');
		});
		if (actions) { actions.classList.add('ts-modern-form-actions'); }

		/* Form Select2 widgets share one behavioural contract. Short single-choice
		   lists are compact enums; relational and multi-value controls remain
		   searchable. The native option values and empty-option semantics are never
		   changed: only Select2's presentation is annotated. */
		function sourceSelectFor(container) {
			var sibling = container && container.previousElementSibling;
			if (sibling && sibling.matches('select')) { return sibling; }
			return Array.from(form.querySelectorAll('select')).find(function (select) {
				return select.nextElementSibling === container;
			}) || null;
		}
		function formSelectLabel(select) {
			var cell = select && select.closest('td.ts-form-value');
			var labelCell = cell && cell.previousElementSibling;
			var label = labelCell ? (labelCell.textContent || '').replace(/\s+/g, ' ').trim().replace(/\s*\*$/, '') : '';
			return label ? 'Select ' + label : 'Select an option';
		}
		function decorateFormSelect2() {
			var fixedEnumFields = new Set([
				'status', 'typent_id', 'forme_juridique_code', 'effectif_id',
				'cond_reglement_id', 'mode_reglement_id', 'incoterm_id'
			]);
			form.querySelectorAll('.select2-container').forEach(function (container) {
				var select = sourceSelectFor(container);
				if (!select) { return; }
				var valueCell = select.closest('td.ts-form-value');
				if (valueCell) {
					var compactControl = valueCell.classList.contains('ts-form-width-compact');
					var largeControl = valueCell.classList.contains('ts-form-width-large');
					var fullControl = valueCell.classList.contains('ts-form-width-full');
					container.classList.toggle('ts-form-control-compact', compactControl);
					container.classList.toggle('ts-form-control-large', largeControl);
					container.classList.toggle('ts-form-control-full', fullControl);
					if (compactControl) {
						container.style.setProperty('width', 'min(100%, 340px)', 'important');
						container.style.setProperty('max-width', '340px', 'important');
					} else if (largeControl) {
						var largeCap = valueCell.classList.contains('ts-form-control--large-email') ? '760px' : '820px';
						container.style.setProperty('width', 'min(100%, ' + largeCap + ')', 'important');
						container.style.setProperty('max-width', largeCap, 'important');
					} else if (fullControl) {
						container.style.setProperty('width', '100%', 'important');
						container.style.setProperty('max-width', 'none', 'important');
					}
				}
				var meaningfulOptions = Array.from(select.options).filter(function (option) {
					return (option.textContent || '').replace(/\u00a0/g, ' ').trim();
				});
				var compact = !select.multiple && (fixedEnumFields.has(select.name || select.id || '') || meaningfulOptions.length <= 12);
				container.classList.toggle('ts-form-select2-compact', compact);
				container.classList.toggle('ts-form-select2-searchable', !compact);
				var selected = select.options[select.selectedIndex];
				var rendered = container.querySelector('.select2-selection__rendered');
				if (rendered && selected && !(selected.textContent || '').replace(/\u00a0/g, ' ').trim()) {
					var placeholder = formSelectLabel(select);
					rendered.textContent = placeholder;
					rendered.setAttribute('title', placeholder);
					rendered.classList.add('ts-form-select2-placeholder');
				} else if (rendered) {
					rendered.classList.remove('ts-form-select2-placeholder');
				}
			});
		}
		decorateFormSelect2();

		/* Select2 dropdowns are body-mounted, so descendant selectors cannot give
		   them the form control's width. Mark only surfaces whose expanded source is
		   inside this form and anchor them to that live trigger. */
		var syncFormSelect2 = function () {
			window.requestAnimationFrame(function () {
				decorateFormSelect2();
				var selection = form.querySelector('.select2-selection[aria-expanded="true"]');
				var container = selection && selection.closest('.select2-container');
				var dropdown = document.querySelector('.select2-container--open .select2-dropdown');
				if (!container || !dropdown) { return; }
				var select = sourceSelectFor(container);
				var compact = container.classList.contains('ts-form-select2-compact');
				var rect = container.getBoundingClientRect();
				var popupRoot = dropdown.parentElement;
				var popupWidth = Math.min(rect.width, window.innerWidth - 24);
				dropdown.classList.add('ts-form-select2-dropdown', compact ? 'ts-form-select2-dropdown-compact' : 'ts-form-select2-dropdown-searchable');
				dropdown.style.setProperty('width', popupWidth + 'px', 'important');
				dropdown.style.setProperty('min-width', popupWidth + 'px');
				dropdown.querySelectorAll('.select2-results__option').forEach(function (option) {
					var blank = !(option.textContent || '').replace(/\u00a0/g, ' ').trim();
					option.classList.toggle('ts-form-select2-empty-option', blank);
					if (blank) { option.setAttribute('aria-hidden', 'true'); }
				});
				if (select) { dropdown.setAttribute('data-ts-select-name', select.name || select.id || ''); }
				if (!popupRoot) { return; }
				var popupHeight = dropdown.getBoundingClientRect().height;
				var roomBelow = window.innerHeight - rect.bottom;
				var roomAbove = rect.top;
				var openAbove = popupHeight > roomBelow && roomAbove > roomBelow;
				var left = Math.max(12, Math.min(rect.left, window.innerWidth - popupWidth - 12));
				var top = openAbove ? rect.top - popupHeight : rect.bottom;
				popupRoot.classList.add('ts-form-select2-root');
				popupRoot.style.setProperty('position', 'fixed', 'important');
				popupRoot.style.setProperty('left', left + 'px', 'important');
				popupRoot.style.setProperty('top', Math.max(12, Math.min(top, window.innerHeight - popupHeight - 12)) + 'px', 'important');
				popupRoot.style.setProperty('width', popupWidth + 'px', 'important');
				popupRoot.style.setProperty('z-index', '3600', 'important');
				dropdown.classList.toggle('select2-dropdown--above', openAbove);
				dropdown.classList.toggle('select2-dropdown--below', !openAbove);
				container.classList.toggle('select2-container--above', openAbove);
				container.classList.toggle('select2-container--below', !openAbove);
			});
		};
		var formSelect2Observer = new MutationObserver(syncFormSelect2);
		formSelect2Observer.observe(document.body, {childList: true, subtree: true});
		document.addEventListener('select2:open', syncFormSelect2);
		window.addEventListener('resize', syncFormSelect2, {passive: true});
		document.addEventListener('scroll', syncFormSelect2, {passive: true, capture: true});
		return true;
	}

	function polishCategoryDialogPage() {
		if (!document.body.classList.contains('dol_openinpopup') || !/\/categories\/(?:categorie_list|card)\.php$/.test(window.location.pathname)) { return false; }
		document.body.classList.add('ts-category-dialog-page');
		var isCreate = /\/categories\/card\.php$/.test(window.location.pathname);
		document.body.classList.toggle('ts-category-dialog-create-page', isCreate);
		function sizeHostDialog(dialog, createMode) {
			if (!dialog) { return; }
			window.requestAnimationFrame(function () {
				var fiche = document.querySelector('.fiche');
				var contentHeight = fiche ? fiche.scrollHeight : document.body.scrollHeight;
				var viewportLimit = Math.floor(window.parent.innerHeight * .84);
				var desiredHeight = createMode ? Math.min(820, viewportLimit) : Math.min(Math.max(contentHeight + 64, 360), 620, viewportLimit);
				dialog.style.setProperty('height', desiredHeight + 'px', 'important');
			});
		}
		try {
			var hostFrame = Array.from(window.parent.document.querySelectorAll('iframe.iframedialog')).find(function (frame) { return frame.contentWindow === window; });
			var hostDialog = hostFrame && hostFrame.closest('.ui-dialog');
			var dialogTitle = hostDialog?.querySelector('.ui-dialog-title');
			var innerTitle = document.querySelector('div.titre');
			var translatedTitle = ((innerTitle && innerTitle.textContent) || document.title || '').replace(/\s+/g, ' ').trim().replace(/(\))\d+$/, '$1');
			if (dialogTitle && translatedTitle) { dialogTitle.textContent = translatedTitle; }
			if (hostDialog) {
				hostDialog.classList.toggle('ts-category-dialog-create', isCreate);
				hostDialog.classList.toggle('ts-category-dialog-list-state', !isCreate);
			}
		} catch (e) { /* Same-origin iframe is expected; retain native title if not. */ }
		if (isCreate) {
			var createForm = document.querySelector('form[action*="/categories/card.php"]');
			if (createForm) {
				createForm.classList.add('ts-category-create-form');
				var createTable = createForm.querySelector('.tabBar table.border');
				if (createTable) {
					createTable.querySelectorAll(':scope > tbody > tr').forEach(function (row) {
						var cells = row.querySelectorAll(':scope > td');
						if (cells.length < 2) { return; }
						row.classList.add('ts-category-form-row');
						cells[0].classList.add('ts-category-form-label');
						cells[1].classList.add('ts-category-form-control');
					});
				}
				var colorInput = createForm.querySelector('#colorpickercolor');
				var colorPicker = colorInput && colorInput.parentElement.querySelector('.jPicker');
				if (colorInput && colorPicker && !colorInput.closest('.ts-category-color-control')) {
					var colorControl = document.createElement('span');
					colorControl.className = 'ts-category-color-control';
					colorInput.parentNode.insertBefore(colorControl, colorInput);
					colorControl.appendChild(colorInput);
					colorControl.appendChild(colorPicker);
				}
				var parentSelect = createForm.querySelector('select#parent');
				if (parentSelect) {
					parentSelect.classList.add('ts-category-parent-select');
					var neutralOption = Array.from(parentSelect.options).find(function (option) { return option.value === '-1' || !option.value; });
					var parentIcon = parentSelect.parentElement.querySelector('.pictofixedwidth[title]');
					if (neutralOption && !neutralOption.textContent.trim()) { neutralOption.textContent = parentIcon?.getAttribute('title') || 'Parent tag/category'; }
					var parentContainer = parentSelect.nextElementSibling?.classList.contains('select2-container') ? parentSelect.nextElementSibling : null;
					if (parentContainer) {
						parentContainer.classList.add('ts-category-parent-container');
						parentContainer.style.removeProperty('width');
						var rendered = parentContainer.querySelector('.select2-selection__rendered');
						if (rendered && neutralOption?.selected) { rendered.textContent = neutralOption.textContent; }
					}
					if (window.jQuery && !parentSelect.dataset.tsCategorySelectBound) {
						parentSelect.dataset.tsCategorySelectBound = '1';
						window.jQuery(parentSelect).on('select2:open.tsCategoryDialog', function () {
							window.requestAnimationFrame(function () {
								var trigger = parentSelect.nextElementSibling?.querySelector('.select2-selection');
								var openContainer = document.querySelector('.select2-container--open:not(.select2)');
								var dropdown = openContainer?.querySelector('.select2-dropdown');
								if (!trigger || !dropdown) { return; }
								dropdown.classList.add('ts-category-select2-dropdown');
								dropdown.style.setProperty('width', trigger.getBoundingClientRect().width + 'px', 'important');
								var search = dropdown.querySelector('.select2-search--dropdown');
								if (search) { search.classList.toggle('ts-category-select2-search-hidden', parentSelect.options.length <= 8); }
								dropdown.querySelectorAll('.select2-results__option').forEach(function (option) {
									if (!option.textContent.trim() && neutralOption) { option.textContent = neutralOption.textContent; }
								});
							});
						});
					}
				}
			}
			sizeHostDialog(hostDialog, true);
			return Boolean(createForm);
		}
		var content = document.querySelector('.fiche');
		if (content) { content.classList.add('ts-category-dialog-content'); }
		var pageTitle = document.querySelector('.ts-pagehead div.titre');
		if (pageTitle) {
			var shortTitle = (pageTitle.textContent || '').replace(/\s+/g, ' ').trim().replace(/\s*\(.+\)\d*\s*$/, '').replace(/\d+$/, '').trim();
			pageTitle.textContent = shortTitle;
			pageTitle.closest('.ts-pagehead-title')?.classList.add('ts-category-redundant-title');
		}
		var viewLinks = Array.from(document.querySelectorAll('a.btnTitle')).filter(function (link) { return /List view|Hierarch/i.test(link.getAttribute('title') || ''); });
		var viewHost = document.querySelector('.ts-category-view-switch');
		if (!viewHost && viewLinks.length) {
			viewHost = document.createElement('span');
			viewHost.className = 'ts-category-view-switch';
			var actions = document.querySelector('.ts-pagehead-actions');
			if (actions) { actions.insertBefore(viewHost, actions.firstChild); }
			else { viewLinks[0].parentNode.insertBefore(viewHost, viewLinks[0]); }
		}
		if (viewHost) {
			viewLinks.forEach(function (link) {
				link.classList.add('ts-category-view-option');
				if (!link.querySelector('.ts-category-view-label')) {
					var label = document.createElement('span');
					label.className = 'ts-category-view-label';
					label.textContent = (link.getAttribute('title') || '').replace(/([a-z])([A-Z])/g, '$1 $2');
					link.appendChild(label);
				}
				viewHost.appendChild(link);
			});
			viewHost.querySelectorAll('.button-title-separator').forEach(function (node) { node.remove(); });
		}
		document.querySelectorAll('.ts-pagehead-actions .button-title-separator, .ts-pagehead-actions .ts-view-switch:empty').forEach(function (node) { node.remove(); });
		var list = document.querySelector('table.liste');
		if (list) { list.closest('.fichecenter')?.classList.add('ts-category-dialog-list'); }
		if (list && !list.querySelector('#iddivjstree')) {
			list.querySelectorAll('tr.oddeven').forEach(function (row) {
				var categoryLink = row.querySelector('a.classforajaxtooltip');
				if (!categoryLink) { return; }
				var categoryCell = categoryLink.closest('td');
				var rowContent = document.createElement('div');
				rowContent.className = 'ts-category-list-row-content';
				var actions = document.createElement('span');
				actions.className = 'ts-category-row-actions';
				row.querySelectorAll('a.editfielda, a.deletefilelink').forEach(function (action) {
					var titledIcon = action.querySelector('[title]');
					if (titledIcon) {
						action.setAttribute('aria-label', titledIcon.getAttribute('title'));
						titledIcon.removeAttribute('title');
					}
					actions.appendChild(action);
				});
				if (categoryLink.getAttribute('title') === 'tocomplete') { categoryLink.removeAttribute('title'); }
				categoryCell.colSpan = 100;
				categoryCell.classList.add('ts-category-row-main');
				rowContent.appendChild(categoryLink);
				rowContent.appendChild(actions);
				categoryCell.replaceChildren(rowContent);
				row.querySelectorAll('td').forEach(function (cell) { if (cell !== categoryCell) { cell.classList.add('ts-category-row-hidden'); } });
				row.classList.add('ts-category-data-row');
			});
		}
		if (list && list.querySelector('#iddivjstree')) {
			list.querySelectorAll('#iddivjstree a.editfielda, #iddivjstree a.deletefilelink').forEach(function (action) {
				var titledIcon = action.querySelector('[title]');
				if (titledIcon) { action.setAttribute('aria-label', titledIcon.getAttribute('title')); titledIcon.removeAttribute('title'); }
			});
			list.querySelectorAll('#iddivjstree a[title="tocomplete"]').forEach(function (link) { link.removeAttribute('title'); });
		}
		var empty = list && list.querySelector('.opacitymedium');
		var emptyCell = empty && (empty.closest('td[colspan]') || empty.closest('td'));
		if (emptyCell) {
			var emptyTable = empty.closest('table');
			if (emptyTable === list) {
				var emptyRow = document.createElement('tr');
				emptyRow.className = 'ts-category-empty-row';
				var modernEmptyCell = document.createElement('td');
				/* Dolibarr's filter and label rows do not always expose the same cell count.
				   A deliberately large colspan lets HTML clamp this presentation row to the
				   real table width without coupling the empty state to enabled columns. */
				modernEmptyCell.colSpan = 100;
				modernEmptyCell.className = 'ts-category-dialog-empty';
				emptyRow.appendChild(modernEmptyCell);
				var lastHeading = Array.from(list.querySelectorAll('tr.liste_titre')).pop();
				(lastHeading || emptyCell.closest('tr')).insertAdjacentElement('afterend', emptyRow);
				modernEmptyCell.appendChild(empty);
				emptyCell = modernEmptyCell;
			} else {
				emptyCell.classList.add('ts-category-dialog-empty');
				if (emptyTable) { emptyTable.classList.add('ts-category-empty-content'); }
			}
			if (!emptyCell.querySelector('.ts-category-empty-icon')) {
				var icon = document.createElement('span');
				icon.className = 'ts-category-empty-icon fas fa-tags';
				icon.setAttribute('aria-hidden', 'true');
				emptyCell.insertBefore(icon, emptyCell.firstChild);
			}
		}
		try { hostDialog?.classList.toggle('ts-category-dialog-empty-state', Boolean(emptyCell)); } catch (e) { /* no-op */ }
		sizeHostDialog(hostDialog, false);
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
		if (listWrap && !list) {
			/* Several record tabs -- Events/Agenda in its list mode, for one -- render
			   the very same filter/heading/data structure on a table.noborder instead
			   of table.liste, so the shared composition skipped them and the raw
			   Dolibarr filter row showed through. Match on the liste_titre rows this
			   function actually operates on rather than on the table class, still
			   scoped to the responsive wrapper so unrelated tables are untouched. */
			list = Array.prototype.find.call(listWrap.querySelectorAll('table'), function (table) {
				return table.querySelector('tr.liste_titre') && table.querySelector('tr.oddeven, tr.impair, tr.pair');
			}) || null;
		}
		var form = listWrap && listWrap.closest('form');
		if (!list || !form) { return false; }

		var filter = form.querySelector('div.liste_titre.liste_titre_bydiv');
		var embeddedFilterRow = null;
		/* Only some lists ship the filters as a div.liste_titre_bydiv block; the
		   rest keep them in the table's own filter row. This fallback was limited
		   to third-party record tabs, so every other such list -- stock movements,
		   for one -- composed its table but left the raw filter row showing above
		   it. The row is the same structure wherever it appears.

		   Gated on Dolibarr's own bodyforlist marker: without it this also claimed
		   the filter row on admin screens such as website.php, which then failed
		   to compose as a settings page. */
		if (!filter && (document.body.classList.contains('bodyforlist') || document.body.classList.contains('ts-thirdparty-record-context'))) {
			embeddedFilterRow = list.querySelector('tr.liste_titre_filter') || Array.from(list.rows || []).find(function (row, index) {
				return index < 2 && row.querySelector('input:not([type="hidden"]), select, button[name="button_search_x"]');
			});
			if (embeddedFilterRow) {
				filter = document.createElement('div');
				filter.className = 'liste_titre liste_titre_bydiv ts-secondary-filter-source';
				form.insertBefore(filter, listWrap);
			}
		}
		var composition = document.createElement('section');
		composition.className = 'ts-list-composition';
		composition.setAttribute('aria-label', (title.textContent || '').replace(/\s+/g, ' ').trim());
		listWrap.parentNode.insertBefore(composition, filter || listWrap);
		if (filter) {
			filter.classList.add('ts-filter-surface');
			composition.appendChild(filter);

			/* Promote the native name search into the shared filter surface and put
			   the remaining per-column controls behind one disclosure. Every original
			   form control is moved intact, so names, values and submission behaviour
			   remain Dolibarr's own. */
			var filterRow = list.querySelector('tr.liste_titre_filter') || embeddedFilterRow;
			if (filterRow) {
				var headingRow = filterRow.nextElementSibling;
				var quick = document.createElement('div');
				quick.className = 'ts-quick-search';
				var searchIcon = document.createElement('span');
				searchIcon.className = 'fas fa-search';
				searchIcon.setAttribute('aria-hidden', 'true');
				quick.appendChild(searchIcon);
				var nameSearch = filterRow.querySelector('input[name="search_nom"], input[name="search_name"], input[type="text"]');
				if (nameSearch) {
					nameSearch.classList.add('ts-quick-search-input');
					if (!document.body.classList.contains('ts-thirdparty-record-context')) {
						/* Every list said "Search third parties" regardless of what it
						   listed. Name the page instead -- its heading already does. */
						var pageTitle = document.querySelector('.ts-pagehead div.titre');
						var pageLabel = pageTitle ? (pageTitle.textContent || '').replace(/\s+/g, ' ').replace(/\d[\d\s.,]*$/, '').trim() : '';
						nameSearch.setAttribute('placeholder', pageLabel ? 'Search ' + pageLabel.toLowerCase() + '…' : 'Search…');
					} else {
						/* Name the tab actually being searched. This used to say
						   "contacts/addresses" on every record tab, which read as plainly
						   wrong once tabs beyond Contacts/Addresses gained this surface. */
						var activeTabLink = document.querySelector('.tabsElemActive a');
						var tabLabel = activeTabLink ? (activeTabLink.textContent || '').replace(/\s+/g, ' ').replace(/\d+$/, '').trim() : '';
						if (/^events\b/i.test(tabLabel)) { tabLabel = 'events'; }
						nameSearch.setAttribute('placeholder', tabLabel ? 'Search ' + tabLabel.toLowerCase() + '…' : 'Search…');
					}
					nameSearch.classList.remove('maxwidth50', 'maxwidth75', 'maxwidth100', 'maxwidth150');
					quick.appendChild(nameSearch);
				}
				var clearFilters = null;
				filterRow.querySelectorAll('button[name="button_search_x"], button[name="button_removefilter_x"]').forEach(function (button) {
					button.classList.add(button.name === 'button_removefilter_x' ? 'ts-clear-filters' : 'ts-submit-search');
					if (button.name === 'button_removefilter_x') {
						clearFilters = button;
						button.classList.add('ts-clear-all-filters');
						var clearIcon = button.querySelector('[class*="fa-"]');
						if (clearIcon) { clearIcon.setAttribute('aria-hidden', 'true'); }
						button.appendChild(document.createTextNode('Clear filters'));
					} else {
						quick.appendChild(button);
					}
				});
				if (nameSearch) {
					nameSearch.addEventListener('keydown', function (event) {
						if (event.key !== 'Enter' || event.isComposing) { return; }
						var submit = quick.querySelector('button[name="button_search_x"]');
						if (!submit) { return; }
						event.preventDefault();
						submit.click();
					});
				}
				if (nameSearch) { filter.insertBefore(quick, filter.firstChild); }
				/* Dolibarr parks the timeline/list view switch in its own title table, which
				   rendered as a card containing nothing else. Lift it into this toolbar so
				   both view modes present one control bar. */
				var nativeTitle = document.querySelector('table.table-fiche-title');
				var nativeSwitch = nativeTitle && nativeTitle.querySelector('.paginationafterarrows');
				if (nativeSwitch && nativeSwitch.querySelector('a')) {
					var switcher = document.createElement('div');
					switcher.className = 'ts-events-view-switch';
					nativeSwitch.querySelectorAll(':scope > a').forEach(function (link) {
						link.classList.add('ts-events-view-option');
						link.setAttribute('aria-label', link.getAttribute('title') || 'Change event view');
						switcher.appendChild(link);
					});
					filter.insertBefore(switcher, filter.firstChild);
					nativeTitle.classList.add('ts-events-native-title-source');
				}
				/* The first row of Dolibarr's list filter provides the promoted toolbar
				   controls in display order. Add structural hooks without depending on
				   translated labels or field names. */
				Array.from(filter.children).filter(function (child) {
					return child.classList.contains('divsearchfield');
				}).forEach(function (control, index) {
					control.classList.add('ts-toolbar-filter', 'ts-toolbar-filter-' + (index + 1));
				});

				var details = document.createElement('details');
				details.className = 'ts-column-filters';
				var disclosure = document.createElement('summary');
				disclosure.appendChild(document.createTextNode('Filters'));
				var filterGlyph = document.createElement('span');
				filterGlyph.className = 'fas fa-filter';
				filterGlyph.setAttribute('aria-hidden', 'true');
				disclosure.insertBefore(filterGlyph, disclosure.firstChild);
				var panel = document.createElement('div');
				panel.className = 'ts-column-filters-panel';
				var nameCell = nameSearch && nameSearch.closest('td, th');
				/* List pages promote their leading filters into the toolbar; the record-tab
				   path used to send every one to the disclosure, so Events/Agenda hid its
				   Date and Type behind "Filters" while the timeline mode showed them inline.
				   Promote the same number of leading controls here, reusing the existing
				   ts-toolbar-filter slots so both modes share one visual language. */
				var promoted = 0;
				var promoteLimit = embeddedFilterRow ? 2 : 0;
				Array.from(filterRow.cells).forEach(function (cell, index) {
					if (index === 0 || cell === nameCell) { return; }
					var movable = Array.from(cell.children).filter(function (child) { return child.tagName !== 'SCRIPT'; });
					if (!movable.some(function (child) { return child.matches('input, select, .select2') || child.querySelector('input, select'); })) { return; }
					var control = document.createElement('label');
					control.className = 'ts-column-filter-control';
					var label = document.createElement('span');
					label.className = 'ts-column-filter-label';
					label.textContent = (headingRow && headingRow.cells[index] && headingRow.cells[index].innerText || 'Filter').replace(/\s+/g, ' ').trim();
					control.appendChild(label);
					movable.forEach(function (child) { control.appendChild(child); });
					/* Select2 may initialise before or after this deferred script's ready
					   callback, so detect the real select without depending on its runtime
					   select2-hidden-accessible class. */
					var compactSelect = control.querySelector('select');
					if (compactSelect && compactSelect.options.length <= 8) {
						compactSelect.setAttribute('data-ts-compact-select2', '1');
					}
					var compactEmptyLabels = {
						'status': 'All statuses',
						'nature of third party': 'All types',
						'third-party type': 'All third-party types'
					};
					var compactLabelKey = label.textContent.toLowerCase();
					var compactEmptyLabel = compactEmptyLabels[compactLabelKey] || ('All ' + compactLabelKey);
					if (compactSelect && compactSelect.getAttribute('data-ts-compact-select2') === '1') {
						var emptyOption = Array.from(compactSelect.options).find(function (option) {
							return !(option.textContent || '').replace(/\u00a0/g, ' ').trim();
						});
						if (emptyOption) {
							emptyOption.textContent = compactEmptyLabel;
							compactSelect.setAttribute('data-ts-empty-label', compactEmptyLabel);
							var renderedEmpty = control.querySelector('.select2-selection__rendered');
							if (renderedEmpty && compactSelect.value === emptyOption.value) {
								renderedEmpty.textContent = compactEmptyLabel;
								renderedEmpty.setAttribute('title', compactEmptyLabel);
							}
							compactSelect.addEventListener('change', function (event) {
								var changedSelect = event.currentTarget;
								window.requestAnimationFrame(function () {
									if (changedSelect.value !== emptyOption.value) { return; }
									var rendered = changedSelect.parentElement.querySelector('.select2-selection__rendered');
									var selectedEmptyLabel = changedSelect.getAttribute('data-ts-empty-label');
									if (rendered && selectedEmptyLabel) { rendered.textContent = selectedEmptyLabel; rendered.setAttribute('title', selectedEmptyLabel); }
								});
							});
						}
					}
					if (promoted < promoteLimit && control.querySelector('select, input:not([type="hidden"])')) {
						promoted += 1;
						control.classList.add('divsearchfield', 'ts-toolbar-filter', 'ts-toolbar-filter-' + promoted);
						filter.appendChild(control);
					} else {
						panel.appendChild(control);
					}
				});
				filterRow.classList.add('ts-filter-row-extracted');
				if (panel.children.length) {
					details.appendChild(disclosure);
					details.appendChild(panel);
					filter.appendChild(details);
					if (clearFilters) {
						var hasActiveFilter = Array.from(filter.querySelectorAll('.ts-quick-search-input, .ts-column-filter-control input:not([type="hidden"]), .ts-column-filter-control select')).some(function (field) {
							if (field.disabled || field.name === 'button_search_x' || field.name === 'button_removefilter_x') { return false; }
							if (field.type === 'checkbox' || field.type === 'radio') { return field.checked; }
							var value = window.jQuery && window.jQuery(field).val ? window.jQuery(field).val() : field.value;
							if (Array.isArray(value)) { return value.length > 0; }
							return value !== null && String(value).trim() !== '' && String(value) !== '-1';
						});
						clearFilters.hidden = !hasActiveFilter;
						clearFilters.lastChild.textContent = 'Clear';
						filter.appendChild(clearFilters);
					}

					/* Select2 mounts its dropdown under body. Only a dropdown whose
					   expanded source selection lives in this panel is internal; toolbar
					   and page-size Select2 instances must dismiss the advanced panel. */
					var hasOpenAdvancedSelect2 = function () {
						return Boolean(panel.querySelector('.select2-selection[aria-expanded="true"]'));
					};
					document.addEventListener('click', function (event) {
						if (!details.open || details.contains(event.target)) { return; }
						var bodyMountedDropdown = event.target.closest && event.target.closest('.select2-dropdown');
						if (bodyMountedDropdown && (bodyMountedDropdown.classList.contains('ts-column-filter-dropdown') || hasOpenAdvancedSelect2())) { return; }
						details.removeAttribute('open');
					});
					document.addEventListener('keydown', function (event) {
						if (event.key !== 'Escape' || !details.open) { return; }
						details.removeAttribute('open');
						window.requestAnimationFrame(function () { disclosure.focus(); });
					});

					/* These Select2 instances were initialised before Dolibarr's controls
					   were moved into the panel. Their dropdown adapter retained the old
					   table-cell measure, so synchronise each open surface to its current
					   grid control and add one shared styling hook. */
					var syncOpenSelect2 = function () {
						if (!details.open) { return; }
						window.requestAnimationFrame(function () {
							var selection = panel.querySelector('.select2-selection[aria-expanded="true"]');
							var container = selection && selection.closest('.select2-container');
							var dropdown = document.querySelector('.select2-container--open .select2-dropdown');
							if (!container || !dropdown) { return; }
							var width = container.getBoundingClientRect().width;
							var source = container.parentElement && container.parentElement.querySelector('select.select2-hidden-accessible');
							dropdown.classList.add('ts-column-filter-dropdown');
							if (source && source.getAttribute('data-ts-compact-select2') === '1') {
								dropdown.classList.add('ts-compact-select2-dropdown');
							}
							var emptyLabel = source && source.getAttribute('data-ts-empty-label');
							if (emptyLabel) {
								dropdown.querySelectorAll('.select2-results__option').forEach(function (option) {
									if (!(option.textContent || '').replace(/\u00a0/g, ' ').trim()) { option.textContent = emptyLabel; }
								});
							}
							dropdown.style.setProperty('--ts-column-filter-width', width + 'px');
							dropdown.style.setProperty('width', width + 'px', 'important');
							dropdown.style.setProperty('min-width', width + 'px');
							dropdown.style.setProperty('max-width', 'calc(100vw - 24px)');
						});
					};
					/* Select2 may load after this file and mounts its dropdown under body.
					   Observing added nodes is independent of plugin load order and covers
					   mouse, touch and keyboard opens through the same path. */
					var select2Observer = new MutationObserver(syncOpenSelect2);
					select2Observer.observe(document.body, {childList: true, subtree: true});
				}
				filterRow.classList.add('ts-column-filters-source');
			}
		}
		var card = document.createElement('div');
		card.className = 'ts-list-card';
		composition.appendChild(card);
		card.appendChild(listWrap);
		list.querySelectorAll('a.classforajaxtooltip[title]').forEach(function (link) {
			if ((link.getAttribute('title') || '').trim().toLowerCase() !== 'tocomplete') { return; }
			if (!link.hasAttribute('aria-label')) {
				var accessibleName = (link.textContent || '').replace(/\s+/g, ' ').trim();
				if (accessibleName) { link.setAttribute('aria-label', accessibleName); }
			}
			/* Dolibarr's delegated handler needs this sentinel title while it fetches.
			   structureAjaxTooltip removes it as soon as the custom card arrives. */
			link.addEventListener('mouseleave', function () {
				if (!link.hasAttribute('title')) { link.setAttribute('title', 'tocomplete'); }
			});
		});
		/* Dolibarr repeats each visible sortable label in the parent th title.
		   That produces a redundant native tooltip and adds no accessible name. */
		list.querySelectorAll('tr.liste_titre > th[title], tr.liste_titre > td[title]').forEach(function (cell) {
			var sortLink = cell.querySelector('a.reposition');
			if (!sortLink) { return; }
			var visibleLabel = (sortLink.textContent || '').replace(/\s+/g, ' ').trim();
			var tooltipLabel = (cell.getAttribute('title') || '').replace(/\s+/g, ' ').trim();
			if (visibleLabel && visibleLabel === tooltipLabel) { cell.removeAttribute('title'); }
		});
		/* Dolibarr emits the active sort indicator before its anchor. Move that
		   existing indicator after the href-bearing label without recreating it. */
		list.querySelectorAll('tr.liste_titre > th.liste_titre_sel, tr.liste_titre > td.liste_titre_sel').forEach(function (cell) {
			var sortLink = cell.querySelector(':scope > a.reposition');
			var sortIndicator = cell.querySelector(':scope > span.nowrap');
			if (!sortLink || !sortIndicator) { return; }
			sortIndicator.classList.add('ts-sort-indicator');
			sortLink.insertAdjacentElement('afterend', sortIndicator);
		});

		var totalNode = title.querySelector('.ts-count');
		var total = totalNode ? parseInt((totalNode.textContent || '').replace(/[^0-9]/g, ''), 10) : NaN;
		var limitSelect = form.querySelector('select[name="limit"], select.selectlimit');
		if (limitSelect) { limitSelect.setAttribute('data-ts-compact-select2', '1'); }
		var limit = limitSelect ? parseInt(limitSelect.value, 10) : NaN;
		var pageInput = form.querySelector('input[name="pageplusone"], .pageplusone input');
		var current = pageInput ? parseInt(pageInput.value, 10) : 1;
		if (!current || current < 1) { current = 1; }
		var kanbanItems = list.querySelectorAll('tr.trkanban .box-flex-container.kanban > .box-flex-item').length;
		var visibleRows = kanbanItems || list.querySelectorAll('tbody tr.oddeven').length;
		if (!limit || limit < 1) { limit = visibleRows || total || 0; }
		var first = total === 0 ? 0 : ((current - 1) * limit) + 1;
		var last = Number.isNaN(total) ? first + Math.max(visibleRows - 1, 0) : Math.min(total, first + Math.max(visibleRows - 1, 0));

		var footer = document.createElement('footer');
		footer.className = 'ts-results-footer';
		var summary = document.createElement('span');
		summary.className = 'ts-results-summary';
		summary.textContent = Number.isNaN(total)
			? ('Showing ' + first + '\u2013' + last)
			: ('Showing ' + first + '\u2013' + last + ' of ' + total);
		footer.appendChild(summary);
		var topPager = form.querySelector('table.table-fiche-title div.pagination');
		if (topPager) {
			/* List/Kanban are page modes, not result navigation. Move the original
			   href-bearing anchors to the page-action toolbar and leave only paging
			   controls in the footer. */
			var viewLinks = Array.from(topPager.querySelectorAll('a.btnTitle')).filter(function (link) {
				return Boolean(link.querySelector('.imgforviewmode'));
			});
			var pageActions = document.querySelector('.ts-pagehead-actions');
			if (viewLinks.length && pageActions) {
				var viewSwitch = document.createElement('nav');
				viewSwitch.className = 'ts-view-switch';
				viewSwitch.setAttribute('aria-label', 'View');
				viewLinks.forEach(function (link) {
					var oldParent = link.parentElement;
					link.classList.add('ts-view-switch-option');
					if (link.classList.contains('btnTitleSelected')) { link.setAttribute('aria-current', 'page'); }
					viewSwitch.appendChild(link);
					if (oldParent && oldParent.tagName === 'LI' && !oldParent.children.length && !(oldParent.textContent || '').trim()) { oldParent.remove(); }
				});
				pageActions.insertBefore(viewSwitch, pageActions.firstChild);
			}

			var pagerList = topPager.querySelector('ul');
			if (pagerList) {
				/* Dolibarr prints '/' as a bare text node between current and last. */
				Array.from(pagerList.childNodes).forEach(function (node) {
					if (node.nodeType === Node.TEXT_NODE && !(node.textContent || '').replace('/', '').trim()) { node.remove(); }
				});
				var limitItem = pagerList.querySelector('.paginationcombolimit');
				var currentPage = pagerList.querySelector('li.pageplusone');
				var previous = pagerList.querySelector('.paginationpageleft, .paginationprevious, .paginationleft');
				var next = pagerList.querySelector('.paginationpageright, .paginationnext, .paginationright');
				var makeDisabledDirection = function (direction) {
					var item = document.createElement('li');
					item.className = 'pagination paginationpage ts-pagination-disabled ts-pagination-' + direction;
					var label = document.createElement('span');
					label.className = 'inactive';
					label.setAttribute('aria-disabled', 'true');
					label.setAttribute('aria-label', direction === 'previous' ? 'Previous page unavailable' : 'Next page unavailable');
					item.appendChild(label);
					return item;
				};
				if (currentPage && !previous) {
					previous = makeDisabledDirection('previous');
					pagerList.insertBefore(previous, currentPage);
				}
				if (currentPage && !next) {
					next = makeDisabledDirection('next');
					pagerList.appendChild(next);
				}
				if (previous) { previous.classList.add('ts-pagination-previous'); }
				if (next) { next.classList.add('ts-pagination-next'); }

				var currentNumber = currentPage ? parseInt(currentPage.querySelector('input') && currentPage.querySelector('input').value, 10) : current;
				var totalPages = total > 0 && limit > 0 ? Math.max(1, Math.ceil(total / limit)) : currentNumber;
				var numericItems = Array.from(pagerList.children).filter(function (item) {
					return item !== previous && item !== next && item !== currentPage && /^\d+$/.test((item.textContent || '').trim());
				});
				numericItems.forEach(function (item) {
					if (parseInt((item.textContent || '').trim(), 10) === currentNumber) { item.remove(); }
				});
				var hasPageNumber = function (number) {
					return Array.from(pagerList.children).some(function (item) {
						return item !== currentPage && parseInt((item.textContent || '').trim(), 10) === number;
					});
				};
				var makePageNumber = function (number, directionItem) {
					var source = directionItem && directionItem.querySelector('a[href]');
					if (!source) { return null; }
					var item = document.createElement('li');
					item.className = 'pagination ts-pager-number';
					var link = source.cloneNode(false);
					link.classList.remove('paginationprevious', 'paginationnext');
					link.removeAttribute('title');
					link.setAttribute('aria-label', 'Page ' + number);
					link.textContent = String(number);
					item.appendChild(link);
					return item;
				};
				if (currentNumber > 1 && !hasPageNumber(currentNumber - 1)) {
					var previousNumber = makePageNumber(currentNumber - 1, previous);
					if (previousNumber) { pagerList.insertBefore(previousNumber, currentPage); }
				}
				if (currentNumber < totalPages && !hasPageNumber(currentNumber + 1)) {
					var nextNumber = makePageNumber(currentNumber + 1, next);
					if (nextNumber) { pagerList.insertBefore(nextNumber, next); }
				}
				var pageItems = Array.from(pagerList.children).filter(function (item) {
					return item !== limitItem && item !== previous && item !== next;
				}).sort(function (a, b) {
					var aValue = a === currentPage ? currentNumber : parseInt((a.textContent || '').trim(), 10);
					var bValue = b === currentPage ? currentNumber : parseInt((b.textContent || '').trim(), 10);
					return aValue - bValue;
				});
				if (previous) { pagerList.appendChild(previous); }
				pageItems.forEach(function (item) { pagerList.appendChild(item); });
				if (next) { pagerList.appendChild(next); }
				pagerList.classList.add('ts-pager-group');
				if (!limitItem) { limitItem = topPager.querySelector('.paginationcombolimit'); }
				if (limitItem && !limitItem.querySelector('.ts-per-page-label')) {
					var perPage = document.createElement('span');
					perPage.className = 'ts-per-page-label';
					perPage.textContent = 'per page';
					limitItem.appendChild(perPage);
				}
				if (limitItem && limitSelect) {
					var syncPageSizeDropdown = function () {
						window.requestAnimationFrame(function () {
							var selection = limitItem.querySelector('.select2-selection[aria-expanded="true"]');
							var dropdown = selection && document.querySelector('.select2-container--open .select2-dropdown');
							if (!dropdown) { return; }
							dropdown.classList.add('ts-page-size-dropdown', 'ts-compact-select2-dropdown');
							dropdown.querySelectorAll('.select2-results__option').forEach(function (option, index) {
								if (index >= 6) { option.remove(); }
							});
							var width = limitItem.getBoundingClientRect().width;
							dropdown.style.setProperty('width', width + 'px', 'important');
							dropdown.style.setProperty('min-width', width + 'px');
							/* Select2 positions its body-mounted wrapper before the compact
							   option styles settle. Anchor the final-sized popover to the live
							   trigger instead of retaining that stale vertical calculation. */
							var portal = dropdown.closest('.select2-container--open');
							if (portal && portal.parentElement === document.body) {
								var triggerRect = limitItem.getBoundingClientRect();
								var dropdownHeight = dropdown.getBoundingClientRect().height;
								var popoverGap = 8;
								var openBelow = window.innerHeight - triggerRect.bottom >= dropdownHeight + popoverGap;
								var top = openBelow
									? triggerRect.bottom + popoverGap
									: triggerRect.top - dropdownHeight - popoverGap;
								var left = Math.min(
									Math.max(popoverGap, triggerRect.left),
									window.innerWidth - width - popoverGap
								);
								portal.style.top = (top + window.scrollY) + 'px';
								portal.style.left = (left + window.scrollX) + 'px';
							}
						});
					};
					var pageSizeObserver = new MutationObserver(syncPageSizeDropdown);
					pageSizeObserver.observe(document.body, {childList: true, subtree: true});
				}
				if (limitItem) {
					var pageSizeGroup = document.createElement('ul');
					pageSizeGroup.className = 'ts-page-size-group';
					topPager.insertBefore(pageSizeGroup, pagerList);
					pageSizeGroup.appendChild(limitItem);
				}
			}
			var nav = document.createElement('nav');
			nav.className = 'ts-results-nav';
			nav.setAttribute('aria-label', 'Results pages');
			nav.appendChild(topPager);
			footer.appendChild(nav);
		}
		card.appendChild(footer);
		return true;
	}

	/* Enum vs relational selects.
	   A status or a business-entity type is a short fixed list and does not need
	   the full width of the form, a search box, or a tall panel. A parent company
	   or a tag list is a lookup and does need them. Nothing here is keyed to a
	   field name: the option count is what separates the two, so any module's
	   selects are classified the same way. Selects that load remotely report no
	   options and are treated as lookups, which is the safe direction. */
	function classifySelects() {
		var form = document.querySelector('form.ts-modern-form');
		if (!form) { return false; }
		var COMPACT_MAX = 25;
		form.querySelectorAll('select').forEach(function (sel) {
			if (sel.multiple) { return; }
			if (sel.classList.contains('ts-enum') || sel.classList.contains('ts-lookup')) { return; }
			var n = sel.options ? sel.options.length : 0;
			var ajax = sel.getAttribute('data-ajax-url') || sel.className.indexOf('ajax') >= 0;
			var compact = !ajax && n > 0 && n <= COMPACT_MAX;
			sel.classList.add(compact ? 'ts-enum' : 'ts-lookup');
			/* select2 renders its own control next to the original, so the class has
			   to reach that too for the CSS to see it. */
			/* select2 does not always leave its container as the next sibling -- the
			   form enhancement can wrap or reorder it -- so it is looked up within the
			   field's own cell, which is where it has to be either way. */
			var scope = sel.closest('td') || sel.parentElement;
			var container = scope ? scope.querySelector('.select2-container, .select2') : null;
			if (container && container.classList) {
				container.classList.add(compact ? 'ts-enum-c' : 'ts-lookup-c');
			}
		});
		return true;
	}

	/* Home dashboard composition. This is deliberately route and structure gated:
	   dashboard widgets reuse generic .box/.info-box classes throughout Dolibarr,
	   so no other module should inherit the dashboard grid or compact card layout. */
	function polishDashboard() {
		var summary = document.querySelector('.opened-dash-board-wrap > .box-flex-container');
		var fiche = summary && summary.closest('.fiche');
		/* The same dashboard is served both as /index.php and as the directory
		   index at /, and only the first form matched -- so entering Dolibarr by
		   its bare URL gave the unstyled page. */
		if (!summary || !fiche || !/(^|\/)(index\.php)?$/.test(window.location.pathname)) { return false; }
		if (document.body.classList.contains('ts-command-dashboard')) { return true; }
		document.body.classList.add('ts-command-dashboard');
		summary.classList.add('ts-dashboard-summary-grid');

		var accentFor = function (icon) {
			var classes = icon ? icon.className : '';
			if (/order_supplier|supplier_order|proposal_supplier/.test(classes)) { return 'rose'; }
			if (/expensereport|supplier_proposal|handshake/.test(classes)) { return 'orange'; }
			if (/commande|ticket|holiday|umbrella/.test(classes)) { return 'green'; }
			if (/facture|invoice/.test(classes)) { return 'cyan'; }
			if (/project|contract|contrat|bank|building-columns/.test(classes)) { return 'blue'; }
			return 'violet';
		};
		summary.querySelectorAll(':scope > .box-flex-item').forEach(function (item) {
			var box = item.querySelector('.info-box');
			if (!box) { return; }
			item.classList.add('ts-dashboard-summary-item');
			box.classList.add('ts-dashboard-summary-card');
			box.classList.add('ts-dashboard-accent-' + accentFor(box.querySelector('.info-box-icon i, .info-box-icon .fa, .info-box-icon .fas')));
		});

		var nativeTitle = fiche.querySelector('table.titleforhome');
		if (nativeTitle) { nativeTitle.classList.add('ts-dashboard-native-title'); }
		if (!fiche.querySelector('.ts-dashboard-pagehead')) {
			var head = document.createElement('header');
			head.className = 'ts-dashboard-pagehead';
			var copy = document.createElement('div');
			copy.className = 'ts-dashboard-pagehead-copy';
			var title = document.createElement('h1');
			title.textContent = 'Home';
			var subtitle = document.createElement('p');
			var userNode = document.querySelector('.atoploginusername, #topmenu-login-dropdown .dropdown-toggle');
			var userName = userNode && (userNode.textContent || '').trim();
			subtitle.textContent = 'Welcome back, ' + (userName || 'admin') + "! Here's what's happening with your business.";
			copy.appendChild(title);
			copy.appendChild(subtitle);
			head.appendChild(copy);

			var customize = document.createElement('button');
			customize.type = 'button';
			customize.className = 'ts-dashboard-customize';
			customize.setAttribute('aria-pressed', 'false');
			customize.innerHTML = '<span class="fas fa-cog" aria-hidden="true"></span><span>Customize</span>';
			customize.addEventListener('click', function () {
				var active = document.body.classList.toggle('ts-dashboard-customizing');
				customize.setAttribute('aria-pressed', active ? 'true' : 'false');
				var combo = document.getElementById('boxcombo');
				if (combo) {
					combo.style.display = active ? '' : 'none';
					if (active) { combo.focus(); }
				}
			});
			head.appendChild(customize);
			fiche.insertBefore(head, nativeTitle || fiche.firstChild);
		}

		var left = document.getElementById('boxhalfleft');
		var right = document.getElementById('boxhalfright');
		if (left && right && left.parentElement === right.parentElement) {
			var lower = document.createElement('section');
			lower.className = 'ts-dashboard-lower-grid';
			left.parentElement.insertBefore(lower, left);
			lower.appendChild(left);
			lower.appendChild(right);
			[left, right].forEach(function (column) {
				column.querySelectorAll(':scope > .box').forEach(function (widget) {
					widget.classList.add('ts-dashboard-widget');
					var heading = widget.querySelector('.box_titre');
					var label = heading ? (heading.textContent || '').trim() : '';
					if (/customer invoices/i.test(label)) { widget.classList.add('ts-dashboard-invoices'); }
					if (/prospects/i.test(label)) { widget.classList.add('ts-dashboard-prospects'); }
				});
			});
		}
		return true;
	}

	/* Third Parties module landing dashboard. The route guard is intentionally
	   exact because these legacy two-column classes are shared by many modules.
	   Existing chart/table/link nodes are moved into the COMMAND composition; only
	   headings and data-derived summary tiles are new presentation. */
	function polishThirdPartyDashboard() {
		var params = new URLSearchParams(window.location.search);
		if (!/\/societe\/(index\.php)?$/.test(window.location.pathname)) { return false; }
		var canvas = document.getElementById('canvas_idgraphthirdparties');
		var center = document.querySelector('.fichecenter.fichecenterbis');
		var left = center && center.querySelector('#boxhalfleft');
		var right = center && center.querySelector('#boxhalfright');
		if (!canvas || !left || !right || document.body.classList.contains('ts-thirdparty-dashboard')) { return false; }
		document.body.classList.add('ts-thirdparty-dashboard');

		var pageHead = document.querySelector('.ts-pagehead');
		if (pageHead) {
			var titleHost = pageHead.querySelector('.ts-pagehead-title');
			if (titleHost && !titleHost.querySelector('.ts-module-subtitle')) {
				var subtitle = document.createElement('p');
				subtitle.className = 'ts-module-subtitle';
				subtitle.textContent = 'Overview of contacts, third parties, and recent activity.';
				titleHost.appendChild(subtitle);
			}
			var actions = pageHead.querySelector('.ts-pagehead-actions');
			var contactSource = Array.from(document.querySelectorAll('a[href]')).find(function (link) {
				var href = link.getAttribute('href') || '';
				return /\/contact\/list\.php/.test(href) && !/[?&]type=/.test(href);
			});
			if (actions && contactSource && !actions.querySelector('.ts-view-all-contacts')) {
				var contacts = contactSource.cloneNode(false);
				contacts.className = 'ts-view-all-contacts';
				contacts.removeAttribute('title');
				contacts.innerHTML = '<span class="fas fa-user-friends" aria-hidden="true"></span><span>View all contacts</span>';
				actions.appendChild(contacts);
			}
		}

		center.classList.add('ts-thirdparty-dashboard-grid');
		var styleCard = function (column, kind) {
			column.classList.add('ts-module-dashboard-card', 'ts-module-dashboard-' + kind);
			var responsive = column.querySelector('.div-table-responsive-no-min, .div-table-responsive');
			var table = responsive && responsive.querySelector('table');
			if (!responsive || !table) { return null; }
			responsive.classList.add('ts-module-dashboard-body');
			table.classList.add('ts-module-dashboard-table');
			return {wrap: responsive, table: table};
		};
		var stats = styleCard(left, 'stats');
		var recent = styleCard(right, 'recent');
		if (!stats || !recent) { return false; }

		var makeHeader = function (title, iconClass) {
			var header = document.createElement('header');
			header.className = 'ts-module-card-header';
			var icon = document.createElement('span');
			icon.className = 'ts-module-card-icon fas ' + iconClass;
			icon.setAttribute('aria-hidden', 'true');
			var heading = document.createElement('h2');
			heading.textContent = title;
			header.appendChild(icon);
			header.appendChild(heading);
			return header;
		};

		var statsTitleRow = stats.table.querySelector('tr.liste_titre');
		if (statsTitleRow) { statsTitleRow.remove(); }
		var statsHeader = makeHeader('Statistics', 'fa-chart-bar');
		left.insertBefore(statsHeader, stats.wrap);
		var chartCell = canvas.closest('td');
		var chartRow = chartCell && chartCell.closest('tr');
		if (chartRow) { chartRow.classList.add('ts-statistics-chart-row'); }
		var totalRow = stats.table.querySelector('tr.liste_total');
		if (totalRow) {
			totalRow.classList.add('ts-statistics-total-row');
			var totalLabel = totalRow.cells[0];
			if (totalLabel && !totalLabel.querySelector('.ts-total-icon')) {
				var totalIcon = document.createElement('span');
				totalIcon.className = 'ts-total-icon fas fa-building';
				totalIcon.setAttribute('aria-hidden', 'true');
				totalLabel.insertBefore(totalIcon, totalLabel.firstChild);
			}
		}

		var chart = window.chart;
		var labels = chart && chart.data && chart.data.labels || [];
		var values = chart && chart.data && chart.data.datasets && chart.data.datasets[0] && chart.data.datasets[0].data || [];
		if (labels.length && values.length && !left.querySelector('.ts-stat-mini-grid')) {
			var miniGrid = document.createElement('div');
			miniGrid.className = 'ts-stat-mini-grid';
			var iconClasses = ['fa-user', 'fa-user-friends', 'fa-people-carry', 'fa-users'];
			labels.forEach(function (label, index) {
				var tile = document.createElement('div');
				tile.className = 'ts-stat-mini ts-stat-mini-' + (index + 1);
				var icon = document.createElement('span');
				icon.className = 'ts-stat-mini-icon fas ' + (iconClasses[index] || 'fa-users');
				icon.setAttribute('aria-hidden', 'true');
				var copy = document.createElement('span');
				copy.className = 'ts-stat-mini-copy';
				var name = document.createElement('span');
				name.className = 'ts-stat-mini-label';
				name.textContent = label;
				var value = document.createElement('strong');
				value.textContent = String(values[index] == null ? 0 : values[index]);
				copy.appendChild(name);
				copy.appendChild(value);
				tile.appendChild(icon);
				tile.appendChild(copy);
				miniGrid.appendChild(tile);
			});
			left.appendChild(miniGrid);
		}
		var graph = canvas.closest('.dolgraph');
		if (graph) { graph.classList.add('ts-thirdparty-donut'); }
		if (chart) {
			var legendOptions = chart.options.plugins && chart.options.plugins.legend || chart.options.legend;
			if (legendOptions) {
				legendOptions.display = false;
			}
			chart.resize();
			chart.update();
		}
		if (graph && chartCell && labels.length && !chartCell.querySelector('.ts-chart-composition')) {
			var composition = document.createElement('div');
			composition.className = 'ts-chart-composition';
			graph.parentNode.insertBefore(composition, graph);
			composition.appendChild(graph);
			var legend = document.createElement('div');
			legend.className = 'ts-chart-legend';
			var dataset = chart && chart.data.datasets[0];
			var colors = dataset && dataset.backgroundColor || chart && chart.options.elements && chart.options.elements.arc.backgroundColor || [];
			labels.forEach(function (label, index) {
				var item = document.createElement('div');
				item.className = 'ts-chart-legend-item';
				var dot = document.createElement('span');
				dot.className = 'ts-chart-legend-dot';
				if (colors[index]) { dot.style.backgroundColor = colors[index]; }
				var name = document.createElement('span');
				name.className = 'ts-chart-legend-name';
				name.textContent = label;
				var count = document.createElement('strong');
				count.textContent = String(values[index] == null ? 0 : values[index]);
				item.appendChild(dot); item.appendChild(name); item.appendChild(count); legend.appendChild(item);
			});
			composition.appendChild(legend);
			if (chart) { chart.resize(); chart.update(); }
		}

		var recentTitleRow = recent.table.querySelector('tr.liste_titre');
		var listLink = recentTitleRow && recentTitleRow.querySelector('a[href]');
		var recentHeader = makeHeader('Latest modified third parties', 'fa-clock');
		if (listLink) {
			listLink.className = 'ts-module-card-link';
			listLink.removeAttribute('title');
			listLink.textContent = 'View all';
			recentHeader.appendChild(listLink);
		}
		if (recentTitleRow) { recentTitleRow.remove(); }
		right.insertBefore(recentHeader, recent.wrap);

		var body = recent.table.tBodies[0];
		if (body && !body.querySelector('.ts-recent-columns')) {
			var headings = document.createElement('tr');
			headings.className = 'ts-recent-columns';
			['Third party', 'Type', 'Last modified', 'Status'].forEach(function (label) {
				var th = document.createElement('th'); th.textContent = label; headings.appendChild(th);
			});
			body.insertBefore(headings, body.firstChild);
		}
		recent.table.querySelectorAll('tr.oddeven').forEach(function (row) {
			row.classList.add('ts-recent-thirdparty-row');
			var nature = row.cells[1] && row.cells[1].querySelector('a');
			if (nature && nature.getAttribute('title')) { nature.textContent = nature.getAttribute('title'); }
		});
		if (listLink && !right.querySelector('.ts-module-card-footer')) {
			var footer = document.createElement('footer');
			footer.className = 'ts-module-card-footer';
			var footerLink = listLink.cloneNode(false);
			footerLink.className = 'ts-module-footer-link';
			footerLink.textContent = 'View all third parties';
			footer.appendChild(footerLink);
			right.appendChild(footer);
		}
		center.querySelectorAll(':scope > .twocolumns > br, #boxhalfleft > br, #boxhalfright > br').forEach(function (node) { node.remove(); });
		return true;
	}

	/* Partnership create/edit form. Re-parent the four native field rows and the
	   original submit/cancel controls into a compact form card. Field names,
	   hidden token/action values, Select2 instances and datepicker inputs are never
	   recreated, so server validation and widget behaviour remain authoritative. */
	function polishPartnershipForm() {
		if (!/\/partnership\/partnership_card\.php$/.test(window.location.pathname)) { return false; }
		var table = document.querySelector('form table.tableforfieldcreate');
		var form = table && table.closest('form');
		var nativeTitle = document.querySelector('.fiche > table.table-fiche-title');
		if (!form || !table || !nativeTitle || document.body.classList.contains('ts-partnership-form-page')) { return false; }
		document.body.classList.add('ts-partnership-form-page');
		form.classList.add('ts-partnership-form');
		var fiche = form.closest('.fiche');
		var titleNode = nativeTitle.querySelector('.titre');
		var titleText = (titleNode && titleNode.textContent || 'Partnership').replace(/\s+/g, ' ').trim();
		var isCreate = /new|create/i.test(titleText) || form.querySelector('input[name="action"][value="add"]');

		var crumb = document.createElement('nav');
		crumb.className = 'ts-partnership-breadcrumb';
		crumb.setAttribute('aria-label', 'Breadcrumb');
		var sourceLinks = Array.from(document.querySelectorAll('.cmd-nav a[href]'));
		var thirdPartySource = sourceLinks.find(function (a) { return /^Third parties$/i.test((a.textContent || '').trim()); });
		var partnershipSource = sourceLinks.find(function (a) { return /^Partnership$/i.test((a.textContent || '').trim()); });
		var addCrumbLink = function (source, fallbackText, fallbackHref) {
			var link = document.createElement('a');
			link.textContent = source ? (source.textContent || '').trim() : fallbackText;
			link.href = source && source.getAttribute('href') || fallbackHref;
			crumb.appendChild(link);
			var sep = document.createElement('span'); sep.textContent = '›'; sep.setAttribute('aria-hidden', 'true'); crumb.appendChild(sep);
		};
		addCrumbLink(thirdPartySource, 'Third parties', '/societe/index.php?leftmenu=thirdparties');
		addCrumbLink(partnershipSource, 'Partnership', '/partnership/partnership_list.php?leftmenu=partnership');
		var current = document.createElement('span'); current.textContent = titleText; current.setAttribute('aria-current', 'page'); crumb.appendChild(current);

		var pageTitle = document.createElement('h1');
		pageTitle.className = 'ts-partnership-title';
		if (titleNode) { pageTitle.textContent = titleText; } else { pageTitle.textContent = isCreate ? 'New Partnership' : 'Partnership'; }
		var info = document.createElement('div');
		info.className = 'ts-partnership-info';
		info.setAttribute('role', 'note');
		info.innerHTML = '<span class="fas fa-info-circle" aria-hidden="true"></span><span>' + (isCreate
			? 'Create a new partnership to link your organization with another third party for collaboration, distribution or business relationships.'
			: 'Review and update the partnership details for this business relationship.') + '</span>';
		fiche.insertBefore(crumb, nativeTitle);
		fiche.insertBefore(pageTitle, nativeTitle);
		fiche.insertBefore(info, nativeTitle);
		nativeTitle.classList.add('ts-partnership-native-title');

		var tabBar = table.closest('.tabBar');
		tabBar.classList.add('ts-partnership-card');
		var cardTitle = document.createElement('h2');
		cardTitle.className = 'ts-partnership-card-title';
		cardTitle.textContent = 'Partnership details';
		tabBar.insertBefore(cardTitle, table);
		var grid = document.createElement('div');
		grid.className = 'ts-partnership-field-grid';
		Array.from(table.querySelectorAll('tbody > tr')).forEach(function (row) {
			var labelCell = row.cells[0];
			var valueCell = row.cells[1];
			if (!labelCell || !valueCell) { return; }
			var field = document.createElement('div');
			field.className = 'ts-partnership-field ' + row.className;
			var label = document.createElement('div');
			label.className = 'ts-partnership-label';
			while (labelCell.firstChild) { label.appendChild(labelCell.firstChild); }
			var control = document.createElement('div');
			control.className = 'ts-partnership-control';
			while (valueCell.firstChild) { control.appendChild(valueCell.firstChild); }
			var relatedCreate = control.querySelector('a.butActionNew');
			if (relatedCreate) { relatedCreate.classList.add('ts-partnership-related-create'); label.appendChild(relatedCreate); }
			if (row.matches('.field_fk_type,.field_fk_soc,.field_date_partnership_start')) { field.classList.add('ts-partnership-required'); }
			if (row.classList.contains('field_date_partnership_start') || row.classList.contains('field_date_partnership_end')) {
				field.classList.add('ts-partnership-date-field');
				var help = document.createElement('p');
				help.className = 'ts-partnership-help';
				help.textContent = row.classList.contains('field_date_partnership_start')
					? 'The date the partnership becomes effective.' : 'Optional end date for the partnership.';
				field.appendChild(label); field.appendChild(control); field.appendChild(help);
				var now = control.querySelector('.datenowlink');
				if (now) { now.textContent = 'Today'; now.classList.add('ts-partnership-today'); label.appendChild(now); }
				var dateInput = control.querySelector('input.hasDatepicker');
				if (dateInput && !dateInput.getAttribute('placeholder')) { dateInput.setAttribute('placeholder', 'mm/dd/yyyy'); }
			} else {
				field.appendChild(label); field.appendChild(control);
			}
			grid.appendChild(field);
		});
		table.insertAdjacentElement('afterend', grid);
		table.classList.add('ts-partnership-source-table');

		var type = form.querySelector('#fk_type');
		var thirdParty = form.querySelector('#fk_soc');
		var setPlaceholder = function (select, text) {
			if (!select) { return; }
			var empty = Array.from(select.options).find(function (option) { return option.value === '-1' || !(option.textContent || '').trim(); });
			if (empty) { empty.textContent = text; }
			var rendered = select.parentElement.querySelector('.select2-selection__rendered');
			if (rendered && (!select.value || select.value === '-1')) { rendered.textContent = text; rendered.setAttribute('title', text); }
		};
		setPlaceholder(type, 'Select type');
		setPlaceholder(thirdParty, 'Search and select a third party…');
		if (type) { type.setAttribute('data-ts-partnership-select', 'compact'); }
		if (thirdParty) { thirdParty.setAttribute('data-ts-partnership-select', 'searchable'); }
		if (window.jQuery) {
			window.jQuery([type, thirdParty].filter(Boolean)).on('select2:open', function (event) {
				var source = event.currentTarget;
				window.requestAnimationFrame(function () {
					var dropdown = document.querySelector('.select2-container--open .select2-dropdown');
					if (!dropdown) { return; }
					dropdown.classList.add('ts-partnership-select-dropdown');
					if (source.getAttribute('data-ts-partnership-select') === 'compact') { dropdown.classList.add('ts-partnership-compact-dropdown'); }
					dropdown.querySelectorAll('.select2-results__option').forEach(function (option) {
						if (!(option.textContent || '').replace(/\u00a0/g, ' ').trim()) { option.style.display = 'none'; }
					});
					var container = source.parentElement.querySelector('.select2-container');
					var width = container && container.getBoundingClientRect().width;
					if (width) { dropdown.style.setProperty('width', width + 'px', 'important'); }
				});
			});
		}

		var nativeActions = Array.from(form.children).find(function (node) {
			return node.classList && node.classList.contains('center') && node.querySelector('input[type="submit"]');
		});
		if (nativeActions) {
			nativeActions.classList.add('ts-partnership-actions');
			var create = nativeActions.querySelector('input[name="add"], input[name="save"]');
			var cancel = nativeActions.querySelector('input[name="cancel"]');
			if (create && isCreate) { create.value = 'Create partnership'; }
			if (cancel) { nativeActions.appendChild(cancel); }
			if (create) { nativeActions.appendChild(create); }
			tabBar.appendChild(nativeActions);
		}
		return true;
	}


	/* ==========================================================================
	   Display > Skin and colors (admin/ihm.php?mode=template)

	   Dolibarr renders this whole screen as one table: a skin picker, a handful
	   of general preferences and fourteen colour fields, each as a label/control
	   row. At page width that leaves narrow wrapped labels beside mostly empty
	   control cells.

	   Every native control is MOVED, never rebuilt, so field names, values,
	   jPicker bindings, the AJAX on/off widgets and the submitting form all stay
	   exactly as Dolibarr produced them. Only composition changes.
	   ========================================================================== */
	/* jPicker centres its popup on the trigger, which covers the very swatch the
	   colour is being chosen for, and it only closes via its own two buttons.
	   Place it under the field instead and dismiss it on outside click or
	   Escape. The plugin's own open/close is reused -- clicking its trigger
	   toggles -- so no picker state is managed here. */
	function tameColorPickers() {
		var visibleContainer = function () {
			return Array.prototype.slice.call(document.querySelectorAll('.jPicker.Container')).filter(function (container) {
				var rect = container.getBoundingClientRect();
				return rect.width > 0 && rect.height > 0;
			})[0] || null;
		};
		var openTrigger = null;
		var place = function (trigger) {
			var container = visibleContainer();
			if (!container || !trigger) { return false; }
			var box = container.getBoundingClientRect();
			var width = box.width;
			var height = box.height;
			/* Measured, not offsetWidth: called too early the popup has no layout
			   yet, the clamp below becomes a no-op and the panel opens off-screen.
			   Returning false lets the caller retry until it has a size. */
			if (!width || !height) { return false; }
			var rect = trigger.getBoundingClientRect();
			var viewportW = document.documentElement.clientWidth;
			var viewportH = window.innerHeight;
			var left = Math.max(12, Math.min(rect.left, viewportW - width - 12));
			var top = rect.bottom + 8;
			if (top + height > viewportH) {
				/* Prefer above the field; if it does not fit there either, sit it
				   against the bottom edge rather than running past it. */
				top = rect.top - height - 8 > 0 ? rect.top - height - 8 : Math.max(12, viewportH - height - 12);
			}
			container.style.left = (left + window.pageXOffset) + 'px';
			container.style.top = (top + window.pageYOffset) + 'px';
			return true;
		};
		document.addEventListener('click', function (event) {
			var trigger = event.target && event.target.closest ? event.target.closest('.ts-color-control span.jPicker') : null;
			if (!trigger) { return; }
			openTrigger = trigger;
			/* jPicker lays the popup out on its own schedule, so keep trying until
			   it has a size to clamp against. */
			var attempts = 0;
			var settle = window.setInterval(function () {
				attempts += 1;
				if (place(trigger) || attempts > 25) { window.clearInterval(settle); }
			}, 20);
			window.requestAnimationFrame(function () { place(trigger); });
		});
		var dismiss = function () {
			var container = visibleContainer();
			if (!container) { return; }
			/* Close through jPicker's own Cancel control. Clicking the trigger again
			   would toggle, but the synthetic click re-enters the open handler below
			   and the popup simply reopens. Cancel also leaves the stored colour
			   alone, which is what dismissing without choosing should do. */
			var cancel = Array.prototype.slice.call(container.querySelectorAll('input[type="button"], button, a'))
				.filter(function (control) {
					var label = (control.value || control.textContent || '').trim().toLowerCase();
					return label === 'cancel';
				})[0];
			if (cancel) { cancel.click(); }
			openTrigger = null;
		};
		document.addEventListener('mousedown', function (event) {
			var container = visibleContainer();
			if (!container || container.contains(event.target)) { return; }
			if (openTrigger && openTrigger.contains(event.target)) { return; }
			dismiss();
		});
		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape') { dismiss(); }
		});
	}

	function composeDisplaySettings() {
		if (!/\/admin\/ihm\.php$/.test(window.location.pathname)) { return false; }
		if (document.body.classList.contains('ts-display-settings')) { return false; }
		var table = document.querySelector('table.editmodeforshowskin');
		if (!table || !table.rows.length) { return false; }
		var rows = Array.prototype.slice.call(table.rows);
		if (rows.length < 3) { return false; }

		var host = table.parentNode;
		if (!host) { return false; }
		document.body.classList.add('ts-display-settings');

		var moveInto = function (target, cell) {
			if (!cell) { return; }
			Array.prototype.slice.call(cell.childNodes).forEach(function (node) {
				target.appendChild(node);
			});
		};

		/* A row is a colour field when its control cell owns one of Dolibarr's
		   jPicker-bound inputs. Detect the input rather than the row index, so a
		   Dolibarr release that adds or drops a colour keeps working. */
		var colorInputOf = function (row) {
			return row.cells[1] ? row.cells[1].querySelector('input[id^="colorpicker"]') : null;
		};

		var shell = document.createElement('div');
		shell.className = 'ts-settings-shell';
		table.insertAdjacentElement('beforebegin', shell);

		/* ---- card 1: skin picker + general preferences ---- */
		var skinCard = document.createElement('section');
		skinCard.className = 'ts-settings-card';
		shell.appendChild(skinCard);

		var head = document.createElement('div');
		head.className = 'ts-settings-card-head';
		var headTitle = document.createElement('h2');
		headTitle.className = 'ts-settings-card-title';
		moveInto(headTitle, rows[0].cells[0]);
		head.appendChild(headTitle);
		if (rows[0].cells[1]) {
			var headAside = document.createElement('div');
			headAside.className = 'ts-settings-card-aside';
			moveInto(headAside, rows[0].cells[1]);
			head.appendChild(headAside);
		}
		skinCard.appendChild(head);

		var themeGrid = document.createElement('div');
		themeGrid.className = 'ts-theme-grid';
		skinCard.appendChild(themeGrid);
		Array.prototype.slice.call(rows[1].querySelectorAll('div.inline-block')).forEach(function (card) {
			card.classList.add('ts-theme-card');
			/* Dolibarr spaces these with inline margins that fight the grid. */
			card.style.margin = '';
			card.style.marginTop = '';
			card.style.marginBottom = '';
			card.style.marginLeft = '';
			card.style.marginRight = '';
			var radio = card.querySelector('input[type="radio"]');
			if (radio && radio.checked) { card.classList.add('ts-theme-card-selected'); }
			themeGrid.appendChild(card);
		});
		/* Reflect the choice immediately; the radio still submits it. */
		themeGrid.addEventListener('change', function (event) {
			if (!event.target || event.target.type !== 'radio') { return; }
			themeGrid.querySelectorAll('.ts-theme-card').forEach(function (card) {
				var radio = card.querySelector('input[type="radio"]');
				card.classList.toggle('ts-theme-card-selected', Boolean(radio && radio.checked));
			});
		});

		var generalRows = rows.slice(2).filter(function (row) {
			return row.cells.length > 1 && !colorInputOf(row);
		});
		var colorRows = rows.slice(2).filter(function (row) { return colorInputOf(row); });

		if (generalRows.length) {
			var generalGrid = document.createElement('div');
			generalGrid.className = 'ts-settings-grid';
			skinCard.appendChild(generalGrid);
			generalRows.forEach(function (row) {
				var field = document.createElement('div');
				field.className = 'ts-setting';
				var label = document.createElement('div');
				label.className = 'ts-setting-label';
				moveInto(label, row.cells[0]);
				var control = document.createElement('div');
				control.className = 'ts-setting-control';
				moveInto(control, row.cells[1]);
				field.appendChild(label);
				field.appendChild(control);
				generalGrid.appendChild(field);
			});
		}

		/* ---- card 2: colour fields ---- */
		if (colorRows.length) {
			var colorCard = document.createElement('section');
			colorCard.className = 'ts-settings-card';
			shell.appendChild(colorCard);
			var colorHead = document.createElement('div');
			colorHead.className = 'ts-settings-card-head';
			var colorTitle = document.createElement('h2');
			colorTitle.className = 'ts-settings-card-title';
			colorTitle.textContent = 'Color settings';
			colorHead.appendChild(colorTitle);
			colorCard.appendChild(colorHead);

			var colorGrid = document.createElement('div');
			colorGrid.className = 'ts-color-grid';
			colorCard.appendChild(colorGrid);

			colorRows.forEach(function (row) {
				var item = document.createElement('div');
				item.className = 'ts-color-item';
				var label = document.createElement('div');
				label.className = 'ts-color-label';
				moveInto(label, row.cells[0]);
				var control = document.createElement('div');
				control.className = 'ts-color-control';
				moveInto(control, row.cells[1]);
				item.appendChild(label);
				item.appendChild(control);
				colorGrid.appendChild(item);

				/* Dolibarr prints the default as loose nodes after the field, with the
				   hex in its own coloured element. Collect whatever is left once the
				   input, its picker and the help icon are accounted for, and restate it
				   as one muted hint. The field itself is untouched. */
				var colorInput = control.querySelector('input[id^="colorpicker"]');
				var picker = control.querySelector('span.jPicker');
				var helpIcon = control.querySelector('.classfortooltip');
				var hintNodes = Array.prototype.slice.call(control.childNodes).filter(function (node) {
					if (node === colorInput || node === picker || node === helpIcon) { return false; }
					if (node.nodeType === 1 && (node.tagName === 'SCRIPT' || node.tagName === 'LINK')) { return false; }
					if (node.nodeType === 1 && node.contains && node.contains(colorInput)) { return false; }
					return (node.textContent || '').replace(/\s+/g, ' ').trim() !== '';
				});
				if (hintNodes.length) {
					var hint = document.createElement('span');
					hint.className = 'ts-color-default';
					var hintText = hintNodes.map(function (node) {
						return (node.textContent || '').replace(/\s+/g, ' ').trim();
					}).join(' ').replace(/\s+/g, ' ').trim();
					/* "Default : 263c5c" -> "Default: #263c5c", matching how the value is
					   written everywhere else. Presentation only; nothing is submitted. */
					hintText = hintText.replace(/\s*:\s*/, ': ').replace(/:\s*([0-9a-f]{3,8})\b/i, ': #$1');
					hint.textContent = hintText;
					hintNodes.forEach(function (node) { if (node.parentNode) { node.parentNode.removeChild(node); } });
					control.appendChild(hint);
					if (helpIcon) { control.appendChild(helpIcon); }

					/* An unset field still renders in a colour -- its default. Show that
					   in the swatch so each row reads at a glance.
					   jPicker builds its swatch markup on a later ready handler, so it does
					   not exist yet at this point -- look it up when painting, not now.
					   Paint the Icon rather than the Color: the plugin owns Color and
					   repaints it from the empty value, while Color stays transparent
					   above the Icon, so a picked colour still wins. */
					var defaultHex = (hintText.match(/#([0-9a-f]{3,8})\b/i) || [])[1];
					if (colorInput && defaultHex) {
						var paintDefault = function () {
							var icon = control.querySelector('span.jPicker span.Icon');
							if (!icon) { return false; }
							if ((colorInput.value || '').trim()) { return true; }
							icon.style.backgroundColor = '#' + defaultHex;
							return true;
						};
						if (!paintDefault()) {
							var tries = 0;
							var waitForPicker = window.setInterval(function () {
								tries += 1;
								if (paintDefault() || tries > 40) { window.clearInterval(waitForPicker); }
							}, 50);
						}
						colorInput.addEventListener('change', paintDefault);
						colorInput.addEventListener('blur', paintDefault);
					}
				}
			});

			/* A settings form has no result set, so the row counter another pass
			   added here reads as noise. */
			var resultsFooter = document.querySelector('.ts-results-footer');
			if (resultsFooter) { resultsFooter.remove(); }
		}

		table.remove();

		/* ---- action footer: mark the native buttons, do not replace them ---- */
		var submit = document.querySelector('input[type="submit"][name="modify"], input.button[type="submit"]');
		var actionHost = submit && submit.closest('div.center, .tabsAction');
		if (actionHost) {
			actionHost.classList.add('ts-settings-actions');
			actionHost.querySelectorAll('input[type="submit"], input.button, input.butAction').forEach(function (button) {
				button.classList.add('ts-settings-action');
			});
			actionHost.querySelectorAll('input.button_delete, input[name="cancel"], a.butActionDelete').forEach(function (button) {
				button.classList.add('ts-settings-action-secondary');
			});
			if (submit) { submit.classList.add('ts-settings-action-primary'); }
		}
		return true;
	}


	/* ==========================================================================
	   Third party > Customer tab (comm/card.php) -- right-hand summary column

	   Dolibarr stacks four stat links inside one boxtable cell, then three record
	   lists each wrapped in its own responsive div with the section title buried
	   in a nested table inside the list's first row. That renders as uneven stat
	   widths and three lists merged into a single slab inside an outer box.

	   Compose the four stats as a 2x2 grid and give each list its own card with a
	   real header. Every link, icon, count and row is moved, never rebuilt.
	   ========================================================================== */
	function composeCustomerSummary() {
		if (!/\/comm\/card\.php$/.test(window.location.pathname)) { return false; }
		if (document.body.classList.contains('ts-customer-summary')) { return false; }
		var right = document.querySelector('div.fichehalfright');
		if (!right) { return false; }
		var statCell = right.querySelector('td.tdboxstats');
		var lists = Array.prototype.slice.call(right.querySelectorAll('table.lastrecordtable'));
		if (!statCell && !lists.length) { return false; }
		document.body.classList.add('ts-customer-summary');

		var stack = document.createElement('div');
		stack.className = 'ts-cust-stack';
		right.appendChild(stack);

		/* ---- 2x2 stat grid ---- */
		if (statCell) {
			var grid = document.createElement('div');
			grid.className = 'ts-kpi-grid';
			stack.appendChild(grid);
			Array.prototype.slice.call(statCell.querySelectorAll('a.thumbstat')).forEach(function (link) {
				link.classList.add('ts-kpi-card');
				var body = link.querySelector('.boxstats') || link;
				var textWrap = body.querySelector('.boxstatstext');
				var icon = textWrap && textWrap.querySelector('[class*="fa-"]');
				if (icon) {
					var iconTile = document.createElement('span');
					iconTile.className = 'ts-kpi-icon';
					icon.parentNode.insertBefore(iconTile, icon);
					iconTile.appendChild(icon);
					body.insertBefore(iconTile, body.firstChild);
				}
				if (textWrap) { textWrap.classList.add('ts-kpi-label'); }
				/* The value is the indicator span that is not the link itself. */
				Array.prototype.slice.call(body.querySelectorAll('span.boxstatsindicator')).forEach(function (value) {
					value.classList.add('ts-kpi-value');
				});
				grid.appendChild(link);
			});
			var oldBox = right.querySelector('div.box.divboxtable, div.box-halfright');
			if (oldBox) { oldBox.remove(); }
		}

		/* ---- one card per record list ---- */
		lists.forEach(function (list) {
			var card = document.createElement('section');
			card.className = 'ts-latest-card';
			var titleRow = list.querySelector('tr.liste_titre');
			var titleTable = titleRow && titleRow.querySelector('table');
			if (titleTable) {
				var head = document.createElement('div');
				head.className = 'ts-latest-head';
				var cells = Array.prototype.slice.call(titleTable.rows[0].cells);
				cells.forEach(function (cell, index) {
					var part = document.createElement('div');
					part.className = index === 0 ? 'ts-latest-title' : 'ts-latest-aside';
					Array.prototype.slice.call(cell.childNodes).forEach(function (node) { part.appendChild(node); });
					head.appendChild(part);
				});
				card.appendChild(head);
				titleRow.remove();
			}
			var wrap = list.closest('div.div-table-responsive-no-min, div.div-table-responsive') || list;
			wrap.parentNode.insertBefore(card, wrap);
			card.appendChild(wrap === list ? list : wrap);
			list.classList.add('ts-latest-table');
			stack.appendChild(card);
		});
		return true;
	}


	/* ==========================================================================
	   Shared admin settings composition

	   Dolibarr builds nearly every setup screen the same way: a table whose first
	   row is a tr.liste_titre section title, followed by rows of
	   <td>label</td><td>control</td>. That is the seam this works from, so the
	   treatment generalises across admin pages instead of targeting one file.

	   Rows are moved, never rebuilt: field names, values, AJAX on/off widgets and
	   the submitting form are Dolibarr's own throughout.
	   ========================================================================== */
	function composeAdminSettings() {
		if (!/\/admin\//.test(window.location.pathname)) { return false; }
		/* The skin page has its own composition already. */
		if (document.body.classList.contains('ts-display-settings')) { return false; }
		if (document.body.classList.contains('ts-settings-page')) { return false; }

		var isSettingRow = function (row) {
			return row.cells.length >= 2 && row.querySelector('input:not([type="hidden"]), select, textarea, .linkobject');
		};
		var tables = Array.prototype.slice.call(document.querySelectorAll('form table')).filter(function (table) {
			if (table.closest('.ts-settings-card')) { return false; }
			if (!Array.prototype.slice.call(table.rows).filter(isSettingRow).length) { return false; }
			/* Several setup screens wrap their settings table in a layout table.
			   Composing the wrapper would swallow the real one, so take the
			   innermost table that actually holds setting rows. */
			var nested = Array.prototype.slice.call(table.querySelectorAll('table')).some(function (inner) {
				return Array.prototype.slice.call(inner.rows).filter(isSettingRow).length > 0;
			});
			return !nested;
		});
		if (!tables.length) { return false; }
		document.body.classList.add('ts-settings-page');

		/* Give a control a width band from what it is, rather than stretching
		   everything to the full column. */
		var classifyControl = function (control) {
			var field = control.querySelector('select, textarea, input:not([type="hidden"])');
			if (!field) { return 'ts-control-compact'; }
			if (field.tagName === 'TEXTAREA') { return 'ts-control-full'; }
			if (field.tagName === 'SELECT') {
				return field.options && field.options.length > 25 ? 'ts-control-full' : 'ts-control-medium';
			}
			var type = (field.getAttribute('type') || 'text').toLowerCase();
			if (type === 'checkbox' || type === 'radio') { return 'ts-control-compact'; }
			if (type === 'number') { return 'ts-control-compact'; }
			var size = parseInt(field.getAttribute('size') || '0', 10);
			if (size && size <= 8) { return 'ts-control-compact'; }
			if (/url|link|mail/i.test(field.name || '')) { return 'ts-control-full'; }
			return 'ts-control-wide';
		};

		tables.forEach(function (table) {
			var rows = Array.prototype.slice.call(table.rows);
			var settingRows = rows.filter(isSettingRow);
			if (!settingRows.length) { return; }

			var card = document.createElement('section');
			card.className = 'ts-settings-card';
			table.insertAdjacentElement('beforebegin', card);

			var titleRow = rows[0] && rows[0].classList.contains('liste_titre') && !isSettingRow(rows[0]) ? rows[0] : null;
			if (titleRow) {
				var head = document.createElement('div');
				head.className = 'ts-settings-card-head';
				var title = document.createElement('h2');
				title.className = 'ts-settings-card-title';
				Array.prototype.slice.call(titleRow.cells).forEach(function (cell, index) {
					var target = title;
					if (index > 0) {
						target = document.createElement('div');
						target.className = 'ts-settings-card-aside';
					}
					Array.prototype.slice.call(cell.childNodes).forEach(function (node) { target.appendChild(node); });
					if (index === 0) { head.appendChild(title); } else { head.appendChild(target); }
				});
				card.appendChild(head);
				titleRow.remove();
			}

			var grid = document.createElement('div');
			grid.className = 'ts-settings-grid ts-settings-grid-single';
			card.appendChild(grid);

			settingRows.forEach(function (row) {
				var field = document.createElement('div');
				field.className = 'ts-setting';
				var label = document.createElement('div');
				label.className = 'ts-setting-label';
				Array.prototype.slice.call(row.cells[0].childNodes).forEach(function (node) { label.appendChild(node); });
				var control = document.createElement('div');
				control.className = 'ts-setting-control';
				/* Trailing cells are usually help icons or units; keep them with the
				   control rather than letting them form phantom columns. */
				Array.prototype.slice.call(row.cells).slice(1).forEach(function (cell) {
					Array.prototype.slice.call(cell.childNodes).forEach(function (node) { control.appendChild(node); });
				});
				control.classList.add(classifyControl(control));
				field.appendChild(label);
				field.appendChild(control);
				grid.appendChild(field);
			});

			/* Anything left is layout scaffolding for rows that have all moved. */
			var remaining = Array.prototype.slice.call(table.rows).filter(function (row) {
				return (row.innerText || '').replace(/\s+/g, ' ').trim() !== '' || row.querySelector('input:not([type="hidden"]), select, textarea');
			});
			if (!remaining.length) { table.remove(); } else { card.appendChild(table); }
		});

		/* A settings form has no result set, so a row counter added by the list
		   passes reads as noise here. */
		Array.prototype.slice.call(document.querySelectorAll('.ts-results-footer')).forEach(function (footer) {
			footer.remove();
		});

		/* Action rows: mark Dolibarr's own buttons, never replace them. */
		Array.prototype.slice.call(document.querySelectorAll('div.center, .tabsAction')).forEach(function (host) {
			var submit = host.querySelector('input[type="submit"], button[type="submit"]');
			if (!submit) { return; }
			host.classList.add('ts-settings-actions');
			Array.prototype.slice.call(host.querySelectorAll('input[type="submit"], button[type="submit"], input.button, a.butAction')).forEach(function (button) {
				button.classList.add('ts-settings-action');
			});
			submit.classList.add('ts-settings-action-primary');
			Array.prototype.slice.call(host.querySelectorAll('input[name="cancel"], a.butActionDelete, input.button_delete')).forEach(function (button) {
				button.classList.add('ts-settings-action-secondary');
			});
		});
		return true;
	}

	/* Categories landing page. Its summary table carries no filter form, so the
	   shared list composition -- which exists to relocate filter controls -- has
	   nothing to do here. Mark the page and let CSS give the table a card, the
	   same treatment the record detail panels get. */
	function polishCategoryIndex() {
		if (!/\/categories\/(index\.php)?$/.test(window.location.pathname)) { return false; }
		var table = document.querySelector('table.liste');
		if (!table || !table.querySelector('tr.liste_titre')) { return false; }
		document.body.classList.add('ts-category-index');
		table.classList.add('ts-category-index-table');
		return true;
	}


	/* Page header icon.

	   Dolibarr prints a picto beside some titles and nothing beside others, so
	   headers read inconsistently. Map the area a page belongs to onto an icon
	   and add one only where the page ships none.

	   Every glyph here is FontAwesome 5, which is what Dolibarr bundles. A
	   version 6 name renders as an empty tile rather than failing loudly, so
	   additions want checking against the shipped set rather than the docs. */
	var TS_PAGE_ICONS = [
		[/^\/admin\/ihm\.php/, 'fa-desktop'],
		[/^\/admin\/(company|company_socialnetworks)\.php/, 'fa-building'],
		[/^\/admin\/(mails|emailcollector|mails_templates)/, 'fa-envelope'],
		[/^\/admin\/sms/, 'fa-sms'],
		[/^\/admin\/(security|proxy)/, 'fa-shield-alt'],
		[/^\/admin\/(pdf|document)/, 'fa-file-pdf'],
		[/^\/admin\/(menus|menu)/, 'fa-bars'],
		[/^\/admin\/(translation|languages)/, 'fa-language'],
		[/^\/admin\/(modules|const)/, 'fa-puzzle-piece'],
		[/^\/admin\//, 'fa-cog'],
		[/^\/societe\//, 'fa-building'],
		[/^\/contact\//, 'fa-address-book'],
		[/^\/product\/stock/, 'fa-warehouse'],
		[/^\/product\//, 'fa-box'],
		[/^\/projet\//, 'fa-project-diagram'],
		[/^\/comm\/propal/, 'fa-file-signature'],
		[/^\/comm\/action/, 'fa-calendar-alt'],
		[/^\/comm\//, 'fa-briefcase'],
		[/^\/commande\//, 'fa-shopping-cart'],
		[/^\/compta\/facture/, 'fa-file-invoice-dollar'],
		[/^\/compta\/bank/, 'fa-university'],
		[/^\/compta\//, 'fa-calculator'],
		[/^\/fourn\//, 'fa-truck'],
		[/^\/expedition\//, 'fa-shipping-fast'],
		[/^\/ticket\//, 'fa-life-ring'],
		[/^\/user\//, 'fa-user'],
		[/^\/adherents\//, 'fa-users'],
		[/^\/contrat\//, 'fa-file-contract'],
		[/^\/expensereport\//, 'fa-receipt'],
		[/^\/holiday\//, 'fa-umbrella-beach'],
		[/^\/hrm\//, 'fa-id-badge'],
		[/^\/categories\//, 'fa-tags'],
		[/^\/don\//, 'fa-hand-holding-heart'],
		[/^\/fichinter\//, 'fa-tools']
	];

	function applyPageHeadIcon() {
		var head = document.querySelector('.ts-pagehead .ts-pagehead-title');
		if (!head || head.querySelector('.ts-pagehead-icon')) { return false; }
		/* A page that already shows its own picto keeps it. */
		if (head.querySelector('img.pictotitle, span.pictotitle, .titre > [class*="fa-"]')) { return false; }
		var path = window.location.pathname.replace(/^.*?(\/(?:admin|societe|contact|product|projet|comm|commande|compta|fourn|expedition|ticket|user|adherents|contrat|expensereport|holiday|hrm|categories|don|fichinter)\/)/, '$1');
		var match = TS_PAGE_ICONS.filter(function (entry) { return entry[0].test(path); })[0];
		if (!match) { return false; }
		var icon = document.createElement('span');
		icon.className = 'ts-pagehead-icon fas ' + match[1];
		icon.setAttribute('aria-hidden', 'true');
		head.insertBefore(icon, head.firstChild);
		return true;
	}


	/* Kanban surface for every module.

	   The third-party adapter above also promotes that module's own filters, so
	   it is gated on third-party cards. The classes it sets for the surface
	   itself -- the card container, the table wrapper and the grid -- are not
	   module-specific, but nothing applied them anywhere else, so every other
	   module's Kanban kept Dolibarr's raw markup.

	   This runs after that adapter and only where it declined, so the third-party
	   view is untouched and every other module gets the same surface. */
	function applyKanbanSurface() {
		var grids = Array.prototype.slice.call(document.querySelectorAll('div.box-flex-container.kanban'));
		var changed = false;
		grids.forEach(function (grid) {
			if (grid.getAttribute('data-ts-kanban')) { return; }
			if (!grid.querySelector(':scope > .box-flex-item')) { return; }
			grid.setAttribute('data-ts-kanban', 'shared');
			grid.classList.add('ts-command-kanban');
			var table = grid.closest('table.liste');
			if (table) { table.classList.add('ts-kanban-table'); }
			var listCard = table && table.closest('.ts-list-card');
			if (listCard) { listCard.classList.add('ts-kanban-card-surface'); }
			changed = true;
		});
		return changed;
	}


	/* Compound measurement fields (value + unit).

	   Marks the parts so the shared pattern can size them. Nothing is moved and
	   no value, name or unit option is touched -- the marker exists because the
	   stylesheet's own full-width rules exclude inputs carrying a width marker,
	   which is how Dolibarr's dimension fields already escape them. */
	function markMeasurementFields() {
		var units = Array.prototype.slice.call(document.querySelectorAll('select[name$="_units"]'));
		if (!units.length) { return false; }
		var changed = false;
		units.forEach(function (unit) {
			var cell = unit.closest('td') || unit.parentElement;
			if (!cell || cell.classList.contains('ts-measure-cell')) { return; }
			cell.classList.add('ts-measure-cell');
			Array.prototype.slice.call(cell.querySelectorAll('input')).forEach(function (input) {
				var type = (input.getAttribute('type') || '').toLowerCase();
				if (type === 'hidden' || type === 'checkbox' || type === 'radio') { return; }
				input.classList.add('ts-measure');
				input.classList.add(/^size/.test(input.name || '') ? 'ts-measure-dim' : 'ts-measure-value');
			});
			/* The separators between length, width and height are bare text nodes;
			   wrap them so they can be aligned with the fields they sit between. */
			Array.prototype.slice.call(cell.childNodes).forEach(function (node) {
				if (node.nodeType !== 3) { return; }
				var text = (node.nodeValue || '').trim();
				if (text !== 'x' && text !== '×') { return; }
				var span = document.createElement('span');
				span.className = 'ts-measure-x';
				span.textContent = '×';
				node.parentNode.replaceChild(span, node);
			});
			changed = true;
		});
		return changed;
	}


	/* Kanban card metadata.

	   Dolibarr emits the card body as a flat run of nodes separated by <br>:
	   reference, select box, label, then one div per remaining field. Grouping
	   the trailing fields lets them be laid out as rows instead of a stack of
	   line breaks, without rebuilding anything -- each field keeps its own
	   markup, links and tooltips. */
	function composeKanbanCardMeta() {
		var grids = Array.prototype.slice.call(document.querySelectorAll('[data-ts-kanban="shared"]'));
		if (!grids.length) { return false; }
		var changed = false;
		grids.forEach(function (grid) {
			Array.prototype.slice.call(grid.querySelectorAll('.info-box-content')).forEach(function (content) {
				if (content.getAttribute('data-ts-meta') === '1') { return; }
				content.setAttribute('data-ts-meta', '1');
				var label = content.querySelector('.info-box-label');
				if (!label) { return; }
				/* Everything after the label is per-module detail. */
				var meta = document.createElement('div');
				meta.className = 'ts-kanban-meta';
				var node = label.nextSibling;
				var moved = [];
				while (node) {
					var next = node.nextSibling;
					if (node.nodeType === 1 && node.tagName === 'BR') { node.remove(); }
					else if (node.nodeType === 3 && !(node.nodeValue || '').trim()) { node.remove(); }
					else { moved.push(node); }
					node = next;
				}
				if (!moved.length) { return; }
				moved.forEach(function (item) {
					if (item.nodeType === 1) { item.classList.add('ts-kanban-meta-row'); }
					meta.appendChild(item);
				});
				content.appendChild(meta);
				changed = true;
			});
		});
		return changed;
	}


	/* Some list pages reserve the leading select column but never fill it.
	   Products (Stocks) is one: Dolibarr emits an empty <th> and no picker,
	   no checkboxes and no mass-action handler, leaving an unexplained gap
	   before the first real column.

	   Collapse that column only where it is genuinely unused -- no picker, no
	   select checkbox anywhere in the table -- so lists that do offer selection
	   keep theirs. Emptiness has to be tested here; a stylesheet cannot ask
	   whether a cell has content. */
	function collapseUnusedSelectColumn() {
		var changed = false;
		Array.prototype.slice.call(document.querySelectorAll('table.liste')).forEach(function (table) {
			if (table.getAttribute('data-ts-selectcol')) { return; }
			var header = table.querySelector('tr.liste_titre');
			if (!header || !header.cells.length) { return; }
			var first = header.cells[0];
			var hasPicker = Boolean(table.querySelector('dl.dropdown'));
			var hasCheckbox = Boolean(table.querySelector('input[name="toselect[]"], input.checkforselect'));
			var firstIsEmpty = !(first.textContent || '').trim() && !first.querySelector('input, a, dl, span[class*="fa-"]');
			table.setAttribute('data-ts-selectcol', hasPicker || hasCheckbox ? 'used' : 'unused');
			if (hasPicker || hasCheckbox || !firstIsEmpty) { return; }
			table.classList.add('ts-list-no-select-col');
			changed = true;
		});
		return changed;
	}

	ready(function () {
		try { watchAjaxTooltips(); } catch (e) { /* keep native AJAX tooltip content */ }
		try { polishDashboard(); } catch (e) { /* retain Dolibarr's native dashboard */ }
		try { normalizeThirdPartyRecordContext(); } catch (e) { /* retain the module's native record context */ }
		try { buildPageHeader(); } catch (e) { /* keep Dolibarr's header */ }
		try { applyPageHeadIcon(); } catch (e) { /* leave the header without an icon */ }
		try { polishThirdPartyDashboard(); } catch (e) { /* retain the native Third Parties module landing */ }
		try { polishPartnershipForm(); } catch (e) { /* retain the native Partnership form */ }
		try { markCount(); } catch (e) { /* leave the title as Dolibarr printed it */ }
		try { composeListSurface(); } catch (e) { /* leave the list's native structure */ }
		try { collapseUnusedSelectColumn(); } catch (e) { /* keep the native column */ }
		try { enhanceThirdPartyKanban(); } catch (e) { /* retain Dolibarr's native Kanban */ }
		try { applyKanbanSurface(); } catch (e) { /* retain the native Kanban surface */ }
		try { composeKanbanCardMeta(); } catch (e) { /* retain the native card body */ }
		try { enhanceThirdPartyForm(); } catch (e) { /* retain Dolibarr's native edit table */ }
		try { enhanceSharedFormPage(); } catch (e) { /* retain the native standard form */ }
		/* After the form is rebuilt, not before: enhanceThirdPartyForm replaces the
		   select containers, which discarded the classification when it ran first. */
		try { markMeasurementFields(); } catch (e) { /* leave measurement fields native */ }
		try { classifySelects(); } catch (e) { /* leave selects as they are */ }
		try { markEmptyTitleTables(); } catch (e) { /* keep placeholder title tables */ }
		try { normalizePageTitleIcons(); } catch (e) { /* retain Dolibarr's title layout */ }
		try { polishCategoryDialogPage(); } catch (e) { /* retain the native category dialog page */ }
		try { polishCategoryIndex(); } catch (e) { /* retain the native categories landing */ }
		try { moveActionsIntoHeader(); } catch (e) { /* leave Dolibarr's layout alone */ }
		try { groupRecordActions(); } catch (e) { /* retain the original action row */ }
		try { polishEntityHeader(); } catch (e) { /* retain the native identity layout */ }
		try { placeTabsBelowHeader(); } catch (e) { /* retain Dolibarr's tab placement */ }
		try { polishSharedRecordTabContent(); } catch (e) { /* retain the tab's native content */ }
		try { polishSharedModuleIndex(); } catch (e) { /* retain the native module index */ }
		try { polishShipmentStatistics(); } catch (e) { /* retain the native shipment statistics */ }
		try { polishSharedEmptyStates(); } catch (e) { /* retain native empty messages */ }
		try { applyThirdPartyFieldSchema(); } catch (e) { /* retain the native field layout */ }
		try { buildBreadcrumb(); } catch (e) { /* idem */ }
		try { polishRecordSections(); } catch (e) { /* retain the native section layout */ }
		try { polishThirdPartyOverview(); } catch (e) { /* retain the shared record shell */ }
		try { polishThirdPartyEvents(); } catch (e) { /* retain the native Events tab */ }
		try { composeDisplaySettings(); } catch (e) { /* retain Dolibarr native Display settings */ }
		try { composeCustomerSummary(); } catch (e) { /* retain the native Customer tab column */ }
		try { markPairedSelectCells(document); } catch (e) { /* leave the select full width */ }
		if (/\/stats\//.test(window.location.pathname)) { document.body.classList.add('ts-command-stats'); }
		/* select2 builds its container from an inline script that can run after this
		   pass, so the cells hold no container yet on the first look. Check again once
		   the page has settled. */
		window.addEventListener('load', function () {
			try { markPairedSelectCells(document); } catch (e) { /* leave the select full width */ }
			try {
				document.querySelectorAll('.ts-module-index-data-card').forEach(sizeModuleIndexColumns);
			} catch (e) { /* leave the even split */ }
			window.setTimeout(function () {
				try { markPairedSelectCells(document); } catch (e) { /* leave the select full width */ }
			}, 400);
		});
		try { composeAdminSettings(); } catch (e) { /* retain the native admin settings tables */ }
		try { tameColorPickers(); } catch (e) { /* leave jPicker's own popup behaviour */ }
		try { polishThirdPartyTabContent(); } catch (e) { /* retain the module's native tab content */ }
		try { polishThirdPartyAuxiliaryTabs(); } catch (e) { /* retain the native auxiliary tab content */ }
		try { polishThirdPartyCustomerTab(); } catch (e) { /* retain the native customer tab */ }
	});
})();
