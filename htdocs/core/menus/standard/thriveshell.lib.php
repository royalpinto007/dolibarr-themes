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
 *	\file       core/menus/standard/thriveshell.lib.php
 *	\brief      Shared shell framework for the Thrive theme family.
 *
 *	Builds Dolibarr's menu tree once and renders it as one of several shells
 *	(workbench, aurora, editorial, dense). Each theme picks its shell; the tree
 *	building, permission filtering, de-duplication and active-entry matching are
 *	shared so a fix lands in every shell at once.
 */


/**
 *  Whether the current user may see a menu entry.
 *
 *  Mirrors the enabled/perms evaluation Dolibarr's own managers apply, so
 *  entries the user has no rights for never reach the palette.
 *
 *  @param  array<string,mixed> $entry Menu entry from Menubase
 *  @return bool
 */
function thriveshell_entry_allowed($entry)
{
	global $conf, $user, $langs, $db, $mainmenu, $leftmenu;

	if (isset($entry['enabled']) && $entry['enabled'] !== '' && $entry['enabled'] !== null) {
		$enabled = $entry['enabled'];
		if (!is_numeric($enabled)) {
			$enabled = (int) dol_eval((string) $enabled, 1, 1, '2');
		}
		if (empty($enabled)) {
			return false;
		}
	}
	if (isset($entry['perms']) && $entry['perms'] !== '' && $entry['perms'] !== null) {
		$perms = $entry['perms'];
		if (!is_numeric($perms)) {
			$perms = (int) dol_eval((string) $perms, 1, 1, '2');
		}
		if (empty($perms)) {
			return false;
		}
	}
	return true;
}


/**
 *  Build the navigation tree: top-level modules, each with its sub-entries.
 *
 *  @param  DoliDB                              $db        Database handler
 *  @param  array<int,array<string,mixed>>      $tabMenu   Menu entries from Menubase
 *  @param  string                              $atarget   Link target
 *  @param  int<0,1>                            $type_user 0=internal, 1=external
 *  @return array<int,array<string,mixed>>                 Groups with 'items'
 */
function thriveshell_build_tree($db, &$tabMenu, $atarget, $type_user)
{
	require_once DOL_DOCUMENT_ROOT.'/core/menus/standard/eldy.lib.php';
	require_once DOL_DOCUMENT_ROOT.'/core/class/menu.class.php';

	global $conf, $langs, $user;

	$groups = array();

	// print_eldy_menu() reads the active module from the session rather than
	// taking it as an argument, so the per-module sub-entry pass below swaps
	// the session value and restores it afterwards.
	$savedMain = isset($_SESSION['mainmenu']) ? $_SESSION['mainmenu'] : null;
	$savedLeft = isset($_SESSION['leftmenu']) ? $_SESSION['leftmenu'] : null;

	// Which module/branch the user is actually in. Dolibarr nests a second
	// level under some entries (Setup -> Display, Security, Emails ...) and
	// only emits it when leftmenu names that branch. Asking every module for
	// leftmenu='' therefore hides those pages entirely, so the open module is
	// queried with the real leftmenu while the rest stay collapsed.
	$curMain = GETPOSTISSET('mainmenu') ? GETPOST('mainmenu', 'aZ09') : (string) $savedMain;
	$curLeft = GETPOSTISSET('leftmenu') ? GETPOST('leftmenu', 'aZ09') : (string) $savedLeft;

	// Top-level entries, in Dolibarr's own order.
	$topmenu = new Menu();
	print_eldy_menu($db, $atarget, $type_user, $tabMenu, $topmenu, 1, 'top');

	foreach ($topmenu->liste as $top) {
		if (empty($top['mainmenu'])) {
			continue;
		}
		$mm = $top['mainmenu'];
		// Titles arrive already HTML-encoded (e.g. "Users &amp; Groups").
		// Decode once here so the single escape on output is correct.
		$title = trim(html_entity_decode(strip_tags((string) $top['titre']), ENT_QUOTES, 'UTF-8'));
		if ($title === '') {
			continue;
		}

		// Dolibarr ships the glyph on the entry itself as ready-made markup.
		// The alternative -- the .mainmenu.<module>::before rules -- is
		// generated inside eldy's global.inc.php, which this theme does not load.
		$icon = (!empty($top['prefix'])) ? $top['prefix'] : '<span class="fas fa-circle"></span>';

		$group = array(
			'key'   => $mm,
			'title' => $title,
			'url'   => thriveshell_abs_url((string) $top['url']),
			'icon'  => $icon,
			'items' => array(),
		);

		// Sub-entries for this module. print_left_eldy_menu() takes the module
		// explicitly, but still reads the session in places, so both are set.
		$useLeft = ($mm === $curMain) ? $curLeft : '';
		$_SESSION['mainmenu'] = $mm;
		$_SESSION['leftmenu'] = $useLeft;
		$sub = new Menu();
		$before = array();
		$after = array();
		$seen = array();
		print_left_eldy_menu($db, $before, $after, $tabMenu, $sub, 1, $mm, $useLeft, null, $type_user);
		foreach ($sub->liste as $item) {
			$label = trim(html_entity_decode(strip_tags((string) $item['titre']), ENT_QUOTES, 'UTF-8'));
			if ($label === '' || empty($item['url'])) {
				continue;
			}
			$absUrl = thriveshell_abs_url((string) $item['url']);
			// Dolibarr's left menus repeat entries (e.g. "List" once per
			// third-party type), which reads as noise in a flat tree.
			$dedupeKey = $label.'|'.$absUrl;
			if (isset($seen[$dedupeKey])) {
				continue;
			}
			$seen[$dedupeKey] = true;

			$group['items'][] = array(
				'title' => $label,
				'url'   => $absUrl,
				'level' => isset($item['level']) ? (int) $item['level'] : 0,
			);
		}

		$groups[] = $group;
	}

	// Restore the session exactly as we found it.
	if ($savedMain === null) {
		unset($_SESSION['mainmenu']);
	} else {
		$_SESSION['mainmenu'] = $savedMain;
	}
	if ($savedLeft === null) {
		unset($_SESSION['leftmenu']);
	} else {
		$_SESSION['leftmenu'] = $savedLeft;
	}

	return $groups;
}


/**
 *  Make a menu URL absolute against DOL_URL_ROOT.
 *
 *  @param  string $url Raw menu url
 *  @return string
 */
function thriveshell_abs_url($url)
{
	// Menu urls arrive already HTML-encoded (&amp;). Escaping them again on
	// output would produce &amp;amp; and rename the query parameter that
	// follows, so decode once here and let the caller escape exactly once.
	$url = trim(html_entity_decode($url, ENT_QUOTES, 'UTF-8'));
	if ($url === '' || $url === '#') {
		return '';
	}
	if (preg_match('/^https?:\/\//i', $url)) {
		return $url;
	}
	// Guard against DOL_URL_ROOT being '' (Dolibarr at the web root): strpos()
	// with an empty needle returns 0, which made this match every url and skip
	// the dol_buildpath resolution below.
	if (DOL_URL_ROOT !== '' && strpos($url, DOL_URL_ROOT) === 0) {
		return $url;
	}

	// Modules installed under htdocs/custom register menu urls without that
	// prefix ("/ebayreconcile/reconcile.php"), and Dolibarr resolves them with
	// dol_buildpath against dolibarr_main_url_root_alt. Prepending DOL_URL_ROOT
	// blindly produced a 404 for every custom module's menu entry.
	$path = $url;
	$query = '';
	$qpos = strpos($path, '?');
	if ($qpos !== false) {
		$query = substr($path, $qpos);
		$path = substr($path, 0, $qpos);
	}
	$built = dol_buildpath($path, 1);
	if ($built !== '') {
		return $built.$query;
	}

	return DOL_URL_ROOT.(strncmp($url, '/', 1) === 0 ? '' : '/').$url;
}


/**
 *  Score how well a menu URL matches the page currently being viewed.
 *
 *  The script path must match; beyond that the score is the number of the
 *  entry's own query parameters that the request also carries, so
 *  "list.php?type=c" beats a bare "list.php" on the customers page.
 *
 *  @param  string $url Menu entry url (absolute)
 *  @return int         -1 when the path differs, otherwise a match score
 */
function thriveshell_match_score($url)
{
	if ($url === '') {
		return -1;
	}
	$parts = parse_url($url);
	if (empty($parts['path'])) {
		return -1;
	}
	$self = (string) $_SERVER['PHP_SELF'];
	if (basename($parts['path']) !== basename($self)) {
		return -1;
	}
	// Compare directories too, so product/list.php never matches societe/list.php.
	if (rtrim(dirname($parts['path']), '/') !== rtrim(dirname($self), '/')) {
		return -1;
	}

	$score = 0;
	if (!empty($parts['query'])) {
		$q = array();
		parse_str(html_entity_decode($parts['query']), $q);
		foreach ($q as $k => $v) {
			if ($k === 'mainmenu' || $k === 'leftmenu' || $v === '') {
				continue;
			}
			if (isset($_GET[$k]) && (string) $_GET[$k] === (string) $v) {
				$score += 10;
			} else {
				return -1;	// entry demands a value the page does not have
			}
		}
	}
	return $score;
}


/**
 *  Shared preamble: resolve the active module and common labels.
 *
 *  @param  array<int,array<string,mixed>> $tree Navigation tree
 *  @return array<string,mixed>                  Context for the renderers
 */
function thriveshell_context($tree)
{
	global $langs;

	$currentMain = GETPOSTISSET('mainmenu') ? GETPOST('mainmenu', 'aZ09') : (isset($_SESSION['mainmenu']) ? $_SESSION['mainmenu'] : '');
	$currentTitle = '';
	$currentItems = array();
	foreach ($tree as $g) {
		if ($g['key'] === $currentMain) {
			$currentTitle = $g['title'];
			$currentItems = $g['items'];
			break;
		}
	}

	$brand = getDolGlobalString('MAIN_INFO_SOCIETE_NOM');
	if ($brand === '') {
		$brand = 'Dolibarr';
	}

	return array(
		'main'   => $currentMain,
		'title'  => $currentTitle,
		'items'  => $currentItems,
		'brand'  => $brand,
		'home'   => DOL_URL_ROOT.'/index.php?mainmenu=home',
	);
}


/**
 *  Index of the sub-entry matching the page currently open.
 *
 *  @param  array<int,array<string,mixed>> $items Sub-entries
 *  @return int                                   Index, or -1
 */
function thriveshell_current_item($items)
{
	$bestIdx = -1;
	$bestScore = -1;
	foreach ($items as $idx => $it) {
		$sc = thriveshell_match_score($it['url']);
		if ($sc > $bestScore) {
			$bestScore = $sc;
			$bestIdx = $idx;
		}
	}
	return $bestScore >= 0 ? $bestIdx : -1;
}


/**
 *  Serialise the tree for the shared command palette.
 *
 *  @param  array<int,array<string,mixed>> $tree Navigation tree
 *  @return void
 */
function thriveshell_print_palette_data($tree)
{
	$payload = array();
	$seen = array();
	foreach ($tree as $g) {
		if ($g['url'] !== '') {
			$payload[] = array('g' => $g['title'], 't' => $g['title'], 'u' => $g['url']);
		}
		foreach ($g['items'] as $it) {
			$key = $it['title'].'|'.$it['url'];
			if (isset($seen[$key])) {
				continue;
			}
			$seen[$key] = true;
			$payload[] = array('g' => $g['title'], 't' => $it['title'], 'u' => $it['url']);
		}
	}
	print '<script type="application/json" id="cmd-nav-data">';
	print json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
	print '</script>'."\n";
}


/**
 *  The command palette markup, shared by every shell.
 *
 *  Navigation the user can see is what makes an app browsable; the palette is
 *  the accelerator on top of it, never the only way in.
 *
 *  @return void
 */
function thriveshell_print_palette()
{
	global $langs;

	print '<div class="cmd-palette" id="cmd-palette" role="dialog" aria-modal="true" aria-label="'.dol_escape_htmltag($langs->trans('Search')).'" hidden>';
	print '<div class="cmd-palette-backdrop" data-cmd-close></div>';
	print '<div class="cmd-palette-panel">';
	print '<div class="cmd-palette-input-row">';
	print '<span class="fas fa-search cmd-palette-input-icon" aria-hidden="true"></span>';
	print '<input type="text" class="cmd-palette-input" id="cmd-palette-input" autocomplete="off" spellcheck="false" placeholder="'.dol_escape_htmltag($langs->trans('Search')).'…">';
	print '<kbd class="cmd-kbd">Esc</kbd>';
	print '</div>';
	print '<div class="cmd-palette-results" id="cmd-palette-results" role="listbox"></div>';
	print '<div class="cmd-palette-foot">';
	print '<span><kbd class="cmd-kbd">↑</kbd><kbd class="cmd-kbd">↓</kbd> '.dol_escape_htmltag($langs->trans('Navigation')).'</span>';
	print '<span><kbd class="cmd-kbd">↵</kbd> '.dol_escape_htmltag($langs->trans('Open')).'</span>';
	print '</div>';
	print '</div></div>'."\n";
}


/**
 *  Palette trigger button, shared by every shell.
 *
 *  @param  string $extraClass Additional class for shell-specific placement
 *  @return void
 */
function thriveshell_print_trigger($extraClass = '')
{
	global $langs;

	print '<button type="button" class="cmd-trigger '.$extraClass.'" id="cmd-trigger" aria-haspopup="dialog">';
	print '<span class="cmd-trigger-icon fas fa-search" aria-hidden="true"></span>';
	print '<span class="cmd-trigger-label">'.dol_escape_htmltag($langs->trans('Search')).'</span>';
	print '<kbd class="cmd-kbd">Ctrl</kbd><kbd class="cmd-kbd">K</kbd>';
	print '</button>';
}


/**
 *  Turn Dolibarr's flat, level-tagged entry list into a real tree.
 *
 *  Rendering the list flat means a module like Home dumps 40+ rows at once,
 *  which is a list, not a menu. Nesting lets each branch fold.
 *
 *  @param  array<int,array<string,mixed>> $items Flat sub-entries
 *  @return array<int,array<string,mixed>>        Nested nodes with 'children'
 */
function thriveshell_nest_items($items)
{
	$root = array();
	// Level -> reference to the child list new nodes at that depth append to.
	$slots = array();
	$slots[-1] = &$root;

	foreach ($items as $it) {
		$lvl = max(0, min(6, (int) $it['level']));
		// A jump in depth with no parent emitted (Dolibarr does this) would
		// otherwise drop the entry, so climb to the nearest real parent.
		while ($lvl > 0 && !isset($slots[$lvl - 1])) {
			$lvl--;
		}

		$node = $it;
		$node['children'] = array();
		$slots[$lvl - 1][] = $node;
		$last = count($slots[$lvl - 1]) - 1;

		// Anything deeper than this node is no longer a valid parent.
		foreach (array_keys($slots) as $k) {
			if ($k >= $lvl) {
				unset($slots[$k]);
			}
		}
		$slots[$lvl] = &$slots[$lvl - 1][$last]['children'];
	}

	return $root;
}


/**
 *  Mark the node for the current page, and open every ancestor of it.
 *
 *  @param  array<int,array<string,mixed>> $nodes   Nested nodes (by reference)
 *  @param  string                          $curUrl Url of the current entry
 *  @return bool                                    True when the branch holds it
 */
function thriveshell_open_branch(&$nodes, $curUrl)
{
	$found = false;
	foreach ($nodes as &$n) {
		$isCur = ($curUrl !== '' && $n['url'] === $curUrl);
		$n['current'] = $isCur;
		$childHas = !empty($n['children']) ? thriveshell_open_branch($n['children'], $curUrl) : false;
		// Open the path down to the current page -- and the current node itself
		// when it has children, or landing on "Setup" would hide its pages.
		$n['open'] = $childHas || ($isCur && !empty($n['children']));
		if ($isCur || $childHas) {
			$found = true;
		}
	}
	unset($n);
	return $found;
}


/**
 *  Render nested entries, with a fold control on any node that has children.
 *
 *  @param  array<int,array<string,mixed>> $nodes   Nested nodes
 *  @param  string                          $linkCls Class for each link
 *  @param  int                             $depth   Current depth
 *  @return void
 */
function thriveshell_print_nodes($nodes, $linkCls, $depth = 0)
{
	foreach ($nodes as $n) {
		$hasKids = !empty($n['children']);
		$open = !empty($n['open']);

		print '<div class="ts-node'.($hasKids ? ' has-kids' : '').($open ? ' is-open' : '').'">';
		print '<div class="ts-row" style="--lvl:'.$depth.'">';

		if ($hasKids) {
			// A separate control, so the label stays a plain link: clicking the
			// name navigates, clicking the caret folds.
			print '<button type="button" class="ts-caret" data-ts-fold aria-expanded="'.($open ? 'true' : 'false').'" aria-label="'.dol_escape_htmltag($n['title']).'"></button>';
		} else {
			print '<span class="ts-caret is-empty" aria-hidden="true"></span>';
		}

		$cls = $linkCls.(!empty($n['current']) ? ' is-current' : '');
		print '<a class="'.$cls.'" href="'.dol_escape_htmltag($n['url']).'">';
		print dol_escape_htmltag($n['title']);
		print '</a>';
		print '</div>';

		if ($hasKids) {
			print '<div class="ts-children">';
			thriveshell_print_nodes($n['children'], $linkCls, $depth + 1);
			print '</div>';
		}
		print '</div>';
	}
}


/**
 *  Render the active module's sub-entries as a foldable tree.
 *
 *  @param  array<int,array<string,mixed>> $items    Flat sub-entries
 *  @param  string                          $linkCls Class for each link
 *  @return void
 */
function thriveshell_print_subitems($items, $linkCls = 'ts-sublink', $flat = false)
{
	if (empty($items)) {
		return;
	}
	$cur = thriveshell_current_item($items);
	$curUrl = ($cur >= 0) ? $items[$cur]['url'] : '';

	// A horizontal strip cannot fold: collapsed children would simply vanish
	// from the row. Those shells take the flat list and rely on scrolling.
	if ($flat) {
		foreach ($items as $idx => $it) {
			$cls = $linkCls.($idx === $cur ? ' is-current' : '');
			print '<a class="'.$cls.'" href="'.dol_escape_htmltag($it['url']).'">';
			print dol_escape_htmltag($it['title']);
			print '</a>';
		}
		return;
	}

	$nodes = thriveshell_nest_items($items);
	thriveshell_open_branch($nodes, $curUrl);
	thriveshell_print_nodes($nodes, $linkCls, 0);
}


/**
 *  WORKBENCH -- two-tier navigation: a module icon rail, plus a panel showing
 *  the active module's entries. No top bar; the rail carries brand and account.
 *
 *  @param  array<int,array<string,mixed>> $tree Navigation tree
 *  @return void
 */
function thriveshell_render_workbench($tree)
{
	global $langs;
	$c = thriveshell_context($tree);

	print '<div class="wb-rail">';
	print '<a class="wb-brand" href="'.dol_escape_htmltag($c['home']).'" title="'.dol_escape_htmltag($c['brand']).'">';
	print dol_escape_htmltag(dol_substr($c['brand'], 0, 1));
	print '</a>';
	print '<nav class="wb-rail-nav" aria-label="'.dol_escape_htmltag($langs->trans('Menu')).'">';
	foreach ($tree as $g) {
		$cls = 'wb-rail-item'.($g['key'] === $c['main'] ? ' is-active' : '');
		print '<a class="'.$cls.'" href="'.dol_escape_htmltag($g['url'] !== '' ? $g['url'] : '#').'" title="'.dol_escape_htmltag($g['title']).'">';
		print '<span class="wb-rail-icon" aria-hidden="true">'.$g['icon'].'</span>';
		print '<span class="wb-rail-tip">'.dol_escape_htmltag($g['title']).'</span>';
		print '</a>';
	}
	print '</nav>';
	print '<div class="wb-rail-foot" id="cmd-bar-tools"></div>';
	print '</div>';

	print '<div class="wb-panel">';
	print '<div class="wb-panel-head">';
	print '<span class="wb-panel-title">'.dol_escape_htmltag($c['title'] !== '' ? $c['title'] : $langs->trans('Home')).'</span>';
	print '</div>';
	thriveshell_print_trigger('wb-search');
	print '<nav class="wb-panel-nav">';
	thriveshell_print_subitems($c['items'], 'wb-sublink');
	print '</nav>';
	print '</div>';
}


/**
 *  AURORA -- dark-first. Slim glass top bar over a collapsible categorized
 *  sidebar; the dashboard reads as a bento of translucent cards.
 *
 *  @param  array<int,array<string,mixed>> $tree Navigation tree
 *  @return void
 */
function thriveshell_render_aurora($tree)
{
	global $langs;
	$c = thriveshell_context($tree);

	print '<header class="au-bar">';
	print '<a class="au-brand" href="'.dol_escape_htmltag($c['home']).'" title="'.dol_escape_htmltag($c['brand']).'">';
	print '<span class="au-brand-mark" aria-hidden="true"></span>';
	print '<span class="au-brand-text">'.dol_escape_htmltag($c['brand']).'</span>';
	print '</a>';
	if ($c['title'] !== '') {
		print '<span class="au-here">'.dol_escape_htmltag($c['title']).'</span>';
	}
	thriveshell_print_trigger('au-search');
	print '<div class="au-bar-spacer"></div>';
	print '<div class="au-bar-tools" id="cmd-bar-tools"></div>';
	print '</header>';

	print '<aside class="au-side" aria-label="'.dol_escape_htmltag($langs->trans('Menu')).'">';
	print '<div class="au-side-scroll">';
	foreach ($tree as $g) {
		$isActive = ($g['key'] === $c['main']);
		print '<div class="au-group'.($isActive ? ' is-active' : '').'">';
		print '<a class="au-group-link" href="'.dol_escape_htmltag($g['url'] !== '' ? $g['url'] : '#').'">';
		print '<span class="au-group-icon" aria-hidden="true">'.$g['icon'].'</span>';
		print '<span class="au-group-label">'.dol_escape_htmltag($g['title']).'</span>';
		print '</a>';
		if ($isActive && !empty($g['items'])) {
			print '<div class="au-sub">';
			thriveshell_print_subitems($g['items'], 'au-sublink');
			print '</div>';
		}
		print '</div>';
	}
	print '</div></aside>';
}


/**
 *  EDITORIAL -- design-forward. Oversized display type, wide margins and a
 *  typographic sidebar with no chrome: the page reads as a designed document
 *  rather than an admin panel.
 *
 *  @param  array<int,array<string,mixed>> $tree Navigation tree
 *  @return void
 */
function thriveshell_render_editorial($tree)
{
	global $langs;
	$c = thriveshell_context($tree);

	print '<aside class="ed-side" aria-label="'.dol_escape_htmltag($langs->trans('Menu')).'">';
	print '<a class="ed-brand" href="'.dol_escape_htmltag($c['home']).'">'.dol_escape_htmltag($c['brand']).'</a>';
	thriveshell_print_trigger('ed-search');
	print '<nav class="ed-nav">';
	foreach ($tree as $g) {
		$isActive = ($g['key'] === $c['main']);
		print '<div class="ed-group'.($isActive ? ' is-active' : '').'">';
		print '<a class="ed-group-link" href="'.dol_escape_htmltag($g['url'] !== '' ? $g['url'] : '#').'">';
		print '<span class="ed-group-num" aria-hidden="true"></span>';
		print dol_escape_htmltag($g['title']);
		print '</a>';
		if ($isActive && !empty($g['items'])) {
			print '<div class="ed-sub">';
			thriveshell_print_subitems($g['items'], 'ed-sublink');
			print '</div>';
		}
		print '</div>';
	}
	print '</nav>';
	print '<div class="ed-side-foot" id="cmd-bar-tools"></div>';
	print '</aside>';
}


/**
 *  DENSE -- data-first. Modules as a horizontal text tab strip, the active
 *  module's entries as a second strip, and no sidebar at all, so the table
 *  gets the entire viewport.
 *
 *  @param  array<int,array<string,mixed>> $tree Navigation tree
 *  @return void
 */
function thriveshell_render_dense($tree)
{
	global $langs;
	$c = thriveshell_context($tree);

	print '<header class="dn-bar">';
	print '<a class="dn-brand" href="'.dol_escape_htmltag($c['home']).'" title="'.dol_escape_htmltag($c['brand']).'">'.dol_escape_htmltag($c['brand']).'</a>';
	print '<nav class="dn-tabs" aria-label="'.dol_escape_htmltag($langs->trans('Menu')).'">';
	foreach ($tree as $g) {
		$cls = 'dn-tab'.($g['key'] === $c['main'] ? ' is-active' : '');
		print '<a class="'.$cls.'" href="'.dol_escape_htmltag($g['url'] !== '' ? $g['url'] : '#').'">';
		print dol_escape_htmltag($g['title']);
		print '</a>';
	}
	print '</nav>';
	print '<div class="dn-bar-spacer"></div>';
	thriveshell_print_trigger('dn-search');
	print '<div class="dn-bar-tools" id="cmd-bar-tools"></div>';
	print '</header>';

	if (!empty($c['items'])) {
		print '<nav class="dn-subbar" aria-label="'.dol_escape_htmltag($c['title']).'">';
		thriveshell_print_subitems($c['items'], 'dn-subtab', true);
		print '</nav>';
	}
}


/**
 *  Entry point: build the tree once and render the shell the theme asks for.
 *
 *  @param  string                          $shell     Shell name
 *  @param  DoliDB                          $db        Database handler
 *  @param  array<int,array<string,mixed>>  $tabMenu   Menu entries
 *  @param  string                          $atarget   Link target
 *  @param  int<0,1>                        $type_user 0=internal, 1=external
 *  @return void
 */
function thriveshell_render($shell, $db, &$tabMenu, $atarget, $type_user)
{
	global $langs;

	$langs->loadLangs(array('main', 'other'));
	$tree = thriveshell_build_tree($db, $tabMenu, $atarget, $type_user);

	switch ($shell) {
		case 'workbench':
			thriveshell_render_workbench($tree);
			break;
		case 'aurora':
			thriveshell_render_aurora($tree);
			break;
		case 'editorial':
			thriveshell_render_editorial($tree);
			break;
		case 'dense':
			thriveshell_render_dense($tree);
			break;
		default:
			return;
	}

	thriveshell_print_palette();
	thriveshell_print_palette_data($tree);
}
