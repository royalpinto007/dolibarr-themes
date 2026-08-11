<?php
/* Copyright (C) 2026 Thrive
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

/**
 * Modernisation layer.
 *
 * Included last, after components.inc.php, so it refines the component set
 * rather than competing with it. Everything here is expressed in the --c-*
 * token contract, so it inherits whichever palette the active theme defines
 * and never hardcodes a colour.
 *
 * Scope rules this file keeps to, learned from regressions in this codebase:
 *   - no `display: none !important`  (Dolibarr reveals panels with an inline style)
 *   - no opacity:0 on selectors matched by href (skin thumbnails were caught once)
 *   - no rule that depends on a module's own markup; only Dolibarr's shared classes
 * so third-party modules inherit the styling instead of breaking under it.
 */
if (!defined('ISLOADEDBYSTEELSHEET')) {
	die('Must be call by steelsheet');
}
?>

/* ==========================================================================
   Status pills
   Dolibarr emits <span class="badge badge-statusN badge-status">. The numbered
   variant carries the meaning (4 = open/active, 8 = closed, ...), so the tint is
   attached to the number and the shape to the shared class.
   ========================================================================== */
.badge-status {
	display: inline-flex;
	align-items: center;
	gap: var(--sp-1);
	padding: 2px var(--sp-2);
	min-height: 22px;
	border-radius: var(--r-pill);
	font-size: 0.75rem;
	font-weight: 600;
	line-height: 1.4;
	letter-spacing: 0;
	border: 1px solid transparent;
	white-space: nowrap;
}
/* Draft / neutral */
.badge-status0, .badge-status9 {
	background: var(--c-sunken); color: var(--c-muted);
	border-color: var(--c-border);
}
/* Open / validated / active */
.badge-status1, .badge-status4 {
	background: color-mix(in srgb, var(--c-success) 10%, transparent);
	color: var(--c-success);
	border-color: color-mix(in srgb, var(--c-success) 22%, transparent);
}
/* In progress / waiting */
.badge-status3, .badge-status7 {
	background: color-mix(in srgb, var(--c-warning) 12%, transparent);
	color: var(--c-warning);
	border-color: color-mix(in srgb, var(--c-warning) 24%, transparent);
}
/* Cancelled / refused */
.badge-status5, .badge-status8 {
	background: color-mix(in srgb, var(--c-danger) 9%, transparent);
	color: var(--c-danger);
	border-color: color-mix(in srgb, var(--c-danger) 20%, transparent);
}
/* Paid / closed positively */
.badge-status6 {
	background: var(--c-accent-soft); color: var(--c-accent-ink);
	border-color: color-mix(in srgb, var(--c-accent) 20%, transparent);
}

/* ==========================================================================
   Action hierarchy
   One filled accent action, everything else a quiet surface button. Dolibarr
   decides which is which through its own classes, so the hierarchy follows the
   markup instead of the page.
   ========================================================================== */
.butAction, .butActionDelete, .butActionRefused, .butActionNew,
a.butAction, a.butActionDelete, a.butActionRefused, a.butActionNew {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: var(--sp-2);
	min-height: 34px;
	padding: 0 var(--sp-4);
	border-radius: var(--r);
	font-size: 0.8125rem;
	font-weight: 600;
	line-height: 1;
	text-transform: none;
	letter-spacing: 0;
	border: 1px solid var(--c-border);
	background: var(--c-surface);
	color: var(--c-ink-2);
	box-shadow: var(--sh-sm);
	transition: background var(--t), border-color var(--t), color var(--t), box-shadow var(--t);
}
.butAction:hover, a.butAction:hover {
	background: var(--c-sunken);
	border-color: var(--c-border-strong);
	color: var(--c-ink);
}
/* The primary action on a record: Dolibarr marks it butActionNew, or it is the
   first butAction in the bar. Only one is filled, so the eye has one target. */
.butActionNew, a.butActionNew,
div.tabsAction .butAction:first-of-type, div.tabsAction a.butAction:first-of-type {
	background: var(--c-accent);
	border-color: var(--c-accent);
	color: #fff;
	box-shadow: 0 1px 2px var(--c-accent-ring);
}
.butActionNew:hover, a.butActionNew:hover,
div.tabsAction .butAction:first-of-type:hover, div.tabsAction a.butAction:first-of-type:hover {
	background: var(--c-accent-hover);
	border-color: var(--c-accent-hover);
	color: #fff;
}
.butActionDelete, a.butActionDelete {
	background: var(--c-surface);
	border-color: color-mix(in srgb, var(--c-danger) 28%, transparent);
	color: var(--c-danger);
}
.butActionDelete:hover, a.butActionDelete:hover {
	background: color-mix(in srgb, var(--c-danger) 8%, transparent);
	border-color: var(--c-danger);
	color: var(--c-danger);
}
.butActionRefused, a.butActionRefused {
	background: var(--c-sunken);
	border-color: var(--c-border);
	color: var(--c-faint);
	box-shadow: none;
	cursor: not-allowed;
}
/* The action bar itself: a right-aligned row that wraps instead of overflowing. */
div.tabsAction {
	display: flex;
	flex-wrap: wrap;
	justify-content: flex-end;
	align-items: center;
	gap: var(--sp-2);
	margin: var(--sp-4) 0;
}
div.tabsAction > * { margin: 0; }

/* ==========================================================================
   List filter row
   The filter inputs share the table header band. Giving them one control style
   makes the band read as a filter bar rather than a second header row.
   ========================================================================== */
tr.liste_titre_filter > td, tr.liste_titre_filter > th {
	background: var(--c-surface);
	border-bottom: 1px solid var(--c-hairline);
	padding: var(--sp-2) var(--sp-3);
	vertical-align: middle;
}
tr.liste_titre_filter input[type="text"],
tr.liste_titre_filter input[type="search"],
tr.liste_titre_filter select,
tr.liste_titre_filter .select2-selection {
	min-height: 32px;
	border-radius: var(--r-sm);
	border: 1px solid var(--c-border);
	background: var(--c-surface);
	font-size: 0.8125rem;
}
tr.liste_titre_filter input:focus,
tr.liste_titre_filter select:focus {
	border-color: var(--c-accent);
	box-shadow: 0 0 0 3px var(--c-accent-ring);
	outline: none;
}

/* ==========================================================================
   Pagination
   ========================================================================== */
.pagination select, select.flat.selectlimit {
	min-height: 32px;
	border-radius: var(--r-sm);
	border: 1px solid var(--c-border);
	background: var(--c-surface);
	font-size: 0.8125rem;
	padding: 0 var(--sp-2);
}
.pagination a, .pagination .paginationafterarrows a, li.pagination a {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 30px;
	min-height: 30px;
	padding: 0 var(--sp-2);
	border-radius: var(--r-sm);
	color: var(--c-ink-2);
	transition: background var(--t), color var(--t);
}
.pagination a:hover { background: var(--c-sunken); color: var(--c-ink); }
.pagination .active, .pagination li.active > a, .pagination .pageplusone.active {
	background: var(--c-accent);
	color: #fff;
	font-weight: 600;
}

/* ==========================================================================
   Empty states
   Dolibarr prints a muted "None" / "No record found" where a table would be.
   Centring it and giving it room turns a stray word into a deliberate state.
   ========================================================================== */
tr.oddeven td.opacitymedium:only-child,
td.opacitymedium[colspan]:only-child,
tr td.center.opacitymedium[colspan] {
	padding: var(--sp-7) var(--sp-4);
	text-align: center;
	color: var(--c-faint);
	font-size: 0.875rem;
}

/* ==========================================================================
   Responsive
   The action bar and filter controls are the two rows that overflow first on a
   narrow window, so they stack rather than scroll.
   ========================================================================== */
@media only screen and (max-width: 900px) {
	div.tabsAction { justify-content: stretch; }
	div.tabsAction > * { flex: 1 1 auto; }
	.pagination select, select.flat.selectlimit { width: auto; }
}

/* ==========================================================================
   Record header (paired with thriveshell/modern.js)
   The JS moves Dolibarr's own action bar into the banner and derives a
   breadcrumb from its "Back to list" link. These rules only apply once that has
   happened -- .ts-has-actions is set by the script -- so a page the script
   skipped keeps Dolibarr's original layout untouched.
   ========================================================================== */
.ts-breadcrumb {
	display: flex; align-items: center; gap: var(--sp-2);
	margin: 0 0 var(--sp-3);
	font-size: 0.8125rem; color: var(--c-muted);
}
.ts-breadcrumb a { color: var(--c-accent-ink); font-weight: 500; text-decoration: none; }
.ts-breadcrumb a:hover { text-decoration: underline; }
.ts-breadcrumb-sep { color: var(--c-faint); }
.ts-breadcrumb-current { color: var(--c-ink); font-weight: 600; }

div.arearef.ts-has-actions {
	display: flex;
	align-items: flex-start;
	justify-content: space-between;
	gap: var(--sp-4);
	flex-wrap: wrap;
}
.ts-header-actions { margin-<?php echo $left; ?>: auto; flex: 0 0 auto; }
.ts-header-actions div.tabsAction { margin: 0; justify-content: flex-end; }
@media only screen and (max-width: 900px) {
	div.arearef.ts-has-actions { flex-direction: column; }
	.ts-header-actions { margin-<?php echo $left; ?>: 0; width: 100%; }
}

/* Dolibarr pins its record pager ("Back to list", prev/next) to the top-right of
   the banner, which is exactly where the relocated actions now sit -- the two
   painted over each other. Returning it to normal flow lets the flex row lay both
   out. The pager keeps its arrows: the breadcrumb repeats the "Back to list"
   target but not the record-to-record navigation, so nothing is lost. */
div.arearef.ts-has-actions .pagination.paginationref {
	position: static;
	float: none;
	order: 3;
	margin-<?php echo $left; ?>: var(--sp-3);
	white-space: nowrap;
}
div.arearef.ts-has-actions .ts-header-actions { order: 2; }
div.arearef.ts-has-actions .refid {
	display: grid;
	grid-template-columns: auto auto 1fr;
	align-items: center;
	column-gap: var(--sp-2);
	row-gap: var(--sp-1);
}
div.arearef.ts-has-actions .refid .refidno { grid-column: 1 / -1; grid-row: 2; }
div.arearef.ts-has-actions .refid .statusref { grid-column: 3; grid-row: 1; display: inline-flex; margin: 0 0 0 var(--sp-2); }
div.arearef.ts-has-actions .divphotoref {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 56px;
	height: 56px;
	margin-<?php echo $right; ?>: var(--sp-4);
	border-radius: var(--r-lg);
	background: var(--c-accent-soft);
	color: var(--c-accent-ink);
}
div.arearef.ts-has-actions .divphotoref .photoref,
div.arearef.ts-has-actions .divphotoref .pictotitle { margin: 0; font-size: 1.5rem; }

/* Compact entity card: identity and frequent actions share the first row, with
   record navigation immediately below. The containing tabBar supplies the full
   content width, rather than mounting this structure in a title-table cell. */
div.tabBar.ts-entity-card {
	padding: 0;
	background: var(--c-surface);
	border: 1px solid var(--c-hairline);
	border-radius: var(--r-lg);
	box-shadow: var(--sh-sm);
	overflow: visible;
}
div.tabBar.ts-entity-card > div.arearef.ts-has-actions {
	padding: var(--sp-4) var(--sp-5);
	border-bottom: 0;
	border-radius: var(--r-lg) var(--r-lg) 0 0;
}
div.tabBar.ts-entity-card > div.tabs[data-ts-placed="1"] {
	margin: 0;
	padding: 0 var(--sp-4);
	border-top: 1px solid var(--c-hairline);
	border-bottom: 1px solid var(--c-hairline);
	background: var(--c-surface);
}
div.tabBar.ts-entity-card > div.tabs[data-ts-placed="1"] + * { margin-top: var(--sp-4); }

/* Edit stays primary, Send email secondary. The lower-frequency controls are the
   original Dolibarr nodes inside a disclosure, preserving hooks and tokens. */
div.tabsAction .ts-record-primary,
div.tabsAction a.ts-record-primary:first-of-type {
	background: var(--c-accent);
	border-color: var(--c-accent);
	color: #fff;
}
div.tabsAction .ts-record-secondary {
	background: var(--c-surface);
	border-color: var(--c-border);
	color: var(--c-ink-2);
}
.ts-header-actions div.tabsAction > .ts-record-primary { order: 1; }
.ts-header-actions div.tabsAction > .ts-record-secondary { order: 2; }
.ts-header-actions div.tabsAction > .ts-more-actions { order: 3; }
.ts-more-actions { position: relative; flex: 0 0 auto; }
.ts-more-actions > summary { list-style: none; }
.ts-more-actions > summary::-webkit-details-marker { display: none; }
.ts-more-actions-trigger {
	display: inline-flex; align-items: center; justify-content: center; gap: var(--sp-2);
	min-height: 34px; padding: 0 var(--sp-3);
	border: 1px solid var(--c-border); border-radius: var(--r);
	background: var(--c-surface); color: var(--c-ink-2);
	font-size: 0.8125rem; font-weight: 600; cursor: pointer;
	box-shadow: var(--sh-sm);
}
.ts-more-actions-trigger:hover,
.ts-more-actions[open] .ts-more-actions-trigger { background: var(--c-sunken); color: var(--c-ink); }
.ts-more-actions-trigger:focus-visible { outline: 2px solid var(--c-accent); outline-offset: 2px; }
.ts-more-actions-trigger .fa-chevron-down { font-size: 0.625rem; transition: transform var(--t); }
.ts-more-actions[open] .fa-chevron-down { transform: rotate(180deg); }
.ts-more-actions-menu {
	position: absolute; z-index: 40; top: calc(100% + var(--sp-2)); <?php echo $right; ?>: 0;
	min-width: 180px; padding: var(--sp-1);
	background: var(--c-surface); border: 1px solid var(--c-border);
	border-radius: var(--r); box-shadow: var(--sh-lg);
}
.ts-more-actions-menu .ts-more-action-item,
.ts-more-actions-menu a.ts-more-action-item,
.ts-more-actions-menu span.ts-more-action-item {
	display: flex; align-items: center; justify-content: flex-start;
	width: 100%; min-height: 36px; margin: 0; padding: 0 var(--sp-3);
	border: 0; border-radius: var(--r-sm); box-shadow: none;
	background: transparent; color: var(--c-ink-2); cursor: pointer;
}
.ts-more-actions-menu .ts-more-action-item:hover { background: var(--c-sunken); color: var(--c-ink); }
.ts-more-actions-menu .ts-more-action-item.butActionDelete { color: var(--c-danger); }

@media only screen and (max-width: 900px) {
	div.tabBar.ts-entity-card > div.arearef.ts-has-actions { padding: var(--sp-4); }
	.ts-header-actions div.tabsAction { width: 100%; }
	.ts-header-actions div.tabsAction > .ts-record-primary,
	.ts-header-actions div.tabsAction > .ts-record-secondary { flex: 1 1 160px; }
	.ts-more-actions { flex: 0 0 auto; }
}
@media only screen and (max-width: 640px) {
	.ts-header-actions div.tabsAction > .ts-record-primary,
	.ts-header-actions div.tabsAction > .ts-record-secondary { flex: 1 1 calc(50% - var(--sp-2)); }
	.ts-more-actions { flex: 1 1 100%; }
	.ts-more-actions-trigger { width: 100%; }
	.ts-more-actions-menu { <?php echo $left; ?>: 0; <?php echo $right; ?>: 0; }
}

/* ==========================================================================
   Declarative Third Party field groups
   The companion hook maps translated labels to stable data-field attributes.
   Layout only consumes data-group, and activates only when every native row was
   mapped, so custom or future fields always fall back to Dolibarr's safe layout.
   ========================================================================== */
.ts-thirdparty-groups {
	display: grid;
	grid-template-columns: repeat(3, minmax(0, 1fr));
	align-items: stretch;
	gap: var(--sp-4);
	padding: 0 var(--sp-4) var(--sp-4);
}
.ts-field-group {
	min-width: 0;
	background: var(--c-surface);
	border: 1px solid var(--c-hairline);
	border-radius: var(--r-lg);
	box-shadow: var(--sh-sm);
	overflow: hidden;
	display: flex;
	flex-direction: column;
}
.ts-field-group-title {
	margin: 0;
	padding: var(--sp-3) var(--sp-4);
	border-bottom: 1px solid var(--c-hairline);
	font-size: 0.9375rem;
	font-weight: 650;
	line-height: 1.4;
	color: var(--c-ink);
	display: flex;
	align-items: center;
	gap: var(--sp-2);
}
.ts-field-group-icon { color: var(--c-accent); width: 18px; text-align: center; }
.ts-field-group td.ts-empty-value::after { content: "—"; color: var(--c-faint); }
.ts-field-group table.tableforfield { margin: 0; border: 0; box-shadow: none; flex: 1 1 auto; }
.ts-field-group table.tableforfield td { padding: 10px var(--sp-4); font-size: 0.8125rem; line-height: 1.35; }
.ts-field-group table.tableforfield tr + tr { border-top: 1px solid var(--c-hairline); }
.ts-field-group table.tableforfield td:first-child { color: var(--c-ink-2); font-weight: 600; }
@media only screen and (max-width: 1400px) {
	.ts-thirdparty-groups { grid-template-columns: repeat(2, minmax(0, 1fr)); }
	.ts-field-group[data-group="identity"] { grid-row: span 2; }
}
@media only screen and (max-width: 900px) {
	.ts-thirdparty-groups { grid-template-columns: minmax(0, 1fr); padding: 0 var(--sp-3) var(--sp-3); }
	.ts-field-group[data-group="identity"] { grid-row: auto; }
}

/* Dolibarr tags Merge with butActionDelete, so it inherited the destructive red
   and sat next to Delete looking equally final -- but merging is not a deletion.
   Only the merge action is exempted, by its own href, and it is exempted *back to*
   the neutral surface button rather than given a style of its own.

   The exemption is deliberately narrow: anything else Dolibarr or a module tags as
   destructive keeps the red. Losing a warning on a genuinely destructive action is
   worse than over-warning on one that is not, so the unknown case stays red. This
   is presentation only -- the href, its confirmation step and its behaviour are
   untouched. */
div.tabsAction a.butActionDelete[href*="action=merge"],
div.tabsAction a.butActionDelete[href*="action=confirm_merge"] {
	background: var(--c-surface);
	border-color: var(--c-border);
	color: var(--c-ink-2);
}
div.tabsAction a.butActionDelete[href*="action=merge"]:hover,
div.tabsAction a.butActionDelete[href*="action=confirm_merge"]:hover {
	background: var(--c-sunken);
	border-color: var(--c-border-strong);
	color: var(--c-ink);
}

/* ==========================================================================
   List page header
   Dolibarr prints "Third parties(39)" as one string in div.titre and renders the
   page actions as icon-only a.btnTitle links whose label lives in @title.
   modern.js splits the count into .ts-count; the label is drawn from the title
   attribute, so it stays translated and no text is invented here.
   ========================================================================== */
div.titre {
	font-size: 1.375rem;
	font-weight: 650;
	letter-spacing: -0.02em;
	color: var(--c-ink);
	display: inline-flex;
	align-items: center;
	gap: var(--sp-2);
}
.ts-count {
	display: inline-flex; align-items: center; justify-content: center;
	min-width: 24px; height: 22px; padding: 0 var(--sp-2);
	border-radius: var(--r-pill);
	background: var(--c-sunken); color: var(--c-muted);
	font-size: 0.75rem; font-weight: 600; letter-spacing: 0;
}
/* The create action is the only btnTitle carrying a plus glyph. Promoting it to a
   labelled primary button uses @title, which Dolibarr has already translated. */
div.pagination a.btnTitle:has(.fa-plus-circle), div.pagination a.btnTitle:has(.fa-plus) {
	display: inline-flex; align-items: center; gap: var(--sp-2);
	min-height: 34px; padding: 0 var(--sp-4);
	border-radius: var(--r);
	background: var(--c-accent); border: 1px solid var(--c-accent); color: #fff;
	font-size: 0.8125rem; font-weight: 600; line-height: 1;
	box-shadow: 0 1px 2px var(--c-accent-ring);
	transition: background var(--t), border-color var(--t);
}
div.pagination a.btnTitle:has(.fa-plus-circle):hover, div.pagination a.btnTitle:has(.fa-plus):hover {
	background: var(--c-accent-hover); border-color: var(--c-accent-hover);
}
div.pagination a.btnTitle:has(.fa-plus-circle)::after, div.pagination a.btnTitle:has(.fa-plus)::after {
	content: attr(title);
	font: inherit;
}
div.pagination a.btnTitle:has(.fa-plus-circle) .btnTitle-label,
div.pagination a.btnTitle:has(.fa-plus) .btnTitle-label { display: none; }   /* @title already prints it */
div.pagination a.btnTitle:has(.fa-plus-circle) span[class*="fa-"],
div.pagination a.btnTitle:has(.fa-plus) span[class*="fa-"] { color: #fff; }
/* Secondary title actions (view mode, tools) stay quiet next to it. */
a.btnTitle:not(:has(.fa-plus-circle)):not(:has(.fa-plus)) {
	display: inline-flex; align-items: center; justify-content: center;
	min-width: 32px; min-height: 32px;
	border-radius: var(--r-sm); color: var(--c-muted);
	transition: background var(--t), color var(--t);
}
a.btnTitle:not(:has(.fa-plus-circle)):not(:has(.fa-plus)):hover {
	background: var(--c-sunken); color: var(--c-ink);
}

/* ==========================================================================
   Record detail sections as cards (generic)
   Dolibarr's own left/right split is the only structure it reliably provides, so
   each half becomes a card. No group titles are invented: the markup carries no
   dependable per-field semantics, so naming them would be guesswork.
   ========================================================================== */
div.fichehalfleft, div.fichehalfright {
	background: var(--c-surface);
	border: 1px solid var(--c-hairline);
	border-radius: var(--r-lg);
	box-shadow: var(--sh-sm);
	padding: var(--sp-2) var(--sp-4);
	box-sizing: border-box;
}
div.fichehalfleft table td, div.fichehalfright table td {
	padding-top: var(--sp-3);
	padding-bottom: var(--sp-3);
}
/* Borrowed from the Novo review: one deliberate breakpoint, so the two halves
   stack instead of the page acquiring a horizontal scrollbar. */
@media only screen and (max-width: 1100px) {
	div.fichehalfleft, div.fichehalfright { width: 100%; float: none; margin-bottom: var(--sp-4); }
}

/* ==========================================================================
   Linked files / events blocks as cards, with a considered empty state
   ========================================================================== */
div.fichecenter div.fichehalfleft .div-table-responsive,
div.fichecenter div.fichehalfright .div-table-responsive { box-shadow: none; border: 0; }
.ts-emptybox {
	border: 1px dashed var(--c-border-strong);
	border-radius: var(--r-lg);
	background: var(--c-canvas);
	padding: var(--sp-7) var(--sp-4);
	text-align: center;
	color: var(--c-faint);
	font-size: 0.875rem;
}
.ts-record-section-card {
	background: var(--c-surface);
	border: 1px solid var(--c-hairline);
	border-radius: var(--r-lg);
	box-shadow: var(--sh-sm);
	padding: var(--sp-4);
	box-sizing: border-box;
	min-height: 270px;
}
.ts-record-section-card table.table-fiche-title td.col-picto { display: none; }
.ts-record-section-card table.table-fiche-title { margin: 0 0 var(--sp-3); }
.ts-record-section-card table.table-fiche-title td.col-title,
.ts-record-section-card .titre {
	display: flex;
	align-items: center;
	gap: var(--sp-2);
	font-size: 0.9375rem;
	font-weight: 650;
}
.ts-record-section-card .ts-section-icon { color: var(--c-accent); }
.ts-record-section-files .ts-native-empty-source { display: none; }
.ts-record-section-card .ts-emptybox {
	min-height: 150px;
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	gap: var(--sp-2);
}
.ts-record-section-card .ts-emptybox > .far { font-size: 1.5rem; color: var(--c-muted); }
.ts-record-section-card .ts-emptybox > strong { color: var(--c-ink-2); }
.ts-record-section-card .ts-emptybox > span:last-child { font-size: 0.8125rem; }

/* The title-bar actions share div.pagination with the pager. Adding a label to the
   create button widened that row, and as a rigid row it pushed the button past the
   viewport. It wraps instead -- and below 1200px the label is dropped back to the
   icon, which is a chosen breakpoint rather than an accidental scrollbar. */
div.pagination {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	justify-content: flex-end;
	gap: var(--sp-2);
	max-width: 100%;
}
@media only screen and (max-width: 1200px) {
	div.pagination a.btnTitle:has(.fa-plus-circle)::after, div.pagination a.btnTitle:has(.fa-plus)::after { content: none; }
	div.pagination a.btnTitle:has(.fa-plus-circle), div.pagination a.btnTitle:has(.fa-plus) { padding: 0 var(--sp-3); }
}

/* At phone widths the title bar is a table row, so the actions cell cannot wrap
   under the heading and the pager ran off the side (clipped, and out of reach).
   Letting those cells become blocks stacks the heading and its actions instead. */
@media only screen and (max-width: 768px) {
	div.titre_list > table, table.centpercent.notopnoleftnoright { display: block; width: 100%; }
	table.centpercent.notopnoleftnoright > tbody,
	table.centpercent.notopnoleftnoright > tbody > tr { display: block; width: 100%; }
	table.centpercent.notopnoleftnoright > tbody > tr > td { display: block; width: 100%; text-align: <?php echo $left; ?>; }
	div.pagination { justify-content: flex-<?php echo $left; ?>; }
}

/* ==========================================================================
   Record header corrections
   ========================================================================== */
/* Only the anchor the breadcrumb duplicated is hidden -- the pager's prev/next
   arrows sit beside it in the same block and keep working. */
div.arearef.ts-has-actions .paginationref a.ts-crumb-source { display: none; }

/* The banner reserved room for a pager that no longer prints its label, leaving a
   tall empty band between the title and the first field. */
div.arearef.ts-has-actions { padding-top: var(--sp-2); padding-bottom: var(--sp-2); }
div.arearef.ts-has-actions .refid,
div.arearef.ts-has-actions .refidno { margin-top: 0; margin-bottom: 0; }
div.arearef.ts-has-actions + div,
div.tabBar > div.arearef.ts-has-actions { margin-bottom: var(--sp-3); }
div.arearef.ts-has-actions .paginationref { align-self: center; }

/* ==========================================================================
   Tabs: one row, scrolled rather than wrapped
   A wrapped second row is the most legacy-looking part of a record page. Keeping
   them on one line and scrolling the overflow avoids measuring anything in JS.
   The scrollbar chrome is hidden but the strip stays focusable and swipeable, so
   keyboard and touch reach the tabs the scrollbar would have revealed.
   ========================================================================== */
div.tabs, ul.tabs, div.tabBar > div.tabs {
	display: flex;
	flex-wrap: nowrap;
	align-items: stretch;
	gap: var(--sp-1);
	overflow-x: auto;
	overflow-y: hidden;
	scrollbar-width: none;
	-webkit-overflow-scrolling: touch;
	overscroll-behavior-x: contain;
	max-width: 100%;
}
div.tabs::-webkit-scrollbar, ul.tabs::-webkit-scrollbar { height: 0; width: 0; }
div.tabs a.tab, div.tabs .tabsElem, ul.tabs a.tab, ul.tabs li.tabsElem {
	white-space: nowrap;
	flex: 0 0 auto;
}
div.tabs a.tab, ul.tabs a.tab {
	display: inline-flex;
	align-items: center;
	gap: var(--sp-2);
	padding: var(--sp-3) var(--sp-3);
	font-size: 0.8125rem;
	border-radius: var(--r-sm) var(--r-sm) 0 0;
}
/* Focus must stay visible: the strip scrolls, so a tab reached by keyboard has to
   announce itself even though the scrollbar is hidden. */
div.tabs a.tab:focus-visible, ul.tabs a.tab:focus-visible {
	outline: 2px solid var(--c-accent);
	outline-offset: -2px;
}

/* ==========================================================================
   Sections below a record (Linked files, Events, ...)
   Dolibarr reuses div.titre for these, the same class as a list's page title, so
   they inherited page-title weight and read as if each were a new page. Their
   Their location inside div.fichecenter separates a section heading from a
   page heading; td.col-title is used for both and is not a safe discriminator.
   ========================================================================== */
div.fichecenter td.col-title div.titre,
div.fichecenter .col-title div.titre {
	font-size: 1rem;
	font-weight: 620;
	letter-spacing: -0.01em;
}
/* The block that follows each of those headings becomes the card. Scoped to the
   table wrapper Dolibarr already emits, so no section is restructured. */
td.col-title ~ td .div-table-responsive,
.col-title ~ td .div-table-responsive,
div.fichecenter .div-table-responsive,
div.fichecenter .div-table-responsive-no-min {
	background: var(--c-surface);
	border: 1px solid var(--c-hairline);
	border-radius: var(--r-lg);
	box-shadow: var(--sh-sm);
	overflow: hidden;
}
/* Actions that sit beside a section heading were colliding with it; the heading
   row lays them out instead of letting them overlap. */
tr:has(> td.col-title) {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: var(--sp-3);
	flex-wrap: wrap;
	width: 100%;
}
tr:has(> td.col-title) > td { display: inline-flex; align-items: center; gap: var(--sp-2); }
tr:has(> td.col-title) > td.col-title { flex: 1 1 auto; }

/* A genuinely empty section reads as a considered state rather than a stray word.
   Only cells Dolibarr has actually marked empty are treated this way. */
div.fichecenter td.opacitymedium[colspan],
div.fichecenter tr.oddeven td.opacitymedium {
	border: 1px dashed var(--c-border-strong);
	border-radius: var(--r-lg);
	background: var(--c-canvas);
	padding: var(--sp-6) var(--sp-4);
	text-align: center;
	color: var(--c-faint);
}

/* A section heading has nowhere near the room of the page title bar, so a create
   action beside one stays an icon button and is not given a label it would only
   have to clip. */
.col-title ~ td a.btnTitle, tr:has(> td.col-title) a.btnTitle {
	display: inline-flex; align-items: center; justify-content: center;
	min-width: 30px; min-height: 30px; padding: 0 var(--sp-2);
	border-radius: var(--r-sm);
	background: var(--c-accent-soft); border: 1px solid transparent; color: var(--c-accent-ink);
	box-shadow: none; overflow: visible;
}
.col-title ~ td a.btnTitle::after, tr:has(> td.col-title) a.btnTitle::after { content: none; }
.col-title ~ td a.btnTitle span[class*="fa-"],
tr:has(> td.col-title) a.btnTitle span[class*="fa-"] { color: var(--c-accent-ink); }

/* ==========================================================================
   Record header: place the pager deliberately
   The banner is a flex row, and the pager block comes first in Dolibarr's markup,
   so once its "back to list" label was hidden the bare arrows wrapped above the
   title. Ordering the row puts the identity first and groups the pager with the
   actions on the right, where it reads as navigation for this record. No markup
   is moved: only the visual order changes, so prev/next keep their hrefs.
   ========================================================================== */
div.arearef.ts-has-actions > *:not(.ts-header-actions):not(.pagination) {
	order: 1;
	flex: 1 1 auto;
	min-width: 0;
}
div.arearef.ts-has-actions .ts-header-actions { order: 2; align-self: center; }
div.arearef.ts-has-actions .paginationref {
	order: 3;
	align-self: center;
	display: inline-flex;
	align-items: center;
	gap: var(--sp-1);
	flex: 0 0 auto;
}
div.arearef.ts-has-actions .paginationref { opacity: .72; }
div.arearef.ts-has-actions .paginationref a {
	display: inline-flex; align-items: center; justify-content: center;
	min-width: 28px; min-height: 28px;
	border-radius: var(--r-sm);
	color: var(--c-muted);
	transition: background var(--t), color var(--t);
}
div.arearef.ts-has-actions .paginationref a:hover { background: var(--c-sunken); color: var(--c-ink); }

/* ==========================================================================
   List filters as one surface
   Dolibarr splits filtering across a block above the table and a filter row
   inside it. Both are given the same surface treatment so they read as one
   control area, without moving either out of the form they post from.
   ========================================================================== */
div.liste_titre.liste_titre_bydiv {
	background: var(--c-surface);
	border: 1px solid var(--c-hairline);
	border-radius: var(--r-lg);
	box-shadow: var(--sh-sm);
	padding: var(--sp-3);
	margin-bottom: var(--sp-3);
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	gap: var(--sp-2);
}
div.liste_titre.liste_titre_bydiv .select2-container,
div.liste_titre.liste_titre_bydiv select,
div.liste_titre.liste_titre_bydiv input { max-width: 100%; }
@media only screen and (max-width: 900px) {
	div.liste_titre.liste_titre_bydiv > * { flex: 1 1 100%; }
}

/* ==========================================================================
   Results footer
   Dolibarr prints the row range, the page-size select and the pager separately
   at the foot of a list. They are laid out as one row so the foot of every list
   reads the same. Nothing is added that Dolibarr does not already render.
   ========================================================================== */
div.div-table-responsive + div.pagination,
.paginationatbottom,
div.fichecenter > form > div.pagination:last-child {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	justify-content: space-between;
	gap: var(--sp-3);
	padding: var(--sp-3) var(--sp-1);
	margin-top: var(--sp-2);
	border-top: 1px solid var(--c-hairline);
	font-size: 0.8125rem;
	color: var(--c-muted);
}

/* ==========================================================================
   Corrections found by comparing screenshots against the target
   ========================================================================== */
/* The status pill was being treated as its own flex item in the banner row and
   drifted away from the name it belongs to. Only the identity block absorbs the
   free space; everything else keeps its natural width and stays put. */
div.arearef.ts-has-actions > *:not(.ts-header-actions):not(.pagination) { flex: 0 0 auto; }
div.arearef.ts-has-actions > div:first-of-type,
div.arearef.ts-has-actions > .refid:first-of-type { flex: 1 1 auto; min-width: 0; }

/* The create action shares a fixed-width cell with the pager and the view-mode
   switches, and as the last item in that row it was the one clipped when the
   label made it wider. Ordering it first means the least important controls give
   way instead of the primary action -- and it never shrinks. */
div.pagination a.btnTitle:has(.fa-plus-circle),
div.pagination a.btnTitle:has(.fa-plus) {
	order: -1;
	flex: 0 0 auto;
}
div.pagination { overflow: visible; }

/* The create action keeps the primary treatment but drops the generated label.
   Drawing it from @title widened the control inside a fixed-width cell it shares
   with the pager and the view switches, and it clipped -- first at the viewport
   edge, then inside its own container even after being ordered first. A filled
   icon button reads as the primary action without depending on how much room
   that cell happens to have, which varies by module and by how many pager
   controls a list renders. The label remains available as the tooltip. */
div.pagination a.btnTitle:has(.fa-plus-circle)::after,
div.pagination a.btnTitle:has(.fa-plus)::after { content: none; }
div.pagination a.btnTitle:has(.fa-plus-circle),
div.pagination a.btnTitle:has(.fa-plus) {
	min-width: 34px;
	padding: 0 var(--sp-2);
	justify-content: center;
}

/* ==========================================================================
   Page header (paired with buildPageHeader in modern.js)
   Title and count on the left, the primary create action on the right. The rules
   apply only once the script has built .ts-pagehead, so a page it skipped keeps
   Dolibarr's own header untouched.
   ========================================================================== */
.ts-pagehead {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: var(--sp-4);
	flex-wrap: wrap;
	margin: 0 0 var(--sp-4);
}
.ts-pagehead-title { display: flex; align-items: center; gap: var(--sp-2); min-width: 0; }
.ts-pagehead-title div.titre { font-size: 1.375rem; font-weight: 650; letter-spacing: -0.02em; }
.ts-pagehead-actions { display: flex; align-items: center; gap: var(--sp-2); flex: 0 0 auto; }
.ts-view-switch {
	display: inline-flex;
	align-items: center;
	height: 38px;
	padding: 3px;
	border: 1px solid var(--c-border);
	border-radius: var(--r);
	background: var(--c-surface);
}
.ts-view-switch a.ts-view-switch-option {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 30px;
	height: 30px;
	min-width: 30px;
	margin: 0;
	padding: 0;
	border: 0;
	border-radius: var(--r-sm);
	background: transparent;
	box-shadow: none;
	color: var(--c-muted);
}
.ts-view-switch a.ts-view-switch-option:hover { background: var(--c-sunken); color: var(--c-ink); }
.ts-view-switch a.ts-view-switch-option[aria-current="page"] {
	background: var(--c-accent-soft);
	color: var(--c-accent-ink);
}

/* In the header the create action has room for its label, so it is a full button
   again -- the earlier clipping came from the fixed-width pager cell it used to
   share, which it no longer sits in. */
.ts-pagehead a.btnTitle.ts-primary-action {
	display: inline-flex; align-items: center; gap: var(--sp-2);
	min-height: 36px; padding: 0 var(--sp-4); min-width: 0;
	border-radius: var(--r);
	background: var(--c-accent); border: 1px solid var(--c-accent); color: #fff;
	font-size: 0.8125rem; font-weight: 600; line-height: 1;
	box-shadow: 0 1px 2px var(--c-accent-ring);
	white-space: nowrap;
}
.ts-pagehead a.btnTitle.ts-primary-action:hover { background: var(--c-accent-hover); border-color: var(--c-accent-hover); }
.ts-pagehead a.btnTitle.ts-primary-action span[class*="fa-"] { color: #fff; }
.ts-action-label { font: inherit; }
@media only screen and (max-width: 640px) {
	.ts-pagehead { align-items: stretch; }
	.ts-pagehead-actions { width: 100%; flex-wrap: wrap; }
	.ts-pagehead a.btnTitle.ts-primary-action { flex: 1 1 180px; width: auto; justify-content: center; }
}

/* ==========================================================================
   Coherent list composition (paired with composeListSurface in modern.js)
   The filter block stays in the submitting form, while the table and its result
   footer share a single card edge. Wide tables scroll inside that card only.
   ========================================================================== */
.ts-list-composition { display: grid; gap: var(--sp-3); min-width: 0; }
.ts-list-composition .ts-filter-surface {
	margin: 0;
	padding: var(--sp-3);
	border: 1px solid var(--c-hairline);
	border-radius: var(--r-lg);
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	gap: 10px;
}
.ts-filter-surface .ts-quick-search {
	display: flex;
	align-items: center;
	gap: var(--sp-2);
	flex: 1 1 340px;
	width: auto;
	max-width: 380px;
	min-width: 240px;
	height: 38px;
	padding: 0 var(--sp-2) 0 var(--sp-3);
	border: 1px solid var(--c-border);
	border-radius: var(--r);
	background: var(--c-surface);
	color: var(--c-faint);
}
.ts-filter-surface .ts-quick-search:focus-within {
	border-color: var(--c-accent);
	box-shadow: 0 0 0 3px var(--c-accent-ring);
}
.ts-filter-surface input.ts-quick-search-input {
	flex: 1 1 auto;
	width: auto !important;
	min-width: 80px;
	height: 34px;
	padding: 0;
	border: 0 !important;
	box-shadow: none !important;
	background: transparent;
}
.ts-filter-surface .ts-submit-search,
.ts-filter-surface .ts-clear-filters {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 28px;
	height: 28px;
	padding: 0;
	border: 0;
	border-radius: var(--r-sm);
	background: transparent;
	color: var(--c-muted);
}
.ts-filter-surface .ts-submit-search { display: none; }
.ts-filter-surface .ts-submit-search:hover { background: var(--c-accent-soft); color: var(--c-accent-ink); }
.ts-filter-surface .ts-clear-filters:hover { background: var(--c-sunken); color: var(--c-ink); }
.ts-filter-surface .ts-clear-all-filters {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: var(--sp-1);
	height: 32px;
	width: auto;
	flex: 0 0 auto;
	margin: 0 0 0 auto;
	padding: 0 var(--sp-1);
	border: 0;
	border-radius: var(--r);
	background: transparent;
	color: var(--c-accent-ink);
	font-size: 0.8125rem;
	font-weight: 550;
	white-space: nowrap;
}
.ts-filter-surface .ts-clear-all-filters:hover { background: var(--c-accent-soft); color: var(--c-accent); }
.ts-filter-surface .ts-clear-all-filters[hidden] { display: none !important; }
.ts-filter-surface .ts-clear-all-filters [class*="fa-"] { display: none; }
.ts-filter-surface .divsearchfield {
	margin: 0;
	flex: 0 1 190px;
	width: 190px;
	min-width: 180px;
}
.ts-filter-surface .ts-toolbar-filter-1 { flex-basis: 270px; width: 270px; min-width: 270px; }
.ts-filter-surface .ts-toolbar-filter-2 { flex-basis: 225px; width: 225px; min-width: 225px; }
.ts-filter-surface .ts-toolbar-filter-3 { flex-basis: 220px; width: 220px; }
.ts-filter-surface .ts-toolbar-filter > span:not([class*="fa-"]) {
	display: block;
	width: 100%;
	min-width: 0;
}
.ts-filter-surface .ts-toolbar-filter .select2-container,
.ts-filter-surface .ts-toolbar-filter .select2-selection {
	box-sizing: border-box;
	width: 100% !important;
	min-width: 0 !important;
	max-width: 100% !important;
}
.ts-filter-surface .ts-toolbar-filter .select2-search--inline,
.ts-filter-surface .ts-toolbar-filter .select2-search__field {
	box-sizing: border-box;
	max-width: 100%;
	white-space: nowrap;
	text-overflow: clip;
}
.ts-filter-surface .divsearchfield > span[class*="fa-"] { display: none; }
.ts-filter-surface .select2-container { width: 100% !important; min-width: 0 !important; }
.ts-filter-surface .select2-selection { min-height: 38px; }
/* Select2's multiple-value control contains a real search input. The global
   input component styled that nested editor as a second bordered control inside
   the already-bordered Select2 surface. Keep one frame: the selection owns it. */
.ts-filter-surface .select2-selection--multiple {
	height: 40px;
	min-height: 40px;
	padding: 2px 28px 2px var(--sp-2) !important;
	overflow: hidden;
}
.ts-filter-surface .select2-selection--multiple .select2-search__field {
	height: 34px;
	margin: 0;
	padding: 0 !important;
	border: 0 !important;
	border-radius: 0 !important;
	background: transparent !important;
	box-shadow: none !important;
}

/* Dolibarr uses fa-list for the selected-columns dropdown. Its glyph includes
   three bullet stems that read as stray dividers beside the select-all box.
   The trigger and dropdown stay intact; only its painted glyph is simplified. */
.ts-list-card tr.liste_titre > :first-child .dropdown .fa-list::before {
	content: "\f1de";
}

/* This is one utility column in Dolibarr's table, not two table columns. Give
   it two fixed internal slots so the chooser cannot alter the table algorithm:
   trigger on the left, select checkbox (and every row checkbox) on the right. */
.ts-list-card table tr.liste_titre > :first-child,
.ts-list-card table tr.oddeven > :first-child,
.ts-list-card table tr.impair > :first-child,
.ts-list-card table tr.pair > :first-child {
	box-sizing: border-box;
	width: 96px;
	min-width: 96px;
	max-width: 96px;
}
/* A sticky layer on every th leaves a compositor seam between otherwise
   identical backgrounds. Keep one sticky painted surface on the row; only the
   utility cell needs positioning, as the containing block for its controls. */
.ts-list-card div.div-table-responsive table.liste tr.liste_titre {
	position: sticky;
	top: 0;
	z-index: 3;
	background: var(--colorbacktitle1);
}
.ts-list-card div.div-table-responsive table.liste tr.liste_titre > th {
	position: static;
	top: auto;
	z-index: auto;
	background: transparent;
}
.ts-list-card div.div-table-responsive table.liste tr.liste_titre > :first-child {
	position: relative;
	padding-left: 0;
	padding-right: 0;
}
.ts-list-card table tr.oddeven > :first-child,
.ts-list-card table tr.impair > :first-child,
.ts-list-card table tr.pair > :first-child {
	padding-right: 15px;
	text-align: right;
}
.ts-list-card table tr.oddeven > :first-child > input.checkforselect,
.ts-list-card table tr.impair > :first-child > input.checkforselect,
.ts-list-card table tr.pair > :first-child > input.checkforselect {
	float: right;
	margin: 0;
}
.ts-list-card tr.liste_titre > :first-child dl.dropdown {
	position: absolute;
	top: 50%;
	left: 12px;
	width: 34px;
	height: 34px;
	margin: 0;
	transform: translateY(-50%);
}
.ts-list-card tr.liste_titre > :first-child div.checkallactions {
	position: absolute;
	top: 50%;
	right: 15px;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 18px;
	height: 18px;
	transform: translateY(-50%);
}
.ts-list-card tr.liste_titre > :first-child div.checkallactions input[type="checkbox"] { margin: 0; }
.ts-list-card tr.liste_titre > :first-child dl.dropdown dt a {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 34px;
	height: 34px;
	padding: 0;
	border: 0;
	border-radius: 8px;
	background: transparent;
	color: var(--c-muted);
	overflow: visible;
	transition: background var(--t), color var(--t), box-shadow var(--t);
}
.ts-list-card tr.liste_titre > :first-child dl.dropdown dt a:hover {
	background: var(--c-accent-soft);
	color: var(--c-accent-ink);
}
.ts-list-card tr.liste_titre > :first-child dl.dropdown dt a:focus { outline: none; }
.ts-list-card tr.liste_titre > :first-child dl.dropdown dt a:focus-visible {
	background: var(--c-accent-soft);
	color: var(--c-accent-ink);
	box-shadow: 0 0 0 2px var(--c-accent-ring);
}
.ts-list-card tr.liste_titre > :first-child dl.dropdown dt a .fa-list {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 16px;
	height: 16px;
	font-size: 15px;
	line-height: 1;
}
.ts-list-card tr.liste_titre > :first-child dl.dropdown dd { position: static; }

/* Keep Dolibarr's selected-column chooser and its event handlers, but present
   the existing list as a COMMAND popover.  The stock rules leave this control
   at the inherited table font size and add label padding on top of the flex
   gap, which makes the rows look cramped and uneven. */
.ts-list-card tr.liste_titre > :first-child dl.dropdown dd ul.selectedfieldsleft {
	top: calc(100% + 7px);
	left: 0;
	right: auto;
	width: 280px;
	min-width: 280px;
	max-width: calc(100vw - 32px);
	max-height: min(380px, calc(100vh - 32px));
	padding: 12px;
	border: 1px solid var(--c-border);
	border-radius: 12px;
	background: var(--c-surface);
	box-shadow: 0 14px 36px rgba(11, 18, 32, 0.14), 0 3px 10px rgba(11, 18, 32, 0.06);
	font-size: 0.8125rem;
	line-height: 1.35;
	scrollbar-width: thin;
	scrollbar-color: #cbd2dc transparent;
}
.ts-list-card tr.liste_titre > :first-child dl.dropdown dd ul.selectedfieldsleft::-webkit-scrollbar { width: 6px; }
.ts-list-card tr.liste_titre > :first-child dl.dropdown dd ul.selectedfieldsleft::-webkit-scrollbar-track { background: transparent; }
.ts-list-card tr.liste_titre > :first-child dl.dropdown dd ul.selectedfieldsleft::-webkit-scrollbar-thumb {
	border-radius: 999px;
	background: #cbd2dc;
}
.ts-list-card tr.liste_titre > :first-child dl.dropdown dd ul.selectedfieldsleft li {
	min-height: 38px;
	padding: 0 8px;
	gap: 10px;
	border: 0;
	border-radius: 8px;
	font-size: 0.8125rem;
	line-height: 1.35;
}
.ts-list-card tr.liste_titre > :first-child dl.dropdown dd ul.selectedfieldsleft li:hover:not(.liinputsearch) {
	background: var(--c-accent-soft);
}
.ts-list-card tr.liste_titre > :first-child dl.dropdown dd ul.selectedfieldsleft li.liinputsearch {
	min-height: 0;
	padding: 0 0 10px;
	background: var(--c-surface);
}
.ts-list-card tr.liste_titre > :first-child dl.dropdown dd ul.selectedfieldsleft input.inputsearch_dropdownselectedfields {
	width: 100% !important;
	min-width: 0 !important;
	height: 38px;
	padding: 0 11px !important;
	border: 1px solid var(--c-border) !important;
	border-radius: 8px !important;
	background: var(--c-surface) !important;
	box-shadow: none !important;
	font-size: 0.8125rem !important;
}
.ts-list-card tr.liste_titre > :first-child dl.dropdown dd ul.selectedfieldsleft input.inputsearch_dropdownselectedfields:focus {
	border-color: var(--c-border-strong) !important;
	box-shadow: none !important;
}
.ts-list-card tr.liste_titre > :first-child dl.dropdown dd ul.selectedfieldsleft input.inputsearch_dropdownselectedfields:focus-visible {
	border-color: var(--c-accent) !important;
	box-shadow: 0 0 0 2px var(--c-accent-ring) !important;
}
.ts-list-card tr.liste_titre > :first-child dl.dropdown dd ul.selectedfieldsleft li > input[type="checkbox"] {
	flex: 0 0 16px;
	width: 16px;
	height: 16px;
	margin: 0;
}
.ts-list-card tr.liste_titre > :first-child dl.dropdown dd ul.selectedfieldsleft li > label {
	flex: 1 1 auto;
	padding: 0;
	font-size: inherit;
	line-height: inherit;
	text-align: left;
	cursor: pointer;
}
@media only screen and (max-width: 900px) {
	.ts-list-card tr.liste_titre > :first-child dl.dropdown dd ul.selectedfieldsleft {
		max-height: min(340px, calc(100vh - 32px));
	}
}

.ts-column-filters { position: relative; flex: 0 0 auto; }
.ts-column-filters > summary {
	display: inline-flex;
	align-items: center;
	gap: var(--sp-2);
	height: 38px;
	padding: 0 var(--sp-3);
	border: 1px solid var(--c-border);
	border-radius: var(--r);
	background: var(--c-surface);
	color: var(--c-ink-2);
	font-size: 0.8125rem;
	font-weight: 550;
	cursor: pointer;
	list-style: none;
}
.ts-column-filters > summary::-webkit-details-marker { display: none; }
.ts-column-filters[open] > summary,
.ts-column-filters > summary:hover { border-color: var(--c-accent); color: var(--c-accent-ink); }
.ts-column-filters-panel {
	position: absolute;
	z-index: 120;
	top: calc(100% + var(--sp-2));
	right: 0;
	display: grid;
	grid-template-columns: repeat(3, minmax(150px, 1fr));
	gap: var(--sp-3);
	width: min(720px, calc(100vw - var(--nav-w) - var(--sp-7)));
	padding: var(--sp-4);
	border: 1px solid var(--c-border);
	border-radius: var(--r-lg);
	background: var(--c-surface);
	box-shadow: var(--sh-lg);
}
.ts-column-filter-control { display: grid; gap: var(--sp-1); min-width: 0; }
.ts-column-filter-label {
	font-size: 0.6875rem;
	font-weight: 620;
	letter-spacing: 0.035em;
	text-transform: uppercase;
	color: var(--c-muted);
}
.ts-column-filter-control input,
.ts-column-filter-control select,
.ts-column-filter-control .select2-container { width: 100% !important; max-width: none !important; }
.ts-column-filter-control .select2-selection--single {
	width: 100% !important;
	height: 40px;
	min-height: 40px;
	padding: 0 34px 0 var(--sp-3);
	border: 1px solid var(--c-border) !important;
	border-radius: var(--r) !important;
	background: var(--c-surface);
	box-shadow: none;
}
.ts-column-filter-control .select2-selection--single .select2-selection__rendered {
	padding: 0;
	line-height: 38px;
}
.ts-column-filter-control .select2-selection--single .select2-selection__arrow {
	top: 0;
	right: 2px;
	height: 38px;
}
.ts-column-filter-control .select2-container--default.select2-container--focus .select2-selection--single,
.ts-column-filter-control .select2-container--default.select2-container--open .select2-selection--single {
	border-color: var(--c-accent) !important;
	box-shadow: 0 0 0 1px var(--c-accent-ring);
}
.select2-dropdown.ts-column-filter-dropdown {
	box-sizing: border-box;
	width: var(--ts-column-filter-width) !important;
	min-width: var(--ts-column-filter-width) !important;
	padding: 0 !important;
	border: 1px solid var(--c-border);
	border-radius: var(--r-lg);
	background: var(--c-surface);
	box-shadow: var(--sh-md);
	overflow: hidden;
}
.select2-dropdown.ts-column-filter-dropdown .select2-search--dropdown { padding: var(--sp-2); }
.select2-dropdown.ts-column-filter-dropdown .select2-search__field {
	width: 100% !important;
	height: 38px;
	padding: 0 var(--sp-3) !important;
	border: 1px solid var(--c-border) !important;
	border-radius: var(--r) !important;
	background: var(--c-surface) !important;
	box-shadow: none !important;
}
.select2-dropdown.ts-column-filter-dropdown .select2-search__field:focus {
	border-color: var(--c-accent) !important;
	box-shadow: 0 0 0 1px var(--c-accent-ring) !important;
}
.select2-dropdown.ts-column-filter-dropdown .select2-results__options { padding: var(--sp-1); }
.select2-dropdown.ts-column-filter-dropdown .select2-results__option {
	display: flex;
	align-items: center;
	min-height: 36px;
	padding: 0 var(--sp-3);
	border-radius: var(--r-sm);
	font-size: 0.8125rem;
}
.select2-dropdown.ts-column-filter-dropdown.ts-compact-select2-dropdown .select2-search--dropdown { display: none; }
.select2-dropdown.ts-page-size-dropdown {
	box-sizing: border-box;
	padding: var(--sp-1) !important;
	border: 1px solid var(--c-border);
	border-radius: var(--r);
	background: var(--c-surface);
	box-shadow: var(--sh-md);
	overflow: hidden;
}
.select2-dropdown.ts-page-size-dropdown .select2-search--dropdown { display: none; }
.select2-dropdown.ts-page-size-dropdown .select2-results__options {
	max-height: none !important;
	padding: 0;
	overflow-y: visible !important;
}
.select2-dropdown.ts-page-size-dropdown .select2-results__option {
	display: flex;
	align-items: center;
	min-height: 35px;
	padding: 0 var(--sp-3);
	border-radius: var(--r-sm);
	font-size: 0.875rem;
}
.select2-dropdown.ts-page-size-dropdown .select2-results__option[aria-selected="true"] {
	background: var(--c-accent-soft);
	color: var(--c-accent-ink);
	font-weight: 620;
}

.ts-list-card tr.liste_titre .ts-sort-indicator {
	display: inline-flex;
	align-items: center;
	margin-left: 5px;
	vertical-align: middle;
}
.ts-list-card tr.liste_titre .ts-sort-indicator .paddingright { padding-right: 0; }

/* AJAX record previews share .mytooltip. Keep ordinary compact help tooltips
   content-sized while giving record cards an opaque, structured surface. */
.ui-tooltip.mytooltip {
	box-sizing: border-box;
	width: 400px;
	max-width: calc(100vw - 24px);
	padding: 16px;
	border: 1px solid var(--c-border);
	border-radius: var(--r-lg);
	background: var(--c-surface) !important;
	box-shadow: var(--sh-lg);
	color: var(--c-ink-2);
	font-size: 0.875rem;
	line-height: 1.6;
	opacity: 1 !important;
	z-index: 3000 !important;
}
.ui-tooltip.mytooltip .ui-tooltip-content { padding: 0; }
.ui-tooltip.mytooltip .centpercent {
	position: relative;
}
.ui-tooltip.mytooltip .centpercent > [class*="fa-"]:first-child {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 34px;
	height: 34px;
	margin-right: var(--sp-2);
	border-radius: var(--r);
	background: var(--c-accent-soft);
	vertical-align: middle;
}
.ui-tooltip.mytooltip u.paddingrightonly {
	display: inline-block;
	margin: 0;
	padding: 0;
	border: 0;
	font-size: 0.875rem;
	font-weight: 650;
	letter-spacing: 0;
	text-transform: none;
	opacity: 1;
	vertical-align: middle;
}
.ui-tooltip.mytooltip .customer-back,
.ui-tooltip.mytooltip .vendor-back,
.ui-tooltip.mytooltip .prospect-back {
	display: inline-flex;
	align-items: center;
	min-height: 22px;
	padding: 0 7px;
	border-radius: var(--r-pill);
	background: var(--c-sunken);
	color: var(--c-muted);
	font-size: 0.75rem;
	font-weight: 600;
}
.ui-tooltip.mytooltip .badge-status {
	position: absolute;
	top: 5px;
	right: 0;
	float: none;
	margin: 0;
}
.ui-tooltip.mytooltip b,
.ui-tooltip.mytooltip strong {
	display: inline-block;
	width: 112px;
	color: var(--c-muted);
	font-weight: 550;
	white-space: nowrap;
}
.ui-tooltip.mytooltip .pictofixedwidth,
.ui-tooltip.mytooltip .paddingright > [class*="fa-"]:first-child {
	width: 112px;
	padding: 0;
	text-align: left;
}
.ui-tooltip.mytooltip .centpercent[data-ts-structured="1"] { position: static; }
.ui-tooltip.mytooltip .ts-tooltip-header {
	display: flex;
	align-items: center;
	gap: var(--sp-2);
	min-width: 0;
}
.ui-tooltip.mytooltip .ts-tooltip-header .ts-tooltip-icon {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	flex: 0 0 34px;
	width: 34px;
	height: 34px;
	border-radius: var(--r);
	background: var(--c-accent-soft);
	color: var(--c-accent-ink);
}
.ui-tooltip.mytooltip .ts-tooltip-kind {
	margin: 0;
	padding: 0;
	border: 0;
	color: var(--c-ink);
	font-size: 0.875rem;
	font-weight: 650;
	text-decoration: none;
}
.ui-tooltip.mytooltip .ts-tooltip-header .ts-tooltip-status {
	position: static;
	margin: 0 0 0 auto;
	flex: 0 0 auto;
}
.ui-tooltip.mytooltip .ts-tooltip-details {
	display: grid;
	gap: 7px;
	margin-top: var(--sp-3);
}
.ui-tooltip.mytooltip .ts-tooltip-row {
	display: grid;
	grid-template-columns: 112px minmax(0, 1fr);
	align-items: start;
	column-gap: var(--sp-3);
}
.ui-tooltip.mytooltip .ts-tooltip-label {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	color: var(--c-muted);
	font-weight: 550;
}
.ui-tooltip.mytooltip .ts-tooltip-label [class*="fa-"] {
	flex: 0 0 14px;
	width: 14px;
	text-align: center;
}
.ui-tooltip.mytooltip .ts-tooltip-value {
	min-width: 0;
	color: var(--c-ink-2);
	font-weight: 400;
	overflow-wrap: anywhere;
}
.ts-column-filters-source { display: none; }
.ts-list-card {
	min-width: 0;
	background: var(--c-surface);
	border: 1px solid var(--c-hairline);
	border-radius: var(--r-lg);
	box-shadow: var(--sh-sm);
	overflow: hidden;
}
.ts-list-card > .div-table-responsive,
.ts-list-card > .div-table-responsive-no-min {
	margin: 0;
	border: 0;
	border-radius: 0;
	box-shadow: none;
	overflow-x: auto;
}
.ts-list-card tr.liste_titre > th:not(:first-child),
.ts-list-card tr.liste_titre > td:not(:first-child) { text-align: center !important; }
.ts-list-card td a.opacitymedium:is(.customer-back, .vendor-back, .prospect-back) { opacity: 1; }
.ts-results-footer {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: var(--sp-3);
	min-height: 50px;
	padding: var(--sp-2) var(--sp-4);
	border-top: 1px solid var(--c-hairline);
	background: var(--c-surface);
	color: var(--c-muted);
	font-size: 0.8125rem;
}
.ts-results-summary { white-space: nowrap; }
.ts-results-nav {
	display: flex;
	align-items: center;
	margin-left: auto;
}
.ts-results-nav div.pagination {
	display: flex;
	align-items: center;
	gap: 8px;
}
.ts-results-nav div.pagination > ul {
	display: flex; align-items: center; gap: 8px;
	margin: 0; padding: 0; list-style: none;
}
.ts-results-nav li { display: inline-flex; align-items: center; min-height: 38px; }
.ts-results-nav li.paginationcombolimit {
	position: relative;
	width: 126px;
	height: 38px;
	box-sizing: border-box;
	padding: 0;
	border: 1px solid var(--c-border);
	border-radius: var(--r);
	background: var(--c-surface);
}
.ts-results-nav li.paginationcombolimit .select2-container { width: 100% !important; }
.ts-results-nav li.paginationcombolimit .selection {
	display: block;
	width: 100%;
	height: 100%;
}
.ts-results-nav li.paginationcombolimit .select2-selection--single {
	box-sizing: border-box;
	width: 100% !important;
	min-width: 100% !important;
	max-width: none !important;
	height: 36px;
	min-height: 36px;
	padding: 0 30px 0 12px;
	border: 0 !important;
	border-radius: 0 !important;
	background: transparent;
	box-shadow: none !important;
}
.ts-results-nav li.paginationcombolimit .select2-selection__rendered {
	line-height: 36px;
	padding: 0 !important;
	overflow: visible;
	text-overflow: clip;
	font-size: 0.875rem;
	color: var(--c-ink-2);
}
.ts-results-nav li.paginationcombolimit .select2-selection__rendered::after { content: " per page"; }
.ts-results-nav li.paginationcombolimit .select2-selection__arrow { width: 24px; height: 36px; right: 6px; }
.ts-per-page-label { display: none; }
.ts-results-nav input.pageplusone {
	width: 38px !important;
	min-width: 38px !important;
	max-width: 38px !important;
	height: 38px !important;
	min-height: 38px !important;
	max-height: 38px !important;
	box-sizing: border-box;
	padding: 0;
	margin: 0 !important;
	border: 1px solid var(--c-accent) !important;
	border-radius: var(--r);
	background: var(--c-accent) !important;
	box-shadow: none !important;
	color: #fff !important;
	font-weight: 620;
	text-align: center;
}
.ts-results-nav a,
.ts-results-nav .inactive,
.ts-results-nav .ts-pager-value {
	display: inline-flex; align-items: center; justify-content: center;
	width: 38px !important; min-width: 38px !important; max-width: 38px !important;
	height: 38px !important; min-height: 38px !important; max-height: 38px !important;
	box-sizing: border-box; margin: 0 !important; padding: 0 !important;
	border: 1px solid var(--c-border);
	border-radius: var(--r); color: var(--c-ink-2);
	background: var(--c-surface);
	font-size: 0.875rem;
}
.ts-results-nav li.pagination:not(.paginationcombolimit) {
	width: 38px;
	min-width: 38px;
	max-width: 38px;
	height: 38px;
	min-height: 38px;
}
.ts-results-nav .paginationpageleft a,
.ts-results-nav .paginationpageright a,
.ts-results-nav .ts-pagination-disabled .inactive { font-size: 0; }
.ts-results-nav .paginationpageleft a::before,
.ts-results-nav .paginationpageright a::before,
.ts-results-nav .ts-pagination-disabled .inactive::before {
	display: block;
	font-family: "Font Awesome 5 Free", "Font Awesome 6 Free";
	font-size: 0.875rem;
	font-weight: 900;
	line-height: 1;
}
.ts-results-nav .paginationpageleft a::before,
.ts-results-nav .ts-pagination-previous .inactive::before { content: "\f053"; }
.ts-results-nav .paginationpageright a::before { content: "\f054"; }
.ts-results-nav .ts-pagination-next .inactive::before { content: "\f054"; }
.ts-results-nav .paginationpageleft a > i,
.ts-results-nav .paginationpageright a > i { display: none; }
.ts-results-nav .ts-pagination-disabled .inactive { color: var(--c-faint); cursor: default; }
.ts-results-nav .ts-pager-value { border: 1px solid var(--c-border); background: var(--c-surface); }
.ts-results-nav a:hover { background: var(--c-sunken); color: var(--c-ink); }

/* Sort labels stay part of the flat header plane. The shared hit-area rule
   otherwise paints a white rounded chip on hover. */
.ts-list-card tr.liste_titre th a.reposition:hover,
.ts-list-card tr.liste_titre td a.reposition:hover {
	background: transparent;
	box-shadow: none;
	color: var(--c-accent-ink);
}
@media only screen and (max-width: 640px) {
	.ts-list-composition .ts-filter-surface { padding: var(--sp-3); }
	.ts-filter-surface .ts-quick-search,
	.ts-filter-surface .divsearchfield { flex-basis: 100%; width: 100%; min-width: 0; }
	.ts-filter-surface .ts-clear-all-filters { width: auto; flex: 0 0 auto; margin-left: auto; }
	.ts-column-filters { width: 100%; }
	.ts-column-filters > summary { width: 100%; justify-content: center; }
	.ts-column-filters-panel {
		right: auto; left: 0;
		grid-template-columns: 1fr;
		width: calc(100vw - var(--nav-w) - (var(--sp-4) * 2));
	}
	.ts-results-footer {
		flex-wrap: nowrap;
		padding-left: var(--sp-2);
		padding-right: var(--sp-2);
	}
}

/* ==========================================================================
   COMMAND parity scale and surface rhythm
   Shared structural components keep their semantics; this final layer aligns
   their browser proportions with the COMMAND reference system.
   ========================================================================== */
body { font-size: 0.875rem; }
.fiche { max-width: 1600px; padding: var(--sp-6) var(--sp-6) var(--sp-8); }

.ts-pagehead { margin-bottom: var(--sp-5); }
.ts-pagehead-title div.titre, div.titre { font-size: 1.625rem; line-height: 1.15; }
.ts-count { min-width: 30px; height: 26px; font-size: 0.8125rem; }
.ts-pagehead a.btnTitle.ts-primary-action {
	min-height: 40px;
	padding: 0 18px;
	font-size: 0.875rem;
}

.ts-list-composition { gap: var(--sp-5); }
.ts-list-composition .ts-filter-surface {
	padding: var(--sp-4);
	gap: 10px;
	box-shadow: var(--sh);
}
.ts-filter-surface .ts-quick-search,
.ts-column-filters > summary { height: 40px; }
.ts-filter-surface .select2-selection { min-height: 40px; }
.ts-list-card { border-color: var(--c-border); box-shadow: var(--sh); }
.ts-list-card tr.liste_titre th,
.ts-list-card tr.liste_titre td { height: 48px; padding-top: var(--sp-3); padding-bottom: var(--sp-3); background: #f7f9fc; }
.ts-list-card tr.oddeven td,
.ts-list-card tr.impair td,
.ts-list-card tr.pair td { height: 52px; padding-top: var(--sp-3); padding-bottom: var(--sp-3); border-bottom: 1px solid var(--c-hairline); }
.ts-results-footer { min-height: 52px; padding: 6px var(--sp-4); background: #fbfcfe; }

/* The record is a canvas containing independent surfaces, not one white sheet. */
div.tabBar.ts-entity-card {
	background: transparent;
	border: 0;
	border-radius: 0;
	box-shadow: none;
}
div.tabBar.ts-entity-card > div.arearef.ts-has-actions {
	margin: 0 0 var(--sp-3);
	padding: 20px var(--sp-5);
	background: var(--c-surface);
	border: 1px solid var(--c-border);
	border-radius: var(--r-lg);
	box-shadow: var(--sh);
}
div.tabBar.ts-entity-card > div.tabs[data-ts-placed="1"] {
	margin: 0 0 var(--sp-4);
	padding: 0 var(--sp-3);
	background: var(--c-surface);
	border: 1px solid var(--c-border);
	border-radius: var(--r-lg);
	box-shadow: var(--sh-sm);
}
div.tabBar.ts-entity-card > div.tabs[data-ts-placed="1"] + * { margin-top: 0; }
div.tabBar.ts-entity-card div.tabs a.tab {
	min-height: 48px;
	padding: 0 18px;
	font-size: 0.875rem;
	font-weight: 520;
	border: 0;
	border-radius: 0;
	background: transparent;
}
div.tabBar.ts-entity-card div.tabs .tabactive > a.tab,
div.tabBar.ts-entity-card div.tabs .tabsElemActive > a.tab {
	color: var(--c-accent-ink);
	font-weight: 650;
	background: transparent;
}
div.tabBar.ts-entity-card div.tabs .tabactive::after,
div.tabBar.ts-entity-card div.tabs .tabsElemActive::after {
	left: var(--sp-3);
	right: var(--sp-3);
	height: 2px;
}
div.arearef.ts-has-actions .refid { font-size: 1.625rem; }
div.arearef.ts-has-actions .paginationref { opacity: .58; margin-<?php echo $left; ?>: var(--sp-2); }
.ts-header-actions .butAction,
.ts-header-actions .butActionRefused,
.ts-header-actions .ts-more-actions-trigger { min-height: 40px; padding: 0 var(--sp-4); font-size: 0.875rem; }

.ts-thirdparty-groups {
	align-items: start;
	gap: var(--sp-4);
	padding: 0;
}
.ts-field-group { display: block; border-color: var(--c-border); box-shadow: var(--sh); }
.ts-field-group-title { padding: var(--sp-4); font-size: 1rem; border-bottom-color: var(--c-hairline); }
.ts-field-group table.tableforfield td { padding: 11px var(--sp-4); font-size: 0.875rem; }
.ts-field-group table.tableforfield tr + tr { border-top-color: var(--c-hairline); }

.ts-record-section-card { border-color: var(--c-border); box-shadow: var(--sh); padding: var(--sp-5); }
.ts-record-section-card table.table-fiche-title { margin-bottom: var(--sp-4); }
.ts-record-section-card table.table-fiche-title td.col-title,
.ts-record-section-card .titre { font-size: 1rem; }
.ts-record-section-card tr.liste_titre th,
.ts-record-section-card tr.liste_titre td { background: #f7f9fc; }
.ts-record-section-card tr.oddeven td { height: 48px; }
.ts-record-section-card a.btnTitle {
	min-width: 34px;
	min-height: 34px;
	background: var(--c-surface);
	border: 1px solid var(--c-border);
	box-shadow: none;
}
.ts-record-section-card tr:has(> td.col-title) a.btnTitle {
	background: var(--c-surface);
	border-color: var(--c-border);
	color: var(--c-accent-ink);
}
.ts-record-section-card .ts-section-labelled-action {
	width: auto;
	padding: 0 var(--sp-3);
	gap: var(--sp-2);
	font-size: 0.8125rem;
	font-weight: 600;
}
.ts-section-action-label { white-space: nowrap; }

@media only screen and (min-width: 1401px) {
	.ts-thirdparty-groups { align-items: stretch; }
	.ts-field-group { display: flex; flex-direction: column; }
	.ts-field-group table.tableforfield { flex: 1 1 auto; }
}

@media only screen and (max-width: 992px) {
	.fiche { padding: var(--sp-5) var(--sp-4) var(--sp-7); }
}
@media only screen and (max-width: 640px) {
	.fiche { padding: var(--sp-4) var(--sp-3) var(--sp-6); }
	.ts-pagehead-title div.titre, div.titre { font-size: 1.375rem; }
	div.tabBar.ts-entity-card > div.arearef.ts-has-actions { padding: var(--sp-4); }
	.ts-list-composition { gap: var(--sp-4); }
}
@media only screen and (max-width: 767px) {
	/* Read-only semantic cards stay compact on phones; generic edit tables still
	   use the safer label-above-field layout from components.inc.php. */
	.ts-field-group table.tableforfield:not(.liste) > tbody > tr {
		display: grid;
		grid-template-columns: minmax(0, 48%) minmax(0, 1fr);
		align-items: center;
		padding: 0;
	}
	.ts-field-group table.tableforfield:not(.liste) > tbody > tr > td {
		display: table-cell;
		width: auto !important;
		padding: 11px var(--sp-4);
	}
}

/* ==========================================================================
   Shared COMMAND form composition

   modern.js adds these classes only after recognising a supported native form.
   The table, rows, controls, Select2 instances and action inputs are the original
   Dolibarr nodes; CSS changes their layout without changing submission semantics.
   ========================================================================== */

form.ts-modern-form {
	width: 100%;
	max-width: 1100px;
	margin: 0 auto;
}
form.ts-modern-form .ts-modern-form-card {
	margin: 0;
	padding: 22px 24px 24px;
	background: var(--c-surface);
	border: 1px solid #e4e8ef;
	border-radius: 12px;
	box-shadow: 0 6px 24px rgba(20, 31, 56, 0.055);
}

form.ts-modern-form table.ts-modern-form-header {
	width: 100%;
	margin: 0 0 10px;
	padding: 0;
	background: transparent;
	border: 0;
	border-bottom: 1px solid var(--c-hairline);
	border-radius: 0;
	box-shadow: none;
}
form.ts-modern-form table.ts-modern-form-header td {
	padding: 0 0 20px;
	border: 0;
}
form.ts-modern-form table.ts-modern-form-header tr.toptitle {
	display: flex;
	align-items: center;
}
form.ts-modern-form table.ts-modern-form-header td.col-picto {
	display: flex;
	align-items: center;
	width: 38px !important;
	min-width: 38px;
}
form.ts-modern-form table.ts-modern-form-header td.col-title {
	display: block;
	flex: 1 1 auto;
	width: auto !important;
}
form.ts-modern-form table.ts-modern-form-header div.titre {
	display: flex;
	align-items: center;
	gap: 10px;
	font-size: 1.5rem;
	font-weight: 650;
	line-height: 1.25;
	letter-spacing: -0.025em;
}
form.ts-modern-form table.ts-modern-form-header td.col-picto [class*="fa-"] {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 28px;
	height: 28px;
	margin: 0;
	border-radius: 7px;
	background: var(--c-accent-soft);
	color: var(--c-accent) !important;
	font-size: 0.875rem;
}

form.ts-modern-form table.ts-modern-form-table {
	display: block;
	width: 100%;
	margin: 0;
	border: 0;
	background: transparent;
}
form.ts-modern-form table.ts-modern-form-table > tbody {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	column-gap: 24px;
	width: 100%;
}
form.ts-modern-form table.ts-modern-form-table tr.ts-form-row {
	display: grid;
	grid-template-columns: minmax(128px, 0.58fr) minmax(0, 1.42fr);
	align-items: center;
	min-width: 0;
	min-height: 60px;
	box-sizing: border-box;
	padding: 10px 0;
	border: 0;
	border-bottom: 1px solid #f3f5f8;
	background: transparent;
}
form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-full {
	grid-column: 1 / -1;
	grid-template-columns: minmax(182px, 0.38fr) minmax(0, 1.62fr);
}
form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-paired {
	grid-template-columns: minmax(128px, 0.58fr) minmax(0, 1.42fr) minmax(128px, 0.58fr) minmax(0, 1.42fr);
	column-gap: 16px;
}
form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-empty { display: none; }

body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-label,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value {
	display: flex;
	align-items: center;
	width: auto !important;
	max-width: none !important;
	min-width: 0;
	min-height: 40px;
	padding: 0;
	border: 0;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-label {
	padding-<?php echo $right; ?>: 12px;
	color: var(--c-ink-2);
	font-size: 0.8125rem;
	font-weight: 600;
	line-height: 1.35;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value {
	position: relative;
	gap: 8px;
	color: var(--c-ink);
	font-size: 0.8125rem;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table tr:has(textarea) td {
	align-items: flex-start;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table tr:has(textarea) td.ts-form-label {
	padding-top: 11px;
}

/* Components' generic form alignment reserves an absolute icon gutter. The
   structured grid has a real flex gap, so the original pictogram can return to
   normal flow and remain predictably attached to its control. */
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > span.ts-form-leading-icon,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > span.pictofixedwidth:first-child,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > img.pictofixedwidth:first-child {
	position: static;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	flex: 0 0 20px;
	width: 20px;
	height: 40px;
	margin: 0;
	text-align: center;
	color: var(--c-muted);
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > .ts-form-help {
	position: static;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	flex: 0 0 16px;
	width: 16px;
	height: 40px;
	margin: 0;
	color: var(--c-muted);
}

body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]):not([type="file"]):not([type="submit"]):not([type="button"]),
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > textarea,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > select,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > .select2-container,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > table.nobordernopadding,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > span:not([class*="fa-"]):has(> .select2-container) {
	width: 100% !important;
	max-width: none !important;
	min-width: 0 !important;
	box-sizing: border-box;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]):not([type="file"]):not([type="submit"]):not([type="button"]),
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > textarea,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > .select2-container,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > span:has(> .select2-container),
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > table.nobordernopadding {
	flex: 1 1 auto;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > span:has(> .select2-container) {
	display: block;
	flex: 1 1 auto;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > span > .select2-container {
	width: 100% !important;
	max-width: none !important;
	min-width: 0 !important;
}
form.ts-modern-form table.ts-modern-form-table td.ts-form-value > table.nobordernopadding input {
	width: 100% !important;
	max-width: none !important;
	box-sizing: border-box;
}
form.ts-modern-form table.ts-modern-form-table input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]):not([type="file"]):not([type="submit"]):not([type="button"]):not(.select2-search__field),
form.ts-modern-form table.ts-modern-form-table select {
	height: 40px;
	padding-top: 0;
	padding-bottom: 0;
	border-color: #e0e6ef;
	border-radius: 8px;
	font-size: 0.875rem;
}
form.ts-modern-form table.ts-modern-form-table textarea {
	min-height: 104px;
	padding: 10px 12px;
	border-color: #e0e6ef;
	border-radius: 8px;
	font-size: 0.875rem;
	resize: vertical;
}
form.ts-modern-form table.ts-modern-form-table input:focus,
form.ts-modern-form table.ts-modern-form-table textarea:focus,
form.ts-modern-form table.ts-modern-form-table select:focus {
	border-color: var(--c-accent);
	box-shadow: 0 0 0 3px color-mix(in srgb, var(--c-accent) 14%, transparent);
}

form.ts-modern-form .select2-container--default .select2-selection--single {
	height: 40px;
	padding: 0 10px;
	border-color: #e0e6ef !important;
	border-radius: 8px !important;
}
form.ts-modern-form .select2-container--default .select2-selection--single .select2-selection__rendered {
	line-height: 38px;
	font-size: 0.875rem;
}
form.ts-modern-form .select2-container--default .select2-selection--single .select2-selection__arrow {
	width: 36px;
}
form.ts-modern-form .select2-container--default .select2-selection--multiple {
	position: relative;
	display: flex;
	align-items: center;
	width: 100%;
	min-height: 40px;
	padding: 3px 30px 3px 7px;
	border-color: #e0e6ef !important;
	border-radius: 8px !important;
}
form.ts-modern-form .select2-container--default .select2-selection--multiple .select2-selection__rendered {
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	gap: 4px;
	width: 100%;
	min-width: 0;
	margin: 0;
	padding: 0;
}
form.ts-modern-form .select2-container--default .select2-selection--multiple .select2-selection__choice {
	margin: 0;
	padding: 3px 8px;
	border: 0;
	border-radius: 6px;
	background: var(--c-accent-soft);
	color: var(--c-accent-ink);
}
form.ts-modern-form .select2-container--default .select2-selection--multiple::after {
	content: "";
	position: absolute;
	<?php echo $right; ?>: 13px;
	top: 50%;
	width: 6px;
	height: 6px;
	border-<?php echo $right; ?>: 1.5px solid var(--c-muted);
	border-bottom: 1.5px solid var(--c-muted);
	transform: translateY(-65%) rotate(45deg);
	pointer-events: none;
}
form.ts-modern-form .select2-container--default .select2-search--inline {
	display: inline-flex;
	align-items: center;
	flex: 1 1 90px;
	min-width: 60px;
	height: 30px;
	margin: 0;
}
body#mainbody form.ts-modern-form .select2-container--default .select2-search__field {
	width: 100% !important;
	min-width: 60px !important;
	height: 30px !important;
	margin: 0 !important;
	padding: 0 3px !important;
	border: 0 !important;
	border-radius: 0 !important;
	background: transparent !important;
	box-shadow: none !important;
	outline: 0 !important;
}
body .select2-dropdown.ts-form-select2-dropdown {
	border: 1px solid #e0e6ef;
	border-radius: 8px;
	background: var(--c-surface);
	box-shadow: var(--sh-lg);
	overflow: hidden;
}
body .ts-form-select2-dropdown .select2-search--dropdown { padding: 8px; }
body .ts-form-select2-dropdown .select2-search__field {
	height: 36px;
	padding: 0 10px;
	border: 1px solid #e0e6ef;
	border-radius: 7px;
	box-shadow: none;
}
body .ts-form-select2-dropdown .select2-search__field:focus {
	border-color: var(--c-accent);
	box-shadow: 0 0 0 3px color-mix(in srgb, var(--c-accent) 12%, transparent);
	outline: 0;
}
body .ts-form-select2-dropdown .select2-results__option { min-height: 34px; padding: 8px 10px; font-size: .8125rem; }

form.ts-modern-form tr.ts-form-choice-row td.ts-form-value {
	gap: 18px;
	flex-wrap: wrap;
}
form.ts-modern-form tr.ts-form-choice-row td.ts-form-value > label {
	display: inline-flex;
	align-items: center;
	justify-content: flex-end;
	gap: 8px;
	margin: 0;
	font-size: 0.8125rem;
	font-weight: 520;
}
form.ts-modern-form input[type="checkbox"],
form.ts-modern-form input[type="radio"] {
	width: 16px;
	height: 16px;
	margin: 0;
}

/* Compound fields retain every native control while presenting one deliberate
   relationship instead of unrelated fragments. */
body#mainbody form.ts-modern-form tr.ts-form-compound-incoterms td.ts-form-value {
	display: grid;
	grid-template-columns: minmax(170px, .38fr) minmax(0, .62fr);
	gap: 8px;
}
form.ts-modern-form tr.ts-form-compound-incoterms td.ts-form-value > .select2-container { grid-column: 1; width: 100% !important; }
form.ts-modern-form tr.ts-form-compound-incoterms td.ts-form-value .select2-selection--single { width: 100% !important; }
form.ts-modern-form tr.ts-form-compound-incoterms td.ts-form-value > input[name="location_incoterms"] { grid-column: 2; width: 100% !important; }
body#mainbody form.ts-modern-form tr.ts-form-compound-capital td.ts-form-value { gap: 0; }
form.ts-modern-form tr.ts-form-compound-capital td.ts-form-value > input[name="capital"] {
	border-radius: 8px 0 0 8px;
}
form.ts-modern-form tr.ts-form-compound-capital td.ts-form-value > span:not([class*="fa-"]) {
	display: inline-flex !important;
	align-items: center;
	justify-content: center;
	flex: 0 0 54px;
	height: 40px;
	box-sizing: border-box;
	border: 1px solid #e0e6ef;
	border-<?php echo $left; ?>: 0;
	border-radius: 0 8px 8px 0;
	background: var(--c-sunken);
	color: var(--c-ink-2);
}

body#mainbody form.ts-modern-form input[type="file"] {
	width: 100%;
	max-width: none !important;
	height: 40px;
	padding: 4px 10px 4px 4px;
	border-color: #e0e6ef;
	border-radius: 8px;
	background: var(--c-surface);
	color: var(--c-muted);
	font-size: 0.8125rem;
}
form.ts-modern-form input[type="file"]::file-selector-button {
	height: 30px;
	margin-<?php echo $right; ?>: 10px;
	padding: 0 12px;
	border: 1px solid var(--c-border);
	border-radius: 6px;
	background: var(--c-sunken);
	color: var(--c-ink-2);
	font: inherit;
	font-weight: 550;
}
form.ts-modern-form table.ts-modern-form-table td.ts-form-value > a.button_custcats,
form.ts-modern-form table.ts-modern-form-table td.ts-form-value > a.button_suppcats {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	flex: 0 0 40px;
	width: 40px;
	height: 40px;
	margin: 0;
	padding: 0;
	border: 1px solid #e0e6ef;
	border-radius: 8px;
	background: var(--c-surface);
	color: var(--c-accent);
	box-shadow: none;
}
form.ts-modern-form table.ts-modern-form-table td.ts-form-value > a.button_custcats:hover,
form.ts-modern-form table.ts-modern-form-table td.ts-form-value > a.button_suppcats:hover {
	background: var(--c-accent-soft);
	border-color: color-mix(in srgb, var(--c-accent) 25%, #e0e6ef);
}

form.ts-modern-form .ts-modern-form-actions {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 14px;
	margin: 30px 0 0;
	padding: 0 0 8px;
}
form.ts-modern-form .ts-modern-form-actions input.button {
	height: 40px;
	min-width: 110px;
	margin: 0;
	padding: 0 18px;
	border-radius: 8px;
	font-size: 0.8125rem;
	font-weight: 600;
}
form.ts-modern-form .ts-modern-form-actions input.button-save {
	background: var(--c-accent);
	border-color: var(--c-accent);
	color: #fff;
	box-shadow: 0 2px 5px var(--c-accent-ring);
}
form.ts-modern-form .ts-modern-form-actions input.button-save:hover {
	background: var(--c-accent-hover);
	border-color: var(--c-accent-hover);
}

@media only screen and (max-width: 900px) {
	form.ts-modern-form .ts-modern-form-card { padding: 20px; }
	form.ts-modern-form table.ts-modern-form-table > tbody { column-gap: 20px; }
	form.ts-modern-form table.ts-modern-form-table tr.ts-form-row,
	form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-full {
		grid-template-columns: minmax(135px, 0.65fr) minmax(0, 1.35fr);
	}
	form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-paired {
		grid-template-columns: minmax(120px, 0.6fr) minmax(0, 1.4fr) minmax(120px, 0.6fr) minmax(0, 1.4fr);
		column-gap: 14px;
	}
}

@media only screen and (max-width: 767px) {
	form.ts-modern-form .ts-modern-form-card {
		padding: 18px 16px;
		border-radius: 10px;
	}
	form.ts-modern-form table.ts-modern-form-header div.titre { font-size: 1.375rem; }
	form.ts-modern-form table.ts-modern-form-table > tbody {
		display: block;
	}
	form.ts-modern-form table.ts-modern-form-table tr.ts-form-row,
	form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-full,
	form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-half,
	form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-paired {
		display: grid;
		grid-template-columns: minmax(0, 1fr);
		gap: 5px;
		min-height: 0;
		padding: 12px 0;
	}
	form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-paired {
		grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
		column-gap: 12px;
	}
	form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-paired > td.ts-form-label,
	form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-paired > td.ts-form-value {
		grid-column: auto;
	}
	form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-paired > td.ts-form-label:nth-child(1),
	form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-paired > td.ts-form-label:nth-child(3) {
		grid-row: 1;
	}
	form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-paired > td.ts-form-value:nth-child(2),
	form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-paired > td.ts-form-value:nth-child(4) {
		grid-row: 2;
	}
	form.ts-modern-form table.ts-modern-form-table td.ts-form-label,
	form.ts-modern-form table.ts-modern-form-table td.ts-form-value {
		display: flex;
		width: 100% !important;
		min-height: 0;
		padding: 0;
	}
	form.ts-modern-form table.ts-modern-form-table td.ts-form-label { padding-top: 0; }
	body#mainbody form.ts-modern-form tr.ts-form-compound-incoterms td.ts-form-value {
		grid-template-columns: minmax(0, 1fr);
	}
	form.ts-modern-form tr.ts-form-compound-incoterms td.ts-form-value > .select2-container,
	form.ts-modern-form tr.ts-form-compound-incoterms td.ts-form-value > input[name="location_incoterms"] { grid-column: 1; }
	form.ts-modern-form table.ts-modern-form-table tr.ts-form-row.hideonsmartphone { display: grid !important; }
	form.ts-modern-form .ts-modern-form-actions { align-items: stretch; }
	form.ts-modern-form .ts-modern-form-actions input.button { flex: 1 1 0; min-width: 0; }
}

@media only screen and (max-width: 520px) {
	form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-paired {
		grid-template-columns: minmax(0, 1fr);
	}
	form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-paired > td.ts-form-label,
	form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-paired > td.ts-form-value {
		grid-row: auto !important;
	}
}

/* ==========================================================================
   COMMAND Kanban cards
   The generic component activates only after modern.js identifies Dolibarr's
   native Kanban container. List mode therefore keeps its existing table rules.
   Entity-specific nature colours and detail rows are layered on the shared grid
   and card geometry rather than turning table rows into visual cards.
   ========================================================================== */
.ts-list-card.ts-kanban-card-surface {
	background: transparent;
	border: 0;
	border-radius: 0;
	box-shadow: none;
	overflow: visible;
}
.ts-kanban-table {
	background: transparent !important;
	border: 0 !important;
	box-shadow: none !important;
}
.ts-kanban-table tr.liste_titre,
.ts-kanban-table tr.liste_titre_filter { display: none !important; }
.ts-kanban-table tr.trkanban,
.ts-kanban-table tr.trkanban > td {
	display: table-row;
	width: 100%;
	padding: 0 !important;
	border: 0 !important;
	background: transparent !important;
}
.ts-kanban-table tr.trkanban > td { display: table-cell; }
div.box-flex-container.kanban.ts-command-kanban {
	display: grid;
	grid-template-columns: repeat(4, minmax(0, 1fr));
	grid-auto-rows: auto;
	gap: 14px;
	width: 100%;
	margin: 0;
	padding: 0;
	align-items: stretch;
}
div.box-flex-container.kanban.ts-command-kanban > .ts-kanban-item {
	width: auto;
	min-width: 0;
	max-width: none;
	margin: 0;
}
div.box-flex-container.kanban.ts-command-kanban .ts-kanban-card {
	position: relative;
	display: flex;
	flex-direction: row;
	align-items: flex-start;
	gap: 12px;
	min-width: 0;
	min-height: 160px;
	height: 100%;
	padding: 14px;
	border: 1px solid var(--c-border);
	border-radius: 11px;
	background: var(--c-surface);
	box-shadow: 0 1px 2px rgba(15, 23, 42, .035), 0 6px 18px rgba(15, 23, 42, .035);
	overflow: visible;
	transform: none;
}
div.box-flex-container.kanban.ts-command-kanban .ts-kanban-card:hover {
	border-color: color-mix(in srgb, var(--c-accent) 20%, var(--c-border));
	box-shadow: 0 2px 4px rgba(15, 23, 42, .05), 0 10px 24px rgba(15, 23, 42, .06);
	transform: translateY(-1px);
}
div.box-flex-container.kanban.ts-command-kanban .ts-kanban-icon-tile {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	flex: 0 0 40px;
	width: 40px;
	height: 40px;
	min-width: 40px;
	min-height: 40px;
	margin: 0;
	padding: 0;
	box-sizing: border-box;
	border: 1px solid color-mix(in srgb, var(--c-accent) 14%, var(--c-border));
	border-radius: 9px;
	background: var(--c-accent-soft) !important;
	color: var(--c-accent-ink);
}
div.box-flex-container.kanban.ts-command-kanban .ts-kanban-icon-tile > span,
div.box-flex-container.kanban.ts-command-kanban .ts-kanban-icon-tile > i,
div.box-flex-container.kanban.ts-command-kanban .ts-kanban-icon-tile > img {
	width: auto;
	max-width: 24px;
	height: auto;
	max-height: 24px;
	margin: 0;
	font-size: 1.125rem;
}
div.box-flex-container.kanban.ts-command-kanban .info-box-content {
	display: flex;
	flex: 1 1 auto;
	flex-direction: column;
	align-items: stretch;
	justify-content: flex-start;
	gap: 0;
	min-width: 0;
	width: auto;
	height: 100%;
	padding: 0;
	background: transparent !important;
}
.ts-kanban-head {
	display: flex;
	align-items: flex-start;
	justify-content: space-between;
	gap: 8px;
	min-width: 0;
}
.ts-kanban-title-block { min-width: 0; flex: 1 1 auto; }
.ts-kanban-identity { min-width: 0; }
.ts-kanban-name {
	display: block;
	width: 100%;
	min-width: 0;
	margin: 0;
	padding: 0;
	overflow: hidden;
	color: var(--c-ink);
	font-size: .875rem;
	font-weight: 650;
	line-height: 1.35;
	text-overflow: ellipsis;
	white-space: nowrap;
}
.ts-kanban-name:hover { color: var(--c-accent-ink); }
.ts-kanban-alias {
	display: block;
	margin-top: 1px;
	overflow: hidden;
	color: var(--c-faint);
	font-size: .75rem;
	line-height: 1.3;
	text-overflow: ellipsis;
	white-space: nowrap;
}
.ts-kanban-natures {
	display: flex;
	flex-wrap: wrap;
	gap: 4px;
	margin-top: 4px;
}
.ts-kanban-natures[hidden] { display: none; }
.ts-kanban-nature {
	display: inline-flex;
	align-items: center;
	min-height: 19px;
	padding: 1px 8px;
	border: 1px solid transparent;
	border-radius: 999px;
	font-size: .7rem;
	font-weight: 550;
	line-height: 1.2;
}
.ts-kanban-nature-customer {
	color: #147a4b;
	background: #edf9f2;
	border-color: #ccebd9;
}
.ts-kanban-nature-prospect {
	color: var(--c-accent-ink);
	background: var(--c-accent-soft);
	border-color: color-mix(in srgb, var(--c-accent) 18%, transparent);
}
.ts-kanban-nature-vendor,
.ts-kanban-nature-supplier {
	color: #a64b12;
	background: #fff5e9;
	border-color: #f5dcc1;
}
.ts-kanban-code {
	display: block;
	margin-top: 4px;
	color: var(--c-muted);
	font-size: .75rem;
	font-weight: 500;
	line-height: 1.25;
}
.ts-kanban-details {
	display: grid;
	gap: 4px;
	min-width: 0;
	margin-top: 8px;
	padding-bottom: 24px;
}
.ts-kanban-detail {
	display: grid;
	grid-template-columns: 16px minmax(0, 1fr);
	align-items: start;
	gap: 7px;
	min-width: 0;
	padding: 0;
	color: var(--c-muted);
	font-size: .8125rem;
	line-height: 1.3;
}
.ts-kanban-detail > .fas { margin-top: 2px; color: var(--c-faint); font-size: .75rem; text-align: center; }
.ts-kanban-detail-value {
	display: block;
	min-width: 0;
	overflow: hidden;
	color: var(--c-ink-2);
	text-overflow: ellipsis;
	white-space: nowrap;
}
.ts-kanban-location .ts-kanban-detail-value { white-space: nowrap; }
.ts-kanban-status {
	position: absolute;
	right: 14px;
	bottom: 12px;
	display: inline-flex;
	margin: 0;
}
.ts-kanban-status .badge-status { min-height: 22px; padding: 1px 9px; }
.ts-kanban-bulk-selection { display: none !important; }
.ts-kanban-actions { position: relative; flex: 0 0 auto; }
.ts-kanban-actions > summary { list-style: none; }
.ts-kanban-actions > summary::-webkit-details-marker { display: none; }
.ts-kanban-actions-trigger {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 28px;
	height: 28px;
	border-radius: 7px;
	color: var(--c-muted);
	cursor: pointer;
}
.ts-kanban-actions-trigger:hover,
.ts-kanban-actions[open] .ts-kanban-actions-trigger { background: var(--c-sunken); color: var(--c-ink); }
.ts-kanban-actions-trigger:focus-visible { outline: 2px solid var(--c-accent); outline-offset: 1px; }
.ts-kanban-actions-menu {
	position: absolute;
	z-index: 45;
	top: calc(100% + 6px);
	right: 0;
	min-width: 150px;
	padding: 5px;
	border: 1px solid var(--c-border);
	border-radius: 9px;
	background: var(--c-surface);
	box-shadow: var(--sh-lg);
}
.ts-kanban-action {
	display: flex;
	align-items: center;
	gap: 8px;
	min-height: 34px;
	padding: 0 9px;
	border-radius: 6px;
	color: var(--c-ink-2);
	font-size: .8rem;
	white-space: nowrap;
}
.ts-kanban-action:hover { background: var(--c-sunken); color: var(--c-ink); }

@media only screen and (max-width: 1400px) {
	div.box-flex-container.kanban.ts-command-kanban { grid-template-columns: repeat(3, minmax(0, 1fr)); }
}
@media only screen and (max-width: 1050px) {
	div.box-flex-container.kanban.ts-command-kanban { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media only screen and (max-width: 680px) {
	div.box-flex-container.kanban.ts-command-kanban { grid-template-columns: minmax(0, 1fr); gap: 12px; }
	div.box-flex-container.kanban.ts-command-kanban .ts-kanban-card { min-height: 164px; padding: 14px; }
	.ts-kanban-status { right: 14px; bottom: 12px; }
}
