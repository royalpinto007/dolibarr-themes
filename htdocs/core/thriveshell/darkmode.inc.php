<?php
/* Copyright (C) 2026  Thrive / Accellier
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 */

/**
 *	\file       htdocs/core/thriveshell/darkmode.inc.php
 *	\brief      Dark mode for every Thrive shell.
 *
 *	All shells share one custom-property contract, so dark mode is a token
 *	override rather than a per-theme stylesheet. Each theme keeps its own
 *	accent hue -- lightened, because a mid-tone accent picked for white loses
 *	contrast on a dark surface -- and only the neutral ramp is replaced.
 *
 *	Include this AFTER the theme's :root block so it wins on source order.
 */

if (!defined('ISLOADEDBYSTEELSHEET')) {
	die('Must be loaded by a stylesheet');
}
/**
 * @var int $darkmode
 * @var string $left
 * @var string $right
 */

if (empty($darkmode)) {
	return;
}
?>

:root {
	color-scheme: dark;

	/* Neutral ramp, inverted. Surfaces stay separated by lightness so cards
	   still read as raised without relying on shadow, which is nearly
	   invisible on dark. */
	--c-ink: #E7ECF2;
	--c-ink-2: #C3CBD6;
	--c-muted: #94A0AE;
	--c-faint: #6E7B8A;
	--c-hairline: #212933;
	--c-border: #2B343F;
	--c-border-strong: #3D4854;
	--c-surface: #151B23;
	--c-canvas: #0D1218;
	--c-sunken: #1A222B;

	/* Keep the theme's hue, lift it for dark backgrounds. */
	--c-accent: color-mix(in srgb, var(--c-accent) 62%, #FFFFFF);
	--c-accent-hover: color-mix(in srgb, var(--c-accent) 75%, #FFFFFF);
	--c-accent-ink: color-mix(in srgb, var(--c-accent) 78%, #FFFFFF);
	--c-accent-soft: color-mix(in srgb, var(--c-accent) 20%, transparent);
	--c-accent-ring: color-mix(in srgb, var(--c-accent) 35%, transparent);

	--c-success: #4FB07E;
	--c-warning: #D9A441;
	--c-danger: #E0685F;
	--c-info: #6BA3D6;

	/* Shadows read as depth only when much stronger than on light. */
	--sh-sm: 0 1px 2px rgba(0, 0, 0, 0.35);
	--sh: 0 1px 3px rgba(0, 0, 0, 0.42);
	--sh-md: 0 8px 22px -6px rgba(0, 0, 0, 0.55);
	--sh-lg: 0 24px 60px -12px rgba(0, 0, 0, 0.72);

	/* Dolibarr's own contract, re-pointed at the dark ramp. */
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
	--colortext: var(--c-ink);
	--colortextlink: var(--c-accent-ink);
	--colortexttitle: var(--c-ink);
	--colortexttitlenotab: var(--c-ink);
	--colorwhite: var(--c-surface);
	--colorblack: var(--c-ink);
	--inputbackgroundcolor: #10161D;
	--inputbackgroundcolordisabled: #171E26;
	--inputbordercolor: #333E4A;
	--inputcolordisabled: var(--c-faint);
	--fieldrequiredcolor: var(--c-ink);
	--tableforfieldcolor: var(--c-muted);
	--tablevalidbgcolor: var(--c-accent-soft);
	--oddevencolor: var(--c-ink);
	--dolgraphbg: var(--c-surface);
	--tooltipbgcolor: rgba(231, 236, 242, 0.97);
	--tooltipfontcolor: #0D1218;
	--refidnocolor: var(--c-muted);
	--colorboxiconbg: var(--c-sunken);
	--colorboxstatsborder: var(--c-border);
	--infoboxmoduleenabledbgcolor: var(--c-accent-soft);
}

/* Dolibarr and third-party modules hardcode white backgrounds in places;
   without this they punch bright holes in a dark page. */
.bg-white, table.liste, table.border, div.tabBar, div.info-box, table.boxtable {
	background-color: var(--c-surface);
}

/* Images that are dark line-art on transparency disappear on dark surfaces.
   Only pictos are touched -- never photos, logos or user avatars. */
img.pictofixedwidth[src$=".png"],
td > img[src*="/img/"][src$=".png"] {
	filter: invert(0.88) hue-rotate(180deg);
}
img.userphoto, img.photo, img#img_logo, .dropdown-user-image { filter: none; }

/* The editor ships a light chrome of its own. */
.cke_top, .cke_bottom { background: var(--c-sunken) !important; }
.cke_contents, .cke_inner { background: var(--c-surface) !important; }

/* ---- Third-party markup that hardcodes light colours ----
   Found on a dark theme only, and only with real data on the page.

   Dolibarr paints agenda event blocks #F0F0F0 inline-ish, so the themed link
   colour on top measured 1.6:1. CKEditor ships its own #484848 button labels
   and a black resizer, which vanish on a dark toolbar. Both are given the
   theme's own surface and ink. */
table.cal_event, td.cal_event, .cal_event {
	background-color: var(--c-sunken) !important;
	border-color: var(--c-border) !important;
}
table.cal_event a, .cal_event a.cal_event_title, .cal_event a { color: var(--c-ink) !important; }
.cal_peruserviewname, .cal_current_month, .cal_other_month { color: var(--c-ink-2); }

.cke_button_label, .cke_combo_text, .cke_path_item, .cke_toolbar_text {
	color: var(--c-ink-2) !important;
}
.cke_resizer { border-color: transparent var(--c-border-strong) transparent transparent !important; }
.cke_panel_list a, .cke_panel_listItem a { color: var(--c-ink-2) !important; }
