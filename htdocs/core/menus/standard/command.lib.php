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
 *	\file       core/menus/standard/command.lib.php
 *	\brief      Shell renderer for the COMMAND theme.
 *
 *	Emits a slim command bar and serialises the full menu tree as JSON for the
 *	Ctrl/Cmd+K palette. There is no sidebar and no top-menu strip.
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
function command_menu_entry_allowed($entry)
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
 *  @param  string                          $forceMain Force active module while building submenus
 *  @param  string                          $forceLeft Force active submenu while building submenus
 *  @return array<int,array<string,mixed>>             Groups with 'items'
 */
function command_build_tree($db, &$tabMenu, $atarget, $type_user, $forceMain = '', $forceLeft = '')
{
	require_once DOL_DOCUMENT_ROOT.'/core/menus/standard/eldy.lib.php';
	require_once DOL_DOCUMENT_ROOT.'/core/class/menu.class.php';
	// Foldable-tree helpers are shared with the other Thrive shells.
	require_once DOL_DOCUMENT_ROOT.'/core/menus/standard/thriveshell.lib.php';

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
	if ($forceMain !== '') {
		$curMain = $forceMain;
		$curLeft = $forceLeft;
	}

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
			'url'   => command_abs_url((string) $top['url']),
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
			$absUrl = command_abs_url((string) $item['url']);
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
 * Infer the active module from the current route when Dolibarr omitted
 * mainmenu from a deep link. Session state is not reliable here: opening a
 * Third Party URL after Setup otherwise leaves Home expanded indefinitely.
 *
 * @param  array<int,array<string,mixed>> $tree Navigation groups
 * @return string                               Best matching mainmenu key
 */
function command_infer_current_main($tree)
{
	$selfDir = rtrim(dirname(command_normalize_path((string) $_SERVER['PHP_SELF'])), '/');
	$bestKey = '';
	$bestScore = 0;
	foreach ($tree as $group) {
		$score = 0;
		$urls = array($group['url']);
		foreach ($group['items'] as $item) {
			$urls[] = $item['url'];
		}
		foreach ($urls as $url) {
			$exact = command_match_score($url);
			if ($exact >= 0) {
				$score = max($score, 100 + $exact);
				continue;
			}
			$parts = parse_url($url);
			$urlPath = !empty($parts['path']) ? command_normalize_path($parts['path']) : '';
			if ($urlPath !== '' && rtrim(dirname($urlPath), '/') === $selfDir) {
				$score = max($score, 10);
			}
		}
		if ($score > $bestScore) {
			$bestScore = $score;
			$bestKey = $group['key'];
		}
	}
	return $bestKey;
}


/**
 * Normalize a request/menu path against Dolibarr's configured URL root.
 * Both aliased web-root requests and canonical /dolibarr requests must compare
 * identically.
 *
 * @param string $path Request or menu path
 * @return string
 */
function command_normalize_path($path)
{
	$urlRoot = rtrim((string) DOL_URL_ROOT, '/');
	if ($urlRoot !== '' && ($path === $urlRoot || strpos($path, $urlRoot.'/') === 0)) {
		$path = substr($path, strlen($urlRoot));
	}
	return $path === '' ? '/' : $path;
}


/**
 *  Make a menu URL absolute against DOL_URL_ROOT.
 *
 *  @param  string $url Raw menu url
 *  @return string
 */
function command_abs_url($url)
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
function command_match_score($url)
{
	if ($url === '') {
		return -1;
	}
	$parts = parse_url($url);
	if (empty($parts['path'])) {
		return -1;
	}
	$path = command_normalize_path($parts['path']);
	$self = command_normalize_path((string) $_SERVER['PHP_SELF']);
	if (basename($path) !== basename($self)) {
		return -1;
	}
	// Compare directories too, so product/list.php never matches societe/list.php.
	if (rtrim(dirname($path), '/') !== rtrim(dirname($self), '/')) {
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
 *  Print the COMMAND shell: the command bar plus the palette scaffold.
 *
 *  @param  DoliDB                          $db        Database handler
 *  @param  array<int,array<string,mixed>>  $tabMenu   Menu entries
 *  @param  string                          $atarget   Link target
 *  @param  int<0,1>                        $type_user 0=internal, 1=external
 *  @return void
 */
function print_command_shell($db, &$tabMenu, $atarget, $type_user)
{
	global $conf, $langs, $user;

	$langs->loadLangs(array('main', 'other'));

	$tree = command_build_tree($db, $tabMenu, $atarget, $type_user);

	// The route/menu tree is authoritative. mainmenu is frequently missing on
	// deep links and can also be stale (for example a Setup session followed by
	// a direct Third Party URL). Use the requested/session value only when no
	// menu-tree destination matches the current route at all.
	$requestedMain = GETPOSTISSET('mainmenu') ? GETPOST('mainmenu', 'aZ09') : '';
	$inferredMain = command_infer_current_main($tree);
	$currentMain = $inferredMain !== '' ? $inferredMain : $requestedMain;
	if ($currentMain === '') {
		$currentMain = isset($_SESSION['mainmenu']) ? $_SESSION['mainmenu'] : '';
	}
	$treeMain = $requestedMain !== '' ? $requestedMain : (isset($_SESSION['mainmenu']) ? $_SESSION['mainmenu'] : '');
	if ($currentMain !== '' && $currentMain !== $treeMain) {
		// Rebuild so the resolved module receives its own complete submenu rather
		// than the stale module's left-menu branch.
		$tree = command_build_tree($db, $tabMenu, $atarget, $type_user, $currentMain, '');
	}
	$currentTitle = '';
	foreach ($tree as $g) {
		if ($g['key'] === $currentMain) {
			$currentTitle = $g['title'];
			break;
		}
	}

	$brand = getDolGlobalString('MAIN_INFO_SOCIETE_NOM');
	if ($brand === '') {
		$brand = 'Dolibarr';
	}
	$home = DOL_URL_ROOT.'/index.php?mainmenu=home';

	/* The company logo set in Home - Setup - Company/Organization. Dolibarr keeps
	   several renderings of the same upload and generates the thumbs at upload
	   time, so the smallest that is actually on disk is the one to ask for -- a
	   constant can name a file a later upload replaced. The squarred variants come
	   first because this is a 32px tile.

	   MAIN_SHOW_LOGO is deliberately not consulted. It governs whether eldy adds a
	   logo entry of its own to the menu bar; here the brand mark is always drawn,
	   and the only question is whether it shows the logo or the initial. Reading
	   that setting would hide the logo on every install that never set it. */
	$brandlogo = '';
	$logodir = $conf->mycompany->dir_output.'/logos/';
	foreach (array(
		'MAIN_INFO_SOCIETE_LOGO_SQUARRED_MINI' => 'thumbs/',
		'MAIN_INFO_SOCIETE_LOGO_MINI' => 'thumbs/',
		'MAIN_INFO_SOCIETE_LOGO_SQUARRED_SMALL' => 'thumbs/',
		'MAIN_INFO_SOCIETE_LOGO_SMALL' => 'thumbs/',
		'MAIN_INFO_SOCIETE_LOGO_SQUARRED' => '',
		'MAIN_INFO_SOCIETE_LOGO' => '',
	) as $constname => $subdir) {
		$file = getDolGlobalString($constname);
		if ($file === '' || !is_readable($logodir.$subdir.$file)) {
			continue;
		}
		$brandlogo = DOL_URL_ROOT.'/viewimage.php?cache=1&modulepart=mycompany&file='.urlencode('logos/'.$subdir.$file);
		break;
	}

	// Apply the stored collapse state before first paint so the rail does not
	// visibly jump from expanded to collapsed on every page load.
	//
	// The same script holds the content back until the theme has composed it.
	// Dolibarr's own markup is what the browser paints first -- the stylesheets
	// are all in the head and arrive early, but modern.js is deferred, so the
	// native tables and columns are on screen for a few hundred milliseconds
	// before they are rearranged into cards and sections. Hiding the content
	// area until that pass has run replaces a visible rebuild with a short wait,
	// and the chrome around it -- the bar and the nav, both built here on the
	// server -- stays on screen throughout, so the page never looks empty.
	//
	// The gate is only ever raised by script, so a browser without JavaScript
	// sees the native page as before. modern.js takes it down once the page has
	// settled; failing that this script takes it down a moment after load, and
	// failing even that on a long stop, so no failure in the theme's own
	// JavaScript can leave a page hidden.
	//
	// The backstop follows load rather than a fixed delay because a guessed delay
	// races the thing it is insuring: a heavy dashboard settles at about 1.4s, so
	// a 1.5s timer sometimes fired first and revealed the page mid-settle -- the
	// jump this is meant to prevent, on the page most in need of it.
	//
	// Deliberately not on a window error event. A resource that 404s reports its
	// failure there too, so a single missing image on a page -- Dolibarr's module
	// list has one -- would take the gate down before anything was composed,
	// which is to say on exactly the pages that need it most.
	//
	// Nor on DOMContentLoaded, which is earlier than the page is finished:
	// Dolibarr builds its Select2 controls from jQuery ready handlers, and those
	// replace a plain select with a wider one, moving whatever shares its row.
	// Revealing at that point traded a rebuild of the page for a rebuild of every
	// filter bar on it.
	print '<script nonce="'.getNonce().'">(function(){var d=document,r=d.documentElement;'
		.'try{if(localStorage.getItem("cmdNavCollapsed")==="1"){d.body.classList.add("cmd-nav-collapsed");}}catch(e){}'
		.'function show(){r.classList.remove("ts-shell-pending");}'
		.'r.classList.add("ts-shell-pending");'
		.'window.addEventListener("load",function(){window.setTimeout(show,250);});'
		.'window.setTimeout(show,4000);'
		.'})();</script>'."\n";

	print '<header id="cmd-bar" class="cmd-bar">'."\n";

	// Opens the navigation on a narrow screen. The control at the foot of the
	// nav sits on the very bottom edge, which on a phone is where the browser
	// keeps its own bar -- reachable in theory, under something else in practice.
	print '<button type="button" class="cmd-nav-open" id="cmd-nav-open" aria-label="'.dol_escape_htmltag($langs->trans('Menu')).'" aria-expanded="false">';
	print '<span class="cmd-nav-open-bars" aria-hidden="true"></span>';
	print '</button>';

	// Brand
	print '<a class="cmd-brand" href="'.dol_escape_htmltag($home).'">';
	if ($brandlogo !== '') {
		print '<span class="cmd-brand-mark cmd-brand-mark-logo" aria-hidden="true">';
		print '<img src="'.dol_escape_htmltag($brandlogo).'" alt="">';
		print '</span>';
	} else {
		print '<span class="cmd-brand-mark" aria-hidden="true">'.dol_escape_htmltag(dol_substr($brand, 0, 1)).'</span>';
	}
	print '<span class="cmd-brand-text">'.dol_escape_htmltag($brand).'</span>';
	print '</a>';

	// Where you are. The brand already links home and the nav always shows the
	// Home module, so a "Home >" crumb in front of every page is pure noise.
	// Suppress it on the Home module: the brand beside it already says that.
	if ($currentTitle !== '' && $currentMain !== 'home') {
		print '<nav class="cmd-crumbs" aria-label="'.dol_escape_htmltag($langs->trans('Menu')).'">';
		print '<span class="cmd-crumb cmd-crumb-current">'.dol_escape_htmltag($currentTitle).'</span>';
		print '</nav>';
	}

	print '<div class="cmd-bar-spacer"></div>';

	// Search sits on the right, next to the account controls it belongs with.
	print '<button type="button" class="cmd-trigger" id="cmd-trigger" aria-haspopup="dialog">';
	print '<span class="cmd-trigger-icon fas fa-search" aria-hidden="true"></span>';
	print '<span class="cmd-trigger-label">'.dol_escape_htmltag($langs->trans('Search')).'</span>';
	print '<kbd class="cmd-kbd">Ctrl</kbd><kbd class="cmd-kbd">K</kbd>';
	print '</button>';

	// Dolibarr injects its own tools/login block into .login_block elsewhere in
	// the page; the bar just reserves space for it on the right.
	print '<div class="cmd-bar-tools" id="cmd-bar-tools"></div>';

	print '</header>'."\n";

	// Persistent navigation. The palette is an accelerator, not a replacement:
	// without a visible tree there is no discoverability and no sense of place,
	// which matters more in an ERP than the width the sidebar costs.
	print '<aside class="cmd-nav" id="cmd-nav" aria-label="'.dol_escape_htmltag($langs->trans('Menu')).'">';
	print '<div class="cmd-nav-scroll">';
	print '<ul class="cmd-nav-list">';

	foreach ($tree as $g) {
		$isActive = ($g['key'] === $currentMain);
		$hasKids = !empty($g['items']);

		print '<li class="cmd-nav-group'.($isActive ? ' is-active' : '').($hasKids ? ' has-kids' : '').'">';
		print '<a class="cmd-nav-link" href="'.dol_escape_htmltag($g['url'] !== '' ? $g['url'] : '#').'"';
		print ' title="'.dol_escape_htmltag($g['title']).'">';
		print '<span class="cmd-nav-icon" aria-hidden="true">'.$g['icon'].'</span>';
		print '<span class="cmd-nav-label">'.dol_escape_htmltag($g['title']).'</span>';
		print '</a>';

		// Only the active module expands, and its entries nest so long branches
		// (Setup alone is 20+ pages) fold instead of dumping in one list.
		if ($hasKids && $isActive) {
			print '<div class="cmd-nav-sub">';
			thriveshell_print_subitems($g['items'], 'cmd-nav-sublink');
			print '</div>';
		}
		print '</li>';
	}

	print '</ul>';
	print '</div>';
	print '<button type="button" class="cmd-nav-toggle" id="cmd-nav-toggle" aria-label="'.dol_escape_htmltag($langs->trans('Menu')).'">';
	print '<span class="cmd-nav-toggle-icon" aria-hidden="true"></span>';
	print '<span class="cmd-nav-toggle-label">'.dol_escape_htmltag($langs->trans('Collapse')).'</span>';
	print '</button>';
	print '</aside>'."\n";

	// Palette markup. Kept in the DOM (hidden) so it needs no runtime fetch.
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
	print '</div>';
	print '</div>'."\n";

	// Navigation data for the palette.
	$payload = array();
	$seenPayload = array();
	foreach ($tree as $g) {
		if ($g['url'] !== '') {
			$payload[] = array('g' => $g['title'], 't' => $g['title'], 'u' => $g['url'], 'k' => $g['key']);
		}
		foreach ($g['items'] as $it) {
			// The same action is often reachable from several modules; one row
			// per destination keeps the result list honest.
			$key = $it['title'].'|'.$it['url'];
			if (isset($seenPayload[$key])) {
				continue;
			}
			$seenPayload[$key] = true;
			$payload[] = array('g' => $g['title'], 't' => $it['title'], 'u' => $it['url'], 'k' => $g['key']);
		}
	}
	print '<script type="application/json" id="cmd-nav-data">';
	print json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
	print '</script>'."\n";

	// Core versions theme JavaScript with DOL_VERSION only. A theme deployment
	// therefore leaves command.js at the same URL and browsers can retain the
	// pre-modern loader indefinitely. Load the structural layer directly from
	// server-rendered markup with a content-derived version so every deployed
	// modern.js revision has a distinct URL. The structural script is idempotent,
	// so the command.js compatibility loader may safely resolve the same file.
	$modernFile = DOL_DOCUMENT_ROOT.'/core/thriveshell/modern.js';
	$modernVersion = is_readable($modernFile) ? substr(sha1_file($modernFile), 0, 12) : DOL_VERSION;
	print '<script src="'.DOL_URL_ROOT.'/core/thriveshell/modern.js?v='.rawurlencode($modernVersion).'" defer></script>'."\n";
}
