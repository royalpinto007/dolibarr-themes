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
   Utilities
   Dolibarr's PHP emits these class names and depends on them for layout, not
   decoration, so they are behavioural and reimplemented in meaning.
   ========================================================================== */

.hidden, .hideobject { display: none !important; }
.clearboth { clear: both; }
.nowrap, .nowraponall { white-space: nowrap !important; }
.wrapimp { white-space: normal !important; }
.wordbreak { word-break: break-all; }
.center { text-align: center !important; }
.left { text-align: <?php echo $left; ?> !important; }
.right { text-align: <?php echo $right; ?> !important; }
.floatleft, .float { float: <?php echo $left; ?>; }
.floatright { float: <?php echo $right; ?>; }
.floatnone { float: none; }
.inline-block { display: inline-block; }
.inline-blockimp { display: inline-block !important; }
.block { display: block; }
.valignmiddle { vertical-align: middle; }
.valigntop { vertical-align: top; }
.valignbottom { vertical-align: bottom; }
.bold, .strong { font-weight: 600 !important; }
.nobold { font-weight: 400 !important; }
.italic { font-style: italic; }
.uppercase { text-transform: uppercase; }
.small, .smallfont { font-size: <?php echo $fontsizesmaller; ?>; }
.opacitymedium { opacity: 0.62; }
.opacitymediumbycolor { color: var(--c-muted); }
.opacityhigh { opacity: 0.4; }
.opacitylow { opacity: 0.8; }
.cursorpointer { cursor: pointer; }
.cursormove { cursor: move; }
.cursornotallowed { cursor: not-allowed; }
.marginleftonly { margin-<?php echo $left; ?>: var(--sp-2); }
.marginrightonly { margin-<?php echo $right; ?>: var(--sp-2); }
.margintoponly { margin-top: var(--sp-2); }
.marginbottomonly { margin-bottom: var(--sp-2); }
.marginleftonlyshort { margin-<?php echo $left; ?>: var(--sp-1); }
.marginrightonlyshort { margin-<?php echo $right; ?>: var(--sp-1); }
.nomargin { margin: 0 !important; }
.paddingleft { padding-<?php echo $left; ?>: var(--sp-2); }
.paddingright { padding-<?php echo $right; ?>: var(--sp-2); }
.paddingtop { padding-top: var(--sp-2); }
.paddingbottom { padding-bottom: var(--sp-2); }
.padding { padding: var(--sp-3); }
.nopadding { padding: 0 !important; }
.nopaddingleft { padding-<?php echo $left; ?>: 0 !important; }
.nopaddingright { padding-<?php echo $right; ?>: 0 !important; }
.centpercent { width: 100%; }
.quatrevingtpercent { width: 80%; }
.moitie { width: 50%; }
/* Dolibarr puts its tooltip classes on links, spans and whole blocks -- 45-67
   elements on a typical page -- so a blanket `cursor: help` turned the pointer
   into a question-mark arrow over most of the UI. Links must stay a pointer and
   blocks a normal arrow; the help cursor belongs only to a standalone info
   glyph, where it actually means "hover for an explanation". */
.classfortooltip, .classforajaxtooltip, .classfortooltiponclick { cursor: inherit; }
a.classfortooltip, a.classforajaxtooltip { cursor: pointer; }

/* The help cursor is not used anywhere in this theme -- it reads as a stray
   question mark rather than an affordance. Tooltip targets keep the cursor
   their element type implies. */
/* Dolibarr writes `cursor: help` as an INLINE style from PHP on info pictos,
   so a stylesheet rule cannot win without !important. This is the one place
   the theme needs it. */
.classfortooltip, .classforajaxtooltip, .classfortooltiponclick,
[title], [class*="fa-info"], [class*="fa-question"] { cursor: inherit !important; }
a[title], a.classfortooltip, a.classforajaxtooltip { cursor: pointer !important; }
input[title], select[title], textarea[title] { cursor: auto !important; }
button[title], label[title] { cursor: pointer !important; }
.pictofixedwidth { width: 1.35em; text-align: center; display: inline-block; }
.fieldrequired { font-weight: 600; color: var(--fieldrequiredcolor); }
.fieldrequired::after { content: " *"; color: var(--c-danger); }
.error { color: var(--c-danger) !important; }
.warning { color: var(--c-warning) !important; }
.ok, .green { color: var(--c-success) !important; }
.red { color: var(--c-danger) !important; }
.orange { color: var(--c-warning) !important; }
.hideonsmartphone { }
.nocellnopadd { padding: 0 !important; }
.noborderspacing { border-spacing: 0; }

[class*="tdoverflowmax"] { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
<?php
foreach (array(20, 25, 30, 35, 40, 50, 60, 65, 70, 75, 80, 90, 100, 125, 150, 175, 200, 250, 300, 350, 400, 500, 750) as $w) {
	echo '.tdoverflowmax'.$w.' { max-width: '.$w.'px; }'."\n";
}
foreach (array(50, 75, 100, 125, 150, 200, 250, 300, 400, 500, 750) as $w) {
	echo '.maxwidth'.$w.' { max-width: '.$w.'px; }'."\n";
	echo '.minwidth'.$w.' { min-width: '.$w.'px; }'."\n";
	echo '.width'.$w.' { width: '.$w.'px; }'."\n";
}
?>


