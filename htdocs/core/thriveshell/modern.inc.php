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
   td.col-title parent is what separates a section heading from a page heading.
   ========================================================================== */
td.col-title div.titre, .col-title div.titre {
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
	.ts-pagehead-actions { width: 100%; }
	.ts-pagehead a.btnTitle.ts-primary-action { width: 100%; justify-content: center; }
}
