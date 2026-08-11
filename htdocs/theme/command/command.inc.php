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
 *	\file       htdocs/theme/command/command.inc.php
 *	\brief      Stylesheet body for the COMMAND theme.
 *
 *	COMMAND has no sidebar and no module strip. Navigation is a Ctrl/Cmd+K
 *	palette plus a slim breadcrumb bar, which frees the full viewport width for
 *	content. The shell DOM comes from core/menus/standard/command.lib.php.
 */

if (!defined('ISLOADEDBYSTEELSHEET')) {
	die('Must be loaded by a stylesheet');
}

/**
 * @var Conf $conf
 * @var Translate $langs
 * @var User $user
 * @var string $path
 * @var string $theme
 * @var string $left
 * @var string $right
 * @var string $fontsize
 * @var string $fontsizesmaller
 * @var string $toolTipBgColor
 * @var string $toolTipFontColor
 * @var string $textSuccess
 * @var string $textWarning
 * @var string $textDanger
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
 * @var int $dol_hide_topmenu
 * @var int $dol_no_mouse_hover
 */

$darkmode = getDolGlobalInt('THEME_DARKMODEENABLED');
$barheight = 60;
?>

/* ==========================================================================
   COMMAND -- tokens
   ========================================================================== */

:root {
	color-scheme: light;

	/* Neutrals: a cool slate ramp. */
	--c-ink: #0B1220;
	--c-ink-2: #263244;
	--c-muted: #5B6B82;
	--c-faint: #8C9AAE;
	--c-hairline: #EEF0F3;
	--c-border: #E7E9EE;
	--c-border-strong: #C9D2DE;
	--c-surface: #FFFFFF;
	--c-canvas: #F7F8FA;
	--c-sunken: #F1F4F8;

	/* One accent. Indigo reads as "tool", not "brochure". */
	--c-accent: #4F46E5;
	--c-accent-hover: #4338CA;
	--c-accent-ink: #4338CA;
	--c-accent-soft: #EEF0FE;
	--c-accent-ring: rgba(79, 70, 229, 0.22);

	--c-success: #0F7B4F;
	--c-warning: #96610A;
	--c-danger: #B3261E;
	--c-info: #2B5C8A;

	--c-font: "Inter", "Segoe UI", -apple-system, BlinkMacSystemFont, Roboto, "Helvetica Neue", Arial, sans-serif;
	--c-mono: "SF Mono", "JetBrains Mono", "Cascadia Mono", Menlo, Consolas, monospace;

	--sp-1: 4px;  --sp-2: 8px;  --sp-3: 12px; --sp-4: 16px;
	--sp-5: 24px; --sp-6: 32px; --sp-7: 48px; --sp-8: 64px;

	--r-sm: 6px; --r: 8px; --r-lg: 12px; --r-xl: 16px; --r-pill: 999px;

	--sh-sm: 0 1px 2px rgba(11, 18, 32, 0.035);
	--sh: 0 1px 2px rgba(11, 18, 32, 0.035), 0 5px 14px -5px rgba(11, 18, 32, 0.07);
	--sh-md: 0 2px 4px rgba(11, 18, 32, 0.04), 0 12px 24px -6px rgba(11, 18, 32, 0.09);
	--sh-lg: 0 24px 64px -12px rgba(11, 18, 32, 0.28), 0 4px 12px rgba(11, 18, 32, 0.08);

	--bar-h: <?php echo $barheight; ?>px;
	--control-h: 40px;
	--table-row-h: 50px;
	--t: 120ms cubic-bezier(0.4, 0, 0.2, 1);

	/* Dolibarr's documented variable contract -- core and third-party module
	   CSS reference these names, so all of them stay defined. */
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
	--colorwhite: #ffffff;
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
	--colortextlink-h: 244; --colortextlink-s: 75%; --colortextlink-l: 51%; --colortextlink-a: 1;
}

<?php if ($darkmode) { ?>
:root {
	color-scheme: dark;
	--c-ink: #E6EAF2;
	--c-ink-2: #C2CBD9;
	--c-muted: #94A0B4;
	--c-faint: #6C7A8E;
	--c-hairline: #1E2632;
	--c-border: #28323F;
	--c-border-strong: #3A4654;
	--c-surface: #131A24;
	--c-canvas: #0C1119;
	--c-sunken: #182029;
	--c-accent: #818CF8;
	--c-accent-hover: #A5B0FB;
	--c-accent-ink: #A5B0FB;
	--c-accent-soft: #1E2340;
	--c-accent-ring: rgba(129, 140, 248, 0.28);
	--c-success: #4FB07E;
	--c-warning: #D9A441;
	--c-danger: #E0685F;
	--c-info: #6BA3D6;
	--sh-sm: 0 1px 2px rgba(0,0,0,0.3);
	--sh: 0 1px 3px rgba(0,0,0,0.35);
	--sh-md: 0 4px 12px rgba(0,0,0,0.4);
	--sh-lg: 0 24px 64px -12px rgba(0,0,0,0.7);
	--tooltipbgcolor: rgba(230,234,242,0.97);
	--tooltipfontcolor: #0B1220;
	--colorwhite: var(--c-surface);
}
<?php } ?>


/* ==========================================================================
   Base
   ========================================================================== */

*, *::before, *::after { box-sizing: border-box; }
html { -webkit-text-size-adjust: 100%; text-size-adjust: 100%; }

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

/* Figures align in columns -- the highest-value typographic choice in an ERP. */
td, th, input, .amount, .refid, .badge, .cmd-kbd { font-variant-numeric: tabular-nums; }

a { color: var(--colortextlink); text-decoration: none; transition: color var(--t); }
a:hover { color: var(--c-accent-hover); }
a.nounderline, a.nounderline:hover { text-decoration: none !important; }

:focus-visible { outline: 2px solid var(--c-accent); outline-offset: 1px; border-radius: var(--r-sm); }

h1, h2, h3, h4 {
	margin: 0 0 var(--sp-3) 0;
	font-weight: 620;
	line-height: 1.18;
	letter-spacing: -0.022em;
	color: var(--c-ink);
}
h1 { font-size: 1.75rem; } h2 { font-size: 1.375rem; }
h3 { font-size: 1.125rem; } h4 { font-size: 1rem; }
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



<?php /* Utilities, palette and component CSS are shared with the other
   Thrive shells: keeping a private copy here let COMMAND drift, and three
   separate fixes landed in the shared file without reaching this theme. */ ?>
<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/darkmode.inc.php'; ?>
<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/utilities.inc.php'; ?>

/* ==========================================================================
   The COMMAND bar
   ========================================================================== */

header.cmd-bar {
	/* Not sticky: Dolibarr nests this inside <header id="id-top"><div.tmenu>,
	   and a sticky element cannot outlive its containing block -- the bar
	   scrolled away with that short wrapper. Fixed escapes it. */
	position: fixed;
	top: 0;
	<?php echo $left; ?>: 0;
	<?php echo $right; ?>: 0;
	z-index: 1300;
	display: flex;
	align-items: center;
	gap: var(--sp-4);
	height: var(--bar-h);
	padding: 0 var(--sp-5);
	/* Near-opaque: at 82% the page scrolled visibly through the bar and the
	   text behind it competed with the controls. */
	background: color-mix(in srgb, var(--c-surface) 97%, transparent);
	-webkit-backdrop-filter: saturate(180%) blur(16px);
	backdrop-filter: saturate(180%) blur(16px);
	border-bottom: 1px solid var(--c-hairline);
}
<?php if (!empty($dol_hide_topmenu)) { ?>
header.cmd-bar { display: none; }
<?php } ?>

.cmd-brand {
	display: inline-flex;
	align-items: center;
	gap: var(--sp-2);
	color: var(--c-ink);
	font-weight: 620;
	letter-spacing: -0.018em;
	white-space: nowrap;
}
.cmd-brand:hover { color: var(--c-ink); }
.cmd-brand-mark {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 26px;
	height: 26px;
	border-radius: var(--r-sm);
	background: var(--c-accent);
	color: #fff;
	font-size: 0.8125rem;
	font-weight: 700;
}
.cmd-brand-text { max-width: 200px; overflow: hidden; text-overflow: ellipsis; }

.cmd-crumbs { display: flex; align-items: center; gap: var(--sp-2); min-width: 0; }
.cmd-crumb {
	color: var(--c-muted);
	font-size: <?php echo $fontsizesmaller; ?>;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}
.cmd-crumb-current {
	color: var(--c-ink);
	font-weight: 550;
	font-size: <?php echo $fontsize; ?>;
	padding-<?php echo $left; ?>: var(--sp-3);
	margin-<?php echo $left; ?>: var(--sp-1);
	border-<?php echo $left; ?>: 1px solid var(--c-border);
}
.cmd-crumb-sep {
	width: 5px; height: 5px;
	border-<?php echo $right; ?>: 1.5px solid var(--c-faint);
	border-top: 1.5px solid var(--c-faint);
	transform: rotate(45deg);
	opacity: 0.7;
}

/* The palette trigger is the primary navigation control, so it looks like an
   input rather than a button. */
.cmd-trigger {
	display: inline-flex;
	align-items: center;
	gap: var(--sp-2);
	min-width: 240px;
	height: var(--control-h);
	padding: 0 var(--sp-2) 0 var(--sp-3);
	margin-<?php echo $right; ?>: var(--sp-2);
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

@media only screen and (min-width: 993px) {
	header.cmd-bar .cmd-trigger {
		position: absolute;
		left: 50%;
		transform: translateX(-50%);
		width: clamp(380px, 30vw, 470px);
		margin: 0;
		background: var(--c-surface);
		box-shadow: var(--sh-sm);
	}
}

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

.cmd-bar-spacer { flex: 1 1 auto; }
.cmd-bar-tools { display: flex; align-items: center; }

/* Dolibarr's own tools/account block, relocated into the bar by command.js */
.cmd-bar-tools div.login_block {
	position: static;
	display: flex;
	align-items: center;
	gap: var(--sp-2);
	color: var(--c-muted);
	font-size: <?php echo $fontsizesmaller; ?>;
}
.cmd-bar-tools .login_block_other,
.cmd-bar-tools .login_block_tools,
.cmd-bar-tools .login_block_user { display: flex; align-items: center; gap: var(--sp-2); }
.cmd-bar-tools .login_block_elem {
	display: inline-flex; align-items: center; justify-content: center;
	min-width: 32px; height: 32px; padding: 0 var(--sp-1);
	border-radius: var(--r); transition: background var(--t);
}
.cmd-bar-tools .login_block_elem:hover { background: var(--c-sunken); color: var(--c-ink); }
.cmd-bar-tools a, .cmd-bar-tools a:hover { color: var(--c-muted); }
.cmd-bar-tools .atoplogin {
	display: inline-flex; align-items: center; gap: var(--sp-2);
	height: 34px; padding: 0 var(--sp-2);
	border-radius: var(--r-pill);
	color: var(--c-muted); font-weight: 500;
}
.cmd-bar-tools .atoplogin:hover { background: var(--c-sunken); color: var(--c-ink); }
/* Only the account chip is outlined. Applying the outline to every .atoplogin
   draws empty pills around the bare icon links beside it. */
.cmd-bar-tools #topmenu-login-dropdown > .atoplogin {
	padding-<?php echo $left; ?>: var(--sp-1);
	border: 1px solid var(--c-border);
	color: var(--c-ink-2);
}
/* Dolibarr emits placeholder anchors for disabled top-bar tools; with no icon
   inside they would render as empty boxes. */
.cmd-bar-tools .login_block_elem:empty,
.cmd-bar-tools .atoplogin:empty { display: none; }
img.userphoto { border-radius: 50%; object-fit: cover; }

/* Dolibarr's own left column stays suppressed -- navigation is the .cmd-nav
   rail emitted by command.lib.php instead. */
div.side-nav, #id-left, .menuhider { display: none !important; }
#id-container, .id-container { display: block; width: 100%; }
/* The offset lives on <body>, not on #id-right: several Dolibarr pages print
   markup outside the #id-right container (compta/sociales/list.php among
   them), and anything outside it slid underneath the fixed rail. Padding the
   body clears the chrome for every element on the page, stray or not. */
body {
	padding-<?php echo $left; ?>: var(--nav-w);
	padding-top: var(--bar-h);
	transition: padding var(--t);
}
body.bodylogin { padding: 0; }		/* the login page has no chrome */

#id-right {
	margin-<?php echo $left; ?>: 0 !important;
	min-width: 0;
	overflow-x: hidden;
}


/* ==========================================================================
   Persistent navigation
   The palette is fast but invisible; this is what makes the app browsable.
   ========================================================================== */

:root { --nav-w: 260px; --nav-w-collapsed: 68px; }

aside.cmd-nav {
	position: fixed;
	top: var(--bar-h);
	/* Fixed elements position against the viewport, so body padding does not
	   shift them -- they stay flush to the edges. */
	<?php echo $left; ?>: 0;
	bottom: 0;
	width: var(--nav-w);
	display: flex;
	flex-direction: column;
	background: var(--c-surface);
	border-<?php echo $right; ?>: 1px solid var(--c-hairline);
	z-index: 1100;
	transition: width var(--t);
}
.cmd-nav-scroll { flex: 1 1 auto; min-height: 0; overflow-y: auto; overflow-x: hidden; padding: var(--sp-3) var(--sp-2); }
/* Breathing room after an expanded module, so its children stay visually
   attached to it and separate from the next module. */
.cmd-nav-group.is-active { margin-bottom: var(--sp-3); }
.cmd-nav-list, .cmd-nav-sub { list-style: none; margin: 0; padding: 0; }

.cmd-nav-link {
	display: flex;
	align-items: center;
	gap: var(--sp-3);
	padding: var(--sp-2) var(--sp-3);
	margin-bottom: 1px;
	border-radius: var(--r);
	color: var(--c-ink-2);
	font-weight: 500;
	white-space: nowrap;
	transition: background var(--t), color var(--t);
	min-height: 42px;
	font-size: 0.875rem;
}
.cmd-nav-link:hover { background: var(--c-sunken); color: var(--c-ink); }
.cmd-nav-group.is-active > .cmd-nav-link {
	background: var(--c-accent-soft);
	color: var(--c-accent-ink);
	font-weight: 620;
}
.cmd-nav-icon {
	flex: 0 0 auto;
	width: 1.15em;
	text-align: center;
	color: var(--c-faint);
	font-size: 1.125rem;
}
.cmd-nav-group.is-active .cmd-nav-icon { color: var(--c-accent); }
.cmd-nav-link:hover .cmd-nav-icon { color: var(--c-muted); }
.cmd-nav-label { overflow: hidden; text-overflow: ellipsis; }

/* Sub-entries of the open module, with a guide rule so depth is readable */
.cmd-nav-sub {
	margin: var(--sp-1) 0 var(--sp-3) 0;
	padding-<?php echo $left; ?>: var(--sp-5);
	border-<?php echo $left; ?>: 1px solid var(--c-hairline);
	margin-<?php echo $left; ?>: var(--sp-4);
}
.cmd-nav-sublink {
	display: block;
	/* --lvl carries the entry's real depth, so repeated labels ("List",
	   "Customers") read as children of their parent rather than duplicates. */
	padding: 5px var(--sp-2) 5px calc(var(--sp-2) + (var(--lvl, 0) * 12px));
	border-radius: var(--r-sm);
	color: var(--c-muted);
	font-size: <?php echo $fontsizesmaller; ?>;
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
	transition: background var(--t), color var(--t);
}
.cmd-nav-sublink:hover { background: var(--c-sunken); color: var(--c-ink); }
/* The page you are actually on */
.cmd-nav-sublink.is-current {
	background: var(--c-sunken);
	color: var(--c-ink);
	font-weight: 600;
	box-shadow: inset 2px 0 0 var(--c-accent);
}

.cmd-nav-toggle {
	flex: 0 0 auto;
	display: flex;
	align-items: center;
	gap: var(--sp-3);
	width: 100%;
	padding: var(--sp-3) var(--sp-4);
	background: transparent;
	border: 0;
	border-top: 1px solid var(--c-hairline);
	color: var(--c-faint);
	font-family: inherit;
	font-size: <?php echo $fontsizesmaller; ?>;
	cursor: pointer;
	transition: color var(--t), background var(--t);
}
.cmd-nav-toggle:hover { color: var(--c-ink); background: var(--c-sunken); }
.cmd-nav-toggle-icon {
	width: 7px; height: 7px;
	border-<?php echo $left; ?>: 1.5px solid currentColor;
	border-bottom: 1.5px solid currentColor;
	transform: rotate(45deg);
	margin-<?php echo $left; ?>: 4px;
	transition: transform var(--t);
}

/* Collapsed: an icon rail. Labels are dropped, not hidden, so the row stays
   centred; the module name still reaches screen readers via the link title. */
body.cmd-nav-collapsed { --nav-w: var(--nav-w-collapsed); }
body.cmd-nav-collapsed .cmd-nav-label,
body.cmd-nav-collapsed .cmd-nav-sub,
body.cmd-nav-collapsed .cmd-nav-toggle-label { display: none; }
body.cmd-nav-collapsed .cmd-nav-link { justify-content: center; padding-left: 0; padding-right: 0; }
body.cmd-nav-collapsed .cmd-nav-toggle { justify-content: center; }
body.cmd-nav-collapsed .cmd-nav-toggle-icon { transform: rotate(-135deg); margin: 0; }


<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/palette.inc.php'; ?>

<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/components.inc.php'; ?>

/* ==========================================================================
   Responsive and print
   ========================================================================== */

@media only screen and (max-width: 992px) {
	.fiche { padding: var(--sp-4); }
	:root { --nav-w: var(--nav-w-collapsed); }
	.cmd-nav-label, .cmd-nav-sub, .cmd-nav-toggle-label { display: none; }
	.cmd-nav-scroll { padding: var(--sp-2) var(--sp-1); }
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

<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/navtree.inc.php'; ?>
<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/select2.inc.php'; ?>
<?php include DOL_DOCUMENT_ROOT.'/core/thriveshell/modern.inc.php'; ?>


<?php
/* Icon and flag maps carried from eldy: menu-key -> glyph and country flag
   sprite. Data, not styling. */
include __DIR__.'/main_menu_fa_icons.inc.php';
include __DIR__.'/flags-sprite.inc.php';
?>
