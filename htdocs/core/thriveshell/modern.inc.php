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
.butAction:focus-visible, .butActionNew:focus-visible,
a.butAction:focus-visible, a.butActionNew:focus-visible {
	/* The configured action colour also owns the visible keyboard focus ring. */
	outline: 2px solid color-mix(in srgb, var(--c-btn-action, var(--c-accent)) 62%, transparent);
	outline-offset: 2px;
}
/* The primary action on a record: Dolibarr marks it butActionNew, or it is the
   first butAction in the bar. Only one is filled, so the eye has one target. */
.butActionNew, a.butActionNew,
div.tabsAction .butAction:first-of-type, div.tabsAction a.butAction:first-of-type {
	/* The filled action honours the colour configured in Display > Skin and
	   colours, falling back to the theme accent when none is set. */
	background: var(--c-btn-action, var(--c-accent));
	border-color: var(--c-btn-action, var(--c-accent));
	color: var(--c-btn-action-text, #fff);
	box-shadow: 0 1px 2px var(--c-accent-ring);
}
.butActionNew:hover, a.butActionNew:hover,
div.tabsAction .butAction:first-of-type:hover, div.tabsAction a.butAction:first-of-type:hover {
	background: var(--c-btn-action, var(--c-accent-hover));
	border-color: var(--c-btn-action, var(--c-accent-hover));
	color: var(--c-btn-action-text, #fff);
	filter: brightness(0.94);
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
/* Dolibarr often prints search and clear as adjacent icon submits inside a
   nowrap helper. Give the real controls a deliberate gap and identical square
   geometry everywhere instead of letting their borders fuse. */
.nowraponall:has(> .button_search + .button_removefilter) {
	display: inline-flex;
	align-items: center;
	gap: var(--sp-2);
}
.nowraponall:has(> .button_search + .button_removefilter) > .button_search,
.nowraponall:has(> .button_search + .button_removefilter) > .button_removefilter {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 40px;
	height: 40px;
	min-height: 40px;
	margin: 0 !important;
	padding: 0 !important;
	border: 1px solid var(--c-border) !important;
	border-radius: var(--r) !important;
	line-height: 1;
}
/* Only the commercial quick-create group gets a surface of its own.  Other
   action rows stay on their page canvas and inherit no accidental card chrome. */
.ts-commercial-create-actions {
	box-sizing: border-box;
	width: 100%;
	padding: 14px 16px;
	margin: 0 0 var(--sp-4) !important;
	background: var(--c-surface);
	border: 1px solid var(--c-border);
	border-radius: var(--r-lg);
	box-shadow: var(--sh-sm);
}
.ts-commercial-create-actions > * { flex: 0 0 auto; }
@media (max-width: 640px) {
	.ts-commercial-create-actions { justify-content: stretch; }
	.ts-commercial-create-actions > * { flex: 1 1 180px; }
}

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
	background: var(--c-btn-action, var(--c-accent));
	border-color: var(--c-btn-action, var(--c-accent));
	color: var(--c-btn-action-text, #fff);
}
div.tabsAction .ts-record-secondary {
	background: var(--c-surface);
	border-color: var(--c-border);
	color: var(--c-ink-2);
}
.ts-header-actions div.tabsAction > .ts-record-primary { order: 1; }
.ts-header-actions div.tabsAction > .ts-record-secondary { order: 2; }
.ts-header-actions div.tabsAction > .ts-more-actions { order: 3; }
/* A legacy first-of-type rule can otherwise promote the first visible action
   (often Send email) even after the composer has identified Modify as the
   primary record action. The header classes are the semantic authority. */
.ts-header-actions div.tabsAction > .ts-record-primary {
	background: var(--c-btn-action, var(--c-accent)) !important;
	border-color: var(--c-btn-action, var(--c-accent)) !important;
	color: var(--c-btn-action-text, #fff) !important;
}
.ts-header-actions div.tabsAction > .ts-record-secondary {
	background: var(--c-surface) !important;
	border-color: var(--c-border) !important;
	color: var(--c-ink-2) !important;
	box-shadow: none !important;
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
	border: 0; border-radius: var(--r-sm); box-shadow: none !important;
	/* This overrides the legacy first-of-type filled-action rule for a moved
	   Clone/Create link. Only genuinely destructive actions are red below. */
	background: transparent !important; color: var(--c-ink-2) !important; cursor: pointer;
}
.ts-more-actions-menu .ts-more-action-item:hover { background: var(--c-sunken) !important; color: var(--c-ink) !important; }
.ts-more-actions-menu .ts-more-action-item.butActionDelete { color: var(--c-danger) !important; }

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
	.ts-header-actions div.tabsAction > .ts-more-actions { flex: 1 1 100%; }
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
	background: var(--c-btn-action); border: 1px solid var(--c-btn-action); color: var(--c-btn-action-text);
	font-size: 0.8125rem; font-weight: 600; line-height: 1;
	box-shadow: 0 1px 2px var(--c-btn-action-ring);
	transition: background var(--t), border-color var(--t);
}
div.pagination a.btnTitle:has(.fa-plus-circle):hover, div.pagination a.btnTitle:has(.fa-plus):hover {
	filter: brightness(.94);
}
div.pagination a.btnTitle:has(.fa-plus-circle)::after, div.pagination a.btnTitle:has(.fa-plus)::after {
	content: attr(title);
	font: inherit;
}
div.pagination a.btnTitle:has(.fa-plus-circle) .btnTitle-label,
div.pagination a.btnTitle:has(.fa-plus) .btnTitle-label { display: none; }   /* @title already prints it */
div.pagination a.btnTitle:has(.fa-plus-circle) span[class*="fa-"],
div.pagination a.btnTitle:has(.fa-plus) span[class*="fa-"] { color: currentColor; }
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
	background: var(--c-btn-action); border: 1px solid var(--c-btn-action); color: var(--c-btn-action-text);
	font-size: 0.8125rem; font-weight: 600; line-height: 1;
	box-shadow: 0 1px 2px var(--c-btn-action-ring);
	white-space: nowrap;
}
.ts-pagehead a.btnTitle.ts-primary-action:hover { filter: brightness(.94); }
.ts-pagehead a.btnTitle.ts-primary-action span[class*="fa-"] { color: currentColor; }
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
.ts-list-composition tr.ts-filter-row-extracted { display: none !important; }
body.ts-command-record-secondary .ts-list-composition { gap: 16px; }
body.ts-command-record-secondary .ts-list-composition .ts-filter-surface { padding: 12px; }
body.ts-command-record-secondary .ts-list-composition .ts-filter-surface .ts-quick-search {
	flex: 1 1 340px;
	width: min(100%, 380px);
	max-width: 380px;
}
body.ts-command-record-secondary .ts-list-composition .ts-filter-surface .ts-quick-search-input {
	width: 100% !important;
	max-width: none !important;
}
body.ts-command-record-secondary .ts-column-filters-panel {
	/* This anchored left from when the disclosure sat at the start of the filter
	   row. The toolbar now ends with it, so opening leftwards ran the panel off
	   the card and over the table. Anchor it to the control it belongs to and
	   size it to the filters it actually holds. */
	left: auto;
	right: 0;
	grid-template-columns: repeat(auto-fit, minmax(180px, max-content));
	width: max-content;
	max-width: min(680px, calc(100vw - var(--nav-w) - 48px));
}
body.ts-command-record-secondary .ts-list-card { overflow: visible; }
body.ts-command-record-secondary .ts-list-card .ts-record-tab-surface { margin: 0 !important; }
body.ts-command-record-secondary .ts-list-card .ts-results-footer { border-radius: 0 0 10px 10px; }
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
	min-width: 280px;
	width: max-content;
	max-width: 380px;
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

/* Anchored to the toolbar rather than to the Filters button. Positioned against
   the button the panel opened leftwards from wherever that button happened to
   sit, and on a wide toolbar it started left of the content area and was clipped
   under the sidebar. The toolbar's own left edge is a stable anchor, so the
   panel stays inside the content whatever the button's position. */
/* The content column scrolls (overflow-y:auto) and is only as tall as whatever
   it holds, so on a page with no rows a dropdown opening near the bottom of the
   filter panel was clipped by the column's own edge -- the same dropdown was
   fine as soon as the list had data to make the column taller. Floor the column
   at the viewport height so a short page still has somewhere to open into. */
#id-right { min-height: calc(100vh - 108px); }

/* Submit buttons sat in flex toolbars with the default flex-shrink, so a narrow
   toolbar squeezed them below their own label -- the projects filter bar cut
   "Refresh" down to "Refre" against an overflow:clip. A button is not the give
   in a layout; let the fields around it take the pressure instead. */
input[type="submit"],
input[type="button"],
button[type="submit"] {
	flex-shrink: 0;
	min-width: max-content;
}

/* A date range is two fields, and stacking them made its control twice the
   height of every other filter, which set the row height for the whole grid.
   Put the pair on one row under a full-width label so a range costs the same
   height as a single field.

   The field does not shrink on its own: the input sits inside a wrapper that
   holds its own width, and the input carries a matching min-width, so
   constraining either alone left the pair overflowing its box. Release both. */
.ts-column-filter-control:has(> .nowrapfordate ~ .nowrapfordate) {
	grid-column: span 2;
	grid-template-columns: max-content max-content;
	column-gap: var(--sp-3);
	align-items: center;
	justify-content: start;
}
.ts-column-filter-control:has(> .nowrapfordate ~ .nowrapfordate) > .ts-column-filter-label {
	grid-column: 1 / -1;
}
.ts-column-filter-control > .nowrapfordate,
.ts-column-filter-control > .nowrapfordate > .nowraponall {
	display: flex;
	align-items: center;
	gap: var(--sp-1);
	min-width: 0;
	max-width: 100%;
}
.ts-column-filter-control > .nowrapfordate .nowraponall > *:not(input) {
	flex: none;
}

/* A widget with no data still reserved a 200px placeholder box and then wrote
   "Not enough data..." underneath it, so the card was mostly empty space with a
   caption on the floor. There is nothing to plot, so drop the box and let the
   message stand on its own. */
body.ts-command-module-index .nographyet {
	display: none;
}

/* Dashboard toolbar controls. The picker is a select2 like every other dropdown
   in the theme, but it sits straight inside the form rather than in a record
   table, so none of the field styling reached it and it kept the native look
   next to its unstyled Refresh button. */
body.ts-command-module-index form > .select2-container .select2-selection--single,
body.ts-command-module-index .fichecenter .select2-container .select2-selection--single {
	height: 38px;
	border: 1px solid var(--c-border);
	border-radius: var(--r-sm);
	background: var(--c-surface);
}
body.ts-command-module-index form > .select2-container .select2-selection__rendered,
body.ts-command-module-index .fichecenter .select2-container .select2-selection__rendered {
	line-height: 36px;
	color: var(--c-text);
}
body.ts-command-module-index form > .select2-container .select2-selection__arrow,
body.ts-command-module-index .fichecenter .select2-container .select2-selection__arrow {
	height: 36px;
}
body.ts-command-module-index input[type="submit"],
body.ts-command-module-index input[type="button"] {
	height: 38px;
	padding: 0 var(--sp-4);
	border: 1px solid var(--c-border);
	border-radius: var(--r-sm);
	background: var(--c-surface);
	color: var(--c-text);
	font-size: 0.8125rem;
	font-weight: 600;
	line-height: 1;
	vertical-align: middle;
	cursor: pointer;
}
body.ts-command-module-index input[type="submit"]:hover,
body.ts-command-module-index input[type="button"]:hover {
	border-color: var(--c-primary);
	color: var(--c-primary);
}

/* Statistics filter forms, corrected in place rather than restyled. An earlier
   pass rebuilt the table and made things worse -- the label column came out
   narrower and wrapped more than before -- so this touches only the three things
   that are actually wrong: labels wrapping mid-phrase, the picto sitting on its
   own line above the field it belongs to, and the field stretching the row. */
body.ts-command-stats form td {
	vertical-align: middle;
}
body.ts-command-stats form td:first-child {
	white-space: nowrap;
	padding-right: var(--sp-4);
}
/* The gutter has to be reserved, not merely offered. A statistics page is also a
   secondary record tab, and that tab's own cell padding is set from a longer
   selector, so the gutter collapsed to 12px and the icon -- which sits at the
   gutter's left edge -- ended up underneath the field. Match that selector's
   reach so the gutter holds. */
body.ts-command-stats form.ts-record-tab-native-surface td + td,
body.ts-command-stats form td + td {
	padding-left: 40px !important;
}
/* Give the icon a gutter of its own so the fields line up in one column whether
   a row has an icon or not. Previously a row without one started its field where
   the icon would have been, and the form read as two ragged columns.

   Only a leading icon moves into the gutter: some rows carry a trailing hint
   icon after the field, and that one belongs where it is. */
body.ts-command-stats form td + td {
	position: relative;
	padding-left: 40px;
}
/* The markup puts a hard space after a leading icon, and that space stays in
   flow once the icon is lifted into the gutter, so an icon row still sat eight
   pixels right of a plain one. Pad the plain rows by the same amount. */
body.ts-command-stats form td + td:not(:has(> span.fas:first-child)):not(:has(> span.far:first-child)):not(:has(> img.pictofixedwidth:first-child)) {
	padding-left: 48px;
}
body.ts-command-stats form td + td > span.fas:first-child,
body.ts-command-stats form td + td > span.far:first-child,
body.ts-command-stats form td + td > img.pictofixedwidth:first-child {
	position: absolute;
	left: var(--sp-3);
	top: 50%;
	transform: translateY(-50%);
	margin: 0;
}
body.ts-command-stats form td > span.fas,
body.ts-command-stats form td > span.far,
body.ts-command-stats form td > img.pictofixedwidth {
	vertical-align: middle;
	margin-right: var(--sp-2);
}
body.ts-command-stats form td .select2-container {
	width: auto !important;
	min-width: 220px !important;
	max-width: 100%;
	vertical-align: middle;
}
/* Every dropdown here draws its arrow hard against its own right border. On the
   wide ones that only looks tight; on a short one such as the year it collides
   with the value. Hold the arrow off the edge and keep the text clear of it. */
body.ts-command-stats form .select2-container .select2-selection__arrow {
	right: 6px !important;
}
body.ts-command-stats form .select2-container .select2-selection__rendered {
	padding-right: 26px;
}
/* The bordered box does not inherit the width the container was given, so
   widening the container alone left the year field small with its chevron
   stranded outside the border. */
body.ts-command-stats form td .select2-container .select2-selection--single,
body.ts-command-stats form td .select2-container .select2-selection--multiple {
	width: 100% !important;
	box-sizing: border-box;
}
/* Tag pickers are multi-selects, which carry different markup from the single
   selects beside them, so they kept the pre-theme box while everything else on
   the form had been brought into line. */
/* select2 puts its own search field inside the multi-select, so it must not pick
   up the bordered field treatment or the control renders as a box in a box. */
body.ts-command-stats form td .select2-search__field {
	border: 0 !important;
	background: transparent !important;
	height: 30px !important;
	padding: 0 !important;
	min-width: 60px;
}
body.ts-command-stats form td .select2-container .select2-selection--multiple {
	min-height: 38px;
	padding: 2px var(--sp-2);
	border: 1px solid var(--c-border);
	border-radius: var(--r-sm);
	background: var(--c-surface);
}
body.ts-command-stats form td input.flat:not(.select2-search__field):not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]) {
	height: 38px;
	padding: 0 var(--sp-3);
	border: 1px solid var(--c-border);
	border-radius: var(--r-sm);
	background: var(--c-surface);
	box-sizing: border-box;
	vertical-align: middle;
}

/* Avatars inside a dropdown. Dolibarr serves the full-size user photo and the
   option row stretched to it, so picking an author opened a list of portraits.
   The photo is an identifier here, not the content: size it to the line. */
.select2-dropdown img,
.select2-results__option img,
.select2-container .select2-selection__rendered img,
.select2-container .select2-selection__choice img {
	width: 20px !important;
	height: 20px !important;
	max-width: 20px !important;
	max-height: 20px !important;
	border-radius: 50%;
	object-fit: cover;
	vertical-align: middle;
	margin-right: 6px;
}
.select2-results__option {
	display: flex;
	align-items: center;
	gap: 2px;
}

/* Date fields in a filter row. The wrapper was 118px while holding a 112px
   input and a 34px picker, so the picker overflowed its own wrapper and came to
   rest between the two fields, reading as though it belonged to neither. Let the
   wrapper take the width its contents need, and trim the input to make room.
   The inputs also ran shorter than the picker beside them and the search box
   further along the bar, which is what made the row look ragged. */
.ts-column-filter-control .nowrapfordate,
.ts-column-filter-control .nowraponall {
	width: auto !important;
	max-width: none !important;
	flex: 0 0 auto;
}
.ts-column-filter-control .nowrapfordate input.maxwidthdate {
	width: 92px !important;
	min-width: 0 !important;
	max-width: none !important;
}
.ts-column-filter-control .nowrapfordate input:not([type="hidden"]),
.ts-column-filter-control > input.flat:not([type="checkbox"]):not([type="radio"]) {
	height: 34px;
	box-sizing: border-box;
	vertical-align: middle;
}
.ts-column-filter-control .nowrapfordate img.ui-datepicker-trigger {
	width: 30px;
	height: 30px;
	flex: none;
}

/* select2 puts its own search field inside a multi-select. Given the bordered
   field treatment the theme applies to inputs, that inner field draws a second
   box inside the control -- most visible once the control is focused and the
   search field appears. The control already has a border; the field inside it
   should not. Applies wherever a multi-select is used, not just on the pages
   where it was first noticed. */
.select2-container .select2-search--inline .select2-search__field,
.select2-container .select2-selection--multiple .select2-search__field,
.select2-container .select2-search__field,
td .select2-container .select2-search__field {
	height: auto !important;
	min-height: 26px;
	margin: 0 !important;
	padding: 0 2px !important;
	border: 0 !important;
	border-radius: 0 !important;
	background: transparent !important;
	box-shadow: none !important;
	outline: none !important;
}
.select2-container .select2-selection--multiple {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	gap: 4px;
	padding: 3px 6px;
}
.select2-container .select2-selection--multiple ul.select2-selection__rendered {
	display: contents;
	margin: 0;
	padding: 0;
	list-style: none;
}

/* The results table below these forms is composed into a card, but the filter
   block above it kept the bare legacy table with a grey bar on top, so the two
   halves of the same page did not look related. Give the filter the same card:
   a bordered surface with its heading as the header row. Chrome only -- the
   field alignment inside is left alone. */
body.ts-command-stats form table.border,
body.ts-command-stats form table.noborder,
body.ts-command-stats form > table {
	width: 100%;
	border: 1px solid var(--c-border);
	border-radius: var(--r-lg);
	background: var(--c-surface);
	border-collapse: separate;
	border-spacing: 0;
	overflow: hidden;
	box-shadow: var(--sh-sm);
}
body.ts-command-stats form tr.liste_titre > td,
body.ts-command-stats form tr.liste_titre > th {
	padding: var(--sp-3) var(--sp-4);
	border: 0;
	border-bottom: 1px solid var(--c-border);
	background: #f6f8fb;
	color: var(--c-text);
	font-size: 0.75rem;
	font-weight: 650;
	letter-spacing: 0.03em;
	text-transform: uppercase;
}
body.ts-command-stats form tr:not(.liste_titre) > td {
	background: var(--c-surface);
}

/* Refresh submits the form, so it carries the action colour the theme uses for
   every other primary button rather than the neutral chrome it inherited. */
body.ts-command-stats form input[type="submit"] {
	height: 38px;
	padding: 0 var(--sp-5);
	border: 1px solid var(--c-btn-action);
	border-radius: var(--r-sm);
	background: var(--c-btn-action);
	color: var(--c-btn-action-text);
	font-size: 0.8125rem;
	font-weight: 600;
	cursor: pointer;
}
body.ts-command-stats form input[type="submit"]:hover {
	filter: brightness(1.07);
}

/* A statistics table with nothing to report rendered as a row of headings over
   empty space, which reads as though it were still loading. */
body.ts-command-stats .ts-stats-empty-row > td {
	padding: var(--sp-6) var(--sp-4) !important;
	border: 0 !important;
	background: var(--c-surface) !important;
	text-align: center;
	color: var(--c-muted);
	font-size: 0.8125rem;
}
body.ts-command-stats .ts-stats-empty-inner {
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	gap: var(--sp-2);
}
body.ts-command-stats .ts-stats-empty-icon {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 42px;
	height: 42px;
	border-radius: 10px;
	background: #f4f1ff;
	color: var(--c-primary);
	font-size: 18px;
}

/* Controls promoted out of the toolbar arrive carrying the toolbar's own layout
   class, which seats a label beside its field. In the panel every control stacks
   its label above a full-width field, so a promoted one squeezed its field into
   whatever the heading left over -- a few characters wide, and its dropdown
   rendered one letter per line. Put them in the panel's layout, and keep a long
   heading to two lines so it cannot crowd the field again. */
.ts-column-filters-panel > .ts-column-filter-control {
	display: grid !important;
	grid-template-columns: minmax(0, 1fr) !important;
	gap: var(--sp-1);
	align-items: start;
	min-width: 0;
}
/* Dolibarr marks several relational selectors minwidth500. That describes the
   old table layout, not a COMMAND filter-panel slot: honoring it here makes one
   filter spill across two columns and breaks the panel rhythm. The shared
   control/popup sizing below already matches a bounded visible trigger. */
.ts-column-filters-panel > .ts-column-filter-control:has(.select2-container.minwidth500),
.ts-column-filters-panel > .ts-column-filter-control:has(select.minwidth500) {
	grid-column: auto;
	width: auto !important;
}
/* A date range still wants its two fields side by side; the single column above
   is for the promoted controls, not for these. */
.ts-column-filters-panel > .ts-column-filter-control:has(> .nowrap ~ .nowrap) {
	grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
	column-gap: var(--sp-3);
	justify-content: start;
	align-items: center;
}
.ts-column-filters-panel > .ts-column-filter-control:has(> .nowrap ~ .nowrap) > .ts-column-filter-label {
	grid-column: 1 / -1;
}
.ts-column-filters-panel > .ts-column-filter-control:has(> .nowrap ~ .nowrap) > .nowrap {
	display: flex;
	align-items: center;
	min-width: 0;
	gap: var(--sp-1);
}
.ts-column-filters-panel > .ts-column-filter-control:has(> .nowrap ~ .nowrap) > .nowrap .divfordateinput {
	display: flex !important;
	align-items: center;
	width: 100%;
	min-width: 0;
}
.ts-column-filters-panel > .ts-column-filter-control:has(> .nowrap ~ .nowrap) > .nowrap input.hasDatepicker {
	width: 100% !important;
	min-width: 0;
}
/* One line per heading. A heading that wrapped pushed its own control down a
   row, so controls on the same line no longer started at the same height and the
   panel read as though it were misaligned. The full wording stays available on
   hover. */
.ts-column-filters-panel > .ts-column-filter-control > .ts-column-filter-label {
	display: block;
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis;
	line-height: 1.3;
}
.ts-column-filters-panel > .ts-column-filter-control > .select2-container,
.ts-column-filters-panel > .ts-column-filter-control > select,
.ts-column-filters-panel > .ts-column-filter-control > input:not([type="hidden"]):not([type="checkbox"]):not([type="radio"]) {
	width: 100% !important;
	min-width: 0 !important;
	max-width: none !important;
	box-sizing: border-box;
}
/* Some legacy multi-selects (notably role/contact filters) wrap Select2 in an
   extra span and carry minwidth500 on the generated selection. The outer
   container was bounded but the inner selection still expanded to 500px,
   visually escaping its grid slot. Keep every level of an in-panel Select2
   bound to the same filter-control width. */
.ts-column-filters-panel > .ts-column-filter-control :is(.multiselectarraysearch, .multiselectarraysearch_roles, .select2-container, .select2-selection) {
	box-sizing: border-box;
	width: 100% !important;
	min-width: 0 !important;
	max-width: 100% !important;
}

/* While the column picker is open its panel must be allowed out of the boxes
   that hold the list, which otherwise cut it off on a short or empty list. The
   marker is removed as soon as the picker closes, so scrolling a wide list is
   unaffected the rest of the time. */
.ts-list-card.ts-picker-open,
.div-table-responsive.ts-picker-open,
.div-table-responsive-no-min.ts-picker-open {
	overflow: visible !important;
}

/* Module logos are shown at whatever size the file happens to be, and a handful
   ship large -- a 300px and two 256px images sat among icons drawn at 18px, so
   those rows towered over the rest of the list. Cap them and let the image keep
   its proportions inside that box. A ceiling, not a size, so the many logos that
   are already small are left alone. */
img.pictomodule {
	max-width: 24px;
	max-height: 24px;
	object-fit: contain;
	vertical-align: middle;
}

/* Held only while a widget table is measured. Every cap inside it is lifted so
   each column reports the width its content actually needs -- including the
   nested table a reference sits in, which is otherwise capped at the width of
   the cell being measured. Removed again in the same pass. */
body.ts-command-module-index .ts-module-index-card.ts-measuring table,
body.ts-command-module-index .ts-module-index-card.ts-measuring td,
body.ts-command-module-index .ts-module-index-card.ts-measuring a,
body.ts-command-module-index .ts-module-index-card.ts-measuring span {
	max-width: none !important;
	min-width: 0 !important;
	overflow: visible !important;
	text-overflow: clip !important;
}

/* A reference link in a widget carries an icon, which is enough for the
   icon-button treatment elsewhere in the theme to claim it: it was laid out as a
   flex box with a floor of one icon width and its own overflow hidden, so the
   reference beside the icon was cut off whatever width the column had. These
   links are text with an icon in front, not buttons. */
body.ts-command-module-index .ts-module-index-card td a,
body.ts-command-module-index .ts-module-index-card td a.classforajaxtooltip {
	display: inline !important;
	min-width: 0 !important;
	max-width: none !important;
	overflow: visible !important;
	white-space: nowrap;
}
body.ts-command-module-index .ts-module-index-card td a > .fas,
body.ts-command-module-index .ts-module-index-card td a > .far,
body.ts-command-module-index .ts-module-index-card td a > img {
	display: inline-block !important;
	vertical-align: middle;
	margin-right: 4px;
	min-width: 0 !important;
}

/* A user photo standing in a column heading is an identifier, not the content:
   the everybody placeholder came through at its own 64px and towered over the
   headings around it. Size it to the line, as the same photo already is in a
   dropdown. */
table tr.liste_titre img.photouserphoto,
table tr.liste_titre img.userphoto {
	width: 24px !important;
	height: 24px !important;
	max-width: 24px;
	max-height: 24px;
	border-radius: 50%;
	object-fit: cover;
	vertical-align: middle;
}

/* Title-bar buttons.

   The icon inside carries margins of its own, which pushed it off centre in a
   button that holds nothing else -- about four pixels right, enough to read as
   crooked when three sit in a row. Earlier attempts to single out the icon-only
   buttons failed because a labelled one keeps its label as a bare text node,
   which :has() cannot see, so the guard caught those too and squashed them.

   No detection is needed: clear the icon's own margins and let flex spacing
   separate it from a label when there is one. An anonymous text node is still a
   flex item, so the gap applies to a labelled button exactly as it does between
   two elements, and an icon on its own ends up centred because nothing offsets
   it any more. */
a.btnTitle {
	gap: 6px;
}
a.btnTitle > .btnTitle-icon,
a.btnTitle > [class*="fa-"] {
	margin: 0 !important;
}
/* and one button is not the next */
a.btnTitle + a.btnTitle {
	margin-left: 6px;
}

/* A field table that sits straight in a section, with no half-column around it.

   On a record card these tables are inside .fichehalfleft or .fichehalfright,
   which carry the section surface, so they read as cards. The selling price tab
   puts its table directly in the section instead, and it was left standing on
   the page background: rows of tax rate, price and label with no surface under
   them, beside a price-history table that does have one. Give it the same
   surface the halves have. */
body.ts-command-record-page .fichecenter > table.tableforfield {
	width: 100%;
	margin: 0 0 var(--sp-4);
	border: 1px solid var(--c-hairline) !important;
	border-radius: var(--r-lg);
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
	border-collapse: separate !important;
	border-spacing: 0;
	overflow: hidden;
	box-sizing: border-box;
}
body.ts-command-record-page .fichecenter > table.tableforfield > tbody > tr > td {
	padding: 11px var(--sp-4) !important;
	border: 0 !important;
	background: transparent !important;
	font-size: 0.8125rem;
	line-height: 1.35;
}
body.ts-command-record-page .fichecenter > table.tableforfield > tbody > tr + tr > td {
	border-top: 1px solid var(--c-hairline) !important;
}
body.ts-command-record-page .fichecenter > table.tableforfield > tbody > tr > td:first-child {
	width: 260px;
	color: var(--c-ink-2, var(--c-muted));
	font-weight: 600;
}

/* Record page section rows.

   The two halves are floated inside a block, so the next row cannot begin until
   the taller column clears: on a product card the second row touched the card
   above it, and the shorter right column left a gap of its own beneath it.

   Laying the halves out as a row of two gives the columns a common height and
   the rows a gutter. Only the halves are touched -- an earlier attempt also gave
   their children a full height, which made the field tables inside claim the
   whole column and spill their content over the row below. */
body.ts-command-record-page .fichecenter:has(> .fichehalfleft),
body.ts-command-record-page .fichecenter:has(> .fichehalfright) {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	gap: 16px;
	align-items: stretch;
}
body.ts-command-record-page .fichecenter > .fichehalfleft,
body.ts-command-record-page .fichecenter > .fichehalfright {
	float: none !important;
	width: auto !important;
	min-width: 0;
	margin: 0 !important;
}
body.ts-command-record-page .fichecenter + .fichecenter,
body.ts-command-record-page .fichecenter + .clearboth + .fichecenter {
	margin-top: 16px;
}
@media (max-width: 900px) {
	body.ts-command-record-page .fichecenter:has(> .fichehalfleft),
	body.ts-command-record-page .fichecenter:has(> .fichehalfright) {
		grid-template-columns: minmax(0, 1fr);
	}
}

/* The submit row of a statistics filter sits after the table rather than inside
   it, so it landed on the card's bottom edge with the border running through the
   button. Give it room of its own. */
body.ts-command-stats form > div.center,
body.ts-command-stats form div.center:has(> input[type="submit"]) {
	margin-top: var(--sp-4);
}

/* A statistics table heads each block with a title and, in a cell of its own, the
   control that recalculates it. That cell is already right aligned, but the title
   beside it is only as wide as its text, so the control came to rest in the
   middle of the card. Let the title take the width and the control keeps to the
   edge. */
body.ts-command-stats table.noborder > tbody > tr.liste_titre > td:first-child:not(:last-child),
body.ts-command-stats table.liste > tbody > tr.liste_titre > td:first-child:not(:last-child) {
	width: calc(100% - 52px) !important;
}
body.ts-command-stats table.noborder > tbody > tr.liste_titre > td:last-child:not(:first-child),
body.ts-command-stats table.liste > tbody > tr.liste_titre > td:last-child:not(:first-child) {
	width: 52px !important;
	padding-left: 0 !important;
	white-space: nowrap;
	text-align: right;
}

.ts-column-filters { position: relative; flex: 0 0 auto; }
.ts-filter-surface { position: relative; }
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
	/* Anchored to the Filters control so the panel opens beneath the button that
	   summons it. It was anchored to the toolbar instead because a fixed-width
	   panel opening leftwards from a button could start left of the content and
	   be clipped by the sidebar; the width below is capped against the space
	   actually available, so it can shrink rather than overflow. */
	left: auto;
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

/* Below this width the Filters button sits far enough left that a panel hanging
   from it would start outside the content and be clipped by the sidebar, which
   CSS cannot prevent while the width is fixed and the anchor is the button. Fall
   back to the toolbar edge, where it always fits. */
@media (max-width: 1439px) {
	/* Release the button as the anchor as well, or left:0 measures from the
	   button and the panel runs off the other edge instead. */
	.ts-column-filters {
		position: static;
	}
	.ts-column-filters-panel {
		left: 0;
		right: auto;
	}
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
	/* Content decides, within a ceiling: a fixed width clipped longer values
	   and left short ones padded out. */
	/* Content decides. An earlier floor of 300px was meant to stop long values
	   clipping, but it also inflated a two-word tooltip into a slab; max-width
	   alone does the job. */
	width: max-content;
	min-width: 0;
	max-width: 420px;
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
.ui-tooltip.mytooltip .ui-tooltip-content {
	position: relative;
	padding: 0;
}
/* Company previews may include a 40px logo floated by Dolibarr. Take it out
   of the legacy float flow and reserve a separate slot so the status pill and
   image keep a small, intentional gap instead of colliding. */
.ui-tooltip.mytooltip .ui-tooltip-content > .photointooltip {
	position: absolute;
	top: 0;
	right: 0;
	float: none !important;
	margin: 0;
}
.ui-tooltip.mytooltip .ui-tooltip-content > .photointooltip img {
	display: block;
	width: 40px;
	height: 40px;
	object-fit: contain;
	border-radius: 6px;
}
.ui-tooltip.mytooltip .ui-tooltip-content:has(> .photointooltip) .ts-tooltip-header {
	padding-right: 136px;
}
.ui-tooltip.mytooltip .ui-tooltip-content:has(> .photointooltip) .ts-tooltip-status {
	right: 64px;
}
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
	/* Absolute positioning pinned every badge to the same corner, so a record
	   carrying two statuses -- a product that is neither for sale nor for
	   purchase -- drew them on top of each other. Let them flow to the right so
	   any number sit side by side. */
	position: static;
	float: right;
	margin: 0 0 0 6px;
}
/* Contain the floated badges so they cannot ride over the line beneath. */
.ui-tooltip.mytooltip .ui-tooltip-content::after {
	content: '';
	display: table;
	clear: both;
}
.ui-tooltip.mytooltip b,
.ui-tooltip.mytooltip strong {
	display: inline-block;
	/* A fixed width here collided with its own value: labels longer than 112px,
	   such as "Amount (excl. tax):", overflowed straight into the number. Treat
	   it as a floor and keep a gap after it. */
	min-width: 0;
	padding-right: 0;
	margin-right: 0;
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
	position: relative;
	display: flex;
	align-items: center;
	gap: var(--sp-2);
	min-width: 0;
	padding-right: 72px;
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
	position: absolute;
	top: 0;
	right: 0;
	margin: 0;
	flex: none;
}
/* One grid for the whole list rather than a fixed column per row: at 112px a
   longer label such as "Amount (excl. tax):" ran straight into its own value,
   and each row measured independently so nothing lined up. Letting the labels
   share a content-sized column aligns them and gives each the width it needs. */
/* The separator Dolibarr writes between contact details and accounting codes,
   drawn as the rule it stands for rather than left as blank space. */
.ui-tooltip.mytooltip .ts-tooltip-rule {
	display: none;
}
/* Any break that survives into the structured list would add space of its own on
   top of the row spacing. */
.ui-tooltip.mytooltip .ts-tooltip-details br {
	display: none;
}
.ui-tooltip.mytooltip .ts-tooltip-details {
	display: flex;
	flex-direction: column;
	gap: 5px;
	margin-top: 8px;
}
.ui-tooltip.mytooltip .ts-tooltip-row {
	display: grid;
	/* Sized to the label, not to a fixed 118px. The email and phone rows label
	   themselves with just an icon, so a fixed column left ~100px of dead space
	   and stranded their values out to the right while the text-labelled rows
	   sat snug against theirs. */
	grid-template-columns: auto minmax(0, 1fr);
	justify-content: start;
	align-items: start;
	column-gap: var(--sp-3);
}
/* An icon standing in for a label still occupies the label track. */
.ui-tooltip.mytooltip .ts-tooltip-row > [class*="fa-"]:first-child {
	justify-self: start;
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
/* Structured contact rows use a small predictable icon slot. Override
   Dolibarr's legacy .pictofixedwidth treatment, which reserves a wide label
   track and leaves email/phone values stranded far to the right. */
.ui-tooltip.mytooltip .ts-tooltip-details .ts-tooltip-label > [class*="fa-"] {
	flex: 0 0 16px !important;
	width: 16px !important;
	padding: 0 !important;
	margin: 0 !important;
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
/* The Contacts/Addresses grid deliberately uses centered headings. Match its
   visible data cells to those axes as well; leaving the names and metadata at
   the far-left edge of each column made the table read as visibly skewed. The
   leading bulk-selection slot remains untouched. */
body.page-contact-list .ts-list-card tr.oddeven > td:not(:first-child),
body.page-contact-list .ts-list-card tr.impair > td:not(:first-child),
body.page-contact-list .ts-list-card tr.pair > td:not(:first-child) {
	text-align: center !important;
}
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
	min-width: 126px;
	width: auto;
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

/* Shared data-table headers: Dolibarr's native header font is frequently
   rendered at a tiny, uneven weight, particularly in wide admin grids. Give
   every standard list/data header the same quiet COMMAND hierarchy without
   affecting form labels, calendar headers, or the control/filter row. */
table.liste tr.liste_titre > th,
table.liste tr.liste_titre > td,
table.tagtable tr.liste_titre > th,
table.tagtable tr.liste_titre > td,
table.noborder tr.liste_titre > th,
table.noborder tr.liste_titre > td {
	/* Keep table headers on the configured COMMAND typeface.  A separate
	   system-ui stack here made admin grids visibly different whenever the
	   theme font was changed in Display > Skin and colors. */
	font-family: var(--c-font) !important;
	font-size: 0.8125rem !important;
	font-weight: 600 !important;
	letter-spacing: 0.005em;
	line-height: 1.25 !important;
	color: var(--c-ink-subtle) !important;
	text-transform: none !important;
}
table.liste tr.liste_titre > th > a,
table.liste tr.liste_titre > td > a,
table.tagtable tr.liste_titre > th > a,
table.tagtable tr.liste_titre > td > a,
table.noborder tr.liste_titre > th > a,
table.noborder tr.liste_titre > td > a {
	font: inherit;
	color: inherit !important;
	text-decoration: none;
}
table.liste tr.liste_titre [class*="fa-"],
table.tagtable tr.liste_titre [class*="fa-"],
table.noborder tr.liste_titre [class*="fa-"] {
	font-family: "Font Awesome 5 Free", "Font Awesome 6 Free" !important;
	font-weight: 900 !important;
	letter-spacing: 0;
}

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
	border-bottom: 0;
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
/* Relational Select2 fields often carry Dolibarr's adjacent "new" link
   (the fa-plus-circle action).  It is a sibling of the trigger in the
   original table cell; the grid above otherwise auto-places that sibling on
   a second row.  Compose these cells as one inline control group while
   preserving the original link and form behaviour. */
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value:has(> a .fa-plus-circle, > a .fa-plus) {
	display: flex;
	align-items: center;
	gap: 8px;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value:has(> a .fa-plus-circle, > a .fa-plus) > .select2-container,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value:has(> a .fa-plus-circle, > a .fa-plus) > span:has(> .select2-container),
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value:has(> a .fa-plus-circle, > a .fa-plus) > div:has(> .select2-container) {
	flex: 1 1 auto;
	width: auto !important;
	min-width: 0 !important;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value:has(> a .fa-plus-circle, > a .fa-plus) > a:has(.fa-plus-circle),
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value:has(> a .fa-plus-circle, > a .fa-plus) > a:has(.fa-plus) {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	flex: 0 0 32px;
	width: 32px;
	height: 32px;
	margin: 0;
	border-radius: 7px;
	color: var(--c-accent) !important;
}
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value:has(> a .fa-plus-circle, > a .fa-plus) > a:has(.fa-plus-circle):hover,
body#mainbody form.ts-modern-form table.ts-modern-form-table td.ts-form-value:has(> a .fa-plus-circle, > a .fa-plus) > a:has(.fa-plus):hover {
	background: var(--c-accent-soft);
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
	border: 1px solid var(--c-btn-action) !important;
	border-radius: 8px !important;
	background: var(--c-btn-action) !important;
	color: var(--c-btn-action-text) !important;
	box-shadow: 0 3px 8px var(--c-btn-action-ring);
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
body.ts-category-dialog-create-page .center:has(input[type="submit"]) input[name="creation"] { border: 1px solid var(--c-btn-action) !important; background: var(--c-btn-action) !important; color: var(--c-btn-action-text) !important; box-shadow: 0 3px 8px var(--c-btn-action-ring); }
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
/* The compound layout owns the two tracks, while the shared semantic select
   classifier owns the compact trigger inside track one. This prevents a short
   enum such as Incoterms from leaving a detached chevron across the whole row. */
body#mainbody form.ts-modern-form tr.ts-form-compound-incoterms td.ts-form-value > .select2-container.ts-command-select-compact {
	width: min(100%, 340px) !important;
	justify-self: start;
}
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
	background: var(--c-btn-action, var(--c-accent));
	border-color: var(--c-btn-action, var(--c-accent));
	color: var(--c-btn-action-text, #fff);
	box-shadow: 0 2px 5px var(--c-accent-ring);
}
form.ts-modern-form .ts-modern-form-actions input.button-save:hover {
	background: var(--c-btn-action, var(--c-accent-hover));
	border-color: var(--c-btn-action, var(--c-accent-hover));
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
	--kpi-tint: #eef2f7;
	--kpi-ink: #475569;
	--kpi-deep: #1e293b;
	position: relative;
	display: grid !important;
	grid-template-columns: 50px minmax(0, 1fr);
	align-items: start !important;
	column-gap: 14px;
	min-height: 118px !important;
	height: 100% !important;
	padding: 16px !important;
	border: 1px solid #e7e9ee !important;
	border-radius: 11px !important;
	background: #fff !important;
	box-shadow: 0 1px 2px rgba(15, 23, 42, .03), 0 10px 26px rgba(15, 23, 42, .05) !important;
	overflow: hidden;
}
/* A wash of the card's own colour in the corner, and a fine dot field over it.
   Both are drawn rather than placed, so there is no image to load and the colour
   follows the accent. They are positioned out of the grid so the two columns are
   unaffected. */
body.ts-command-dashboard .ts-dashboard-summary-card::before {
	content: "";
	position: absolute;
	top: 0;
	right: 0;
	width: 96px;
	height: 96px;
	background: radial-gradient(circle at 100% 0, var(--kpi-tint) 0, var(--kpi-tint) 58px, transparent 59px);
	pointer-events: none;
}
body.ts-command-dashboard .ts-dashboard-summary-card::after {
	content: "";
	position: absolute;
	top: 14px;
	right: 14px;
	width: 34px;
	height: 26px;
	background-image: radial-gradient(var(--kpi-ink) 1px, transparent 1px);
	background-size: 7px 7px;
	opacity: 0.28;
	pointer-events: none;
}
/* The title carries a short rule in the card's colour, which is what gives the
   set its rhythm when several sit side by side. */
body.ts-command-dashboard .ts-dashboard-summary-card .info-box-title::after {
	content: "";
	display: block;
	width: 34px;
	height: 4px;
	margin: 7px 0 2px;
	border-radius: 999px;
	background: linear-gradient(90deg, var(--kpi-ink), var(--kpi-deep));
}
body.ts-command-dashboard .ts-dashboard-summary-card .info-box-icon {
	display: flex !important;
	align-items: center !important;
	justify-content: center !important;
	grid-column: 1;
	grid-row: 1;
	width: 46px !important;
	height: 46px !important;
	min-width: 46px !important;
	border-radius: 50% !important;
	background: linear-gradient(145deg, var(--kpi-ink), var(--kpi-deep)) !important;
	color: #fff !important;
	box-shadow: 0 0 0 4px var(--kpi-tint) !important;
	margin: 0 !important;
	padding: 0 !important;
	border: 0 !important;
	font-size: 20px !important;
}
/* The weather card is styled separately upstream, by a rule that names the
   container and matches the weather class, and that rule is more specific than
   the one describing every other card here -- so this one card kept a short
   square icon while the rest became round tokens. Match its weight so the set
   reads as one. */
body.ts-command-dashboard div.box-flex-container .ts-dashboard-summary-card[class*="weather"] .info-box-icon {
	width: 46px !important;
	height: 46px !important;
	min-width: 46px !important;
	border-radius: 50% !important;
	background: linear-gradient(145deg, var(--kpi-ink), var(--kpi-deep)) !important;
	color: #fff !important;
	box-shadow: 0 0 0 4px var(--kpi-tint) !important;
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
/* Each accent carries a tint, an ink and a deeper shade of the same hue. The
   card, its icon and its rule all read from these, so a colour is described once
   instead of being repeated in every rule that uses it. */
body.ts-command-dashboard .ts-dashboard-summary-card.ts-dashboard-accent-violet { --kpi-tint: #f1edff; --kpi-ink: #7047eb; --kpi-deep: #4a2fb0; }
body.ts-command-dashboard .ts-dashboard-summary-card.ts-dashboard-accent-blue   { --kpi-tint: #eaf1ff; --kpi-ink: #3474e8; --kpi-deep: #1d4ea8; }
body.ts-command-dashboard .ts-dashboard-summary-card.ts-dashboard-accent-green  { --kpi-tint: #eaf8ee; --kpi-ink: #24a65a; --kpi-deep: #14713c; }
body.ts-command-dashboard .ts-dashboard-summary-card.ts-dashboard-accent-cyan   { --kpi-tint: #e8f7fb; --kpi-ink: #1597b8; --kpi-deep: #0c6a83; }
body.ts-command-dashboard .ts-dashboard-summary-card.ts-dashboard-accent-orange { --kpi-tint: #fff1e6; --kpi-ink: #e66a13; --kpi-deep: #a8480a; }
body.ts-command-dashboard .ts-dashboard-summary-card.ts-dashboard-accent-rose { --kpi-tint: #fdebf5; --kpi-ink: #d94b91; --kpi-deep: #9c2f63; }
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

/* The widget primitives below are written against the widget markup rather than
   against the home dashboard, so any page that renders Dolibarr boxes -- a module
   dashboard, or one a custom module adds -- is described by the same vocabulary
   without being named here. Only the section grouping and the two-column sorting
   surface stay tied to the home dashboard, which is the page that has them. */
/* Widgets are grouped into sections, and each section packs its own widgets into
   balanced columns.

   Two independent stacks ended at wildly different points -- 4130px against
   3005px on the home dashboard -- and a row grid is no better, because every row
   is as tall as the tallest card in it, so a 429px chart beside an 83px widget
   leaves a hole the height of the difference. Columns pack by height and even
   themselves out, which is what a set of mixed-height cards wants; sections give
   the reader somewhere to look rather than one long undifferentiated stream.

   The native two columns are emptied only while the dashboard is being read.
   Customize puts every widget back into them, because Dolibarr sorts within
   those two elements and dragging must keep working. */
body.ts-command-dashboard .ts-dashboard-lower-grid {
	display: block;
	clear: both;
}
body.ts-command-dashboard:not(.ts-dashboard-customizing) .ts-dashboard-lower-grid > #boxhalfleft,
body.ts-command-dashboard:not(.ts-dashboard-customizing) .ts-dashboard-lower-grid > #boxhalfright {
	display: contents;
}
body.ts-command-dashboard .ts-dash-section {
	margin: 0 0 22px;
}
body.ts-command-dashboard .ts-dash-section-title {
	margin: 0 0 10px;
	color: var(--c-muted);
	font-size: 12px;
	font-weight: 700;
	letter-spacing: 0.05em;
	text-transform: uppercase;
}
/* Columns are chosen from the narrowest card that still reads well, rather than
   fixed per breakpoint: the browser fits as many as the width allows and drops
   one when it cannot, which is the same rule at every size. */
body.ts-command-dashboard .ts-dash-section-body {
	column-width: 330px;
	column-gap: 16px;
}
.ts-dashboard-widget {
	break-inside: avoid;
	page-break-inside: avoid;
}
/* A section holding one or two widgets should not spread them thinly across
   three columns, and a lone card should not run the width of the page. */
body.ts-command-dashboard .ts-dash-section-body[data-ts-count="1"] {
	column-count: 1;
	max-width: 540px;
}
body.ts-command-dashboard .ts-dash-section-body[data-ts-count="2"] {
	column-count: 2;
}
/* Charts carry two plots side by side and need the width to stay readable. */
body.ts-command-dashboard .ts-dash-section[data-ts-section="analytics"] .ts-dash-section-body {
	column-count: 2;
}
@media (max-width: 900px) {
	body.ts-command-dashboard .ts-dash-section-body,
	body.ts-command-dashboard .ts-dash-section-body[data-ts-count="2"],
	body.ts-command-dashboard .ts-dash-section[data-ts-section="analytics"] .ts-dash-section-body {
		column-count: 1;
		column-width: auto;
	}
	body.ts-command-dashboard .ts-dash-section-body[data-ts-count="1"] { max-width: none; }
}
/* While sorting, Dolibarr's own two columns are back and carry the widgets. */
body.ts-command-dashboard.ts-dashboard-customizing .ts-dashboard-lower-grid {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	gap: 16px;
	align-items: start;
}

/* Widget titles are sized for a full-width card and overran a column card, so
   the heading ran past its own border. Keep them to the card. */
.ts-dashboard-widget .box_titre th > div:first-child {
	max-width: 100% !important;
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis;
	/* The heading text lives in this div and is styled here rather than inherited,
	   so the casing and weight have to be stated on it too. */
	color: #6b7088 !important;
	font-size: 12px !important;
	font-weight: 700 !important;
	letter-spacing: 0.48px !important;
	text-transform: uppercase !important;
}
/* The picture Dolibarr shows beside a "nothing here" line is decoration, and at
   full size it made an empty card twice the height of the message in it. */
.ts-widget--empty tr:not(.box_titre) img,
.ts-widget--empty tr:not(.box_titre) .fas,
.ts-widget--empty tr:not(.box_titre) .far {
	width: 15px !important;
	height: 15px !important;
	max-width: 15px !important;
	max-height: 15px !important;
	font-size: 13px !important;
	line-height: 1 !important;
	vertical-align: middle;
	opacity: 0.55;
}
.ts-widget--empty tr:not(.box_titre) .ts-empty-illustration,
.ts-widget--empty tr:not(.box_titre) [class*="ts-module-index-empty"] {
	display: none !important;
}

/* A widget with nothing to report states that in one line instead of holding a
   card of blank surface. */
.ts-widget--empty tr:not(.box_titre) > td {
	height: auto !important;
	padding: 12px 14px !important;
	border-bottom: 0 !important;
	color: var(--c-muted);
	font-size: 12.5px !important;
	text-align: center;
}
.ts-widget--empty tr.box_titre,
.ts-widget--empty tr.box_titre > th {
	height: 40px !important;
}
.ts-widget--empty .opacitymedium {
	opacity: 1;
}
/* The stray info icon Dolibarr trails after an empty message adds a line of its
   own; it says nothing the message has not. */
.ts-widget--empty tr:not(.box_titre) .fa-info-circle,
.ts-widget--empty tr:not(.box_titre) img[src*="info"] {
	display: none;
}

/* Charts: less air around the canvas, so the card is sized by the plot. */
.ts-widget--chart .dolgraph {
	margin: 0 auto !important;
}
.ts-widget--chart tr:not(.box_titre) > td {
	padding: 8px 10px !important;
}

/* Figure tiles. Each figure is its own link that wrapped three to a row inside a
   narrow card, which made a readout of thirty-one figures 767px tall. Laid out as
   a grid that fills the width available, the same readout is a few rows deep. */
.ts-widget--tiles tr:not(.box_titre) > td {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(124px, 1fr));
	gap: 8px;
	height: auto !important;
	padding: 12px !important;
}
.ts-widget--tiles .boxstatsindicator.thumbstat {
	display: block;
	/* The class carries a width of its own, which left each tile 35px wide inside a
	   130px track and clipped every label to its first letter. */
	width: auto !important;
	min-width: 0 !important;
	max-width: none !important;
	height: auto !important;
	min-height: 0 !important;
	margin: 0 !important;
	padding: 0 !important;
	border: 0 !important;
}
.ts-widget--tiles .boxstats {
	display: flex;
	flex-direction: column;
	gap: 3px;
	min-height: 0;
	padding: 9px 10px;
	border: 1px solid var(--c-border);
	border-radius: 9px;
	background: #fff;
	text-align: left;
}
.ts-widget--tiles .boxstats:hover {
	border-color: var(--c-primary);
}
.ts-widget--tiles .boxstatstext {
	display: -webkit-box;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
	overflow: hidden;
	color: var(--c-muted);
	font-size: 10.5px;
	font-weight: 600;
	line-height: 1.25;
	letter-spacing: 0.02em;
	text-transform: uppercase;
}
.ts-widget--tiles .boxstats br { display: none; }
.ts-widget--tiles .boxstats > .boxstatsindicator {
	display: flex;
	align-items: center;
	gap: 6px;
	color: var(--c-text);
	font-size: 15px;
	font-weight: 650;
}
/* A readout of figures earns the full width of its section rather than sitting in
   a single narrow column. */
body.ts-command-dashboard .ts-dash-section-body[data-ts-count="1"]:has(.ts-widget--tiles) {
	max-width: none;
}
/* Charts stay at two columns however wide the screen. Each of these cards holds
   two plots of about 256px side by side, so a card narrower than roughly 540px
   clips the second one -- at three columns on a 1680px screen the cards came out
   at 449px and the months ran off the axis. */
/* Charts sit in the same columns as everything else. Demanding a card wide
   enough for both plots side by side (560px) meant a single column at 1280,
   where six charts stacked into a longer page than we started with; letting the
   pair wrap inside the card instead keeps the plots at their natural size and
   the section in step with the rest of the dashboard. */
.ts-widget--chart tr:not(.box_titre) > td {
	display: flex;
	flex-wrap: wrap;
	justify-content: center;
	gap: 10px;
	height: auto !important;
}
.ts-widget--chart .dolgraph {
	flex: 0 1 auto;
	min-width: 0;
}
.ts-widget--chart .dolgraph,
.ts-widget--chart canvas {
	max-width: 100%;
}
body.ts-command-dashboard .ts-dashboard-lower-grid > #boxhalfleft,
body.ts-command-dashboard .ts-dashboard-lower-grid > #boxhalfright {
	float: none !important;
	width: auto !important;
	min-width: 0;
	margin: 0 !important;
	padding: 0 !important;
}
.ts-dashboard-widget {
	margin: 0 0 16px !important;
	border: 1px solid #e7e9ee !important;
	border-radius: 11px !important;
	background: #fff !important;
	box-shadow: 0 3px 12px rgba(15, 23, 42, .035) !important;
	overflow: hidden;
}
.ts-dashboard-widget > table.boxtable {
	margin: 0 !important;
	border: 0 !important;
	border-collapse: collapse !important;
	background: #fff !important;
}
/* The widget heading sits on a band of its own rather than on the card's white,
   which is what separates one widget from the next when several are stacked in a
   column. Small, upper case and muted, so the record rows beneath it stay the
   thing being read. */
.ts-dashboard-widget tr.box_titre,
.ts-dashboard-widget tr.box_titre > th {
	height: 44px;
	padding: 0 14px !important;
	border: 0 !important;
	border-bottom: 1px solid #e3e8f0 !important;
	background: #eef1f7 !important;
	color: #6b7088 !important;
	font-size: 12px !important;
	font-weight: 700 !important;
	letter-spacing: 0.48px !important;
	text-transform: uppercase !important;
}
.ts-dashboard-widget tr.oddeven > td,
.ts-dashboard-widget table.boxtable > tbody > tr:not(.box_titre) > td {
	height: 44px;
	padding: 10px 14px !important;
	border: 0 !important;
	border-bottom: 1px solid rgba(107, 112, 136, .14) !important;
	background: #fff !important;
	color: #4b4f66 !important;
	font-size: 13px !important;
}
.ts-dashboard-widget table.boxtable > tbody > tr:last-child > td {
	border-bottom: 0 !important;
}
.ts-dashboard-widget tr.oddeven:hover > td {
	background: #fafbff !important;
}
/* Shown only while arranging the dashboard. Quiet at rest -- a muted glyph with
   no box of its own, since a pair of bordered buttons on every heading turns the
   band into a row of chrome -- and each takes its meaning on hover: neutral for
   the handle, red for removal.

   Only the icons are sized. The element wrapping them carries the same boxclose
   class as the remove icon inside it, so a rule written for the class alone
   squeezed the wrapper to one icon's width and pushed both icons out of it --
   the remove icon ended a pixel past the heading's own edge and overlapped the
   title. */
.ts-dashboard-widget .box_titre th > div.boxclose {
	display: inline-flex !important;
	align-items: center;
	gap: 2px;
	width: auto !important;
	min-width: 0 !important;
	height: auto !important;
	padding: 0 !important;
	border: 0 !important;
	background: transparent !important;
}
.ts-dashboard-widget span.boxhandle,
.ts-dashboard-widget span.boxclose {
	display: inline-flex !important;
	align-items: center;
	justify-content: center;
	flex: 0 0 26px;
	width: 26px !important;
	min-width: 26px !important;
	height: 26px !important;
	margin: 0 !important;
	padding: 0 !important;
	border: 0 !important;
	border-radius: 8px;
	background: transparent !important;
	box-shadow: none !important;
	color: #99a0b5 !important;
	font-size: 12px !important;
	opacity: 1 !important;
	cursor: pointer;
	transition: background-color .12s ease, color .12s ease;
}
.ts-dashboard-widget span.boxhandle { cursor: move; }
.ts-dashboard-widget span.boxhandle:hover {
	background: #e2e8f1 !important;
	color: #475569 !important;
}
.ts-dashboard-widget span.boxclose:hover {
	background: #ffe4e6 !important;
	color: #e11d48 !important;
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
/* The tile is a deliberate exception to the generic "leading icon in a table
   cell" rule, which sets display:inline-block, width:1.25em and an 8px gap from a
   selector of higher specificity than a plain class. That is why the tile kept
   rendering 23x36 instead of a 36px square however the class was declared: the
   rule was correct and simply never won. Stated here at the specificity of the
   rule it has to override, and scoped to the same td > span:first-child shape so
   it cannot leak onto other icons. */
body.ts-thirdparty-dashboard table td > span.ts-total-icon:first-child,
body.ts-thirdparty-dashboard td > span.ts-total-icon:first-child,
body.ts-thirdparty-dashboard .ts-total-icon {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 36px;
	height: 36px;
	min-width: 36px;
	margin-right: 12px;
	border-radius: 9px;
	background: #e9e4ff;
	color: #7047eb;
	font-size: 18px !important;
	line-height: 1 !important;
	vertical-align: middle;
}
body.ts-thirdparty-dashboard .ts-statistics-total-row > td:first-child {
	white-space: nowrap;
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
	border: 1px solid var(--c-btn-action) !important;
	background: var(--c-btn-action) !important;
	color: var(--c-btn-action-text) !important;
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
	padding: 0 !important;
	border: 0 !important;
	border-radius: 0 !important;
	background: transparent !important;
	box-shadow: none !important;
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
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearefnobottom,
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearefnoborder,
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearef {
	display: flex;
	align-items: center;
	gap: 16px;
	min-height: 104px;
	padding: 20px 24px !important;
	border: 0 !important;
	background: #fff !important;
	box-sizing: border-box;
}
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearefnobottom > div:first-child,
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearefnoborder > div:first-child,
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearef > div:first-child {
	flex: 1 1 auto;
	min-width: 0;
}
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearef .divphotoref,
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearefnobottom .divphotoref,
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearefnoborder .divphotoref {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 56px;
	height: 56px;
	margin-right: 16px;
	border-radius: 10px;
	background: #f0edff;
	color: #5846e8;
}
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearef .refid,
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearefnobottom .refid,
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearefnoborder .refid {
	display: inline-grid;
	grid-template-columns: auto auto;
	align-items: center;
	column-gap: 10px;
	row-gap: 5px;
	min-width: 0;
	font-size: 24px;
	font-weight: 680;
}
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearef .refid > .refidno,
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearefnobottom .refid > .refidno,
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearefnoborder .refid > .refidno {
	grid-column: 1 / -1;
	font-size: 13px;
	font-weight: 400;
	line-height: 1.55;
}
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearef .refid > .statusref,
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearefnobottom .refid > .statusref,
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearefnoborder .refid > .statusref {
	grid-column: 2;
	grid-row: 1;
	margin: 0;
}
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearef .paginationref,
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearefnobottom .paginationref,
body.ts-command-record-page div.tabBar.ts-entity-card > div.arearefnoborder .paginationref {
	position: static !important;
	flex: 0 0 auto;
	float: none !important;
	margin-left: auto;
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
body.ts-command-record-page div.tabs[data-ts-placed="1"] {
	min-height: 52px;
	align-items: center;
	border-top: 1px solid #eef0f4 !important;
}
body.ts-command-record-page div.tabs[data-ts-placed="1"] .tabsElem,
body.ts-command-record-page div.tabs[data-ts-placed="1"] .tab { min-height: 50px; }
body.ts-command-record-page div.tabs[data-ts-placed="1"] a.tab {
	display: inline-flex !important;
	align-items: center;
	height: 50px;
	padding-inline: 14px !important;
}
body.ts-command-record-page div.fichehalfleft,
body.ts-command-record-page div.fichehalfright {
	padding: 14px 16px;
}
body.ts-command-record-page div.fichehalfleft table td,
body.ts-command-record-page div.fichehalfright table td {
	padding-top: 10px;
	padding-bottom: 10px;
	border-bottom-color: #f1f3f6 !important;
}
body.ts-command-record-secondary .ts-record-tab-native-surface {
	margin: 16px 0 0 !important;
	border: 1px solid #e7e9ee !important;
	border-radius: 12px !important;
	background: #fff !important;
	box-shadow: 0 4px 14px rgba(15, 23, 42, .04) !important;
	overflow: auto;
}
body.ts-command-record-secondary form.ts-record-tab-native-surface { padding: 16px; }
body.ts-command-record-secondary .ts-record-tab-native-surface table { margin: 0 !important; box-shadow: none !important; }
body.ts-command-record-secondary .ts-record-tab-native-surface table td,
body.ts-command-record-secondary .ts-record-tab-native-surface table th {
	padding: 10px 12px !important;
	border-color: #f0f2f5 !important;
}
body.ts-command-record-secondary .ts-record-tab-native-surface tr.liste_titre td,
body.ts-command-record-secondary .ts-record-tab-native-surface tr.liste_titre th {
	height: 44px;
	background: #f7f9fc !important;
	font-size: 12px;
	font-weight: 650;
}

/* Auxiliary Third Party tabs share one calm context summary instead of leaving
   their record fields as loose rows on the application canvas. */
body.ts-command-record-secondary .ts-record-context-summary {
	margin-top: 16px;
	padding: 8px 20px;
	border: 1px solid var(--c-border);
	border-radius: 12px;
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
}
body.ts-command-record-secondary .ts-record-context-summary table { margin: 0 !important; box-shadow: none !important; }
body.ts-command-record-secondary .ts-record-context-summary td {
	height: 38px;
	padding: 7px 10px !important;
	border-bottom: 0 !important;
}
body.ts-command-record-secondary .ts-record-context-summary td.titlefield { width: 230px; font-weight: 600; }

/* Warehouse activity tabs emit plain text metadata rather than a fiche table.
   Give that compact log the same independent summary-card language as other
   record tabs, while keeping the original user link in place. */
body.page-stock_info .ts-warehouse-log-summary {
	max-width: 920px;
	margin: 16px 0 0 !important;
}
body.page-stock_info .ts-warehouse-log-card {
	padding: 20px 22px;
	border: 1px solid var(--c-border);
	border-radius: var(--r-lg);
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
}
body.page-stock_info .ts-warehouse-log-title {
	display: flex;
	align-items: center;
	gap: 9px;
	margin: 0 0 16px;
	font-size: 1rem;
	font-weight: 650;
	color: var(--c-ink);
}
body.page-stock_info .ts-warehouse-log-title .fas { color: var(--c-accent); }
body.page-stock_info .ts-warehouse-log-grid {
	display: grid;
	grid-template-columns: repeat(3, minmax(0, 1fr));
	gap: 12px;
}
body.page-stock_info .ts-warehouse-log-item {
	display: grid;
	grid-template-columns: 36px minmax(0, 1fr);
	align-items: center;
	gap: 10px;
	min-height: 58px;
	padding: 10px 12px;
	border: 1px solid var(--c-hairline);
	border-radius: var(--r);
	background: var(--c-surface);
}
body.page-stock_info .ts-warehouse-log-icon {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 36px;
	height: 36px;
	border-radius: 9px;
	background: var(--c-accent-soft);
	color: var(--c-accent);
}
body.page-stock_info .ts-warehouse-log-label {
	display: block;
	margin-bottom: 3px;
	font-size: .75rem;
	font-weight: 600;
	color: var(--c-muted);
}
body.page-stock_info .ts-warehouse-log-value {
	min-width: 0;
	font-size: .8125rem;
	font-weight: 550;
	line-height: 1.35;
	color: var(--c-ink-2);
	word-break: break-word;
}
body.page-stock_info .ts-warehouse-log-value .userimg img {
	width: 26px !important;
	height: 26px !important;
	margin-right: 6px;
	border-radius: 50%;
	vertical-align: middle;
}

/* Product price tabs are detail summary + a native price list.  The native
   page uses bare sibling tables, so retain their forms/links and provide the
   shared record-tab surfaces at the container level. */
body.page-price_suppliers .ts-entity-card .divphotoref + .divphotoref:empty { display: none !important; }
body.page-price_suppliers .ts-product-price-summary {
	max-width: none;
	margin: 16px 0 20px !important;
}
body.page-price_suppliers .ts-product-price-details {
	width: 100%;
	margin: 0 !important;
	border: 1px solid var(--c-hairline) !important;
	border-radius: var(--r-lg);
	border-collapse: separate;
	border-spacing: 0;
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
	overflow: hidden;
}
body.page-price_suppliers .ts-product-price-details > tbody > tr > td {
	padding: 13px 18px !important;
	border: 0 !important;
	border-bottom: 1px solid var(--c-hairline) !important;
	background: transparent !important;
	font-size: .8125rem;
	vertical-align: middle;
}
body.page-price_suppliers .ts-product-price-details > tbody > tr:last-child > td { border-bottom: 0 !important; }
body.page-price_suppliers .ts-product-price-details > tbody > tr > td:first-child {
	width: 42%;
	font-weight: 600;
	color: var(--c-ink-2);
}
body.page-price_suppliers .ts-product-price-details table { background: transparent !important; }
body.page-price_suppliers .ts-product-price-details table td { padding: 0 !important; border: 0 !important; }
body.page-price_suppliers .ts-product-price-list-card {
	margin: 0;
	border: 1px solid var(--c-hairline);
	border-radius: var(--r-lg);
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
	overflow: hidden;
}
body.page-price_suppliers .ts-product-price-list-card > .ts-pagehead {
	min-height: 64px;
	margin: 0 !important;
	padding: 0 20px;
	border-bottom: 1px solid var(--c-hairline);
	background: transparent;
}
body.page-price_suppliers .ts-product-price-list-card > .ts-pagehead .titre {
	font-size: 1.125rem !important;
	font-weight: 650;
}
body.page-price_suppliers .ts-product-price-list-card > form { margin: 0 !important; }
body.page-price_suppliers .ts-product-price-list-card table.liste {
	margin: 0 !important;
	border: 0 !important;
	box-shadow: none !important;
}

/* Stock-card summary data lives inside the tab card, while the stock table
   heading follows it as a fiche sibling. Reserve the normal record-section gap
   so its title/action row never touches the summary or tab strip. */
body.page-stock_card .fiche > .ts-pagehead {
	margin-top: 20px !important;
	margin-bottom: 20px !important;
}
@media only screen and (max-width: 700px) {
	body.page-price_suppliers .ts-product-price-summary { margin: 12px 0 16px !important; }
	body.page-price_suppliers .ts-product-price-details > tbody > tr > td { padding: 11px 12px !important; }
	body.page-price_suppliers .ts-product-price-list-card > .ts-pagehead { padding: 0 14px; }
	body.page-stock_card .fiche > .ts-pagehead { margin-top: 16px !important; margin-bottom: 16px !important; }
}
@media only screen and (max-width: 900px) {
	body.page-stock_info .ts-warehouse-log-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media only screen and (max-width: 600px) {
	body.page-stock_info .ts-warehouse-log-summary { margin-top: 12px !important; }
	body.page-stock_info .ts-warehouse-log-card { padding: 16px; }
	body.page-stock_info .ts-warehouse-log-grid { grid-template-columns: 1fr; }
}
body.ts-thirdparty-notes-tab .ts-notes-card {
	display: grid;
	gap: 0;
	margin-top: 16px;
	padding: 4px 20px;
	border: 1px solid var(--c-border) !important;
	border-radius: 12px;
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
}
body.ts-thirdparty-notes-tab .ts-notes-card .tagtr {
	display: grid;
	grid-template-columns: minmax(180px, 260px) minmax(0, 1fr);
	min-height: 72px;
	align-items: start;
	padding: 16px 0;
	border-bottom: 1px solid var(--c-hairline);
}
body.ts-thirdparty-notes-tab .ts-notes-card .tagtr:last-child { border-bottom: 0; }
body.ts-thirdparty-notes-tab .ts-notes-card .tagtd { width: auto !important; padding: 0 12px !important; border: 0 !important; }
body.ts-thirdparty-notes-tab .ts-notes-card .editfielda {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 32px;
	height: 32px;
	border-radius: 8px;
}
body.ts-thirdparty-margins-tab .ts-margin-card { margin-top: 16px !important; }
body.ts-thirdparty-margins-tab .ts-margin-card .ts-pagehead { margin-bottom: 14px; }
body.ts-thirdparty-margins-tab .ts-margin-card .ts-pagehead .titre { font-size: 22px; }
body.ts-thirdparty-margins-tab .ts-margin-card .div-table-responsive {
	border: 1px solid var(--c-border);
	border-radius: 10px;
	overflow: auto;
}
body.ts-thirdparty-documents-tab .ts-documents-heading { margin-top: 16px !important; }
body.ts-thirdparty-documents-tab .ts-documents-card {
	margin-top: 12px !important;
	border: 1px solid var(--c-border) !important;
	border-radius: 12px !important;
	background: var(--c-surface) !important;
	box-shadow: var(--sh-sm) !important;
	overflow: hidden;
}
body.ts-thirdparty-documents-tab .ts-documents-card table { margin: 0 !important; box-shadow: none !important; }
body.ts-thirdparty-documents-tab .ts-documents-card td[colspan] { min-height: 120px; }
body.ts-thirdparty-documents-tab table.ts-documents-heading {
	padding: 0 12px;
	border: 1px solid var(--c-border) !important;
	border-radius: 12px;
	background: var(--c-surface);
}

body.ts-thirdparty-customer-tab .ts-customer-overview-grid {
	display: grid;
	grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
	/* Independent record columns must retain their intrinsic height.  The default
	   grid stretch makes a short activity stack look like an empty white slab
	   whenever the detail panel beside it happens to be taller. */
	align-items: start;
	gap: 16px;
	margin-top: 16px;
	padding: 0 !important;
	border: 0 !important;
	background: transparent !important;
	box-shadow: none !important;
}
body.ts-thirdparty-customer-tab .ts-customer-overview-card {
	align-self: start;
	width: auto !important;
	margin: 0 !important;
	padding: 20px !important;
	border: 1px solid var(--c-border);
	border-radius: 12px;
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
}
body.ts-thirdparty-customer-tab .ts-customer-overview-card table { margin: 0 !important; box-shadow: none !important; }
body.ts-thirdparty-customer-tab .ts-customer-overview-card table.tableforfield td {
	height: 42px;
	padding: 8px 10px !important;
	border-bottom: 1px solid var(--c-hairline) !important;
}
body.ts-thirdparty-customer-tab .ts-customer-overview-card table.tableforfield tr:last-child td { border-bottom: 0 !important; }
body.ts-thirdparty-customer-tab .ts-customer-overview-card table.tableforfield td:first-child { width: 46%; font-weight: 600; }
body.ts-thirdparty-customer-tab .ts-customer-overview-card table.tableforfield > tbody > tr > td:first-child > table.nobordernopadding {
	display: block !important;
	width: 100% !important;
}
body.ts-thirdparty-customer-tab .ts-customer-overview-card table.tableforfield > tbody > tr > td:first-child > table.nobordernopadding > tbody,
body.ts-thirdparty-customer-tab .ts-customer-overview-card table.tableforfield > tbody > tr > td:first-child > table.nobordernopadding > tbody > tr {
	display: contents !important;
}
body.ts-thirdparty-customer-tab .ts-customer-overview-card table.tableforfield > tbody > tr > td:first-child > table.nobordernopadding td {
	height: auto !important;
	min-height: 0 !important;
	padding: 0 !important;
	border: 0 !important;
}
body.ts-thirdparty-customer-tab .ts-customer-overview-card table.tableforfield > tbody > tr > td:first-child > table.nobordernopadding td:first-child {
	display: inline !important;
	width: auto !important;
}
body.ts-thirdparty-customer-tab .ts-customer-overview-card table.tableforfield > tbody > tr > td:first-child > table.nobordernopadding td.right {
	display: inline-flex !important;
	float: right;
	align-items: center;
	justify-content: flex-end;
	width: 30px !important;
}
body.ts-thirdparty-customer-tab .ts-customer-overview-card .fichecenter,
body.ts-thirdparty-customer-tab .ts-customer-overview-card .fichehalfleft,
body.ts-thirdparty-customer-tab .ts-customer-overview-card .fichehalfright { width: auto !important; margin: 0 !important; padding: 0 !important; }
body.ts-thirdparty-customer-tab .ts-customer-actions {
	display: flex;
	align-items: center;
	justify-content: flex-end;
	flex-wrap: wrap;
	gap: 8px;
	margin: 16px 0 0 !important;
	padding: 16px;
	border: 1px solid var(--c-border);
	border-radius: 12px;
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
}
body.ts-thirdparty-customer-tab .ts-customer-actions .butAction {
	min-height: 38px;
	margin: 0 !important;
	padding: 0 14px !important;
	border-radius: 8px !important;
	font-size: 12px;
}
@media (max-width: 900px) {
	body.ts-thirdparty-customer-tab .ts-customer-overview-grid { grid-template-columns: minmax(0, 1fr); }
}
@media (max-width: 640px) {
	body.ts-thirdparty-customer-tab .ts-customer-overview-card { padding: 12px !important; }
	body.ts-thirdparty-customer-tab .ts-customer-overview-card table.tableforfield tr {
		display: grid !important;
		grid-template-columns: minmax(130px, 42%) minmax(0, 1fr);
		align-items: center;
	}
	body.ts-thirdparty-customer-tab .ts-customer-overview-card table.tableforfield td,
	body.ts-thirdparty-customer-tab .ts-customer-overview-card table.tableforfield td:first-child {
		display: flex !important;
		align-items: center;
		width: auto !important;
		min-height: 42px !important;
		padding: 7px 8px !important;
	}
	body.ts-thirdparty-customer-tab .ts-customer-actions { justify-content: stretch; padding: 12px; }
	body.ts-thirdparty-customer-tab .ts-customer-actions .butAction { flex: 1 1 180px; text-align: center; }
}

/* Title glyphs occupy a deliberate tile rather than colliding with text. */
.fiche > table.table-fiche-title.ts-command-title-with-icon { margin-bottom: 16px !important; }
.fiche > table.table-fiche-title.ts-command-title-with-icon td.col-picto {
	width: 44px !important;
	min-width: 44px !important;
	padding: 0 !important;
	vertical-align: middle;
}
.fiche > table.table-fiche-title.ts-command-title-with-icon td.col-picto .pictotitle {
	display: inline-flex !important;
	align-items: center;
	justify-content: center;
	width: 36px !important;
	height: 36px;
	margin: 0 8px 0 0 !important;
	border-radius: 9px;
	background: var(--c-accent-soft);
	color: var(--c-accent) !important;
	font-size: 16px !important;
}
.fiche > table.table-fiche-title.ts-command-title-with-icon td.col-title { padding-left: 8px !important; }
.fiche > table.table-fiche-title.ts-command-title-with-icon .titre { line-height: 1.2; }
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

/* Shared module landing dashboards -------------------------------------- */
body.ts-command-module-index .fiche { padding: 28px 32px 48px !important; }
body.ts-command-module-index .fiche > table.table-fiche-title {
	margin-bottom: 8px !important;
	border: 0 !important;
	background: transparent !important;
	box-shadow: none !important;
}
body.ts-command-module-index .fiche > table.table-fiche-title .titre,
body.ts-command-module-index .ts-pagehead .titre { font-size: 26px; font-weight: 680; }
body.ts-command-module-index .ts-pagehead {
	display: flex;
	flex-direction: column;
	align-items: flex-start;
	gap: 5px;
	margin-bottom: 22px;
}
body.ts-command-module-index .ts-module-index-subtitle {
	color: var(--c-muted);
	font-size: 14px;
	line-height: 1.45;
}
body.ts-command-module-index .fiche > .opacitymedium { display: block; margin-bottom: 20px; font-size: 14px; }
body.ts-command-module-index .ts-module-index-grid {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	gap: 16px;
	align-items: start;
}
body.ts-command-module-index .ts-module-index-column > .div-table-responsive,
body.ts-command-module-index .ts-module-index-column > .div-table-responsive-no-min { min-width: 0; overflow: visible; }
body.ts-products-module-index .ts-module-index-grid { grid-template-columns: minmax(0, .95fr) minmax(0, 1.05fr); }
body.ts-products-module-index .ts-module-index-card td.center:has(.dolgraph) { padding: 16px !important; }
body.ts-products-module-index .ts-module-index-card .dolgraph { margin-inline: auto; }
body.ts-products-module-index .ts-module-index-column:first-child { align-content: start; }
body.ts-products-module-index .ts-module-index-column:first-child .ts-module-index-card { min-height: 360px; }
body.ts-shipments-module-index .ts-module-index-grid { grid-template-columns: minmax(280px, .72fr) minmax(0, 1.28fr); }
body.ts-shipments-module-index .ts-module-index-column > br { display: none; }
body.ts-shipments-module-index .ts-module-index-column .div-table-responsive-no-min,
body.ts-shipments-module-index .ts-module-index-column .div-table-responsive { margin: 0; }

/* Shipment statistics: keep the real form/chart, but present them as two aligned
   dashboard panels instead of one oversized legacy tab sheet. */
body.ts-shipment-statistics .fiche > .tabs { margin-top: 12px; }
body.ts-shipment-statistics .fiche > .tabBar {
	margin-top: 12px;
	padding: 20px;
	border: 1px solid var(--c-border);
	border-radius: 12px;
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
}
body.ts-shipment-statistics .fiche > .tabBar > .fichecenter {
	display: grid;
	grid-template-columns: minmax(300px, .8fr) minmax(0, 1.2fr);
	gap: 24px;
	align-items: start;
}
body.ts-shipment-statistics .ts-statistics-column { width: auto !important; margin: 0 !important; padding: 0 !important; }
body.ts-shipment-statistics .ts-statistics-column table {
	margin: 0 !important;
	border: 1px solid var(--c-border);
	border-radius: 10px;
	background: var(--c-surface);
	box-shadow: none !important;
	overflow: hidden;
}
body.ts-shipment-statistics .ts-statistics-column td { padding: 10px 14px !important; }
body.ts-shipment-statistics .ts-statistics-column tr.liste_titre td { height: 44px; background: var(--c-sunken) !important; }
body.ts-shipment-statistics .ts-statistics-column select,
body.ts-shipment-statistics .ts-statistics-column .select2-container { max-width: 100% !important; }
body.ts-shipment-statistics .ts-statistics-column .dolgraph { margin-inline: auto; }
@media (max-width: 900px) {
	body.ts-shipment-statistics .fiche > .tabBar > .fichecenter { grid-template-columns: minmax(0, 1fr); }
	body.ts-shipments-module-index .ts-module-index-grid,
	body.ts-products-module-index .ts-module-index-grid { grid-template-columns: minmax(0, 1fr); }
}
@media (max-width: 640px) {
	body.ts-thirdparty-notes-tab .ts-notes-card .tagtr { grid-template-columns: minmax(0, 1fr); gap: 10px; }
	body.ts-command-record-secondary .ts-record-context-summary td.titlefield { width: 46%; }
	.fiche > table.table-fiche-title.ts-command-title-with-icon td.col-picto { width: 40px !important; min-width: 40px !important; }
}
body.ts-command-module-index .ts-module-index-column {
	display: grid;
	gap: 16px;
	width: auto !important;
	margin: 0 !important;
	padding: 0 !important;
	border: 0;
	background: transparent;
	box-shadow: none;
}
body.ts-command-module-index .ts-module-index-empty { display: none; }
body.ts-command-module-index .ts-module-index-card {
	width: 100%;
	margin: 0 !important;
	border: 1px solid #e7e9ee !important;
	border-radius: 12px !important;
	background: #fff !important;
	box-shadow: 0 4px 14px rgba(15, 23, 42, .04) !important;
	overflow: hidden;
	table-layout: auto !important;
}
body.ts-command-module-index .ts-module-index-data-card { table-layout: fixed !important; }
/* Compact three-column transaction widgets (proposals/orders) size more
   reliably from their reference, customer and amount content. A fixed table
   lets the colspan heading influence the column algorithm and collapses each
   cell to a few pixels. */
body.ts-command-module-index .ts-module-index-data-card.ts-module-index-cols-3 { table-layout: auto !important; }
/* Scoped to the card's own rows. A reference cell holds a nested table of its
   own -- Dolibarr's picto-and-ref layout -- whose row is not a heading row, so an
   unscoped rule reached into it and applied the zero max-width meant for the
   card's cells. The inner cells collapsed and the reference showed as an
   ellipsis, while the cell around it was the full width all along. */
body.ts-command-module-index .ts-module-index-data-card > tbody > tr:not(.liste_titre) > td {
	max-width: none !important;
	min-width: 0 !important;
	box-sizing: border-box;
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
}
body.ts-command-module-index .ts-module-index-card tr.liste_titre > th,
body.ts-command-module-index .ts-module-index-card tr.liste_titre > td {
	display: table-cell !important;
	width: auto !important;
	min-width: 0 !important;
	height: auto;
	min-height: 48px;
	padding: 12px 16px !important;
	border: 0 !important;
	border-bottom: 1px solid #e7e9ee !important;
	background: #fff !important;
	font-size: 13px;
	font-weight: 650;
	white-space: normal !important;
	line-height: 1.35;
	vertical-align: middle;
}
/* The widget title shares this row with the column labels. Under auto layout
   the labels claimed width by content ratio and left the title 137px, so
   "Latest 3 modified Vendor invoices" wrapped to four lines and longer titles
   truncated. Let each label take only its own width and give the rest to the
   title. */
/* The card carries a min-height so the columns line up, and a table row will
   happily absorb that slack -- the Statistics header stretched to 126px against
   47px on every other card. Pin the header to its content height so the body
   row takes the extra instead. */
body.ts-command-module-index .ts-module-index-card tr.liste_titre {
	height: 1px;
}
/* Keep the labels and the title on one line each and let auto layout size the
   columns from that. Forcing widths here instead starved the data columns: the
   title cell spans two of them, so a percentage on it truncated the reference
   and customer beneath. */
body.ts-command-module-index .ts-module-index-card tr.liste_titre > th,
body.ts-command-module-index .ts-module-index-card tr.liste_titre > td {
	white-space: nowrap !important;
}
body.ts-command-module-index .ts-module-index-card tr.liste_titre > th:first-child,
body.ts-command-module-index .ts-module-index-card tr.liste_titre > td:first-child {
	overflow: hidden;
	text-overflow: ellipsis;
}
body.ts-command-module-index .ts-module-index-card tr.liste_titre > :first-child {
	position: relative;
	padding-left: 62px !important;
}
body.ts-command-module-index .ts-module-index-heading-icon {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 36px;
	height: 36px;
	border-radius: 9px;
	background: #f0edff;
	color: var(--c-primary);
	font-size: 16px;
}
body.ts-command-module-index .ts-module-index-card tr.liste_titre > :first-child > .ts-module-index-heading-icon {
	position: absolute;
	top: 50%;
	left: 16px;
	transform: translateY(-50%);
}
body.ts-command-module-index .ts-module-index-heading-icon [class*="fa-"] {
	margin: 0 !important;
	padding: 0 !important;
	color: inherit !important;
	font-size: inherit !important;
}
body.ts-command-module-index .ts-module-index-card tr.liste_titre > th > a:has(.badge),
body.ts-command-module-index .ts-module-index-card tr.liste_titre > td > a:has(.badge) { float: right; margin-left: 10px; }
body.ts-command-module-index .ts-module-index-card tr:not(.liste_titre) > td {
	height: 48px;
	padding: 10px 16px !important;
	border-bottom-color: #f1f3f6 !important;
	font-size: 13px;
}
body.ts-command-module-index .ts-module-index-empty-row td {
	/* display:flex on a td drops its table-cell behaviour, so the colspan is
	   ignored and the cell shrinks to its own content -- "None" ended up 32px
	   wide against the left edge while sibling widgets centred theirs across the
	   card. Keep the cell a table cell and centre its contents instead. */
	display: table-cell !important;
	height: 112px;
	color: var(--c-muted);
	text-align: center;
	vertical-align: middle;
}
body.ts-command-module-index .ts-module-index-empty-inner {
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	gap: 8px;
}
body.ts-command-module-index .ts-module-index-empty-row .ts-module-index-empty-icon {
	flex: none;
}

/* These links ship as inline-flex, and a flex container cannot render an
   ellipsis: the name overflowed the anchor and was sheared off by the cell
   instead. Constraining the flex items only made it worse -- the text collapsed
   to zero and the anchor shrank to its icon. Lay the anchor out as inline-block
   so text-overflow applies to it directly, keeping the icon inline. */
body.ts-command-module-index .ts-module-index-data-card > tbody > tr:not(.liste_titre) > td > a,
body.ts-command-module-index .ts-module-index-data-card > tbody > tr:not(.liste_titre) > td > span {
	display: inline-block !important;
	max-width: 100%;
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis;
	vertical-align: middle;
}
body.ts-command-module-index .ts-module-index-data-card > tbody > tr:not(.liste_titre) > td > a > img,
body.ts-command-module-index .ts-module-index-data-card > tbody > tr:not(.liste_titre) > td > a > .fas,
body.ts-command-module-index .ts-module-index-data-card > tbody > tr:not(.liste_titre) > td > a > .far,
body.ts-command-module-index .ts-module-index-data-card > tbody > tr:not(.liste_titre) > td > a > .fa {
	vertical-align: middle;
	margin-right: 4px;
}
/* Recent proposal/order widgets wrap each reference in a small nested table.
   The card's ellipsis rule must not collapse that inner reference cell to the
   icon alone; keep the nested table fluid and let its reference remain readable. */
body.ts-command-module-index .ts-module-index-data-card > tbody > tr:not(.liste_titre) > td > table {
	width: 100% !important;
	table-layout: auto !important;
}
body.ts-command-module-index .ts-module-index-data-card > tbody > tr:not(.liste_titre) > td > table td {
	max-width: none !important;
	min-width: 0 !important;
	overflow: visible !important;
	text-overflow: clip !important;
}
body.ts-command-module-index .ts-module-index-data-card > tbody > tr:not(.liste_titre) > td > table td a {
	display: inline-flex !important;
	max-width: 100% !important;
	overflow: visible !important;
	text-overflow: clip !important;
}
body.ts-command-module-index .ts-module-index-data-card > tbody > tr:not(.liste_titre) > td > table > tbody > tr > td:first-child {
	width: 96px !important;
	min-width: 96px !important;
}
body.ts-command-module-index .ts-module-index-data-card > tbody > tr:not(.liste_titre) > td > table > tbody > tr > td:nth-child(2),
body.ts-command-module-index .ts-module-index-data-card > tbody > tr:not(.liste_titre) > td > table > tbody > tr > td:nth-child(3) {
	width: 16px !important;
	min-width: 16px !important;
}

body.ts-command-module-index .ts-module-stat-summary {
	display: grid;
	gap: 14px;
	padding: 16px;
	border: 1px solid var(--c-border);
	border-radius: 12px;
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
}
body.ts-command-module-index .ts-module-stat-total {
	display: grid;
	grid-template-columns: 36px minmax(0, 1fr) auto;
	align-items: center;
	gap: 12px;
	min-height: 58px;
	padding: 10px 14px;
	border-radius: 10px;
	background: linear-gradient(90deg, #f4f1ff, #faf9ff);
}
body.ts-command-module-index .ts-module-stat-total strong { font-size: 14px; font-weight: 650; }
body.ts-command-module-index .ts-module-stat-total b { color: var(--c-primary); font-size: 24px; }
body.ts-command-module-index .ts-module-stat-tiles {
	display: grid;
	grid-template-columns: repeat(3, minmax(0, 1fr));
	gap: 10px;
}
body.ts-command-module-index .ts-module-stat-tile {
	display: grid;
	grid-template-columns: 28px minmax(0, 1fr);
	align-items: center;
	gap: 3px 9px;
	min-height: 74px;
	padding: 11px;
	border: 1px solid var(--c-border);
	border-radius: 10px;
	background: #fff;
}
body.ts-command-module-index .ts-module-stat-tile > span:first-child { grid-row: 1 / 3; color: #7654d8; font-size: 17px; text-align: center; }
body.ts-command-module-index .ts-module-stat-tile > span:nth-child(2) { overflow: hidden; color: var(--c-muted); font-size: 11px; line-height: 1.25; }
body.ts-command-module-index .ts-module-stat-tile > b { font-size: 16px; }
body.ts-command-module-index .ts-module-stat-tile-2 > span:first-child { color: #2698bd; }
body.ts-command-module-index .ts-module-stat-tile-3 > span:first-child { color: #ee9b22; }
body.ts-command-module-index .ts-module-stat-tile-4 > span:first-child { color: #48a55f; }
@media (max-width: 900px) {
	body.ts-command-module-index .ts-module-index-grid { grid-template-columns: minmax(0, 1fr); }
}
@media (max-width: 640px) {
	body.ts-command-module-index .fiche { padding: 18px 12px 36px !important; }
	body.ts-command-module-index .ts-pagehead { margin-bottom: 16px; }
	body.ts-command-module-index .ts-pagehead .titre { font-size: 23px; }
	body.ts-command-module-index .ts-module-stat-tiles { grid-template-columns: repeat(2, minmax(0, 1fr)); }
	body.ts-command-module-index .ts-module-index-column > .div-table-responsive:has(.ts-module-index-data-card),
	body.ts-command-module-index .ts-module-index-column > .div-table-responsive-no-min:has(.ts-module-index-data-card) {
		max-width: 100%;
		overflow-x: auto;
		border-radius: 12px;
		scrollbar-width: thin;
	}
	body.ts-command-module-index .ts-module-index-data-card { min-width: 600px; }
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
	border-bottom: 0 !important;
	background: transparent !important;
	font-size: 13px;
	vertical-align: middle;
}
body.ts-command-form-page .ts-command-form-fields > tbody > tr:last-child > td { border-bottom: 0 !important; }
/* Keep Dolibarr's adjacent "new" action beside relational selects.  The
   legacy markup emits the plus-circle link as a sibling of Select2; without
   an inline value-group it wraps onto its own line. */
body.ts-command-form-page .ts-command-form-fields td:has(> a .fa-plus-circle, > a .fa-plus) {
	display: flex;
	align-items: center;
	gap: 8px;
}
body.ts-command-form-page .ts-command-form-fields td:has(> a .fa-plus-circle, > a .fa-plus) > .select2-container,
body.ts-command-form-page .ts-command-form-fields td:has(> a .fa-plus-circle, > a .fa-plus) > span:has(> .select2-container),
body.ts-command-form-page .ts-command-form-fields td:has(> a .fa-plus-circle, > a .fa-plus) > div:has(> .select2-container) {
	flex: 1 1 auto;
	width: auto !important;
	min-width: 0 !important;
}
body.ts-command-form-page .ts-command-form-fields td:has(> a .fa-plus-circle, > a .fa-plus) > a:has(.fa-plus-circle),
body.ts-command-form-page .ts-command-form-fields td:has(> a .fa-plus-circle, > a .fa-plus) > a:has(.fa-plus) {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	flex: 0 0 32px;
	width: 32px;
	height: 32px;
	margin: 0;
	border-radius: 7px;
	color: var(--c-accent) !important;
}
body.ts-command-form-page .ts-command-form-fields td:has(> a .fa-plus-circle, > a .fa-plus) > a:has(.fa-plus-circle):hover,
body.ts-command-form-page .ts-command-form-fields td:has(> a .fa-plus-circle, > a .fa-plus) > a:has(.fa-plus):hover {
	background: var(--c-accent-soft);
}
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
body.ts-command-form-page .ts-command-form .ts-command-select-compact {
	width: min(100%, 340px) !important;
	max-width: 340px !important;
}
body.ts-command-form-page .ts-command-form .select2-selection--multiple {
	min-height: 40px !important;
	padding: 3px 38px 3px 8px !important;
	border: 1px solid #dfe4ec !important;
	border-radius: 8px !important;
	background: #fff !important;
	box-shadow: none !important;
}
body.ts-command-form-page .ts-command-form .select2-selection--multiple .select2-search--inline,
body.ts-command-form-page .ts-command-form .select2-selection--multiple .select2-search__field {
	border: 0 !important;
	background: transparent !important;
	box-shadow: none !important;
	outline: 0 !important;
}
body .select2-dropdown.ts-command-form-dropdown {
	border: 1px solid #e0e6ef !important;
	border-radius: 9px !important;
	background: #fff !important;
	box-shadow: 0 14px 34px rgba(15, 23, 42, .14) !important;
	overflow: hidden;
}
body .ts-command-form-dropdown .select2-search--dropdown { padding: 9px; }
body .ts-command-form-dropdown .select2-search__field {
	height: 38px;
	padding: 0 12px;
	border: 1px solid #dfe4ec;
	border-radius: 8px;
	outline: 0;
}
body .ts-command-form-dropdown .select2-results__options { max-height: 300px; padding: 4px; scrollbar-width: thin; }
body .ts-command-form-dropdown .select2-results__option { min-height: 36px; padding: 8px 10px; border-radius: 6px; font-size: 13px; }
body .ts-command-form-dropdown .select2-results__option--highlighted { background: var(--c-accent-soft) !important; color: var(--c-accent-ink) !important; }
body .ts-command-form-dropdown-compact .select2-search--dropdown,
body .ts-command-form-dropdown .ts-command-empty-option { display: none !important; }

/* Shared legacy list fallback: retain Dolibarr's real filter inputs and empty
   result semantics while giving pages without a richer adapter COMMAND rhythm. */
body:not(.ts-thirdparty-list) table.liste tr.liste_titre_filter > td,
body:not(.ts-thirdparty-list) table.liste tr.liste_titre_filter > th {
	padding: 9px 7px !important;
	border-bottom: 1px solid #e7e9ee !important;
	background: #fbfcfe !important;
}
body:not(.ts-thirdparty-list) table.liste tr.liste_titre_filter input[type="text"],
body:not(.ts-thirdparty-list) table.liste tr.liste_titre_filter input[type="number"],
body:not(.ts-thirdparty-list) table.liste tr.liste_titre_filter select,
body:not(.ts-thirdparty-list) table.liste tr.liste_titre_filter .select2-selection {
	min-height: 38px !important;
	border: 1px solid #dfe4ec !important;
	border-radius: 8px !important;
	background: #fff !important;
	box-shadow: none !important;
}
td.ts-command-empty-state {
	height: 124px !important;
	padding: 72px 20px 22px !important;
	position: relative;
	text-align: center !important;
	vertical-align: middle !important;
	color: #7a8497 !important;
	font-size: 13px !important;
	font-weight: 500 !important;
}
td.ts-command-empty-state::before {
	content: "\f15c";
	position: absolute;
	/* Lift the tile slightly so the message has a deliberate 6–8px breathing
	   gap without changing the icon, text, or empty-state card dimensions. */
	top: 22px;
	left: 50%;
	width: 36px;
	height: 36px;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	transform: translateX(-50%);
	border-radius: 10px;
	background: var(--c-accent-soft);
	color: var(--c-accent);
	font-family: "Font Awesome 5 Free", "Font Awesome 6 Free", FontAwesome;
	font-size: 16px;
	font-weight: 900;
	line-height: 1;
	box-sizing: border-box;
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
	border-color: var(--c-btn-action) !important;
	background: var(--c-btn-action) !important;
	color: var(--c-btn-action-text) !important;
}
body.ts-command-form-page .ts-command-submit-secondary {
	border-color: #dfe4ec !important;
	background: #fff !important;
	color: #334155 !important;
}
/* Native Dolibarr card forms (including Third Party create/edit) may not carry
   the composed form-page marker, but their real submit still uses
   .button-save. Keep that action in the shared COMMAND indigo hierarchy. */
body.page-card input.button-save,
body.page-card button.button-save {
	border-color: var(--c-btn-action) !important;
	background: var(--c-btn-action) !important;
	color: var(--c-btn-action-text) !important;
}
/* Some native card forms expose only the shared action-row marker. */
.ts-modern-form-actions input.button-save,
.ts-modern-form-actions button.button-save {
	border-color: var(--c-btn-action) !important;
	background: var(--c-btn-action) !important;
	color: var(--c-btn-action-text) !important;
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
/* Some records emit an unclassified legacy secondary line alongside the
   normalized location/email rows. It can overlap the email at narrow identity
   widths; the concise overview rows are the only metadata shown in this shell. */
body.ts-thirdparty-overview .ts-entity-identity > .refid > .refidno {
	display: none !important;
}
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
body.ts-thirdparty-overview .ts-entity-identity > .refid > .ts-overview-location {
	display: inline-flex !important;
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
/* The entity icon already identifies the record in the header. Repeating it in
   the first Overview tab consumes scarce horizontal space and is what causes
   lower-priority tabs to clip at the right edge. Keep the existing link and
   active state, but use the same text-only tab treatment as the other tabs. */
body.ts-thirdparty-record-context .tabs a[href*="/societe/card.php"] > [class*="fa-"],
body.ts-thirdparty-record-context .tabs a[href*="/societe/card.php"] > svg {
	display: none !important;
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
	/* Send email is the visible primary action on this overview. It must honour
	   Display > Skin and colors just like every other butAction. */
	border: 1px solid var(--c-btn-action, var(--c-accent)) !important;
	background: var(--c-btn-action, var(--c-accent)) !important;
	color: var(--c-btn-action-text, #fff) !important;
	box-shadow: 0 2px 5px color-mix(in srgb, var(--c-btn-action, var(--c-accent)) 24%, transparent) !important;
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
body.ts-thirdparty-overview .ts-field-group > table.tableforfield {
	flex: 0 0 auto;
	width: calc(100% - 40px) !important;
	margin: 10px 20px 16px !important;
	padding: 0;
	table-layout: fixed;
}
body.ts-thirdparty-overview .ts-field-group > table.tableforfield > tbody > tr > td {
	height: 31px;
	padding: 6px 0;
	border: 0 !important;
	font-size: 13px;
	line-height: 1.4;
	vertical-align: top;
}
body.ts-thirdparty-overview .ts-field-group > table.tableforfield > tbody > tr,
body.ts-thirdparty-overview .ts-field-group > table.tableforfield > tbody > tr + tr { border: 0 !important; }
body.ts-thirdparty-overview .ts-field-group > table.tableforfield > tbody > tr > td:first-child {
	width: 56%;
	padding-right: 18px;
	color: #273449;
	font-weight: 550;
}
body.ts-thirdparty-overview .ts-field-group > table.tableforfield > tbody > tr > td:last-child {
	width: 44%;
	color: #526176;
	font-size: 13.5px;
	overflow-wrap: anywhere;
}
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
body.ts-thirdparty-overview .ts-field-group[data-group="relationships"] > table { flex: 0 0 auto !important; }
body.ts-thirdparty-overview .ts-field-group[data-group="relationships"] > table > tbody > tr > td {
	height: auto;
	padding-top: 14px;
	padding-bottom: 14px;
	vertical-align: top;
}
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
	body.ts-thirdparty-overview .ts-field-group > table.tableforfield {
		width: calc(100% - 32px) !important;
		margin-inline: 16px !important;
	}
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
	background: var(--c-btn-action) !important;
	color: var(--c-btn-action-text) !important;
}
body.ts-thirdparty-events .ts-events-native-title-source { display: none !important; }
body.ts-thirdparty-events .ts-events-toolbar {
	display: grid;
	grid-template-columns: auto minmax(0, 1fr);
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
body.ts-thirdparty-events .ts-events-view-switch + .vbar { display: none !important; }
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
	grid-template-columns: minmax(240px, 1fr) 210px 240px minmax(196px, max-content);
	align-items: center;
	gap: 10px;
	width: 100%;
	min-width: 0;
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
	border-radius: 8px !important;
	background: #fff;
	color: #475569;
	font-size: 13px;
	font-weight: 500;
	text-decoration: none;
	justify-content: flex-start !important;
}
body.ts-thirdparty-events .ts-events-filter-form th:nth-child(2) > .nowrap,
body.ts-thirdparty-events .ts-events-filter-form th:nth-child(4) > [class*="fa-square"] { display: none !important; }
body.ts-thirdparty-events .ts-events-filter-form .select2-container {
	/* Select2's container is inline and unsized, so the 40px selection inside it
	   sat below the row's shared baseline. Give the container the control height
	   it is standing in for. */
	width: 100% !important;
	display: block;
	height: 40px;
	align-self: center;
}
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
body.ts-thirdparty-events .ts-events-filter-form th:nth-child(1) { gap: 8px; justify-content: flex-end; }
body.ts-thirdparty-events .ts-events-filter-form th:nth-child(1) button.button_search { flex: 0 0 auto; min-width: 92px; }
body.ts-thirdparty-events .ts-events-filter-form button.button_removefilter { flex: 0 0 auto; width: 40px; padding: 0 !important; }
/* Dolibarr wraps only part of this column's buttons, so the wrapper box laid
   the group out from its own origin and ran the reset control past the card
   edge. Take the wrapper out of layout entirely and let the column align both
   buttons as one row. */
body.ts-thirdparty-events .ts-events-filter-form th:nth-child(1) > .nowraponall { display: contents; }
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
	body.ts-thirdparty-events .ts-events-toolbar { grid-template-columns: minmax(0, 1fr); }
	body.ts-thirdparty-events .ts-events-view-switch { justify-self: start; }
	body.ts-thirdparty-events .ts-events-filter-form { width: 100%; }
	body.ts-thirdparty-events .ts-events-filter-form .liste_titre { grid-template-columns: minmax(220px, 1fr) minmax(180px, .8fr) minmax(220px, .9fr) 132px; }
	body.ts-thirdparty-events .ts-events-filter-form th:nth-child(1) { grid-column: 4; grid-row: 1; }
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
	body.ts-thirdparty-events .ts-events-filter-form th:nth-child(1) {
		display: grid !important;
		grid-template-columns: minmax(0, 1fr) 40px;
		gap: 8px;
		width: 100%;
	}
	body.ts-thirdparty-events .ts-events-filter-form th:nth-child(1) button.button_search,
	body.ts-thirdparty-events .ts-events-filter-form th:nth-child(1) button.button_removefilter {
		width: 100% !important;
		min-width: 0;
		margin: 0 !important;
	}
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

/* Filters promoted into the record-tab toolbar (Events/Agenda, Contacts).
   These reuse .ts-column-filter-control, which is a stacked grid built for the
   Filters disclosure -- inside the toolbar that stacked the label over the
   control and broke a date range onto two lines, pushing its picker buttons
   over the neighbouring field. Lay them out as one row at the shared 40px
   control height instead. Scoped to controls carrying both classes, so the
   disclosure panel and the main list pages keep their own layout. */
.ts-filter-surface .ts-column-filter-control.ts-toolbar-filter {
	display: flex;
	flex-direction: row;
	align-items: center;
	gap: 8px;
	height: 40px;
	min-height: 40px;
	padding: 0 10px;
	border: 1px solid var(--c-line);
	border-radius: var(--r);
	background: var(--c-surface);
	overflow: hidden;
}
.ts-filter-surface .ts-column-filter-control.ts-toolbar-filter:focus-within {
	border-color: var(--c-accent);
}
.ts-filter-surface .ts-column-filter-control.ts-toolbar-filter > .ts-column-filter-label {
	/* The disclosure-panel rule lays this label out full width above its control.
	   In a row it must size to its text, or it consumes the slot and collapses
	   the field it names to zero. */
	flex: none;
	display: inline-block;
	width: auto;
	min-width: 0;
	max-width: none;
	font-size: 12px;
	font-weight: 600;
	text-transform: none;
	color: var(--c-ink-subtle);
	white-space: nowrap;
}
.ts-filter-surface .ts-column-filter-control.ts-toolbar-filter .divfordateinput {
	display: flex;
	align-items: center;
	gap: 4px;
	min-width: 0;
}
.ts-filter-surface .ts-column-filter-control.ts-toolbar-filter input[type="text"] {
	width: 74px;
	min-width: 0;
	height: 28px;
	padding: 0 6px;
	border: 1px solid var(--c-line);
	border-radius: 6px;
	background: var(--c-surface);
	font-size: 13px;
	text-align: center;
}
.ts-filter-surface .ts-column-filter-control.ts-toolbar-filter img.ui-datepicker-trigger {
	width: 15px;
	height: 15px;
	flex: none;
	cursor: pointer;
	opacity: .6;
}
.ts-filter-surface .ts-column-filter-control.ts-toolbar-filter img.ui-datepicker-trigger:hover { opacity: 1; }
/* The select carries Dolibarr's own max-width utilities; the toolbar slot is
   the authority on width here. */
.ts-filter-surface .ts-column-filter-control.ts-toolbar-filter .select2-container {
	flex: 1 1 auto;
	width: auto !important;
	min-width: 0 !important;
	max-width: none !important;
}
.ts-filter-surface .ts-column-filter-control.ts-toolbar-filter .select2-selection {
	min-height: 28px;
	height: 28px;
	border: 1px solid var(--c-line);
	border-radius: 6px;
	background: var(--c-surface);
}
.ts-filter-surface .ts-column-filter-control.ts-toolbar-filter .select2-selection__rendered {
	line-height: 26px;
	font-size: 13px;
	padding-left: 6px;
}
.ts-filter-surface .ts-column-filter-control.ts-toolbar-filter .select2-selection__arrow { height: 26px; }
/* A date range needs both inputs plus their pickers; the enum next to it does not. */
.ts-filter-surface .ts-column-filter-control.ts-toolbar-filter-1 {
	flex-basis: 300px;
	width: 300px;
	min-width: 300px;
}
.ts-filter-surface .ts-column-filter-control.ts-toolbar-filter-2 {
	flex-basis: 230px;
	width: 230px;
	min-width: 230px;
}
/* Dolibarr's view switch lived in a title table that is now empty. */
table.table-fiche-title.ts-events-native-title-source { display: none !important; }

/* ==========================================================================
   Display > Skin and colors (admin/ihm.php?mode=template)

   Route-gated by composeDisplaySettings() in modern.js. Every selector below
   is scoped to body.ts-display-settings, so no other admin screen is touched.
   ========================================================================== */
body.ts-display-settings .ts-settings-shell {
	display: grid;
	gap: 20px;
	min-width: 0;
}
body.ts-display-settings .ts-settings-card {
	min-width: 0;
	border: 1px solid var(--c-hairline);
	border-radius: var(--r-lg);
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
	overflow: hidden;
}
body.ts-display-settings .ts-settings-card-head {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 16px;
	padding: 16px 20px;
	border-bottom: 1px solid var(--c-hairline);
}
body.ts-display-settings .ts-settings-card-title {
	margin: 0;
	font-size: 0.9375rem;
	font-weight: 650;
	color: var(--c-ink);
}
body.ts-display-settings .ts-settings-card-aside {
	flex: 0 0 auto;
	font-size: 0.8125rem;
	white-space: nowrap;
}
body.ts-display-settings .ts-settings-card-aside a { color: var(--c-accent); text-decoration: none; }
body.ts-display-settings .ts-settings-card-aside a:hover { text-decoration: underline; }

/* ---- skin picker ---- */
body.ts-display-settings .ts-theme-grid {
	/* Flex rather than grid: the skin count is whatever is installed, and a
	   trailing partial row should centre rather than strand itself at column 1. */
	display: flex;
	flex-wrap: wrap;
	justify-content: center;
	gap: 16px;
	padding: 20px;
}
body.ts-display-settings .ts-theme-card { flex: 0 1 158px; }
body.ts-display-settings .ts-theme-card {
	display: flex !important;
	flex-direction: column;
	align-items: center;
	gap: 8px;
	min-width: 0;
	padding: 10px;
	border: 1px solid var(--c-hairline);
	border-radius: var(--r);
	background: var(--c-surface);
	text-align: center;
}
body.ts-display-settings .ts-theme-card:hover { border-color: var(--c-border); }
body.ts-display-settings .ts-theme-card-selected {
	border-color: var(--c-accent);
	box-shadow: 0 0 0 2px var(--c-accent-soft);
}
/* One thumbnail ratio for every skin, whatever each theme ships. */
body.ts-display-settings .ts-theme-card img.img-skinthumb {
	display: block;
	width: 100%;
	height: 92px;
	margin: 0 !important;
	border: 1px solid var(--c-hairline) !important;
	border-radius: 6px;
	object-fit: cover;
	object-position: top center;
	box-shadow: none !important;
}
body.ts-display-settings .ts-theme-card br { display: none; }
body.ts-display-settings .ts-theme-card input[type="radio"] { margin: 0; }
body.ts-display-settings .ts-theme-card label {
	font-size: 0.8125rem;
	font-weight: 550;
	color: var(--c-ink-2);
	cursor: pointer;
}
body.ts-display-settings .ts-theme-card-selected label { color: var(--c-accent-ink); }

/* ---- general preferences ---- */
body.ts-display-settings .ts-settings-grid {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	gap: 0 32px;
	padding: 4px 20px 20px;
	border-top: 1px solid var(--c-hairline);
}
body.ts-display-settings .ts-setting {
	display: grid;
	grid-template-columns: minmax(0, 150px) minmax(0, 1fr);
	align-items: center;
	gap: 16px;
	min-height: 64px;
	padding: 8px 0;
	border-bottom: 1px solid var(--c-hairline);
}
body.ts-display-settings .ts-settings-grid .ts-setting:nth-last-child(-n + 2) { border-bottom: 0; }
body.ts-display-settings .ts-setting-label {
	font-size: 0.8125rem;
	font-weight: 550;
	color: var(--c-ink-2);
}
body.ts-display-settings .ts-setting-control {
	display: flex;
	align-items: center;
	gap: 10px;
	min-width: 0;
}
body.ts-display-settings .ts-setting-control select { max-width: 100%; }
body.ts-display-settings .ts-setting-control .select2-container {
	width: min(100%, 240px) !important;
	min-width: 0 !important;
}
body.ts-display-settings .ts-setting-control .select2-selection { min-height: 40px; }
body.ts-display-settings .ts-setting-control .select2-selection__rendered { line-height: 38px; }
body.ts-display-settings .ts-setting-control .select2-selection__arrow { height: 38px; }
/* Dolibarr's AJAX switch ships both states; only the live one should show. */
body.ts-display-settings .ts-setting-control span.valignmiddle { display: inline-flex; align-items: center; }

/* ---- colour fields ---- */
body.ts-display-settings .ts-color-grid {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	gap: 0 32px;
	padding: 4px 20px 12px;
}
body.ts-display-settings .ts-color-item {
	display: grid;
	/* A fixed control track, so the swatch and hex field share one x down the
	   column instead of drifting with each label's hint length. */
	grid-template-columns: minmax(0, 1fr) 332px;
	align-items: center;
	gap: 12px;
	min-height: 52px;
	padding: 8px 0;
	border-bottom: 1px solid var(--c-hairline);
}
body.ts-display-settings .ts-color-grid .ts-color-item:nth-last-child(-n + 2) { border-bottom: 0; }
body.ts-display-settings .ts-color-label {
	font-size: 0.8125rem;
	font-weight: 550;
	line-height: 1.35;
	color: var(--c-ink-2);
}
body.ts-display-settings .ts-color-control {
	/* Fixed tracks, not flex: the default hints differ in length, and on flex
	   that pushed every row's swatch and field to a different x. */
	display: grid;
	grid-template-columns: 38px 118px minmax(0, 1fr) 16px;
	align-items: center;
	gap: 10px;
	min-width: 0;
}
/* Every cell is pinned to row 1: the picker markup follows the input in the
   DOM, so assigning it an earlier column alone wrapped it onto a second row. */
body.ts-display-settings .ts-color-control span.jPicker { grid-column: 1; grid-row: 1; }
body.ts-display-settings .ts-color-control input[id^="colorpicker"] { grid-column: 2; grid-row: 1; }
body.ts-display-settings .ts-color-control .ts-color-default { grid-column: 3; grid-row: 1; }
body.ts-display-settings .ts-color-control .classfortooltip { grid-column: 4; grid-row: 1; }
/* jPicker renders the swatch as a bound container next to its input. */
/* jPicker already renders a live swatch; it just needs a size and to sit
   before the hex field, the way the value reads. */
body.ts-display-settings .ts-color-control span.jPicker {
	flex: 0 0 auto;
	margin: 0 !important;
}
body.ts-display-settings .ts-color-control span.jPicker span.Icon {
	display: block !important;
	width: 38px !important;
	height: 36px !important;
	border: 1px solid var(--c-border);
	border-radius: 6px;
	background-image: none !important;
	overflow: hidden;
}
body.ts-display-settings .ts-color-control span.jPicker span.Color {
	display: block !important;
	width: 100% !important;
	height: 100% !important;
	border: 0 !important;
	background-image: none !important;
}
body.ts-display-settings .ts-color-control span.jPicker span.Alpha { display: none !important; }
/* Image is jPicker's click target -- hiding it made the swatch inert and took
   the picker away. Keep it, sized over the swatch with its sprite dropped, so
   it stays the trigger while the colour underneath shows through. */
body.ts-display-settings .ts-color-control span.jPicker span.Image {
	display: block !important;
	width: 100% !important;
	height: 100% !important;
	background-image: none !important;
	background-color: transparent !important;
	border: 0 !important;
	cursor: pointer;
}
body.ts-display-settings .ts-color-control span.jPicker span.Icon { cursor: pointer; }
/* Dolibarr emits both halves of its AJAX switch and hides the inactive one with
   .hideobject; that rule is not reaching here, so both were rendering. */
body.ts-display-settings .ts-setting-control .hideobject { display: none !important; }
body.ts-display-settings .ts-color-control input[type="text"] {
	width: 118px;
	height: 36px;
	padding: 0 10px;
	border: 1px solid var(--c-border);
	border-radius: 6px;
	background: var(--c-surface);
	font-size: 0.8125rem;
	font-family: var(--font-mono, ui-monospace, SFMono-Regular, Menlo, monospace);
}
body.ts-display-settings .ts-color-default {
	font-size: 0.75rem;
	color: var(--c-ink-subtle);
	white-space: nowrap;
}
/* Dolibarr bolds and link-colours the default value; it is a hint, not a link. */
body.ts-display-settings .ts-color-control b,
body.ts-display-settings .ts-color-control strong,
body.ts-display-settings .ts-color-default-value {
	font-weight: 500 !important;
	color: var(--c-ink-subtle) !important;
}
body.ts-display-settings .ts-color-item .ts-color-control > img,
body.ts-display-settings .ts-setting-control > img {
	flex: 0 0 auto;
	opacity: .55;
}
body.ts-display-settings .ts-results-footer {
	padding: 12px 20px;
	border-top: 1px solid var(--c-hairline);
	font-size: 0.8125rem;
	color: var(--c-ink-subtle);
}

/* ---- action footer ---- */
body.ts-display-settings .ts-settings-actions {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 12px;
	margin: 24px 0 8px;
}
body.ts-display-settings .ts-settings-action {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	height: 42px;
	min-width: 108px;
	padding: 0 20px !important;
	border-radius: var(--r);
	font-size: 0.875rem;
	font-weight: 600;
	cursor: pointer;
}
body.ts-display-settings input.ts-settings-action-primary {
	border: 1px solid var(--c-btn-action, var(--c-accent)) !important;
	background: var(--c-btn-action, var(--c-accent)) !important;
	color: var(--c-btn-action-text, #fff) !important;
}
body.ts-display-settings input.ts-settings-action-primary:hover { filter: brightness(1.06); }
body.ts-display-settings .ts-settings-action-secondary,
body.ts-display-settings .ts-settings-actions a.butAction {
	border: 1px solid var(--c-border) !important;
	background: var(--c-surface) !important;
	color: var(--c-ink-2) !important;
}

@media only screen and (max-width: 1100px) {
	body.ts-display-settings .ts-settings-grid,
	body.ts-display-settings .ts-color-grid { grid-template-columns: minmax(0, 1fr); gap: 0; }
	body.ts-display-settings .ts-settings-grid .ts-setting:nth-last-child(-n + 2),
	body.ts-display-settings .ts-color-grid .ts-color-item:nth-last-child(-n + 2) {
		border-bottom: 1px solid var(--c-hairline);
	}
	body.ts-display-settings .ts-settings-grid .ts-setting:last-child,
	body.ts-display-settings .ts-color-grid .ts-color-item:last-child { border-bottom: 0; }
}
@media only screen and (max-width: 700px) {
	body.ts-display-settings .ts-theme-grid { grid-template-columns: repeat(auto-fill, minmax(132px, 1fr)); gap: 12px; padding: 16px; }
	body.ts-display-settings .ts-setting,
	body.ts-display-settings .ts-color-item { grid-template-columns: minmax(0, 1fr); gap: 8px; align-items: start; }
	body.ts-display-settings .ts-settings-card-head { flex-wrap: wrap; gap: 8px; }
	body.ts-display-settings .ts-settings-actions { flex-wrap: wrap; }
	body.ts-display-settings .ts-settings-action { flex: 1 1 140px; }
}

/* ==========================================================================
   Third party > Customer tab, right-hand summary column (comm/card.php)
   Route-gated by composeCustomerSummary(); scoped to body.ts-customer-summary.
   ========================================================================== */
body.ts-customer-summary .ts-cust-stack { display: grid; gap: 16px; min-width: 0; }
body.ts-customer-summary div.fichehalfright > br,
body.ts-customer-summary div.fichehalfright > .underbanner { display: none; }

/* ---- stat cards ---- */
body.ts-customer-summary .ts-kpi-grid {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	gap: 16px;
	min-width: 0;
}
body.ts-customer-summary a.ts-kpi-card {
	display: block !important;
	min-width: 0;
	padding: 16px 18px;
	border: 1px solid var(--c-hairline);
	border-radius: var(--r-lg);
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
	text-decoration: none !important;
}
body.ts-customer-summary a.ts-kpi-card:hover { border-color: var(--c-accent); }
body.ts-customer-summary a.ts-kpi-card .boxstats {
	display: grid !important;
	grid-template-columns: 44px minmax(0, 1fr);
	grid-template-rows: auto auto;
	align-items: center;
	gap: 2px 14px;
	width: auto !important;
	min-height: 0 !important;
	padding: 0 !important;
	border: 0 !important;
	background: none !important;
	text-align: left;
}
body.ts-customer-summary a.ts-kpi-card .ts-kpi-icon {
	display: inline-flex;
	grid-column: 1;
	grid-row: 1 / span 2;
	align-items: center;
	justify-content: center;
	width: 44px;
	height: 44px;
	border-radius: 10px;
	background: var(--c-accent-soft);
	color: var(--c-accent);
}
body.ts-customer-summary a.ts-kpi-card .ts-kpi-icon [class*="fa-"] {
	color: var(--c-accent) !important;
	font-size: 17px !important;
}
body.ts-customer-summary a.ts-kpi-card .ts-kpi-label {
	grid-column: 2;
	grid-row: 1;
	font-size: 0.8125rem;
	font-weight: 550;
	color: var(--c-ink-2);
}
body.ts-customer-summary a.ts-kpi-card .ts-kpi-value {
	grid-column: 2;
	grid-row: 2;
	font-size: 1.375rem;
	font-weight: 700;
	color: var(--c-ink);
}
body.ts-customer-summary a.ts-kpi-card br { display: none; }

/* ---- record list cards ---- */
body.ts-customer-summary .ts-latest-card {
	min-width: 0;
	border: 1px solid var(--c-hairline);
	border-radius: var(--r-lg);
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
	overflow: hidden;
}
body.ts-customer-summary .ts-latest-head {
	display: flex;
	align-items: center;
	gap: 10px;
	padding: 14px 18px;
	border-bottom: 1px solid var(--c-hairline);
}
body.ts-customer-summary .ts-latest-title {
	flex: 1 1 auto;
	min-width: 0;
	font-size: 0.9375rem;
	font-weight: 650;
	color: var(--c-ink);
}
body.ts-customer-summary .ts-latest-aside { flex: 0 0 auto; font-size: 0.8125rem; }
body.ts-customer-summary .ts-latest-aside a { color: var(--c-accent); text-decoration: none; }
body.ts-customer-summary .ts-latest-aside a:hover { text-decoration: underline; }
body.ts-customer-summary .ts-latest-aside .badge {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 22px;
	height: 22px;
	margin-left: 6px;
	padding: 0 7px;
	border-radius: 999px;
	background: var(--c-sunken);
	color: var(--c-ink-2);
	font-size: 0.75rem;
	font-weight: 600;
}
body.ts-customer-summary .ts-latest-card .div-table-responsive-no-min,
body.ts-customer-summary .ts-latest-card .div-table-responsive { margin: 0 !important; overflow-x: auto; }
body.ts-customer-summary table.ts-latest-table {
	width: 100%;
	margin: 0 !important;
	border: 0 !important;
	background: transparent !important;
}
body.ts-customer-summary table.ts-latest-table tr.oddeven,
body.ts-customer-summary table.ts-latest-table tr.impair,
body.ts-customer-summary table.ts-latest-table tr.pair { background: transparent !important; }
body.ts-customer-summary table.ts-latest-table tr + tr > td { border-top: 1px solid var(--c-hairline); }
body.ts-customer-summary table.ts-latest-table td {
	padding: 12px 14px !important;
	border-bottom: 0 !important;
	font-size: 0.8125rem;
	vertical-align: middle;
}
body.ts-customer-summary table.ts-latest-table td:first-child { padding-left: 18px !important; }
body.ts-customer-summary table.ts-latest-table td:last-child { padding-right: 18px !important; text-align: right; }
body.ts-customer-summary table.ts-latest-table a { text-decoration: none; }
body.ts-customer-summary table.ts-latest-table td [class*="fa-"] { margin-right: 6px; opacity: .85; }
/* Dolibarr's status label, as a pill. */
body.ts-customer-summary table.ts-latest-table span.badge-status,
body.ts-customer-summary table.ts-latest-table .badge {
	display: inline-flex;
	align-items: center;
	height: 24px;
	padding: 0 10px;
	border-radius: 999px;
	font-size: 0.75rem;
	font-weight: 600;
	white-space: nowrap;
}

@media only screen and (max-width: 1100px) {
	body.ts-customer-summary .ts-kpi-grid { gap: 12px; }
}
@media only screen and (max-width: 700px) {
	body.ts-customer-summary .ts-kpi-grid { grid-template-columns: minmax(0, 1fr); }
	body.ts-customer-summary a.ts-kpi-card { padding: 14px; }
	body.ts-customer-summary table.ts-latest-table td { padding: 10px !important; }
}

/* Corrections after measuring the composed column. */
/* The stat anchor carries Dolibarr's own thumbstat width; the grid cell is the
   authority here, and the label span is hidden by the native boxstats rules. */
body.ts-customer-summary a.ts-kpi-card {
	width: 100% !important;
	max-width: none !important;
	min-width: 0 !important;
	box-sizing: border-box;
}
body.ts-customer-summary a.ts-kpi-card .ts-kpi-label,
body.ts-customer-summary a.ts-kpi-card .ts-kpi-label > span {
	display: inline !important;
	visibility: visible !important;
	overflow: visible !important;
	width: auto !important;
	height: auto !important;
	font-size: 0.8125rem !important;
	color: var(--c-ink-2) !important;
}
body.ts-customer-summary a.ts-kpi-card .ts-kpi-value {
	display: block !important;
	width: auto !important;
}
/* Let the record tables size to their content and scroll inside the card
   rather than clipping refs and stacking dates one character per line. */
body.ts-customer-summary table.ts-latest-table {
	table-layout: auto !important;
	min-width: 100%;
}
body.ts-customer-summary table.ts-latest-table td { white-space: nowrap; }
body.ts-customer-summary table.ts-latest-table td:first-child { width: 38%; }
body.ts-customer-summary table.ts-latest-table td a { white-space: nowrap; }

/* Events/Agenda toolbar: target proportions -- 44px controls, 12px gaps, the
   search taking the slack, Date and Type at fixed widths, reset only when it
   has something to clear. */
body.ts-thirdparty-events .ts-events-toolbar { gap: 12px; padding: 0 24px 20px; }
body.ts-thirdparty-events .ts-events-filter-form .liste_titre {
	grid-template-columns: minmax(260px, 1fr) 190px 210px max-content;
	gap: 12px;
}
body.ts-thirdparty-events .ts-events-filter-form th { height: 44px !important; }
body.ts-thirdparty-events .ts-events-view-switch { height: 44px; }
body.ts-thirdparty-events .ts-events-search-control,
body.ts-thirdparty-events .ts-events-date-control { height: 44px; }
body.ts-thirdparty-events .ts-events-search-input { height: 42px !important; font-size: 0.875rem; }
body.ts-thirdparty-events .ts-events-filter-form .select2-container { height: 44px; }
body.ts-thirdparty-events .ts-events-filter-form .select2-selection--single {
	height: 44px !important;
	min-height: 44px !important;
}
body.ts-thirdparty-events .ts-events-filter-form .select2-selection__rendered {
	height: 42px !important;
	line-height: 42px !important;
	font-size: 0.875rem !important;
}
body.ts-thirdparty-events .ts-events-filter-form .select2-selection__arrow { height: 42px !important; }
body.ts-thirdparty-events .ts-events-filter-form button.button_search,
body.ts-thirdparty-events .ts-events-filter-form button.button_removefilter {
	height: 44px !important;
	padding: 0 16px !important;
	font-size: 0.875rem;
}
/* Reset carries a word now, so it is no longer a bare square. */
body.ts-thirdparty-events .ts-events-filter-form button.button_removefilter {
	width: auto !important;
	gap: 8px;
}
body.ts-thirdparty-events .ts-events-filter-form button.button_removefilter[hidden] { display: none !important; }
body.ts-thirdparty-events .ts-events-date-order { margin-left: auto; font-size: 11px; opacity: .6; }
body.ts-thirdparty-events .ts-events-date-control { justify-content: flex-start !important; gap: 10px; }
/* Segmented view switch: two equal, clearly-stated views. */
body.ts-thirdparty-events .ts-events-view-option {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 38px;
	height: 38px;
	border-radius: 7px;
}

/* ==========================================================================
   Shared COMMAND settings surface -- every Dolibarr admin screen composed by
   composeAdminSettings(). Scoped to body.ts-settings-page.
   ========================================================================== */
body.ts-settings-page .ts-settings-card {
	min-width: 0;
	margin: 0 0 20px;
	border: 1px solid var(--c-hairline);
	border-radius: var(--r-lg);
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
	overflow: hidden;
}
body.ts-settings-page .ts-settings-card-head {
	display: grid;
	grid-template-columns: minmax(0, 420px) minmax(0, 1fr);
	align-items: center;
	gap: 18px;
	padding: 15px 20px;
	border-bottom: 1px solid var(--c-hairline);
	background: var(--c-sunken);
}
body.ts-settings-page .ts-settings-card-title {
	display: flex;
	align-items: center;
	gap: 9px;
	margin: 0;
	font-size: 0.9375rem;
	font-weight: 650;
	color: var(--c-accent);
}
body.ts-settings-page .ts-settings-card-title [class*="fa-"] { color: var(--c-accent) !important; }
body.ts-settings-page .ts-settings-card-aside { flex: 0 0 auto; font-size: 0.8125rem; color: var(--c-ink-subtle); }
/* Native settings title rows contain actual column headings. Keep a second
   heading (for example “Late warning after”) above the same value axis as the
   controls instead of letting flexbox push it to the far edge of the card. */
body.ts-settings-page .ts-settings-card-head:not(:has(.ts-settings-card-aside)) .ts-settings-card-title { grid-column: 1 / -1; }
body.ts-settings-page .ts-settings-grid { display: grid; grid-template-columns: minmax(0, 1fr); }
body.ts-settings-page .ts-setting {
	display: grid;
	grid-template-columns: minmax(0, 420px) minmax(0, 1fr);
	align-items: center;
	gap: 18px;
	padding: 12px 20px;
	border-bottom: 1px solid var(--c-hairline);
}
body.ts-settings-page .ts-settings-grid .ts-setting:last-child { border-bottom: 0; }
body.ts-settings-page .ts-setting-label {
	display: flex;
	align-items: center;
	gap: 8px;
	flex-wrap: wrap;
	font-size: 0.8125rem;
	font-weight: 550;
	line-height: 1.4;
	color: var(--c-ink-2);
}
body.ts-settings-page .ts-setting-label [class*="fa-info"] { opacity: .55; }
body.ts-settings-page .ts-setting-control {
	display: flex;
	align-items: center;
	gap: 10px;
	min-width: 0;
}
/* Width bands, so a toggle does not occupy the same room as a URL field. */
body.ts-settings-page .ts-setting-control.ts-control-compact { max-width: 120px; }
body.ts-settings-page .ts-setting-control.ts-control-medium { max-width: 320px; }
body.ts-settings-page .ts-setting-control.ts-control-wide { max-width: 420px; }
body.ts-settings-page .ts-setting-control.ts-control-full { max-width: 100%; }

/* Limits/Precision configuration prints live examples as loose inline nodes.
   composeLimitsExamples() retains those nodes and gives them the same calm,
   compact settings surface used throughout COMMAND. */
body.ts-limits-examples-page .ts-limits-examples-card {
	margin: 20px 0 0;
	border: 1px solid var(--c-hairline);
	border-radius: var(--r-lg);
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
	overflow: hidden;
}
body.ts-limits-examples-page .ts-limits-examples-head {
	display: flex;
	align-items: center;
	min-height: 62px;
	padding: 0 24px;
	border-bottom: 1px solid var(--c-hairline);
}
body.ts-limits-examples-page .ts-limits-examples-title {
	margin: 0;
	font-size: 1.125rem;
	font-weight: 650;
	line-height: 1.25;
	color: var(--c-ink);
}
body.ts-limits-examples-page .ts-limits-examples-body {
	display: grid;
	gap: 8px;
	padding: 18px 24px 20px;
}
body.ts-limits-examples-page .ts-limits-example-line {
	display: flex;
	align-items: baseline;
	flex-wrap: wrap;
	gap: 0 5px;
	min-width: 0;
	font-size: .8125rem;
	line-height: 1.55;
	color: var(--c-ink-subtle);
}
body.ts-limits-examples-page .ts-limits-example-line > * { min-width: 0; }
body.ts-limits-examples-page .ts-limits-example-line b,
body.ts-limits-examples-page .ts-limits-example-line strong {
	font-weight: 650;
	color: var(--c-ink-2);
}
@media only screen and (max-width: 600px) {
	body.ts-limits-examples-page .ts-limits-examples-head { min-height: 56px; padding: 0 16px; }
	body.ts-limits-examples-page .ts-limits-examples-body { padding: 16px; gap: 10px; }
}

/* Constants is the one admin editor whose native table has four meaningful
   editable columns. Treat those columns as a real compact data grid instead
   of squeezing every field into the generic label/control setting row. */
body.ts-settings-page .ts-admin-constants-card .ts-settings-card-head,
body.ts-settings-page .ts-admin-constants-card .ts-admin-const-row {
	display: grid;
	grid-template-columns: minmax(240px, 1.35fr) minmax(150px, .62fr) minmax(260px, 1fr) minmax(118px, .5fr) 32px;
	column-gap: 12px;
}
body.ts-settings-page .ts-admin-constants-card .ts-settings-card-head {
	align-items: center;
	justify-content: unset;
}
body.ts-settings-page .ts-admin-constants-card .ts-settings-card-title,
body.ts-settings-page .ts-admin-constants-card .ts-settings-card-aside {
	min-width: 0;
	margin: 0;
}
body.ts-settings-page .ts-admin-constants-card .ts-settings-card-aside {
	font-weight: 600;
	white-space: nowrap;
}
body.ts-settings-page .ts-admin-constants-card .ts-admin-const-row {
	align-items: center;
	gap: 12px;
	padding: 12px 20px;
}
body.ts-settings-page .ts-admin-constants-card .ts-admin-const-row .ts-setting-label {
	grid-column: 1;
	min-width: 0;
	font-weight: 600;
	overflow-wrap: anywhere;
}
body.ts-settings-page .ts-admin-constants-card .ts-admin-const-row .ts-setting-control {
	display: contents;
}
body.ts-settings-page .ts-admin-constants-card .ts-admin-const-row input:not([type="hidden"]) {
	min-width: 0;
	width: 100% !important;
	max-width: none !important;
}
body.ts-settings-page .ts-admin-constants-card .ts-admin-const-value { grid-column: 2; }
body.ts-settings-page .ts-admin-constants-card .ts-admin-const-comment { grid-column: 3; }
body.ts-settings-page .ts-admin-constants-card .ts-admin-const-date {
	grid-column: 4;
	font-size: .8125rem;
	line-height: 1.35;
	color: var(--c-ink-subtle);
}
body.ts-settings-page .ts-admin-constants-card .ts-admin-const-remove {
	grid-column: 5;
	justify-self: center;
	width: 16px !important;
	height: 16px;
}
body.ts-settings-page .ts-admin-constants-card .ts-admin-const-add {
	grid-column: 4;
	justify-self: start;
	width: 88px !important;
	min-width: 88px !important;
	max-width: 88px !important;
}
@media only screen and (max-width: 900px) {
	body.ts-settings-page .ts-admin-constants-card .ts-settings-card-head { display: none; }
	body.ts-settings-page .ts-admin-constants-card .ts-admin-const-row {
		grid-template-columns: minmax(150px, .8fr) minmax(0, 1fr);
	}
	body.ts-settings-page .ts-admin-constants-card .ts-admin-const-row .ts-setting-label { grid-column: 1; }
	body.ts-settings-page .ts-admin-constants-card .ts-admin-const-value { grid-column: 2; }
	body.ts-settings-page .ts-admin-constants-card .ts-admin-const-comment { grid-column: 2; }
	body.ts-settings-page .ts-admin-constants-card .ts-admin-const-date { grid-column: 2; }
	body.ts-settings-page .ts-admin-constants-card .ts-admin-const-remove { grid-column: 2; justify-self: start; }
	body.ts-settings-page .ts-admin-constants-card .ts-admin-const-add { grid-column: 2; }
}
@media only screen and (max-width: 600px) {
	body.ts-settings-page .ts-admin-constants-card .ts-admin-const-row { grid-template-columns: minmax(0, 1fr); }
	body.ts-settings-page .ts-admin-constants-card .ts-admin-const-row .ts-setting-label,
	body.ts-settings-page .ts-admin-constants-card .ts-admin-const-value,
	body.ts-settings-page .ts-admin-constants-card .ts-admin-const-comment,
	body.ts-settings-page .ts-admin-constants-card .ts-admin-const-date,
	body.ts-settings-page .ts-admin-constants-card .ts-admin-const-remove,
	body.ts-settings-page .ts-admin-constants-card .ts-admin-const-add { grid-column: 1; }
}

/* Widgets administration: the active-widget table is still emitted by
   Dolibarr as a seven-cell table. Keep its native links and ordering controls,
   but give the name/target/order/action columns deliberate space so names do
   not collapse into vertical strips. */
body.page-boxes .ts-boxes-active-table {
	width: 100%;
	table-layout: fixed;
	border-collapse: separate;
	border-spacing: 0;
	border: 1px solid var(--c-hairline);
	border-radius: var(--r-lg);
	overflow: hidden;
	background: var(--c-surface);
}
body.page-boxes .ts-boxes-section-title {
	margin: 28px 0 12px;
	font-size: 1.125rem;
	font-weight: 650;
	color: var(--c-ink);
}
body.page-boxes .ts-boxes-active-table tr {
	height: 48px !important;
	background: var(--c-surface) !important;
}
body.page-boxes .ts-boxes-active-table tr:hover { background: var(--c-accent-soft) !important; }
body.page-boxes .ts-boxes-active-table td {
	padding: 10px 14px !important;
	border-bottom: 1px solid var(--c-hairline) !important;
	vertical-align: middle !important;
	font-size: .8125rem;
	line-height: 1.35;
}
body.page-boxes .ts-boxes-active-table tr:last-child td { border-bottom: 0 !important; }
body.page-boxes .ts-boxes-active-table td:nth-child(1) { width: 34%; min-width: 260px; text-align: left !important; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
body.page-boxes .ts-boxes-active-table td:nth-child(2) { width: 28%; text-align: left !important; color: var(--c-ink-subtle); }
body.page-boxes .ts-boxes-active-table td:nth-child(3) { width: 5%; text-align: center; }
body.page-boxes .ts-boxes-active-table td:nth-child(4) { width: 13%; text-align: center; }
body.page-boxes .ts-boxes-active-table td:nth-child(5) { width: 10%; text-align: center; }
body.page-boxes .ts-boxes-active-table td:nth-child(6) { width: 6%; text-align: center; }
body.page-boxes .ts-boxes-active-table td:nth-child(7) { width: 6%; text-align: center; }
body.page-boxes .ts-boxes-active-table td:first-child > span[class*="fa-"],
body.page-boxes .ts-boxes-active-table td:first-child > img { margin-right: 8px; color: var(--c-accent); }
body.page-boxes .ts-boxes-active-table td a.reposition {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 30px;
	height: 30px;
	margin: 0 2px;
	border-radius: 7px;
	color: var(--c-accent);
	text-decoration: none;
}
body.page-boxes .ts-boxes-active-table td a.reposition:hover { background: var(--c-accent-soft); }
body.page-boxes .ts-boxes-active-table td a.reposition .pictodelete { color: var(--c-danger); }
body.page-boxes .ts-boxes-active-table td a.reposition span[class*="fa-caret"] { font-size: .8rem; }
body.page-boxes .ts-boxes-active-table td a.reposition span.pictodelete { font-size: .9rem; }
body.page-boxes .ts-settings-action-primary,
body.page-boxes input[name="save"] { background: var(--c-btn-action) !important; color: var(--c-btn-action-text) !important; border: 0 !important; }
@media only screen and (max-width: 900px) {
	body.page-boxes .ts-boxes-active-table { table-layout: auto; }
	body.page-boxes .ts-boxes-active-table td:nth-child(1) { min-width: 220px; white-space: normal; }
	body.page-boxes .ts-boxes-active-table td:nth-child(2) { min-width: 120px; }
}
@media only screen and (max-width: 600px) {
	body.page-boxes .ts-boxes-active-table td { padding: 9px 10px !important; }
	body.page-boxes .ts-boxes-active-table td:nth-child(2),
	body.page-boxes .ts-boxes-active-table td:nth-child(3) { display: none; }
	body.page-boxes .ts-boxes-active-table td:nth-child(1) { min-width: 0; width: 55%; }
	body.page-boxes .ts-boxes-active-table td:nth-child(4) { width: 20%; }
	body.page-boxes .ts-boxes-active-table td:nth-child(5) { width: 12%; }
	body.page-boxes .ts-boxes-active-table td:nth-child(6),
	body.page-boxes .ts-boxes-active-table td:nth-child(7) { width: 7%; }
}
body.ts-settings-page .ts-setting-control input[type="text"],
body.ts-settings-page .ts-setting-control input[type="number"],
body.ts-settings-page .ts-setting-control input[type="email"],
body.ts-settings-page .ts-setting-control input[type="url"],
body.ts-settings-page .ts-setting-control input[type="password"],
body.ts-settings-page .ts-setting-control textarea {
	width: 100%;
	min-width: 0;
	height: 40px;
	padding: 0 12px;
	border: 1px solid var(--c-border);
	border-radius: var(--r);
	background: var(--c-surface);
	font-size: 0.8125rem;
}
body.ts-settings-page .ts-setting-control textarea { height: auto; min-height: 80px; padding: 10px 12px; }
body.ts-settings-page .ts-setting-control.ts-control-compact input { text-align: left; }
body.ts-settings-page .ts-setting-control select { max-width: 100%; }
body.ts-settings-page .ts-setting-control .select2-container {
	width: 100% !important;
	min-width: 0 !important;
}
body.ts-settings-page .ts-setting-control .select2-selection { min-height: 40px; }
body.ts-settings-page .ts-setting-control .select2-selection__rendered { line-height: 38px; font-size: 0.8125rem; }
body.ts-settings-page .ts-setting-control .select2-selection__arrow { height: 38px; }
/* Dolibarr renders both halves of its AJAX switch and hides the inactive one
   with .hideobject; that rule does not reach these composed rows. */
body.ts-settings-page .ts-setting-control .hideobject { display: none !important; }
body.ts-settings-page .ts-setting-control .fa-toggle-on,
body.ts-settings-page .ts-setting-control .fa-toggle-off { font-size: 26px; }
body.ts-settings-page .ts-setting-control > img,
body.ts-settings-page .ts-setting-control .classfortooltip { flex: 0 0 auto; }

/* Action row */
body.ts-settings-page .ts-settings-actions {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 12px;
	margin: 22px 0;
}
body.ts-settings-page .ts-settings-action {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	height: 42px;
	min-width: 104px;
	padding: 0 20px !important;
	border-radius: var(--r);
	font-size: 0.875rem;
	font-weight: 600;
	cursor: pointer;
}
body.ts-settings-page .ts-settings-action-primary {
	border: 1px solid var(--c-btn-action) !important;
	background: var(--c-btn-action) !important;
	color: var(--c-btn-action-text) !important;
}
/* A number of native setup forms submit an unnamed input rather than the
   composed .ts-settings-action-primary class. Keep those real Save actions in
   the same COMMAND hierarchy instead of exposing Dolibarr's red default. */
body.ts-settings-page input[type="submit"],
body.ts-settings-page button[type="submit"] {
	border: 1px solid var(--c-btn-action) !important;
	background: var(--c-btn-action) !important;
	color: var(--c-btn-action-text) !important;
	min-height: 42px;
	padding-inline: 20px !important;
	border-radius: var(--r) !important;
	font-weight: 600;
}
body.ts-settings-page input[type="submit"]:hover,
body.ts-settings-page button[type="submit"]:hover { filter: brightness(.94); }
/* Native setup pages often implement Cancel as another submit control. It is
   an escape route, not an action-color button, so it must retain the shared
   neutral treatment even when the configured action color is changed. */
body.ts-settings-page input[type="submit"][name="cancel"],
body.ts-settings-page input[type="submit"].button-cancel,
body.ts-settings-page button[type="submit"][name="cancel"],
body.ts-settings-page button[type="submit"].button-cancel {
	border-color: var(--c-border) !important;
	background: var(--c-surface) !important;
	color: var(--c-ink-2) !important;
	filter: none !important;
}
body.ts-settings-page .ts-settings-action-secondary,
body.ts-settings-page .ts-settings-actions a.butAction {
	border: 1px solid var(--c-border) !important;
	background: var(--c-surface) !important;
	color: var(--c-ink-2) !important;
}

/* Accounting source export: a standalone form that does not pass through the
   settings-table composer. Keep its existing fields/submission intact while
   applying the shared COMMAND card and control geometry. */
body.ts-accounting-files form.ts-accounting-export-form {
	margin: 0;
	padding: 22px 28px 26px;
	border: 0;
	border-radius: 0;
	background: transparent;
	box-shadow: none;
	color: var(--c-ink-2);
	font-size: .8125rem;
	line-height: 1.45;
}
body.ts-accounting-files .ts-accounting-export-intro {
	display: block;
	margin: 0 0 14px;
	color: var(--c-muted);
	font-size: .875rem;
}
/* The legacy form emits two literal breaks after the intro; the intro already
   owns its spacing, so those breaks created the large blank band in the card. */
body.ts-accounting-files .ts-accounting-export-intro + br,
body.ts-accounting-files .ts-accounting-export-intro + br + br { display: none; }
body.ts-accounting-files form[name="searchfiles"] .divfordateinput {
	display: inline-flex;
	align-items: center;
	vertical-align: middle;
}
body.ts-accounting-files form[name="searchfiles"] input.hasDatepicker {
	box-sizing: border-box;
	width: 150px;
	height: 40px;
	padding: 0 12px;
	border: 1px solid var(--c-border);
	border-radius: var(--r);
	background: var(--c-surface);
	font-size: .875rem;
}
body.ts-accounting-files form[name="searchfiles"] img.ui-datepicker-trigger {
	width: 18px;
	height: 18px;
	margin-left: -28px;
	opacity: .65;
}
body.ts-accounting-files form[name="searchfiles"] button.datenowlink {
	height: 32px;
	margin: 0 8px;
	padding: 0 10px;
	border: 1px solid var(--c-border);
	border-radius: var(--r);
	background: var(--c-sunken);
	color: var(--c-muted);
}
body.ts-accounting-files form[name="searchfiles"] .select2-container {
	width: 260px !important;
	max-width: min(260px, 100%);
	vertical-align: middle;
}
body.ts-accounting-files form[name="searchfiles"] .select2-selection,
body.ts-accounting-files form[name="searchfiles"] select {
	min-height: 40px;
	border: 1px solid var(--c-border);
	border-radius: var(--r);
	background: var(--c-surface);
}
body.ts-accounting-files form[name="searchfiles"] input[type="checkbox"] {
	width: 16px;
	height: 16px;
	margin: 0 4px 0 8px;
	accent-color: var(--c-accent);
	vertical-align: middle;
}
body.ts-accounting-files form[name="searchfiles"] input[type="checkbox"] + * {
	font-size: .8125rem;
}
body.ts-accounting-files form[name="searchfiles"] input[type="submit"],
body.ts-accounting-files form[name="searchfiles"] button[type="submit"] {
	height: 40px;
	margin-left: 12px;
	padding: 0 20px;
	border: 1px solid var(--c-btn-action) !important;
	border-radius: var(--r);
	background: var(--c-btn-action) !important;
	color: var(--c-btn-action-text) !important;
	font-weight: 650;
}
@media only screen and (max-width: 700px) {
	body.ts-accounting-files form.ts-accounting-export-form { padding: 18px 16px 20px; }
	body.ts-accounting-files form[name="searchfiles"] .select2-container { width: 100% !important; }
}

@media only screen and (max-width: 1100px) {
	body.ts-settings-page .ts-setting { grid-template-columns: minmax(0, 300px) minmax(0, 1fr); gap: 14px; }
}
@media only screen and (max-width: 700px) {
	body.ts-settings-page .ts-settings-card-head { grid-template-columns: minmax(0, 1fr); gap: 6px; }
	body.ts-settings-page .ts-settings-card-aside { grid-column: 1; }
	body.ts-settings-page .ts-setting { grid-template-columns: minmax(0, 1fr); gap: 8px; align-items: start; }
	body.ts-settings-page .ts-setting-control.ts-control-compact,
	body.ts-settings-page .ts-setting-control.ts-control-medium,
	body.ts-settings-page .ts-setting-control.ts-control-wide { max-width: 100%; }
	body.ts-settings-page .ts-settings-actions { flex-wrap: wrap; }
	body.ts-settings-page .ts-settings-action { flex: 1 1 140px; }
}

/* ==========================================================================
   Record card field panels (Proposal, Order, Invoice, Product, Contact ...)

   Dolibarr renders each detail panel as table.tableforfield inside the
   fichehalfleft/fichehalfright columns. Those rows carry nested tables and
   inline edit links, so this is styling only -- no node is moved, which keeps
   every edit link, tooltip and form exactly where Dolibarr put it.

   The third-party record page has its own composition and is excluded.
   ========================================================================== */
body.ts-command-record-page:not(.ts-thirdparty-record-context) div.fichehalfleft > table.tableforfield,
body.ts-command-record-page:not(.ts-thirdparty-record-context) div.fichehalfright > table.tableforfield,
body.ts-command-record-page:not(.ts-thirdparty-record-context) div.fichehalfleft > div > table.tableforfield,
body.ts-command-record-page:not(.ts-thirdparty-record-context) div.fichehalfright > div > table.tableforfield {
	width: 100%;
	margin: 0 0 16px;
	border: 1px solid var(--c-hairline) !important;
	border-radius: var(--r-lg);
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
	border-collapse: separate;
	border-spacing: 0;
	overflow: hidden;
}
body.ts-command-record-page:not(.ts-thirdparty-record-context) table.tableforfield > tbody > tr > td {
	padding: 11px 16px !important;
	border: 0 !important;
	border-bottom: 1px solid var(--c-hairline) !important;
	font-size: 0.8125rem;
	vertical-align: middle;
	background: transparent !important;
}
body.ts-command-record-page:not(.ts-thirdparty-record-context) table.tableforfield > tbody > tr:last-child > td {
	border-bottom: 0 !important;
}
/* First cell is the label column; keep every panel on one label axis. */
body.ts-command-record-page:not(.ts-thirdparty-record-context) table.tableforfield > tbody > tr > td:first-child {
	width: 38%;
	color: var(--c-ink-2);
	font-weight: 550;
}
body.ts-command-record-page:not(.ts-thirdparty-record-context) table.tableforfield > tbody > tr > td:first-child table {
	width: 100%;
	background: transparent !important;
}
body.ts-command-record-page:not(.ts-thirdparty-record-context) table.tableforfield > tbody > tr > td:first-child table td {
	padding: 0 !important;
	border: 0 !important;
	font-weight: inherit;
	color: inherit;
}
body.ts-command-record-page:not(.ts-thirdparty-record-context) table.tableforfield > tbody > tr > td:last-child {
	color: var(--c-ink);
}
/* Dolibarr stripes these rows; a card reads better flat. */
body.ts-command-record-page:not(.ts-thirdparty-record-context) table.tableforfield > tbody > tr.oddeven,
body.ts-command-record-page:not(.ts-thirdparty-record-context) table.tableforfield > tbody > tr.impair,
body.ts-command-record-page:not(.ts-thirdparty-record-context) table.tableforfield > tbody > tr.pair {
	background: transparent !important;
}
body.ts-command-record-page:not(.ts-thirdparty-record-context) table.tableforfield a.editfielda {
	opacity: .6;
	margin-left: 6px;
}
body.ts-command-record-page:not(.ts-thirdparty-record-context) table.tableforfield a.editfielda:hover { opacity: 1; }

@media only screen and (max-width: 700px) {
	body.ts-command-record-page:not(.ts-thirdparty-record-context) table.tableforfield > tbody > tr > td:first-child { width: 44%; }
	body.ts-command-record-page:not(.ts-thirdparty-record-context) table.tableforfield > tbody > tr > td { padding: 9px 12px !important; }
}

/* ==========================================================================
   Record card line items (Proposal, Order, Invoice)

   All three render their lines as table#tablelines inside a responsive div,
   so one selector covers the set. Styling only -- the drag handles, inline
   edit forms and the "add new line" row keep their markup and behaviour.
   ========================================================================== */
body.ts-command-record-page:not(.ts-thirdparty-record-context) div.div-table-responsive-no-min:has(> table#tablelines),
body.ts-command-record-page:not(.ts-thirdparty-record-context) div.div-table-responsive:has(> table#tablelines) {
	margin: 0 0 16px;
	border: 1px solid var(--c-hairline);
	border-radius: var(--r-lg);
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
	overflow: hidden;
	overflow-x: auto;
}
body.ts-command-record-page:not(.ts-thirdparty-record-context) table#tablelines {
	width: 100%;
	margin: 0 !important;
	border: 0 !important;
	background: transparent !important;
}
body.ts-command-record-page:not(.ts-thirdparty-record-context) table#tablelines > tbody > tr.liste_titre > td,
body.ts-command-record-page:not(.ts-thirdparty-record-context) table#tablelines > tbody > tr.liste_titre > th {
	padding: 11px 12px !important;
	border: 0 !important;
	border-bottom: 1px solid var(--c-hairline) !important;
	background: var(--c-sunken) !important;
	color: var(--c-ink-2) !important;
	font-size: 0.75rem;
	font-weight: 600;
	white-space: nowrap;
}
body.ts-command-record-page:not(.ts-thirdparty-record-context) table#tablelines > tbody > tr > td {
	padding: 11px 12px !important;
	border: 0 !important;
	border-bottom: 1px solid var(--c-hairline) !important;
	font-size: 0.8125rem;
	vertical-align: middle;
}
body.ts-command-record-page:not(.ts-thirdparty-record-context) table#tablelines > tbody > tr:last-child > td { border-bottom: 0 !important; }
/* Dolibarr stripes these; a card reads better flat. */
body.ts-command-record-page:not(.ts-thirdparty-record-context) table#tablelines > tbody > tr.pair,
body.ts-command-record-page:not(.ts-thirdparty-record-context) table#tablelines > tbody > tr.impair,
body.ts-command-record-page:not(.ts-thirdparty-record-context) table#tablelines > tbody > tr.oddeven {
	background: transparent !important;
}
body.ts-command-record-page:not(.ts-thirdparty-record-context) table#tablelines > tbody > tr:hover > td { background: var(--c-sunken); }
body.ts-command-record-page:not(.ts-thirdparty-record-context) table#tablelines input[type="text"],
body.ts-command-record-page:not(.ts-thirdparty-record-context) table#tablelines select {
	height: 36px;
	border-radius: 6px;
	font-size: 0.8125rem;
}

/* Narrow Select2 triggers.

   Dolibarr sizes some selects with its own width50/width75/width100 classes.
   Our Select2 rendering reserves 38px on the right for the arrow, which at
   75px leaves too little room for the value itself -- a year read as "20…".
   Give those triggers a workable floor and tighten their internal spacing so
   the value fits. Applies wherever a narrow select appears, not just the
   product statistics filter where it was noticed. */
body span.select2-container.width50,
body span.select2-container.width75 { min-width: 92px !important; }
body span.select2-container.width100 { min-width: 108px !important; }
body span.select2-container.width50 span.select2-selection__rendered,
body span.select2-container.width75 span.select2-selection__rendered,
body span.select2-container.width100 span.select2-selection__rendered {
	width: auto !important;
	min-width: 0 !important;
	max-width: none !important;
	padding-right: 24px !important;
	padding-left: 9px !important;
	text-overflow: clip !important;
}
body span.select2-container.width50 span.select2-selection__arrow,
body span.select2-container.width75 span.select2-selection__arrow,
body span.select2-container.width100 span.select2-selection__arrow { right: 4px !important; }

/* Categories landing page summary table. Styling only -- the page has no filter
   form, so there is nothing to compose, just a table sitting bare on the page. */
body.ts-category-index table.ts-category-index-table {
	width: 100%;
	margin: 0 0 16px;
	border: 1px solid var(--c-hairline) !important;
	border-radius: var(--r-lg);
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
	border-collapse: separate;
	border-spacing: 0;
	overflow: hidden;
}
body.ts-category-index table.ts-category-index-table > tbody > tr > td,
body.ts-category-index table.ts-category-index-table > tbody > tr > th {
	padding: 12px 16px !important;
	border: 0 !important;
	border-bottom: 1px solid var(--c-hairline) !important;
	font-size: 0.8125rem;
	vertical-align: middle;
}
body.ts-category-index table.ts-category-index-table > tbody > tr.liste_titre > th,
body.ts-category-index table.ts-category-index-table > tbody > tr.liste_titre > td {
	background: var(--c-sunken) !important;
	color: var(--c-ink-2) !important;
	font-size: 0.75rem;
	font-weight: 600;
}
body.ts-category-index table.ts-category-index-table > tbody > tr:last-child > td { border-bottom: 0 !important; }
body.ts-category-index table.ts-category-index-table > tbody > tr.oddeven,
body.ts-category-index table.ts-category-index-table > tbody > tr.impair,
body.ts-category-index table.ts-category-index-table > tbody > tr.pair { background: transparent !important; }


/* Date filter controls and the jQuery UI picker.

   The two halves of a range were sized by different rules, so "from" and "to"
   did not match; the picker trigger was a bare 26px image; and the calendar
   itself inherited jQuery UI's default metrics, which read as cramped next to
   the rest of the toolbar. Size all three together. */
.ts-filter-surface input.hasDatepicker,
.ts-filter-surface .divfordateinput input[type="text"],
.ts-setting-control input.hasDatepicker {
	width: 112px !important;
	min-width: 112px !important;
	max-width: 112px !important;
	height: 38px;
	box-sizing: border-box;
	text-align: center;
}
.ts-filter-surface img.ui-datepicker-trigger,
.ts-setting-control img.ui-datepicker-trigger {
	width: 34px !important;
	height: 34px !important;
	padding: 8px;
	box-sizing: border-box;
	border: 1px solid var(--c-border);
	border-radius: var(--r);
	background: var(--c-surface);
	cursor: pointer;
	opacity: .75;
}
.ts-filter-surface img.ui-datepicker-trigger:hover,
.ts-setting-control img.ui-datepicker-trigger:hover {
	border-color: var(--c-accent);
	opacity: 1;
}

/* The calendar itself */
#ui-datepicker-div.ui-datepicker {
	width: 268px;
	padding: 10px;
	border: 1px solid var(--c-hairline);
	border-radius: var(--r-lg);
	background: var(--c-surface);
	box-shadow: var(--sh-lg);
	font-size: 0.8125rem;
}
#ui-datepicker-div .ui-datepicker-header {
	padding: 2px 0 8px;
	border: 0;
	background: transparent;
}
#ui-datepicker-div .ui-datepicker-title {
	display: flex;
	gap: 8px;
	justify-content: center;
	margin: 0 28px;
}
#ui-datepicker-div .ui-datepicker-title select.ui-datepicker-month,
#ui-datepicker-div .ui-datepicker-title select.ui-datepicker-year {
	width: auto !important;
	min-width: 78px !important;
	height: 32px;
	padding: 0 6px !important;
	border: 1px solid var(--c-border);
	border-radius: 6px;
	background: var(--c-surface);
	font-size: 0.8125rem;
}
#ui-datepicker-div .ui-datepicker-prev,
#ui-datepicker-div .ui-datepicker-next {
	top: 4px;
	width: 28px;
	height: 28px;
	border: 1px solid var(--c-border) !important;
	border-radius: 6px;
	background: var(--c-surface) !important;
	cursor: pointer;
}
#ui-datepicker-div .ui-datepicker-prev { left: 4px; }
#ui-datepicker-div .ui-datepicker-next { right: 4px; }
#ui-datepicker-div table.ui-datepicker-calendar { margin: 0; font-size: 0.8125rem; }
#ui-datepicker-div .ui-datepicker-calendar th {
	padding: 6px 0;
	color: var(--c-ink-subtle);
	font-size: 0.6875rem;
	font-weight: 600;
}
#ui-datepicker-div .ui-datepicker-calendar td { padding: 2px; border: 0; }
#ui-datepicker-div .ui-datepicker-calendar td a,
#ui-datepicker-div .ui-datepicker-calendar td span {
	display: block;
	width: 32px;
	height: 32px;
	padding: 0;
	border: 0;
	border-radius: 7px;
	background: transparent;
	color: var(--c-ink-2);
	line-height: 32px;
	text-align: center;
}
#ui-datepicker-div .ui-datepicker-calendar td a:hover { background: var(--c-sunken); }
#ui-datepicker-div .ui-datepicker-calendar td a.ui-state-active,
#ui-datepicker-div .ui-datepicker-calendar td a.ui-state-highlight {
	background: var(--c-accent) !important;
	color: #fff !important;
	font-weight: 600;
}

/* Busy month cells retain their real event links but disclose surplus entries
   instead of stretching one week into a page-length column. */
/* A single Dolibarr calendar event can contain a full title, an avatar, and a
   status cell.  In month view that is information density, not a record card:
   constrain the visual preview while preserving the original event link and
   its native title tooltip. */
table.cal_month .agendacell > .event > table.cal_event {
	table-layout: fixed !important;
	width: 100% !important;
	margin: 0 !important;
	font-size: 11px !important;
}
table.cal_month .agendacell > .event > table.cal_event td.cal_event {
	min-width: 0 !important;
	padding: 5px 4px !important;
	white-space: normal !important;
	vertical-align: top !important;
}
table.cal_month .agendacell > .event .cal_event_title {
	display: -webkit-box !important;
	-webkit-box-orient: vertical;
	-webkit-line-clamp: 2;
	overflow: hidden !important;
	line-height: 1.35 !important;
	word-break: break-word;
}
table.cal_month .agendacell > .event > table.cal_event td.cal_event > br,
table.cal_month .agendacell > .event > table.cal_event td.cal_event > a.classforajaxtooltip:not(.cal_event_title) {
	display: none !important;
}
table.cal_month .agendacell > .event {
	max-height: 66px;
	margin-bottom: 4px;
	overflow: hidden;
}
table.cal_month .agendacell > .event.ts-agenda-month-overflow { display: none; }
table.cal_month .agendacell.ts-agenda-month-expanded > .event.ts-agenda-month-overflow { display: block; }
table.cal_month .ts-agenda-month-more {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-height: 28px;
	margin: 6px 0 2px;
	padding: 0 8px;
	border: 0;
	border-radius: 6px;
	background: var(--c-accent-soft);
	color: var(--c-accent-ink);
	font: inherit;
	font-size: 12px;
	font-weight: 600;
	cursor: pointer;
}
table.cal_month .ts-agenda-month-more:hover,
table.cal_month .ts-agenda-month-more:focus-visible { background: color-mix(in srgb, var(--c-accent) 16%, #fff); outline: 0; }
@media (max-width: 700px) {
	/* Seven translated weekday names cannot all retain desktop typography on a
	   phone. Give every header a fixed share of the calendar and clip within its
	   own cell instead of allowing neighbouring labels to run together. */
	table.cal_month { table-layout: fixed !important; }
	table.cal_month th,
	table.cal_month td.tdfordaytitle {
		min-width: 0 !important;
		padding-inline: 1px !important;
		font-size: 9px !important;
		letter-spacing: -.15px;
		white-space: nowrap !important;
		overflow: hidden !important;
		text-overflow: ellipsis !important;
	}
}

/* Status dots.

   Dolibarr's .badge-dot carries the pill padding of a full status badge, so it
   measured 18x22 and a 999px radius drew an ellipse rather than a dot. Give it
   equal sides and the radius follows. Scoped to badge-dot so the wider
   badge-status pills keep their shape. */
span.badge.badge-dot,
.badge.badge-dot {
	display: inline-block !important;
	width: 11px !important;
	height: 11px !important;
	min-width: 11px !important;
	min-height: 11px !important;
	max-width: 11px !important;
	padding: 0 !important;
	border-radius: 50% !important;
	font-size: 0 !important;
	line-height: 0 !important;
	vertical-align: middle;
	overflow: hidden;
}

/* Colour rows carry the picker's own <link> and <script> tags inside the cell.
   As grid items they auto-placed into a second row, padding each control to
   67px for 36px of visible content -- the swatch stayed at the top while the
   label centred against the taller box, which read as the labels sitting low.
   Take them out of layout. */
.ts-color-control > link,
.ts-color-control > script,
.ts-setting-control > link,
.ts-setting-control > script {
	display: none !important;
}
/* Only the first row holds real cells; template it and pin the extras to zero
   so a stray anonymous item cannot pad the control. */
.ts-color-control {
	grid-template-rows: minmax(38px, auto) !important;
	grid-auto-rows: 0 !important;
	row-gap: 0 !important;
	overflow: hidden;
}

/* The version in the top bar rendered at 8px -- below the point where it can be
   read at all, and the smallest text anywhere in the shell. */
#id-top .hideonsmartphone.small,
.ts-topbar .hideonsmartphone.small {
	font-size: 0.6875rem !important;
	color: var(--c-ink-subtle) !important;
	letter-spacing: 0.01em;
}

/* Page header and tab strip.

   Applies wherever the shell renders a .ts-pagehead with a tab strip, so the
   treatment is shared rather than per-page. */

/* Any picto Dolibarr already prints beside a page title becomes a soft tile.
   Only pages that ship an icon get one -- none is invented. */
.ts-pagehead-title > .ts-pagehead-icon,
.ts-pagehead-title .titre > img.pictotitle,
.ts-pagehead-title .titre > span.pictotitle,
.ts-pagehead-title .titre > [class*="fa-"]:first-child {
	display: inline-flex !important;
	align-items: center;
	justify-content: center;
	width: 46px;
	height: 46px;
	margin: 0 14px 0 0 !important;
	padding: 0 !important;
	border-radius: 12px;
	background: var(--c-accent-soft);
	color: var(--c-accent) !important;
	font-size: 20px !important;
	vertical-align: middle;
}

/* Colour picker popup.

   jPicker positions this itself, near the trigger, and a field in the right
   column or low on the page opened it past the edge of the window -- partly
   unreachable. Scripted repositioning proved unreliable because the plugin
   lays the panel out on its own schedule, so pin it in the viewport instead:
   centred, never larger than the window, and scrollable if it ever is. */
.jPicker.Container {
	position: fixed !important;
	top: 50% !important;
	left: 50% !important;
	right: auto !important;
	bottom: auto !important;
	transform: translate(-50%, -50%) !important;
	max-width: calc(100vw - 32px) !important;
	max-height: calc(100vh - 32px) !important;
	overflow: auto !important;
	z-index: 2000 !important;
	border-radius: var(--r-lg);
	box-shadow: var(--sh-lg);
}

/* ==========================================================================
   Compound measurement fields (value + unit)

   Dolibarr renders weight, dimensions, area and volume as a value input and a
   unit select loose in one cell. Two form rules stretched those inputs to the
   full cell, which pushed the unit select onto its own line.

   The winning one scored (0,33,5):
     div.tabBar table.border:not(.liste) td > span[class*="fa-"] + input:not(...)
   It matches because these fields carry an icon immediately before the input.
   Dimensions escaped it only because they ship class="width50", which those
   same rules already exclude.

   So this follows the exclusion the stylesheet already uses -- the inputs are
   marked .ts-measure and added to that :not() list -- rather than escalating
   specificity. Sizing below is then plain, and one pattern covers every
   measurement field wherever Dolibarr renders one.
   ========================================================================== */
.ts-measure-cell { white-space: nowrap; }
.ts-measure-cell > span[class*="fa-"] {
	display: inline-block;
	width: 26px;
	margin-right: 10px;
	color: var(--c-accent);
	text-align: center;
	vertical-align: middle;
}
input.ts-measure {
	display: inline-block;
	height: 40px;
	margin: 0;
	vertical-align: middle;
}
input.ts-measure-value { width: 168px; }
div.tabBar table td > input.ts-measure-dim { width: 116px; margin-right: 4px; }
.ts-measure-cell .ts-measure-x {
	display: inline-block;
	width: 18px;
	color: var(--c-ink-subtle);
	text-align: center;
	vertical-align: middle;
}
.ts-measure-cell > .select2-container {
	display: inline-block !important;
	/* Sized to the longest unit label it has to hold ("mm3 (ul)"), not to the
	   space available -- a unit is a short enum, so a wide box reads as an empty
	   field rather than a compact control. */
	width: 138px !important;
	min-width: 138px !important;
	max-width: 138px !important;
	margin-left: 12px;
	vertical-align: middle;
}
.ts-measure-cell > .select2-container .select2-selection {
	height: 40px !important;
	min-height: 40px !important;
	border: 1px solid var(--c-border) !important;
	border-radius: var(--r) !important;
	background: var(--c-surface) !important;
	box-shadow: none !important;
}
.ts-measure-cell > .select2-container .select2-selection__rendered {
	height: 38px !important;
	line-height: 38px !important;
	padding: 0 26px 0 12px !important;
	font-size: 0.8125rem !important;
}
.ts-measure-cell > .select2-container .select2-selection__arrow { height: 38px !important; right: 6px !important; }

@media only screen and (max-width: 1100px) {
	input.ts-measure-value { width: 132px; }
	div.tabBar table td > input.ts-measure-dim { width: 92px; }
	.ts-measure-cell > .select2-container { width: 126px !important; min-width: 126px !important; max-width: 126px !important; margin-left: 8px; }
}
@media only screen and (max-width: 700px) {
	.ts-measure-cell { white-space: normal; }
	input.ts-measure-value {
		width: 168px !important;
		max-width: 100% !important;
	}
	div.tabBar table td > input.ts-measure-dim {
		width: 96px !important;
		max-width: 96px !important;
	}
	.ts-measure-cell > .select2-container {
		width: 138px !important;
		min-width: 0 !important;
		max-width: 100% !important;
		margin-left: 8px;
	}
}

/* ==========================================================================
   Kanban cards for modules without their own adapter

   The third-party adapter rebuilds its cards into a ts-kanban-* structure.
   Every other module keeps Dolibarr's native card: an .info-box holding
   .info-box-img, .info-box-ref, a select checkbox, .info-box-label and
   .info-box-status. Those parts are the same everywhere, so they can be given
   the same treatment without rebuilding anything -- styling only, so each
   module's own fields, links and tooltips stay exactly as Dolibarr wrote them.

   Scoped to the shared marker so the third-party view, which is already
   composed, is untouched.
   ========================================================================== */
[data-ts-kanban="shared"] > .box-flex-item {
	position: relative;
	padding: 0;
	border: 1px solid var(--c-hairline);
	border-radius: var(--r-lg);
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
	overflow: hidden;
	transition: border-color var(--t), box-shadow var(--t);
}
[data-ts-kanban="shared"] > .box-flex-item:hover {
	border-color: var(--c-border-strong);
	box-shadow: var(--sh-md, var(--sh-sm));
}
div.box-flex-container[data-ts-kanban="shared"] > .box-flex-item .info-box,
[data-ts-kanban="shared"] .info-box {
	display: flex !important;
	flex-direction: row !important;
	align-items: flex-start;
	gap: 12px;
	min-height: 0 !important;
	margin: 0 !important;
	padding: 14px 16px !important;
	border: 0 !important;
	background: transparent !important;
	box-shadow: none !important;
}
/* Picto becomes the same soft tile the rest of the shell uses. */
[data-ts-kanban="shared"] .info-box-img,
[data-ts-kanban="shared"] .info-box-icon {
	display: inline-flex !important;
	align-items: center;
	justify-content: center;
	flex: 0 0 auto;
	width: 38px !important;
	height: 38px !important;
	margin: 0 !important;
	padding: 0 !important;
	border-radius: 10px;
	background: var(--c-accent-soft) !important;
	font-size: 15px;
	line-height: 1;
}
[data-ts-kanban="shared"] .info-box-img [class*="fa-"],
[data-ts-kanban="shared"] .info-box-icon [class*="fa-"] { color: var(--c-accent) !important; }
div.box-flex-container[data-ts-kanban="shared"] > .box-flex-item .info-box-content,
[data-ts-kanban="shared"] .info-box-content {
	display: block !important;
	flex: 1 1 auto;
	min-width: 0;
	margin: 0 !important;
	padding: 0 !important;
}
/* Reference reads as the card's title. */
[data-ts-kanban="shared"] .info-box-ref,
[data-ts-kanban="shared"] .info-box-ref a {
	max-width: 100% !important;
	font-size: 0.875rem !important;
	font-weight: 650 !important;
	color: var(--c-ink) !important;
	text-decoration: none;
}
[data-ts-kanban="shared"] .info-box-ref a:hover { color: var(--c-accent) !important; }
[data-ts-kanban="shared"] .info-box-label {
	display: block !important;
	max-width: 100% !important;
	margin-top: 2px;
	color: var(--c-ink-subtle) !important;
	font-size: 0.8125rem !important;
	opacity: 1 !important;
}
/* Remaining rows -- amounts, stock, dates -- read as quiet metadata. */
[data-ts-kanban="shared"] .info-box-status {
	display: flex !important;
	align-items: center;
	gap: 8px;
	margin-top: 10px;
	color: var(--c-ink-2) !important;
	font-size: 0.8125rem !important;
	opacity: 1 !important;
}
[data-ts-kanban="shared"] .info-box-status [class*="fa-"] { opacity: .6; }
/* The select box belongs in the corner, not in the middle of the text. */
[data-ts-kanban="shared"] input.checkforselect {
	position: absolute;
	top: 12px;
	right: 12px;
	float: none !important;
	margin: 0 !important;
}
[data-ts-kanban="shared"] .info-box-content > br { display: none; }

/* Module cards keep their version marker inside the icon element.  Once the
   shared Kanban surface turns that element into a fixed tile, the marker
   must leave inline flow or it can paint over the module title. */
body.page-modules div.box-flex-container[data-ts-kanban="shared"] .info-box-module .info-box {
	position: relative;
}
body.page-modules div.box-flex-container[data-ts-kanban="shared"] .info-box-module .info-box-icon {
	position: static;
	overflow: visible;
}
body.page-modules div.box-flex-container[data-ts-kanban="shared"] .info-box-module .info-box-icon-version {
	position: absolute;
	top: 9px;
	right: 12px;
	z-index: 2;
	max-width: 92px;
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
	font-size: .6875rem;
	line-height: 1.2;
	font-weight: 600;
	color: var(--c-muted);
	background: var(--c-surface);
}
body.page-modules div.box-flex-container[data-ts-kanban="shared"] .info-box-module .info-box-title {
	padding-right: 78px;
}

/* Kanban card metadata rows: one field per line, aligned with the title. */
[data-ts-kanban="shared"] .ts-kanban-meta {
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
	align-items: center;
	gap: 6px 10px;
	margin-top: 10px;
}
[data-ts-kanban="shared"] .ts-kanban-meta-row {
	display: flex !important;
	align-items: center;
	gap: 8px;
	margin: 0 !important;
	padding: 0 !important;
	color: var(--c-ink-2);
	font-size: 0.8125rem;
	opacity: 1 !important;
}
[data-ts-kanban="shared"] .ts-kanban-meta-row [class*="fa-"] {
	flex: 0 0 16px;
	width: 16px;
	text-align: center;
	opacity: .6;
}
[data-ts-kanban="shared"] .ts-kanban-meta-row .badge-status { margin-left: 0; }

/* ==========================================================================
   Icon-only actions

   Dolibarr's inline edit/copy/view actions rendered at whatever size their
   content and inherited line-height produced: measured across record cards the
   same .editfielda was 14x17 bare inline in one place and 32x24 or 34x24 with a
   radius in another, holding a 22px icon in a 24px box. The glyph is centred in
   that box, but a squat rectangle around a nearly-overflowing icon reads as
   off-centre, and the hover surface differed per page.

   One square interaction box, with the icon's own line-height and spacing
   neutralised so nothing can push it off centre. Only actions that are icon-only
   are matched -- labelled buttons keep their own shape.
   ========================================================================== */
a.editfielda,
a.editfield,
span.editfielda,
.ts-icon-action {
	box-sizing: border-box !important;
	display: inline-flex !important;
	align-items: center !important;
	justify-content: center !important;
	width: 32px !important;
	height: 32px !important;
	min-width: 32px !important;
	padding: 0 !important;
	margin: 0 2px !important;
	border-radius: 8px;
	line-height: 1 !important;
	text-indent: 0 !important;
	vertical-align: middle;
	color: var(--c-ink-subtle);
	transition: background var(--t), color var(--t);
}
a.editfielda:hover,
a.editfield:hover,
.ts-icon-action:hover {
	background: var(--c-sunken);
	color: var(--c-accent);
}
a.editfielda:focus-visible,
a.editfield:focus-visible,
.ts-icon-action:focus-visible {
	outline: 2px solid var(--c-accent);
	outline-offset: 1px;
}
/* The glyph itself carries Dolibarr's line-height and padding; neutralise both
   so the flex centring is the only thing positioning it. */
a.editfielda > [class*="fa-"],
a.editfield > [class*="fa-"],
span.editfielda > [class*="fa-"],
.ts-icon-action > [class*="fa-"],
a.editfielda > svg,
a.editfield > svg,
span.editfielda > svg,
.ts-icon-action > svg {
	display: inline-flex !important;
	align-items: center !important;
	justify-content: center !important;
	width: 14px !important;
	height: 14px !important;
	min-width: 14px !important;
	max-width: 14px !important;
	min-height: 14px !important;
	max-height: 14px !important;
	flex: 0 0 14px !important;
	box-sizing: border-box !important;
	margin: 0 !important;
	padding: 0 !important;
	line-height: 1 !important;
	font-size: 13px !important;
	vertical-align: middle !important;
}

/* Lists whose leading select column is never filled -- see
   collapseUnusedSelectColumn(). Marked in script because emptiness cannot be
   tested in CSS. */
table.liste.ts-list-no-select-col > tbody > tr > *:first-child {
	width: 0 !important;
	min-width: 0 !important;
	max-width: 0 !important;
	padding-left: 0 !important;
	padding-right: 0 !important;
	overflow: hidden;
}

/* Member note tab: turn the native detail and note tables into the same calm
   cards used by the other member/record tabs.  The original tables and edit
   links remain in place so permissions and note actions are untouched. */
body.page-card_note .ts-member-note-details {
	display: block;
	width: 100% !important;
	margin: 0 0 16px !important;
	padding: 16px 20px !important;
	border: 1px solid #e7e9ee !important;
	border-radius: 12px !important;
	background: #fff !important;
	box-shadow: 0 4px 16px rgba(15,23,42,.045) !important;
	border-collapse: separate !important;
}
body.page-card_note .ts-member-note-details tr {
	display: grid;
	width: 100%;
	grid-template-columns: minmax(180px, 32%) minmax(0, 1fr);
	min-height: 44px;
	align-items: center;
	border-bottom: 1px solid #f0f2f5;
}
body.page-card_note .ts-member-note-details tbody { display: block; width: 100%; }
body.page-card_note .ts-member-note-details tr:last-child { border-bottom: 0; }
body.page-card_note .ts-member-note-details td {
	padding: 11px 0 !important;
	border: 0 !important;
	font-size: 13px !important;
}
body.page-card_note .ts-member-note-details td:first-child {
	color: #526581;
	font-weight: 650;
}
body.page-card_note .ts-member-note-details td.valeur {
	color: #24324a;
	font-weight: 500;
}
body.page-card_note .ts-member-note-card {
	display: block;
	width: 100% !important;
	margin: 0 0 16px !important;
	padding: 0 !important;
	border: 1px solid #e7e9ee !important;
	border-radius: 12px !important;
	background: #fff !important;
	box-shadow: 0 4px 16px rgba(15,23,42,.045) !important;
	border-collapse: separate !important;
	overflow: hidden;
}
body.page-card_note .ts-member-note-card tr {
	display: flex;
	width: 100%;
	align-items: center;
	justify-content: space-between;
	min-height: 54px;
	padding: 0 18px;
	border-bottom: 1px solid #edf0f4;
}
body.page-card_note .ts-member-note-card tbody { display: block; width: 100%; }
body.page-card_note .ts-member-note-card tr > td {
	padding: 0 !important;
	border: 0 !important;
	font-size: 15px !important;
	font-weight: 650;
	color: #24324a;
}
body.page-card_note .ts-member-note-card tr > td.right { flex: 0 0 auto; }
body.page-card_note .ts-member-note-card .editfielda {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 32px;
	height: 32px;
	border-radius: 7px;
	color: var(--c-accent) !important;
}
body.page-card_note .ts-member-note-card .editfielda:hover,
body.page-card_note .ts-member-note-card .editfielda:focus-visible { background: var(--c-accent-soft); }
body.page-card_note .ts-member-note-card .editfielda .fa-pencil-alt {
	float: none !important;
	color: currentColor !important;
}
body.page-card_note .ts-member-note-empty {
	display: flex;
	align-items: center;
	min-height: 104px;
	padding: 18px;
	color: #7b8ba3;
	font-size: 13px;
}
/* Secondary record tabs (members, documents, notes, events, etc.) share the
   same entity/header and tab surfaces as the primary record view. */
body.ts-command-record-secondary div.tabBar.ts-entity-card > div.arearef,
body.ts-command-record-secondary div.tabBar.ts-entity-card > div.arearefnobottom,
body.ts-command-record-secondary div.tabBar.ts-entity-card > div.arearefnoborder {
	margin: 0 0 var(--sp-4) !important;
	border: 1px solid var(--c-border) !important;
	border-radius: var(--r-lg) !important;
	background: var(--c-surface) !important;
	box-shadow: var(--sh-sm) !important;
}
body.ts-command-record-secondary div.tabBar.ts-entity-card > :first-child:not(.tabs) {
	background: var(--c-surface) !important;
	border: 1px solid var(--c-border) !important;
	border-radius: var(--r-lg) !important;
	box-shadow: var(--sh-sm) !important;
}
body.ts-command-record-secondary div.tabBar.ts-entity-card > form > .arearef,
body.ts-command-record-secondary div.tabBar.ts-entity-card > form > .arearefnobottom,
body.ts-command-record-secondary div.tabBar.ts-entity-card > form > .arearefnoborder {
	display: flex;
	align-items: center;
	gap: 16px;
	min-height: 104px;
	margin: 0 0 var(--sp-4) !important;
	padding: 20px 24px !important;
	box-sizing: border-box;
	background: var(--c-surface) !important;
	border: 1px solid var(--c-border) !important;
	border-radius: var(--r-lg) !important;
	box-shadow: var(--sh-sm) !important;
}
body.ts-command-record-secondary div.tabBar.ts-entity-card > div.tabs {
	margin: 0 0 var(--sp-4) !important;
	padding: 0 var(--sp-3);
	background: var(--c-surface);
	border: 1px solid var(--c-border);
	border-radius: var(--r-lg);
	box-shadow: var(--sh-sm);
}
body.ts-command-record-secondary div.tabBar.ts-entity-card .tabs {
	background: var(--c-surface);
	border: 1px solid var(--c-border);
	border-radius: var(--r-lg);
	box-shadow: var(--sh-sm);
}
body.ts-command-record-secondary div.tabBar.ts-entity-card > form > .tabs {
	display: block;
	margin: 0 0 var(--sp-4) !important;
	padding: 0 var(--sp-3);
	background: var(--c-surface);
	border: 1px solid var(--c-border);
	border-radius: var(--r-lg);
	box-shadow: var(--sh-sm);
}
body.page-card_note .ts-member-note-layout { display: grid; grid-template-columns: minmax(420px, 48%) minmax(0, 1fr); gap: 16px; width: 100%; align-items: start; }
body.page-card_note .ts-member-note-layout > .ts-member-note-details { width: 100% !important; margin: 0 !important; }
body.page-card_note .ts-member-note-grid { display: grid; grid-template-columns: 1fr; gap: 16px; width: 100%; }
body.page-card_note .ts-member-note-details td:first-child { width: 190px; white-space: nowrap; }
body.page-card_note .ts-member-note-grid .ts-member-note-card { width: 100% !important; margin: 0 !important; min-height: 132px; }
body.page-card_note .ts-member-note-grid .ts-member-note-card tr { min-height: 48px; padding: 0 20px; border-bottom: 1px solid #edf0f4; }
body.page-card_note .ts-member-note-grid .ts-member-note-card .ts-member-note-empty { min-height: 74px; padding: 18px 20px; }
body.page-card_note .ts-member-note-grid .ts-member-note-card .editfielda { width: 34px; height: 34px; border-radius: 8px; }
body.page-card_note .ts-member-note-grid .ts-member-note-card .editfielda .fa-pencil-alt { font-size: 13px !important; }
@media (max-width: 760px) {
	body.page-card_note .ts-member-note-layout { display: block; }
	body.page-card_note .ts-member-note-layout > .ts-member-note-details { width: 100% !important; margin-bottom: 16px !important; }
}

body.ts-member-documents-page .ts-member-files-details { display:block; width:100% !important; margin:0 0 16px !important; padding:12px 18px !important; border:1px solid #e7e9ee !important; border-radius:12px !important; background:#fff !important; box-shadow:0 4px 16px rgba(15,23,42,.045) !important; border-collapse:separate !important; }
body.ts-member-documents-page .ts-member-files-details tbody { display:block; width:100%; }
body.ts-member-documents-page .ts-member-files-details tr { display:grid; width:100%; grid-template-columns:minmax(220px,34%) minmax(0,1fr); align-items:center; min-height:42px; border-bottom:1px solid #f0f2f5; }
body.ts-member-documents-page .ts-member-files-details tr:last-child { border-bottom:0; }
body.ts-member-documents-page .ts-member-files-details td { padding:10px 0 !important; border:0 !important; font-size:13px !important; }
body.ts-member-documents-page table.ts-member-files-details > tbody > tr > td {
	border: 0 !important;
	border-width: 0 !important;
	border-style: none !important;
	box-shadow: none !important;
}
body.ts-member-documents-page .ts-member-files-details td:first-child { color:#526581; font-weight:650; }
body.ts-member-documents-page .ts-member-files-details td.valeur, body.ts-member-documents-page .ts-member-files-details td[colspan] { color:#24324a; }
body.ts-member-documents-page .ts-member-files-attached-head { display:flex !important; align-items:center; justify-content:space-between; margin:0 0 10px !important; padding:0 !important; min-height:48px; }
body.ts-member-documents-page .ts-member-files-attached-head .ts-pagehead-icon, body.ts-member-documents-page .ts-member-files-linked-head .pictotitle { display:inline-flex; align-items:center; justify-content:center; width:40px; height:40px; margin-right:10px; border-radius:10px; background:var(--c-accent-soft); color:var(--c-accent); }
body.ts-member-documents-page .ts-member-files-attached-head .titre, body.ts-member-documents-page .ts-member-files-linked-head .titre { font-size:20px !important; font-weight:700 !important; color:var(--c-ink) !important; }
body.ts-member-documents-page .ts-member-files-attached-title { display:none !important; }
body.ts-member-documents-page .ts-member-files-attached-head .ts-pagehead-actions { margin-left:auto; }
body.ts-member-documents-page .ts-member-files-upload { display:none; margin:0 0 12px; padding:14px 16px; border:1px dashed #c8bfff; border-radius:10px; background:#faf9ff; }
body.ts-member-documents-page .ts-member-files-upload:not(.hidden) { display:block; }
body.ts-member-documents-page .ts-member-files-upload form { margin:0; }
body.ts-member-documents-page .ts-member-files-upload table { width:100%; margin:0; }
body.ts-member-documents-page .ts-member-files-upload td { padding:0 !important; border:0 !important; }
body.ts-member-documents-page .ts-member-files-upload input[type=file] { width:min(100%,520px); height:40px; padding:5px 8px; border:1px solid #dbe2ec; border-radius:8px; background:#fff; color:#526581; font-size:13px; }
body.ts-member-documents-page .ts-member-files-upload input[type=file]::file-selector-button { margin-right:10px; padding:7px 12px; border:0; border-radius:6px; background:#eef0ff; color:var(--c-accent); font-weight:650; cursor:pointer; }
body.ts-member-documents-page .ts-member-files-upload input[type=submit] { margin-left:10px; }
body.ts-member-documents-page .ts-member-files-documents, body.ts-member-documents-page .ts-member-files-linked-form { display:block; margin:0 0 16px; padding:0 !important; border:1px solid #e7e9ee; border-radius:12px; background:#fff; box-shadow:0 4px 16px rgba(15,23,42,.045); overflow:hidden; }
body.ts-member-documents-page .ts-member-files-doc-table, body.ts-member-documents-page .ts-member-files-link-table { width:100%; margin:0 !important; border:0 !important; }
body.ts-member-documents-page .ts-member-files-doc-table th, body.ts-member-documents-page .ts-member-files-link-table th { height:44px; padding:0 14px !important; background:#f8fafc !important; color:#526581 !important; font-size:12px !important; font-weight:700 !important; border-bottom:1px solid #e7e9ee !important; }
body.ts-member-documents-page .ts-member-files-doc-table td, body.ts-member-documents-page .ts-member-files-link-table td { height:48px; padding:0 14px !important; border-bottom:1px solid #f0f2f5 !important; font-size:13px; }
body.ts-member-documents-page .ts-member-files-doc-table td[colspan], body.ts-member-documents-page .ts-member-files-link-table td[colspan] { height:112px; text-align:center !important; color:#8a9ab3 !important; }
body.ts-member-documents-page .ts-member-files-linked-head { display:block !important; margin:16px 0 10px !important; padding:12px 16px !important; border:1px solid #e7e9ee; border-radius:12px; background:#fff; box-shadow:0 4px 16px rgba(15,23,42,.045); }
body.ts-member-documents-page .ts-member-files-linked-head tbody { display:block; }
body.ts-member-documents-page .ts-member-files-linked-head tr { display:flex; align-items:center; width:100%; }
body.ts-member-documents-page .ts-member-files-linked-head td { border:0 !important; padding:0 !important; }
body.ts-member-documents-page .ts-member-files-linked-head .col-picto { flex:0 0 auto; }
body.ts-member-documents-page .ts-member-files-linked-head .col-title { flex:1 1 auto; }
body.ts-member-documents-page .ts-member-files-linked-head .col-right { margin-left:auto; }
body.ts-member-documents-page .ts-member-files-linked-head .col-right .btnTitle { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; }
body.mod-member table.ts-member-recent-events { margin-top:16px !important; }
body.mod-member .ts-member-recent-events-section { margin-top:16px !important; }
body.mod-member .ts-record-section-events { margin-top:16px !important; }
/* Keep the event Type heading/value readable in the compact member card. The
   native auto table algorithm was assigning that column ~30px, producing
   a clipped “T...”; reserve a real label slot and let Title absorb the rest. */
body.mod-member .ts-record-section-events table.listactions th:nth-child(4),
body.mod-member .ts-record-section-events table.listactions td:nth-child(4) {
	min-width:64px !important;
	white-space:nowrap;
}
/* Member detail values (notably the linked Type label) must not collapse into
   a clipped one-letter fragment when the legacy half-column table is placed in
   the COMMAND card. Keep the value/link as one readable inline unit. */
body.mod-member .fichecenter .fichehalfleft td.valeur,
body.mod-member .fichecenter .fichehalfright td.valeur {
	min-width: 0;
	white-space: nowrap;
	overflow: visible !important;
}
body.mod-member .fichecenter .fichehalfleft td.valeur a,
body.mod-member .fichecenter .fichehalfright td.valeur a {
	display: inline-flex;
	align-items: center;
	max-width: 100%;
	white-space: nowrap;
}

/* Translation administration is a data grid, not a centred presentation
   table. Align headers, values, and the filter inputs to the same columns so
   long translation keys remain visibly associated with their header. */
body.page-translation table.noborder.centpercent:has(th:nth-child(2)) {
	table-layout: fixed;
}
body.page-translation table.noborder.centpercent:has(th:nth-child(2)) tr > :nth-child(1) { width: 19% !important; }
body.page-translation table.noborder.centpercent:has(th:nth-child(2)) tr > :nth-child(2) { width: 39% !important; text-align: left !important; }
body.page-translation table.noborder.centpercent:has(th:nth-child(2)) tr > :nth-child(3) { width: 34% !important; text-align: left !important; }
body.page-translation table.noborder.centpercent:has(th:nth-child(2)) tr > :nth-child(4) { width: 8% !important; text-align: center !important; }
body.page-translation table.noborder.centpercent:has(th:nth-child(2)) tr.liste_titre > td {
	white-space: nowrap !important;
	overflow: hidden !important;
	text-overflow: ellipsis;
}
body.page-translation table.noborder.centpercent:has(th:nth-child(2)) tr:first-child > :nth-child(2),
body.page-translation table.noborder.centpercent:has(th:nth-child(2)) tr:first-child > :nth-child(3) {
	padding-left: var(--sp-3) !important;
}
body.page-translation table.noborder.centpercent:has(th:nth-child(2)) tr:first-child input[type="text"] {
	width: min(100%, 280px) !important;
	margin: 0 !important;
}
@media (max-width:700px) {
	body.mod-member .fichecenter .fichehalfleft td.valeur,
	body.mod-member .fichecenter .fichehalfright td.valeur,
	body.mod-member .fichecenter .fichehalfleft td.valeur a,
	body.mod-member .fichecenter .fichehalfright td.valeur a { white-space: normal; }
}
@media (max-width:700px) { body.ts-member-documents-page .ts-member-files-details tr { grid-template-columns:1fr; gap:0; padding:5px 0; } body.ts-member-documents-page .ts-member-files-upload input[type=file] { width:100%; margin-bottom:8px; } body.ts-member-documents-page .ts-member-files-upload input[type=submit] { margin-left:0; } }
body.page-card_note div.tabBar.ts-entity-card > form {
	margin: 0 !important;
	padding: 0 !important;
	background: transparent !important;
	border: 0 !important;
	box-shadow: none !important;
}
body.page-card_note form {
	background: transparent !important;
	border: 0 !important;
	box-shadow: none !important;
}
body.page-card_note div.tabBar.ts-entity-card > form > .arearef {
	min-height: 104px !important;
	height: 145px !important;
}
body.page-card_note div.tabBar.ts-entity-card > form > .fichecenter {
	margin: 0 !important;
	padding: 0 !important;
	background: transparent !important;
}

/* Shared User-record tab rhythm. User detail tabs expose the same Dolibarr
   heading and field-table structures as other records, so normalize the
   structure once instead of relying on each individual URL. */
body.mod-user .ts-user-record-content { display: flow-root; }
body.mod-user .ts-user-detail-card { display:block; width:100% !important; margin:0 0 16px !important; padding:0 !important; border:1px solid var(--c-border) !important; border-radius:var(--r-lg) !important; background:var(--c-surface) !important; box-shadow:var(--sh-sm) !important; overflow:hidden; border-collapse:separate !important; }
body.mod-user .ts-user-detail-card tbody { display:block; width:100%; }
body.mod-user .ts-user-detail-card tr { display:grid; grid-template-columns:minmax(210px,34%) minmax(0,1fr); align-items:center; min-height:46px; border-bottom:1px solid var(--c-border-subtle); }
body.mod-user .ts-user-detail-card tr:last-child { border-bottom:0; }
body.mod-user .ts-user-detail-card td { width:auto !important; min-width:0 !important; max-width:none !important; padding:11px 18px !important; border:0 !important; line-height:1.35; overflow:visible !important; white-space:normal !important; }
body.mod-user .ts-user-detail-card td:first-child { font-weight:650; color:var(--c-ink); }
body.mod-user .ts-user-detail-card td.valeur { color:var(--c-text); }
body.mod-user .ts-user-section-heading { display:flex !important; align-items:center; gap:10px; min-height:48px; margin:24px 0 12px !important; padding:0 !important; }
body.mod-user .ts-user-section-heading .ts-pagehead-actions { margin-left:auto; }
body.mod-user .ts-user-section-surface, body.mod-user .ts-user-section-heading + form, body.mod-user .ts-user-section-heading + .div-table-responsive, body.mod-user .ts-user-section-heading + .div-table-responsive-no-min { margin:0 0 20px !important; }
/* A page heading begins a new section. It must not fuse with the preceding
   detail-card, and side-by-side record cards must retain intrinsic height. */
body.mod-user .fichecenter:has(> .ts-pagehead) { margin-top:24px !important; }
body.mod-user .fichehalfleft, body.mod-user .fichehalfright { align-self:start !important; }
body.mod-user .ts-record-section-events { margin-top:20px !important; }
/* Event header utilities are true icon actions, not loose adjacent glyphs. */
body.mod-user .ts-record-section-events .btnTitle, body.mod-user .ts-record-section-events a[class*="butAction"]:has(> [class*="fa-"]) { display:inline-flex !important; align-items:center !important; justify-content:center !important; width:34px !important; height:34px !important; min-width:34px !important; padding:0 !important; margin-left:8px !important; line-height:1 !important; border-radius:8px !important; }
body.mod-user .ts-record-section-events .btnTitle > [class*="fa-"], body.mod-user .ts-record-section-events a[class*="butAction"]:has(> [class*="fa-"]) > [class*="fa-"] { margin:0 !important; line-height:1 !important; }
/* Agenda tables need space for owner/type/label, rather than letting Ref/Date
   absorb the row. The filter row is a stable COMMAND toolbar. */
body.mod-user .ts-user-agenda-filter { display:flex !important; flex-wrap:wrap; align-items:end; gap:10px !important; margin:0 0 14px !important; padding:14px 16px !important; border:1px solid var(--c-border) !important; border-radius:var(--r-lg) !important; background:var(--c-surface) !important; box-shadow:var(--sh-sm) !important; }
body.mod-user .ts-user-agenda-filter > * { margin:0 !important; }
body.mod-user .ts-user-agenda-table { table-layout:fixed; }
body.mod-user .ts-user-agenda-table th:nth-child(1), body.mod-user .ts-user-agenda-table td:nth-child(1) { width:8% !important; }
body.mod-user .ts-user-agenda-table th:nth-child(2), body.mod-user .ts-user-agenda-table td:nth-child(2) { width:13% !important; }
body.mod-user .ts-user-agenda-table th:nth-child(3), body.mod-user .ts-user-agenda-table td:nth-child(3) { width:17% !important; }
body.mod-user .ts-user-agenda-table th:nth-child(4), body.mod-user .ts-user-agenda-table td:nth-child(4) { width:13% !important; }
body.mod-user .ts-user-agenda-table th:nth-child(5), body.mod-user .ts-user-agenda-table td:nth-child(5) { width:auto !important; }
body.mod-user .ts-user-agenda-table th > img, body.mod-user .ts-user-agenda-table td > img, body.page-card_perms .div-table-responsive-no-min img.pictofixedwidth { max-width:20px !important; max-height:20px !important; width:auto !important; height:auto !important; object-fit:contain; vertical-align:middle; }
/* Inline note editors occur in Member, Contact and User tabs. Constrain the
   real form to a calm card without changing its submit/token behaviour. */
body.page-card_note form:has(textarea[name*="note"]), body.page-contact-card_note form:has(textarea[name*="note"]), body.page-user-note form:has(textarea[name*="note"]) { max-width:900px; margin:16px 0 !important; padding:20px !important; border:1px solid var(--c-border) !important; border-radius:var(--r-lg) !important; background:var(--c-surface) !important; box-shadow:var(--sh-sm) !important; }
body.page-card_note form textarea[name*="note"], body.page-contact-card_note form textarea[name*="note"], body.page-user-note form textarea[name*="note"] { width:100% !important; min-height:150px !important; box-sizing:border-box; }
body.mod-user textarea#note_public, body.mod-user textarea#note_private { width:100% !important; height:150px !important; min-height:150px !important; max-height:220px; box-sizing:border-box !important; resize:vertical; }
@media (max-width:900px) { body.mod-user .ts-user-detail-card tr { grid-template-columns:minmax(160px,36%) minmax(0,1fr); } }
@media (max-width:700px) { body.mod-user .ts-user-detail-card tr { grid-template-columns:1fr; padding:5px 0; } body.mod-user .ts-user-detail-card td { padding:6px 14px !important; } body.mod-user .ts-user-agenda-filter { align-items:stretch; } body.mod-user .ts-user-agenda-filter > * { width:100%; } }

/* COMMAND action colour contract.

   Native setup pages do not all receive the settings-page composer (Display
   deliberately has its own layout), but their Save control still carries the
   same semantic Dolibarr classes.  Resolve every real Save action through the
   configured action-button tokens instead of letting an inherited native
   button rule choose a separate colour.  Cancel remains a neutral escape
   route. */
body:not(.bodylogin) input.button-save,
body:not(.bodylogin) button.button-save,
body:not(.bodylogin) input.buttonforacesave,
body:not(.bodylogin) button.buttonforacesave,
body:not(.bodylogin) input[type="submit"][name="submit"]:not(.button-cancel),
body:not(.bodylogin) button[type="submit"][name="submit"]:not(.button-cancel) {
	border-color: var(--c-btn-action) !important;
	background: var(--c-btn-action) !important;
	color: var(--c-btn-action-text) !important;
	box-shadow: 0 1px 2px var(--c-btn-action-ring) !important;
}
body:not(.bodylogin) input.button-save:hover,
body:not(.bodylogin) button.button-save:hover,
body:not(.bodylogin) input.buttonforacesave:hover,
body:not(.bodylogin) button.buttonforacesave:hover,
body:not(.bodylogin) input[type="submit"][name="submit"]:not(.button-cancel):hover,
body:not(.bodylogin) button[type="submit"][name="submit"]:not(.button-cancel):hover {
	filter: brightness(.94);
}
body:not(.bodylogin) input.button-cancel,
body:not(.bodylogin) button.button-cancel,
body:not(.bodylogin) input[name="cancel"],
body:not(.bodylogin) button[name="cancel"] {
	border-color: var(--c-border) !important;
	background: var(--c-surface) !important;
	color: var(--c-ink-2) !important;
	box-shadow: none !important;
}

/* A Select2 container owns the trigger width.  Native widthNN classes are
   duplicated onto the inner selection by Dolibarr; if that inner width wins,
   the visible border ends before its arrow (as on Menu handler).  Make the
   selection fill its real container so text, border and chevron remain one
   control everywhere. */
body .select2-container > .selection,
body .select2-container > .selection > .select2-selection {
	display: block;
	width: 100% !important;
	box-sizing: border-box;
}
body .select2-container--default .select2-selection--single .select2-selection__arrow {
	display: flex;
	align-items: center;
	justify-content: center;
	top: 0 !important;
	right: 0 !important;
	height: 100% !important;
	line-height: 1;
}
body .select2-container--default .select2-selection--single .select2-selection__arrow b {
	position: static !important;
	margin: 0 !important;
}

/* Dolibarr's AJAX on/off controls render both action halves and rely on this
   semantic class to keep the inactive half out of the flow.  A legacy display
   override was leaking it back into COMMAND pages, leaving two toggle icons
   fused together.  Honour the framework contract globally. */
body .hideobject { display: none !important; }

/* Shared search/reset icon actions.  Their glyph inherits different native
   line-heights across admin tables; normalize the inner glyph rather than
   letting Search and Clear sit high or low in otherwise identical controls. */
body button.button_search,
body button.button_removefilter,
body input.button_search,
body input.button_removefilter {
	display: inline-flex !important;
	align-items: center !important;
	justify-content: center !important;
	line-height: 1 !important;
}
body button.button_search > [class*="fa-"],
body button.button_removefilter > [class*="fa-"],
body input.button_search > [class*="fa-"],
body input.button_removefilter > [class*="fa-"] {
	margin: 0 !important;
	line-height: 1 !important;
}

/* Empty mandatory cells are layout placeholders in a number of native forms,
   not validation messages.  Rendering them after a file picker produces bare
   vertical asterisks.  Remove only rows with no actual form control/content. */
body form tr:has(> td.fieldrequired:empty):not(:has(input, select, textarea, button, a, img, .select2-container)) {
	display: none !important;
}

/* Email test compose uses the same settings form primitive as the rest of
   admin, but its attach/send/cancel actions are compact inline utilities.
   Keep their hierarchy while avoiding oversized controls in that row. */
body.page-mails form#mailform input.button.smallpaddingimp,
body.page-mails form#mailform .ts-settings-actions .ts-settings-action {
	min-height: 38px !important;
	height: 38px !important;
	min-width: auto;
	padding-inline: 15px !important;
	font-size: 0.8125rem !important;
}
body.page-mails form#mailform input.button.smallpaddingimp {
	border-color: var(--c-btn-action) !important;
	background: var(--c-btn-action) !important;
	color: var(--c-btn-action-text) !important;
}

/* System tools submit real operations, so they follow the configured action
   colour instead of the incidental native .button palette. */
body.page-system_filecheck input[name="check"],
body.page-tools_dolibarr_export input#buttonGo,
body.page-system_dolibarr a.butAction.smallpaddingimp {
	border-color: var(--c-btn-action) !important;
	background: var(--c-btn-action) !important;
	color: var(--c-btn-action-text) !important;
	box-shadow: 0 1px 2px var(--c-btn-action-ring) !important;
}

/* Performance reports are made from repeated heading + divsection pairs.
   Present those native sections as a readable COMMAND report without changing
   any diagnostic content or links. */
body.page-system_perf .fiche > br { display: none; }
body.page-system_perf .fiche > strong {
	display: inline-flex;
	align-items: center;
	margin: 22px 0 8px;
	font-size: 0.875rem;
	font-weight: 650;
	color: var(--c-ink);
}
body.page-system_perf .fiche > span.fas.fa-folder {
	margin: 22px 5px 8px 0;
	color: var(--c-accent);
}
/* The first native heading follows the Reload link directly.  Make that link
   its own line so the Version section begins as a proper section heading,
   consistent with XDebug and the following diagnostic sections. */
body.page-system_perf .fiche > a[href*="/perf.php"] {
	display: block;
	width: fit-content;
	margin: 14px 0 0;
}
body.page-system_perf .fiche > span.fas.fa-folder + strong { margin-left: 5px; }
body.page-system_perf .fiche > .divsection {
	margin: 0 0 18px !important;
	padding: 16px 20px !important;
	border: 1px solid var(--c-border) !important;
	border-radius: var(--r-lg) !important;
	background: var(--c-surface) !important;
	box-shadow: var(--sh-sm);
	line-height: 1.55;
	color: var(--c-ink-2);
}
body.page-system_about .divsection,
body.page-system_about td {
	white-space: normal;
	overflow-wrap: anywhere;
}

/* Native Update/Purge tools place prose, controls, and forms as sibling text
   nodes.  The enhancer groups those nodes, letting the existing settings card
   language give operational instructions a deliberate, readable surface. */
body.page-tools_update .ts-admin-tools-content,
body.page-tools_purge .ts-admin-tools-content {
	max-width: 980px;
	margin: 18px 0 28px;
	padding: 22px 24px;
	border: 1px solid var(--c-border);
	border-radius: var(--r-lg);
	background: var(--c-surface);
	box-shadow: var(--sh-sm);
	line-height: 1.6;
	color: var(--c-ink-2);
}
body.page-tools_update .ts-admin-tools-content hr {
	margin: 14px 0;
	border: 0;
	border-top: 1px solid var(--c-border);
}
body.page-tools_update .ts-admin-tools-content > a.button,
body.page-tools_purge .ts-admin-tools-content input.button {
	min-height: 38px;
	padding-inline: 16px;
	vertical-align: middle;
}
body.page-tools_purge .ts-admin-tools-content .divsection {
	margin: 16px 0 18px !important;
	padding: 16px 18px !important;
	border: 1px solid var(--c-border) !important;
	border-radius: var(--r-md) !important;
	background: color-mix(in srgb, var(--c-canvas) 62%, var(--c-surface)) !important;
}
body.page-tools_purge .ts-admin-tools-content label {
	line-height: 1.55;
}

/* Dashboard widget identities pair a Font Awesome star and a user name.  Keep
   the glyph in the same inline-flex baseline slot as every other compact icon. */
body.ts-command-module-index .ts-module-index-card .fa-star,
body.ts-command-module-index .ts-module-index-card .fa-star-o {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 1em;
	height: 1em;
	margin: 0 .28em 0 0;
	vertical-align: -0.08em;
	line-height: 1;
}
@media (max-width: 700px) {
	body.page-card_note .ts-member-note-details tr { grid-template-columns: 1fr; gap: 2px; padding: 8px 0; }
	body.page-card_note .ts-member-note-details td { padding: 5px 0 !important; }
	body.page-card_note div.tabBar.ts-entity-card > form > .arearef { height: auto !important; }
}
