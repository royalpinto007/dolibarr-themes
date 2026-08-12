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
.ts-header-actions {
	max-width: min(100%, 900px);
}
.ts-header-actions div.tabsAction {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	justify-content: flex-end;
	gap: var(--sp-2);
	margin: 0;
}
.ts-header-actions div.tabsAction > a,
.ts-header-actions div.tabsAction > button,
.ts-header-actions div.tabsAction > input,
.ts-header-actions div.tabsAction > .inline-block,
.ts-header-actions div.tabsAction > details {
	flex: 0 0 auto;
	margin: 0 !important;
}
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
/* Some Third Party tabs emit .arearefnobottom instead of .arearef and omit an
   action bar entirely. The canonical overview-tab context marks both variants
   as the same entity surface, so the shell cannot fall back to the legacy
   banner merely because a tab has no actions. */
body.ts-thirdparty-record-context div.tabBar.ts-entity-card > .ts-entity-banner {
	display: flex;
	align-items: center;
	justify-content: space-between;
	flex-wrap: wrap;
	gap: var(--sp-4);
	min-height: 112px;
	margin: 0 0 var(--sp-3);
	padding: 20px var(--sp-5);
	background: var(--c-surface);
	border: 1px solid var(--c-border);
	border-radius: var(--r-lg);
	box-shadow: var(--sh);
	overflow: visible;
}
body.ts-thirdparty-record-context div.tabBar.ts-entity-card > .ts-entity-banner.arearefnobottom > div:first-child {
	display: contents;
}
body.ts-thirdparty-record-context div.tabBar.ts-entity-card > .ts-entity-banner.arearefnobottom > .underrefbanner {
	display: none;
}
body.ts-thirdparty-record-context .ts-entity-banner > .ts-entity-identity {
	display: grid;
	grid-template-columns: 56px minmax(0, 1fr);
	align-items: center;
	gap: var(--sp-4);
	flex: 1 1 430px;
	min-width: 0;
}
body.ts-thirdparty-record-context .ts-entity-banner .ts-entity-identity > .divphotoref {
	display: flex !important;
	align-items: center;
	justify-content: center;
	width: 56px !important;
	height: 56px !important;
	min-width: 56px;
	margin: 0 !important;
	border-radius: var(--r-lg);
	background: var(--c-accent-soft);
	color: var(--c-accent-ink);
}
body.ts-thirdparty-record-context .ts-entity-banner .ts-entity-identity > .refid {
	display: grid;
	grid-template-columns: auto auto minmax(0, 1fr);
	align-items: center;
	column-gap: var(--sp-2);
	row-gap: 3px;
	width: auto !important;
	max-width: none !important;
	margin: 0 !important;
	padding: 0 !important;
}
body.ts-thirdparty-record-context .ts-entity-banner .ts-entity-identity > .refid > .refidno {
	grid-column: 1 / -1;
}
body.ts-thirdparty-record-context .ts-entity-banner .ts-entity-identity > .refid > .statusref {
	grid-column: 2;
	grid-row: 1;
	display: inline-flex;
	justify-self: start;
	margin: 0;
}
body.ts-thirdparty-record-context .ts-entity-banner .ts-legacy-identity-source,
body.ts-thirdparty-record-context .ts-entity-banner .ts-entity-photo-unused {
	display: none !important;
}
body.ts-thirdparty-record-context .ts-entity-banner .refid {
	min-width: 0;
	font-size: 1.625rem;
	font-weight: 650;
	line-height: 1.25;
}
body.ts-thirdparty-record-context .ts-entity-banner .refidno,
body.ts-thirdparty-record-context .ts-entity-banner .refaddress {
	font-size: .8125rem;
	font-weight: 400;
	line-height: 1.55;
	color: var(--c-muted);
}
body.ts-thirdparty-record-context .ts-entity-banner .refaddress .address,
body.ts-thirdparty-record-context .ts-entity-banner .refaddress .paddingright,
body.ts-thirdparty-record-context .ts-entity-banner .refaddress > a {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	max-width: 100%;
	margin: 2px var(--sp-3) 2px 0;
}
body.ts-thirdparty-record-context .ts-entity-banner .refaddress [class*="fa-"] {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 17px;
	min-width: 17px;
	margin: 0 !important;
	padding: 0 !important;
}
body.ts-thirdparty-record-context .ts-entity-banner .pagination.paginationref {
	position: static;
	float: none;
	margin-left: auto;
	white-space: nowrap;
	opacity: .58;
}
body.ts-thirdparty-record-context .ts-entity-banner .paginationref a.ts-crumb-source {
	display: none !important;
}
body.ts-thirdparty-record-context .ts-entity-banner .statusref {
	margin-left: var(--sp-2);
}
body.ts-thirdparty-record-context div.tabBar.ts-entity-card > div.tabs[data-ts-placed="1"] {
	width: 100%;
	max-width: 100%;
	overflow-x: auto;
	overflow-y: hidden;
	white-space: nowrap;
	scrollbar-width: none;
}
body.ts-thirdparty-record-context div.tabBar.ts-entity-card > div.tabs[data-ts-placed="1"]::-webkit-scrollbar {
	display: none;
}
body.ts-thirdparty-record-context div.tabBar.ts-entity-card > div.tabs[data-ts-placed="1"] > * {
	flex: 0 0 auto;
}
body.ts-thirdparty-record-context .ts-record-tab-surface {
	margin: var(--sp-3) 0 var(--sp-4);
	border: 1px solid var(--c-border) !important;
	border-radius: var(--r-lg);
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
	overflow-x: auto;
}
body.ts-thirdparty-record-context .ts-record-tab-surface > table {
	margin: 0 !important;
	border: 0 !important;
	background: transparent !important;
}
body.ts-thirdparty-record-context .ts-record-tab-surface tr.liste_titre th,
body.ts-thirdparty-record-context .ts-record-tab-surface tr.liste_titre td {
	height: 48px;
	background: #f7f9fc !important;
	border-bottom: 1px solid var(--c-hairline) !important;
}
body.ts-thirdparty-record-context .ts-record-tab-surface tr.oddeven td,
body.ts-thirdparty-record-context .ts-record-tab-surface tr.pair td,
body.ts-thirdparty-record-context .ts-record-tab-surface tr.impair td {
	height: 48px;
	border-bottom: 1px solid var(--c-hairline) !important;
}
body.ts-thirdparty-record-context td.ts-record-tab-empty {
	height: 112px !important;
	padding: 28px !important;
	text-align: center !important;
	vertical-align: middle !important;
	color: var(--c-muted) !important;
	font-size: .875rem;
}
body.ts-thirdparty-record-context td.ts-record-tab-empty::before {
	display: block;
	margin: 0 auto 9px;
	color: var(--c-faint);
	font-family: "Font Awesome 5 Free";
	font-size: 20px;
	font-weight: 900;
	content: "\f1ea";
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
	body.ts-thirdparty-record-context div.tabBar.ts-entity-card > .ts-entity-banner {
		align-items: flex-start;
		padding: var(--sp-4);
	}
	body.ts-thirdparty-record-context .ts-entity-banner .pagination.paginationref {
		margin-left: 0;
	}
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
	/* One grid for the whole form. Every row -- paired or full width -- uses the
	   same three slots, so a control's x depends on the grid and never on whether
	   its field happens to own an icon.  label | adornment | control  */
	--tsf-label: 160px;
	--tsf-adorn: 26px;
	--tsf-gap: 28px;
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
	grid-template-columns: var(--tsf-label) minmax(0, 1fr);
	align-items: center;
	min-width: 0;
	min-height: 64px;
	box-sizing: border-box;
	padding: 12px 0;
	border: 0;
	border-bottom: 1px solid #f6f7fa;
	background: transparent;
}
form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-full {
	grid-column: 1 / -1;
	/* Same label slot as a paired row: this is what puts a full-width control on
	   the same axis as the left-hand control of the row above it. The 200px here
	   was the reason the two axes were 11px apart. */
	grid-template-columns: var(--tsf-label) minmax(0, 1fr);
}
form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-paired {
	grid-column: 1 / -1;
	grid-template-columns: var(--tsf-label) minmax(0, 1fr) var(--tsf-gap) var(--tsf-label) minmax(0, 1fr);
	column-gap: 0;
}
/* The centre gap is a real column, so the right-hand half starts at a stated
   position instead of wherever the left half happened to end. */
form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-paired > td:nth-child(3) { grid-column: 4; }
form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-paired > td:nth-child(4) { grid-column: 5; }
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
/* Removed: the label used to be padded when its field owned an icon, which moved
   the label instead of giving the icon a slot -- the per-field offset this pass
   replaces. The adornment column below reserves the space on every row. */
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value {
	position: relative;
	gap: 8px;
	color: var(--c-ink);
	font-size: 0.8125rem;
}
/* Every paired value reserves the same leading-icon and trailing-help slots.
   Controls therefore share an x-axis whether a particular field has zero, one,
   or both adornments. The native icons stay associated with their value cell. */
/* Applies to EVERY value cell, paired or full width: the adornment column exists
   whether or not the field has an icon, so the control's x never depends on it. */
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value {
	display: grid;
	grid-template-columns: var(--tsf-adorn) minmax(0, 1fr) 16px;
	column-gap: 0;
	align-items: center;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > *:not([type="hidden"]):not(.ts-form-leading-icon):not(.ts-form-help):not(.pictofixedwidth):not(img) {
	grid-column: 2;
	min-width: 0;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > span.ts-form-leading-icon,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > span.pictofixedwidth:first-child,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > img.pictofixedwidth:first-child {
	position: static;
	grid-column: 1;
	grid-row: 1;
	justify-self: center;
	width: auto;
	height: auto;
	margin: 0;
	color: var(--c-muted);
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > .ts-form-help {
	position: static;
	grid-column: 3;
	grid-row: 1;
	justify-self: center;
	width: auto;
	height: auto;
	margin: 0;
	color: var(--c-muted);
}
body#mainbody form.ts-modern-form table.ts-modern-form-table tr:has(textarea),
body#mainbody form.ts-modern-form table.ts-modern-form-table tr:has(.select2-selection--multiple) {
	align-items: start;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table tr:has(textarea) td.ts-form-label {
	padding-top: 12px;
}

/* Components' generic form alignment reserves an absolute icon gutter. The
   structured grid has a real flex gap, so the original pictogram can return to
   normal flow and remain predictably attached to its control. */
/* Removed: full-width rows used to hang their icon in an absolute gutter at
   -28px while paired rows kept theirs in flow, which is why the two row types
   produced different control axes. Both now use the adornment column above. */

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
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > div:has(> .select2-container) {
	position: relative;
	display: block;
	flex: 1 1 auto;
	width: 100%;
	min-width: 0;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > span > .select2-container {
	width: 100% !important;
	max-width: none !important;
	min-width: 0 !important;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > div > .select2-container {
	width: 100% !important;
	max-width: none !important;
	min-width: 0 !important;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > div > .fa-info-circle {
	position: absolute;
	<?php echo $right; ?>: -20px;
	top: 0;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 16px;
	height: 40px;
	margin: 0;
	color: var(--c-muted);
}
form.ts-modern-form table.ts-modern-form-table td.ts-form-value > table.nobordernopadding input {
	width: 100% !important;
	max-width: none !important;
	box-sizing: border-box;
}
form.ts-modern-form table.ts-modern-form-table input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]):not([type="file"]):not([type="submit"]):not([type="button"]):not(.select2-search__field),
form.ts-modern-form table.ts-modern-form-table select {
	height: 40px;
	padding-<?php echo $left; ?>: 12px !important;
	padding-<?php echo $right; ?>: 12px !important;
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
	padding: 0 12px;
	border-color: #e0e6ef !important;
	border-radius: 8px !important;
}
form.ts-modern-form .select2-container--default.select2-container--focus .select2-selection--single,
form.ts-modern-form .select2-container--default.select2-container--focus .select2-selection--multiple,
form.ts-modern-form .select2-container--default.select2-container--open .select2-selection--single,
form.ts-modern-form .select2-container--default.select2-container--open .select2-selection--multiple {
	border-color: var(--c-accent) !important;
	box-shadow: 0 0 0 3px color-mix(in srgb, var(--c-accent) 14%, transparent);
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
	flex: 1 1 40px;
	min-width: 24px;
	height: 30px;
	margin: 0;
}
body#mainbody form.ts-modern-form .select2-container--default .select2-search__field {
	width: 100% !important;
	min-width: 24px !important;
	height: 30px !important;
	margin: 0 !important;
	padding: 0 3px !important;
	border: 0 !important;
	border-radius: 0 !important;
	background: transparent !important;
	box-shadow: none !important;
	outline: 0 !important;
}
body#mainbody form.ts-modern-form .select2-selection--multiple .select2-search--inline,
body#mainbody form.ts-modern-form .select2-selection--multiple .select2-search__field {
	border: 0 !important;
	border-radius: 0 !important;
	background: transparent !important;
	box-shadow: none !important;
	outline: 0 !important;
	appearance: none;
}
body .select2-dropdown.ts-form-select2-dropdown {
	border: 1px solid #e0e6ef;
	border-radius: 9px;
	background: var(--c-surface);
	box-shadow: 0 14px 34px rgba(15, 23, 42, .14), 0 3px 8px rgba(15, 23, 42, .06);
	overflow: hidden;
	z-index: 3600;
}
body .ts-form-select2-root { margin: 0 !important; transform: none !important; }
body .ts-form-select2-dropdown .select2-search--dropdown { padding: 10px; }
body .ts-form-select2-dropdown .select2-search__field {
	height: 38px;
	padding: 0 12px;
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
body .ts-form-select2-dropdown .select2-results__options {
	max-width: 100%;
	max-height: 300px;
	padding: 4px;
	scrollbar-width: thin;
	scrollbar-color: #cbd2dc transparent;
}
body .ts-form-select2-dropdown .select2-results__options::-webkit-scrollbar { width: 6px; }
body .ts-form-select2-dropdown .select2-results__options::-webkit-scrollbar-thumb { border-radius: 8px; background: #cbd2dc; }
body .ts-form-select2-dropdown .select2-results__option {
	display: flex;
	align-items: center;
	min-height: 36px;
	border-radius: 6px;
}
body .ts-form-select2-dropdown .select2-results__option--highlighted { background: var(--c-accent-soft); color: var(--c-accent-ink); }
body .ts-form-select2-dropdown .select2-results__option[aria-selected="true"] { font-weight: 600; }
body .ts-form-select2-dropdown .ts-form-select2-empty-option { display: none !important; }
body .ts-form-select2-dropdown-compact .select2-search--dropdown { display: none; }
body .ts-form-select2-dropdown-compact .select2-results__options {
	max-height: min(320px, calc(100vh - 32px));
	overflow-y: auto;
}
body .ts-form-select2-dropdown[data-ts-select-name="commercial[]"] .select2-results__option {
	min-height: 52px;
	max-height: 56px;
	padding: 8px 10px;
	overflow: hidden;
}
body .ts-form-select2-dropdown[data-ts-select-name="commercial[]"] .select2-results__option img {
	flex: 0 0 30px;
	width: 30px !important;
	height: 30px !important;
	margin-<?php echo $right; ?>: 10px !important;
	border-radius: 50%;
	object-fit: cover;
}

/* Tags/categories retain Dolibarr's jQuery UI dialog, iframe and routes. This
   scopes COMMAND's shell to those two existing dialog instances only. */
body:has(iframe#iframedialogcustcats) > .ui-widget-overlay,
body:has(iframe#iframedialogsuppcats) > .ui-widget-overlay {
	position: fixed !important;
	inset: 0 !important;
	background: rgba(15, 23, 42, .52) !important;
	opacity: 1 !important;
	z-index: 3400 !important;
}
body > .ui-dialog:has(iframe#iframedialogcustcats),
body > .ui-dialog:has(iframe#iframedialogsuppcats) {
	position: fixed !important;
	top: 50% !important;
	<?php echo $left; ?>: 50% !important;
	width: min(1040px, calc(100vw - 32px)) !important;
	height: min(620px, 84vh) !important;
	margin: 0 !important;
	transform: translate(-50%, -50%) !important;
	border: 1px solid #e0e6ef !important;
	border-radius: 12px !important;
	background: var(--c-surface) !important;
	box-shadow: 0 28px 70px rgba(15, 23, 42, .28) !important;
	overflow: hidden !important;
	z-index: 3500 !important;
}
body > .ui-dialog.ts-category-dialog-empty-state:has(iframe#iframedialogcustcats),
body > .ui-dialog.ts-category-dialog-empty-state:has(iframe#iframedialogsuppcats) { height: min(460px, 84vh) !important; }
body > .ui-dialog.ts-category-dialog-create:has(iframe#iframedialogcustcats),
body > .ui-dialog.ts-category-dialog-create:has(iframe#iframedialogsuppcats) { height: min(820px, 84vh) !important; }
body > .ui-dialog:has(iframe#iframedialogcustcats) .ui-dialog-titlebar,
body > .ui-dialog:has(iframe#iframedialogsuppcats) .ui-dialog-titlebar {
	display: flex;
	align-items: center;
	min-height: 64px;
	padding: 0 22px !important;
	border: 0 !important;
	border-bottom: 1px solid #e7e9ee !important;
	border-radius: 0 !important;
	background: #fff !important;
}
body > .ui-dialog:has(iframe#iframedialogcustcats) .ui-dialog-title,
body > .ui-dialog:has(iframe#iframedialogsuppcats) .ui-dialog-title { color: var(--c-ink) !important; font-size: 1.1875rem !important; font-weight: 600 !important; }
body > .ui-dialog:has(iframe#iframedialogcustcats) .ui-dialog-titlebar-close,
body > .ui-dialog:has(iframe#iframedialogsuppcats) .ui-dialog-titlebar-close {
	position: absolute !important;
	top: 14px !important;
	<?php echo $right; ?>: 16px !important;
	display: inline-flex !important;
	align-items: center;
	justify-content: center;
	width: 36px !important;
	height: 36px !important;
	margin: 0 !important;
	border: 0 !important;
	border-radius: 8px !important;
	background: transparent !important;
}
body > .ui-dialog:has(iframe#iframedialogcustcats) .ui-dialog-titlebar-close:hover,
body > .ui-dialog:has(iframe#iframedialogsuppcats) .ui-dialog-titlebar-close:hover { background: var(--c-accent-soft) !important; }
body > .ui-dialog:has(iframe#iframedialogcustcats) .ui-dialog-titlebar-close:focus-visible,
body > .ui-dialog:has(iframe#iframedialogsuppcats) .ui-dialog-titlebar-close:focus-visible { outline: 2px solid var(--c-accent); outline-offset: 2px; }
body > .ui-dialog:has(iframe#iframedialogcustcats) .ui-dialog-content,
body > .ui-dialog:has(iframe#iframedialogsuppcats) .ui-dialog-content {
	box-sizing: border-box;
	width: 100% !important;
	height: calc(100% - 64px) !important;
	max-height: none !important;
	padding: 0 !important;
	overflow: hidden !important;
}
body > .ui-dialog:has(iframe#iframedialogcustcats) iframe,
body > .ui-dialog:has(iframe#iframedialogsuppcats) iframe { display: block; width: 100% !important; height: 100% !important; border: 0 !important; }

body.ts-category-dialog-page { min-width: 0 !important; padding-top: 0 !important; background: #f7f8fa !important; overflow-x: hidden; }
body.ts-category-dialog-page .fiche { box-sizing: border-box; width: min(100%, 820px); min-width: 0; margin-inline: auto; padding: 22px 24px 24px !important; }
body.ts-category-dialog-page .ts-pagehead { align-items: center; margin: 0 0 14px; }
body.ts-category-dialog-page .ts-pagehead-title { display: block; }
body.ts-category-dialog-page .ts-pagehead div.titre { font-size: 1.125rem; font-weight: 600; }
body.ts-category-dialog-page .ts-pagehead-title.ts-category-redundant-title { display: none !important; }
body.ts-category-dialog-page .ts-pagehead-actions { margin-<?php echo $left; ?>: auto; }
body.ts-category-dialog-page .ts-pagehead-actions { display: flex; width: 100%; align-items: center; justify-content: space-between; gap: 12px; }
body.ts-category-dialog-page table.table-fiche-title { box-sizing: border-box; height: 46px; margin: 0 0 14px; padding: 0 !important; }
body.ts-category-dialog-page table.table-fiche-title,
body.ts-category-dialog-page table.table-fiche-title tbody,
body.ts-category-dialog-page table.table-fiche-title tr { display: block; width: 100%; }
body.ts-category-dialog-page table.table-fiche-title td.col-picto,
body.ts-category-dialog-page table.table-fiche-title td.col-title { display: none; }
body.ts-category-dialog-page table.table-fiche-title td.col-right { display: flex !important; align-items: center; justify-content: flex-end; width: 100% !important; height: 46px; padding: 0 !important; text-align: right; }
body.ts-category-dialog-page table.table-fiche-title .pagination,
body.ts-category-dialog-page table.table-fiche-title .pagination ul { position: static !important; display: inline-flex; align-items: center; height: 40px; float: none !important; margin: 0; padding: 0; transform: none !important; }
body.ts-category-dialog-page .ts-category-view-switch {
	display: inline-flex;
	align-items: center;
	padding: 3px;
	border: 1px solid #e0e6ef;
	border-radius: 9px;
	background: #fff;
}
body.ts-category-dialog-page table.table-fiche-title:has(.col-title:empty):not(:has(a)) { display: none !important; }
body.ts-category-dialog-page .ts-category-view-option {
	display: inline-flex !important;
	align-items: center;
	justify-content: center;
	gap: 7px;
	min-width: 92px;
	height: 34px;
	margin: 0 !important;
	padding: 0 10px !important;
	border: 0 !important;
	border-radius: 7px !important;
	background: transparent !important;
	color: var(--c-muted) !important;
	font-size: .8125rem;
	font-weight: 550;
}
body.ts-category-dialog-page .ts-category-view-option.btnTitleSelected { background: var(--c-accent-soft) !important; color: var(--c-accent) !important; }
body.ts-category-dialog-page .ts-category-view-option:hover { background: #f5f6fa !important; color: var(--c-ink-2) !important; }
body.ts-category-dialog-page .ts-category-view-option .btnTitle-icon { margin: 0 !important; padding: 0 !important; font-size: .8125rem; }
body.ts-category-dialog-page .ts-pagehead .ts-primary-action {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 7px;
	height: 40px;
	padding: 0 15px !important;
	border: 1px solid var(--c-accent) !important;
	border-radius: 8px !important;
	background: var(--c-accent) !important;
	color: #fff !important;
	box-shadow: 0 3px 8px var(--c-accent-ring);
}
body.ts-category-dialog-page .fichecenter.ts-category-dialog-list {
	border: 1px solid #e7e9ee;
	border-radius: 10px;
	background: #fff;
	box-shadow: 0 4px 14px rgba(15, 23, 42, .05);
	overflow: hidden;
}
body.ts-category-dialog-page .ts-category-dialog-list table.liste { margin: 0 !important; border: 0 !important; background: transparent !important; }
body.ts-category-dialog-page table.liste > tbody > tr.liste_titre,
body.ts-category-dialog-page table.liste > tbody > tr.liste_titre_filter { display: none !important; }
body.ts-category-dialog-page .ts-category-dialog-list tr.oddeven td { min-height: 46px; border-bottom: 1px solid #f0f2f5; }
body.ts-category-dialog-page .ts-category-dialog-list tr.oddeven:hover td { background: #fafbff; }
body.ts-category-dialog-page table.liste tr.ts-category-data-row td.ts-category-row-main { height: 48px; padding: 0 10px 0 16px !important; }
body.ts-category-dialog-page table.liste tr.ts-category-data-row td.ts-category-row-hidden { display: none !important; }
body.ts-category-dialog-page .ts-category-list-row-content { display: flex; width: 100%; min-width: 0; align-items: center; gap: 12px; }
body.ts-category-dialog-page .ts-category-list-row-content > a.classforajaxtooltip { display: inline-flex; min-width: 0; align-items: center; gap: 8px; color: var(--c-ink-2) !important; font-size: .875rem; font-weight: 550 !important; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
body.ts-category-dialog-page .ts-category-list-row-content > a.classforajaxtooltip .paddingright { padding: 0 !important; color: var(--c-accent); }
body.ts-category-dialog-page .ts-category-row-actions { display: inline-flex; flex: 0 0 auto; align-items: center; gap: 4px; margin-<?php echo $left; ?>: auto; }
body.ts-category-dialog-page .ts-category-row-actions a,
body.ts-category-dialog-page #iddivjstree a.editfielda,
body.ts-category-dialog-page #iddivjstree a.deletefilelink { display: inline-flex; width: 34px; height: 34px; align-items: center; justify-content: center; border-radius: 8px; color: var(--c-muted); }
body.ts-category-dialog-page .ts-category-row-actions a:hover,
body.ts-category-dialog-page #iddivjstree a.editfielda:hover,
body.ts-category-dialog-page #iddivjstree a.deletefilelink:hover { background: var(--c-accent-soft); color: var(--c-accent); }
body.ts-category-dialog-page .ts-category-dialog-list .noborderoncategories { background: transparent !important; }
body.ts-category-dialog-page .ts-category-dialog-list #iddivjstree,
body.ts-category-dialog-page .ts-category-dialog-list #iddivjstree ul { margin: 0; padding: 0; list-style: none; background: none !important; }
body.ts-category-dialog-page .ts-category-dialog-list #iddivjstree li { box-sizing: border-box; min-height: 46px; padding: 0 0 0 18px; background: none !important; }
body.ts-category-dialog-page .ts-category-dialog-list #iddivjstree li li { padding-<?php echo $left; ?>: 24px; }
body.ts-category-dialog-page .ts-category-dialog-list #iddivjstree table { width: 100%; min-height: 46px; margin: 0; background: transparent; }
body.ts-category-dialog-page .ts-category-dialog-list #iddivjstree td { height: 46px; padding: 0 8px; text-align: <?php echo $left; ?>; vertical-align: middle; }
body.ts-category-dialog-page .ts-category-dialog-list #iddivjstree td.right,
body.ts-category-dialog-page .ts-category-dialog-list #iddivjstree td.center { width: 34px !important; text-align: center; }
body.ts-category-dialog-page .ts-category-dialog-list #iddivjstree .noborderoncategories { background: transparent !important; }
body.ts-category-dialog-page .ts-category-dialog-list #iddivjstree .noborderoncategories > a { display: inline-flex; align-items: center; gap: 7px; color: var(--c-ink-2) !important; font-size: .8125rem; }
body.ts-category-dialog-page .ts-category-dialog-list #iddivjstree .noborderoncategories > a .paddingright { padding: 0 !important; color: var(--c-accent); }
body.ts-category-dialog-page td.ts-category-dialog-empty { height: 138px; padding: 18px 24px !important; text-align: center; }
body.ts-category-dialog-page td.ts-category-dialog-empty > table { display: flex; flex-direction: column; align-items: center; gap: 10px; margin: auto; }
body.ts-category-dialog-page .ts-category-empty-icon { display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 10px; background: var(--c-accent-soft); color: var(--c-accent); font-size: 1rem; }
body.ts-category-dialog-page .ts-category-empty-row .ts-category-empty-icon { margin: 0 auto 10px; }
body.ts-category-dialog-page .ts-category-empty-row .opacitymedium { display: block; }
body.ts-category-dialog-page .ts-category-empty-content tbody,
body.ts-category-dialog-page .ts-category-empty-content tr,
body.ts-category-dialog-page .ts-category-empty-content td { display: contents; }
body.ts-category-dialog-page td.ts-category-dialog-empty img { display: none; }
body.ts-category-dialog-page td.ts-category-dialog-empty .opacitymedium { max-width: 520px; color: var(--c-muted); font-size: .875rem; line-height: 1.55; }

body.ts-category-dialog-create-page .fiche { width: min(100%, 760px); }
body.ts-category-dialog-create-page form.ts-category-create-form > table.table-fiche-title { display: none !important; }
body.ts-category-dialog-create-page .tabBar { box-sizing: border-box; margin: 0; padding: 22px 24px; border: 1px solid #e7e9ee; border-radius: 10px; background: #fff; box-shadow: 0 4px 14px rgba(15,23,42,.05); }
body.ts-category-dialog-create-page .tabBar table.border { display: block; width: 100%; }
body.ts-category-dialog-create-page .tabBar table.border > tbody { display: grid; width: 100%; grid-template-columns: minmax(140px, 150px) minmax(0, 1fr); column-gap: 24px; row-gap: 14px; }
body.ts-category-dialog-create-page .tabBar table.border > tbody > tr.ts-category-form-row { display: contents; }
body.ts-category-dialog-create-page .tabBar table.border td.ts-category-form-label { display: flex; width: auto !important; min-width: 0; min-height: 40px; align-items: center; padding: 0 !important; border: 0; color: var(--c-ink-2); font-size: .8125rem; font-weight: 600; vertical-align: middle; }
body.ts-category-dialog-create-page .tabBar table.border td.ts-category-form-label.tdtop { align-items: flex-start; padding-top: 11px !important; }
body.ts-category-dialog-create-page .tabBar table.border td.ts-category-form-control { display: block; min-width: 0; padding: 0 !important; border: 0; vertical-align: top; }
body.ts-category-dialog-create-page .tabBar input:not([type="hidden"]):not([type="submit"]):not([type="button"]),
body.ts-category-dialog-create-page .tabBar select { box-sizing: border-box; width: 100%; height: 40px; padding: 0 12px; border: 1px solid #e0e6ef; border-radius: 8px; }
body.ts-category-dialog-create-page .tabBar #position { width: 120px !important; min-width: 0 !important; max-width: none !important; text-align: <?php echo $left; ?>; }
body.ts-category-dialog-create-page .tabBar .cke { width: 100% !important; border: 1px solid #e0e6ef !important; border-radius: 8px !important; overflow: hidden; }
body.ts-category-dialog-create-page .tabBar .cke_top { padding: 8px 8px 4px !important; }
body.ts-category-dialog-create-page .tabBar .cke_toolbox { display: flex !important; flex-wrap: wrap; align-items: flex-start; }
body.ts-category-dialog-create-page .tabBar .cke_toolbar { float: none !important; }
body.ts-category-dialog-create-page .tabBar .cke_toolbar_break { display: none !important; }
body.ts-category-dialog-create-page .tabBar .cke_toolgroup { margin: 0 4px 4px 0 !important; }
body.ts-category-dialog-create-page .tabBar .cke_button { padding: 4px !important; }
body.ts-category-dialog-create-page .tabBar .cke_contents { height: 210px !important; }
body.ts-category-dialog-create-page .ts-category-color-control { display: inline-flex; width: min(100%, 380px); height: 40px; align-items: stretch; overflow: hidden; border: 1px solid #e0e6ef; border-radius: 8px; background: #fff; }
body.ts-category-dialog-create-page .ts-category-color-control #colorpickercolor { flex: 1 1 auto; width: auto !important; min-width: 0; height: 38px; border: 0 !important; border-radius: 0 !important; }
body.ts-category-dialog-create-page .ts-category-color-control > .jPicker { display: inline-flex !important; flex: 0 0 40px; width: 40px !important; height: 40px !important; align-items: center; justify-content: center; border-<?php echo $left; ?>: 1px solid #e7e9ee; }
body.ts-category-dialog-create-page .ts-category-color-control > .jPicker .Icon { display: block !important; width: 40px !important; height: 40px !important; margin: 0 !important; }
body.ts-category-dialog-create-page .ts-category-color-control > .jPicker .Icon > span { width: 40px !important; height: 40px !important; }
body.ts-category-dialog-create-page .ts-category-form-control > .pictofixedwidth[title] { display: none; }
body.ts-category-dialog-create-page .ts-category-parent-container { box-sizing: border-box; width: 100% !important; }
body.ts-category-dialog-create-page .ts-category-parent-container .select2-selection { box-sizing: border-box; width: 100%; height: 40px !important; border: 1px solid #e0e6ef !important; border-radius: 8px !important; }
body.ts-category-dialog-create-page .ts-category-parent-container .select2-selection__rendered { height: 38px; padding-inline: 12px 38px !important; line-height: 38px !important; color: var(--c-ink-2); }
body.ts-category-dialog-create-page .ts-category-parent-container .select2-selection__arrow { width: 36px; height: 38px; }
body.ts-category-dialog-create-page .select2-dropdown.ts-category-select2-dropdown { border: 1px solid #e0e6ef !important; border-radius: 8px !important; background: #fff; box-shadow: 0 12px 28px rgba(15,23,42,.16); overflow: hidden; }
body.ts-category-dialog-create-page .ts-category-select2-dropdown .select2-search--dropdown { padding: 8px; }
body.ts-category-dialog-create-page .ts-category-select2-dropdown .select2-search--dropdown.ts-category-select2-search-hidden { display: none !important; }
body.ts-category-dialog-create-page .ts-category-select2-dropdown .select2-search__field { box-sizing: border-box; width: 100%; height: 38px; padding: 0 11px; border: 1px solid #e0e6ef; border-radius: 8px; outline: 0; }
body.ts-category-dialog-create-page .ts-category-select2-dropdown .select2-results__options { max-height: 280px; overflow-y: auto; }
body.ts-category-dialog-create-page .ts-category-select2-dropdown .select2-results__option { display: flex; min-height: 38px; align-items: center; padding: 0 12px; font-size: .8125rem; }
body.ts-category-dialog-create-page .ts-category-select2-dropdown .select2-results__option--highlighted[aria-selected] { background: var(--c-accent-soft); color: var(--c-accent); }
body.ts-category-dialog-create-page .center:has(input[type="submit"]) { display: flex; justify-content: center; gap: 10px; margin-top: 24px; padding-bottom: 4px; }
body.ts-category-dialog-create-page .center:has(input[type="submit"]) input.button { min-width: 110px; height: 40px; margin: 0; border-radius: 8px; }
body.ts-category-dialog-create-page .center:has(input[type="submit"]) input[name="creation"] { border: 1px solid var(--c-accent) !important; background: var(--c-accent) !important; color: #fff !important; box-shadow: 0 3px 8px var(--c-accent-ring); }
body.ts-category-dialog-create-page .center:has(input[type="submit"]) input[name="cancel"] { border: 1px solid #e0e6ef !important; background: #fff !important; color: var(--c-ink-2) !important; }
body .ts-form-select2-dropdown[data-ts-select-name="country_id"] .select2-results__option img,
body .ts-form-select2-dropdown[data-ts-select-name="state_id"] .select2-results__option img {
	flex: 0 0 20px;
	width: 20px !important;
	max-width: 20px !important;
	margin-<?php echo $right; ?>: 8px !important;
	object-fit: contain;
}
@media only screen and (max-width: 900px) {
	body > .ui-dialog:has(iframe#iframedialogcustcats),
	body > .ui-dialog:has(iframe#iframedialogsuppcats) { width: calc(100vw - 24px) !important; max-height: 86vh !important; }
	body.ts-category-dialog-page .fiche { width: 100%; padding: 16px !important; }
	body.ts-category-dialog-page .ts-pagehead { align-items: flex-start; gap: 12px; flex-wrap: wrap; }
	body.ts-category-dialog-page .ts-pagehead-actions { margin-<?php echo $left; ?>: 0; }
	body.ts-category-dialog-create-page .fiche { padding-inline: 16px !important; }
	body.ts-category-dialog-create-page .tabBar { padding: 18px; }
	body.ts-category-dialog-create-page .tabBar table.border > tbody { grid-template-columns: 1fr; row-gap: 7px; }
	body.ts-category-dialog-create-page .tabBar table.border td.ts-category-form-label { min-height: 26px; align-items: flex-end; }
	body.ts-category-dialog-create-page .tabBar table.border td.ts-category-form-label.tdtop { padding-top: 6px !important; }
}
form.ts-modern-form .ts-form-select2-placeholder { color: var(--c-faint); }

body#mainbody form.ts-modern-form tr.ts-form-choice-row td.ts-form-value {
	gap: 24px;
	flex-wrap: nowrap;
}
form.ts-modern-form tr.ts-form-choice-row td.ts-form-value > .spannature {
	display: inline-flex;
	align-items: center;
	flex: 0 0 auto;
	min-height: 32px;
	margin: 0;
	padding: 0;
	border: 0;
	background: transparent;
	box-shadow: none;
}
form.ts-modern-form tr.ts-form-choice-row td.ts-form-value > .spannature > label {
	display: inline-flex;
	align-items: center;
	gap: 8px;
	margin: 0;
	font-size: 0.8125rem;
	font-weight: 520;
	line-height: 1.25;
	cursor: pointer;
}
form.ts-modern-form input[type="checkbox"],
form.ts-modern-form input[type="radio"] {
	width: 17px;
	height: 17px;
	min-width: 17px;
	margin: 0 !important;
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

/* Width roles affect only the control inside the established value track. They
   deliberately do not alter row columns, labels, icon slots, or help slots. */
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-width-compact > input:not([type="hidden"]),
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-width-compact > select,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-width-compact > .select2-container,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-width-compact > span:has(> .select2-container),
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-width-compact .select2-container {
	width: min(100%, 340px) !important;
	max-width: 340px !important;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value.ts-form-width-large > input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]):not([type="file"]):not([type="submit"]):not([type="button"]),
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-width-large > select,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-width-large > .select2-container,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-width-large > span:has(> .select2-container),
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-width-large .select2-container {
	width: min(100%, 820px) !important;
	max-width: 820px !important;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value.ts-form-width-large.ts-form-control--large-email > input[name="email"]:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]):not([type="file"]):not([type="submit"]):not([type="button"]) {
	width: min(100%, 760px) !important;
	max-width: 760px !important;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value.ts-form-width-medium > input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]):not([type="file"]):not([type="submit"]):not([type="button"]),
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-width-medium > select,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-width-medium > .select2-container,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-width-medium > table.nobordernopadding,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-width-medium > span:has(> .select2-container) {
	width: min(100%, 320px) !important;
	max-width: 320px !important;
}
body#mainbody form.ts-modern-form .select2-container.ts-form-control-compact {
	width: min(100%, 340px) !important;
	max-width: 340px !important;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-width-full > input:not([type="hidden"]),
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-width-full > textarea,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-width-full > .select2-container,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-width-full > span:has(> .select2-container) {
	width: 100% !important;
	max-width: none !important;
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
	gap: 12px;
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
		grid-template-columns: 150px minmax(0, 1fr);
	}
	form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-paired {
		grid-template-columns: 120px minmax(0, 1fr) 120px minmax(0, 1fr);
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
	body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value {
		box-sizing: border-box;
		padding-<?php echo $left; ?>: 28px;
		padding-<?php echo $right; ?>: 20px;
	}
	form.ts-modern-form table.ts-modern-form-table td.ts-form-label { padding-top: 0; }
	body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-label:has(+ td.ts-form-value-has-leading) {
		padding-<?php echo $right; ?>: 12px;
	}
	body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > span.ts-form-leading-icon,
	body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > span.pictofixedwidth:first-child,
	body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > img.pictofixedwidth:first-child,
	body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > .ts-form-help {
		position: absolute;
		flex: 0 0 20px;
	}
	body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > span.ts-form-leading-icon,
	body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > span.pictofixedwidth:first-child,
	body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > img.pictofixedwidth:first-child {
		<?php echo $left; ?>: 0;
	}
	body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > .ts-form-help {
		<?php echo $right; ?>: 0;
	}
	body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > div > .fa-info-circle {
		<?php echo $right; ?>: 0;
	}
	body#mainbody form.ts-modern-form tr.ts-form-compound-incoterms td.ts-form-value {
		grid-template-columns: minmax(0, 1fr);
	}
	form.ts-modern-form tr.ts-form-compound-incoterms td.ts-form-value > .select2-container,
	form.ts-modern-form tr.ts-form-compound-incoterms td.ts-form-value > input[name="location_incoterms"] { grid-column: 1; }
	form.ts-modern-form table.ts-modern-form-table tr.ts-form-row.hideonsmartphone { display: grid !important; }
	form.ts-modern-form .ts-modern-form-actions { align-items: stretch; }
	form.ts-modern-form .ts-modern-form-actions input.button { flex: 1 1 0; min-width: 0; }
}

@media only screen and (max-width: 680px) {
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
	display: grid;
	grid-template-columns: 40px minmax(0, 1fr);
	grid-template-rows: auto auto;
	align-items: flex-start;
	column-gap: 12px;
	row-gap: 0;
	min-width: 0;
	min-height: 160px;
	height: 100%;
	padding: 16px;
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
	grid-column: 1;
	grid-row: 1;
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
	display: contents;
}
.ts-kanban-head {
	grid-column: 2;
	grid-row: 1;
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
	margin-top: 6px;
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
.ts-kanban-barcode {
	display: block;
	margin-top: 6px;
	color: var(--c-muted);
	font-size: .75rem;
	font-weight: 500;
	line-height: 1.25;
}
.ts-kanban-barcode[hidden] { display: none; }
.ts-kanban-details {
	grid-column: 1 / -1;
	grid-row: 2;
	display: grid;
	gap: 6px;
	min-width: 0;
	margin-top: 10px;
	padding-bottom: 11px;
}
.ts-kanban-detail {
	display: grid;
	grid-template-columns: 40px minmax(0, 1fr);
	align-items: start;
	column-gap: 12px;
	row-gap: 0;
	min-width: 0;
	padding: 0;
	color: var(--c-muted);
	font-size: .8125rem;
	line-height: 1.3;
}
.ts-kanban-detail > .fas {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	justify-self: center;
	width: 18px;
	height: 18px;
	margin: 0;
	color: var(--c-faint);
	font-size: .75rem;
	text-align: center;
}
.ts-kanban-detail-icon {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	justify-self: center;
	width: 18px;
	height: 18px;
	overflow: hidden;
	border-radius: 50%;
	color: var(--c-faint);
}
.ts-kanban-detail-icon img { width: 18px; height: 18px; object-fit: cover; }
.ts-kanban-detail-icon .fas { font-size: .75rem; }
.ts-kanban-detail-value {
	display: block;
	min-width: 0;
	overflow: hidden;
	color: var(--c-ink-2);
	text-overflow: ellipsis;
	white-space: nowrap;
}
.ts-kanban-address .ts-kanban-detail-value {
	display: -webkit-box;
	-webkit-box-orient: vertical;
	-webkit-line-clamp: 2;
	white-space: normal;
}
.ts-kanban-representative { color: var(--c-ink-2); font-weight: 550; }
.ts-kanban-representative:hover { color: var(--c-accent-ink); }
.ts-kanban-status {
	position: absolute;
	right: 16px;
	bottom: 14px;
	display: inline-flex;
	margin: 0;
}
.ts-kanban-status .badge-status { min-height: 22px; padding: 1px 9px; }
.ts-kanban-bulk-selection { display: none !important; }
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


/* ts-form-value > *:first-child leading space
   Some values wrap their control in a span that carries Dolibarr's own spacing
   utilities (paddinglarge, marginrightonly, ...). That padding sat before the
   control and pushed it off the column the grid had just established -- the
   radio group and the incoterm select were both doing this. The grid owns the
   leading space now, so the first wrapper inside a value cell contributes none.
   Trailing spacing is left alone: it separates a control from what follows it. */
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > span:first-child:not(.select2-container),
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > div:first-child,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > table:first-child {
	margin-<?php echo $left; ?>: 0;
	padding-<?php echo $left; ?>: 0;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > span:first-child > *:first-child {
	margin-<?php echo $left; ?>: 0;
}


/* deterministic value-cell columns
   The value cell has to resolve to exactly three columns for every field, or the
   control's x depends on what the field happens to contain. Two ways it was not:
   a 24px column-gap inherited from the row pushed controls to cell+26+24, and a
   cell whose children auto-placed produced a 335px first column instead of the
   26px adornment slot.

   So the template and gap are stated here, and every child is assigned a column
   explicitly: adornments to 1, help to 3, and everything else -- including the
   hidden originals select2 leaves behind -- to 2. Nothing is left to auto
   placement, which is what made this field-dependent. */
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value {
	grid-template-columns: var(--tsf-adorn) minmax(0, 1fr) 16px;
	column-gap: 0;
	gap: 0;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > * {
	grid-column: 2;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > span.ts-form-leading-icon,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > span.pictofixedwidth:first-child,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > img.pictofixedwidth:first-child {
	grid-column: 1;
	grid-row: 1;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > .ts-form-help {
	grid-column: 3;
	grid-row: 1;
}

/* Help belongs to the control, not to the value track's far boundary. Only
   cells that actually contain help use this five-track composition. The row
   axes and the universal leading-adornment column remain unchanged. */
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value-has-help.ts-form-width-compact {
	grid-template-columns: var(--tsf-adorn) minmax(0, 340px) 8px 16px minmax(0, 1fr);
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value-has-help.ts-form-width-medium {
	grid-template-columns: var(--tsf-adorn) minmax(0, 320px) 8px 16px minmax(0, 1fr);
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value-has-help.ts-form-width-large {
	grid-template-columns: var(--tsf-adorn) minmax(0, 820px) 8px 16px minmax(0, 1fr);
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value-has-help > .ts-form-help {
	grid-column: 4;
	justify-self: start;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 16px !important;
	height: 40px;
	margin: 0 !important;
}
@media only screen and (max-width: 767px) {
	body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value-has-help > .ts-form-help {
		position: static !important;
		<?php echo $left; ?>: auto !important;
		<?php echo $right; ?>: auto !important;
		top: auto !important;
	}
}


/* compact enum selects
   Sized by what the control is for, not by how much room happens to be free. A
   short fixed list gets a readable width and stops; a lookup may use the value
   area. Both are capped so neither stretches across the whole form. */
/* The generic control rule above sets every control to the full value width from
   a deeper selector, so the enum cap has to be stated at the same depth to be
   seen at all -- otherwise a seven-item list keeps stretching the whole form. */
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > select.ts-enum,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > .ts-enum-c,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > span:has(> .ts-enum-c) {
	width: min(100%, 340px) !important;
	max-width: 340px !important;
	justify-self: start;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > select.ts-lookup,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > .ts-lookup-c {
	width: 100% !important;
	max-width: 100% !important;
}

/* A menu for a handful of values should be the height of those values. */
.select2-container--open .select2-dropdown {
	border-radius: var(--r);
	border: 1px solid var(--c-border);
	box-shadow: var(--sh-md);
	overflow: hidden;
}
.select2-container--open .select2-results__option {
	min-height: 36px;
	display: flex;
	align-items: center;
	font-size: 0.8125rem;
}
/* Height follows the content and only scrolls once there is genuinely more than
   fits; a short list therefore shows no scrollbar and no empty panel. */
.select2-container--open .select2-results > .select2-results__options {
	max-height: 320px;
}
/* No search box on a list short enough to read at a glance. select2 still renders
   the field for a single select, so it is removed for the compact ones only. */
.select2-container--open.ts-enum-open .select2-search--dropdown { display: none; }


/* enum width driven by the cell
   select2 replaces the control with its own container and sizes it inline, so
   tagging that container from JS meant the width depended on the class landing
   on the right node. The cell already knows what kind of field it holds -- the
   original select is still in there -- so the cap is driven from the cell
   instead. One rule, no class plumbing, and it cannot drift out of sync. */
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value:has(> select.ts-enum) > .select2-container,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value:has(select.ts-enum) > .select2-container {
	width: min(100%, 340px) !important;
	max-width: 340px !important;
	justify-self: start;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value:has(select.ts-lookup) > .select2-container {
	width: 100% !important;
	max-width: 100% !important;
}


/* paired rows stack below
   Between roughly 700 and 1100px the two halves of a paired row were each too
   narrow to keep the label and control on one line, so labels wrapped by
   different amounts and the controls stopped sharing an axis -- six of them at
   900px. Rather than let the row degrade gradually, the right half moves under
   the left at a stated width. Both halves then use the single-column template
   and every control on the form is back on one axis. */
@media only screen and (max-width: 1100px) {
	body#mainbody form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-paired {
		grid-template-columns: var(--tsf-label) minmax(0, 1fr);
		/* The centre gap belongs to a two-up row. Once the halves are stacked it is
		   just 24px of indent that put these controls off the axis the full-width
		   rows were already using. */
		column-gap: 0;
	}
	body#mainbody form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-paired > td:nth-child(3) {
		grid-column: 1;
	}
	body#mainbody form.ts-modern-form table.ts-modern-form-table tr.ts-form-row-paired > td:nth-child(4) {
		grid-column: 2;
	}
}

/* Compound controls override only the deterministic value cell's internal child
   placement. The form's shared label, adornment, and left/right axes above stay
   untouched. These rules live after the generic `td.ts-form-value > *` mapping
   so Capital and Incoterms cannot be split back into separate visual rows. */
body#mainbody form.ts-modern-form tr.ts-form-compound-capital td.ts-form-value {
	display: grid !important;
	grid-template-columns: var(--tsf-adorn) minmax(0, 1fr) 60px !important;
	gap: 0 !important;
}
body#mainbody form.ts-modern-form tr.ts-form-compound-capital td.ts-form-value.ts-form-control--compound {
	width: min(100%, calc(320px + var(--tsf-adorn))) !important;
	max-width: calc(320px + var(--tsf-adorn)) !important;
	justify-self: start;
}
body#mainbody form.ts-modern-form tr.ts-form-compound-capital td.ts-form-value > input[name="capital"] {
	grid-column: 2 !important;
	grid-row: 1;
	width: 100% !important;
	border-radius: 8px 0 0 8px;
}
body#mainbody form.ts-modern-form tr.ts-form-compound-capital td.ts-form-value > span:not([class*="fa-"]) {
	grid-column: 3 !important;
	grid-row: 1;
	width: 60px;
	height: 40px;
}
body#mainbody form.ts-modern-form tr.ts-form-compound-incoterms td.ts-form-value {
	display: grid !important;
	grid-template-columns: var(--tsf-adorn) minmax(0, .42fr) 8px minmax(0, .58fr) !important;
	column-gap: 0 !important;
	row-gap: 0 !important;
}
body#mainbody form.ts-modern-form tr.ts-form-compound-incoterms td.ts-form-value.ts-form-control--paired {
	width: min(100%, calc(720px + var(--tsf-adorn))) !important;
	max-width: calc(720px + var(--tsf-adorn)) !important;
	justify-self: start;
}
body#mainbody form.ts-modern-form tr.ts-form-compound-incoterms td.ts-form-value > .select2-container {
	grid-column: 2 !important;
	grid-row: 1;
	width: 100% !important;
	max-width: none !important;
}
body#mainbody form.ts-modern-form tr.ts-form-compound-incoterms td.ts-form-value > input[name="location_incoterms"] {
	grid-column: 4 !important;
	grid-row: 1;
	width: 100% !important;
}

/* Semantic exceptions inside the existing paired grid. Zip is intentionally
   short while City retains its native half-track width; VAT is a medium value
   aligned on the right-hand control axis. Neither rule changes table tracks. */
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-control--short > input#zipcode[name="zipcode"] {
	width: min(100%, 210px) !important;
	max-width: 210px !important;
	text-align: left !important;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-control--medium-intent > input#intra_vat[name="tva_intra"] {
	width: min(100%, 300px) !important;
	max-width: 300px !important;
}

/* Category selectors keep the shared adornment and control axes, then reserve a
   real trailing slot for Dolibarr's existing add action. The button stays in the
   same row without being copied or detached from its dialog behavior. */
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value:has(> a.button_custcats),
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value:has(> a.button_suppcats) {
	grid-template-columns: var(--tsf-adorn) minmax(0, 1fr) 48px !important;
	column-gap: 0 !important;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > a.button_custcats,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value > a.button_suppcats {
	grid-column: 3 !important;
	grid-row: 1;
	justify-self: end;
}

/* COMMAND Home dashboard --------------------------------------------------
   Route-gated by modern.js. Dashboard classes such as .box and .info-box are
   shared by many Dolibarr pages, so every rule remains below this body class. */
body.ts-command-dashboard .fiche {
	background: #f7f8fa;
	padding: 20px !important;
}
body.ts-command-dashboard .ts-dashboard-native-title {
	display: none !important;
}
body.ts-command-dashboard .ts-dashboard-pagehead {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 20px;
	margin: 0 0 16px;
}
body.ts-command-dashboard .ts-dashboard-pagehead h1 {
	margin: 0;
	color: #111827;
	font-size: 26px;
	font-weight: 700;
	line-height: 1.2;
}
body.ts-command-dashboard .ts-dashboard-pagehead p {
	margin: 5px 0 0;
	color: #64748b;
	font-size: 14px;
	line-height: 1.4;
}
body.ts-command-dashboard .ts-dashboard-customize {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 9px;
	min-height: 40px;
	padding: 0 14px;
	border: 1px solid #e0e6ef;
	border-radius: 8px;
	background: #fff;
	color: #475569;
	font: inherit;
	font-size: 13px;
	font-weight: 600;
	cursor: pointer;
}
body.ts-command-dashboard .ts-dashboard-customize:hover,
body.ts-command-dashboard .ts-dashboard-customize[aria-pressed="true"] {
	border-color: #c7d2fe;
	background: #f5f3ff;
	color: #4f46e5;
}
body.ts-command-dashboard #boxcombo {
	margin: 0 0 16px auto;
	min-height: 40px;
}
body.ts-command-dashboard .ts-dashboard-summary-grid {
	display: grid !important;
	grid-template-columns: repeat(4, minmax(0, 1fr));
	gap: 16px !important;
	margin: 0 0 16px !important;
	width: 100% !important;
}
body.ts-command-dashboard .ts-dashboard-summary-grid > .ts-dashboard-summary-item {
	width: auto !important;
	min-width: 0 !important;
	margin: 0 !important;
	padding: 0 !important;
}
body.ts-command-dashboard .ts-dashboard-summary-item > .box-flex-item-with-margin {
	height: 100%;
	margin: 0 !important;
}
body.ts-command-dashboard .ts-dashboard-summary-card {
	display: grid !important;
	grid-template-columns: 44px minmax(0, 1fr);
	align-items: start !important;
	column-gap: 14px;
	min-height: 112px !important;
	height: 100% !important;
	padding: 16px !important;
	border: 1px solid #e7e9ee !important;
	border-radius: 11px !important;
	background: #fff !important;
	box-shadow: 0 3px 12px rgba(15, 23, 42, .035) !important;
	overflow: hidden;
}
body.ts-command-dashboard .ts-dashboard-summary-card .info-box-icon {
	display: flex !important;
	align-items: center !important;
	justify-content: center !important;
	grid-column: 1;
	grid-row: 1;
	width: 44px !important;
	height: 44px !important;
	min-width: 44px !important;
	margin: 0 !important;
	padding: 0 !important;
	border: 0 !important;
	border-radius: 10px !important;
	box-shadow: none !important;
	font-size: 20px !important;
}
body.ts-command-dashboard .ts-dashboard-summary-card .info-box-icon i,
body.ts-command-dashboard .ts-dashboard-summary-card .info-box-icon span {
	font-size: 20px !important;
}
/* The weather/global-view glyph has materially less painted area than the
   other Font Awesome module icons at the same nominal size. Compensate only
   that glyph while retaining the shared 44px dashboard tile geometry. */
body.ts-command-dashboard .ts-dashboard-summary-card.info-box-weather .info-box-icon i,
body.ts-command-dashboard .ts-dashboard-summary-card .info-box-icon i.fa-weather-level1 {
	font-size: 25px !important;
}
body.ts-command-dashboard .ts-dashboard-summary-card.ts-dashboard-accent-violet .info-box-icon { background: #f1edff !important; color: #7047eb !important; }
body.ts-command-dashboard .ts-dashboard-summary-card.ts-dashboard-accent-blue .info-box-icon { background: #eaf1ff !important; color: #3474e8 !important; }
body.ts-command-dashboard .ts-dashboard-summary-card.ts-dashboard-accent-green .info-box-icon { background: #eaf8ee !important; color: #24a65a !important; }
body.ts-command-dashboard .ts-dashboard-summary-card.ts-dashboard-accent-cyan .info-box-icon { background: #e8f7fb !important; color: #1597b8 !important; }
body.ts-command-dashboard .ts-dashboard-summary-card.ts-dashboard-accent-orange .info-box-icon { background: #fff1e6 !important; color: #e66a13 !important; }
body.ts-command-dashboard .ts-dashboard-summary-card.ts-dashboard-accent-rose .info-box-icon { background: #fdebf5 !important; color: #d94b91 !important; }
body.ts-command-dashboard .ts-dashboard-summary-card .info-box-content {
	display: flex !important;
	flex-direction: column;
	justify-content: flex-start !important;
	min-width: 0;
	min-height: 0 !important;
	padding: 1px 0 0 !important;
	text-align: left !important;
}
body.ts-command-dashboard .ts-dashboard-summary-card .info-box-title {
	margin: 0 0 7px !important;
	color: #64748b !important;
	font-size: 11px !important;
	font-weight: 700 !important;
	line-height: 1.25 !important;
	letter-spacing: .035em;
	text-transform: uppercase;
}
body.ts-command-dashboard .ts-dashboard-summary-card .info-box-number {
	margin: 0 !important;
	color: #111827 !important;
	font-size: 21px !important;
	font-weight: 650 !important;
	line-height: 1.15 !important;
}
body.ts-command-dashboard .ts-dashboard-summary-card .progress-description,
body.ts-command-dashboard .ts-dashboard-summary-card .info-box-lines,
body.ts-command-dashboard .ts-dashboard-summary-card .info-box-line {
	margin: 0 !important;
	padding: 0 !important;
	color: #64748b;
	font-size: 13px !important;
	line-height: 1.55 !important;
}
body.ts-command-dashboard .ts-dashboard-summary-card .info-box-line {
	display: flex !important;
	align-items: center;
	gap: 6px;
	min-height: 21px;
}
body.ts-command-dashboard .ts-dashboard-summary-card a {
	font-size: 13px !important;
	font-weight: 500;
}
body.ts-command-dashboard .ts-dashboard-summary-card .badge {
	min-width: 22px;
	padding: 2px 7px;
	border-radius: 999px;
}

body.ts-command-dashboard .ts-dashboard-lower-grid {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	gap: 16px;
	align-items: start;
	clear: both;
}
body.ts-command-dashboard .ts-dashboard-lower-grid > #boxhalfleft,
body.ts-command-dashboard .ts-dashboard-lower-grid > #boxhalfright {
	float: none !important;
	width: auto !important;
	min-width: 0;
	margin: 0 !important;
	padding: 0 !important;
}
body.ts-command-dashboard .ts-dashboard-widget {
	margin: 0 0 16px !important;
	border: 1px solid #e7e9ee !important;
	border-radius: 11px !important;
	background: #fff !important;
	box-shadow: 0 3px 12px rgba(15, 23, 42, .035) !important;
	overflow: hidden;
}
body.ts-command-dashboard .ts-dashboard-widget > table.boxtable {
	margin: 0 !important;
	border: 0 !important;
	border-collapse: collapse !important;
	background: #fff !important;
}
body.ts-command-dashboard .ts-dashboard-widget tr.box_titre,
body.ts-command-dashboard .ts-dashboard-widget tr.box_titre > th {
	height: 48px;
	padding: 0 14px !important;
	border: 0 !important;
	border-bottom: 1px solid #edf0f4 !important;
	background: #fff !important;
	color: #1e293b !important;
	font-size: 13px !important;
	font-weight: 650 !important;
	text-transform: none !important;
}
body.ts-command-dashboard .ts-dashboard-widget tr.oddeven > td,
body.ts-command-dashboard .ts-dashboard-widget table.boxtable > tbody > tr:not(.box_titre) > td {
	height: 46px;
	padding: 10px 14px !important;
	border: 0 !important;
	border-bottom: 1px solid #f0f2f5 !important;
	background: #fff !important;
	font-size: 13px !important;
}
body.ts-command-dashboard .ts-dashboard-widget tr.oddeven:hover > td {
	background: #fafbff !important;
}
body.ts-command-dashboard .ts-dashboard-widget .boxhandle,
body.ts-command-dashboard .ts-dashboard-widget .boxclose {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 30px;
	height: 30px;
	margin-left: 4px !important;
	border-radius: 7px;
}
body.ts-command-dashboard:not(.ts-dashboard-customizing) .ts-dashboard-widget .boxhandle,
body.ts-command-dashboard:not(.ts-dashboard-customizing) .ts-dashboard-widget .boxclose {
	display: none !important;
}
body.ts-command-dashboard .ts-dashboard-invoices table.boxtable > tbody > tr:not(.box_titre) > td.center {
	height: 150px;
	padding: 28px !important;
	color: #64748b;
	font-size: 14px !important;
}
body.ts-command-dashboard .ts-dashboard-invoices table.boxtable > tbody > tr:not(.box_titre) > td.center::before {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 48px;
	height: 48px;
	margin: 0 auto 12px;
	border-radius: 50%;
	background: #f1edff;
	color: #7047eb;
	font-family: "Font Awesome 5 Free";
	font-size: 20px;
	font-weight: 900;
	content: "\f571";
}
body.ts-command-dashboard .ts-dashboard-prospects table.boxtable > tbody > tr:not(.box_titre) > td:first-child {
	font-weight: 500;
}

@media (max-width: 1350px) {
	body.ts-command-dashboard .ts-dashboard-summary-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
}
@media (max-width: 980px) {
	body.ts-command-dashboard .fiche { padding: 16px !important; }
	body.ts-command-dashboard .ts-dashboard-summary-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
	body.ts-command-dashboard .ts-dashboard-lower-grid { grid-template-columns: minmax(0, 1fr); }
}
@media (max-width: 640px) {
	body.ts-command-dashboard .fiche { padding: 12px !important; }
	body.ts-command-dashboard .ts-dashboard-pagehead { align-items: flex-start; }
	body.ts-command-dashboard .ts-dashboard-pagehead h1 { font-size: 24px; }
	body.ts-command-dashboard .ts-dashboard-pagehead p { font-size: 13px; }
	body.ts-command-dashboard .ts-dashboard-customize { flex: 0 0 auto; }
	body.ts-command-dashboard .ts-dashboard-summary-grid { grid-template-columns: minmax(0, 1fr); gap: 12px !important; }
	body.ts-command-dashboard .ts-dashboard-summary-card { min-height: 108px !important; }
}

/* Third Parties module landing -------------------------------------------
   Exact route-gated by modern.js. No generic .fichecenter/.box selector leaks
   into lists, records, forms, or other module landing pages. */
body.ts-thirdparty-dashboard .fiche {
	padding: 24px 20px 36px !important;
	background: #f7f8fa;
}
body.ts-thirdparty-dashboard .ts-pagehead {
	align-items: center;
	margin-bottom: 24px;
}
body.ts-thirdparty-dashboard .ts-pagehead-title {
	display: flex;
	flex-direction: column;
	align-items: flex-start;
	gap: 4px;
}
body.ts-thirdparty-dashboard .ts-pagehead-title .titre {
	font-size: 27px !important;
	font-weight: 700 !important;
	line-height: 1.2;
}
body.ts-thirdparty-dashboard .ts-module-subtitle {
	margin: 0;
	color: #64748b;
	font-size: 14px;
	font-weight: 400;
	line-height: 1.45;
}
body.ts-thirdparty-dashboard .ts-view-all-contacts {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 9px;
	min-height: 42px;
	padding: 0 15px;
	border: 1px solid #c7d2fe;
	border-radius: 8px;
	background: #fff;
	color: #4f46e5;
	font-size: 13px;
	font-weight: 600;
	text-decoration: none;
}
body.ts-thirdparty-dashboard .ts-view-all-contacts:hover {
	background: #f5f3ff;
	border-color: #a5b4fc;
}
body.ts-thirdparty-dashboard .fichecenter.ts-thirdparty-dashboard-grid {
	margin: 0 !important;
	width: 100%;
}
body.ts-thirdparty-dashboard .ts-thirdparty-dashboard-grid > .twocolumns {
	display: grid !important;
	grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
	align-items: start;
	gap: 18px;
	width: 100%;
}
body.ts-thirdparty-dashboard .ts-thirdparty-dashboard-grid .ts-module-dashboard-card {
	float: none !important;
	width: auto !important;
	min-width: 0;
	margin: 0 !important;
	padding: 0 22px 20px !important;
	border: 1px solid #e7e9ee;
	border-radius: 12px;
	background: #fff;
	box-shadow: 0 5px 18px rgba(15, 23, 42, .045);
	overflow: hidden;
}
body.ts-thirdparty-dashboard .ts-module-card-header {
	display: grid;
	grid-template-columns: 36px minmax(0, 1fr) auto;
	align-items: center;
	gap: 12px;
	min-height: 72px;
	border-bottom: 1px solid #edf0f4;
}
body.ts-thirdparty-dashboard .ts-module-card-icon {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 36px;
	height: 36px;
	border-radius: 9px;
	background: #f1edff;
	color: #7047eb;
	font-size: 16px;
}
body.ts-thirdparty-dashboard .ts-module-card-header h2 {
	margin: 0;
	color: #1e293b;
	font-size: 16px;
	font-weight: 650;
	line-height: 1.3;
}
body.ts-thirdparty-dashboard .ts-module-card-link,
body.ts-thirdparty-dashboard .ts-module-footer-link {
	color: #4f46e5;
	font-size: 13px;
	font-weight: 600;
	text-decoration: none;
}
body.ts-thirdparty-dashboard .ts-module-card-link:hover,
body.ts-thirdparty-dashboard .ts-module-footer-link:hover { text-decoration: underline; }
body.ts-thirdparty-dashboard .ts-module-dashboard-body {
	width: 100% !important;
	margin: 0 !important;
	border: 0 !important;
	border-radius: 0 !important;
	box-shadow: none !important;
	overflow: visible !important;
}
body.ts-thirdparty-dashboard .ts-module-dashboard-table {
	width: 100% !important;
	margin: 0 !important;
	border: 0 !important;
	background: transparent !important;
}

body.ts-thirdparty-dashboard .ts-module-dashboard-stats .ts-statistics-chart-row > td {
	height: 286px;
	padding: 18px 0 12px !important;
	border: 0 !important;
	background: #fff !important;
}
body.ts-thirdparty-dashboard .ts-thirdparty-donut {
	width: 330px !important;
	max-width: 100% !important;
	height: 270px !important;
	min-height: 270px !important;
	max-height: 270px !important;
	margin: 0 auto !important;
}
body.ts-thirdparty-dashboard .ts-thirdparty-donut canvas {
	width: 100% !important;
	height: 270px !important;
}
body.ts-thirdparty-dashboard .ts-chart-composition {
	display: grid;
	grid-template-columns: minmax(260px, 1fr) minmax(150px, .62fr);
	align-items: center;
	gap: 20px;
	width: 100%;
}
body.ts-thirdparty-dashboard .ts-chart-legend {
	display: flex;
	flex-direction: column;
	gap: 15px;
	min-width: 0;
}
body.ts-thirdparty-dashboard .ts-chart-legend-item {
	display: grid;
	grid-template-columns: 10px minmax(0, 1fr) auto;
	align-items: center;
	gap: 9px;
	color: #64748b;
	font-size: 13px;
}
body.ts-thirdparty-dashboard .ts-chart-legend-dot {
	width: 10px;
	height: 10px;
	border-radius: 50%;
}
body.ts-thirdparty-dashboard .ts-chart-legend-item strong {
	color: #334155;
	font-weight: 600;
}
body.ts-thirdparty-dashboard .ts-statistics-total-row {
	display: table-row;
}
body.ts-thirdparty-dashboard .ts-statistics-total-row > td {
	height: 60px;
	padding: 0 16px !important;
	border: 0 !important;
	background: #f5f3ff !important;
	vertical-align: middle;
}
body.ts-thirdparty-dashboard .ts-statistics-total-row > td:first-child {
	border-radius: 10px 0 0 10px;
	color: #334155;
	font-size: 15px;
	font-weight: 550;
}
body.ts-thirdparty-dashboard .ts-statistics-total-row > td:last-child {
	border-radius: 0 10px 10px 0;
	color: #4f46e5;
	font-size: 21px;
	font-weight: 700;
}
body.ts-thirdparty-dashboard .ts-total-icon {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 36px;
	height: 36px;
	margin-right: 12px;
	border-radius: 9px;
	background: #e9e4ff;
	color: #7047eb;
}
body.ts-thirdparty-dashboard .ts-stat-mini-grid {
	display: grid;
	grid-template-columns: repeat(4, minmax(0, 1fr));
	gap: 12px;
	margin-top: 18px;
}
body.ts-thirdparty-dashboard .ts-stat-mini {
	display: flex;
	align-items: center;
	gap: 10px;
	min-width: 0;
	min-height: 76px;
	padding: 12px;
	border: 1px solid #e7e9ee;
	border-radius: 10px;
	background: #fff;
}
body.ts-thirdparty-dashboard .ts-stat-mini-icon {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 30px;
	height: 30px;
	flex: 0 0 30px;
	border-radius: 8px;
	font-size: 15px;
}
body.ts-thirdparty-dashboard .ts-stat-mini-copy {
	display: flex;
	flex-direction: column;
	min-width: 0;
	line-height: 1.25;
}
body.ts-thirdparty-dashboard .ts-stat-mini-label {
	color: #64748b;
	font-size: 12px;
}
body.ts-thirdparty-dashboard .ts-stat-mini strong {
	margin-top: 3px;
	color: #1e293b;
	font-size: 17px;
	font-weight: 700;
}
body.ts-thirdparty-dashboard .ts-stat-mini-1 .ts-stat-mini-icon { background:#f1edff; color:#8453bd; }
body.ts-thirdparty-dashboard .ts-stat-mini-2 .ts-stat-mini-icon { background:#e8f6fb; color:#2694b5; }
body.ts-thirdparty-dashboard .ts-stat-mini-3 .ts-stat-mini-icon { background:#fff1df; color:#e68a14; }
body.ts-thirdparty-dashboard .ts-stat-mini-4 .ts-stat-mini-icon { background:#eaf7ec; color:#47a354; }

body.ts-thirdparty-dashboard .ts-module-dashboard-recent {
	padding-bottom: 0 !important;
}
body.ts-thirdparty-dashboard .ts-module-dashboard-recent .ts-module-dashboard-body {
	margin-top: 18px !important;
	border: 1px solid #e7e9ee !important;
	border-radius: 10px !important;
	overflow: hidden !important;
}
body.ts-thirdparty-dashboard .ts-module-dashboard-recent .ts-recent-columns th {
	height: 46px;
	padding: 0 14px;
	border: 0;
	border-bottom: 1px solid #e7e9ee;
	background: #f8fafc;
	color: #334155;
	font-size: 12px;
	font-weight: 650;
	text-align: left;
}
body.ts-thirdparty-dashboard .ts-module-dashboard-recent .ts-recent-columns th:nth-child(2),
body.ts-thirdparty-dashboard .ts-module-dashboard-recent .ts-recent-columns th:nth-child(4) { text-align: center; }
body.ts-thirdparty-dashboard .ts-recent-thirdparty-row td {
	height: 68px;
	padding: 9px 14px !important;
	border: 0 !important;
	border-bottom: 1px solid #edf0f4 !important;
	background: #fff !important;
	font-size: 13px;
	vertical-align: middle;
}
body.ts-thirdparty-dashboard .ts-module-dashboard-recent .ts-recent-columns th:nth-child(1),
body.ts-thirdparty-dashboard .ts-recent-thirdparty-row td:nth-child(1) { width: 42%; }
body.ts-thirdparty-dashboard .ts-module-dashboard-recent .ts-recent-columns th:nth-child(2),
body.ts-thirdparty-dashboard .ts-recent-thirdparty-row td:nth-child(2) { width: 18%; }
body.ts-thirdparty-dashboard .ts-module-dashboard-recent .ts-recent-columns th:nth-child(3),
body.ts-thirdparty-dashboard .ts-recent-thirdparty-row td:nth-child(3) { width: 24%; }
body.ts-thirdparty-dashboard .ts-module-dashboard-recent .ts-recent-columns th:nth-child(4),
body.ts-thirdparty-dashboard .ts-recent-thirdparty-row td:nth-child(4) { width: 16%; }
body.ts-thirdparty-dashboard .ts-recent-thirdparty-row:last-child td { border-bottom: 0 !important; }
body.ts-thirdparty-dashboard .ts-recent-thirdparty-row:hover td { background: #fafbff !important; }
body.ts-thirdparty-dashboard .ts-recent-thirdparty-row td:first-child a.refurl {
	display: flex;
	align-items: center;
	min-width: 0;
	color: #3543d4;
	font-weight: 550;
	text-decoration: none;
}
body.ts-thirdparty-dashboard .ts-recent-thirdparty-row td:first-child a.refurl > [class*="fa-"] {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 38px;
	height: 38px;
	margin-right: 11px;
	padding: 0 !important;
	flex: 0 0 38px;
	border-radius: 9px;
	background: #f1edff;
	color: #7047eb !important;
	font-size: 16px;
}
body.ts-thirdparty-dashboard .ts-recent-thirdparty-row td:nth-child(2) { text-align: center; }
body.ts-thirdparty-dashboard .ts-recent-thirdparty-row td:nth-child(2) a {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-height: 24px;
	padding: 2px 8px;
	border-radius: 999px;
	background: #eaf8ee;
	color: #21864b;
	font-size: 11px;
	font-weight: 650;
	text-decoration: none;
}
body.ts-thirdparty-dashboard .ts-recent-thirdparty-row td:nth-child(3) {
	color: #475569;
	text-align: left !important;
}
body.ts-thirdparty-dashboard .ts-recent-thirdparty-row td:last-child { text-align: center !important; }
body.ts-thirdparty-dashboard .ts-recent-thirdparty-row .badge-status {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: auto;
	height: 25px;
	min-width: 48px;
	padding: 0 9px;
	border: 1px solid #b9e5c9;
	border-radius: 999px;
	background: #eaf8ee;
	color: #18733d;
	font-size: 11px;
	font-weight: 650;
}
body.ts-thirdparty-dashboard .ts-recent-thirdparty-row .badge-status::after {
	content: attr(aria-label);
}
body.ts-thirdparty-dashboard .ts-recent-thirdparty-row .badge-status::before { display: none !important; }
body.ts-thirdparty-dashboard .ts-module-card-footer {
	display: flex;
	align-items: center;
	justify-content: center;
	min-height: 62px;
	margin-top: 18px;
	border-top: 1px solid #edf0f4;
}

@media (max-width: 1180px) {
	body.ts-thirdparty-dashboard .ts-thirdparty-dashboard-grid > .twocolumns { grid-template-columns: minmax(0, 1fr); }
}
@media (max-width: 900px) {
	body.ts-thirdparty-dashboard .fiche { padding: 20px 16px 32px !important; }
	body.ts-thirdparty-dashboard .ts-pagehead { align-items: flex-start; }
	body.ts-thirdparty-dashboard .ts-stat-mini-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (max-width: 640px) {
	body.ts-thirdparty-dashboard .fiche { padding: 16px 12px 28px !important; }
	body.ts-thirdparty-dashboard .ts-pagehead { flex-wrap: wrap; gap: 14px; }
	body.ts-thirdparty-dashboard .ts-pagehead-title .titre { font-size: 24px !important; }
	body.ts-thirdparty-dashboard .ts-view-all-contacts { width: 100%; }
	body.ts-thirdparty-dashboard .ts-thirdparty-dashboard-grid .ts-module-dashboard-card { padding: 0 14px 16px !important; }
	body.ts-thirdparty-dashboard .ts-module-card-header { min-height: 64px; }
	body.ts-thirdparty-dashboard .ts-thirdparty-donut,
	body.ts-thirdparty-dashboard .ts-thirdparty-donut canvas { width: 100% !important; height: 230px !important; min-height: 230px !important; max-height: 230px !important; }
	body.ts-thirdparty-dashboard .ts-chart-composition { grid-template-columns: minmax(0, 1fr); gap: 8px; }
	body.ts-thirdparty-dashboard .ts-chart-legend { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px 18px; padding: 0 8px; }
	body.ts-thirdparty-dashboard .ts-module-dashboard-stats .ts-statistics-chart-row > td { height: 336px; }
	body.ts-thirdparty-dashboard .ts-stat-mini { min-height: 70px; padding: 10px; }
	body.ts-thirdparty-dashboard .ts-module-dashboard-recent .ts-module-dashboard-body { overflow-x: auto !important; }
	body.ts-thirdparty-dashboard .ts-module-dashboard-recent .ts-module-dashboard-table { min-width: 560px; }
}

/* Partnership create/edit form ------------------------------------------
   Exact-route gated by modern.js; existing native controls are re-parented
   into this shared card/grid rather than replaced. */
body.ts-partnership-form-page .fiche {
	max-width: 1320px;
	margin: 0 auto;
	padding: 20px 24px 40px !important;
	background: #f7f8fa;
}
body.ts-partnership-form-page .ts-partnership-native-title,
body.ts-partnership-form-page .ts-partnership-source-table {
	display: none !important;
}
body.ts-partnership-form-page .ts-partnership-breadcrumb {
	display: flex;
	align-items: center;
	gap: 9px;
	margin: 0 0 14px;
	color: #94a3b8;
	font-size: 13px;
}
body.ts-partnership-form-page .ts-partnership-breadcrumb a {
	color: #4f46e5;
	font-weight: 500;
	text-decoration: none;
}
body.ts-partnership-form-page .ts-partnership-breadcrumb a:hover { text-decoration: underline; }
body.ts-partnership-form-page .ts-partnership-breadcrumb [aria-current="page"] { color: #64748b; }
body.ts-partnership-form-page .ts-partnership-title {
	margin: 0 0 18px;
	color: #111827;
	font-size: 28px;
	font-weight: 700;
	line-height: 1.2;
}
body.ts-partnership-form-page .ts-partnership-info {
	display: flex;
	align-items: center;
	gap: 12px;
	min-height: 50px;
	margin: 0 0 24px;
	padding: 10px 16px;
	border: 1px solid #cfd4ff;
	border-radius: 9px;
	background: #f5f3ff;
	color: #475569;
	font-size: 13px;
	line-height: 1.45;
}
body.ts-partnership-form-page .ts-partnership-info > [class*="fa-"] {
	color: #5b4cf0;
	font-size: 19px;
}
body.ts-partnership-form-page .ts-partnership-card {
	margin: 0 !important;
	padding: 24px !important;
	border: 1px solid #e7e9ee !important;
	border-radius: 12px !important;
	background: #fff !important;
	box-shadow: 0 5px 18px rgba(15, 23, 42, .045) !important;
	overflow: visible !important;
}
body.ts-partnership-form-page .ts-partnership-card-title {
	margin: 0 0 24px;
	color: #1e293b;
	font-size: 18px;
	font-weight: 650;
	line-height: 1.35;
}
body.ts-partnership-form-page .ts-partnership-field-grid {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	gap: 24px 28px;
}
body.ts-partnership-form-page .ts-partnership-field {
	display: flex;
	flex-direction: column;
	min-width: 0;
}
body.ts-partnership-form-page .ts-partnership-label {
	position: relative;
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 10px;
	min-height: 20px;
	margin: 0 0 8px;
	color: #1e293b;
	font-size: 13px;
	font-weight: 600;
}
body.ts-partnership-form-page .ts-partnership-label label { font: inherit; color: inherit; }
body.ts-partnership-form-page .ts-partnership-required .ts-partnership-label label::after {
	margin-left: 6px;
	color: #dc2626;
	content: "*";
}
body.ts-partnership-form-page .ts-partnership-control {
	display: flex;
	align-items: center;
	gap: 8px;
	min-width: 0;
}
body.ts-partnership-form-page .ts-partnership-control > select,
body.ts-partnership-form-page .ts-partnership-control > .select2-container {
	width: 100% !important;
	max-width: none !important;
	min-width: 0 !important;
}
body.ts-partnership-form-page .ts-partnership-control > [class*="fa-building"] {
	display: none !important;
}
body.ts-partnership-form-page .ts-partnership-control .select2-selection,
body.ts-partnership-form-page .ts-partnership-control .select2-selection--single {
	height: 46px !important;
	min-height: 46px !important;
	border: 1px solid #dbe2ec !important;
	border-radius: 8px !important;
	background: #fff !important;
	box-shadow: none !important;
}
body.ts-partnership-form-page .ts-partnership-control .select2-selection__rendered {
	height: 44px !important;
	padding: 0 42px 0 14px !important;
	color: #334155 !important;
	font-size: 13px !important;
	line-height: 44px !important;
}
body.ts-partnership-form-page .ts-partnership-control .select2-selection__arrow {
	top: 0 !important;
	right: 10px !important;
	height: 44px !important;
}
body.ts-partnership-form-page .ts-partnership-control .select2-selection:focus,
body.ts-partnership-form-page .ts-partnership-control .select2-selection[aria-expanded="true"] {
	border-color: #8b7cf6 !important;
	box-shadow: 0 0 0 3px rgba(91, 76, 240, .12) !important;
	outline: 0 !important;
}
body.ts-partnership-form-page .ts-partnership-related-create {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 26px;
	height: 26px;
	min-width: 26px;
	margin: 0;
	padding: 0;
	border: 0;
	border-radius: 6px;
	background: #f5f3ff;
	color: #4f46e5;
	position: absolute;
	top: -3px;
	right: 0;
}
body.ts-partnership-form-page .ts-partnership-date-field .ts-partnership-control {
	position: relative;
	display: block;
}
body.ts-partnership-form-page .ts-partnership-date-field .divfordateinput {
	position: relative;
	display: block !important;
	width: 100%;
}
body.ts-partnership-form-page .ts-partnership-date-field input.hasDatepicker {
	width: 100% !important;
	height: 46px !important;
	max-width: none !important;
	padding: 0 44px 0 14px !important;
	border: 1px solid #dbe2ec !important;
	border-radius: 8px !important;
	background: #fff !important;
	color: #334155;
	font-size: 13px;
	text-align: left !important;
	box-shadow: none !important;
}
body.ts-partnership-form-page .ts-partnership-date-field input.hasDatepicker:focus {
	border-color: #8b7cf6 !important;
	box-shadow: 0 0 0 3px rgba(91, 76, 240, .12) !important;
	outline: 0;
}
body.ts-partnership-form-page .ts-partnership-date-field .ui-datepicker-trigger {
	position: absolute;
	top: 50%;
	right: 13px;
	width: 19px !important;
	height: 19px !important;
	margin: 0 !important;
	transform: translateY(-50%);
	cursor: pointer;
	opacity: .68;
}
body.ts-partnership-form-page .ts-partnership-today {
	display: inline-flex !important;
	align-items: center;
	justify-content: center;
	height: 26px;
	min-width: 48px;
	padding: 0 8px;
	border: 0;
	border-radius: 7px;
	background: transparent;
	color: #4f46e5;
	font-size: 12px;
	font-weight: 600;
	cursor: pointer;
	position: absolute;
	top: -3px;
	right: 0;
}
body.ts-partnership-form-page .ts-partnership-today:hover { background: #f5f3ff; }
body.ts-partnership-form-page .ts-partnership-help {
	margin: 8px 0 0;
	color: #64748b;
	font-size: 12px;
	line-height: 1.4;
}
body.ts-partnership-form-page .ts-partnership-actions {
	display: flex;
	align-items: center;
	justify-content: flex-end;
	gap: 12px;
	margin: 30px 0 0 !important;
	padding: 22px 0 0;
	border-top: 1px solid #e7e9ee;
	text-align: right !important;
}
body.ts-partnership-form-page .ts-partnership-actions input.button {
	height: 44px !important;
	min-height: 44px !important;
	margin: 0 !important;
	padding: 0 22px !important;
	border-radius: 8px !important;
	font-size: 13px !important;
	font-weight: 650 !important;
}
body.ts-partnership-form-page .ts-partnership-actions input[name="cancel"] {
	min-width: 128px;
	border: 1px solid #dbe2ec !important;
	background: #fff !important;
	color: #334155 !important;
}
body.ts-partnership-form-page .ts-partnership-actions input[name="add"],
body.ts-partnership-form-page .ts-partnership-actions input[name="save"] {
	min-width: 205px;
	border: 1px solid #5b4cf0 !important;
	background: #5b4cf0 !important;
	color: #fff !important;
}
body.ts-partnership-form-page .select2-dropdown.ts-partnership-select-dropdown {
	border: 1px solid #dbe2ec !important;
	border-radius: 9px !important;
	background: #fff !important;
	box-shadow: 0 12px 28px rgba(15, 23, 42, .14) !important;
	overflow: hidden;
}
body.ts-partnership-form-page .ts-partnership-select-dropdown .select2-search__field {
	height: 38px;
	padding: 0 12px !important;
	border: 1px solid #dbe2ec !important;
	border-radius: 7px !important;
	outline: 0;
}
body.ts-partnership-form-page .ts-partnership-compact-dropdown .select2-search { display: none !important; }
body.ts-partnership-form-page .ts-partnership-select-dropdown .select2-results__option {
	min-height: 38px;
	padding: 9px 12px !important;
	font-size: 13px;
}
body.ts-partnership-form-page .ts-partnership-select-dropdown .select2-results__option[aria-selected="true"],
body.ts-partnership-form-page .ts-partnership-select-dropdown .select2-results__option--highlighted {
	background: #f1efff !important;
	color: #4338ca !important;
}

/* Shared COMMAND create/edit form surfaces -------------------------------
   JS marks only a top-level create/edit form with a real field table and submit
   controls. Specialized module adapters opt out before these rules apply. */
body.ts-command-form-page .fiche {
	max-width: 1320px;
	margin: 0 auto;
	padding: 22px 24px 42px !important;
}
body.ts-command-form-page .fiche > table.table-fiche-title,
body.ts-command-form-page .fiche > div > table.table-fiche-title {
	margin: 0 0 16px !important;
}
body.ts-command-form-page .fiche div.titre {
	font-size: 26px;
	font-weight: 680;
	line-height: 1.25;
}
body.ts-command-form-page .ts-command-form {
	margin: 0 !important;
	padding: 24px !important;
	border: 1px solid #e7e9ee;
	border-radius: 12px;
	background: #fff;
	box-shadow: 0 5px 18px rgba(15, 23, 42, .045);
}

/* Shared record normalization. The node movement is generic and only activates
   after Dolibarr exposes both its native entity banner and native tabs. This
   keeps dense action sets inside the card without changing their links or order. */
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearef.ts-has-actions {
	display: flex;
	align-items: center;
	gap: 16px;
	min-width: 0;
}
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearef.ts-has-actions > .refid,
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearef.ts-has-actions > .ts-entity-identity {
	flex: 1 1 320px;
	min-width: 0;
}
body.ts-command-record-page .ts-header-actions {
	flex: 1 1 480px;
	min-width: 0;
}
body.ts-command-record-page div.tabs[data-ts-placed="1"] {
	display: flex;
	flex-wrap: nowrap;
	overflow-x: auto;
	overflow-y: hidden;
	scrollbar-width: thin;
}
body.ts-command-record-page div.tabs[data-ts-placed="1"] > a.tab {
	flex: 0 0 auto;
}
@media (max-width: 900px) {
	body.ts-command-record-page div.tabBar.ts-entity-card > div.arearef.ts-has-actions { align-items: flex-start; }
	body.ts-command-record-page .ts-header-actions { flex-basis: 100%; max-width: 100%; }
}
@media (max-width: 640px) {
	body.ts-command-record-page .ts-header-actions div.tabsAction {
		justify-content: flex-start;
		width: 100%;
	}
	body.ts-command-record-page .ts-header-actions div.tabsAction > a,
	body.ts-command-record-page .ts-header-actions div.tabsAction > button,
	body.ts-command-record-page .ts-header-actions div.tabsAction > input,
	body.ts-command-record-page .ts-header-actions div.tabsAction > .inline-block {
		max-width: 100%;
	}
}
body.ts-command-form-page .ts-command-form-fields {
	width: 100% !important;
	margin: 0 !important;
	border: 0 !important;
	background: transparent !important;
	box-shadow: none !important;
}
body.ts-command-form-page .ts-command-form-fields > tbody > tr {
	border: 0 !important;
}
body.ts-command-form-page .ts-command-form-fields > tbody > tr > td {
	min-height: 52px;
	padding-top: 8px !important;
	padding-bottom: 8px !important;
	border: 0 !important;
	border-bottom: 1px solid #f1f3f6 !important;
	background: transparent !important;
	font-size: 13px;
	vertical-align: middle;
}
body.ts-command-form-page .ts-command-form-fields > tbody > tr:last-child > td { border-bottom: 0 !important; }
body.ts-command-form-page .ts-command-form-fields > tbody > tr > td:first-child,
body.ts-command-form-page .ts-command-form-fields .titlefield,
body.ts-command-form-page .ts-command-form-fields .titlefieldcreate,
body.ts-command-form-page .ts-command-form-fields .titlefieldmiddle {
	width: clamp(180px, 23%, 230px) !important;
	padding-right: 22px !important;
	color: #273449;
	font-size: 13px;
	font-weight: 600;
}
body.ts-command-form-page .ts-command-control,
body.ts-command-form-page .ts-command-form input[type="text"],
body.ts-command-form-page .ts-command-form input[type="email"],
body.ts-command-form-page .ts-command-form input[type="url"],
body.ts-command-form-page .ts-command-form input[type="number"],
body.ts-command-form-page .ts-command-form input[type="password"] {
	min-height: 40px !important;
	padding-left: 13px !important;
	padding-right: 13px !important;
	border: 1px solid #dfe4ec !important;
	border-radius: 8px !important;
	background: #fff !important;
	box-shadow: none !important;
	font-size: 13px !important;
}
body.ts-command-form-page .ts-command-form textarea.ts-command-control {
	min-height: 104px !important;
	padding: 11px 13px !important;
	line-height: 1.5;
}
body.ts-command-form-page .ts-command-form input:focus,
body.ts-command-form-page .ts-command-form textarea:focus,
body.ts-command-form-page .ts-command-form .select2-selection:focus,
body.ts-command-form-page .ts-command-form .select2-selection[aria-expanded="true"] {
	border-color: #8b7cf6 !important;
	box-shadow: 0 0 0 3px rgba(91, 76, 240, .12) !important;
	outline: 0 !important;
}
body.ts-command-form-page .ts-command-form .select2-selection--single {
	height: 40px !important;
	min-height: 40px !important;
	border: 1px solid #dfe4ec !important;
	border-radius: 8px !important;
	background: #fff !important;
	box-shadow: none !important;
}
body.ts-command-form-page .ts-command-form .select2-selection__rendered {
	height: 38px !important;
	padding: 0 40px 0 13px !important;
	font-size: 13px !important;
	line-height: 38px !important;
}
body.ts-command-form-page .ts-command-form .select2-selection__arrow {
	top: 0 !important;
	right: 9px !important;
	height: 38px !important;
}
body.ts-command-form-page .ts-command-form-actions {
	display: flex !important;
	align-items: center;
	justify-content: center;
	gap: 11px;
	margin: 28px 0 0 !important;
	padding: 22px 0 0;
	border-top: 1px solid #e7e9ee;
}
body.ts-command-form-page .ts-command-form-actions input,
body.ts-command-form-page .ts-command-form-actions button {
	min-width: 112px;
	height: 40px !important;
	margin: 0 !important;
	padding: 0 18px !important;
	border-radius: 8px !important;
	font-size: 13px !important;
	font-weight: 650 !important;
}
body.ts-command-form-page .ts-command-submit-primary {
	border-color: #5546e8 !important;
	background: #5546e8 !important;
	color: #fff !important;
}
body.ts-command-form-page .ts-command-submit-secondary {
	border-color: #dfe4ec !important;
	background: #fff !important;
	color: #334155 !important;
}
@media (max-width: 760px) {
	body.ts-command-form-page .fiche { padding: 16px 12px 30px !important; }
	body.ts-command-form-page .ts-command-form { padding: 16px !important; }
	body.ts-command-form-page .ts-command-form-fields > tbody > tr {
		display: grid;
		grid-template-columns: minmax(0, 1fr);
		padding: 10px 0;
		border-bottom: 1px solid #f1f3f6 !important;
	}
	body.ts-command-form-page .ts-command-form-fields > tbody > tr > td {
		display: block;
		width: 100% !important;
		min-height: 0;
		padding: 4px 0 !important;
		border: 0 !important;
	}
	body.ts-command-form-page .ts-command-form-fields > tbody > tr > td:first-child,
	body.ts-command-form-page .ts-command-form-fields .titlefield,
	body.ts-command-form-page .ts-command-form-fields .titlefieldcreate,
	body.ts-command-form-page .ts-command-form-fields .titlefieldmiddle { width: 100% !important; padding-right: 0 !important; }
	body.ts-command-form-page .ts-command-form input[type="text"],
	body.ts-command-form-page .ts-command-form input[type="email"],
	body.ts-command-form-page .ts-command-form input[type="url"],
	body.ts-command-form-page .ts-command-form textarea,
	body.ts-command-form-page .ts-command-form .select2-container { max-width: 100% !important; }
	body.ts-command-form-page .ts-command-form-actions { flex-wrap: wrap; }
}

/* Third Party overview reference composition -----------------------------
   Route-gated by modern.js so these refinements cannot alter other record
   tabs or any list/create surface. */
body.ts-thirdparty-overview .ts-thirdparty-record-shell {
	border: 0;
	background: transparent;
	box-shadow: none;
}
body.ts-thirdparty-overview .ts-thirdparty-record-shell > .ts-entity-banner {
	min-height: 142px;
	padding: 24px !important;
	border: 1px solid #e7e9ee !important;
	border-radius: 12px !important;
	background: #fff !important;
	box-shadow: 0 4px 16px rgba(15, 23, 42, .045) !important;
}
body.ts-thirdparty-overview .ts-entity-identity {
	display: grid !important;
	grid-template-columns: 80px minmax(0, 1fr) !important;
	gap: 20px !important;
}
body.ts-thirdparty-overview .ts-entity-banner .ts-entity-identity > .divphotoref.ts-overview-photo-source { display: none !important; }
body.ts-thirdparty-overview .ts-entity-banner .ts-entity-identity.ts-overview-has-logo > .divphotoref.ts-overview-photo-source {
	display: flex !important;
	width: 80px !important;
	height: 80px !important;
	min-width: 80px;
	padding: 8px;
	border: 0;
	border-radius: 14px;
	background: #f0edff;
	box-shadow: 0 6px 15px rgba(79, 70, 229, .09);
}
body.ts-thirdparty-overview .ts-overview-has-logo .ts-overview-photo-source a,
body.ts-thirdparty-overview .ts-overview-has-logo .ts-overview-photo-source img {
	display: block;
	width: 100% !important;
	height: 100% !important;
	max-width: 100% !important;
	max-height: 100% !important;
	margin: 0 !important;
	object-fit: contain;
}
body.ts-thirdparty-overview .ts-overview-has-logo .ts-overview-icon-tile { display: none; }
body.ts-thirdparty-overview .ts-overview-icon-tile {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 80px;
	height: 80px;
	border: 1px solid #e4dfff;
	border-radius: 12px;
	background: #f1edff;
	color: #6551d8;
	font-size: 30px;
}
body.ts-thirdparty-overview .ts-entity-identity > .refid {
	grid-template-columns: minmax(0, auto) auto auto minmax(0, 1fr) !important;
	column-gap: 9px !important;
	font-size: 24px !important;
	font-weight: 650 !important;
	letter-spacing: -.025em;
}
body.ts-thirdparty-overview .ts-entity-identity > .refid > .ts-overview-name {
	grid-column: 1;
	grid-row: 1;
	min-width: 0;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}
body.ts-thirdparty-overview .ts-entity-identity > .refid > a.refid { display: none !important; }
body.ts-thirdparty-overview .ts-overview-secondary-hidden { display: none !important; }
body.ts-thirdparty-overview .ts-overview-location {
	grid-column: 1 / -1;
	grid-row: 2;
	display: inline-flex !important;
	align-items: center;
	gap: 7px;
	margin: 5px 0 0 !important;
	color: #64748b;
	font-size: 13px;
	font-weight: 400;
}
body.ts-thirdparty-overview .ts-overview-location [class*="fa-"] { color: #8491a6 !important; }
body.ts-thirdparty-overview .ts-overview-email {
	grid-column: 1 / -1;
	grid-row: 3;
	display: inline-flex;
	align-items: center;
	gap: 7px;
	max-width: 100%;
	margin-top: 2px;
	color: #4f46e5;
	font-size: 13px;
	font-weight: 450;
	text-decoration: none;
}
body.ts-thirdparty-overview .ts-overview-email [class*="fa-"] {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 16px;
	margin: 0 !important;
	padding: 0 !important;
	color: #64748b !important;
}
body.ts-thirdparty-overview .ts-overview-info {
	grid-column: 3;
	grid-row: 1;
	color: #8190a6;
	font-size: 14px;
}
body.ts-thirdparty-overview .ts-header-actions div.tabsAction { gap: 10px; }
body.ts-thirdparty-overview .ts-header-actions .ts-record-primary {
	order: 2 !important;
	min-height: 40px !important;
	border: 1px solid #dfe4ec !important;
	background: #fff !important;
	color: #334155 !important;
	box-shadow: none !important;
}
body.ts-thirdparty-overview .ts-header-actions .ts-record-secondary {
	order: 1 !important;
	min-height: 40px !important;
	border: 1px solid #5546e8 !important;
	background: #5546e8 !important;
	color: #fff !important;
	box-shadow: 0 2px 5px rgba(79, 70, 229, .18) !important;
}
body.ts-thirdparty-overview .ts-header-actions .ts-record-primary,
body.ts-thirdparty-overview .ts-header-actions .ts-record-secondary { gap: 9px; }
body.ts-thirdparty-overview .ts-overview-button-icon { font-size: 14px; }
body.ts-thirdparty-overview .ts-more-actions { order: 3 !important; }
body.ts-thirdparty-overview .ts-more-actions-trigger { min-height: 40px; border-radius: 8px; box-shadow: none; }
body.ts-thirdparty-overview .ts-more-actions-menu {
	min-width: 178px;
	padding: 6px;
	border-color: #e2e7ef;
	border-radius: 10px;
	box-shadow: 0 14px 32px rgba(15, 23, 42, .14);
}
body.ts-thirdparty-overview .ts-more-actions-menu .ts-more-action-item { min-height: 40px; gap: 10px; border-radius: 7px; }
body.ts-thirdparty-overview .ts-overview-action-icon { width: 16px; text-align: center; color: #64748b; }
body.ts-thirdparty-overview .ts-more-action-item.butActionDelete .ts-overview-action-icon { color: #dc2626; }
body.ts-thirdparty-overview .ts-entity-banner .pagination.paginationref {
	order: 4;
	margin-left: 2px;
	display: inline-flex;
	opacity: 1;
}
body.ts-thirdparty-overview .ts-entity-banner .pagination.paginationref li.pagination > a,
body.ts-thirdparty-overview .ts-entity-banner .pagination.paginationref li.pagination > span {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 32px;
	height: 40px;
	border: 1px solid #dfe4ec;
	border-radius: 7px;
	background: #fff;
	color: #64748b;
}
body.ts-thirdparty-overview .ts-thirdparty-record-shell > .tabs[data-ts-placed="1"] {
	display: flex;
	align-items: stretch;
	gap: 0;
	margin: 16px 0;
	padding: 0 8px;
	border: 1px solid #e7e9ee;
	border-radius: 12px;
	background: #fff;
	box-shadow: 0 4px 14px rgba(15, 23, 42, .04);
}
body.ts-thirdparty-overview .ts-thirdparty-record-shell > .tabs a.tab { min-height: 52px; padding: 0 19px; font-size: 13px; }
body.ts-thirdparty-overview .ts-tabs-more { position: relative; flex: 0 0 auto; }
body.ts-thirdparty-overview .ts-tabs-more > summary {
	display: inline-flex;
	align-items: center;
	gap: 8px;
	min-height: 50px;
	padding: 0 16px;
	list-style: none;
	color: #536078;
	font-size: 14px;
	font-weight: 520;
	cursor: pointer;
}
body.ts-thirdparty-overview .ts-tabs-more > summary::-webkit-details-marker { display: none; }
body.ts-thirdparty-overview .ts-tabs-more > summary .fa-chevron-down { font-size: 10px; }
body.ts-thirdparty-overview .ts-tabs-more-menu {
	position: absolute;
	z-index: 80;
	top: calc(100% + 6px);
	right: 0;
	min-width: 180px;
	padding: 6px;
	border: 1px solid #e2e7ef;
	border-radius: 10px;
	background: #fff;
	box-shadow: 0 14px 32px rgba(15, 23, 42, .14);
}
body.ts-thirdparty-overview .ts-tabs-more-menu .tabsElem { display: block !important; }
body.ts-thirdparty-overview .ts-tabs-more-menu .tab { display: block; margin: 0 !important; }
body.ts-thirdparty-overview .ts-tabs-more-menu a.tab {
	display: flex;
	align-items: center;
	width: 100%;
	min-height: 40px;
	padding: 0 11px;
	border-radius: 7px;
}
body.ts-thirdparty-overview .ts-tabs-more-menu a.tab:hover { background: #f7f7fc; }
body.ts-thirdparty-overview .ts-thirdparty-groups { align-items: stretch; gap: 16px; }
body.ts-thirdparty-overview .ts-field-group {
	display: flex;
	flex-direction: column;
	min-height: 328px;
	border: 1px solid #e6e9ef;
	border-radius: 12px;
	box-shadow: 0 4px 14px rgba(15, 23, 42, .035);
}
body.ts-thirdparty-overview .ts-field-group-title {
	padding: 19px 20px 13px;
	border-bottom: 1px solid #f1f3f6;
	font-size: 16px;
}
body.ts-thirdparty-overview .ts-field-group-icon { width: 22px; font-size: 20px; }
body.ts-thirdparty-overview .ts-field-group table.tableforfield { flex: 0 0 auto; padding: 10px 20px 16px; }
body.ts-thirdparty-overview .ts-field-group table.tableforfield td {
	height: 31px;
	padding: 5px 2px;
	border: 0 !important;
	font-size: 13px;
}
body.ts-thirdparty-overview .ts-field-group table.tableforfield tr,
body.ts-thirdparty-overview .ts-field-group table.tableforfield tr + tr { border: 0 !important; }
body.ts-thirdparty-overview .ts-field-group table.tableforfield td:first-child { width: 62%; color: #273449; font-weight: 550; }
body.ts-thirdparty-overview .ts-field-group table.tableforfield td:last-child { color: #526176; font-size: 13.5px; }
body.ts-thirdparty-overview .ts-field-group tr[data-field="nature"] a {
	display: inline-flex;
	align-items: center;
	min-height: 23px;
	padding: 0 8px;
	border-radius: 999px;
	background: #eaf8ee;
	color: #18733d;
	font-size: 11px;
	font-weight: 650;
	text-decoration: none;
}
body.ts-thirdparty-overview .ts-field-group[data-group="relationships"] table { flex: 0 0 auto !important; }
body.ts-thirdparty-overview .ts-field-group[data-group="relationships"] td { height: 62px; vertical-align: middle; }
body.ts-thirdparty-overview .ts-field-group[data-group="relationships"] .userimg img {
	width: 29px !important;
	height: 29px !important;
	border-radius: 50%;
}
body.ts-thirdparty-overview .ts-field-group .clipboardCP { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
body.ts-thirdparty-overview .ts-overview-copy {
	display: inline-flex !important;
	align-items: center;
	justify-content: center;
	width: 30px;
	height: 30px;
	margin: -6px 0 !important;
	padding: 0 !important;
	border-radius: 7px;
}
body.ts-thirdparty-overview .ts-overview-copy:hover { background: #f3f1ff; color: #5546e8 !important; opacity: 1; }
body.ts-thirdparty-overview .ts-overview-lower-grid {
	display: grid;
	grid-template-columns: minmax(0, .9fr) minmax(0, 1.1fr);
	align-items: start;
	gap: 16px;
	margin-top: 16px;
}
body.ts-thirdparty-overview .ts-overview-lower-grid > .ts-record-section-card {
	float: none !important;
	width: auto !important;
	min-width: 0;
	margin: 0 !important;
	padding: 20px !important;
	border: 1px solid #e6e9ef;
	border-radius: 12px;
	box-shadow: 0 4px 14px rgba(15, 23, 42, .035);
}
body.ts-thirdparty-overview .ts-record-section-card table.table-fiche-title { min-height: 36px; margin-bottom: 14px; }
body.ts-thirdparty-overview .ts-record-section-files .ts-emptybox {
	min-height: 166px;
	border: 1px dashed #cfc8ff;
	border-radius: 12px;
	background: #fdfcff;
}
body.ts-thirdparty-overview .ts-record-section-files .ts-emptybox > .far {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 54px;
	height: 54px;
	border-radius: 50%;
	background: #f0edff;
	color: #5b4cf0;
	font-size: 23px;
}
body.ts-thirdparty-overview .ts-record-section-files form + br,
body.ts-thirdparty-overview .ts-record-section-files form + br + br { display: none; }
body.ts-thirdparty-overview .ts-record-section-events .ts-record-tab-surface { margin: 0; border-radius: 9px; box-shadow: none; }
body.ts-thirdparty-overview .ts-record-section-events tr.liste_titre th { height: 40px; padding: 8px 10px; font-size: 11px; color: #64748b; }
body.ts-thirdparty-overview .ts-record-section-events tr.oddeven td { height: 49px; padding: 8px 10px; font-size: 12px; }
body.ts-thirdparty-overview .ts-record-section-events .userimg img { width: 27px !important; height: 27px !important; border-radius: 50%; }
body.ts-thirdparty-overview .ts-overview-event-type { color: #6d5bd0 !important; }
body.ts-thirdparty-overview .ts-overview-event-type > [class*="fa-"] { display: none; }
body.ts-thirdparty-overview .ts-record-section-events tr > :nth-child(4) { display: none; }
body.ts-thirdparty-overview .ts-record-section-events tr > :nth-child(5) { width: 42%; }
body.ts-thirdparty-overview .ts-record-section-events tr.oddeven .badge-dot {
	background: #18a66a !important;
	color: #18a66a !important;
}
body.ts-thirdparty-overview .ts-overview-redundant-event-action { display: none !important; }

@media (min-width: 1101px) {
	body.ts-thirdparty-overview .ts-thirdparty-groups { grid-template-columns: repeat(3, minmax(0, 1fr)); }
	body.ts-thirdparty-overview .ts-field-group[data-group="identity"],
	body.ts-thirdparty-overview .ts-field-group[data-group="relationships"] { grid-column: auto; grid-row: auto; }
}
@media (max-width: 1100px) {
	body.ts-thirdparty-overview .ts-thirdparty-groups { grid-template-columns: repeat(2, minmax(0, 1fr)); }
	body.ts-thirdparty-overview .ts-field-group[data-group="relationships"] { grid-column: 1 / -1; }
}
@media (max-width: 900px) {
	body.ts-thirdparty-overview .ts-thirdparty-record-shell > .ts-entity-banner { align-items: flex-start; }
	body.ts-thirdparty-overview .ts-header-actions { width: 100%; }
	body.ts-thirdparty-overview .ts-overview-lower-grid { grid-template-columns: minmax(0, 1fr); }
}
@media (max-width: 700px) {
	body.ts-thirdparty-overview .ts-thirdparty-record-shell > .ts-entity-banner { padding: 16px !important; }
	body.ts-thirdparty-overview .ts-entity-identity { grid-template-columns: 48px minmax(0, 1fr) !important; gap: 12px !important; }
	body.ts-thirdparty-overview .ts-overview-icon-tile { width: 48px; height: 48px; }
	body.ts-thirdparty-overview .ts-entity-identity > .refid { font-size: 21px !important; }
	body.ts-thirdparty-overview .ts-thirdparty-groups { grid-template-columns: minmax(0, 1fr); }
	body.ts-thirdparty-overview .ts-field-group[data-group="relationships"] { grid-column: auto; }
	body.ts-thirdparty-overview .ts-tabs-more-menu { right: auto; left: 0; }
}

/* Third Party Events/Agenda tab -----------------------------------------
   Exact-route gated by modern.js. Native filters, view links, users, dates,
   references and event actions are moved intact into this composition. */
body.ts-thirdparty-events .ts-thirdparty-record-shell { margin-bottom: 16px; }
body.ts-thirdparty-events .ts-thirdparty-record-shell > .tabs[data-ts-placed="1"] { margin-bottom: 0; }
body.ts-thirdparty-events .ts-thirdparty-record-shell .tabsElemActive a#agenda {
	color: #4f46e5;
	font-weight: 650;
}
body.ts-thirdparty-events .fiche > .fichecenter {
	display: block;
	min-width: 0;
}
body.ts-thirdparty-events .ts-events-summary {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	margin: 0 0 16px;
	padding: 4px 18px;
	border: 1px solid #e7e9ee;
	border-radius: 12px;
	background: #fff;
	box-shadow: 0 4px 14px rgba(15, 23, 42, .04);
}
body.ts-thirdparty-events .ts-events-summary-item {
	display: grid;
	grid-template-columns: 42px minmax(0, 1fr);
	align-items: center;
	gap: 14px;
	min-height: 76px;
	padding: 12px 2px;
}
body.ts-thirdparty-events .ts-events-summary-item:nth-child(odd) { padding-right: 24px; }
body.ts-thirdparty-events .ts-events-summary-item:nth-child(even) { padding-left: 24px; }
body.ts-thirdparty-events .ts-events-summary-item:nth-child(n+3) { border-top: 1px solid #edf0f4; }
body.ts-thirdparty-events .ts-events-summary-icon {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 42px;
	height: 42px;
	border-radius: 10px;
	background: #f1edff;
	color: #5b4cf0;
	font-size: 16px;
}
body.ts-thirdparty-events .ts-events-summary-copy { display: grid; gap: 4px; min-width: 0; }
body.ts-thirdparty-events .ts-events-summary-label,
body.ts-thirdparty-events .ts-events-summary-value {
	display: block !important;
	width: auto !important;
	height: auto !important;
	padding: 0 !important;
	border: 0 !important;
	background: transparent !important;
}
body.ts-thirdparty-events .ts-events-summary-label {
	color: #64748b;
	font-size: 12px;
	font-weight: 550;
}
body.ts-thirdparty-events .ts-events-summary-value {
	color: #25324a;
	font-size: 13px;
	font-weight: 550;
}
body.ts-thirdparty-events .ts-events-summary-value .userimg img {
	width: 28px !important;
	height: 28px !important;
	border-radius: 50%;
}
body.ts-thirdparty-events .ts-events-pagehead {
	margin: 0 !important;
	padding: 22px 24px 16px;
	border: 1px solid #e7e9ee;
	border-bottom: 0;
	border-radius: 12px 12px 0 0;
	background: #fff;
}
body.ts-thirdparty-events .ts-events-pagehead .titre { font-size: 23px !important; font-weight: 650; }
body.ts-thirdparty-events .ts-events-create {
	min-height: 42px !important;
	padding: 0 17px !important;
	border-radius: 8px !important;
	background: #5546e8 !important;
	color: #fff !important;
}
body.ts-thirdparty-events .ts-events-native-title-source { display: none !important; }
body.ts-thirdparty-events .ts-events-toolbar {
	display: flex;
	align-items: center;
	gap: 10px;
	margin: 0;
	padding: 0 24px 18px;
	border: 1px solid #e7e9ee;
	border-top: 0;
	background: #fff;
}
body.ts-thirdparty-events .ts-events-view-switch {
	display: inline-flex;
	align-items: center;
	flex: 0 0 auto;
	height: 40px;
	padding: 3px;
	border: 1px solid #dfe4ec;
	border-radius: 8px;
	background: #fff;
}
body.ts-thirdparty-events .ts-events-view-option {
	display: inline-flex !important;
	align-items: center;
	justify-content: center;
	width: 36px !important;
	height: 32px !important;
	min-width: 36px !important;
	min-height: 32px !important;
	padding: 0 !important;
	border: 0 !important;
	border-radius: 6px !important;
	background: transparent !important;
	box-shadow: none !important;
	color: #64748b !important;
}
body.ts-thirdparty-events .ts-events-view-option.btnTitleSelected {
	background: #eeebff !important;
	color: #4f46e5 !important;
}
body.ts-thirdparty-events .ts-events-filter-form { flex: 1 1 auto; min-width: 0; margin: 0 !important; }
body.ts-thirdparty-events .ts-events-filter-form .ts-record-tab-surface {
	margin: 0 !important;
	border: 0 !important;
	border-radius: 0;
	box-shadow: none;
	overflow: visible;
}
body.ts-thirdparty-events .ts-events-filter-form table,
body.ts-thirdparty-events .ts-events-filter-form tbody,
body.ts-thirdparty-events .ts-events-filter-form tr {
	display: contents;
}
body.ts-thirdparty-events .ts-events-filter-form .liste_titre {
	display: grid;
	grid-template-columns: minmax(240px, 1.35fr) minmax(160px, .7fr) minmax(190px, .8fr) auto;
	align-items: center;
	gap: 10px;
	width: 100%;
	padding: 0 !important;
	border: 0 !important;
	background: transparent !important;
}
body.ts-thirdparty-events .ts-events-filter-form th {
	display: flex !important;
	align-items: center;
	min-width: 0;
	height: 40px !important;
	padding: 0 !important;
	border: 0 !important;
	background: transparent !important;
}
body.ts-thirdparty-events .ts-events-filter-form th:nth-child(5) { grid-column: 1; grid-row: 1; }
body.ts-thirdparty-events .ts-events-filter-form th:nth-child(2) { grid-column: 2; grid-row: 1; }
body.ts-thirdparty-events .ts-events-filter-form th:nth-child(4) { grid-column: 3; grid-row: 1; }
body.ts-thirdparty-events .ts-events-filter-form th:nth-child(1) { grid-column: 4; grid-row: 1; }
body.ts-thirdparty-events .ts-events-filter-form th:nth-child(3) { display: none !important; }
body.ts-thirdparty-events .ts-events-search-control {
	display: flex;
	align-items: center;
	gap: 9px;
	width: 100%;
	height: 40px;
	padding: 0 12px;
	border: 1px solid #dfe4ec;
	border-radius: 8px;
	background: #fff;
	color: #7c899d;
}
body.ts-thirdparty-events .ts-events-search-input {
	flex: 1 1 auto;
	width: 100% !important;
	height: 38px !important;
	min-width: 0;
	padding: 0 !important;
	border: 0 !important;
	background: transparent !important;
	box-shadow: none !important;
	font-size: 13px;
}
body.ts-thirdparty-events .ts-events-date-control {
	display: flex;
	align-items: center;
	gap: 9px;
	width: 100%;
	height: 40px;
	padding: 0 12px;
	border: 1px solid #dfe4ec;
	border-radius: 8px;
	background: #fff;
	color: #475569;
	font-size: 13px;
	font-weight: 500;
	text-decoration: none;
}
body.ts-thirdparty-events .ts-events-filter-form th:nth-child(2) > .nowrap,
body.ts-thirdparty-events .ts-events-filter-form th:nth-child(4) > [class*="fa-square"] { display: none !important; }
body.ts-thirdparty-events .ts-events-filter-form .select2-container { width: 100% !important; }
body.ts-thirdparty-events .ts-events-filter-form .select2-selection--single {
	height: 40px !important;
	min-height: 40px !important;
	border: 1px solid #dfe4ec !important;
	border-radius: 8px !important;
	background: #fff !important;
	box-shadow: none !important;
}
body.ts-thirdparty-events .ts-events-filter-form .select2-selection__rendered {
	height: 38px !important;
	padding: 0 38px 0 12px !important;
	font-size: 13px !important;
	line-height: 38px !important;
}
body.ts-thirdparty-events .ts-events-filter-form .select2-selection__arrow { top: 0 !important; right: 8px !important; height: 38px !important; }
body.ts-thirdparty-events .ts-events-filter-form button.button_search,
body.ts-thirdparty-events .ts-events-filter-form button.button_removefilter {
	display: inline-flex !important;
	align-items: center;
	justify-content: center;
	gap: 8px;
	height: 40px !important;
	margin: 0 !important;
	padding: 0 13px !important;
	border: 1px solid #dfe4ec !important;
	border-radius: 8px !important;
	background: #fff !important;
	color: #475569 !important;
	font-size: 13px;
}
body.ts-thirdparty-events .ts-events-filter-form button.button_removefilter { width: 40px; padding: 0 !important; }
body.ts-thirdparty-events .ts-events-filter-label { font-weight: 550; }
body.ts-thirdparty-events .select2-dropdown.ts-events-select-dropdown {
	border: 1px solid #dfe4ec !important;
	border-radius: 9px !important;
	background: #fff !important;
	box-shadow: 0 12px 28px rgba(15, 23, 42, .14) !important;
	overflow: hidden;
}
body.ts-thirdparty-events .ts-events-select-dropdown .select2-search { display: none !important; }
body.ts-thirdparty-events .ts-events-select-dropdown .select2-results__option {
	min-height: 38px;
	padding: 9px 12px !important;
	font-size: 13px;
}
body.ts-thirdparty-events .ts-events-select-dropdown .select2-results__option--highlighted { background: #f0edff !important; color: #4338ca !important; }
body.ts-thirdparty-events .ts-events-timeline {
	position: relative;
	margin: 0;
	padding: 4px 24px 24px 56px;
	border: 1px solid #e7e9ee;
	border-top: 0;
	border-radius: 0 0 12px 12px;
	background: #fff;
	box-shadow: 0 5px 18px rgba(15, 23, 42, .04);
}
body.ts-thirdparty-events .ts-events-timeline::before {
	position: absolute;
	top: 52px;
	bottom: 30px;
	left: 31px;
	width: 1px;
	background: #e2e7ef;
	content: "";
}
body.ts-thirdparty-events .ts-events-timeline > li.time-label {
	margin: 0 0 10px -32px;
	padding-top: 15px;
	color: #64748b;
	font-size: 12px;
	font-weight: 650;
	letter-spacing: .045em;
}
body.ts-thirdparty-events .ts-events-entry {
	position: relative;
	min-height: 86px;
	margin: 0 0 10px !important;
	padding: 14px 16px 14px 72px !important;
	border: 1px solid #e5e9f0 !important;
	border-radius: 11px !important;
	background: #fff !important;
	box-shadow: 0 2px 8px rgba(15, 23, 42, .025) !important;
}
body.ts-thirdparty-events .ts-events-entry::before {
	position: absolute;
	top: 30px;
	left: -38px;
	width: 9px;
	height: 9px;
	border: 2px solid #fff;
	border-radius: 50%;
	background: #aab6c9;
	box-shadow: 0 0 0 1px #d7dde7;
	content: "";
}
body.ts-thirdparty-events .ts-events-entry-icon {
	position: absolute;
	top: 16px;
	left: 16px;
	display: inline-flex !important;
	align-items: center;
	justify-content: center;
	width: 42px !important;
	height: 42px !important;
	padding: 0 !important;
	border-radius: 10px;
	background: #f1edff;
	color: #5b4cf0 !important;
	font-size: 17px;
}
body.ts-thirdparty-events .ts-events-entry .timeline-item {
	display: grid;
	grid-template-columns: minmax(0, 1fr) auto auto;
	align-items: center;
	gap: 6px 12px;
}
body.ts-thirdparty-events .ts-events-entry .timeline-header {
	display: contents;
}
body.ts-thirdparty-events .ts-events-entry-title {
	grid-column: 1 / -1;
	grid-row: 1;
	color: #1e293b;
	font-size: 14px;
	font-weight: 650;
	line-height: 1.4;
}
body.ts-thirdparty-events .ts-events-entry-author {
	grid-column: 1;
	grid-row: 2;
	margin: 0 !important;
	color: #475569;
	font-size: 13px;
}
body.ts-thirdparty-events .ts-events-entry-author .userimg img { width: 24px !important; height: 24px !important; }
body.ts-thirdparty-events .ts-events-entry-reference,
body.ts-thirdparty-events .ts-events-entry-time,
body.ts-thirdparty-events .ts-events-entry-status {
	position: static !important;
	float: none !important;
	margin: 0 !important;
	color: #64748b;
	font-size: 12px;
}
body.ts-thirdparty-events .ts-events-entry-reference { grid-column: 2; grid-row: 2; }
body.ts-thirdparty-events .ts-events-entry-time { grid-column: 3; grid-row: 2; }
body.ts-thirdparty-events .ts-events-entry-status { grid-column: 4; grid-row: 2; }
body.ts-thirdparty-events .ts-events-entry-reference > a:first-child { color: #4f46e5; font-weight: 650; }
body.ts-thirdparty-events .ts-events-entry-reference .infobox-action { display: none; }
body.ts-thirdparty-events .ts-events-entry-reference .timeline-btn2 {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 28px;
	height: 28px;
	margin-left: 5px;
	padding: 0 !important;
	border-radius: 6px;
}
body.ts-thirdparty-events .ts-events-entry-status .badge-status {
	min-height: 22px;
	padding: 2px 7px;
	border-radius: 999px;
	background: #eef1f6;
	color: #64748b;
	font-size: 10px;
}

@media (max-width: 1100px) {
	body.ts-thirdparty-events .ts-events-toolbar { flex-wrap: wrap; }
	body.ts-thirdparty-events .ts-events-filter-form { flex-basis: 100%; }
}
@media (max-width: 760px) {
	body.ts-thirdparty-events .ts-events-summary { grid-template-columns: minmax(0, 1fr); }
	body.ts-thirdparty-events .ts-events-summary-item:nth-child(n) { padding-left: 2px; padding-right: 2px; }
	body.ts-thirdparty-events .ts-events-summary-item:nth-child(n+2) { border-top: 1px solid #edf0f4; }
	body.ts-thirdparty-events .ts-events-pagehead { align-items: flex-start; flex-wrap: wrap; padding: 18px 16px 14px; }
	body.ts-thirdparty-events .ts-events-pagehead .ts-pagehead-actions { width: 100%; }
	body.ts-thirdparty-events .ts-events-create { width: 100%; }
	body.ts-thirdparty-events .ts-events-toolbar { padding: 0 16px 16px; }
	body.ts-thirdparty-events .ts-events-filter-form .liste_titre { grid-template-columns: minmax(0, 1fr); }
	body.ts-thirdparty-events .ts-events-filter-form th:nth-child(5),
	body.ts-thirdparty-events .ts-events-filter-form th:nth-child(2),
	body.ts-thirdparty-events .ts-events-filter-form th:nth-child(4),
	body.ts-thirdparty-events .ts-events-filter-form th:nth-child(1) { grid-column: 1; }
	body.ts-thirdparty-events .ts-events-filter-form th:nth-child(5) { grid-row: 1; }
	body.ts-thirdparty-events .ts-events-filter-form th:nth-child(2) { grid-row: 2; }
	body.ts-thirdparty-events .ts-events-filter-form th:nth-child(4) { grid-row: 3; }
	body.ts-thirdparty-events .ts-events-filter-form th:nth-child(1) { grid-row: 4; }
	body.ts-thirdparty-events .ts-events-timeline { padding-right: 16px; padding-left: 44px; }
	body.ts-thirdparty-events .ts-events-timeline::before { left: 24px; }
	body.ts-thirdparty-events .ts-events-entry::before { left: -26px; }
	body.ts-thirdparty-events .ts-events-entry .timeline-item { grid-template-columns: minmax(0, 1fr) auto; }
	body.ts-thirdparty-events .ts-events-entry-title { grid-column: 1 / -1; }
	body.ts-thirdparty-events .ts-events-entry-author { grid-column: 1 / -1; grid-row: 2; }
	body.ts-thirdparty-events .ts-events-entry-reference { grid-column: 1; grid-row: 3; }
	body.ts-thirdparty-events .ts-events-entry-time { grid-column: 2; grid-row: 3; }
	body.ts-thirdparty-events .ts-events-entry-status { grid-column: 3; grid-row: 3; }
}

@media (max-width: 900px) {
	body.ts-partnership-form-page .fiche { padding: 18px 16px 34px !important; }
	body.ts-partnership-form-page .ts-partnership-field-grid { gap: 22px 20px; }
}
@media (max-width: 700px) {
	body.ts-partnership-form-page .fiche { padding: 16px 12px 28px !important; }
	body.ts-partnership-form-page .ts-partnership-title { font-size: 24px; }
	body.ts-partnership-form-page .ts-partnership-info { align-items: flex-start; }
	body.ts-partnership-form-page .ts-partnership-card { padding: 18px !important; }
	body.ts-partnership-form-page .ts-partnership-field-grid { grid-template-columns: minmax(0, 1fr); }
	body.ts-partnership-form-page .ts-partnership-actions { flex-wrap: wrap; }
	body.ts-partnership-form-page .ts-partnership-actions input.button { flex: 1 1 180px; }
}
