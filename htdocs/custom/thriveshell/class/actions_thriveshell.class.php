<?php
/* Copyright (C) 2026 Thrive
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

require_once DOL_DOCUMENT_ROOT.'/core/class/commonhookactions.class.php';

/** Hook actions for stable Thrive shell presentation metadata. */
class ActionsThriveshell extends CommonHookActions
{
	/** @var DoliDB */
	public $db;

	/** @param DoliDB $db Database handler */
	public function __construct($db)
	{
		$this->db = $db;
	}

	/**
	 * Publish the Third Party view's translated field schema.
	 *
	 * Core prints the rows after this hook returns. This method emits metadata
	 * only, leaving native markup, permissions, edit links and values untouched.
	 *
	 * @param array       $parameters Hook parameters
	 * @param Societe     $object     Current third party
	 * @param string      $action     Current action
	 * @param HookManager $hookmanager Hook manager
	 * @return int 0 to retain Dolibarr's native content
	 */
	public function tabContentViewThirdparty($parameters, &$object, &$action, $hookmanager)
	{
		global $langs;

		if (empty($object) || empty($object->id)) {
			return 0;
		}

		$langs->loadLangs(array('companies', 'members', 'thriveshell@thriveshell'));
		$groups = array(
			'identity' => array(
				'title' => $langs->transnoentitiesnoconv('ThriveshellGroupIdentity'),
				'icon' => 'fa-building',
				'fields' => array(
					'nature' => array($langs->trans('NatureOfThirdParty')),
					'customer-categories' => array($langs->trans('CustomersCategoriesShort')),
					'supplier-categories' => array($langs->trans('SuppliersCategoriesShort')),
					'thirdparty-type' => array($langs->trans('ThirdPartyType')),
					'workforce' => array($langs->trans('Workforce')),
					'legal-form' => array($langs->trans('JuridicalStatus')),
					'capital' => array($langs->trans('Capital')),
					'incoterms' => array($langs->trans('IncotermLabel')),
					'currency' => array($langs->trans('Currency')),
					'parent-company' => array($langs->trans('ParentCompany')),
				),
			),
			'business' => array(
				'title' => $langs->transnoentitiesnoconv('ThriveshellGroupBusiness'),
				'icon' => 'fa-shield-alt',
				'fields' => array(
					'customer-code' => array($langs->trans('CustomerCode')),
					'supplier-code' => array($langs->trans('SupplierCode')),
					'barcode' => array($langs->trans('Gencod')),
					'vat-id' => array($langs->trans('VATIntra')),
				),
			),
			'relationships' => array(
				'title' => $langs->transnoentitiesnoconv('ThriveshellGroupRelationships'),
				'icon' => 'fa-briefcase',
				'fields' => array(
					'sales-representatives' => array($langs->trans('SalesRepresentatives')),
					'member-link' => array($langs->trans('LinkedToDolibarrMember')),
				),
			),
		);

		$maxProfId = getDolGlobalInt('THIRDPARTY_MAX_NB_PROF_ID', 6);
		for ($i = 1; $i <= $maxProfId; $i++) {
			$label = $langs->transcountry('ProfId'.$i, $object->country_code);
			if ($label !== '-') {
				$groups['business']['fields']['professional-id-'.$i] = array($label);
			}
		}

		print '<script type="application/json" id="ts-thirdparty-field-schema">';
		print json_encode(array('groups' => $groups), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
		print '</script>';

		return 0;
	}
}
