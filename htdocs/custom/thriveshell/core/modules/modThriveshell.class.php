<?php
/* Copyright (C) 2026 Thrive
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

include_once DOL_DOCUMENT_ROOT.'/core/modules/DolibarrModules.class.php';

/** Minimal hook companion for the Thrive shell themes. */
class modThriveshell extends DolibarrModules
{
	/** @param DoliDB $db Database handler */
	public function __construct($db)
	{
		global $conf;

		$this->db = $db;
		$this->numero = 500201;
		$this->rights_class = 'thriveshell';
		$this->family = 'interface';
		$this->module_position = '91';
		$this->name = preg_replace('/^mod/i', '', get_class($this));
		$this->description = 'ThriveshellDescription';
		$this->version = '1.0.0';
		$this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
		$this->picto = 'palette';
		$this->module_parts = array(
			'hooks' => array(
				'data' => array('thirdpartycard'),
				'entity' => '0',
			),
		);
		$this->dirs = array();
		$this->config_page_url = array();
		$this->hidden = false;
		$this->depends = array();
		$this->requiredby = array();
		$this->conflictwith = array();
		$this->langfiles = array('thriveshell@thriveshell');
		$this->phpmin = array(7, 4);
		$this->need_dolibarr_version = array(19, 0);
		$this->need_javascript_ajax = 0;
		$this->rights = array();
		$this->menu = array();
		$this->tabs = array();

		if (!isModEnabled('thriveshell')) {
			$conf->thriveshell = new stdClass();
			$conf->thriveshell->enabled = 0;
		}
	}

	/** @param string $options Activation options */
	public function init($options = '')
	{
		return $this->_init(array(), $options);
	}

	/** @param string $options Deactivation options */
	public function remove($options = '')
	{
		return $this->_remove(array(), $options);
	}
}
