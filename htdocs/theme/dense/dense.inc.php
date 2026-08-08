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
 *	\file       htdocs/theme/dense/dense.inc.php
 *	\brief      Data-first shell: horizontal module tabs, no sidebar, compact tables.
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
   DENSE -- tokens
   ========================================================================== */

:root {
	color-scheme: light;

	--c-ink: #101828;
	--c-ink-2: #2E3A4D;
	--c-muted: #667085;
	--c-faint: #98A2B3;
	--c-hairline: #F0F2F5;
	--c-border: #E4E7EC;
	--c-border-strong: #CDD3DC;
	--c-surface: #FFFFFF;
	--c-canvas: #FBFCFD;
	--c-sunken: #F6F8FA;

	--c-accent: #2563EB;
	--c-accent-hover: #1D4ED8;
	--c-accent-ink: #1D4ED8;
	--c-accent-soft: #EFF4FF;
	--c-accent-ring: rgba(37, 99, 235, 0.20);

	--c-success: #067647;
	--c-warning: #B54708;
	--c-danger: #B42318;
	--c-info: #175CD3;

	--bar-h: 42px;
	--subbar-h: 36px;

	--c-font: "Inter", "Segoe UI", -apple-system, BlinkMacSystemFont, Roboto, Arial, sans-serif;
	--c-head: var(--c-font);
	--c-mono: "SF Mono", "JetBrains Mono", "Cascadia Mono", Menlo, Consolas, monospace;

	--sp-1: 4px;  --sp-2: 8px;  --sp-3: 12px; --sp-4: 16px;
	--sp-5: 24px; --sp-6: 32px; --sp-7: 48px; --sp-8: 64px;

	--r-sm: 4px; --r: 5px; --r-lg: 7px; --r-xl: 9px; --r-pill: 999px;

	--sh-sm: 0 1px 2px rgba(15, 23, 42, 0.04);
	--sh: 0 1px 2px rgba(15, 23, 42, 0.04), 0 4px 10px -2px rgba(15, 23, 42, 0.05);
	--sh-md: 0 2px 4px rgba(15, 23, 42, 0.04), 0 12px 28px -8px rgba(15, 23, 42, 0.10);
	--sh-lg: 0 24px 64px -12px rgba(15, 23, 42, 0.28), 0 4px 12px rgba(15, 23, 42, 0.08);

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
	line-height: 1.45;
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
	font-weight: 640;
	line-height: 1.15;
	letter-spacing: -0.02em;
	color: var(--c-ink);
}
h1 { font-size: 1.375rem; } h2 { font-size: 1.1875rem; }
h3 { font-size: 1.0625rem; } h4 { font-size: 1rem; }
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
   DENSE -- shell
   ========================================================================== */


.dn-bar {
	position: fixed; top: 0; <?php echo $left; ?>: 0; <?php echo $right; ?>: 0;
	height: var(--bar-h);
	display: flex; align-items: stretch; gap: var(--sp-3);
	padding: 0 var(--sp-3);
	background: var(--c-surface);
	border-bottom: 1px solid var(--c-border);
	z-index: 1300;
}
.dn-brand {
	display: flex; align-items: center;
	padding-<?php echo $right; ?>: var(--sp-3);
	color: var(--c-ink); font-weight: 660; font-size: <?php echo $fontsizesmaller; ?>;
	letter-spacing: -0.01em; white-space: nowrap;
	max-width: 190px; overflow: hidden; text-overflow: ellipsis;
}
/* Modules as a text tab strip: no icons, no wasted vertical space. */
.dn-tabs { display: flex; align-items: stretch; overflow-x: auto; scrollbar-width: none; }
.dn-tabs::-webkit-scrollbar { height: 0; }
.dn-tab {
	display: flex; align-items: center;
	padding: 0 var(--sp-3);
	color: var(--c-muted); font-size: <?php echo $fontsizesmaller; ?>;
	white-space: nowrap; border-bottom: 2px solid transparent;
	transition: color var(--t), border-color var(--t), background var(--t);
}
.dn-tab:hover { color: var(--c-ink); background: var(--c-sunken); }
.dn-tab.is-active { color: var(--c-accent-ink); border-bottom-color: var(--c-accent); font-weight: 600; }
.dn-bar-spacer { flex: 1 1 auto; }
.dn-bar-tools { display: flex; align-items: center; }
.cmd-trigger.dn-search { height: 26px; min-width: 190px; align-self: center; }

.dn-subbar {
	position: fixed; top: var(--bar-h); <?php echo $left; ?>: 0; <?php echo $right; ?>: 0;
	height: var(--subbar-h);
	display: flex; align-items: stretch; gap: 2px;
	padding: 0 var(--sp-3);
	background: var(--c-sunken);
	border-bottom: 1px solid var(--c-border);
	overflow-x: auto; scrollbar-width: none;
	z-index: 1250;
}
.dn-subbar::-webkit-scrollbar { height: 0; }
.dn-subtab {
	display: flex; align-items: center;
	padding: 0 var(--sp-3);
	margin: 5px 0;
	border-radius: var(--r-sm);
	color: var(--c-muted); font-size: <?php echo $fontsizesmaller; ?>;
	white-space: nowrap;
	transition: background var(--t), color var(--t);
}
.dn-subtab:hover { background: var(--c-surface); color: var(--c-ink); }
.dn-subtab.is-current { background: var(--c-surface); color: var(--c-accent-ink); font-weight: 620; box-shadow: var(--sh-sm); }

#id-right {
	margin: 0 !important;
	padding-top: calc(var(--bar-h) + var(--subbar-h));
	min-width: 0; overflow-x: hidden;
}
body.dn-no-sub #id-right { padding-top: var(--bar-h); }


<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/palette.inc.php'; ?>
<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/navtree.inc.php'; ?>
<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/select2.inc.php'; ?>


<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/components.inc.php'; ?>


/* Density is the whole point: tighter rows, smaller type, more data per screen. */
.fiche { padding: var(--sp-4) var(--sp-4) var(--sp-7) var(--sp-4); max-width: none; }
tr.oddeven td, tr.impair td, tr.pair td, table.liste td, table.noborder td {
	padding: var(--sp-1) var(--sp-3);
	height: 32px;
	font-size: <?php echo $fontsizesmaller; ?>;
}
tr.liste_titre th, tr.liste_titre td, th.liste_titre, td.liste_titre {
	padding: var(--sp-2) var(--sp-3);
	font-size: 0.75rem;
	text-transform: uppercase; letter-spacing: 0.04em;
}
div.div-table-responsive, div.div-table-responsive-no-min { border-radius: var(--r-lg); box-shadow: none; }
div.tabBar { padding: var(--sp-4); box-shadow: none; }
table.boxtable { box-shadow: none; }
tr.box_titre td, .box_titre td { padding: var(--sp-3) var(--sp-4); font-size: <?php echo $fontsize; ?>; }
tr.box_impair td, tr.box_pair td { padding: var(--sp-2) var(--sp-4); }
.titre, div.titre { font-size: 1.375rem; }
span.info-box-weight, span.info-box-number { font-size: 1.25rem; }
div.info-box-content { padding: var(--sp-3); }
span.info-box-icon { width: 32px; height: 32px; flex-basis: 32px; margin: var(--sp-3) 0 var(--sp-3) var(--sp-3); font-size: 0.9375rem; }


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
	margin-<?php echo $left; ?>: 0px;
	max-width: calc(100% - 0px);
	box-sizing: border-box;
}
