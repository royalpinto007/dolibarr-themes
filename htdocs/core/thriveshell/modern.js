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
					if (compactSelect && compactSelect.options.length <= 5) {
						compactSelect.setAttribute('data-ts-compact-select2', '1');
					}
					var compactEmptyLabels = {
						'status': 'All statuses',
						'nature of third party': 'All types'
					};
					var compactEmptyLabel = compactEmptyLabels[label.textContent.toLowerCase()];
					if (compactSelect && compactEmptyLabel) {
						var emptyOption = Array.from(compactSelect.options).find(function (option) {
							return option.value === '-1' && !(option.textContent || '').replace(/\u00a0/g, ' ').trim();
						});
						if (emptyOption) {
							emptyOption.textContent = compactEmptyLabel;
							compactSelect.setAttribute('data-ts-empty-label', compactEmptyLabel);
							compactSelect.addEventListener('change', function (event) {
								var changedSelect = event.currentTarget;
								window.requestAnimationFrame(function () {
									if (changedSelect.value !== '-1') { return; }
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
		/* Dolibarr repeats each visible sortable label in the parent th title.
		   That produces a redundant native tooltip and adds no accessible name. */
		list.querySelectorAll('tr.liste_titre > th[title], tr.liste_titre > td[title]').forEach(function (cell) {
			var sortLink = cell.querySelector('a.reposition');
			if (!sortLink) { return; }
			var visibleLabel = (sortLink.textContent || '').replace(/\s+/g, ' ').trim();
			var tooltipLabel = (cell.getAttribute('title') || '').replace(/\s+/g, ' ').trim();
			if (visibleLabel && visibleLabel === tooltipLabel) { cell.removeAttribute('title'); }
		});

		var totalNode = title.querySelector('.ts-count');
		var total = totalNode ? parseInt((totalNode.textContent || '').replace(/[^0-9]/g, ''), 10) : NaN;
		var limitSelect = form.querySelector('select[name="limit"], select.selectlimit');
		if (limitSelect) { limitSelect.setAttribute('data-ts-compact-select2', '1'); }
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
		summary.textContent = Number.isNaN(total)
			? ('Showing ' + first + ' to ' + last)
			: ('Showing ' + first + ' to ' + last + ' of ' + total + ' results');
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
				var currentPage = pagerList.querySelector('li.pageplusone, li.paginationpage');
				var previous = pagerList.querySelector('.paginationpageleft, .paginationprevious, .paginationleft');
				if (currentPage && !previous) {
					previous = document.createElement('li');
					previous.className = 'pagination ts-pagination-disabled';
					var previousLabel = document.createElement('span');
					previousLabel.className = 'inactive';
					previousLabel.setAttribute('aria-hidden', 'true');
					previousLabel.textContent = '‹';
					previous.appendChild(previousLabel);
					pagerList.insertBefore(previous, currentPage);
				}
				var limitItem = pagerList.querySelector('.paginationcombolimit');
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
							var width = limitItem.getBoundingClientRect().width;
							dropdown.style.setProperty('width', width + 'px', 'important');
							dropdown.style.setProperty('min-width', width + 'px');
						});
					};
					var pageSizeObserver = new MutationObserver(syncPageSizeDropdown);
					pageSizeObserver.observe(document.body, {childList: true, subtree: true});
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

	ready(function () {
		try { buildPageHeader(); } catch (e) { /* keep Dolibarr's header */ }
		try { markCount(); } catch (e) { /* leave the title as Dolibarr printed it */ }
		try { composeListSurface(); } catch (e) { /* leave the list's native structure */ }
		try { markEmptyTitleTables(); } catch (e) { /* keep placeholder title tables */ }
		try { moveActionsIntoHeader(); } catch (e) { /* leave Dolibarr's layout alone */ }
		try { groupRecordActions(); } catch (e) { /* retain the original action row */ }
		try { polishEntityHeader(); } catch (e) { /* retain the native identity layout */ }
		try { placeTabsBelowHeader(); } catch (e) { /* retain Dolibarr's tab placement */ }
		try { applyThirdPartyFieldSchema(); } catch (e) { /* retain the native field layout */ }
		try { buildBreadcrumb(); } catch (e) { /* idem */ }
		try { polishRecordSections(); } catch (e) { /* retain the native section layout */ }
	});
})();
