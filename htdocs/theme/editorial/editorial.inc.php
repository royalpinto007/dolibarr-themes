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
 *	\file       htdocs/theme/editorial/editorial.inc.php
 *	\brief      Design-forward shell: display serif headings, wide margins, no chrome.
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
   EDITORIAL -- tokens
   ========================================================================== */

:root {
	color-scheme: light;

	--c-ink: #1A1714;
	--c-ink-2: #443E37;
	/* #7A7269 cleared 4.5:1 on the card but not on the canvas (4.35) or the
	   sunken band (4.09), where most muted text actually sits. */
	--c-muted: #6B6259;
	--c-faint: #938B82;
	--c-hairline: #EDE8E1;
	--c-border: #E2DCD3;
	--c-border-strong: #CFC6B9;
	--c-surface: #FFFDFB;
	--c-canvas: #F8F5F0;
	--c-sunken: #F2EEE7;

	--c-accent: #B4462F;
	--c-accent-hover: #8F3524;
	--c-accent-ink: #8F3524;
	--c-accent-soft: #FBEEE9;
	--c-accent-ring: rgba(180, 70, 47, 0.20);

	--c-success: #3F6B3A;
	--c-warning: #8A6115;
	--c-danger: #A33124;
	--c-info: #35607F;

	--side-w: 268px;

	--c-font: "Inter", "Segoe UI", -apple-system, BlinkMacSystemFont, Roboto, Arial, sans-serif;
	--c-head: "Iowan Old Style", "Palatino Linotype", Palatino, Georgia, "Times New Roman", serif;
	--c-mono: "SF Mono", "JetBrains Mono", "Cascadia Mono", Menlo, Consolas, monospace;

	--sp-1: 4px;  --sp-2: 8px;  --sp-3: 12px; --sp-4: 16px;
	--sp-5: 24px; --sp-6: 32px; --sp-7: 48px; --sp-8: 64px;

	--r-sm: 4px; --r: 6px; --r-lg: 10px; --r-xl: 14px; --r-pill: 999px;

	--sh-sm: 0 1px 2px rgba(41, 37, 32, 0.04);
	--sh: 0 1px 2px rgba(41, 37, 32, 0.04), 0 4px 10px -2px rgba(41, 37, 32, 0.05);
	--sh-md: 0 2px 4px rgba(41, 37, 32, 0.04), 0 12px 28px -8px rgba(41, 37, 32, 0.10);
	--sh-lg: 0 24px 64px -12px rgba(41, 37, 32, 0.28), 0 4px 12px rgba(41, 37, 32, 0.08);

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
	--inputbackgroundcolor: var(--c-surface);
	--inputbackgroundcolordisabled: var(--c-sunken);
	--inputbordercolor: var(--c-border);
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
	line-height: 1.6;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;

}

td, th, input, .amount, .refid, .badge, .cmd-kbd { font-variant-numeric: tabular-nums; }

a { color: var(--colortextlink); text-decoration: none; transition: color var(--t); }
a:hover { color: var(--c-accent-hover); }
a.nounderline, a.nounderline:hover { text-decoration: none !important; }
:focus-visible { outline: 2px solid var(--c-accent); outline-offset: 1px; border-radius: var(--r-sm); }

h1, h2, h3, h4 {
	margin: 0 0 var(--sp-3) 0;
	font-family: var(--c-head);
	font-weight: 600;
	line-height: 1.15;
	letter-spacing: -0.02em;
	color: var(--c-ink);
}
h1 { font-size: 2.5rem; } h2 { font-size: 1.875rem; }
h3 { font-size: 1.375rem; } h4 { font-size: 1rem; }
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
   EDITORIAL -- shell
   ========================================================================== */


.ed-side {
	position: fixed; top: 0; <?php echo $left; ?>: 0; bottom: 0;
	width: var(--side-w);
	display: flex; flex-direction: column;
	padding: var(--sp-7) var(--sp-5) var(--sp-4) var(--sp-6);
	background: transparent;   /* the sidebar is type on paper, not a panel */
	z-index: 1200;
}
.ed-brand {
	font-family: var(--c-head);
	font-size: 1.3125rem; font-weight: 600; letter-spacing: -0.02em;
	color: var(--c-ink); margin-bottom: var(--sp-5);
	overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.ed-brand:hover { color: var(--c-accent-ink); }
.cmd-trigger.ed-search {
	width: 100%; min-width: 0; margin: 0 0 var(--sp-5) 0;
	background: transparent; border: 0; border-bottom: 1px solid var(--c-border);
	border-radius: 0; padding-left: 0;
}
.cmd-trigger.ed-search:hover { background: transparent; border-bottom-color: var(--c-ink); }

.ed-nav { flex: 1 1 auto; overflow-y: auto; margin-<?php echo $left; ?>: calc(var(--sp-2) * -1); }
.ed-group { counter-increment: edgroup; }
.ed-nav { counter-reset: edgroup; }
.ed-group-link {
	display: flex; align-items: baseline; gap: var(--sp-3);
	padding: 5px var(--sp-2);
	color: var(--c-ink-2);
	font-size: 1.0625rem; letter-spacing: -0.012em;
	transition: color var(--t);
}
.ed-group-link:hover { color: var(--c-accent-ink); }
/* Numbered index: the editorial signature, and a genuine scanning aid. */
.ed-group-num::before {
	content: counter(edgroup, decimal-leading-zero);
	font-family: var(--c-mono); font-size: 0.6875rem;
	color: var(--c-faint); font-variant-numeric: tabular-nums;
}
.ed-group.is-active > .ed-group-link { color: var(--c-accent-ink); font-weight: 600; }
.ed-group.is-active .ed-group-num::before { color: var(--c-accent); }

.ed-sub { margin: var(--sp-1) 0 var(--sp-4) calc(var(--sp-5) + var(--sp-2)); }
.ed-sublink {
	display: block;
	padding: 3px 0 3px calc(var(--lvl, 0) * 12px);
	color: var(--c-muted); font-size: <?php echo $fontsizesmaller; ?>;
	overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
	transition: color var(--t);
}
.ed-sublink:hover { color: var(--c-ink); }
.ed-sublink.is-current { color: var(--c-accent-ink); font-weight: 620; }
.ed-side-foot { flex: 0 0 auto; padding-top: var(--sp-3); border-top: 1px solid var(--c-hairline); }

#id-right {
	margin-<?php echo $left; ?>: var(--side-w) !important;
	min-width: 0; overflow-x: hidden;
}

@media only screen and (max-width: 992px) {
	.ed-side { position: static; width: auto; padding: var(--sp-4); }
	#id-right { margin-<?php echo $left; ?>: 0 !important; }
}


<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/palette.inc.php'; ?>
<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/navtree.inc.php'; ?>
<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/select2.inc.php'; ?>


<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/components.inc.php'; ?>


/* Generous measure and a display-serif page title: the editorial payoff. */
.fiche { padding: var(--sp-8) var(--sp-8) var(--sp-8) var(--sp-7); max-width: 1420px; }
.titre, div.titre {
	font-family: var(--c-head);
	font-size: 2.375rem; font-weight: 600; letter-spacing: -0.028em; line-height: 1.08;
}
.refid { font-family: var(--c-head); font-size: 2rem; font-weight: 600; letter-spacing: -0.026em; }
.titre .opacitymedium { font-family: var(--c-font); font-size: 0.42em; }
tr.box_titre td, .box_titre td { font-family: var(--c-head); font-size: 1.0625rem; letter-spacing: -0.012em; }
/* Rules rather than boxes: the page should read as a printed document. */
table.boxtable, div.div-table-responsive, div.tabBar {
	box-shadow: none;
	border-color: var(--c-border);
}


<?php
/* Icon and flag maps: menu-key -> glyph, and the country flag sprite. Data. */
include __DIR__.'/main_menu_fa_icons.inc.php';
include __DIR__.'/flags-sprite.inc.php';
?>


/* ---- Content emitted outside the layout wrapper ----
   A few Dolibarr list pages render their table as a direct child of <body>
   rather than inside #id-right. With side chrome that content slides under the
   navigation and its first column becomes unreachable. Given the same offset
   as #id-right. */
body > div.div-table-responsive,
body > div.div-table-responsive-no-min,
body > div.fichecenter,
body > table.liste,
body > form > div.div-table-responsive {
	margin-<?php echo $left; ?>: var(--side-w, 268px);
	max-width: calc(100% - var(--side-w, 268px));
	box-sizing: border-box;
}


/* ---- Brand ----
   The company name was clipped at 212px inside a 268px column -- "Aarav Demo
   ERP Pvt Ltd" became "Aarav Demo ERP Pvt...". A company name is identity, not
   a label: it gets the column's full width and is allowed a second line rather
   than losing its ending. */
.ed-brand, .ed-side .ed-brand, .ed-side-head a:first-child {
	display: block;
	width: 100%;
	max-width: 100%;
	white-space: normal;
	overflow: visible;
	text-overflow: clip;
	line-height: 1.25;
	overflow-wrap: anywhere;
}
