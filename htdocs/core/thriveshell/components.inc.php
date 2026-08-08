<?php
/* Copyright (C) 2026  Thrive / Accellier
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 */

/**
 *	\file       htdocs/core/thriveshell/components.inc.php
 *	\brief      Component CSS shared by every Thrive shell.
 *
 *	Everything here is chrome-agnostic -- page furniture, tables, forms,
 *	buttons, cards, badges, dialogs, login -- and is driven entirely by the
 *	CSS custom properties each theme defines. Shell chrome lives in the
 *	individual theme stylesheets.
 */

if (!defined('ISLOADEDBYSTEELSHEET')) {
	die('Must be loaded by a stylesheet');
}
/**
 * @var string $left
 * @var string $right
 * @var string $fontsize
 * @var string $fontsizesmaller
 * @var string $badgeStatus0
 * @var string $badgeStatus1
 * @var string $badgeStatus2
 * @var string $badgeStatus3
 * @var string $badgeStatus4
 * @var string $badgeStatus5
 * @var string $badgeStatus6
 * @var string $badgeStatus7
 * @var string $badgeStatus8
 * @var string $badgeStatus9
 * @var string $badgeStatus10
 * @var string $badgeStatus11
 * @var int $dol_no_mouse_hover
 */
?>
/* ==========================================================================
   Page
   ========================================================================== */

.fiche {
	width: 100%;
	max-width: 1560px;
	margin: 0 auto;
	padding: var(--sp-6) var(--sp-7) var(--sp-8) var(--sp-7);
	min-width: 0;
}
.fichecenter { clear: both; width: 100%; }
.fichecenter::after { content: ""; display: table; clear: both; }
.fichehalfleft, .fichethirdleft { float: <?php echo $left; ?>; width: calc(50% - var(--sp-3)); }
.fichehalfright, .fichetwothirdright { float: <?php echo $right; ?>; width: calc(50% - var(--sp-3)); }

.titre, div.titre {
	font-size: 1.75rem;
	font-weight: 640;
	letter-spacing: -0.028em;
	line-height: 1.12;
	color: var(--c-ink);
}
.titre .opacitymedium {
	font-size: 0.6em; font-weight: 450; opacity: 1;
	color: var(--c-muted); margin-<?php echo $left; ?>: var(--sp-2);
}
.table-fiche-title { width: 100%; margin-bottom: var(--sp-5); border: 0; border-spacing: 0; }
.table-fiche-title td { padding: 0; border: 0; vertical-align: middle; }
.table-fiche-title td.col-picto { width: 34px; }
.widthpictotitle { width: 26px; }
.pictotitle { margin-<?php echo $right; ?>: var(--sp-2); color: var(--c-accent) !important; opacity: 0.9; }
/* .print-barre-liste is a span inside the list title (it marks what survives
   printing), not a toolbar -- it must not become a flex container. */
.print-barre-liste { display: inline-block; }


/* ==========================================================================
   Tabs and object banner
   ========================================================================== */

div.tabs { display: flex; flex-wrap: wrap; gap: 2px; border-bottom: 1px solid var(--c-border); }
div.tabsElem { display: inline-block; }
a.tab, a.tabactive, a.tabunactive {
	display: inline-block; position: relative;
	padding: var(--sp-2) var(--sp-4);
	border: 0; border-radius: var(--r) var(--r) 0 0;
	color: var(--c-muted); font-weight: 500; white-space: nowrap;
	transition: background var(--t), color var(--t);
}
a.tab:hover, a.tabunactive:hover { background: var(--c-sunken); color: var(--c-ink); }
/* Dolibarr emits <div class="tab tabactive"><a class="tab">, so the active
   state lives on the wrapper. Styling only a.tabactive matched nothing and
   the current tab had no indicator at all. */
a.tabactive,
div.tab.tabactive > a.tab,
.tabsElemActive a.tab { color: var(--c-accent-ink); font-weight: 620; }

div.tabs div.tab { position: relative; display: inline-block; }
a.tabactive::after,
div.tab.tabactive::after,
.tabsElemActive::after {
	content: ""; position: absolute; left: var(--sp-3); right: var(--sp-3);
	bottom: -1px; height: 2px; background: var(--c-accent); border-radius: 2px 2px 0 0;
}
.tabsElem { position: relative; }
div.tab.tabunactive > a.tab { color: var(--c-muted); }
div.tab:hover > a.tab { color: var(--c-ink); }
.tabs .badge { margin-<?php echo $left; ?>: var(--sp-1); background: var(--c-sunken); color: var(--c-muted); }
a.tabactive .badge { background: var(--c-accent-soft); color: var(--c-accent-ink); }

div.tabBar {
	background: var(--c-surface);
	border: 1px solid var(--c-hairline); border-top: 0;
	border-radius: 0 0 var(--r-lg) var(--r-lg);
	padding: var(--sp-6);
	box-shadow: var(--sh);
}
div.tabBarNoTop, div.tabBarWithBottom { border-top: 1px solid var(--c-hairline); border-radius: var(--r-lg); }

div.arearef { padding-bottom: var(--sp-5); margin-bottom: var(--sp-5); border-bottom: 1px solid var(--c-hairline); }
.refid { font-size: 1.5rem; font-weight: 640; letter-spacing: -0.026em; color: var(--c-ink); }
/* rem, not em: .refidno sits inside the 24px ref block, so an em value
   compounded to ~22px and the address, phone and email lines rendered almost
   as large as the record title. */
.refidno { font-size: 0.9375rem; font-weight: 400; color: var(--refidnocolor); }
/* Everything else in the banner is body text, not heading text. */
div.arearef .address,
div.arearef .refidno,
div.arearef .paddingright,
div.arearef .paddingrightonly,
div.arearef .nospan,
div.arearef a:not(.refid) { font-size: 0.9375rem; line-height: 1.55; }
div.statusref { float: <?php echo $right; ?>; text-align: <?php echo $right; ?>; padding-<?php echo $left; ?>: var(--sp-4); }
div.divphotoref, .photoref { margin-<?php echo $right; ?>: var(--sp-4); }
div.divphotoref img, .photoref img { border-radius: var(--r); border: 1px solid var(--c-border); }


/* ==========================================================================
   Tables and lists
   ========================================================================== */

table { border-collapse: collapse; border-spacing: 0; }

div.div-table-responsive, div.div-table-responsive-no-min {
	max-width: 100%;
	overflow-x: auto;
	background: var(--c-surface);
	border: 1px solid var(--c-hairline);
	border-radius: var(--r-lg);
	box-shadow: var(--sh);
}
table.liste, table.noborder, table.border, table.centpercent { width: 100%; background: transparent; }

tr.liste_titre, .liste_titre { background: var(--colorbacktitle1); }
tr.liste_titre th, tr.liste_titre td, th.liste_titre, td.liste_titre {
	padding: var(--sp-3) var(--sp-4);
	text-align: <?php echo $left; ?>;
	font-size: <?php echo $fontsizesmaller; ?>;
	font-weight: 550;
	color: var(--c-muted);
	white-space: nowrap;
	border-bottom: 1px solid var(--c-border);
	vertical-align: middle;
}
tr.liste_titre th a, .liste_titre a { color: var(--c-muted); }
tr.liste_titre th a:hover, .liste_titre a:hover { color: var(--c-accent-ink); }

tr.liste_titre_filter, tr.liste_titre_filter td, tr.liste_titre_filter th {
	background: var(--c-surface);
	border-bottom: 1px solid var(--c-border);
	padding: var(--sp-2);
}

tr.oddeven, tr.impair, tr.pair {
	background: var(--c-surface);
	border-bottom: 1px solid var(--c-hairline);
	transition: background var(--t);
}
tr.oddeven td, tr.impair td, tr.pair td, table.liste td, table.noborder td {
	padding: var(--sp-3) var(--sp-4);
	height: 44px;
	color: var(--oddevencolor);
	vertical-align: middle;
	border: 0;
}
tr.oddeven:last-child, tr.impair:last-child, tr.pair:last-child { border-bottom: 0; }
<?php if (empty($dol_no_mouse_hover)) { ?>
tr.oddeven:hover, tr.impair:hover, tr.pair:hover { background: var(--colorbacklinepairhover); }
tr.nohover:hover, .nohover:hover { background: var(--c-surface) !important; }
<?php } ?>
tr.highlight, tr.selected { background: var(--colorbacklinepairchecked) !important; }

tr.liste_total td, .liste_total {
	background: var(--c-sunken); font-weight: 620;
	color: var(--listetotal); border-top: 2px solid var(--c-border);
}
tr.trforbreak td { background: var(--colorbacklinebreak); font-weight: 600; }

td.right, th.right, td.amount, th.amount { text-align: <?php echo $right; ?>; }
td.amount, .amount { font-weight: 500; }

/* Sticky list header on tall lists */
div.div-table-responsive table.liste tr.liste_titre th {
	position: sticky; top: 0; z-index: 3; background: var(--colorbacktitle1);
}

/* Key/value field tables on cards */
table.border td, table.tableforfield td, table.border th {
	padding: var(--sp-2) var(--sp-3);
	vertical-align: top;
}
/* The separator belongs to the row, not the cell: Dolibarr emits a varying
   number of <td> per row (2 on single-field rows, 4 on paired ones), so a
   border on the cell stopped mid-table and the rules looked ragged. */
table.border > tbody > tr,
table.tableforfield > tbody > tr {
	border-bottom: 1px solid var(--c-hairline);
}
table.border > tbody > tr:last-child,
table.tableforfield > tbody > tr:last-child { border-bottom: 0; }
table.border > tbody > tr > td,
table.tableforfield > tbody > tr > td { border-bottom: 0; }
/* A percentage label column looks fine in a half-width card (234px) but opens
   a 475px dead gap on a full-width one. A fixed width keeps the label/value
   relationship identical on every tab and every card. */
td.titlefield, td.titlefieldcreate, .tableforfield td:first-child, table.border td.fieldtitle {
	width: 320px;
	/* Was --tableforfieldcolor (muted). Beside a tall control such as the
	   rich-text editor a muted label all but disappeared, so labels now carry
	   body-text weight and colour. */
	color: var(--c-ink-2);
	font-weight: 550;
	padding-<?php echo $right; ?>: var(--sp-4);
	padding-top: var(--sp-3);
}
.titlefieldcreate { width: 320px; }

/* A settings/edit table stretched to the full card width leaves a toggle
   floating a thousand pixels from its label. Capping the measure keeps the
   pair readable as one line of text; list tables are untouched and still use
   the whole width. */
/* No width cap: capping the table made long setting labels wrap inside a
   narrow column while the right half of the card sat empty. Full width with a
   proportional label column keeps labels on one line instead. */
div.tabBar table.border:not(.liste),
div.tabBar table.tableforfield:not(.liste),
table.editmode:not(.liste) {
	max-width: none;
}
/* Full-width settings tables cap the label column instead of scaling it. A
   proportional width looked reasonable on a narrow card and absurd on a wide
   one: at 42% of a 1194px table, "Zip" and "Town" were given a 501px column
   and the form read as two distant halves with a river of empty space down
   the middle. Labels here are short by nature, so a ceiling serves them
   better than a share of whatever width the container happens to have. */
div.tabBar > table.border:not(.liste) > tbody > tr > td.titlefield,
div.tabBar > table.tableforfield:not(.liste) > tbody > tr > td:first-child,
table.editmode:not(.liste) > tbody > tr > td:first-child {
	width: 320px;
	max-width: 34%;
}
/* Two-column cards are already narrow -- no cap needed there. */
.fichehalfleft table, .fichehalfright table { max-width: none; }
/* Narrow containers fall back to proportional so the label never crowds out
   the field beside it. */
@media only screen and (max-width: 1100px) {
	td.titlefield, td.titlefieldcreate, .tableforfield td:first-child { width: 34%; }
}
table.nobordernopadding td, td.nobordernopadding { border: 0; padding: 0; }

div.divsearchfield { display: inline-flex; align-items: center; gap: var(--sp-2); margin-bottom: var(--sp-3); }
.selectedfields, .selectedfieldsleft { color: var(--c-muted); }

/* Pager: Dolibarr emits div.pagination > ul > li, so the list chrome must be
   reset or the controls render as a bulleted column. */
div.pagination, .paginationref { font-size: <?php echo $fontsizesmaller; ?>; color: var(--c-muted); }
div.pagination ul, .paginationref ul {
	display: flex; align-items: center; justify-content: flex-end;
	gap: var(--sp-1); margin: 0; padding: 0; list-style: none;
}
div.pagination li, .paginationref li { display: inline-flex; align-items: center; white-space: nowrap; }
div.pagination li a {
	display: inline-flex; align-items: center; justify-content: center;
	min-width: 28px; height: 28px; padding: 0 var(--sp-2);
	border-radius: var(--r); color: var(--c-muted); transition: background var(--t);
}
div.pagination li a:hover { background: var(--c-sunken); color: var(--c-accent-ink); }
div.pagination li.pagination-active a { background: var(--c-accent-soft); color: var(--c-accent-ink); font-weight: 620; }
div.pagination select.selectlimit { height: 28px; padding-top: 0; padding-bottom: 0; }
/* The current-page field is a text input and will otherwise take the row's
   free width. */
div.pagination input, div.pagination input[type="text"], .paginationref input {
	width: 44px; height: 28px; padding: 0 var(--sp-1); text-align: center;
}


/* ==========================================================================
   Forms and buttons
   ========================================================================== */

/* Dolibarr emits most of its fields as bare <input name="..."> with no type
   attribute, and input[type="text"] cannot match an absent attribute -- those
   fields kept the browser's 2px inset default while the focus ring (from a
   bare input:focus rule) still applied, so they only looked styled on click.
   Matching by exclusion covers typed and untyped fields alike. */
input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]):not([type="reset"]):not([type="file"]):not([type="image"]):not([type="range"]):not([type="color"]):not([type="hidden"]),
textarea, select, .flat {
	font-family: inherit;
	font-size: <?php echo $fontsize; ?>;
	line-height: 1.4;
	color: var(--c-ink);
	background: var(--inputbackgroundcolor);
	border: 1px solid var(--inputbordercolor);
	border-radius: var(--r);
	padding: 6px var(--sp-2);
	max-width: 100%;
	transition: border-color var(--t), box-shadow var(--t);
}
textarea { padding: var(--sp-2); }
/* Hover and focus must repeat the base rule's full :not() list. The base
   carries ten of them -- specificity (0,10,1) -- so a five-clause :focus
   selector scores (0,6,1) and LOSES to it. The ring still drew, because
   nothing else set box-shadow, but the border never changed colour: a focused
   field looked identical to a resting one except for a faint halo. Matching
   the clause count puts focus back in charge of its own border. */
input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]):not([type="reset"]):not([type="file"]):not([type="image"]):not([type="range"]):not([type="color"]):not([type="hidden"]):hover:not([disabled]),
select:hover:not([disabled]), textarea:hover:not([disabled]) { border-color: var(--c-border-strong); }
input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]):not([type="reset"]):not([type="file"]):not([type="image"]):not([type="range"]):not([type="color"]):not([type="hidden"]):focus,
select:focus, textarea:focus, .flat:focus {
	outline: none; border-color: var(--c-accent); box-shadow: 0 0 0 3px var(--c-accent-ring);
}
input[disabled]:not([type="checkbox"]):not([type="radio"]), select[disabled], textarea[disabled],
input[readonly]:not([type="checkbox"]):not([type="radio"]) {
	background: var(--inputbackgroundcolordisabled); color: var(--inputcolordisabled); cursor: not-allowed;
}

/* File pickers keep a native control inside, so they get the frame only. */
input[type="file"] {
	font-family: inherit;
	font-size: <?php echo $fontsizesmaller; ?>;
	color: var(--c-ink-2);
	background: var(--inputbackgroundcolor);
	border: 1px solid var(--inputbordercolor);
	border-radius: var(--r);
	padding: var(--sp-2);
	max-width: 100%;
}
input[type="file"]::file-selector-button {
	margin-<?php echo $right; ?>: var(--sp-3);
	padding: 5px var(--sp-3);
	background: var(--c-sunken);
	border: 1px solid var(--c-border);
	border-radius: var(--r-sm);
	color: var(--c-ink-2);
	font-family: inherit;
	font-size: <?php echo $fontsizesmaller; ?>;
	cursor: pointer;
}
input[type="file"]::file-selector-button:hover { background: var(--c-surface); border-color: var(--c-border-strong); }
select, select.flat {
	padding-<?php echo $right; ?>: var(--sp-5);
	background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='none' stroke='%235B6B82' stroke-width='1.6' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M4 6.5 8 10.5 12 6.5'/%3E%3C/svg%3E");
	background-repeat: no-repeat;
	background-position: <?php echo $right; ?> var(--sp-2) center;
	background-size: 14px;
	appearance: none; -webkit-appearance: none;
}
input[type="checkbox"], input[type="radio"] { accent-color: var(--c-accent); width: 15px; height: 15px; cursor: pointer; }
fieldset { border: 1px solid var(--c-border); border-radius: var(--r); padding: var(--sp-4); margin-bottom: var(--sp-4); }
legend { padding: 0 var(--sp-2); font-weight: 600; color: var(--c-muted); font-size: <?php echo $fontsizesmaller; ?>; }
.button_search, .button_removefilter { background: transparent; border: 0; color: var(--c-muted); cursor: pointer; padding: var(--sp-1); }
.button_search:hover { color: var(--c-accent); }
.button_removefilter:hover { color: var(--c-danger); }

.butAction, .butActionDelete, .butActionRefused, a.butAction, a.butActionDelete,
a.butActionRefused, input.butAction, input.button, button.button, a.button,
button.buttongen, .buttongen {
	display: inline-flex; align-items: center; justify-content: center; gap: var(--sp-2);
	height: 34px; padding: 0 var(--sp-4); margin: 0 0 0 var(--sp-2);
	border: 1px solid transparent; border-radius: var(--r);
	font-family: inherit; font-size: <?php echo $fontsize; ?>; font-weight: 550;
	line-height: 1; white-space: nowrap; cursor: pointer;
	transition: background var(--t), border-color var(--t), box-shadow var(--t);
}
.butAction, a.butAction, input.butAction { background: var(--c-accent); color: #fff; box-shadow: var(--sh-sm); }
.butAction:hover, a.butAction:hover { background: var(--c-accent-hover); color: #fff; }
input.button, .buttongen, button.button, a.button { background: var(--c-surface); color: var(--c-ink); border-color: var(--c-border); }
input.button:hover, .buttongen:hover, a.button:hover { background: var(--c-sunken); border-color: var(--c-border-strong); }
.butActionDelete, a.butActionDelete { background: var(--c-surface); color: var(--c-danger); border-color: var(--c-border); }
.butActionDelete:hover, a.butActionDelete:hover { background: var(--c-danger); border-color: var(--c-danger); color: #fff; }
.butActionRefused, a.butActionRefused {
	background: var(--c-sunken); color: var(--c-faint); border-color: var(--c-border);
	cursor: not-allowed; box-shadow: none;
}
div.tabsAction {
	display: flex; flex-wrap: wrap; justify-content: flex-end; gap: var(--sp-2);
	margin: var(--sp-4) 0 var(--sp-3) 0; border: 0;
}
div.tabsAction .butAction, div.tabsAction .butActionDelete, div.tabsAction .butActionRefused { margin: 0; }


/* ==========================================================================
   Cards, boxes, info-boxes
   ========================================================================== */

.box, div.box, div.divboxtable { margin-bottom: var(--sp-4); }
table.boxtable, div.boxtable {
	width: 100%; background: var(--c-surface);
	border: 1px solid var(--c-hairline); border-radius: var(--r-lg);
	box-shadow: var(--sh); overflow: hidden;
}
tr.box_titre td, .box_titre td {
	padding: var(--sp-4) var(--sp-5);
	font-size: 0.9375rem; font-weight: 620; color: var(--c-ink); letter-spacing: -0.012em;
	border-bottom: 1px solid var(--c-hairline);
}
/* Dolibarr's "no records" rows are the most-seen state on a fresh install and
   should read as calm, not as missing content. */
table.boxtable td.center, table.boxtable tr.oddeven td[colspan] {
	color: var(--c-muted);
}
tr.box_impair td, tr.box_pair td { padding: var(--sp-3) var(--sp-5); border-bottom: 1px solid var(--c-hairline); }
table.boxtable tr:last-child td { border-bottom: 0; }
.boxclose, .boxhandle, .boxfilter { color: var(--c-faint); cursor: pointer; opacity: 0; transition: color var(--t), opacity var(--t); }
table.boxtable:hover .boxclose, table.boxtable:hover .boxhandle, table.boxtable:hover .boxfilter { opacity: 1; }
.boxclose:hover { color: var(--c-danger); }
.boxhandle:hover, .boxfilter:hover { color: var(--c-accent); }
.boxhalfleft { float: <?php echo $left; ?>; width: calc(50% - var(--sp-3)); }
.boxhalfright { float: <?php echo $right; ?>; width: calc(50% - var(--sp-3)); }

.box-flex-container { display: flex; flex-wrap: wrap; gap: var(--sp-4); margin-bottom: var(--sp-5); }
.box-flex-item { flex: 1 1 300px; min-width: 280px; }

div.info-box {
	display: flex; align-items: center;
	background: var(--c-surface);
	border: 1px solid var(--c-hairline);
	border-radius: var(--r-lg);
	box-shadow: var(--sh);
	overflow: hidden;
	transition: box-shadow var(--t), border-color var(--t), transform var(--t);
}
div.info-box:hover { border-color: var(--c-border); box-shadow: var(--sh-md); transform: translateY(-1px); }
span.info-box-icon {
	display: flex; align-items: center; justify-content: center;
	width: 40px; height: 40px; flex: 0 0 40px;
	margin: var(--sp-4) 0 var(--sp-4) var(--sp-4);
	background: var(--c-sunken); border-radius: var(--r);
	color: var(--c-accent); font-size: 1.0625rem;
}
div.info-box-content { flex: 1; min-width: 0; padding: var(--sp-4); display: flex; flex-direction: column; justify-content: center; gap: 2px; }
/* Label above, figure below: the metric is what the tile is for. */
span.info-box-title, div.info-box-title {
	font-size: 0.6875rem;
	font-weight: 620;
	letter-spacing: 0.05em;
	text-transform: uppercase;
	color: var(--c-muted);
	order: -1;
}
span.info-box-text, .info-box-text { font-size: <?php echo $fontsize; ?>; color: var(--c-ink-2); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.info-box-text-a { color: var(--colortextlink); }
span.info-box-weight, span.info-box-number {
	font-size: 1.5rem; font-weight: 640; color: var(--c-ink); letter-spacing: -0.03em; line-height: 1.15;
}
.info-box-more, .info-box-line { font-size: <?php echo $fontsizesmaller; ?>; color: var(--c-muted); }
.info-box-module-enabled { background: var(--infoboxmoduleenabledbgcolor); }

/* Dolibarr's own glyph aliases: object type -> Font Awesome codepoint. An icon
   map, not styling, so the codepoints match upstream. */
.fa-dol-action:before { content: "\f073"; }
.fa-dol-propal:before, .fa-dol-supplier_proposal:before { content: "\f573"; }
.fa-dol-facture:before, .fa-dol-invoice_supplier:before { content: "\f571"; }
.fa-dol-project:before { content: "\f542"; }
.fa-dol-commande:before, .fa-dol-order_supplier:before { content: "\f570"; }
.fa-dol-contrat:before { content: "\f0f2"; }
.fa-dol-ticket:before { content: "\f3ff"; }
.fa-dol-bank_account:before { content: "\f19c"; }
.fa-dol-member:before { content: "\f007"; }
.fa-dol-expensereport:before { content: "\f555"; }
.fa-dol-holiday:before { content: "\f5ca"; }
.fa-dol-cubes:before { content: "\f1b3"; }
.fa-weather-level0:before { content: "\f185"; color: var(--c-success); }
.fa-weather-level1:before { content: "\f6c4"; color: var(--c-warning); }
.fa-weather-level2:before { content: "\f743"; color: var(--c-warning); }
.fa-weather-level3:before { content: "\f740"; color: var(--c-danger); }
.fa-weather-level4:before { content: "\f0e7"; color: var(--c-danger); }
.info-box-weather .info-box-icon { background: var(--c-sunken) !important; }
.infobox-action { color: #6366F1; }
.infobox-propal, .infobox-facture, .infobox-commande { color: #4F46E5; }
.infobox-supplier_proposal, .infobox-invoice_supplier, .infobox-order_supplier { color: var(--c-info); }
.infobox-contrat, .infobox-ticket { color: #0E9F8E; }
.infobox-bank_account { color: #7A6A1F; }
.infobox-adherent, .infobox-member { color: #7C5AA0; }
.infobox-project { color: #3B5BDB; }
.infobox-expensereport { color: #96610A; }
.infobox-holiday { color: #755114; }
.info-box-icon i { color: inherit; }

/* Stat tiles inside a widget. The anchor is the tile, so it must be
   inline-block: block stacks them one per row, and a border on an inline
   element paints across the label instead of around it. */
a.boxstatsindicator, a.thumbstat {
	display: inline-block; width: calc(33.333% - var(--sp-2));
	margin: 0 var(--sp-1) var(--sp-2) 0; vertical-align: top;
	padding: 0; border: 0; color: inherit;
}
div.boxstats {
	display: flex; flex-direction: column; align-items: center; justify-content: center;
	gap: var(--sp-1); min-height: 68px; padding: var(--sp-3);
	border: 1px solid var(--colorboxstatsborder); border-radius: var(--r);
	background: var(--c-surface); text-align: center;
	transition: border-color var(--t), box-shadow var(--t);
}
a.boxstatsindicator:hover div.boxstats { border-color: var(--c-accent); box-shadow: var(--sh-sm); }
span.boxstatstext { font-size: <?php echo $fontsizesmaller; ?>; color: var(--c-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 100%; }
div.boxstats span.boxstatsindicator {
	display: inline-flex; align-items: center; gap: var(--sp-2);
	font-size: 1.125rem; font-weight: 620; color: var(--c-ink); border: 0; padding: 0; width: auto;
}
div.boxstats br { display: none; }
.tdwidgetstate { padding: var(--sp-2) !important; }
@media only screen and (max-width: 1400px) { a.boxstatsindicator, a.thumbstat { width: calc(50% - var(--sp-2)); } }


/* ==========================================================================
   Badges and notices
   ========================================================================== */

.badge {
	display: inline-flex; align-items: center; gap: var(--sp-1);
	padding: 2px var(--sp-2); border-radius: var(--r-pill);
	font-size: 0.6875rem; font-weight: 620; line-height: 1.5;
	white-space: nowrap; vertical-align: middle;
}
.badge-status { border: 1px solid transparent; background: var(--c-sunken); color: var(--c-muted); }
.badge-dot { padding: 0; width: 9px; height: 9px; min-width: 9px; border-radius: 50%; display: inline-block; }
<?php
$statuscolors = array(
	'0' => $badgeStatus0, '1' => $badgeStatus1, '1b' => $badgeStatus1, '2' => $badgeStatus2,
	'3' => $badgeStatus3, '4' => $badgeStatus4, '4b' => $badgeStatus4, '5' => $badgeStatus5,
	'6' => $badgeStatus6, '7' => $badgeStatus7, '8' => $badgeStatus8, '9' => $badgeStatus9,
	'10' => $badgeStatus10, '11' => $badgeStatus11,
);
foreach ($statuscolors as $num => $hex) {
	// The label is the status hue pulled toward ink. Using the raw hue as text
	// on its own 14% tint measured 1.2:1-3.1:1 -- unreadable. The tint and the
	// dot keep the pure colour so the status is still identifiable at a glance.
	echo '.badge-status'.$num.' { background: color-mix(in srgb, '.$hex.' 15%, transparent); color: color-mix(in srgb, '.$hex.' 52%, var(--c-ink)); border-color: color-mix(in srgb, '.$hex.' 30%, transparent); }'."\n";
	echo '.badge-status'.$num.'.badge-dot { background: '.$hex.'; border-color: '.$hex.'; }'."\n";
}
?>
.badgeneutral { background: var(--c-sunken); color: var(--c-muted); }

<?php
/* Semantic badges. These were dropped when the component CSS was split out of
   the COMMAND stylesheet, leaving e.g. .badge-info with no background at all
   -- it inherited the surrounding link colour, which is why they failed
   contrast. Same ink-weighted mix as the status badges so every palette
   stays legible. */
$semantic = array(
	'primary' => $badgePrimary, 'secondary' => $badgeSecondary, 'info' => $badgeInfo,
	'success' => $badgeSuccess, 'warning' => $badgeWarning, 'danger' => $badgeDanger,
);
foreach ($semantic as $name => $hex) {
	$sel = '.badge-'.$name.', .badge'.ucfirst($name);
	echo $sel.' { background: color-mix(in srgb, '.$hex.' 15%, transparent);'
		.' color: color-mix(in srgb, '.$hex.' 52%, var(--c-ink));'
		.' border: 1px solid color-mix(in srgb, '.$hex.' 30%, transparent); }'."\n";
}
?>
.badge-dark, .badgeDark { background: var(--c-ink); color: var(--c-surface); }
.badge-light, .badgeLight { background: var(--c-sunken); color: var(--c-muted); }

div.ok, div.warning, div.error, div.info {
	padding: var(--sp-3) var(--sp-4); margin-bottom: var(--sp-4);
	border: 1px solid transparent; border-<?php echo $left; ?>: 3px solid transparent;
	border-radius: var(--r);
}
div.ok { background: color-mix(in srgb, var(--c-success) 8%, transparent); border-color: color-mix(in srgb, var(--c-success) 25%, transparent); border-<?php echo $left; ?>-color: var(--c-success); color: var(--c-success); }
div.warning { background: color-mix(in srgb, var(--c-warning) 8%, transparent); border-color: color-mix(in srgb, var(--c-warning) 25%, transparent); border-<?php echo $left; ?>-color: var(--c-warning); color: var(--c-warning); }
div.error { background: color-mix(in srgb, var(--c-danger) 8%, transparent); border-color: color-mix(in srgb, var(--c-danger) 25%, transparent); border-<?php echo $left; ?>-color: var(--c-danger); color: var(--c-danger); }
div.info { background: color-mix(in srgb, var(--c-info) 8%, transparent); border-color: color-mix(in srgb, var(--c-info) 25%, transparent); border-<?php echo $left; ?>-color: var(--c-info); color: var(--c-info); }


/* ==========================================================================
   Tooltips, dropdowns, select2, jQuery UI
   ========================================================================== */

/* Dolibarr builds tooltip bodies as <br>-separated label/value lines with no
   rhythm of their own, so they arrive as a solid block of text. Padding and
   leading do most of the work; the rest is giving its <u> sub-headings and
   bold labels a role. */
.ui-tooltip {
	max-width: 420px;
	padding: var(--sp-3) var(--sp-4);
	background: var(--tooltipbgcolor);
	color: var(--tooltipfontcolor);
	border: 1px solid var(--c-hairline);
	border-radius: var(--r-lg);
	box-shadow: var(--sh-lg);
	font-size: 0.8125rem;
	line-height: 1.7;
	z-index: 10000;
}
.ui-tooltip .ui-tooltip-content { padding: 0; }

/* "Rules for this module:" and friends act as section headings. */
.ui-tooltip u {
	display: block;
	margin: var(--sp-3) 0 var(--sp-1) 0;
	padding-top: var(--sp-3);
	border-top: 1px solid color-mix(in srgb, currentColor 15%, transparent);
	text-decoration: none;
	font-size: 0.6875rem;
	font-weight: 620;
	letter-spacing: 0.06em;
	text-transform: uppercase;
	opacity: 0.65;
}
.ui-tooltip u:first-child { margin-top: 0; padding-top: 0; border-top: 0; }
/* Labels lead, values follow -- they were previously indistinguishable. */
.ui-tooltip b, .ui-tooltip strong { font-weight: 600; }
.ui-tooltip br + b, .ui-tooltip br + strong { display: inline-block; margin-top: 1px; }
.ui-tooltip hr { margin: var(--sp-2) 0; border-color: color-mix(in srgb, currentColor 15%, transparent); }
.ui-tooltip .fa, .ui-tooltip [class*="fa-"] { margin-<?php echo $right; ?>: var(--sp-1); opacity: 0.7; }

/* Dolibarr toggles an .open class rather than inline styles, so the closed
   state must live in CSS or every dropdown renders permanently expanded. */
.dropdown { position: relative; }
.dropdown-menu, .dropdown-search, .dropdown-bookmark, .dropdown-quickadd { display: none; }
.open > .dropdown-menu, .open > .dropdown-search, .open > .dropdown-bookmark,
.open > .dropdown-quickadd, .dropdown dd ul.open { display: block; }
.dropdown-menu {
	position: absolute; <?php echo $right; ?>: 0; top: calc(100% + var(--sp-1));
	min-width: 260px; padding: var(--sp-2); margin: 0;
	background: var(--c-surface); border: 1px solid var(--c-border);
	border-radius: var(--r-lg); box-shadow: var(--sh-lg);
	list-style: none; color: var(--c-ink); z-index: 1500;
}
.dropdown-menu a, .dropdown-item { display: block; padding: var(--sp-2) var(--sp-3); border-radius: var(--r); color: var(--c-ink-2); }
.dropdown-menu a:hover, .dropdown-item:hover { background: var(--c-sunken); color: var(--c-ink); }
.dropdown-menu > .user-header { text-align: center; padding: var(--sp-4) var(--sp-3); border-bottom: 1px solid var(--c-hairline); }
.dropdown-menu > .user-footer { border-top: 1px solid var(--c-hairline); display: flex; justify-content: space-between; gap: var(--sp-2); padding: var(--sp-2); }
.dropdown-menu > .user-body, .dropdown-body { padding: var(--sp-2); max-height: 70vh; overflow: auto; }
.dropdown-user-image, .user-header img { width: 72px; height: 72px; border-radius: 50%; object-fit: cover; border: 2px solid var(--c-border); }

/* The dl/dt/dd "selected fields" picker is a separate widget with its own
   open mechanism, and also needs an explicit closed state. */
dl.dropdown { display: inline-block; margin: 0 2px; padding: 0; vertical-align: middle; }
.dropdown dd, .dropdown dt { margin: 0; padding: 0; }
.dropdown dd { position: relative; }
.dropdown dt a { display: block; overflow: hidden; border: 0; cursor: pointer; }
.dropdown span.value { display: none; }
.dropdown dd ul {
	display: none; position: absolute; top: 2px; <?php echo $right; ?>: 0; z-index: 95;
	min-width: 220px; max-height: 300px; overflow: auto; margin: 0; padding: var(--sp-1);
	list-style: none; background: var(--c-surface); border: 1px solid var(--c-border);
	border-radius: var(--r); box-shadow: var(--sh-lg);
}
.dropdown dd ul.selectedfieldsleft { <?php echo $right; ?>: auto; <?php echo $left; ?>: 0; }
.dropdown dd ul li {
	display: flex; align-items: center; gap: var(--sp-2);
	padding: var(--sp-2); border-radius: var(--r-sm); white-space: nowrap; color: var(--c-ink-2);
}
.dropdown dd ul li:hover:not(.liinputsearch) { background: var(--c-sunken); }
input.inputsearch_dropdownselectedfields { width: 100%; }

.select2-container--default .select2-selection--single,
.select2-container--default .select2-selection--multiple {
	background: var(--inputbackgroundcolor);
	border: 1px solid var(--inputbordercolor) !important;
	border-radius: var(--r) !important; min-height: 32px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered { color: var(--c-ink); line-height: 30px; padding-<?php echo $left; ?>: var(--sp-2); }
.select2-container--default .select2-selection--single .select2-selection__arrow { height: 30px; }
.select2-container--default.select2-container--focus .select2-selection--single,
.select2-container--default.select2-container--open .select2-selection--single {
	border-color: var(--c-accent) !important; box-shadow: 0 0 0 3px var(--c-accent-ring);
}
.select2-dropdown { background: var(--c-surface); border: 1px solid var(--c-border); border-radius: var(--r); box-shadow: var(--sh-lg); color: var(--c-ink); }
.select2-container--default .select2-results__option { padding: var(--sp-2) var(--sp-3); color: var(--c-ink-2); }
.select2-container--default .select2-results__option--highlighted { background: var(--c-accent-soft) !important; color: var(--c-accent-ink) !important; }
.select2-container--default .select2-selection--multiple .select2-selection__choice {
	background: var(--c-accent-soft); border: 1px solid color-mix(in srgb, var(--c-accent) 26%, transparent);
	border-radius: var(--r-sm); color: var(--c-accent-ink); padding: 1px var(--sp-2);
}

/* Stacking. jQuery UI hands the dialog and its overlay z-index 101/100, which was
   sufficient against eldy's static chrome but sits far below the fixed shells here
   (the bars and rails run 1200-1400): the dialog opened *underneath* the top bar and
   the sidebar, its title clipped, and the scrim failed to dim the chrome it covered.
   jQuery writes that z-index inline, so this has to be !important to win. */
.ui-widget-overlay { z-index: 2400 !important; }
.ui-dialog { z-index: 2500 !important; }

.ui-dialog {
	background: var(--c-surface); border: 1px solid var(--c-border);
	border-radius: var(--r-lg); box-shadow: var(--sh-lg); font-family: var(--c-font); padding: 0;
}
.ui-dialog .ui-dialog-titlebar { background: transparent; border: 0; border-bottom: 1px solid var(--c-hairline); padding: var(--sp-3) var(--sp-4); font-weight: 600; color: var(--c-ink); }
.ui-dialog .ui-dialog-content { padding: var(--sp-4); color: var(--c-ink); }
.ui-dialog .ui-dialog-buttonpane { border: 0; border-top: 1px solid var(--c-hairline); padding: var(--sp-3) var(--sp-4); margin: 0; }
.ui-widget-overlay { background: rgba(11, 18, 32, 0.4); opacity: 1; }
.ui-datepicker {
	background: var(--c-surface); border: 1px solid var(--c-border); border-radius: var(--r-lg);
	box-shadow: var(--sh-lg); padding: var(--sp-2); font-family: var(--c-font);
	/* Above .ui-dialog (2500): date fields inside a dialog would otherwise drop
	   their calendar behind it. */
	font-size: <?php echo $fontsizesmaller; ?>; z-index: 2600 !important;
}
.ui-datepicker .ui-datepicker-header { background: transparent; border: 0; color: var(--c-ink); font-weight: 600; }
.ui-datepicker td a, .ui-datepicker td span { text-align: center; border-radius: var(--r-sm); padding: var(--sp-1); color: var(--c-ink-2); border: 0; background: transparent; }
.ui-datepicker td a:hover { background: var(--c-sunken); }
.ui-datepicker .ui-state-active { background: var(--c-accent) !important; color: #fff !important; }
.ui-datepicker td.ui-datepicker-today a { background: var(--c-accent-soft); color: var(--c-accent-ink); font-weight: 600; }
.ui-progressbar, .progress { height: 8px; background: var(--c-sunken); border: 0; border-radius: var(--r-pill); overflow: hidden; }
.ui-progressbar .ui-progressbar-value, .progress-bar { background: var(--c-accent); border: 0; border-radius: var(--r-pill); margin: 0; height: 100%; }


/* ==========================================================================
   Login
   ========================================================================== */

/* Dolibarr paints the login backdrop with an inline background on
   .login_center, so that element must stay full-width; making <body> a flex
   container shrinks it to the form width and the backdrop paints as a stripe. */
body.bodylogin { display: block; min-height: 100vh; background: var(--c-canvas); }
div.login_center {
	display: flex; align-items: center; justify-content: center;
	width: 100%; min-height: 100vh;
	background-color: transparent !important;
	background-image:
		radial-gradient(900px 520px at 50% -10%, var(--c-accent-soft), transparent 70%),
		linear-gradient(180deg, var(--c-canvas), var(--c-canvas)) !important;
	background-repeat: no-repeat !important;
	background-size: cover !important;
}
div.login_vertical_align { width: 100%; }
form#login {
	width: 100%; max-width: 400px; margin: 0 auto; padding: var(--sp-6);
	background: var(--c-surface); border: 1px solid var(--c-border);
	border-radius: var(--r-xl); box-shadow: var(--sh-md);
}
/* The logo ships at its natural size and bursts the card without this. */
#img_logo, #login_left img { max-width: 100%; height: auto; display: block; margin: 0 auto var(--sp-4) auto; }
#login_left { text-align: center; }
div.login_table { width: 100%; background: transparent; border: 0; padding: 0; }
#login .tagtable { display: table; width: 100%; }
#login .trinputlogin { display: table-row; }
#login .tagtd, #login .tdinputlogin { display: table-cell; padding: var(--sp-1) 0; vertical-align: middle; }
#login .tagtd.center:first-child { width: 26px; color: var(--c-muted); }
#login .trinputlogin input { width: 100%; height: 36px; }
.login_table_title { text-align: center; color: var(--c-muted); font-size: <?php echo $fontsizesmaller; ?>; padding-bottom: var(--sp-3); }
input.butActionLogin { width: 100%; height: 38px; margin: var(--sp-3) 0 0 0; font-weight: 620; }
.login_main_home { text-align: center; color: var(--c-muted); font-size: <?php echo $fontsizesmaller; ?>; margin-top: var(--sp-4); }


/* ==========================================================================
   Responsive and print
   ========================================================================== */

@media only screen and (max-width: 992px) {
	.fiche { padding: var(--sp-4); }
	:root { --nav-w: var(--nav-w-collapsed); }
	.cmd-nav-label, .cmd-nav-sub, .cmd-nav-toggle-label { display: none; }
	.cmd-nav-link { justify-content: center; padding-left: 0; padding-right: 0; }
	.cmd-brand-text, .cmd-crumbs { display: none; }
	.cmd-trigger { min-width: 0; flex: 1; }
	.fichehalfleft, .fichehalfright, .fichethirdleft, .fichetwothirdright { float: none; width: 100%; }
}
@media only screen and (max-width: 767px) {
	div.tabBar { padding: var(--sp-3); }
	.boxhalfleft, .boxhalfright, .box-flex-item { float: none; width: 100%; min-width: 100%; }
	.hideonsmartphone { display: none !important; }
	.cmd-trigger-label, .cmd-kbd { display: none; }
	.cmd-palette-panel { margin-top: var(--sp-4); }
}
@media print {
	header.cmd-bar, .cmd-palette, div.tabsAction, .noprint { display: none !important; }
	.fiche { padding: 0; max-width: none; }
	body { background: #fff; }
}
@media (prefers-reduced-motion: reduce) {
	*, *::before, *::after { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; }
}



/* ---- Hit areas ----
   Dolibarr emits several controls as bare inline links, which leaves them a
   few pixels tall and awkward to hit. These are the ones that matter on
   every list screen. */

/* Column sort links: make the whole header cell clickable, not just the text. */
tr.liste_titre th a.reposition, tr.liste_titre td a.reposition,
th.liste_titre a.reposition, .liste_titre a.reposition {
	display: inline-flex;
	align-items: center;
	min-height: 28px;
	padding: 0 var(--sp-1);
	margin: 0 calc(var(--sp-1) * -1);
	border-radius: var(--r-sm);
	transition: background var(--t), color var(--t);
}
tr.liste_titre th a.reposition:hover { background: var(--c-surface); }

/* Field-picker, help and other single-glyph triggers. */
a.multiselectpicto, a.help, a.butActionRefused.help,
.divsearchfield > a, .liste_titre .fa-cog {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 24px;
	min-height: 24px;
	border-radius: var(--r-sm);
	transition: background var(--t), color var(--t);
}
a.multiselectpicto:hover, a.help:hover { background: var(--c-sunken); color: var(--c-ink); }

/* Row checkboxes sit in a wide cell; give them a comfortable target. */
input.checkallactions, td.nowrap input[type="checkbox"] {
	width: 16px;
	height: 16px;
	cursor: pointer;
}


/* ---- Stragglers found by the page-by-page audit ---- */

/* Datepicker "now"/"today" shortcuts ship as bare <button>, so they render
   with the browser's stock outset border unless the theme claims them. */
button.dpInvisibleButtons, .dpInvisibleButtons {
	display: inline-flex;
	align-items: center;
	height: 24px;
	padding: 0 var(--sp-2);
	margin-<?php echo $left; ?>: var(--sp-1);
	background: var(--c-sunken);
	border: 1px solid var(--c-border);
	border-radius: var(--r-sm);
	color: var(--c-muted);
	font-family: inherit;
	font-size: <?php echo $fontsizesmaller; ?>;
	line-height: 1;
	cursor: pointer;
	transition: background var(--t), color var(--t), border-color var(--t);
}
button.dpInvisibleButtons:hover {
	background: var(--c-surface);
	border-color: var(--c-border-strong);
	color: var(--c-ink);
}

/* Agenda/list cells stack several tooltip links; without a min-height the
   line boxes collide and the links physically overlap each other. */
td .classforajaxtooltip, td .classfortooltip {
	display: inline-block;
	max-width: 100%;
	vertical-align: middle;
	overflow: hidden;
	text-overflow: ellipsis;
}
td.tdoverflowmax100 .classforajaxtooltip,
td.tdoverflowmax125 .classforajaxtooltip,
td.tdoverflowmax150 .classforajaxtooltip,
td.tdoverflowmax200 .classforajaxtooltip { white-space: nowrap; }


/* ==========================================================================
   Narrow viewports
   Dolibarr lays its edit forms out as 4-column tables (label | field | label |
   field). Below tablet width the second pair is pushed off-screen entirely,
   so the cells are unwound into stacked rows. List tables are excluded --
   they stay tabular and scroll horizontally inside their container.
   ========================================================================== */

@media only screen and (max-width: 767px) {
	table.border:not(.liste), table.tableforfield:not(.liste),
	div.tabBar table.border, div.tabBar table.tableforfield {
		display: block;
		width: 100%;
	}
	table.border:not(.liste) > tbody,
	table.tableforfield:not(.liste) > tbody { display: block; width: 100%; }

	table.border:not(.liste) > tbody > tr,
	table.tableforfield:not(.liste) > tbody > tr {
		display: block;
		width: 100%;
		padding: var(--sp-2) 0;
		border-bottom: 1px solid var(--c-hairline);
	}
	table.border:not(.liste) > tbody > tr > td,
	table.tableforfield:not(.liste) > tbody > tr > td {
		display: block;
		width: 100% !important;
		max-width: 100% !important;
		padding: 2px 0;
		border: 0;
	}
	/* Labels read as captions above their field. */
	table.border:not(.liste) td.titlefield,
	table.border:not(.liste) td.titlefieldcreate,
	table.tableforfield:not(.liste) td.titlefield {
		font-size: <?php echo $fontsizesmaller; ?>;
		font-weight: 600;
		color: var(--c-muted);
	}
	/* Fields fill the row rather than keeping their desktop pixel widths. */
	table.border:not(.liste) input[type="text"],
	table.border:not(.liste) input[type="email"],
	table.border:not(.liste) input[type="password"],
	table.border:not(.liste) textarea,
	table.border:not(.liste) select,
	table.border:not(.liste) .select2-container {
		width: 100% !important;
		max-width: 100% !important;
		min-width: 0 !important;
	}
	/* Action rows keep buttons reachable without horizontal scrolling. */
	div.tabsAction { justify-content: stretch; }
	div.tabsAction .butAction,
	div.tabsAction .butActionDelete,
	div.tabsAction .butActionRefused { flex: 1 1 auto; justify-content: center; }
}


/* Icon-only action links inside cards and module tiles are bare inline <a>
   elements; without a min box they are a few pixels tall. */
.fiche a.valignmiddle:not(.butAction):not(.button),
div.info-box a.valignmiddle {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-height: 22px;
	min-width: 18px;
}
/* Not inside truncating cells: inline-flex defeats text-overflow, so the
   label was hard-cut mid-word and its icon clipped instead of ellipsised. */
.fiche [class*="tdoverflowmax"] a.valignmiddle:not(.butAction):not(.button),
.fiche .tdoverflow a.valignmiddle:not(.butAction):not(.button),
[class*="tdoverflowmax"] a.valignmiddle,
.tdoverflow a.valignmiddle {
	display: inline;
	min-height: 0;
	min-width: 0;
}


/* ==========================================================================
   Setup-form furniture
   Found by reading the Display and Translation screens at full size rather
   than in a scaled contact sheet.
   ========================================================================== */

/* Section headers inside edit tables are a liste_titre row carrying a single
   label. As list-header styling they read as an empty grey band. */
table.editmode > tbody > tr.liste_titre > td,
table.tableforfield > tbody > tr.liste_titre > td {
	padding: var(--sp-3) var(--sp-4);
	background: var(--c-sunken);
	font-size: <?php echo $fontsize; ?>;
	font-weight: 620;
	color: var(--c-ink);
	text-transform: none;
	letter-spacing: -0.008em;
	border-bottom: 1px solid var(--c-border);
}

/* Dolibarr drops the Save/Cancel row straight after the table with no gap,
   so the buttons collided with the block above. */
div.tabBar div.center,
div.tabBar > .center,
form div.tabBar + div.center {
	margin-top: var(--sp-5);
	margin-bottom: var(--sp-3);
}
div.tabBar div.center .button,
div.tabBar div.center input.button { margin: 0 var(--sp-1); }

/* The colour-picker trigger sat flush against the field's right edge. */
a.colorpickerlink, .colorpicker, td .fa-pencil-alt, td .fa-edit,
input.colorthis + a, input[name^="THEME_"] + a {
	margin-<?php echo $left; ?>: var(--sp-2);
	margin-<?php echo $right; ?>: var(--sp-2);
	display: inline-flex;
	align-items: center;
	min-height: 22px;
}
/* Breathing room before the "Default: xxxxxx" hint that follows. */
td input[name^="THEME_"] ~ span,
td .opacitymedium + b { margin-<?php echo $left; ?>: var(--sp-1); }

/* The rows-per-page select is capped at 75px by Dolibarr; the theme's chevron
   padding then clipped the number inside it. */
div.pagination select.selectlimit,
select.selectlimit {
	min-width: 58px;
	padding-<?php echo $left; ?>: var(--sp-2);
	padding-<?php echo $right; ?>: var(--sp-4);
	background-position: <?php echo $right; ?> 4px center;
	background-size: 11px;
	text-align: <?php echo $left; ?>;
}
div.pagination ul { flex-wrap: nowrap; }
div.pagination li { flex: 0 0 auto; }

/* Dashboard tiles: each card sized to its own content, so a one-line tile sat
   visibly shorter than the two-line tile beside it. */
.box-flex-container { align-items: stretch; }
.box-flex-item, .box-flex-item-with-margin { display: flex; }
.box-flex-item > *, .box-flex-item-with-margin > * { width: 100%; }
div.info-box { height: 100%; }

/* A picker with nothing left to offer (e.g. "add widget" once every widget is
   already on the dashboard) leaves Dolibarr's greyed placeholder as the only
   row, holding a single space -- so it rendered as a blank highlighted box.
   :empty cannot match a whitespace node, hence the class hook. */
.select2-results__options > .select2-results__option.optiongrey:only-child {
	font-size: 0;
	background: transparent !important;
	cursor: default;
}
.select2-results__options > .select2-results__option.optiongrey:only-child::after {
	content: "<?php echo dol_escape_js($langs->transnoentitiesnoconv('None')); ?>";
	display: block;
	padding: var(--sp-3) var(--sp-2);
	text-align: center;
	/* rem, not em: the parent is font-size:0 to hide its whitespace node, and
	   an em value would resolve against that zero. */
	font-size: 0.8125rem;
	color: var(--c-faint);
}
.select2-results__option.optiongrey { color: var(--c-faint); }


/* Dolibarr nests a small table inside some label cells to hang an edit pencil
   beside the text. Its first cell collapsed to ~59px, so labels such as
   "Third-party type" broke across three lines at the hyphen. */
td.titlefield table td:first-child,
.tableforfield td table td:first-child,
.fichehalfleft td table td:first-child,
.fichehalfright td table td:first-child {
	width: auto;
	padding-<?php echo $left; ?>: 0;
}
td.titlefield table td:last-child,
.tableforfield td table td:last-child,
.fichehalfleft td table td:last-child,
.fichehalfright td table td:last-child {
	width: 1%;
	white-space: nowrap;
	vertical-align: top;
}
td.titlefield table, .tableforfield td table { width: 100%; }


/* Date-field calendar triggers ship as a bare 12x11 <img>, which is both an
   awkward target and visually unrelated to the field it opens. */
img.ui-datepicker-trigger, .ui-datepicker-trigger {
	width: auto;
	height: auto;
	min-width: 26px;
	min-height: 26px;
	padding: var(--sp-1);
	margin-<?php echo $left; ?>: calc(var(--sp-1) * -1);
	border-radius: var(--r-sm);
	cursor: pointer;
	opacity: 0.55;
	transition: opacity var(--t), background var(--t);
	vertical-align: middle;
}
img.ui-datepicker-trigger:hover, .ui-datepicker-trigger:hover {
	opacity: 1;
	background: var(--c-sunken);
}


/* ==========================================================================
   Rich-text editor (CKEditor)
   Dolibarr embeds CKEditor for every Description/Note field, and it arrives
   with its own 2010-era chrome. These rules bring the frame, toolbar and
   controls into the theme without touching the editor's own layout.
   ========================================================================== */

/* The frame carries a stronger border than a plain input. An editor is a
   region, not a field: it is often several hundred pixels tall and sits on
   rows that change colour on hover or when checked, and at input-border
   weight its outline disappeared into those backgrounds -- the editor read as
   loose furniture on the page rather than a bounded surface. The small shadow
   holds that separation on any row colour. */
.cke, .cke_chrome {
	border: 1px solid var(--c-border-strong) !important;
	border-radius: var(--r) !important;
	box-shadow: var(--sh-sm) !important;
	overflow: hidden;
	max-width: 100%;
}
.cke_inner { background: var(--c-surface) !important; }

/* Toolbar */
.cke_top {
	background: var(--c-sunken) !important;
	border-bottom: 1px solid var(--c-hairline) !important;
	box-shadow: none !important;
	padding: var(--sp-2) !important;
}
.cke_toolgroup, .cke_combo_button {
	background: transparent !important;
	border: 0 !important;
	box-shadow: none !important;
	margin: 1px var(--sp-1) 1px 0 !important;
}
.cke_button {
	border-radius: var(--r-sm) !important;
	transition: background var(--t);
}
.cke_button:hover, .cke_button__:hover,
.cke_button_on, .cke_combo_button:hover {
	background: var(--c-surface) !important;
	border-color: var(--c-border) !important;
	box-shadow: none !important;
}
.cke_button_on { box-shadow: inset 0 0 0 1px var(--c-border) !important; }
.cke_combo_text {
	font-family: var(--c-font) !important;
	font-size: 0.75rem !important;
	color: var(--c-muted) !important;
}
.cke_toolbar_separator {
	background: var(--c-border) !important;
	margin: var(--sp-1) var(--sp-2) !important;
}

/* Editing surface and status bar */
.cke_contents { background: var(--c-surface) !important; }
.cke_bottom {
	background: var(--c-sunken) !important;
	border-top: 1px solid var(--c-hairline) !important;
	box-shadow: none !important;
	padding: var(--sp-1) var(--sp-2) !important;
}
.cke_path_item, .cke_path_empty {
	color: var(--c-faint) !important;
	font-family: var(--c-font) !important;
	font-size: 0.6875rem !important;
}
.cke_resizer { border-color: transparent var(--c-border-strong) transparent transparent !important; }

/* Focus ring on the whole frame, matching every other field. The border keeps
   full strength alongside the ring -- a ring on its own is a soft tint, and on
   a highlighted row it was the only thing marking the edge. */
.cke_focus, .cke_chrome:focus-within {
	border-color: var(--c-accent) !important;
	box-shadow: 0 0 0 3px var(--c-accent-ring), var(--sh-sm) !important;
}

/* Text selection inside the editor. The browser default is an opaque blue
   that buries the text it covers, which is what made a highlighted block look
   like the editor had gone. A tint keeps the words readable while selected.
   Only reaches the editor when it renders inline (divarea); in iframe mode the
   editing surface is a separate document and CKEditor styles it from its own
   contents.css, which a parent stylesheet cannot cross. */
.cke_editable ::selection, .cke_editable::selection,
.cke_contents ::selection {
	background: var(--c-accent-soft);
	color: var(--c-ink);
}

/* Dropdown panels the toolbar opens (Styles, Format, colours). */
.cke_panel {
	border: 1px solid var(--c-border) !important;
	border-radius: var(--r) !important;
	box-shadow: var(--sh-lg) !important;
}
.cke_panel_list, .cke_panel_block { background: var(--c-surface) !important; }
.cke_panel_listItem a { color: var(--c-ink-2) !important; }
.cke_panel_listItem a:hover, .cke_panel_listItem.cke_selected a {
	background: var(--c-accent-soft) !important;
	color: var(--c-accent-ink) !important;
}


/* ==========================================================================
   Agenda
   ========================================================================== */

/* View switcher. Dolibarr emits <a class="btnTitle"><icon><label></a> in a
   row with no separation, so each icon appeared to belong to the label before
   it. Grouping them as a segmented control makes the pairing unambiguous. */
div.navmode {
	display: inline-flex;
	align-items: center;
	gap: 2px;
	padding: 3px;
	background: var(--c-sunken);
	border: 1px solid var(--c-hairline);
	border-radius: var(--r);
}
div.navmode a.btnTitle {
	display: inline-flex;
	align-items: center;
	gap: var(--sp-2);
	padding: 5px var(--sp-3);
	border-radius: var(--r-sm);
	color: var(--c-muted);
	font-size: <?php echo $fontsizesmaller; ?>;
	white-space: nowrap;
	transition: background var(--t), color var(--t);
}
div.navmode a.btnTitle:hover { background: var(--c-surface); color: var(--c-ink); }
div.navmode a.btnTitleSelected {
	background: var(--c-surface);
	color: var(--c-accent-ink);
	font-weight: 620;
	box-shadow: var(--sh-sm);
}

/* Per-day "add event" control. At full strength it competed with the dates in
   every empty cell; quiet at rest, obvious on hover, and still reachable by
   keyboard and touch. */
td a.btnTitlePlus {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 24px;
	height: 24px;
	border-radius: var(--r-sm);
	color: var(--c-faint);
	opacity: 0.35;
	transition: opacity var(--t), background var(--t), color var(--t);
}
td:hover > a.btnTitlePlus,
tr:hover a.btnTitlePlus { opacity: 1; }
td a.btnTitlePlus:hover, td a.btnTitlePlus:focus-visible {
	opacity: 1;
	background: var(--c-accent-soft);
	color: var(--c-accent);
}

/* Calendar grid: keep day cells readable and the date anchored top-left. */
table.cal_month td, td.cal_current_month, td.cal_other_month {
	vertical-align: top;
}
td.cal_other_month { background: var(--c-sunken); }
td.cal_today, td.cal_today_am, td.cal_today_pm {
	background: var(--c-accent-soft) !important;
	box-shadow: inset 0 2px 0 var(--c-accent);
}


/* ---- Destructive submits ----
   Dolibarr renders irreversible bulk actions (Purge, mass delete) as a plain
   `input.button`, visually identical to Cancel. The form path is the reliable
   signal; the value match is a best-effort extra that only applies on
   English installs and can never make a non-destructive button look worse. */
form[action*="purge.php"] input[type="submit"].button,
form[action*="delete"] input[type="submit"].button,
input[type="submit"].button[value*="Purge" i],
input[type="submit"].button[value*="Delete" i] {
	background: var(--c-surface);
	border-color: color-mix(in srgb, var(--c-danger) 40%, transparent);
	color: var(--c-danger);
	font-weight: 600;
}
form[action*="purge.php"] input[type="submit"].button:hover,
form[action*="delete"] input[type="submit"].button:hover,
input[type="submit"].button[value*="Purge" i]:hover,
input[type="submit"].button[value*="Delete" i]:hover {
	background: var(--c-danger);
	border-color: var(--c-danger);
	color: #fff;
}


/* The record banner's picto sits in a floated column that pushed the title
   45px right of the fields beneath it. Tightening the gutter brings the two
   left edges close enough to read as aligned; the picto itself is Dolibarr's
   markup and keeping it preserves the record-type cue. */
div.arearef { position: relative; }
/* The picto column is the floated block WITHOUT .valignmiddle (that class marks
   the title block beside it). Lifting it into the card's padding gutter puts
   the record title on the same left edge as the fields below. Real photos
   (.divphotoref, product/user avatars) keep their place in the flow. */
/* Previously pulled to -30px so the record title shared a left edge with the
   fields below. That bought alignment with the table underneath at the cost of
   the picto hanging outside the card's content, detached from the record it
   labels. Reverted to normal flow: the icon reads as part of the heading,
   which is the relationship that actually matters here. */
div.arearef div.inline-block.floatleft:not(.valignmiddle):not(.divphotoref):not(:has(img)) {
	position: static;
	width: auto;
	margin: 0 var(--sp-2) 0 0;
	display: inline-flex;
	align-items: center;
}
div.arearef .refid, div.arearef .refidno { margin-<?php echo $left; ?>: 0; }
div.arearef img.photoref, div.arearef .divphotoref { margin-<?php echo $right; ?>: var(--sp-4); }


/* ---- Avatars in tables ----
   Dolibarr emits user photos at their stored size (128x128), so a single
   avatar inflated an events row to ~130px tall. Table context wants a
   thumbnail; the record banner keeps the large photo. */
td img.userphoto, td img.photouserphoto,
td img.photoref, td img.photo {
	width: 28px;
	height: 28px;
	min-width: 28px;
	border-radius: 50%;
	object-fit: cover;
	vertical-align: middle;
}
/* Slightly larger where the cell is the record's own portrait column. */
td.center > img.userphoto, td.photo img.userphoto { width: 34px; height: 34px; min-width: 34px; }
/* The banner portrait is deliberately large and must not be caught above. */
div.arearef img.userphoto, div.arearef img.photoref,
.divphotoref img { width: auto; height: auto; min-width: 0; border-radius: var(--r); }

/* The account avatar lives in the top-bar / rail login dropdown, not a table
   cell, so the thumbnail rules above never reach it and Dolibarr renders it at
   its stored size (up to 80-128px) — towering over the ~52px bar. Constrain it
   to a small round thumbnail wherever the account block lands. */
#topmenu-login-dropdown img.photouserphoto,
#topmenu-login-dropdown img.userphoto,
.login-dropdown-a img.photouserphoto, .login-dropdown-a img.userphoto,
.atoplogin img.photouserphoto, .atoplogin img.userphoto {
	width: 28px;
	height: 28px;
	min-width: 28px;
	border-radius: 50%;
	object-fit: cover;
	vertical-align: middle;
}

/* ---- Leading field pictos ----
   Dolibarr prefixes many fields with a bare icon and no spacing, so the glyph
   sat flush against the input's border. The gap goes on the field rather than
   the icon so it applies however the icon is marked up. */
td span[class*="fa-"] + input,
td span[class*="fa-"] + select,
td span[class*="fa-"] + textarea,
td span[class*="fa-"] + .select2-container,
td span[class*="fa-"] ~ .select2-container,
td span.pictofixedwidth ~ .select2-container,
td img.pictofixedwidth + input,
td img.pictofixedwidth + .select2-container,
td span.pictofixedwidth + input,
td span.pictofixedwidth + .select2-container {
	margin-<?php echo $left; ?>: var(--sp-2);
}


/* The status badge floats directly beneath the back/prev/next row with no gap
   (both end and start at the same y), so the two collided visually even though
   their right edges line up. */
div.arearef div.statusref { margin-top: var(--sp-2); }
div.arearef .paginationref { margin-bottom: var(--sp-1); }
/* Prev/next arrows are bare links; give them a real target and hover box so
   the hovered one does not look like a stray highlighted glyph. */
.paginationref a, div.pagination .paginationafterarrows a, div.pagination .paginationbeforearrows a {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 26px;
	height: 26px;
	border-radius: var(--r-sm);
	color: var(--c-muted);
	transition: background var(--t), color var(--t);
}
.paginationref a:hover { background: var(--c-sunken); color: var(--c-ink); }


/* ---- Trailing pictos ----
   Icons that follow a value (copy-to-clipboard, warning, external link) are
   emitted with no spacing at all, so they sit flush against the text and each
   other. The earlier rule only covered icons that PRECEDE a field.
   :first-child is exempt so a leading picto keeps its original position --
   text nodes are not elements, so the icon after a bare value is not first. */
td span[class*="fa-"], td a[class*="fa-"], td i[class*="fa-"],
td .clipboardCPButton, td .clipboardCPButtonInfo {
	margin-<?php echo $left; ?>: var(--sp-2);
}
/* :first-child counts elements only, so a glyph that FOLLOWS a text value
   still matches -- which is how a warning triangle emitted after
   "utf8mb4_unicode_ci" got its gap stripped and collided with the value. The
   status pictos are always trailing marks, so they are exempt from the reset;
   the rest (a genuine leading icon) still start flush. */
td > span[class*="fa-"]:first-child:not([class*="pictowarning"]):not([class*="pictoerror"]):not([class*="pictodanger"]):not([class*="pictostatus"]),
td > a[class*="fa-"]:first-child,
td > i[class*="fa-"]:first-child,
td > span.pictofixedwidth:first-child { margin-<?php echo $left; ?>: 0; }

/* Same story in the record banner, where the ref is followed by action icons. */
div.arearef .clipboardCPButton,
div.arearef span[class*="fa-"]:not(:first-child) { margin-<?php echo $left; ?>: var(--sp-2); }


/* ==========================================================================
   Module cards (admin/modules.php)
   ========================================================================== */

/* Module logos ship at their source size -- object_paypal.png is 256x256 and
   single-handedly stretched its card to ~900px tall, squeezing the text into
   a one-word column. Every module image is normalised to an icon. */
.box-flex-item img, .box-flex-item-with-margin img,
div.info-box img, .modulebox img {
	max-width: 34px;
	max-height: 34px;
	width: auto;
	height: auto;
	object-fit: contain;
	vertical-align: middle;
}

/* Cards in a row must share a height, and their footer (gear + toggle) must
   sit on a common baseline regardless of how long the description runs. */
.box-flex-container { align-items: stretch; }
.box-flex-item, .box-flex-item-with-margin { display: flex; align-items: stretch; }
.box-flex-item > *, .box-flex-item-with-margin > * { width: 100%; }
.box-flex-item .info-box, .box-flex-item-with-margin .info-box {
	height: 100%;
	display: flex;
	flex-direction: column;
}
.box-flex-item .info-box-content, .box-flex-item-with-margin .info-box-content {
	flex: 1 1 auto;
	display: flex;
	flex-direction: column;
}
/* The action row is pinned to the bottom of the card. */
.box-flex-item .info-box-more, .box-flex-item .info-box-actions,
.box-flex-item-with-margin .info-box-more {
	margin-top: auto;
	padding-top: var(--sp-2);
}


/* Dolibarr's "no image yet" placeholder ships at 100x100 (same in eldy) and
   reads as an unexplained camera glyph next to the upload control. Smaller,
   muted and framed so it registers as an empty preview slot. */
img[src*="nophoto"], img[src*="nophoto.png"] {
	width: 56px;
	height: 56px;
	padding: var(--sp-2);
	border: 1px dashed var(--c-border);
	border-radius: var(--r);
	background: var(--c-sunken);
	opacity: 0.45;
	object-fit: contain;
	vertical-align: middle;
}


/* ---- jQuery treeview (menu editor, category trees) ----
   The plugin draws its hierarchy with bitmap backgrounds: a dotted GIF tiled
   down each <li> for the guide line, and a 16x16 sprite sheet shifted by
   background-position for the +/- control. Both are fixed-size, fixed-colour
   and stuck at a 16px indent, so the tree ignored the theme entirely and the
   control never lined up with the row text beside it.

   Everything below replaces the bitmaps with CSS so the tree inherits the
   theme's ink, spacing and hit-target sizes. */

ul.treeview, ul.treeview ul {
	margin: 0;
	padding: 0;
	list-style: none;
	background-image: none;		/* the tiled dotted GIF */
}
ul.treeview { margin-top: var(--sp-1); padding-<?php echo $left; ?>: 24px; }

/* Nested levels: one continuous hairline instead of the dotted tile. */
/* The indent has to clear the chevron, not just the text. The control is
   positioned 20px left of its row, so at an 18px indent it landed within a
   pixel of the guide line and punched a hole in it at every branch -- the line
   read as a column of disconnected stubs rather than one rail. 30px puts the
   chevron clear of the line with room to spare. */
ul.treeview ul {
	margin-<?php echo $left; ?>: 11px;
	padding-<?php echo $left; ?>: 30px;
	/* No border here: a border on the list spans its whole height, so the rail
	   kept descending past the final entry -- pointing at children that do not
	   exist. Drawn per row instead (below) so the last one can stop. */
}
/* The rail is a segment on each row, full height for every row except the
   last, which stops where its own elbow meets it. */
ul.treeview ul > li::after {
	content: "";
	position: absolute;
	<?php echo $left; ?>: -30px;
	top: 0;
	bottom: 0;
	width: 1px;
	background: var(--c-border);
}
ul.treeview ul > li:last-child::after {
	bottom: auto;
	height: 23px;		/* meets the elbow at top:22px */
}

/* The class-qualified selectors are not redundant. The plugin sets its elbow
   bitmap in `.treeview li.lastExpandable` and friends, which outranks a plain
   `ul.treeview li`, so the GIF survived on precisely the last child of each
   branch -- a stray box floating above those labels while every other row was
   clean. Each variant has to be matched at its own weight. */
ul.treeview li,
ul.treeview li.last,
ul.treeview li.expandable, ul.treeview li.collapsable,
ul.treeview li.lastExpandable, ul.treeview li.lastCollapsable {
	position: relative;
	margin: 0;
	padding: 0;
	background-image: none;		/* per-item elbow GIF */
	min-height: 0;
}
/* Same problem on the control: `.treeview .expandable-hitarea` is stronger
   than `ul.treeview div.hitarea`. */
ul.treeview div.hitarea,
ul.treeview div.expandable-hitarea, ul.treeview div.collapsable-hitarea,
ul.treeview div.lastExpandable-hitarea, ul.treeview div.lastCollapsable-hitarea {
	background-image: none;
}

/* Each row is a full table; give it a real hit area and a hover state so the
   tree reads as a list of targets rather than a wall of links. */
ul.treeview li > table {
	border-radius: var(--r-sm);
	transition: background-color var(--t);
}
ul.treeview li > table:hover { background: var(--c-sunken); }
ul.treeview li > table > tbody > tr > td { padding: var(--sp-1) var(--sp-2); }
ul.treeview li > table strong.paddingleft { padding-<?php echo $left; ?>: 0; font-weight: 550; }
ul.treeview li a { color: var(--c-ink); }
ul.treeview li a:hover { color: var(--c-accent-ink); }

/* ---- The expand/collapse control ----
   Stock markup is an empty 16px div floated with a -16px margin, which put it
   above the row text rather than beside it. Positioned instead, so it stays
   centred on the row whatever the row height. */
ul.treeview div.hitarea {
	position: absolute;
	<?php echo $left; ?>: -20px;
	/* Centred on the row, not the row plus its expanded children: an open
	   branch is as tall as its whole subtree, so this is offset from the top
	   by half a row rather than positioned at 50%. */
	top: 9px;
	float: none;
	width: 22px;
	height: 26px;
	margin: 0;
	background-image: none;		/* the +/- sprite sheet */
	cursor: pointer;
	z-index: 1;
}
ul.treeview div.hitarea::before {
	content: "";
	position: absolute;
	top: 50%;
	<?php echo $left; ?>: 50%;
	width: 6px;
	height: 6px;
	margin: -4px 0 0 -4px;
	border-<?php echo $right; ?>: 1.6px solid var(--c-muted);
	border-bottom: 1.6px solid var(--c-muted);
	transform: rotate(-45deg);	/* collapsed: points right (left in RTL) */
	transition: transform var(--t), border-color var(--t);
}
ul.treeview li.collapsable > div.hitarea::before {
	margin-top: -6px;
	transform: rotate(45deg);	/* expanded: points down */
}
ul.treeview div.hitarea:hover::before { border-color: var(--c-accent); }

/* A leaf has no hitarea; keep its text on the same grid as its siblings by
   marking the branch point on the guide line instead. */
/* The elbow has to start ON the rail and stop short of the label, so it is
   measured from the same indent rather than a fixed guess -- at the old 18px
   it began 11px clear of the line and the branch looked detached. */
ul.treeview ul li:not(.expandable):not(.collapsable)::before {
	content: "";
	position: absolute;
	<?php echo $left; ?>: -30px;
	top: 22px;			/* vertical centre of a 44px row */
	width: 21px;
	height: 1px;
	background: var(--c-border);
}

/* Row action icons: the add/remove controls ship as 12px bitmaps and sat on
   the text baseline, half a line below the pencil beside them. */
ul.treeview img[src*="edit_add"], ul.treeview img[src*="edit_remove"] {
	width: 13px;
	height: 13px;
	vertical-align: middle;
	opacity: 0.6;
	transition: opacity var(--t);
}
ul.treeview a:hover img[src*="edit_add"],
ul.treeview a:hover img[src*="edit_remove"] { opacity: 1; }
ul.treeview span.menuEdit { color: var(--c-muted) !important; vertical-align: middle; }
ul.treeview a:hover span.menuEdit { color: var(--c-accent) !important; }

/* Disabled entries should read as inactive without becoming unreadable. */
ul.treeview li.liuserdisabled > table a { color: var(--c-muted); }


/* ---- Module / kanban card grid ----
   The container is a wrapping flexbox. `align-items: stretch` equalises the
   cards within a single row, but flexbox has no relationship between rows, so
   one long module description made its whole row 232px while every other row
   sat at 210px -- reading as cards of assorted sizes rather than a grid.

   Grid solves what flexbox structurally cannot: `grid-auto-rows: 1fr` sizes
   every row to the tallest card on the page, so the whole board is uniform.
   auto-fill with the same 300px floor keeps the existing wrap points. */
div.box-flex-container.kanban {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
	grid-auto-rows: 1fr;
	align-items: stretch;
}
div.box-flex-container.kanban > .box-flex-item {
	/* flex sizing is inert in grid; the card still lays its own contents out. */
	width: auto;
	max-width: none;
	margin: 0;
}


/* ---- Module card internals ----
   Dolibarr paints the "enabled" tint on .info-box-content, which is inset
   inside .info-box -- 332x208 within a 387x232 card. The result was a
   coloured rectangle floating in a white card with an uneven gutter around
   it, rather than a card that reads as switched on. The tint belongs on the
   card; the content then only has to fill it.

   The icon block is also laid out as a sibling column, so a 17px glyph
   rendered centred above copy that is left-aligned. Stacking the card makes
   both start on the same edge. */
div.box-flex-container.kanban .info-box {
	display: flex;
	flex-direction: column;
	align-items: stretch;
	height: 100%;
	overflow: hidden;
}
div.box-flex-container.kanban .info-box-icon {
	width: 100%;
	height: auto;
	padding: var(--sp-3) var(--sp-4) 0;
	text-align: <?php echo $left; ?>;
	background: transparent;
}
div.box-flex-container.kanban .info-box-content {
	width: 100%;
	flex: 1 1 auto;
	background: transparent !important;	/* the misplaced tint */
}
div.box-flex-container.kanban .info-box:has(.info-box-icon-module-enabled) {
	background: var(--infoboxmoduleenabledbgcolor);
	border-color: color-mix(in srgb, var(--c-accent) 30%, var(--c-border));
}

/* The info affordance sat on its own line between the description and the
   action row, orphaned in whitespace. It belongs beside the controls it
   relates to, on the card's baseline. */
div.box-flex-container.kanban .info-box-content > br { display: none; }
div.box-flex-container.kanban .info-box-more,
div.box-flex-container.kanban .info-box-actions {
	display: inline-flex !important;
	align-items: center;
	gap: var(--sp-2);
	padding-top: 0 !important;
	vertical-align: middle;
}
div.box-flex-container.kanban .info-box-content {
	display: flex;
	flex-direction: column;
	align-items: flex-start;
}
/* Actions pinned to the bottom edge so every card's controls share one line. */
div.box-flex-container.kanban .info-box-actions { margin-top: auto; }
/* The content column stacks, so `more` and `actions` cannot share a row as
   siblings without a wrapper Dolibarr does not emit. Pinning the info
   affordance to the card corner puts it on the controls' line and gives every
   card the same footer regardless of how many lines the description ran to. */
div.box-flex-container.kanban .info-box { position: relative; }
div.box-flex-container.kanban .info-box-more {
	position: absolute;
	<?php echo $right; ?>: var(--sp-4);
	bottom: var(--sp-3);
	margin: 0;
}
div.box-flex-container.kanban .info-box-actions { order: 4; }
/* Keep the description clear of the pinned badge. */
div.box-flex-container.kanban .info-box-desc { padding-<?php echo $right; ?>: var(--sp-5); }


/* ---- Settings form field alignment ----
   Dolibarr puts an optional leading picto inside the value cell, so a row with
   an icon starts its input 27px further right than a row without one. Down a
   16-row form that produced a visibly ragged left edge on the controls -- the
   single most obvious thing wrong with the settings screens.

   Fixing it means reserving the picto gutter on every row, including the ones
   that have no picto. The icon gets a fixed box so the reservation is exact
   rather than dependent on which glyph happens to be used. */
/* The picto is lifted out of the text flow into a fixed gutter. While it was
   inline, a row's field position was the sum of the glyph's width plus every
   margin around it, so picto rows and plain rows drifted apart whenever any of
   those changed -- they were 4px and 12px out on the product form. Out of flow,
   both kinds of row start at exactly the cell's padding. */
div.tabBar table.border:not(.liste):not(.tableforfield) > tbody > tr > td:nth-child(even) > span[class*="fa-"]:first-child,
div.tabBar table.editmode:not(.liste):not(.tableforfield) > tbody > tr > td:nth-child(even) > span[class*="fa-"]:first-child {
	position: absolute;
	<?php echo $left; ?>: var(--sp-3);
	/* Aligned to the centre of the control beside it, not the top of the cell.
	   A 34px field inside this cell's padding centres 30px down, so a 16px
	   glyph starts at 22px -- at 14px it sat 8px high on every picto row. On a
	   tall row (a textarea) this keeps the icon on the first line, which is
	   where the label it marks actually is. */
	top: 22px;
	width: 19px;
	margin: 0;
	text-align: <?php echo $left; ?>;
}
/* Padding on the cell rather than an inline ::before spacer: a pseudo-element
   only pushes inline controls across, so block-level fields (textareas) broke
   to the next line and started back at the unpadded edge -- the exact ragged
   edge this is meant to remove. */
div.tabBar table.border:not(.liste):not(.tableforfield) > tbody > tr > td:nth-child(even),
div.tabBar table.editmode:not(.liste):not(.tableforfield) > tbody > tr > td:nth-child(even) {
	position: relative;
	padding-<?php echo $left; ?>: calc(var(--sp-3) + 27px);
}

/* Short fields were left at whatever `size` attribute the PHP passed, so the
   column's right edge stepped in and out row by row. A floor evens them up
   without forcing a postcode to be as wide as a street address -- fields that
   Dolibarr has explicitly marked narrow keep their intended size. */
/* Dolibarr caps individual fields with utility classes -- maxwidth150 on a
   phone, maxwidth300 on a web address -- sized for its own dense two-column
   card layout. On a full-width settings form those caps are what produced the
   stepped right edge, so inside these tables one shared measure wins over the
   per-field cap. The overrides beat the utility classes, hence !important. */
/* A fixed 420px measure left 411px of a 1194px form empty -- a third of the
   width doing nothing while every control huddled against the left edge. The
   fields now take the column they are in, with a cap so a single line of text
   never runs to an unreadable length. */
div.tabBar table.border:not(.liste) td > input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]):not([type="number"]):not(.button):not([class~="width25"]):not([class~="width50"]):not([class~="width75"]):not([class~="width100"]):not([class~="width125"]):not([class~="maxwidth50"]):not([class~="maxwidth75"]):not([class~="maxwidth100"]),
div.tabBar table.editmode:not(.liste) td > input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]):not([type="number"]):not(.button):not([class~="width25"]):not([class~="width50"]):not([class~="width75"]):not([class~="width100"]):not([class~="width125"]):not([class~="maxwidth50"]):not([class~="maxwidth75"]):not([class~="maxwidth100"]) {
	width: 100% !important;
	max-width: 760px !important;
	box-sizing: border-box;
}
/* Fields that hold a count, not a sentence. Stretching a "7 days" box to the
   full column made a two-character value look like a missing paragraph -- the
   width should suggest what belongs in it. */
div.tabBar table td > input[type="number"],
div.tabBar table td > input[class~="width25"],
div.tabBar table td > input[class~="width50"],
div.tabBar table td > input[class~="width75"],
div.tabBar table td > input[class~="width100"],
div.tabBar table td > input[class~="width125"],
div.tabBar table td > input[class~="maxwidth50"],
div.tabBar table td > input[class~="maxwidth75"],
div.tabBar table td > input[class~="maxwidth100"] {
	width: 110px !important;
	max-width: 110px !important;
	text-align: <?php echo $right; ?>;
	box-sizing: border-box;
}
/* Textareas take the same edge rather than a percentage of the table, which
   was what made them overhang every input above and below them. */
div.tabBar table.editmode:not(.liste) td > textarea,
div.tabBar table.border:not(.liste) td > textarea { width: 100%; max-width: 760px; box-sizing: border-box; }
div.tabBar table.editmode:not(.liste) td > .select2-container,
div.tabBar table.border:not(.liste) td > .select2-container { width: 100% !important; max-width: 760px !important; }


/* ---- Dashboard widget cards ----
   Same mismatch as the module board: the icon block is laid out as its own
   centred column while the title and figures below it are left-aligned, so
   every card had its glyph floating off-axis from its own text. Aligning them
   to one edge is what makes a row of cards read as a set. */
/* The icon block is itself a flex container, so text-align does nothing to
   it -- the glyph is centred by the flex main axis and has to be moved with
   justify-content. */
div.box-flex-container > .box-flex-item .info-box-icon {
	display: flex;
	justify-content: flex-start;
	align-items: center;
	width: 100%;
	height: auto;
	/* No horizontal padding: the card already insets its content, and adding
	   more here pushed the glyph one step right of the title beneath it. */
	padding: var(--sp-3) 0 0;
	text-align: <?php echo $left; ?>;
	background: transparent;
}
div.box-flex-container > .box-flex-item .info-box {
	display: flex;
	flex-direction: column;
	align-items: stretch;
	height: 100%;
}
div.box-flex-container > .box-flex-item .info-box-content {
	width: 100%;
	flex: 1 1 auto;
	text-align: <?php echo $left; ?>;
}


/* ---- Action row hierarchy ----
   Dolibarr gives every enabled action the same .butAction class, so a record
   card commonly presents three or four equally-weighted filled buttons. With
   nothing to separate them the row reads as a wall and the action a user
   actually wants is no faster to find than the ones they don't.

   Dolibarr emits these in significance order -- the primary action first --
   so the first button keeps the filled treatment and the rest step down to
   outlined. Weight is expressed by fill, not colour, so the accent still means
   one thing on the page. Delete and disabled actions already have their own
   classes and are untouched. */
div.tabsAction .butAction:not(:first-of-type),
div.tabsAction a.butAction:not(:first-of-type),
div.tabsAction input.butAction:not(:first-of-type) {
	background: var(--c-surface);
	color: var(--c-accent-ink);
	border-color: var(--c-border);
	box-shadow: none;
}
div.tabsAction .butAction:not(:first-of-type):hover,
div.tabsAction a.butAction:not(:first-of-type):hover {
	background: var(--c-accent-soft);
	border-color: var(--c-accent);
	color: var(--c-accent-ink);
}


/* ---- Chrome label contrast ----
   The search trigger, the nav collapse control and the account name were all
   set in --c-faint, the lightest step of the neutral ramp. That step exists
   for non-essential marks like separators and disabled glyphs; used for real
   words at body size it measured 2.35:1 to 3.73:1 against the bar behind it,
   failing WCAG AA (4.5:1) in every one of the five shells.

   --c-muted is the lightest step intended to carry text. These labels name
   the controls they sit in, so they have to be readable, not decorative. */
.cmd-trigger, .cmd-trigger-label,
.cmd-nav-toggle, .cmd-nav-toggle-label {
	color: var(--c-muted);
}
/* The account name sits on the darkest bar of all, where --c-muted is still
   short of AA, so it takes the next step up. */
.atoplogin, .atoplogin span, .login-dropdown-a .hidden-xs { color: var(--c-ink-2); }
.cmd-trigger:hover, .cmd-trigger:hover .cmd-trigger-label,
.cmd-nav-toggle:hover { color: var(--c-ink-2); }
/* .wb-brand is deliberately excluded: it is a logo tile whose letter sits on
   an accent fill, not chrome text. Recolouring it to a neutral dropped it to
   1.45:1 -- worse than the 3.74:1 it started at. It is fixed in the theme. */


@media only screen and (max-width: 767px) {
	/* The shared 420px field measure has to be released on narrow screens.
	   max-width:100% does not clamp it: these tables use auto layout, so the
	   cell simply grows to fit its widest child and 100% then resolves to the
	   same 420px. Only an explicit width relative to the cell actually binds. */
	div.tabBar table.border:not(.liste) td > input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]):not(.button),
	div.tabBar table.editmode:not(.liste) td > input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]):not(.button),
	div.tabBar table.border:not(.liste) td > textarea,
	div.tabBar table.editmode:not(.liste) td > textarea {
		width: 100% !important;
		min-width: 0 !important;
	}
	div.tabBar table.border:not(.liste) td > .select2-container,
	div.tabBar table.editmode:not(.liste) td > .select2-container {
		width: 100% !important;
		min-width: 0 !important;
	}
	/* The picto gutter is dead weight once rows stack. */
	div.tabBar table.border:not(.liste):not(.tableforfield) > tbody > tr > td:nth-child(even),
	div.tabBar table.editmode:not(.liste):not(.tableforfield) > tbody > tr > td:nth-child(even) {
		padding-<?php echo $left; ?>: var(--sp-3);
	}
}


/* ---- Anonymous user placeholder ----
   nophoto.png is only used for object photos. A user with no portrait gets
   user_anonymous.png (or user_man/user_woman), which Dolibarr ships at 128x128
   and renders at full size -- the oversized grey figure that dominated the
   user, member and product cards. It is a placeholder, not content, so it is
   sized and muted like one. The 28px dropdown avatar is left alone. */
/* The banner-portrait rule above (`div.arearef img.userphoto`, 0-2-2) outranks
   a plain attribute selector (0-1-1), so the placeholder kept width:auto and
   stayed at 128px. The banner variants are therefore matched at their own
   weight rather than relying on source order. */
div.arearef img.userphoto[src*="user_anonymous"],
div.arearef img.photoref[src*="user_anonymous"],
.divphotoref img[src*="user_anonymous"],
div.arearef img.userphoto[src*="user_man"],
div.arearef img.userphoto[src*="user_woman"],
.divphotoref img[src*="user_man"], .divphotoref img[src*="user_woman"],
img[src*="user_anonymous"], img[src*="user_man.png"], img[src*="user_woman.png"] {
	width: 80px;
	height: 80px;
	object-fit: contain;
	opacity: 0.5;
	background: var(--c-sunken);
	border: 1px dashed var(--c-border) !important;
	border-radius: var(--r);
}
img.dropdown-user-image[src*="user_"], .dropdown-user-image {
	width: 28px !important;
	height: 28px !important;
	opacity: 1;
	border: 0 !important;
	border-radius: 50%;
}

/* ---- Agenda month grid ----
   Dolibarr hardcodes #666 on the day links. On a light canvas that is fine; on
   a dark one it is grey on near-black -- 2.7:1, the only contrast failure left
   in the sweep, and it hit every variant of the agenda month view. Taking the
   colour from the ramp lets each theme resolve it correctly. */
a.dayevent-aday, .dayevent-aday, td.cal_other_month a, .cal_past a {
	color: var(--c-ink-2) !important;
}
td.cal_other_month, td.cal_other_month a { opacity: 0.75; }


@media only screen and (max-width: 767px) {
	/* The leading-picto rule adds an 8px left margin to the field beside it.
	   Combined with Dolibarr's own width:100% utility that margin pushes the
	   input exactly 8px past its cell -- which is precisely the overflow the
	   sweep reported on every mobile create form. The gap is only meaningful
	   while the picto sits inline before the field; once rows stack it is not. */
	td span[class*="fa-"] + input,
	td span[class*="fa-"] + select,
	td span[class*="fa-"] + textarea,
	td span[class*="fa-"] + .select2-container,
	td span[class*="fa-"] ~ .select2-container,
	td span.pictofixedwidth ~ .select2-container,
	td img.pictofixedwidth + input,
	td img.pictofixedwidth + .select2-container,
	td span.pictofixedwidth + input,
	td span.pictofixedwidth + .select2-container {
		margin-<?php echo $left; ?>: 0;
	}
	/* Belt and braces: nothing in a cell may exceed the cell. */
	td > input, td > select, td > textarea, td > .select2-container {
		max-width: 100% !important;
		box-sizing: border-box;
	}
}

/* ---- Compact-theme hit targets ----
   The dense shell sizes its controls down far enough that row checkboxes and
   inline row links fall under the 24px minimum. The visual size is the point
   of the theme, so the glyph is left alone and only the clickable area grows,
   via a centred pseudo-element that costs no layout space. */
.dn-bar ~ * input.checkforselect, .dn-bar ~ * input.checkallactions,
body:has(.dn-bar) input.checkforselect, body:has(.dn-bar) input.checkallactions {
	position: relative;
}
body:has(.dn-bar) input.checkforselect::after,
body:has(.dn-bar) input.checkallactions::after,
body:has(.dn-bar) td a.refurl::after,
body:has(.dn-bar) td a.customer-back::after {
	content: "";
	position: absolute;
	top: 50%;
	<?php echo $left; ?>: 50%;
	width: 24px;
	height: 24px;
	transform: translate(-50%, -50%);
}
body:has(.dn-bar) td a.refurl, body:has(.dn-bar) td a.customer-back { position: relative; }


@media only screen and (max-width: 767px) {
	/* The colour picker puts a swatch and a text field in one cell, so the
	   field's own 100% width starts partway across and runs past the edge.
	   It has to leave room for the swatch beside it. */
	td input.colorpicker {
		width: auto !important;
		max-width: calc(100% - 64px) !important;
	}
}

/* ---- Compact-theme hit targets, sized rather than padded ----
   An ::after hit area enlarges what the pointer can reach but not the element
   box, so it neither shows up in an audit nor helps assistive tooling that
   reports control size. These get a real 24px minimum; the checkbox glyph is
   drawn by the UA at its own size, so only the box around it grows. */
body:has(.dn-bar) input.checkforselect,
body:has(.dn-bar) input.checkallactions,
body:has(.dn-bar) td a.refurl,
body:has(.dn-bar) td a.customer-back {
	min-width: 24px;
	min-height: 24px;
}
body:has(.dn-bar) td a.refurl,
body:has(.dn-bar) td a.customer-back {
	display: inline-flex;
	align-items: center;
}


/* ---- Font Awesome weight ----
   FA5 splits its faces by weight: solid is 900, regular 400, brands 400. The
   v4-style `.fa` alias carries no weight of its own, so it inherited the
   theme's 500 and the browser resolved it to the Regular face -- which in FA5
   *Free* contains almost no glyphs. The bug and print icons in the top bar
   therefore rendered as empty boxes while `.fas` icons beside them were fine.
   Brands and explicit regular icons keep their own weight. */
.fa, .fas, .fa-solid {
	font-family: "Font Awesome 5 Free", "Font Awesome 5 Pro", FontAwesome;
	font-weight: 900;
}
.fab, .fa-brands { font-family: "Font Awesome 5 Brands"; font-weight: 400; }
.far, .fa-regular { font-weight: 400; }

/* ---- Dashboard widget counts ----
   The label is an inline-block aligned to the middle while the count beside it
   is a plain inline on the baseline, so the number rode ~4px high against its
   own label. Both sit on one alignment. */
.info-box-content .info-box-more,
.info-box-content .info-box-more *,
.info-box-content a, .info-box-content .badge,
.info-box-content span.classfortooltip {
	vertical-align: middle;
}
.info-box-content .info-box-more { line-height: 1.5; }

/* ---- Setup landing page ----
   admin/index.php emits a bare full-width table with no border, background or
   radius, so the section links and their descriptions floated directly on the
   page background while every neighbouring screen is carded. */
/* Each entry is a <section class="setupsection">, shipped transparent with no
   border and no padding, so the links and their descriptions sat directly on
   the page background while every neighbouring screen is carded. The section
   is already cursorpointer -- Dolibarr makes the whole block clickable -- so it
   also gets a hover state to match the affordance it already claims. */
section.setupsection {
	display: block;
	margin-bottom: var(--sp-3);
	padding: var(--sp-4);
	background: var(--c-surface);
	border: 1px solid var(--c-border);
	border-radius: var(--r-lg);
	box-shadow: var(--sh-sm);
	transition: border-color var(--t), box-shadow var(--t);
}
section.setupsection:hover {
	border-color: var(--c-border-strong);
	box-shadow: var(--sh-md);
}
/* The heading link is inline and the description follows a <br>. Hiding that
   break ran the two together into one sentence -- the link has to become a
   block instead so the description sits under it. */
section.setupsection a {
	display: inline-block;
	margin-bottom: var(--sp-1);
	font-weight: 600;
}
section.setupsection a + br { display: none; }
section.setupsection > .opacitymedium,
section.setupsection > div { color: var(--c-ink-2); }
/* The picto leads the heading, so it belongs on the heading's line. */
section.setupsection > span[class*="fa-"],
section.setupsection > img { vertical-align: middle; margin-<?php echo $right; ?>: var(--sp-2); }

/* The page title on the setup index is a bare table rather than the usual
   header block, so it needs the same card as every other screen's title. */
div.fiche > table.centpercent.notopnoleftnoright {
	display: block;
	margin-bottom: var(--sp-4);
	padding: var(--sp-4) var(--sp-5);
	background: var(--c-surface);
	border: 1px solid var(--c-border);
	border-radius: var(--r-lg);
	box-shadow: var(--sh-sm);
}

/* The shells set a text weight on their tool clusters (.cmd-bar-tools and the
   rail/side equivalents). Those selectors are two classes deep, so they beat
   Font Awesome's own single-class `.fa, .fas { font-weight: 900 }` -- and the
   icons in that cluster silently lost their glyphs, because the weight is what
   selects the font face. Restated at a weight that wins for icons only; the
   surrounding text keeps 500. */
.cmd-bar-tools .atoplogin.fa, .cmd-bar-tools .atoplogin.fas,
.cmd-bar-tools .fa, .cmd-bar-tools .fas,
#cmd-bar-tools .fa, #cmd-bar-tools .fas,
.wb-rail-foot .fa, .wb-rail-foot .fas,
.ed-side-foot .fa, .ed-side-foot .fas,
.au-bar .fa, .au-bar .fas, .dn-bar .fa, .dn-bar .fas {
	font-weight: 900;
}


/* ---- Module card enable/disable control ----
   The toggle is the primary action on every module card and shipped at 17x15,
   smaller than the settings cog beside it and well under a comfortable target.
   It is sized up and given the accent when on, so the card's state is readable
   at a glance instead of requiring a squint at a grey glyph. */
.info-box-actions .fa-toggle-on,
.info-box-actions .fa-toggle-off,
div.box-flex-container.kanban .fa-toggle-on,
div.box-flex-container.kanban .fa-toggle-off {
	font-size: 1.6rem;
	line-height: 1;
	vertical-align: middle;
	opacity: 1;
}
div.box-flex-container.kanban .fa-toggle-on { color: var(--c-accent); }
div.box-flex-container.kanban .fa-toggle-off { color: var(--c-faint); }
.info-box-actions .fa-cog, div.box-flex-container.kanban .fa-cog {
	font-size: 1.05rem;
	vertical-align: middle;
}
div.box-flex-container.kanban .info-box-actions a,
div.box-flex-container.kanban .info-box-actions > div {
	display: inline-flex;
	align-items: center;
	min-height: 30px;
}

/* ---- Marketplace and developer tabs ----
   These panels embed vendor artwork at its native size -- a 626px logo and a
   2683px banner -- so the images dwarfed the text they illustrate and forced
   an inner scrollbar. Nothing here should exceed its column, and the logos
   read fine at a fraction of the size. */
div.tabBar img[src*="dolibarr_logo"],
div.tabBar img[src*="dolistore_logo"],
div.tabBar img[src*="logo_dolistore"] {
	width: auto;
	max-width: 200px;
	max-height: 56px;
	object-fit: contain;
}
div.tabBar img[src*="preferred_partner"],
div.tabBar img[src*="partner"] {
	width: 100%;
	max-width: 520px;
	height: auto;
}
/* Nothing in a settings panel may exceed its container. */
div.tabBar img, div.fiche img { max-width: 100%; height: auto; }
div.tabBar div[style*="overflow"], div.tabBar .div-table-responsive { max-width: 100%; }


/* ---- Page title row ----
   Dolibarr lays the title bar out as three table cells -- title, centre, right
   -- so a short piece of context like "Current language" was centred in the
   page rather than attached to the heading it qualifies, reading as unrelated
   floating text. Collapsing the title cell to its content lets the centre cell
   begin immediately after it, which is where that context belongs. The right
   cell keeps its own alignment. */
/* The first cell is only the picto; the heading lives in .col-title, and that
   is the one that has to shrink to its text for the centre cell to sit next
   to it. */
/* The title cell only shrinks when a centre cell exists to move up beside it.
   On a two-cell title row (picto + title) collapsing both made the table split
   the width between them, which pushed the heading into the middle of the
   page -- "Display" looked deliberately centred when it was just leftovers. */
/* Only collapse the first cell when the row actually has a centre cell to
   pull up beside the title. On a record card the FIRST cell IS the title --
   collapsing it to 1% squeezed it to 5px while its 227px heading overflowed
   into the next cell and sat underneath the view-mode buttons. */
/* Collapse the first cell only when it does NOT hold the heading. On the
   settings pages that cell is a picto and shrinking it pulls the title left;
   on a record card the first cell IS the title, and collapsing it squeezed a
   227px heading into 5px so it overflowed under the view-mode buttons.
   Presence of .titre is the reliable discriminator -- the class list is not,
   because both layouts mark their second cell .col-center. */
tr.toptitle > td:first-child:not(:has(.titre)):not(.col-title) {
	width: 1%;
	white-space: nowrap;
}
/* ...and only when something precedes it. On the settings pages .col-title
   follows a picto cell, so collapsing it pulls the centre cell up beside the
   heading. On a record card .col-title IS the first cell, and collapsing it
   crushed a 227px heading into 5px -- it then overflowed into the next cell
   and sat under the view-mode buttons. */
tr.toptitle:has(> td.col-center) > td.col-title:not(:first-child) {
	width: 1%;
	white-space: nowrap;
}
/* The cell also carries Dolibarr's `.center` utility, which is declared
   !important, so the alignment has to be forced rather than merely outranked. */
tr.toptitle > td.col-center,
tr.toptitle > td.col-center.center {
	text-align: <?php echo $left; ?> !important;
	padding-<?php echo $left; ?>: var(--sp-4);
}


/* ---- Version chip in the top bar ----
   The version sat 6px from the help icon as bare text with no padding or
   background, so it read as part of the help control rather than a separate
   piece of information -- and being a link, it offered no hover feedback. */
/* Scoped to the bar: `span.hideonsmartphone.small` is a generic Dolibarr
   utility that also wraps page content (the program version on the integrity
   report), and an unscoped margin there shunted that value off its cell. */
.cmd-bar a[href*="wikihelp"], .cmd-bar a[href*="doliwiki"],
.cmd-bar .atoplogin.login_block_elem_name,
.cmd-bar span.hideonsmartphone.small {
	margin-<?php echo $left; ?>: var(--sp-3);
}
/* The version is a span.aversion, not a link, so it needs the affordance
   spelled out: it carries a tooltip and nothing about bare grey text said so.
   A quiet chip separates it from the help icon and gives the hover a target. */
span.aversion {
	display: inline-flex;
	align-items: center;
	align-self: center;		/* centre in the bar, not on the text baseline */
	gap: var(--sp-1);
	padding: 0;
	background: none;		/* a filled pill read as a disabled button */
	/* --c-faint is the ramp step for separators and disabled marks; as real
	   words on the bar it measured 2.9:1, under AA. --c-muted is the lightest
	   step meant to carry text and still reads as secondary. */
	color: var(--c-muted);
	font-size: 0.6875rem;
	/* line-height 1.4 left the box a fraction taller than its glyphs, which
	   rounded the centre a pixel above its neighbours. */
	line-height: 1;
	letter-spacing: 0.01em;
	cursor: default;
	transition: color var(--t);
}
/* The tooltip wrapper is the flex item the bar actually positions. */
div.login_block:has(> span.aversion),
div.classfortooltip.login_block:has(span.aversion) {
	display: inline-flex;
	align-items: center;
	align-self: center;
}
span.aversion:hover { color: var(--c-muted); }
div.login_block:has(> span.aversion) { margin-<?php echo $left; ?>: var(--sp-2); }

/* ---- Account dropdown ----
   The panel scrolled sideways because long values (the professional IDs) never
   wrap, pushing content to 404px inside a 338px panel. A dropdown should never
   scroll horizontally -- the text wraps instead. */
.dropdown-menu .user-body {
	overflow-x: hidden !important;
	overflow-y: auto;
}
.dropdown-menu .user-body,
.dropdown-menu .user-body td,
.dropdown-menu .user-body div,
#topmenulogincompanyinfo, #topmenuloginmoreinfo {
	overflow-wrap: anywhere;
	word-break: break-word;
	max-width: 100%;
}
#topmenulogincompanyinfo table, #topmenuloginmoreinfo table { width: 100%; table-layout: fixed; }

/* Both detail blocks ship expanded, which made the panel 720px tall and buried
   the Card and Logout actions below the fold. They are disclosure sections --
   collapsed is the sensible default, and Dolibarr's own toggle still opens
   them because it animates from whatever state it finds. */
#topmenulogincompanyinfo, #topmenuloginmoreinfo { display: none; }

/* ---- Trailing row separator ----
   The final row of a detail table drew a separator with nothing beneath it,
   leaving a rule hanging across the bottom of the card. */
div.tabBar table.border > tbody > tr:last-child,
div.tabBar table.tableforfield > tbody > tr:last-child,
table.border:not(.liste) > tbody > tr:last-child,
table.tableforfield:not(.liste) > tbody > tr:last-child {
	border-bottom: 0;
}
div.tabBar table.border > tbody > tr:last-child > td,
table.tableforfield:not(.liste) > tbody > tr:last-child > td {
	border-bottom: 0;
}


/* ---- Save/notification toast (jNotify) ----
   Dolibarr raises these through the jNotify plugin, whose stock container is a
   full-bleed band pinned across the top of the window -- it covered the search
   field and the account menu, and at that size a four-word confirmation read
   like a page-level takeover. Constrained to a toast in the corner, above the
   fixed chrome but not spanning it. */
.jnotify-container {
	position: fixed;
	top: 68px;
	<?php echo $right; ?>: var(--sp-4);
	<?php echo $left; ?>: auto;
	width: auto;
	max-width: min(420px, calc(100vw - 2 * var(--sp-4)));
	z-index: 2000;
	text-align: <?php echo $left; ?>;
}
.jnotify-notification {
	display: flex;
	align-items: flex-start;
	gap: var(--sp-2);
	margin-bottom: var(--sp-2);
	padding: var(--sp-3) var(--sp-4);
	background: var(--c-surface);
	border: 1px solid var(--c-border);
	border-<?php echo $left; ?>: 3px solid var(--c-success);
	border-radius: var(--r);
	box-shadow: var(--sh-lg);
	color: var(--c-ink);
	font-size: 0.8125rem;
	line-height: 1.5;
}
.jnotify-notification-warning { border-<?php echo $left; ?>-color: var(--c-warning); }
.jnotify-notification-error { border-<?php echo $left; ?>-color: var(--c-danger); }
.jnotify-background { background: none !important; opacity: 1 !important; }
.jnotify-message { flex: 1 1 auto; min-width: 0; overflow-wrap: anywhere; }
.jnotify-close {
	flex: 0 0 auto;
	width: 20px; height: 20px;
	line-height: 18px;
	text-align: center;
	border-radius: var(--r-sm);
	color: var(--c-faint);
	opacity: 1;
}
.jnotify-close:hover { background: var(--c-sunken); color: var(--c-ink); }

/* ---- Trailing info icon ----
   These sit after a value and were dropping below its centre, because an
   icon's glyph box and a text run do not share a baseline. Middle alignment
   plus a matched line-height puts them on one line. */
td .classfortooltip[class*="fa-"], td span[class*="fa-info"],
td .classfortooltip > span[class*="fa-"],
span.classfortooltip.valignmiddle {
	vertical-align: middle;
	line-height: 1;
	position: relative;
	top: -1px;
}

/* ---- Account dropdown disclosures ----
   With both sections collapsed the two headers were left 33px apart, a gap
   inherited from the expanded layout that read as a missing block. */
#topmenulogincompanyinfo-btn, #topmenuloginmoreinfo-btn {
	display: block;
	margin: 0;
	padding: var(--sp-2) 0;
	line-height: 1.4;
}
.dropdown-menu .user-body > br { display: none; }
.dropdown-menu .user-body hr { margin: var(--sp-2) 0; }

/* Optical alignment: the context beside a large heading is much smaller type,
   so centring the two boxes leaves the small text sitting visually high
   against the heading's baseline. A small nudge settles it. */
tr.toptitle > td.col-center { padding-top: 6px; }


/* ---- "Default: <value>" hint rows ----
   These cells mix a colour-picker glyph, a label, a monospace-ish value and a
   tooltip icon. Each is a different element type with its own box, so they
   settled at four different heights -- some riding above the text, some below.
   One flex line puts them on a shared centre regardless of what each is. */
td:has(> .colorpicker), td.nowraponall:has(span[class*="fa-"]),
div.tabBar td:has(> input.colorpicker) {
	white-space: nowrap;
}
div.tabBar td > input.colorpicker ~ *,
div.tabBar td > .colorpicker ~ * { vertical-align: middle; }
div.tabBar table td span[class*="fa-"],
div.tabBar table td img.pictofixedwidth,
div.tabBar table td .classfortooltip {
	vertical-align: middle;
}
/* The value and its label share one baseline box. */
div.tabBar table td b, div.tabBar table td strong { vertical-align: middle; line-height: 1.4; }


/* ---- Toggle controls, everywhere ----
   Only the module-card toggles were sized up earlier; the same glyph is used
   throughout the settings screens and was still rendering at 21x11 -- the
   smallest interactive element on the page, and usually the only control in
   its row. Sized once, globally, so a switch looks like a switch wherever it
   appears. */
span[class*="fa-toggle-on"], span[class*="fa-toggle-off"],
a > span[class*="fa-toggle"], td span[class*="fa-toggle"] {
	font-size: 1.6rem !important;
	line-height: 1;
	vertical-align: middle;
	opacity: 1;
}
span[class*="fa-toggle-on"] { color: var(--c-accent); }
span[class*="fa-toggle-off"] { color: var(--c-faint); }
a:hover > span[class*="fa-toggle-off"] { color: var(--c-muted); }

/* ---- Record banner ----
   .arearef carries a bottom rule, but the portrait beside it is taller than
   the block, so the line ran straight through the photo and the status badge
   instead of sitting under them. The separation belongs below the whole
   banner, not through its middle. */
div.arearef.heightref {
	border-bottom: 0 !important;
	display: flex;
	align-items: center;
	gap: var(--sp-3);
	min-height: 92px;
}
div.arearef.heightref + * { border-top: 1px solid var(--c-hairline); padding-top: var(--sp-3); }

/* The colour-picker pencil sits before the "Default:" text and was riding
   above it -- an icon box and a text run have different baselines. */
td input.colorpicker + span, td .colorpicker,
td span[class*="fa-pencil"], td span[class*="fa-paint"] {
	vertical-align: middle;
	line-height: 1;
}


/* ---- Restore Dolibarr's width utilities ----
   The base input rule matches by excluding ten input types. Every :not()
   carries the specificity of the selector inside it, so that one rule scores
   (0,10,1) and quietly outranked every .maxwidthNN utility in the codebase --
   which is why a field meant to hold "7" was rendering 239px wide. The caps
   are restated here so the utilities mean what they say; the settings-form
   rule above is more specific still and keeps its wide measure where a full
   sentence is expected. */
input.maxwidth50, select.maxwidth50, .maxwidth50 { max-width: 50px !important; }
input.maxwidth75, select.maxwidth75, .maxwidth75 { max-width: 75px !important; }
input.maxwidth100, select.maxwidth100 { max-width: 100px !important; }
input.maxwidth125, select.maxwidth125 { max-width: 125px !important; }
input.maxwidth150, select.maxwidth150 { max-width: 150px !important; }
input.maxwidth200, select.maxwidth200 { max-width: 200px !important; }

/* A number field is sized by the count it holds, not by its column. */
input[type="number"] {
	width: auto;
	min-width: 72px;
	text-align: <?php echo $right; ?>;
}


/* ---- Leading pictos in link lists (dictionaries, setup indexes) ----
   Dolibarr emits the glyph as a bare span before the link with no spacing of
   its own. Narrow glyphs ($, document) happen to leave a gap; wider ones
   (city, map, people) fill their box and touch the label -- the icon and the
   first letter ran together. A fixed box makes the spacing independent of
   which glyph lands there, so every row starts on the same grid. */
/* Not scoped to div.tabBar: the dictionary index renders its table inside
   .div-table-responsive-no-min instead, so a tabBar-only rule missed exactly
   the page this was reported on. Any leading picto inside a table-cell link
   gets the same box. */
table td > span[class*="fa-"]:first-child,
table td > a > span[class*="fa-"]:first-child,
table td > a > img.pictofixedwidth:first-child,
table td > span.pictofixedwidth:first-child {
	display: inline-block;
	width: 1.25em;
	min-width: 1.25em;
	margin-<?php echo $right; ?>: var(--sp-2);
	text-align: center;
	vertical-align: middle;
	flex: 0 0 auto;
}


/* ---- Report / diagnostic pages (security, PHP setup, integrity, SMS) ----
   These pages alternate a one-row title table with a bare <div class="divsection">
   holding the actual content. The title got the card treatment while the
   content it introduces stayed transparent, so every section read as a
   floating heading followed by unstyled text. Heading and body are joined into
   a single card: the title loses its bottom corners and border, the body picks
   up the matching bottom half. */
div.fiche > table.centpercent.notopnoleftnoright:has(+ div.divsection) {
	margin-bottom: 0;
	border-bottom: 0;
	border-bottom-<?php echo $left; ?>-radius: 0;
	border-bottom-<?php echo $right; ?>-radius: 0;
	box-shadow: none;
}
div.fiche > div.divsection {
	margin-bottom: var(--sp-4);
	padding: var(--sp-4) var(--sp-5);
	background: var(--c-surface);
	border: 1px solid var(--c-border);
	border-top: 0;
	border-radius: 0 0 var(--r-lg) var(--r-lg);
	box-shadow: var(--sh-sm);
	overflow-wrap: anywhere;
}
/* A settings form that sits straight in .fiche gets the same shape. */
div.fiche > table.noborder.centpercent {
	background: var(--c-surface);
	border: 1px solid var(--c-border);
	border-radius: var(--r-lg);
	box-shadow: var(--sh-sm);
	border-collapse: separate;
	border-spacing: 0;
	overflow: hidden;
}
/* Collapse the run of empty <br> these pages use for vertical rhythm; the
   cards carry their own spacing now and the breaks just add dead height. */
div.fiche > br + br { display: none; }


/* ---- Module card icon alignment ----
   The module-board icon rule and the dashboard-widget icon rule both compute
   to the same specificity, so the dashboard one -- written later and correct
   for a card whose content is already inset -- won on both. Module cards ended
   up with padding-left:0, dropping the glyph onto the card's left border while
   its title stayed inset 16px. Stated one level more specific so the board
   keeps its own inset, and the glyph is sized to hold its own against the
   title rather than reading as a stray mark. */
div.box-flex-container.kanban > .box-flex-item .info-box-icon {
	padding: var(--sp-3) var(--sp-4) 0;
	justify-content: flex-start;
}
div.box-flex-container.kanban > .box-flex-item .info-box-icon > span,
div.box-flex-container.kanban > .box-flex-item .info-box-icon > i,
div.box-flex-container.kanban > .box-flex-item .info-box-icon > img {
	font-size: 1.15rem;
	line-height: 1;
	width: auto;
	min-width: 0;
	margin: 0;
}


/* The version wrapper carries .login_block_elem, the same class as the bug,
   print and help buttons beside it, so the hover background meant for those
   controls painted a grey pill behind a piece of static text. It is a label
   with a tooltip, not a button -- it gets no press affordance. */
.cmd-bar-tools .login_block_elem:has(> span.aversion):hover,
#cmd-bar-tools .login_block_elem:has(> span.aversion):hover,
.wb-rail-foot .login_block_elem:has(> span.aversion):hover,
.ed-side-foot .login_block_elem:has(> span.aversion):hover,
.au-bar .login_block_elem:has(> span.aversion):hover,
.dn-bar .login_block_elem:has(> span.aversion):hover {
	background: none;
	border-radius: 0;
}


/* ---- Trailing status icons ----
   A warning or error glyph emitted straight after a value has no spacing of
   its own, so it collided with the last character -- "utf8mb4_unicode_ci" ran
   into its own warning triangle. The earlier rule only covered the info icon,
   which is a different class, so every other status glyph still touched.
   Keyed off the picto* classes Dolibarr uses for all of them. */
td span[class*="pictowarning"], td span[class*="pictoerror"],
td span[class*="pictodanger"], td span[class*="pictoinfo"],
td span.fa-exclamation-triangle, td span.fa-exclamation-circle,
td span.fa-times-circle, td span.fa-check-circle,
td img[src*="warning"], td img[src*="error"] {
	margin-<?php echo $left; ?>: var(--sp-2);
	vertical-align: middle;
}
/* No :first-child reset here. :first-child counts elements only, so a glyph
   that FOLLOWS a text value still matches it -- the reset cancelled the very
   margin above on the one case it was meant to protect. The leading-picto rule
   uses margin-right, so the two never collide anyway. */


/* ---- Date picker trigger ----
   The calendar button is pulled onto the field by a -4px margin, so the glyph
   sat on the input's border instead of beside it. */
img.ui-datepicker-trigger, .ui-datepicker-trigger,
button.dpInvisibleButtons, .dpInvisibleButtons {
	margin-<?php echo $left; ?>: var(--sp-2) !important;
	vertical-align: middle;
}

/* ---- Record banner ----
   Two problems, both from the banner being laid out as flex.

   1. "Back to list" lives in .pagination, which Dolibarr floats right in its
      own block layout. As a flex item it simply took its source position --
      first -- and stuck to the left edge above the record name. Pushed back to
      the end with an auto margin rather than a float, which flex ignores.
   2. The logo column is a floated sibling that sits outside the banner, so it
      started at the card's edge (x=3) while every other element began at the
      content inset (x=33) -- the icon looked detached from the record it
      belongs to. */
div.arearef {
	align-items: flex-start;
	flex-wrap: wrap;
}
/* Pinned, not reordered. The wrapper around .pagination also holds the picto
   and the record title, so pushing that wrapper to the end shoved the entire
   banner to the right edge. Taking only the pagination out of flow puts "Back
   to list" in the corner and leaves everything else where it was. */
div.arearef div.pagination.paginationref {
	position: absolute;
	top: 0;
	<?php echo $right; ?>: 0;
	margin: 0;
	width: auto;
	text-align: <?php echo $right; ?>;
	white-space: nowrap;
}
div.tabBar > div.inline-block.floatleft:has(.divforspanimg),
div.tabBar > div.floatleft:has(> .divforspanimg) {
	margin-<?php echo $left; ?>: var(--sp-5);
}
/* The banner picto should share the record title's baseline row. */
div.divforspanimg { display: flex; align-items: center; }


/* ---- Leading pictos inside a wrapper ----
   Some cells wrap their contents in a div before the picto (the export wizard's
   dataset column, among others), so a `td > span:first-child` rule never
   matched and the glyph ran straight into its label. Scoped to a wrapper
   INSIDE a table cell so the settings-form picto gutter -- where the icon is a
   direct child of the td and its spacing is already accounted for in the cell
   padding -- is left alone. */
table td div > span[class*="fa-"]:first-child,
table td div > img.pictofixedwidth:first-child,
table td label > span[class*="fa-"]:first-child {
	margin-<?php echo $right; ?>: var(--sp-2);
	vertical-align: middle;
}


/* ---- Agenda month grid ----
   The month view is an auto-layout table, so a single event with a long title
   stretched its own weekday to 409px while the other six sat near 110px --
   a calendar whose columns change width based on what happens to be in them.
   Fixed layout makes every day equal; the week-number column keeps its own
   narrow width, and event text wraps instead of pushing the column out. */
table.cal_pannel {
	table-layout: fixed;
	width: 100%;
}
table.cal_pannel > tbody > tr > td:first-child,
table.cal_pannel > tbody > tr > th:first-child {
	width: 48px;
}
table.cal_pannel td, table.cal_pannel th { overflow-wrap: anywhere; }
table.cal_pannel .cal_event, table.cal_pannel .cal_event * {
	max-width: 100%;
	white-space: normal;
	overflow-wrap: anywhere;
}


/* ---- Fields that follow a leading picto ----
   width:100% is 100% of the CELL, but a picto row also spends 19px on the
   glyph and 8px on the gap -- so the field overflowed and dropped to the next
   line, leaving the barcode input hanging below its own icon while every other
   row stayed on one line. Those 27px have to come off the field's width.
   The selector repeats the width rule's :not() chain so it can outrank it;
   a shorter selector loses no matter where it sits in the file. */
div.tabBar table.border:not(.liste) td > span[class*="fa-"] + input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]):not([type="number"]):not(.button):not([class~="width25"]):not([class~="width50"]):not([class~="width75"]):not([class~="width100"]):not([class~="width125"]):not([class~="maxwidth50"]):not([class~="maxwidth75"]):not([class~="maxwidth100"]),
div.tabBar table.editmode:not(.liste) td > span[class*="fa-"] + input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]):not([type="number"]):not(.button):not([class~="width25"]):not([class~="width50"]):not([class~="width75"]):not([class~="width100"]):not([class~="width125"]):not([class~="maxwidth50"]):not([class~="maxwidth75"]):not([class~="maxwidth100"]),
div.tabBar table.border:not(.liste) td > span[class*="fa-"] + textarea,
div.tabBar table.editmode:not(.liste) td > span[class*="fa-"] + textarea,
div.tabBar table.border:not(.liste) td > span[class*="fa-"] ~ .select2-container,
div.tabBar table.editmode:not(.liste) td > span[class*="fa-"] ~ .select2-container {
	width: 100% !important;	/* the picto is out of flow; nothing to subtract */
}
@media only screen and (max-width: 767px) {
	/* Rows stack on narrow screens; the picto no longer shares the line. */
	div.tabBar table td > span[class*="fa-"] + input,
	div.tabBar table td > span[class*="fa-"] + textarea,
	div.tabBar table td > span[class*="fa-"] ~ .select2-container {
		width: 100% !important;
	}
}


/* ---- Contrast: muted text that still has to be read ----
   Three roles were all painted in --c-muted and measured 2.6-2.9:1 against
   their own backgrounds: disabled action buttons, select2 placeholders, and
   the warning banner's body. Disabled controls are exempt from WCAG 1.4.3, but
   2.6:1 is unreadable rather than merely quiet -- a user cannot tell what the
   action they cannot take actually is. Each moves to the darkest tone that
   still reads as secondary. */
/* .textbutton wraps the label of EVERY action button, enabled ones included,
   so darkening it wholesale put near-black text on the filled accent primary
   ("Modify" fell to 1.59:1). Only the refused/disabled variant is retouched. */
a.butActionRefused, a.butActionRefused.classfortooltip,
a.butActionRefused span.textbutton, .butActionRefused span.textbutton {
	color: color-mix(in srgb, var(--c-muted) 55%, var(--c-ink)) !important;
}
/* select2 sets the placeholder colour from its own default theme, which is
   two classes deep -- a bare selector never reaches it. */
.select2-container--default .select2-selection--single .select2-selection__placeholder,
.select2-container--default .select2-selection__placeholder,
input::placeholder, textarea::placeholder {
	color: color-mix(in srgb, var(--c-muted) 70%, var(--c-ink)) !important;
	opacity: 1;
}
div.warning, div.warning a, div.warning span {
	color: color-mix(in srgb, var(--c-warning) 45%, var(--c-ink)) !important;
}

/* ---- Tap targets ----
   Row checkboxes shipped at 16px and the nav carets at 18x22 -- both under any
   reasonable minimum, and the checkbox is the control a user hits most often
   on a list. Sized up without disturbing the row rhythm. */
input.checkforselect, input.checkallactions,
table.liste input[type="checkbox"], .liste input[type="checkbox"] {
	width: 18px;
	height: 18px;
	cursor: pointer;
}
button.ts-caret, .ts-caret {
	min-width: 24px;
	min-height: 24px;
	display: inline-flex;
	align-items: center;
	justify-content: center;
}
button.liste_titre, button.button_search, button.button_removefilter {
	min-width: 30px;
	min-height: 26px;
}
/* Inline icon links in list rows get a real box without changing the glyph. */
td a.customer-back, td a.supplier-back {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 22px;
	min-height: 22px;
}


/* The leading-picto gap is a margin on the FIELD, sized for a glyph sharing
   its line. In these settings tables the picto is now absolutely positioned in
   the cell's gutter, so that margin is 8px of nothing -- and it pushed picto
   rows 8px right of plain ones. Removed here only; elsewhere the picto is
   still inline and still needs it. */
div.tabBar table.border:not(.liste):not(.tableforfield) > tbody > tr > td:nth-child(even) > span[class*="fa-"] + input,
div.tabBar table.editmode:not(.liste):not(.tableforfield) > tbody > tr > td:nth-child(even) > span[class*="fa-"] + input,
div.tabBar table.border:not(.liste):not(.tableforfield) > tbody > tr > td:nth-child(even) > span[class*="fa-"] + textarea,
div.tabBar table.editmode:not(.liste):not(.tableforfield) > tbody > tr > td:nth-child(even) > span[class*="fa-"] + textarea,
div.tabBar table.border:not(.liste):not(.tableforfield) > tbody > tr > td:nth-child(even) > span[class*="fa-"] ~ .select2-container,
div.tabBar table.editmode:not(.liste):not(.tableforfield) > tbody > tr > td:nth-child(even) > span[class*="fa-"] ~ .select2-container,
div.tabBar table.border:not(.liste):not(.tableforfield) > tbody > tr > td:nth-child(even) > span[class*="fa-"] + select,
div.tabBar table.editmode:not(.liste):not(.tableforfield) > tbody > tr > td:nth-child(even) > span[class*="fa-"] + select {
	margin-<?php echo $left; ?>: 0;
}


/* ---- Status icons in report prose ----
   The diagnostic pages emit a check or warning glyph immediately before each
   line of text, with no wrapper and no spacing, so every entry read as
   "!PHP session.use_strict_mode". These are not first-child (a <br> or text
   precedes them) and not in a table cell, so none of the earlier picto rules
   reached them. */
div.divsection span[class*="picto"],
div.divsection span[class*="fa-check"],
div.divsection span[class*="fa-exclamation"],
div.divsection span[class*="fa-times"],
div.divsection span[class*="fa-info"] {
	margin-<?php echo $right; ?>: var(--sp-2);
	vertical-align: middle;
}
/* These lines are a list of checks; a little leading makes them scannable. */
div.divsection { line-height: 1.7; }


/* ---- Room for a trailing icon or action ----
   A field at width:100% consumes the whole cell, so an icon after it -- a help
   tooltip, or the regenerate control on a password/API-token field -- had
   nowhere to go and dropped to its own line, stranded under the control it
   belongs to. Only cells that actually carry one give the space back.

   The selector repeats the width rule's :not() chain. Without it this scores
   far lower than that rule's fourteen clauses and never applies, however much
   !important it carries. */
div.tabBar table.border:not(.liste):not(.tableforfield) > tbody > tr > td:nth-child(even):has(> span[class*="fa-info"], > span.linkobject, > span[class*="fa-redo"], > a.linkobject) > input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]):not([type="number"]):not(.button):not([class~="width25"]):not([class~="width50"]):not([class~="width75"]):not([class~="width100"]):not([class~="width125"]):not([class~="maxwidth50"]):not([class~="maxwidth75"]):not([class~="maxwidth100"]),
div.tabBar table.editmode:not(.liste):not(.tableforfield) > tbody > tr > td:nth-child(even):has(> span[class*="fa-info"], > span.linkobject, > span[class*="fa-redo"], > a.linkobject) > input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]):not([type="number"]):not(.button):not([class~="width25"]):not([class~="width50"]):not([class~="width75"]):not([class~="width100"]):not([class~="width125"]):not([class~="maxwidth50"]):not([class~="maxwidth75"]):not([class~="maxwidth100"]),
div.tabBar table.border:not(.liste):not(.tableforfield) > tbody > tr > td:nth-child(even):has(> span[class*="fa-info"], > span.linkobject, > span[class*="fa-redo"], > a.linkobject) > textarea,
div.tabBar table.editmode:not(.liste):not(.tableforfield) > tbody > tr > td:nth-child(even):has(> span[class*="fa-info"], > span.linkobject, > span[class*="fa-redo"], > a.linkobject) > textarea,
div.tabBar table.border:not(.liste):not(.tableforfield) > tbody > tr > td:nth-child(even):has(> span[class*="fa-info"], > span.linkobject, > span[class*="fa-redo"], > a.linkobject) > .select2-container,
div.tabBar table.editmode:not(.liste):not(.tableforfield) > tbody > tr > td:nth-child(even):has(> span[class*="fa-info"], > span.linkobject, > span[class*="fa-redo"], > a.linkobject) > .select2-container {
	width: calc(100% - 34px) !important;
}


/* ---- Dashboard card consistency ----
   Two things broke the set. Dolibarr gives the weather/global-view widget a
   filled icon strip (.info-box-weather) while every other widget's is
   transparent, so one card in the grid wore a grey band nothing else had. And
   the icon block reserved 40px for a 17px glyph, leaving a 49px canyon between
   the icon and the title it belongs to. */
div.box-flex-container .info-box-icon,
div.box-flex-container .info-box-icon[class*="bg-infobox"],
div.box-flex-container .info-box-weather .info-box-icon,
div.box-flex-container .info-box[class*="weather"] .info-box-icon {
	background: transparent !important;
	/* Dolibarr fixes span.info-box-icon at 40px for a 17px glyph. !important
	   rather than specificity, because the stock rule wins on source order in
	   the compiled sheet regardless of weight. */
	/* `auto` still resolved to 40px through flex sizing, so the box is given an
	   explicit compact height instead of arguing with the cascade. */
	height: 26px !important;
	min-height: 0;
	padding-top: var(--sp-2) !important;
	line-height: 1;
}
div.box-flex-container > .box-flex-item .info-box-content,
div.box-flex-container > .box-flex-item-with-margin .info-box-content {
	padding-top: var(--sp-2);
}
/* The create ("+") affordance rides with the icon rather than floating. */
div.box-flex-container .info-box-icon a.info-box-createlink {
	margin-<?php echo $left; ?>: var(--sp-1);
	display: inline-flex;
	align-items: center;
}


/* ---- Role badges in list cells ----
   The customer/vendor/prospect markers are emitted as bare adjacent links
   ("C" then "V" with nothing between), so they read as one two-letter token
   rather than two separate roles. They are distinct affordances -- each links
   somewhere different -- and need to look it. */
td a.customer-back, td a.vendor-back, td a.prospect-back,
td a.customer-back + a, td a.vendor-back + a {
	margin-<?php echo $right; ?>: var(--sp-1);
	border-radius: var(--r-sm);
	font-size: 0.6875rem;
	font-weight: 650;
	line-height: 1;
}
td a.customer-back:last-child, td a.vendor-back:last-child { margin-<?php echo $right; ?>: 0; }
td a.customer-back:hover, td a.vendor-back:hover, td a.prospect-back:hover {
	background: var(--c-sunken);
}

/* ---- Empty states ----
   "No sales order recorded", "No upcoming events" and friends were emitted as
   bare text flush against the cell edge, indistinguishable from data. An empty
   result is information: centred, muted and given room so a user reads it as
   an answer rather than a rendering failure. */
td.opacitymedium:only-child,
table.liste tr td[colspan]:only-child,
.info-box-content .opacitymedium:only-child,
div.tabBar table td.center.opacitymedium {
	padding: var(--sp-4) var(--sp-3);
	color: var(--c-muted);
	text-align: center;
	font-style: normal;
}

/* ---- Table header consistency ----
   Section headers ranged 33-55px across the same page because some carry a
   control and some only a label. A shared minimum keeps a column of cards
   reading as one system. */
tr.liste_titre > th, tr.liste_titre > td,
div.tabBar table > tbody > tr:first-child > td.liste_titre {
	height: auto;
	min-height: 0;
	padding-top: var(--sp-3);
	padding-bottom: var(--sp-3);
	vertical-align: middle;
}


/* ---- Label -> tooltip icon ----
   Dolibarr appends the help icon straight after a field label with no spacing,
   so "Stock management" and its ⓘ ran together as one word-shape. The earlier
   trailing-icon rule only covered the value side of the row; this is the label
   side, where the icon is wrapped in .classfortooltip rather than emitted bare.
   Applies wherever a tooltip wrapper follows content, not only in forms. */
td > span.classfortooltip:not(:first-child),
td > a.classfortooltip:not(:first-child),
label > span.classfortooltip:not(:first-child),
td.titlefield span.classfortooltip,
td.titlefieldcreate span.classfortooltip,
div.tabBar table td:nth-child(odd) > span.classfortooltip {
	margin-<?php echo $left; ?>: var(--sp-2);
	vertical-align: middle;
}
/* The wrapper is inline; give it a real box so the glyph inside centres. */
span.classfortooltip > span[class*="fa-"],
a.classfortooltip > span[class*="fa-"] { vertical-align: middle; }


/* ---- Controls inside table headers ----
   A header cell holding a select ("Doc template") stood 61px against 41px for
   the sixteen plain headers beside it, so the header band stepped up in one
   place. The control is compacted to the header's own rhythm instead of
   setting it. */
tr.liste_titre select, tr.liste_titre .select2-container,
tr.liste_titre input:not([type="checkbox"]):not([type="radio"]) {
	height: 26px;
	min-height: 0;
	font-size: 0.75rem;
	vertical-align: middle;
}
tr.liste_titre .select2-container--default .select2-selection--single { height: 26px; }
tr.liste_titre .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 24px; }


/* ---- Paired inline actions ("All / None", "Expand all / Undo expand") ----
   These sit either side of a "/" separator with no padding, so the two links
   and the slash read as one run of text rather than two choices. Padding on
   the link keeps the separator visible and gives each a real target. */
a.addexpandedmodulesinparamlist, a.removeexpandedmodulesinparamlist,
td.liste_titre a.commonlink, .liste_titre a.reposition {
	display: inline-block;
	padding: 2px var(--sp-1);
	border-radius: var(--r-sm);
	line-height: 1.4;
}
a.addexpandedmodulesinparamlist:hover, a.removeexpandedmodulesinparamlist:hover,
td.liste_titre a.commonlink:hover { background: var(--c-sunken); }


/* NOTE: an attempt to return the picto to the flow on mobile was reverted --
   it collided with the mobile width rule above and took picto-row wrapping
   from 1 case to 6. The absolute gutter is kept at every width. */


/* ---- Counts and "more" links in card headers ----
   Dolibarr emits area-page card headers as:

       <span>Draft orders</span><a href="..."><span class="badge">0</span></a>

   The badge class never picked up badge styling inside a header cell, so the
   count rendered as bare 12px text jammed against the title -- and the "..."
   link that opens the full list read as punctuation rather than an action.
   The header becomes a row: title left, count or "more" right, as a real pill
   with a real target. */
tr.liste_titre > th:has(> a > .badge),
tr.liste_titre > td:has(> a > .badge),
th:has(> span.valignmiddle + a > .badge) {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: var(--sp-2);
	/* Some of these header rows carry an empty trailing cell. Without this the
	   count stops at its own cell's edge and floats mid-header instead of
	   sitting at the card's right edge; claiming the width squeezes the empty
	   cell to nothing. */
	width: 100%;
}
/* An empty trailing header cell has no job once the title cell spans. */
tr.liste_titre > th:has(> a > .badge) + th:empty,
tr.liste_titre > td:has(> a > .badge) + td:empty { padding: 0; width: 0; }
tr.liste_titre th .badge, tr.liste_titre td .badge,
th > a > .badge, td.liste_titre > a > .badge {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 22px;
	height: 20px;
	padding: 0 var(--sp-2);
	background: var(--c-sunken);
	border: 1px solid var(--c-border);
	border-radius: 999px;
	color: var(--c-ink-2);
	font-size: 0.6875rem;
	font-weight: 650;
	line-height: 1;
}
tr.liste_titre th > a, tr.liste_titre td > a {
	display: inline-flex;
	align-items: center;
	min-height: 22px;
}
tr.liste_titre th > a:hover .badge, tr.liste_titre td > a:hover .badge {
	background: var(--c-accent-soft);
	border-color: color-mix(in srgb, var(--c-accent) 35%, transparent);
	color: var(--c-accent-ink);
}
/* A zero count is information, not an alert -- keep it quiet. */
tr.liste_titre th .badge:empty { display: none; }


/* ---- Action link following a widget ----
   Dolibarr appends a control (manage categories, reset, pick) straight after a
   multiselect or similar widget with no spacing, so the two touched and read
   as one object. The gap goes on the trailing action so it applies wherever
   the pattern appears, not just after multiselects. */
/* `~` not `+`: Dolibarr emits a <script> between the widget and its action,
   and the adjacent-sibling combinator skips text nodes but not elements, so
   `+` matched nothing here. */
td > [class*="multiselectarr"] ~ a,
td > .select2-container ~ a.cursorpointer,
td > .select2-container ~ a.reposition,
td > input ~ a.cursorpointer,
td > a.cursorpointer ~ a.cursorpointer {
	margin-<?php echo $left; ?>: var(--sp-2);
	display: inline-flex;
	align-items: center;
	min-height: 22px;
}


/* ---- Label / value baseline on record cards ----
   Dolibarr wraps a card label in a nested table when the field is editable
   (label on the left, inline-edit pencil on the right). That nested table has
   its own height, and the label cell carries 12px of top padding against the
   value cell's 8px -- so the label sat up to 16px below the value it names.
   Both cells centre on the row instead, which holds regardless of whether the
   label is bare text or a nested edit table. */
div.tabBar table.border:not(.liste) > tbody > tr > td,
div.tabBar table.tableforfield:not(.liste) > tbody > tr > td,
table.tableforfield:not(.liste) > tbody > tr > td {
	vertical-align: middle;
	padding-top: var(--sp-2);
	padding-bottom: var(--sp-2);
}
/* A tall control (editor, textarea, multi-select) should keep its label at the
   top -- centring it against 200px of editor looks unmoored. */
div.tabBar table > tbody > tr:has(textarea) > td,
div.tabBar table > tbody > tr:has(.cke) > td,
div.tabBar table > tbody > tr:has(select[multiple]) > td { vertical-align: top; }
/* The nested edit table must not add height of its own -- and it has to
   centre on the row like everything else. Its cells default to
   vertical-align: top, which left the label and its pencil 11px above the
   value they sit beside: four things on one row, on two different lines. */
div.tabBar td > table.nobordernopadding {
	margin: 0;
	height: 100%;
}
div.tabBar td > table.nobordernopadding > tbody,
div.tabBar td > table.nobordernopadding > tbody > tr { height: 100%; }
div.tabBar td > table.nobordernopadding > tbody > tr > td {
	padding: 0;
	vertical-align: middle;
}

/* ---- Long values in the account dropdown ----
   Professional IDs and tokens are unbroken strings with no spaces, so normal
   wrapping cannot split them and they ran under the panel edge. break-all is
   the only thing that breaks a run with no break opportunity. */
.dropdown-menu .user-body td,
.dropdown-menu .user-body div,
#topmenulogincompanyinfo td, #topmenuloginmoreinfo td {
	word-break: break-all;
	overflow-wrap: anywhere;
	white-space: normal;
	max-width: 100%;
}


/* ---- Inline edit affordance (the pencil) ----
   Dolibarr puts the label and its edit pencil in a nested table set to
   centpercent, so the pencil is pushed to the far right of a 320px label cell
   -- stranded mid-row, touching neither the label it edits nor the value it
   sits beside. Collapsing that table to its content puts the pencil directly
   after the label text, where it reads as belonging to it. */
div.tabBar td > table.nobordernopadding.centpercent,
div.tabBar td > table.centpercent.nobordernopadding {
	width: auto;
}
div.tabBar td > table.nobordernopadding a.editfielda,
/* `action=edit` appears in plenty of links that are not the pencil -- Setup >
   Display wraps each skin thumbnail in ihm.php?action=edit&theme=..., inside this
   exact tabBar/nobordernopadding context. Those links were therefore hidden at
   opacity 0 and only surfaced while the pointer was over them, so the skin chooser
   read as an empty band. The pencil is an icon-only link; anything wrapping an
   image is not it. */
div.tabBar td > table.nobordernopadding a[href*="action=edit"]:not(:has(img)) {
	margin-<?php echo $left; ?>: var(--sp-2);
	opacity: 0;
	transition: opacity var(--t);
	/* An inline anchor sits on the text baseline, which left the pencil 11px
	   above the row centre its label and value had settled on. A centred flex
	   box puts the glyph on the same line as everything else. */
	display: inline-flex;
	align-items: center;
	vertical-align: middle;
	line-height: 1;
}
div.tabBar td > table.nobordernopadding a[href*="action=edit"] span[class*="fa-"] {
	vertical-align: middle;
	line-height: 1;
}
/* Reveal on row hover or keyboard focus -- the affordance is available without
   being permanent visual noise on every row of the card. */
div.tabBar tr:hover td > table.nobordernopadding a.editfielda,
div.tabBar tr:hover td > table.nobordernopadding a[href*="action=edit"],
div.tabBar td > table.nobordernopadding a:focus-visible { opacity: 1; }

/* ---- Inline edit form ----
   The value cell can be as narrow as 240px in a four-column card, so a 190px
   control plus Save and Cancel could not fit on one line. They wrapped
   according to whatever width was left, which put Save beside the field and
   Cancel underneath it. The control takes its own line and the buttons stay
   together beneath it, at every width. */
/* Descendant, not child: Dolibarr wraps the inline-edit control in a <form>,
   so a `>` selector matched nothing and the control kept select2's inline
   width -- overflowing its cell and covering the card beside it. */
div.tabBar td:has(input[type="submit"]) .select2-container,
div.tabBar td:has(input[type="submit"]) select,
div.tabBar td:has(input[type="submit"]) input:not([type="submit"]):not([type="hidden"]):not([type="checkbox"]):not([type="radio"]) {
	display: block;
	width: 100% !important;
	max-width: 100% !important;
	margin-bottom: var(--sp-2);
}
div.tabBar td input[type="submit"] + input[type="submit"] {
	margin-<?php echo $left; ?>: var(--sp-2);
}


/* ---- Inline edit must not resize the card ----
   Entering edit mode swaps a short read-only value for a control plus Save and
   Cancel. Those need more room than the value did, so an auto-layout table
   simply widened the column -- on a two-card row that pushed the left card
   over the right one and clipped its labels.

   The card keeps its width and the form fits inside it: compact buttons, and a
   control that cannot exceed the column. */
div.fichehalfleft > table, div.fichehalfright > table,
div.fichehalfleft table.border, div.fichehalfright table.border,
div.fichehalfleft table.tableforfield, div.fichehalfright table.tableforfield {
	max-width: 100%;
	table-layout: fixed;
}
div.fichehalfleft table td, div.fichehalfright table td { overflow-wrap: anywhere; }
/* Compact enough that a control and both buttons fit a half-card column. */
div.tabBar td input[type="submit"].smallpaddingimp,
div.fichehalfleft td input[type="submit"],
div.fichehalfright td input[type="submit"] {
	padding: 4px var(--sp-2);
	font-size: 0.75rem;
	min-height: 26px;
}
div.fichehalfleft td .select2-container,
div.fichehalfright td .select2-container,
div.fichehalfleft td select, div.fichehalfright td select {
	max-width: 100% !important;
}


/* ---- select2 inside an inline-edit cell ----
   select2.inc.php puts a 190px min-width floor on these controls so a short
   select does not truncate its own value. In an inline-edit cell that floor is
   wrong: the cell can be 146px, and min-width beats max-width in the cascade,
   so the control rendered at 190px regardless of any cap -- overflowing the
   cell and covering the card beside it.

   The floor is released here only. Everywhere else it still applies. */
div.tabBar td:has(input[type="submit"]) .select2-container,
div.fichehalfleft td:has(input[type="submit"]) .select2-container,
div.fichehalfright td:has(input[type="submit"]) .select2-container,
td:has(> form input[type="submit"]) .select2-container {
	min-width: 0 !important;
	width: 100% !important;
	max-width: 100% !important;
}
td:has(input[type="submit"]) > form { width: 100%; }


/* ---- Leading picto on a record-card value ----
   The glyph carries its font's line box, which is taller than the text beside
   it, so with vertical-align:middle it still settled ~11px below the label,
   pencil and value that had all landed on the row's centre line. Collapsing
   its line box puts the fourth element on the same line as the other three. */
div.tabBar table.border td > span[class*="fa-"],
div.tabBar table.tableforfield td > span[class*="fa-"],
table.tableforfield td > span[class*="fa-"] {
	line-height: 1;
	vertical-align: middle;
}


/* ---- Linked-event tables inside a half-width card ----
   The "last N events" block carries six columns in a 549px half-card, so its
   table rendered 624px wide and overflowed: the title clipped mid-word and the
   "By" column gave a user name 68px once the avatar had taken its share --
   "SuperAdmin" became "Su...".

   The table is made to fit its card, and the budget is rebalanced: the two
   icon-only columns give up their padding, the title absorbs what is left, and
   the name column gets enough to show a real name. */
div.fichehalfleft .div-table-responsive table,
div.fichehalfright .div-table-responsive table,
div.fichehalfleft .div-table-responsive-no-min table,
div.fichehalfright .div-table-responsive-no-min table {
	width: 100%;
	max-width: 100%;
	/* width:100% alone cannot shrink a column below its content, so the table
	   still measured wider than the card. Fixed layout is what actually makes
	   the columns share the space available. */
	table-layout: fixed;
}
/* Share out that space rather than splitting it six ways. Under fixed layout
   an unspecified column takes an equal share, which gave a user name the same
   91px as an icon-only column. Stated on the first row's cells, whether they
   are th or td, and forced so the utility width classes on those cells cannot
   claim the space back. */
div.fichehalfleft .div-table-responsive tr:first-child > *:nth-child(1),
div.fichehalfleft .div-table-responsive-no-min tr:first-child > *:nth-child(1),
div.fichehalfright .div-table-responsive tr:first-child > *:nth-child(1) { width: 14% !important; }
div.fichehalfright .div-table-responsive-no-min tr:first-child > *:nth-child(1) { width: 14% !important; }
div.fichehalfleft .div-table-responsive tr:first-child > *:nth-child(2),
div.fichehalfleft .div-table-responsive-no-min tr:first-child > *:nth-child(2),
div.fichehalfright .div-table-responsive tr:first-child > *:nth-child(2) { width: 17% !important; }
div.fichehalfright .div-table-responsive-no-min tr:first-child > *:nth-child(2) { width: 17% !important; }
div.fichehalfleft .div-table-responsive tr:first-child > *:nth-child(3),
div.fichehalfleft .div-table-responsive-no-min tr:first-child > *:nth-child(3),
div.fichehalfright .div-table-responsive tr:first-child > *:nth-child(3) { width: 26% !important; }
div.fichehalfright .div-table-responsive-no-min tr:first-child > *:nth-child(3) { width: 26% !important; }
div.fichehalfleft .div-table-responsive tr:first-child > *:nth-child(4),
div.fichehalfleft .div-table-responsive-no-min tr:first-child > *:nth-child(4),
div.fichehalfright .div-table-responsive tr:first-child > *:nth-child(4) { width: 7% !important; }
div.fichehalfright .div-table-responsive-no-min tr:first-child > *:nth-child(4) { width: 7% !important; }
div.fichehalfleft .div-table-responsive tr:first-child > *:nth-child(5),
div.fichehalfleft .div-table-responsive-no-min tr:first-child > *:nth-child(5),
div.fichehalfright .div-table-responsive tr:first-child > *:nth-child(5) { width: 29% !important; }
div.fichehalfright .div-table-responsive-no-min tr:first-child > *:nth-child(5) { width: 29% !important; }
div.fichehalfleft .div-table-responsive tr:first-child > *:nth-child(6),
div.fichehalfleft .div-table-responsive-no-min tr:first-child > *:nth-child(6),
div.fichehalfright .div-table-responsive tr:first-child > *:nth-child(6) { width: 7% !important; }
div.fichehalfright .div-table-responsive-no-min tr:first-child > *:nth-child(6) { width: 7% !important; }
div.fichehalfleft .div-table-responsive td, div.fichehalfright .div-table-responsive td,
div.fichehalfleft .div-table-responsive th, div.fichehalfright .div-table-responsive th,
div.fichehalfleft .div-table-responsive-no-min td, div.fichehalfright .div-table-responsive-no-min td,
div.fichehalfleft .div-table-responsive-no-min th, div.fichehalfright .div-table-responsive-no-min th {
	overflow: hidden;
	text-overflow: ellipsis;
}
/* Icon-only columns do not need a text column's padding. */
div.fichehalfleft .div-table-responsive td:has(> a > span[class*="fa-"]:only-child),
div.fichehalfright .div-table-responsive td:has(> a > span[class*="fa-"]:only-child) {
	width: 1%;
	padding-<?php echo $left; ?>: var(--sp-1);
	padding-<?php echo $right; ?>: var(--sp-1);
}
/* Room for a name: the cap has to clear the avatar that shares the cell. */
div.fichehalfleft td.tdoverflowmax100, div.fichehalfright td.tdoverflowmax100 {
	max-width: 150px;
}
div.fichehalfleft td.tdoverflowmax100 a, div.fichehalfright td.tdoverflowmax100 a {
	max-width: none;
}


/* ---- Event timeline (messaging / "Events about this" tabs) ----
   ul.timeline was never styled, so it fell back to browser defaults: disc
   bullets, a 40px indent, and no card. Combined with an 80px user portrait
   dropped into a log row, the result read as broken markup rather than a
   history. Each entry becomes a card with a small avatar and a clear stamp. */
ul.timeline {
	list-style: none;
	margin: 0;
	padding: 0;
}
ul.timeline > li { list-style: none; position: relative; }

/* A date heading separates groups of events. */
ul.timeline > li.time-label {
	margin: var(--sp-4) 0 var(--sp-2);
	font-size: 0.6875rem;
	font-weight: 700;
	letter-spacing: 0.04em;
	text-transform: uppercase;
	color: var(--c-muted);
}
ul.timeline > li.time-label > span { background: none; padding: 0; }

/* Each event is a card. */
ul.timeline > li[class*="timeline-code"],
ul.timeline > li:not(.time-label) {
	margin-bottom: var(--sp-3);
	padding: var(--sp-3) var(--sp-4);
	background: var(--c-surface);
	border: 1px solid var(--c-border);
	border-radius: var(--r);
	box-shadow: var(--sh-sm);
}
ul.timeline .timeline-item { margin: 0; padding: 0; background: none; border: 0; box-shadow: none; }
ul.timeline .timeline-header { padding: 0; border: 0; font-weight: 600; }
ul.timeline .timeline-body { padding: var(--sp-2) 0 0; }

/* A log row needs a name and a stamp, not a portrait. */
ul.timeline img.userphoto, ul.timeline img.photouserphoto,
ul.timeline .userimg img, ul.timeline img[src*="user_anonymous"],
ul.timeline img[src*="nophoto"] {
	width: 24px !important;
	height: 24px !important;
	min-width: 0 !important;
	border-radius: 50%;
	vertical-align: middle;
	object-fit: cover;
}
ul.timeline .userimg, ul.timeline span.nopadding { display: inline-flex; align-items: center; }
/* The author name was truncating to "Super..." beside an 80px portrait. */
ul.timeline a.classfortooltip, ul.timeline .timeline-header a { max-width: none; }
ul.timeline [class*="tdoverflowmax"] { max-width: none !important; }


/* ---- Markup present in Dolibarr 22.x ----
   These three classes exist in 22.0.4's stock theme but not in 24.x, so they
   were never covered while developing against 24. Styling them keeps the theme
   complete on both versions; on 24 the rules are simply inert. */
.classfortooltiponclick { cursor: pointer; vertical-align: middle; }
.classfortooltiponclick > span[class*="fa-"] { vertical-align: middle; }
input.linputsearch, .linputsearch {
	height: 34px;
	padding: 0 var(--sp-3);
	background: var(--inputbackgroundcolor);
	border: 1px solid var(--inputbordercolor);
	border-radius: var(--r);
	color: var(--c-ink);
	font-family: inherit;
	font-size: <?php echo $fontsize; ?>;
}
input.linputsearch:focus, .linputsearch:focus {
	outline: none;
	border-color: var(--c-accent);
	box-shadow: 0 0 0 3px var(--c-accent-ring);
}
/* 22.x uses fa-question-circle where 24.x moved to a different glyph. */
span.fa-question-circle, .fa-question-circle { vertical-align: middle; line-height: 1; }


/* ---- Treeview plugin background ----
   jquery.treeview.css hardcodes `background-color: white` on the list. On a
   light theme that is invisible; on a dark one it punches a white block into
   the page and drags the muted link colour on top of it down to 2.9:1. The
   list takes the card's own surface instead. */
ul.treeview, ul.treeview ul, .treeview { background-color: transparent; }
ul.treeview a { color: var(--c-ink); }
ul.treeview .opacitymedium, ul.treeview span.small { color: var(--c-muted); opacity: 1; }


/* ---- Widget "more" links on the dashboard ----
   Same construction as the area-page header counts -- an anchor wrapping a
   .badge -- but emitted inside a div rather than a th, so the earlier rule
   never reached them and they stayed 12px of bare text. */
a:has(> span.badge), a.paddingleft:has(.badge) {
	display: inline-flex;
	align-items: center;
	min-height: 22px;
}
div[class*="tdoverflow"] > a > span.badge,
.info-box-content a > span.badge,
div.box a > span.badge {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 22px;
	height: 20px;
	padding: 0 var(--sp-2);
	background: var(--c-sunken);
	border: 1px solid var(--c-border);
	border-radius: 999px;
	color: var(--c-ink-2);
	font-size: 0.6875rem;
	font-weight: 650;
	line-height: 1;
}
div[class*="tdoverflow"] > a:hover > span.badge,
.info-box-content a:hover > span.badge,
div.box a:hover > span.badge {
	background: var(--c-accent-soft);
	border-color: color-mix(in srgb, var(--c-accent) 35%, transparent);
	color: var(--c-accent-ink);
}


/* ---- Icon-only actions in table rows ----
   Edit, delete, move and reorder controls are emitted as a bare glyph inside an
   anchor, so their clickable box is the glyph's own 13-17px. These are the
   controls a user aims at most often on an admin screen, and they are the
   hardest to hit. The box grows to 24px; the glyph does not, so row rhythm is
   unchanged. */
table td > a:has(> span[class*="fa-"]:only-child),
table td > a:has(> img:only-child),
table td > a.editfielda, table td > a.reposition,
ul.treeview td > a:has(> span[class*="fa-"]:only-child),
ul.treeview td > a:has(> img:only-child) {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 24px;
	min-height: 24px;
	border-radius: var(--r-sm);
	transition: background var(--t);
}
table td > a:has(> span[class*="fa-"]:only-child):hover,
table td > a:has(> img:only-child):hover,
ul.treeview td > a:hover:has(> span[class*="fa-"]:only-child) {
	background: var(--c-sunken);
}
/* Sort arrows and reorder chevrons sit in dense clusters -- they get the box
   without the hover fill, which would read as noise repeated down a column. */
table th > a:has(> span[class*="fa-"]:only-child) {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 22px;
	min-height: 22px;
}


/* ---- Avatar in the account control ----
   The 80px cap above is for a card's portrait placeholder. In 24.x the top-bar
   avatar carries .dropdown-user-image and is handled separately, but 22.x uses
   the same .photouserphoto classes as a card portrait -- so the card rule won
   and dropped an 80px photo into the bar, overlapping the chrome.

   Scoped by where it sits rather than what it is called, which holds on both
   versions. */
.login_block img.userphoto, .login_block img.photouserphoto,
.login-dropdown-a img, a.dropdown-toggle img.photo,
.atoplogin img.userphoto, .cmd-bar-tools img.userphoto,
.wb-rail-foot img.userphoto, .au-bar-tools img.userphoto,
.ed-side-foot img.userphoto, .dn-bar-tools img.userphoto {
	width: 26px !important;
	height: 26px !important;
	min-width: 0 !important;
	border: 0 !important;
	border-radius: 50%;
	opacity: 1;
	object-fit: cover;
	vertical-align: middle;
}
