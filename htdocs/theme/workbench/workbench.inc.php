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
 *	\file       htdocs/theme/workbench/workbench.inc.php
 *	\brief      Two-tier shell: a dark module rail beside a contextual panel.
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
   WORKBENCH -- tokens
   ========================================================================== */

:root {
	color-scheme: light;

	--c-ink: #0F172A;
	--c-ink-2: #27364B;
	--c-muted: #5A6B85;
	--c-faint: #93A1B5;
	--c-hairline: #EDF1F6;
	--c-border: #E0E6EF;
	--c-border-strong: #C6D0DE;
	--c-surface: #FFFFFF;
	--c-canvas: #F4F7FA;
	--c-sunken: #EFF3F8;

	/* Shifted one step darker than the original #0D9488. That teal carries
	   white text at 3.74:1 -- under AA -- and the accent's main job here is
	   the filled primary button, so it has to be a colour white can sit on.
	   #0F766E measures 5.47:1 and is the same hue family. */
	--c-accent: #0F766E;
	--c-accent-hover: #115E59;
	--c-accent-ink: #0F766E;
	--c-accent-soft: #E6F6F4;
	--c-accent-ring: rgba(15, 118, 110, 0.22);

	--c-success: #0F7B4F;
	--c-warning: #96610A;
	--c-danger: #B3261E;
	--c-info: #2B5C8A;

	/* Rail is deliberately dark: it is the one fixed anchor on screen. */
	--rail-bg: #0F172A;
	--rail-w: 68px;
	--panel-w: 236px;

	--c-font: "Inter", "Segoe UI", -apple-system, BlinkMacSystemFont, Roboto, Arial, sans-serif;
	--c-head: var(--c-font);
	--c-mono: "SF Mono", "JetBrains Mono", "Cascadia Mono", Menlo, Consolas, monospace;

	--sp-1: 4px;  --sp-2: 8px;  --sp-3: 12px; --sp-4: 16px;
	--sp-5: 24px; --sp-6: 32px; --sp-7: 48px; --sp-8: 64px;

	--r-sm: 6px; --r: 8px; --r-lg: 12px; --r-xl: 16px; --r-pill: 999px;

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
	line-height: 1.5;
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
	font-weight: 620;
	line-height: 1.15;
	letter-spacing: -0.022em;
	color: var(--c-ink);
}
h1 { font-size: 1.625rem; } h2 { font-size: 1.3125rem; }
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
   WORKBENCH -- shell
   ========================================================================== */


.wb-rail {
	position: fixed; top: 0; <?php echo $left; ?>: 0; bottom: 0;
	width: var(--rail-w);
	display: flex; flex-direction: column; align-items: center;
	padding: var(--sp-3) 0;
	background: var(--rail-bg);
	z-index: 1300;
}
.wb-brand {
	display: flex; align-items: center; justify-content: center;
	width: 38px; height: 38px; margin-bottom: var(--sp-4);
	border-radius: var(--r-lg);
	/* White on the raw accent measures 3.74:1 -- under AA for a 16px bold
	   glyph. Darkening only this tile keeps the brand hue while making the
	   letter legible; the accent itself is unchanged everywhere else. */
	background: color-mix(in srgb, var(--c-accent) 74%, #000); color: #fff;
	font-weight: 700; font-size: 1rem;
}
.wb-brand:hover { color: #fff; }
.wb-rail-nav { flex: 1 1 auto; width: 100%; overflow-y: auto; overflow-x: visible; scrollbar-width: none; }
.wb-rail-nav::-webkit-scrollbar { width: 0; }

.wb-rail-item {
	position: relative;
	display: flex; align-items: center; justify-content: center;
	width: 44px; height: 44px; margin: 2px auto;
	border-radius: var(--r); color: rgba(255,255,255,0.55);
	transition: background var(--t), color var(--t);
}
.wb-rail-item:hover { background: rgba(255,255,255,0.10); color: #fff; }
.wb-rail-item.is-active { background: rgba(255,255,255,0.14); color: #fff; }
/* Accent tab marks the open module on the rail edge. */
.wb-rail-item.is-active::before {
	content: ""; position: absolute; <?php echo $left; ?>: -10px; top: 50%;
	transform: translateY(-50%); width: 3px; height: 22px;
	background: var(--c-accent); border-radius: 0 3px 3px 0;
}
.wb-rail-icon { font-size: 1.0625rem; }

/* Icons alone are ambiguous, so each rail entry names itself on hover. */
.wb-rail-tip {
	position: absolute; <?php echo $left; ?>: calc(100% + 10px); top: 50%;
	transform: translateY(-50%);
	padding: 5px var(--sp-3);
	background: var(--c-ink); color: #fff;
	font-size: <?php echo $fontsizesmaller; ?>; white-space: nowrap;
	border-radius: var(--r-sm); box-shadow: var(--sh-md);
	opacity: 0; pointer-events: none; transition: opacity var(--t);
	z-index: 20;
}
.wb-rail-item:hover .wb-rail-tip { opacity: 1; }
.wb-rail-foot { flex: 0 0 auto; padding-top: var(--sp-2); }

.wb-panel {
	position: fixed; top: 0; <?php echo $left; ?>: var(--rail-w); bottom: 0;
	width: var(--panel-w);
	display: flex; flex-direction: column;
	background: var(--c-surface);
	border-<?php echo $right; ?>: 1px solid var(--c-hairline);
	z-index: 1200;
}
.wb-panel-head { padding: var(--sp-5) var(--sp-4) var(--sp-3) var(--sp-4); }
.wb-panel-title {
	font-size: 1.0625rem; font-weight: 640; letter-spacing: -0.018em; color: var(--c-ink);
}
.cmd-trigger.wb-search { width: calc(100% - var(--sp-6)); margin: 0 var(--sp-4) var(--sp-3) var(--sp-4); min-width: 0; }
.wb-panel-nav { flex: 1 1 auto; overflow-y: auto; padding: 0 var(--sp-2) var(--sp-4) var(--sp-2); }

.wb-sublink {
	display: block;
	padding: 6px var(--sp-3) 6px calc(var(--sp-3) + (var(--lvl, 0) * 12px));
	border-radius: var(--r-sm);
	color: var(--c-muted); font-size: <?php echo $fontsize; ?>;
	overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
	transition: background var(--t), color var(--t);
}
.wb-sublink:hover { background: var(--c-sunken); color: var(--c-ink); }
.wb-sublink.is-current {
	background: var(--c-accent-soft); color: var(--c-accent-ink); font-weight: 620;
}

#id-right {
	margin-<?php echo $left; ?>: calc(var(--rail-w) + var(--panel-w)) !important;
	min-width: 0; overflow-x: hidden;
}

@media only screen and (max-width: 992px) {
	.wb-panel { display: none; }
	#id-right { margin-<?php echo $left; ?>: var(--rail-w) !important; }
}


<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/palette.inc.php'; ?>
<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/navtree.inc.php'; ?>
<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/select2.inc.php'; ?>


<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/components.inc.php'; ?>


/* The panel already names the module, so the page heading can be quieter. */
.fiche { padding: var(--sp-6) var(--sp-6) var(--sp-8) var(--sp-6); }


<?php
/* Icon and flag maps: menu-key -> glyph, and the country flag sprite. Data. */
include __DIR__.'/main_menu_fa_icons.inc.php';
include __DIR__.'/flags-sprite.inc.php';
?>

/* The account label sits inside the dark rail (#0F172A), but the shared chrome
   rule colours it from the neutral ramp, which is built for light surfaces --
   so a "readable ink" there is nearly invisible here (1.46:1). On the rail the
   contrast runs the other way: the light end of the ramp is what reads. */
.wb-rail .atoplogin, .wb-rail .atoplogin span,
.wb-rail .login-dropdown-a .hidden-xs,
.wb-rail .login-dropdown-a { color: var(--c-faint); }


/* ---- Content emitted outside the layout wrapper ----
   A few Dolibarr list pages (social/fiscal taxes among them) render their
   table as a direct child of <body> rather than inside #id-right. With a
   top-bar theme that is harmless -- the content simply starts at x=0. Here the
   chrome is a fixed left rail plus panel, so anything at body level slides
   underneath them and its first column becomes unreachable.

   Given the same offset as #id-right, matching its responsive behaviour. */
body > div.div-table-responsive,
body > div.div-table-responsive-no-min,
body > div.fichecenter,
body > table.liste,
body > form > div.div-table-responsive {
	margin-<?php echo $left; ?>: calc(var(--rail-w) + var(--panel-w));
	max-width: calc(100% - var(--rail-w) - var(--panel-w));
	box-sizing: border-box;
}
@media only screen and (max-width: 992px) {
	body > div.div-table-responsive,
	body > div.div-table-responsive-no-min,
	body > div.fichecenter,
	body > table.liste,
	body > form > div.div-table-responsive {
		margin-<?php echo $left; ?>: var(--rail-w);
		max-width: calc(100% - var(--rail-w));
	}
}


/* ---- Rail foot: account menu and version ----
   Two problems created by the rail being a 68px column at the LEFT EDGE of
   the window, when Dolibarr's account block assumes a top-right trigger.

   1. The dropdown is positioned below its trigger. With the trigger sitting at
      the very bottom of a full-height rail, the panel opened downwards and to
      the left -- 295px below the viewport and starting at x=-278. It was
      effectively invisible. It has to open UPWARD, and to the RIGHT of the
      rail, which is the only direction with room.

   2. The version block is 241px of inline content in a 68px column, so it
      overflowed the rail and never appeared. The rail foot stacks instead, and
      the version gets the light end of the ramp -- on this dark surface
      --c-muted is a near-invisible 1.4:1, the inverse of the light-surface
      rule. */
.wb-rail .dropdown-menu,
.wb-rail-foot .dropdown-menu {
	position: absolute;
	top: auto !important;
	bottom: 0;
	<?php echo $left; ?>: calc(var(--rail-w) - 8px) !important;
	<?php echo $right; ?>: auto !important;
	margin: 0;
	transform: none;
	z-index: 1400;
	max-height: calc(100vh - 32px);
	overflow-y: auto;
}

.wb-rail-foot .login_block,
.wb-rail-foot .login_block_other {
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: var(--sp-1);
	width: 100%;
	max-width: var(--rail-w);
}
.wb-rail-foot .login_block_elem { max-width: 100%; }

/* "24.0.0-beta" is 55px at 11px -- it fits a 68px rail once the row stops
   trying to lay itself out horizontally. */
/* The rail hides text labels via `.wb-rail-foot .login_block_other
   span:not([class*="fa"])`. That :not() gives it (0,3,1), which outranks a
   plain `.wb-rail span.aversion` (0,2,1) -- so the version stayed display:none
   however it was coloured. Matched at the same weight, and later in source
   order, to carve out this one span. */
.wb-rail-foot .login_block_other span.aversion,
.wb-rail .login_block_other span.aversion,
.wb-rail span.aversion,
.wb-rail-foot span.aversion,
/* The text lives in a nested span, which the same hide rule also caught --
   the outer block was visible at zero height until this was carved out too. */
.wb-rail-foot .login_block_other span.aversion span,
.wb-rail .login_block_other span.aversion span {
	display: block;
	width: 100%;
	text-align: center;
	color: var(--c-faint);
	font-size: 0.625rem;
	line-height: 1.4;
	white-space: nowrap;
	overflow: visible;
}
.wb-rail span.aversion:hover, .wb-rail-foot span.aversion:hover { color: #fff; }
