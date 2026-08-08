<?php
/* Copyright (C) 2026  Thrive / Accellier
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 */

/**
 *	\file       htdocs/core/thriveshell/select2.inc.php
 *	\brief      select2 styling, shared by every Thrive shell.
 *
 *	Dolibarr converts most of its <select> elements to select2 at runtime, so
 *	the visible control is select2's markup rather than the native element the
 *	form CSS targets. Without this the dropdowns keep select2's stock look --
 *	square corners, a 5px grey CSS-triangle arrow and a 28px box -- which sits
 *	badly next to the themed inputs beside them.
 *
 *	The theme stylesheet is loaded after select2.css, so plain selectors win and
 *	!important is only needed where select2 sets an inline style.
 */

if (!defined('ISLOADEDBYSTEELSHEET')) {
	die('Must be loaded by a stylesheet');
}
/**
 * @var string $left
 * @var string $right
 * @var string $fontsize
 */
?>

/* ---- select2: single ---- */

.select2-container--default .select2-selection--single {
	display: flex;
	align-items: center;
	height: 34px;
	padding: 0 var(--sp-2);
	background: var(--inputbackgroundcolor);
	border: 1px solid var(--inputbordercolor) !important;
	border-radius: var(--r) !important;
	transition: border-color var(--t), box-shadow var(--t);
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
	flex: 1 1 auto;
	min-width: 0;
	padding: 0;
	padding-<?php echo $right; ?>: var(--sp-5);
	line-height: 32px;
	color: var(--c-ink);
	font-size: <?php echo $fontsize; ?>;
}
.select2-container--default .select2-selection--single .select2-selection__placeholder { color: var(--c-faint); }

.select2-container--default .select2-selection--single:hover { border-color: var(--c-border-strong) !important; }
.select2-container--default.select2-container--focus .select2-selection--single,
.select2-container--default.select2-container--open .select2-selection--single {
	border-color: var(--c-accent) !important;
	box-shadow: 0 0 0 3px var(--c-accent-ring);
}

/* Arrow: replace select2's CSS-triangle with a chevron matching the native
   selects and the nav carets. */
.select2-container--default .select2-selection--single .select2-selection__arrow {
	position: absolute;
	top: 0;
	<?php echo $right; ?>: 0;
	width: 30px;
	height: 100%;
	display: flex;
	align-items: center;
	justify-content: center;
	border: 0;
	background: transparent;
	pointer-events: none;
}
.select2-container--default .select2-selection--single .select2-selection__arrow b {
	display: none;		/* the stock 5px border-triangle */
}
.select2-container--default .select2-selection--single .select2-selection__arrow::after {
	content: "";
	width: 6px;
	height: 6px;
	margin-top: -3px;
	border-<?php echo $right; ?>: 1.6px solid var(--c-muted);
	border-bottom: 1.6px solid var(--c-muted);
	transform: rotate(45deg);
	transition: transform var(--t), border-color var(--t);
}
.select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow::after {
	margin-top: 2px;
	transform: rotate(-135deg);
	border-color: var(--c-accent);
}

/* Clear ("x") control */
.select2-container--default .select2-selection--single .select2-selection__clear {
	margin-<?php echo $right; ?>: var(--sp-4);
	color: var(--c-faint);
	font-weight: 400;
}
.select2-container--default .select2-selection--single .select2-selection__clear:hover { color: var(--c-danger); }


/* ---- select2: multiple ---- */

.select2-container--default .select2-selection--multiple {
	min-height: 34px;
	padding: 2px var(--sp-1);
	background: var(--inputbackgroundcolor);
	border: 1px solid var(--inputbordercolor) !important;
	border-radius: var(--r) !important;
}
.select2-container--default.select2-container--focus .select2-selection--multiple {
	border-color: var(--c-accent) !important;
	box-shadow: 0 0 0 3px var(--c-accent-ring);
}
.select2-container--default .select2-selection--multiple .select2-selection__choice {
	margin: 3px 3px 3px 0;
	padding: 1px var(--sp-2);
	background: var(--c-accent-soft);
	border: 1px solid color-mix(in srgb, var(--c-accent) 26%, transparent);
	border-radius: var(--r-sm);
	color: var(--c-accent-ink);
	line-height: 1.6;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
	margin-<?php echo $right; ?>: var(--sp-1);
	color: var(--c-accent-ink);
	opacity: 0.7;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover { opacity: 1; }


/* ---- Dropdown panel ---- */

.select2-dropdown {
	background: var(--c-surface);
	border: 1px solid var(--c-border);
	border-radius: var(--r) !important;
	box-shadow: var(--sh-lg);
	color: var(--c-ink);
	overflow: hidden;
}
.select2-container--default .select2-search--dropdown { padding: var(--sp-2); }
.select2-container--default .select2-search--dropdown .select2-search__field {
	height: 32px;
	padding: 0 var(--sp-2);
	background: var(--inputbackgroundcolor);
	border: 1px solid var(--inputbordercolor);
	border-radius: var(--r-sm);
	color: var(--c-ink);
}
.select2-container--default .select2-search--dropdown .select2-search__field:focus {
	outline: none;
	border-color: var(--c-accent);
	box-shadow: 0 0 0 3px var(--c-accent-ring);
}
.select2-container--default .select2-results__options { padding: var(--sp-1); }
.select2-container--default .select2-results__option {
	padding: var(--sp-2) var(--sp-3);
	border-radius: var(--r-sm);
	color: var(--c-ink-2);
}
.select2-container--default .select2-results__option--highlighted[aria-selected],
.select2-container--default .select2-results__option--highlighted {
	background: var(--c-accent-soft) !important;
	color: var(--c-accent-ink) !important;
}
.select2-container--default .select2-results__option[aria-selected="true"] {
	background: var(--c-sunken);
	color: var(--c-ink);
	font-weight: 550;
}
.select2-container--default .select2-results__message { color: var(--c-muted); }


/* ---- Width floor inside edit forms ----
   Dolibarr sizes many of these selects from a maxwidth utility class and
   select2 copies that width verbatim, so short fields truncated their own
   value ("eratosth...", "Euro..."). A floor keeps the value readable and
   stops the column looking ragged; plain <select> elements (the hour/minute
   pairs beside date fields) are deliberately untouched. */
table.border .select2-container,
table.tableforfield .select2-container,
div.tabBar .select2-container {
	min-width: 190px;
	max-width: 100%;
}
/* Narrow-by-design pickers keep their size. */
table.border .select2-container.select2-container--focus[style*="width: 5"],
.select2-container.minwidth50, .select2-container.maxwidth50,
.select2-container.maxwidth75 { min-width: 0; }

@media only screen and (max-width: 767px) {
	table.border .select2-container,
	table.tableforfield .select2-container { min-width: 0; width: 100% !important; }
}


/* ---- Multi-select affordance ----
   A multiple-select with nothing chosen renders as an empty box: no chevron,
   no placeholder, nothing to say it opens a list. It reads as a text field.
   The chevron is drawn on the control so it matches the single selects. */
.select2-container--default .select2-selection--multiple {
	position: relative;
	padding-<?php echo $right; ?>: 28px;
	cursor: pointer;
}
.select2-container--default .select2-selection--multiple::after {
	content: "";
	position: absolute;
	top: 50%;
	<?php echo $right; ?>: 11px;
	width: 6px;
	height: 6px;
	margin-top: -5px;
	border-<?php echo $right; ?>: 1.6px solid var(--c-muted);
	border-bottom: 1.6px solid var(--c-muted);
	transform: rotate(45deg);
	pointer-events: none;
	transition: transform var(--t), border-color var(--t);
}
.select2-container--default.select2-container--open .select2-selection--multiple::after {
	margin-top: -1px;
	transform: rotate(-135deg);
	border-color: var(--c-accent);
}
/* The inline search field is the only thing in an empty control, so it has to
   carry the width rather than collapse to a caret. */
.select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field {
	margin: 0;
	padding: 0 var(--sp-1);
	min-width: 8em;
	line-height: 30px;
	font-family: inherit;
}

/* ---- Dropdown panel width ----
   `width: auto !important` overrode the inline width select2 copies from the
   control, so the panel collapsed to its content and a 420px field opened a
   260px list -- the panel looked like it belonged to a different control.
   Dropping the override lets it track the control again; the floor stays for
   genuinely narrow filter selects. */
.select2-dropdown {
	min-width: 260px;
	max-width: 90vw;
}
.select2-container--default .select2-results__option {
	line-height: 1.45;
	white-space: normal;
	overflow-wrap: anywhere;
}
.select2-results__options { max-height: 320px; }
