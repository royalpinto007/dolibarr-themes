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
 *	\file       core/menus/standard/thriveshell_menu.php
 *	\brief      Menu manager for the Thrive theme family.
 *
 *	One manager serves every Thrive shell, dispatching on the active theme.
 *	Anything it does not own is handed to the next theme-aware manager in the
 *	chain, which ends at Dolibarr's native Eldy manager -- so selecting this as
 *	the site menu manager leaves every other theme's own shell intact.
 */

/**
 * Themes handled here, mapped to their shell renderer.
 *
 * Defined as a constant rather than a variable: Dolibarr includes menu
 * managers from inside a function, so a file-scope variable is not global and
 * would be invisible to the class below.
 */
if (!defined('THRIVE_SHELLS')) {
	define('THRIVE_SHELLS', 'workbench,aurora,editorial,dense');
}

$activeTheme = (isset($conf) && is_object($conf) && !empty($conf->theme))
	? (string) $conf->theme
	: getDolGlobalString('MAIN_THEME', 'eldy');

if (!in_array($activeTheme, explode(',', THRIVE_SHELLS), true)) {
	if (file_exists(DOL_DOCUMENT_ROOT.'/core/menus/standard/command_menu.php')) {
		require_once DOL_DOCUMENT_ROOT.'/core/menus/standard/command_menu.php';
	} elseif (file_exists(DOL_DOCUMENT_ROOT.'/core/menus/standard/thrivetheme_menu.php')) {
		require_once DOL_DOCUMENT_ROOT.'/core/menus/standard/thrivetheme_menu.php';
	} else {
		require_once DOL_DOCUMENT_ROOT.'/core/menus/standard/eldy_menu.php';
	}
} else {
	/**
	 *	MenuManager emitting one of the Thrive shells.
	 *
	 *	@phan-suppress PhanRedefineClass
	 */
	class MenuManager
	{
		/** @var DoliDB */
		public $db;

		/** @var int<0,1> */
		public $type_user = 0;

		/** @var string */
		public $atarget = '';

		/** @var string */
		public $name = 'thriveshell';

		/** @var Menu|null */
		public $menu;

		/** @var array<int,array<string,mixed>> */
		public $menu_array = array();

		/** @var array<int,array<string,mixed>> */
		public $menu_array_after = array();

		/** @var array<int,array<string,mixed>> */
		public $tabMenu = array();

		/** @var string Shell to render */
		public $shell = '';


		/**
		 *  Constructor
		 *
		 *  @param  DoliDB    $db        Database handler
		 *  @param  int<0,1>  $type_user 0=internal user, 1=external
		 */
		public function __construct($db, $type_user)
		{
			global $conf;

			$this->db = $db;
			$this->type_user = $type_user;

			$theme = !empty($conf->theme) ? (string) $conf->theme : getDolGlobalString('MAIN_THEME', 'eldy');
			$this->shell = in_array($theme, explode(',', THRIVE_SHELLS), true) ? $theme : '';
		}


		/**
		 *  Load $this->tabMenu. Session handling matches eldy_menu.php so code
		 *  reading mainmenu/leftmenu keeps working.
		 *
		 *  @param  string $forcemainmenu Force a mainmenu code
		 *  @param  string $forceleftmenu Force a leftmenu code
		 *  @return void
		 */
		public function loadMenu($forcemainmenu = '', $forceleftmenu = '')
		{
			if (GETPOSTISSET('mainmenu')) {
				$_SESSION['mainmenu'] = GETPOST('mainmenu', 'aZ09');
				$_SESSION['leftmenuopened'] = '';
			}
			if (GETPOSTISSET('idmenu')) {
				$_SESSION['idmenu'] = GETPOSTINT('idmenu');
			}
			$mainmenu = GETPOSTISSET('mainmenu') ? GETPOST('mainmenu', 'aZ09') : (isset($_SESSION['mainmenu']) ? $_SESSION['mainmenu'] : '');
			if (!empty($forcemainmenu)) {
				$mainmenu = $forcemainmenu;
			}

			if (GETPOSTISSET('leftmenu')) {
				$leftmenu = GETPOST('leftmenu', 'aZ09');
				$_SESSION['leftmenu'] = $leftmenu;
				if (isset($_SESSION['leftmenuopened']) && $_SESSION['leftmenuopened'] == $leftmenu) {
					$_SESSION['leftmenuopened'] = '';
				} else {
					$_SESSION['leftmenuopened'] = $leftmenu;
				}
			} else {
				$leftmenu = isset($_SESSION['leftmenu']) ? $_SESSION['leftmenu'] : '';
			}
			if (!empty($forceleftmenu)) {
				$leftmenu = $forceleftmenu;
			}

			require_once DOL_DOCUMENT_ROOT.'/core/class/menubase.class.php';
			$tabMenu = array();
			$menuArbo = new Menubase($this->db, 'eldy');
			$menuArbo->menuLoad($mainmenu, $leftmenu, $this->type_user, 'eldy', $tabMenu);
			$this->tabMenu = $tabMenu;
		}


		/**
		 *  Output menu HTML.
		 *
		 *  @param  'top'|'topnb'|'left'|'leftdropdown'|'jmobile' $mode      Render mode
		 *  @param  ?array<string,string>                          $moredata  Extra data
		 *  @return int<0,max>                                                Top entry count for 'topnb'
		 */
		public function showmenu($mode, $moredata = null)
		{
			global $conf, $langs, $user;

			require_once DOL_DOCUMENT_ROOT.'/core/class/menu.class.php';
			require_once dirname(__FILE__).'/thriveshell.lib.php';

			$this->menu = new Menu();

			if ($mode === 'top') {
				thriveshell_render($this->shell, $this->db, $this->tabMenu, $this->atarget, $this->type_user);
				return 0;
			}

			// Every Thrive shell renders its own navigation in the 'top' pass,
			// so Dolibarr's left column stays empty.
			if ($mode === 'left' || $mode === 'leftdropdown') {
				return 0;
			}

			require_once DOL_DOCUMENT_ROOT.'/core/menus/standard/eldy.lib.php';

			if ($mode === 'topnb') {
				print_eldy_menu($this->db, $this->atarget, $this->type_user, $this->tabMenu, $this->menu, 1, $mode);
				return $this->menu->getNbOfVisibleMenuEntries();
			}

			if ($mode === 'jmobile') {
				print_eldy_menu($this->db, $this->atarget, $this->type_user, $this->tabMenu, $this->menu, 1, $mode);
				foreach ($this->menu->liste as $val) {
					if (empty($val['mainmenu'])) {
						continue;
					}
					$url = $val['url'];
					if ($url && !preg_match('/^https?:\/\//', $url)) {
						$url = DOL_URL_ROOT.$url;
					}
					print '<ul class="ulmenu" data-inset="true"><li class="lilevel0">';
					print '<a class="alilevel0" href="'.dol_escape_htmltag($url).'">'.$val['titre'].'</a>';
					print '</li></ul>';
				}
				return 0;
			}

			return 0;
		}
	}
}
