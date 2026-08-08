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
   Command palette
   ========================================================================== */

.cmd-palette { position: fixed; inset: 0; z-index: 4000; }
.cmd-palette[hidden] { display: none; }
body.cmd-palette-open { overflow: hidden; }

.cmd-palette-backdrop {
	position: absolute; inset: 0;
	background: rgba(11, 18, 32, 0.42);
	-webkit-backdrop-filter: blur(2px);
	backdrop-filter: blur(2px);
}
.cmd-palette-panel {
	position: relative;
	width: min(640px, calc(100vw - var(--sp-6)));
	max-height: min(60vh, 520px);
	margin: 12vh auto 0 auto;
	display: flex;
	flex-direction: column;
	background: var(--c-surface);
	border: 1px solid var(--c-border);
	border-radius: var(--r-xl);
	box-shadow: var(--sh-lg);
	overflow: hidden;
	animation: cmd-pop 130ms cubic-bezier(0.2, 0, 0.2, 1);
}
@keyframes cmd-pop {
	from { opacity: 0; transform: translateY(-6px) scale(0.985); }
	to { opacity: 1; transform: none; }
}

.cmd-palette-input-row {
	display: flex;
	align-items: center;
	gap: var(--sp-3);
	padding: var(--sp-4);
	border-bottom: 1px solid var(--c-hairline);
}
.cmd-palette-input-icon { color: var(--c-faint); }
.cmd-palette-input {
	flex: 1;
	min-width: 0;
	border: 0 !important;
	outline: none;
	background: transparent;
	color: var(--c-ink);
	font-family: inherit;
	font-size: 1rem;
	padding: 0;
	border-radius: 0;
}
/* The row is the field; the input must not draw its own box on top of it. */
.cmd-palette-input:focus, .cmd-palette-input:hover {
	box-shadow: none !important;
	border: 0 !important;
	outline: none;
}

.cmd-palette-results { flex: 1; overflow-y: auto; padding: var(--sp-2); }
.cmd-res-group {
	padding: var(--sp-3) var(--sp-3) var(--sp-1) var(--sp-3);
	font-size: 0.6875rem;
	font-weight: 620;
	letter-spacing: 0.06em;
	text-transform: uppercase;
	color: var(--c-faint);
}
.cmd-res {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: var(--sp-3);
	padding: var(--sp-2) var(--sp-3);
	border-radius: var(--r);
	color: var(--c-ink-2);
}
.cmd-res.is-active { background: var(--c-accent-soft); color: var(--c-accent-ink); }
.cmd-res-title { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.cmd-res-group-tag { flex: 0 0 auto; color: var(--c-faint); font-size: 0.75rem; }
.cmd-res.is-active .cmd-res-group-tag { color: var(--c-accent-ink); opacity: 0.7; }
.cmd-palette-empty { padding: var(--sp-6); text-align: center; color: var(--c-faint); }

.cmd-palette-foot {
	display: flex;
	gap: var(--sp-4);
	padding: var(--sp-2) var(--sp-4);
	border-top: 1px solid var(--c-hairline);
	background: var(--c-sunken);
	color: var(--c-faint);
	font-size: 0.75rem;
}
.cmd-palette-foot span { display: inline-flex; align-items: center; gap: var(--sp-1); }




/* ---- Palette trigger, shared by every shell ---- */
/* The palette trigger is the primary navigation control, so it looks like an
   input rather than a button. */
.cmd-trigger {
	display: inline-flex;
	align-items: center;
	gap: var(--sp-2);
	min-width: 260px;
	height: 34px;
	padding: 0 var(--sp-2) 0 var(--sp-3);
	margin-<?php echo $left; ?>: var(--sp-2);
	background: var(--c-sunken);
	border: 1px solid var(--c-border);
	border-radius: var(--r);
	color: var(--c-faint);
	font-family: inherit;
	font-size: <?php echo $fontsizesmaller; ?>;
	cursor: pointer;
	transition: border-color var(--t), background var(--t);
}
.cmd-trigger:hover { border-color: var(--c-border-strong); background: var(--c-surface); }
.cmd-trigger-label { flex: 1; text-align: <?php echo $left; ?>; }
.cmd-trigger-icon { font-size: 0.8125rem; }

.cmd-kbd {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 20px;
	height: 20px;
	padding: 0 5px;
	background: var(--c-surface);
	border: 1px solid var(--c-border);
	border-radius: var(--r-sm);
	color: var(--c-muted);
	font-family: var(--c-font);
	font-size: 0.6875rem;
	font-weight: 550;
	line-height: 1;
}



/* ---- Relocated account block ----
   command.js moves Dolibarr's own tools/account block into whichever slot the
   active shell exposes (#cmd-bar-tools). Without these rules it lands
   unstyled, because Dolibarr positions it absolutely for its own top bar. */
#cmd-bar-tools div.login_block,
.wb-rail-foot div.login_block,
.ed-side-foot div.login_block {
	position: static;
	display: flex;
	align-items: center;
	gap: var(--sp-1);
	height: auto;
	color: var(--c-muted);
	font-size: <?php echo $fontsizesmaller; ?>;
	white-space: nowrap;
}
/* The rail and the editorial sidebar are narrow columns, so the block stacks. */
.wb-rail-foot div.login_block,
.ed-side-foot div.login_block { flex-direction: column; gap: var(--sp-2); }

#cmd-bar-tools .login_block_other,
#cmd-bar-tools .login_block_tools,
#cmd-bar-tools .login_block_user,
.wb-rail-foot .login_block_other,
.wb-rail-foot .login_block_tools,
.wb-rail-foot .login_block_user,
.ed-side-foot .login_block_other,
.ed-side-foot .login_block_tools,
.ed-side-foot .login_block_user {
	display: flex;
	align-items: center;
	gap: var(--sp-1);
}
.wb-rail-foot .login_block_other,
.wb-rail-foot .login_block_tools,
.wb-rail-foot .login_block_user { flex-direction: column; }

#cmd-bar-tools .login_block_elem,
.wb-rail-foot .login_block_elem,
.ed-side-foot .login_block_elem {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 30px;
	height: 30px;
	padding: 0 var(--sp-1);
	border-radius: var(--r);
	transition: background var(--t), color var(--t);
}
#cmd-bar-tools .login_block_elem:hover,
.ed-side-foot .login_block_elem:hover { background: var(--c-sunken); color: var(--c-ink); }
.wb-rail-foot .login_block_elem:hover { background: rgba(255, 255, 255, 0.12); color: #fff; }

#cmd-bar-tools a, #cmd-bar-tools a:hover,
.ed-side-foot a, .ed-side-foot a:hover { color: var(--c-muted); }
.wb-rail-foot a, .wb-rail-foot a:hover { color: rgba(255, 255, 255, 0.62); }
.wb-rail-foot a:hover { color: #fff; }

#cmd-bar-tools .atoplogin,
.wb-rail-foot .atoplogin,
.ed-side-foot .atoplogin {
	display: inline-flex;
	align-items: center;
	gap: var(--sp-2);
	height: 30px;
	padding: 0 var(--sp-2);
	border-radius: var(--r-pill);
	font-weight: 500;
}
/* Dolibarr emits placeholder anchors for disabled tools; with no icon inside
   they would render as empty boxes.
   :not([class*="fa"]) is essential -- an icon-font element draws its glyph
   from ::before and has no child nodes, so it matches :empty too. Without the
   guard this rule hid the bug, print and help icons in the bar. */
#cmd-bar-tools .login_block_elem:empty:not([class*="fa"]),
#cmd-bar-tools .atoplogin:empty:not([class*="fa"]),
.wb-rail-foot .login_block_elem:empty:not([class*="fa"]),
.ed-side-foot .login_block_elem:empty:not([class*="fa"]) { display: none; }
/* The version string has no room in a rail or a compact bar. */
.wb-rail-foot .login_block_other span:not([class*="fa"]) { display: none; }
img.userphoto { border-radius: 50%; object-fit: cover; }


/* ---- Account dropdown ----
   Dolibarr builds this as <b>Label:</b><span>value</span><br> pairs with no
   row elements, so it reads as one dense block. These rules give it column
   alignment, grouping and a scroll ceiling without needing new markup. */

#topmenu-login-dropdown .dropdown-menu {
	width: 340px;
	max-height: min(78vh, 720px);
	flex-direction: column;
	padding: 0;
	overflow: hidden;
}
/* display belongs to the open state only. Declaring `display: flex` on the
   panel itself outranks the base `.dropdown-menu { display: none }` and left
   the account panel visible on every page load. */
#topmenu-login-dropdown.open > .dropdown-menu,
.open > #topmenu-login-dropdown .dropdown-menu { display: flex; }

/* Header: identity first, timestamps demoted. */
#topmenu-login-dropdown .user-header {
	padding: var(--sp-5) var(--sp-4) var(--sp-4) var(--sp-4);
	text-align: center;
	background: var(--c-sunken);
	border-bottom: 1px solid var(--c-border);
}
#topmenu-login-dropdown .user-header img.dropdown-user-image {
	width: 64px; height: 64px;
	border-radius: 50%;
	border: 2px solid var(--c-surface);
	box-shadow: var(--sh);
	object-fit: cover;
}
#topmenu-login-dropdown .user-header p {
	margin: var(--sp-3) 0 0 0;
	font-size: 0.9375rem;
	font-weight: 620;
	color: var(--c-ink);
	line-height: 1.5;
}
#topmenu-login-dropdown .user-header small {
	display: inline-block;
	font-size: 0.75rem;
	font-weight: 400;
	color: var(--c-muted);
}

/* Body: label column + value, with a scroll ceiling. */
#topmenu-login-dropdown .user-body {
	flex: 1 1 auto;
	min-height: 0;
	overflow-y: auto;
	padding: var(--sp-3) var(--sp-4);
	font-size: 0.8125rem;
	line-height: 1.9;
	color: var(--c-ink-2);
}
#topmenu-login-dropdown .user-body b {
	display: inline-block;
	min-width: 126px;
	padding-<?php echo $right; ?>: var(--sp-1);
	color: var(--c-muted);
	font-weight: 500;
}
/* Each pair is separated by a bare <br>; the leading one just opened a gap
   under the disclosure row. */
#topmenulogincompanyinfo > br:first-child,
#topmenuloginmoreinfo > br:first-child { display: none; }
/* Technical values read better as monospace and should not wrap mid-token. */
#topmenu-login-dropdown #topmenuloginmoreinfo span:not([class]),
#topmenu-login-dropdown #topmenulogincompanyinfo span:not([class]) {
	font-variant-numeric: tabular-nums;
	word-break: break-word;
}
/* "Session" acts as a group heading. */
#topmenu-login-dropdown .user-body u {
	display: block;
	margin: var(--sp-3) 0 var(--sp-1) 0;
	padding-top: var(--sp-3);
	border-top: 1px solid var(--c-hairline);
	text-decoration: none;
	font-size: 0.6875rem;
	font-weight: 620;
	letter-spacing: 0.06em;
	text-transform: uppercase;
	color: var(--c-faint);
}
/* Disclosure triggers become real rows rather than bare carets. */
#topmenulogincompanyinfo-btn, #topmenuloginmoreinfo-btn {
	display: flex;
	align-items: center;
	gap: var(--sp-2);
	margin: var(--sp-1) calc(var(--sp-2) * -1);
	padding: var(--sp-2);
	border-radius: var(--r-sm);
	color: var(--c-ink);
	font-weight: 550;
	cursor: pointer;
	transition: background var(--t);
}
#topmenulogincompanyinfo-btn:hover, #topmenuloginmoreinfo-btn:hover { background: var(--c-sunken); }
#topmenulogincompanyinfo-btn .fa, #topmenuloginmoreinfo-btn .fa {
	color: var(--c-faint);
	transition: transform var(--t);
}

/* Footer: evenly weighted actions on one row. */
#topmenu-login-dropdown .user-footer {
	flex: 0 0 auto;
	display: flex;
	align-items: center;
	gap: var(--sp-2);
	padding: var(--sp-3) var(--sp-4);
	background: var(--c-sunken);
	border-top: 1px solid var(--c-border);
}
#topmenu-login-dropdown .user-footer .pull-left,
#topmenu-login-dropdown .user-footer .pull-right { float: none; }
#topmenu-login-dropdown .user-footer .pull-right { margin-<?php echo $left; ?>: auto; }
#topmenu-login-dropdown .user-footer .clearboth { display: none; }
#topmenu-login-dropdown .user-footer a {
	display: inline-flex;
	align-items: center;
	gap: var(--sp-2);
	height: 30px;
	padding: 0 var(--sp-3);
	border-radius: var(--r);
	color: var(--c-ink-2);
	font-size: 0.8125rem;
	font-weight: 500;
	transition: background var(--t), color var(--t);
}
#topmenu-login-dropdown .user-footer a:hover { background: var(--c-surface); color: var(--c-ink); }
/* Logout is the destructive action here. */
#topmenu-login-dropdown .user-footer .pull-right a:hover { color: var(--c-danger); }
