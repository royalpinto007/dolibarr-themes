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
		parent.textContent = activeNav ? (activeNav.textContent || '').trim() : (back.textContent || '').trim();
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

	function watchAjaxTooltips() {
		structureAjaxTooltip(document);
		var observer = new MutationObserver(function (mutations) {
			if (mutations.some(function (mutation) { return mutation.addedNodes.length; })) { structureAjaxTooltip(document); }
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

			/* Promote the native name search into the shared filter surface and put
			   the remaining per-column controls behind one disclosure. Every original
			   form control is moved intact, so names, values and submission behaviour
			   remain Dolibarr's own. */
			var filterRow = list.querySelector('tr.liste_titre_filter');
			if (filterRow) {
				var headingRow = filterRow.nextElementSibling;
				var quick = document.createElement('div');
				quick.className = 'ts-quick-search';
				var searchIcon = document.createElement('span');
				searchIcon.className = 'fas fa-search';
				searchIcon.setAttribute('aria-hidden', 'true');
				quick.appendChild(searchIcon);
				var nameSearch = filterRow.querySelector('input[name="search_nom"]');
				if (nameSearch) {
					nameSearch.classList.add('ts-quick-search-input');
					nameSearch.setAttribute('placeholder', 'Search third parties…');
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
				if (nameSearch) { filter.insertBefore(quick, filter.firstChild); }
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
				Array.from(filterRow.cells).forEach(function (cell, index) {
					if (index < 2) { return; }
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
					panel.appendChild(control);
				});
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

	ready(function () {
		try { watchAjaxTooltips(); } catch (e) { /* keep native AJAX tooltip content */ }
		try { buildPageHeader(); } catch (e) { /* keep Dolibarr's header */ }
		try { markCount(); } catch (e) { /* leave the title as Dolibarr printed it */ }
		try { composeListSurface(); } catch (e) { /* leave the list's native structure */ }
		try { enhanceThirdPartyKanban(); } catch (e) { /* retain Dolibarr's native Kanban */ }
		try { enhanceThirdPartyForm(); } catch (e) { /* retain Dolibarr's native edit table */ }
		/* After the form is rebuilt, not before: enhanceThirdPartyForm replaces the
		   select containers, which discarded the classification when it ran first. */
		try { classifySelects(); } catch (e) { /* leave selects as they are */ }
		try { markEmptyTitleTables(); } catch (e) { /* keep placeholder title tables */ }
		try { polishCategoryDialogPage(); } catch (e) { /* retain the native category dialog page */ }
		try { moveActionsIntoHeader(); } catch (e) { /* leave Dolibarr's layout alone */ }
		try { groupRecordActions(); } catch (e) { /* retain the original action row */ }
		try { polishEntityHeader(); } catch (e) { /* retain the native identity layout */ }
		try { placeTabsBelowHeader(); } catch (e) { /* retain Dolibarr's tab placement */ }
		try { applyThirdPartyFieldSchema(); } catch (e) { /* retain the native field layout */ }
		try { buildBreadcrumb(); } catch (e) { /* idem */ }
		try { polishRecordSections(); } catch (e) { /* retain the native section layout */ }
	});
})();
