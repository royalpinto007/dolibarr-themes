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
a.btnTitle:has(.fa-plus-circle), a.btnTitle:has(.fa-plus) {
	display: inline-flex; align-items: center; gap: var(--sp-2);
	min-height: 34px; padding: 0 var(--sp-4);
	border-radius: var(--r);
	background: var(--c-accent); border: 1px solid var(--c-accent); color: #fff;
	font-size: 0.8125rem; font-weight: 600; line-height: 1;
	box-shadow: 0 1px 2px var(--c-accent-ring);
	transition: background var(--t), border-color var(--t);
}
a.btnTitle:has(.fa-plus-circle):hover, a.btnTitle:has(.fa-plus):hover {
	background: var(--c-accent-hover); border-color: var(--c-accent-hover);
}
a.btnTitle:has(.fa-plus-circle)::after, a.btnTitle:has(.fa-plus)::after {
	content: attr(title);
	font: inherit;
}
a.btnTitle:has(.fa-plus-circle) .btnTitle-label,
a.btnTitle:has(.fa-plus) .btnTitle-label { display: none; }   /* @title already prints it */
a.btnTitle:has(.fa-plus-circle) span[class*="fa-"],
a.btnTitle:has(.fa-plus) span[class*="fa-"] { color: #fff; }
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
	a.btnTitle:has(.fa-plus-circle)::after, a.btnTitle:has(.fa-plus)::after { content: none; }
	a.btnTitle:has(.fa-plus-circle), a.btnTitle:has(.fa-plus) { padding: 0 var(--sp-3); }
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
