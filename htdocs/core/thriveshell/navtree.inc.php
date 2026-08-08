<?php
/* Copyright (C) 2026  Thrive / Accellier
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 */

/**
 *	\file       htdocs/core/thriveshell/navtree.inc.php
 *	\brief      Foldable navigation tree styling, shared by every Thrive shell.
 *
 *	Markup comes from thriveshell_print_nodes(); behaviour from each theme's JS.
 */

if (!defined('ISLOADEDBYSTEELSHEET')) {
	die('Must be loaded by a stylesheet');
}
/**
 * @var string $left
 * @var string $right
 */
?>



/* ---- Foldable navigation tree, shared by every shell ----
   Dolibarr's deeper modules (Setup is 20+ pages) are unusable as a flat list,
   so each branch folds and only the path to the current page is open. */
.ts-node { display: block; }
.ts-row {
	display: flex;
	align-items: center;
	gap: 2px;
	padding-<?php echo $left; ?>: calc(var(--lvl, 0) * 11px);
}
.ts-caret {
	flex: 0 0 auto;
	width: 18px; height: 22px;
	display: inline-flex; align-items: center; justify-content: center;
	padding: 0; margin: 0;
	background: none; border: 0; cursor: pointer;
	color: var(--c-faint);
	transition: color var(--t);
}
.ts-caret:hover { color: var(--c-ink); }
.ts-caret.is-empty { cursor: default; pointer-events: none; }
.ts-caret:not(.is-empty)::before {
	content: "";
	width: 5px; height: 5px;
	border-<?php echo $right; ?>: 1.5px solid currentColor;
	border-bottom: 1.5px solid currentColor;
	transform: rotate(-45deg);
	transition: transform var(--t);
}
.ts-node.is-open > .ts-row > .ts-caret::before { transform: rotate(45deg); }

/* Children are closed unless their branch is open. */
.ts-children { display: none; }
.ts-node.is-open > .ts-children { display: block; }

/* The link fills the remaining width so the whole row is a target. */
.ts-row > a { flex: 1 1 auto; min-width: 0; }
