<?php
/* Copyright (C) 2026  Thrive / Accellier
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

/**
 *	\file       htdocs/theme/aurora/aurora.inc.php
 *	\brief      Dark-first shell: glass top bar, translucent sidebar, bento cards.
 *
 *	Shell DOM comes from core/menus/standard/thriveshell.lib.php. Component CSS
 *	(tables, forms, buttons, cards, login) is shared from theme/thriveshared and
 *	is driven entirely by the custom properties defined below.
 */

if (!defined('ISLOADEDBYSTEELSHEET')) {
	die('Must be loaded by a stylesheet');
}
/**
 * @var Conf $conf
 * @var Translate $langs
 * @var string $left
 * @var string $right
 * @var string $fontsize
 * @var string $fontsizesmaller
 * @var string $toolTipBgColor
 * @var string $toolTipFontColor
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

$darkmode = getDolGlobalInt('THEME_DARKMODEENABLED');
?>

/* ==========================================================================
   AURORA -- tokens
   ========================================================================== */

:root {
	color-scheme: dark;

	--c-ink: #E8ECF6;
	--c-ink-2: #C3CBDD;
	--c-muted: #8E99B4;
	--c-faint: #6A7590;
	--c-hairline: rgba(255, 255, 255, 0.07);
	--c-border: rgba(255, 255, 255, 0.11);
	--c-border-strong: rgba(255, 255, 255, 0.20);
	/* Surfaces are translucent so the canvas gradient shows through. */
	--c-surface: rgba(23, 28, 46, 0.72);
	--c-canvas: #0A0F1E;
	--c-sunken: rgba(255, 255, 255, 0.045);

	--c-accent: #A78BFA;
	--c-accent-hover: #C4B5FD;
	--c-accent-ink: #C4B5FD;
	--c-accent-soft: rgba(167, 139, 250, 0.16);
	--c-accent-ring: rgba(167, 139, 250, 0.30);

	--c-success: #34D399;
	--c-warning: #FBBF24;
	--c-danger: #FB7185;
	--c-info: #60A5FA;

	--bar-h: 58px;
	--side-w: 250px;

	--c-font: "Inter", "Segoe UI", -apple-system, BlinkMacSystemFont, Roboto, Arial, sans-serif;
	--c-head: var(--c-font);
	--c-mono: "SF Mono", "JetBrains Mono", "Cascadia Mono", Menlo, Consolas, monospace;

	--sp-1: 4px;  --sp-2: 8px;  --sp-3: 12px; --sp-4: 16px;
	--sp-5: 24px; --sp-6: 32px; --sp-7: 48px; --sp-8: 64px;

	--r-sm: 8px; --r: 12px; --r-lg: 18px; --r-xl: 24px; --r-pill: 999px;

	--sh-sm: 0 1px 2px rgba(0, 0, 0, 0.35);
	--sh: 0 2px 6px rgba(0, 0, 0, 0.40);
	--sh-md: 0 10px 30px -8px rgba(0, 0, 0, 0.55);
	--sh-lg: 0 30px 70px -14px rgba(0, 0, 0, 0.75);

	--t: 130ms cubic-bezier(0.4, 0, 0.2, 1);

	/* Dolibarr's documented variable contract. */
	--colorbackbody: var(--c-canvas);
	--colorbackhmenu1: var(--c-surface);
	--colorbackvmenu1: var(--c-surface);
	--colorbacktitle1: var(--c-sunken);
	--colorbacktabcard1: var(--c-surface);
	--colorbacktabactive: var(--c-accent-soft);
	--colorbacklineimpair1: var(--c-surface);
	--colorbacklineimpair2: var(--c-surface);
	--colorbacklinepair1: var(--c-surface);
	--colorbacklinepair2: var(--c-surface);
	--colorbacklinepairhover: var(--c-sunken);
	--colorbacklinepairchecked: var(--c-accent-soft);
	--colorbacklinebreak: var(--c-sunken);
	--colorbackgrey: var(--c-sunken);
	--colorbackmobilemenu: var(--c-surface);
	--colortopbordertitle1: var(--c-accent);
	--colortext: var(--c-ink);
	--colortextlink: var(--c-accent-ink);
	--colortexttitle: var(--c-ink);
	--colortexttitlelink: var(--c-accent-ink);
	--colortexttitlenotab: var(--c-ink);
	--colortexttitlenotab2: var(--c-accent-ink);
	--colortextbackhmenu: var(--c-ink);
	--colortextbackvmenu: var(--c-ink);
	--colortextbacktab: var(--c-ink);
	--colorwhite: var(--c-surface);
	--colorblack: var(--c-ink);
	/* Inputs need to read as recessed wells against translucent panels; the
	   shared 'surface' is itself see-through, so fields disappeared into it. */
	--inputbackgroundcolor: rgba(6, 10, 22, 0.55);
	--inputbackgroundcolordisabled: rgba(255, 255, 255, 0.04);
	--inputbordercolor: rgba(255, 255, 255, 0.20);
	--inputcolordisabled: var(--c-faint);
	--fieldrequiredcolor: var(--c-ink);
	--tableforfieldcolor: var(--c-muted);
	--tablevalidbgcolor: var(--c-accent-soft);
	--oddevencolor: var(--c-ink);
	--listetotal: var(--c-accent-ink);
	--tooltipbgcolor: <?php echo $toolTipBgColor; ?>;
	--tooltipfontcolor: <?php echo $toolTipFontColor; ?>;
	--refidnocolor: var(--c-muted);
	--colorboxiconbg: var(--c-sunken);
	--colorboxstatsborder: var(--c-border);
	--infoboxmoduleenabledbgcolor: var(--c-accent-soft);
	--dolgraphbg: var(--c-surface);
	--heightrow: 155%;
	--amountremaintopaycolor: var(--c-danger);
	--amountremaintopaybackcolor: transparent;
	--amountpaymentcomplete: var(--c-success);
	--productlinestockod: var(--c-success);
	--productlinestocktoolow: var(--c-danger);
}


/* ==========================================================================
   Base
   ========================================================================== */

*, *::before, *::after { box-sizing: border-box; }
html { -webkit-text-size-adjust: 100%; }

body {
	margin: 0;
	background: var(--c-canvas);
	color: var(--c-ink);
	font-family: var(--c-font);
	font-size: <?php echo $fontsize; ?>;
	line-height: 1.55;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	background-image:
		radial-gradient(900px 600px at 8% -10%, rgba(124, 58, 237, 0.20), transparent 62%),
		radial-gradient(800px 520px at 92% 4%, rgba(6, 182, 212, 0.16), transparent 60%);
	background-attachment: fixed;
}

td, th, input, .amount, .refid, .badge, .cmd-kbd { font-variant-numeric: tabular-nums; }

a { color: var(--colortextlink); text-decoration: none; transition: color var(--t); }
a:hover { color: var(--c-accent-hover); }
a.nounderline, a.nounderline:hover { text-decoration: none !important; }
:focus-visible { outline: 2px solid var(--c-accent); outline-offset: 1px; border-radius: var(--r-sm); }

h1, h2, h3, h4 {
	margin: 0 0 var(--sp-3) 0;
	font-family: var(--c-head);
	font-weight: 650;
	line-height: 1.15;
	letter-spacing: -0.028em;
	color: var(--c-ink);
}
h1 { font-size: 1.875rem; } h2 { font-size: 1.5rem; }
h3 { font-size: 1.1875rem; } h4 { font-size: 1rem; }
p { margin: 0 0 var(--sp-3) 0; }
hr { border: 0; border-top: 1px solid var(--c-hairline); margin: var(--sp-4) 0; }
img { border: 0; vertical-align: middle; }
code, pre, kbd { font-family: var(--c-mono); font-size: 0.9em; }
::placeholder { color: var(--c-faint); opacity: 1; }
::selection { background: var(--c-accent-soft); color: var(--c-accent-ink); }
* { scrollbar-width: thin; scrollbar-color: var(--c-border-strong) transparent; }
*::-webkit-scrollbar { width: 10px; height: 10px; }
*::-webkit-scrollbar-track { background: transparent; }
*::-webkit-scrollbar-thumb { background: var(--c-border-strong); border-radius: var(--r-pill); border: 2px solid var(--c-canvas); }

/* Dolibarr's own columns stay empty -- each shell renders its own navigation. */
div.side-nav, #id-left, .menuhider { display: none !important; }
#id-container, .id-container { display: block; width: 100%; }

<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/darkmode.inc.php'; ?>
<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/utilities.inc.php'; ?>

/* ==========================================================================
   AURORA -- shell
   ========================================================================== */


.au-bar {
	position: fixed; top: 0; <?php echo $left; ?>: 0; <?php echo $right; ?>: 0;
	height: var(--bar-h);
	display: flex; align-items: center; gap: var(--sp-4);
	padding: 0 var(--sp-5);
	background: rgba(10, 15, 30, 0.55);
	-webkit-backdrop-filter: saturate(180%) blur(18px);
	backdrop-filter: saturate(180%) blur(18px);
	border-bottom: 1px solid var(--c-hairline);
	z-index: 1300;
}
.au-brand { display: inline-flex; align-items: center; gap: var(--sp-2); color: var(--c-ink); font-weight: 650; letter-spacing: -0.02em; }
.au-brand:hover { color: var(--c-ink); }
.au-brand-mark {
	width: 26px; height: 26px; border-radius: 9px;
	background: linear-gradient(135deg, #A78BFA, #22D3EE);
	box-shadow: 0 0 18px rgba(167, 139, 250, 0.55);
}
.au-here {
	padding: 3px var(--sp-3);
	border: 1px solid var(--c-border); border-radius: var(--r-pill);
	color: var(--c-muted); font-size: <?php echo $fontsizesmaller; ?>;
}
.au-bar-spacer { flex: 1 1 auto; }
.au-bar-tools { display: flex; align-items: center; }
.cmd-trigger.au-search { background: rgba(255,255,255,0.05); border-color: var(--c-border); }

.au-side {
	position: fixed; top: var(--bar-h); <?php echo $left; ?>: 0; bottom: 0;
	width: var(--side-w);
	background: rgba(12, 17, 34, 0.55);
	-webkit-backdrop-filter: blur(18px);
	backdrop-filter: blur(18px);
	border-<?php echo $right; ?>: 1px solid var(--c-hairline);
	z-index: 1200;
}
.au-side-scroll { height: 100%; overflow-y: auto; padding: var(--sp-4) var(--sp-3); }

.au-group { margin-bottom: 2px; }
.au-group-link {
	display: flex; align-items: center; gap: var(--sp-3);
	padding: var(--sp-2) var(--sp-3);
	border-radius: var(--r);
	color: var(--c-ink-2); font-weight: 500; white-space: nowrap;
	transition: background var(--t), color var(--t);
}
.au-group-link:hover { background: rgba(255,255,255,0.06); color: var(--c-ink); }
.au-group.is-active > .au-group-link {
	background: var(--c-accent-soft); color: var(--c-accent-ink); font-weight: 650;
	box-shadow: inset 0 0 0 1px rgba(167, 139, 250, 0.28);
}
.au-group-icon { width: 1.15em; text-align: center; color: var(--c-faint); }
.au-group.is-active .au-group-icon { color: var(--c-accent); }
.au-group-label { overflow: hidden; text-overflow: ellipsis; }

.au-sub {
	margin: var(--sp-1) 0 var(--sp-4) var(--sp-4);
	padding-<?php echo $left; ?>: var(--sp-3);
	border-<?php echo $left; ?>: 1px solid var(--c-hairline);
}
.au-sublink {
	display: block;
	padding: 5px var(--sp-2) 5px calc(var(--sp-2) + (var(--lvl, 0) * 12px));
	border-radius: var(--r-sm);
	color: var(--c-muted); font-size: <?php echo $fontsizesmaller; ?>;
	overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
	transition: background var(--t), color var(--t);
}
.au-sublink:hover { background: rgba(255,255,255,0.06); color: var(--c-ink); }
.au-sublink.is-current { color: var(--c-accent-ink); font-weight: 620; box-shadow: inset 2px 0 0 var(--c-accent); }

#id-right {
	margin-<?php echo $left; ?>: var(--side-w) !important;
	padding-top: var(--bar-h);
	min-width: 0; overflow-x: hidden;
}

@media only screen and (max-width: 992px) {
	.au-side { display: none; }
	#id-right { margin-<?php echo $left; ?>: 0 !important; }
}


<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/palette.inc.php'; ?>
<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/navtree.inc.php'; ?>
<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/select2.inc.php'; ?>


<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/components.inc.php'; ?>


/* Bento: dashboard tiles become glass cards with a lit top edge. */
div.info-box, table.boxtable, div.div-table-responsive, div.tabBar {
	background: var(--c-surface);
	-webkit-backdrop-filter: blur(16px);
	backdrop-filter: blur(16px);
	border-color: var(--c-border);
}
div.info-box { position: relative; overflow: hidden; }
div.info-box::before {
	content: ""; position: absolute; inset: 0 0 auto 0; height: 1px;
	background: linear-gradient(90deg, transparent, rgba(167,139,250,0.55), rgba(34,211,238,0.35), transparent);
}
div.info-box:hover { border-color: rgba(167, 139, 250, 0.35); }
span.info-box-icon {
	background: linear-gradient(135deg, rgba(167,139,250,0.20), rgba(34,211,238,0.14));
	color: var(--c-accent-ink);
}
span.info-box-weight, span.info-box-number { letter-spacing: -0.035em; }
/* Fields keep a visible edge on glass, and lift on focus. */
input[type="text"], input[type="password"], input[type="email"], input[type="number"],
input[type="search"], input[type="tel"], input[type="url"], input[type="date"],
input[type="datetime-local"], input[type="time"], textarea, select, .flat,
.select2-container--default .select2-selection--single,
.select2-container--default .select2-selection--multiple {
	background: var(--inputbackgroundcolor);
	border-color: var(--inputbordercolor) !important;
	color: var(--c-ink);
}
input:hover:not([disabled]), select:hover:not([disabled]), textarea:hover:not([disabled]) {
	border-color: rgba(255, 255, 255, 0.32);
}
.select2-dropdown, .ui-dialog, .dropdown-menu, .cmd-palette-panel {
	background: #131A2C;	/* opaque: stacked translucency turns unreadable */
	-webkit-backdrop-filter: none;
	backdrop-filter: none;
}

/* Rows sit on glass, so the hover needs to be lighter than on a solid card. */
tr.oddeven:hover, tr.impair:hover, tr.pair:hover { background: rgba(255,255,255,0.05); }
tr.liste_titre, .liste_titre { background: rgba(255,255,255,0.035); }


<?php
/* Icon and flag maps: menu-key -> glyph, and the country flag sprite. Data. */
include __DIR__.'/main_menu_fa_icons.inc.php';
include __DIR__.'/flags-sprite.inc.php';
?>
