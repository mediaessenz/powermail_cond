<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Alex Kellner <alexander.kellner@in2code.de>, in2code.de
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Remove fields from session which should be mandatory again
 *
 * @author	Alex Kellner <alexander.kellner@in2code.de>, in2code.
 * @package	TYPO3
 * @subpackage	Tx_PowermailCond_Utility_EidRequiredField
 */
class Tx_PowermailCond_Utility_EidRequiredField {

	/**
	 * Prefix Id
	 *
	 * @var string
	 */
	public $prefixId = 'tx_powermailcond_pi1';

	/**
	 * @var Tx_PowermailCond_Utility_Div
	 */
	protected $div;

	/**
	 * save field in session to be stored for non-mandatory fields
	 *
	 * @return int Field Uid which was disabled
	 */
	public function main() {
		$piVars = t3lib_div::_GP($this->prefixId);
		$formUid = intval($piVars['formUid']);
		$fieldUid = intval($piVars['fieldUid']);

		// start
		if ($formUid === 0 || $fieldUid === 0) {
			return 0;
		}

		// removee single value from session
		$this->div->removeValueFromSession($formUid, $fieldUid, 'deRequiredFields');
		return $fieldUid;
	}

	/**
	 * Initialize eID
	 */
	public function __construct($TYPO3_CONF_VARS) {
		$userObj = tslib_eidtools::initFeUser();
		$GLOBALS['TSFE'] = t3lib_div::makeInstance('tslib_fe', $TYPO3_CONF_VARS, 32, 0, TRUE);
		$GLOBALS['TSFE']->connectToDB();
		$GLOBALS['TSFE']->fe_user = $userObj;
		$GLOBALS['TSFE']->id = t3lib_div::_GET('id');
		$GLOBALS['TSFE']->determineId();
		$GLOBALS['TSFE']->getCompressedTCarray();
		$GLOBALS['TSFE']->initTemplate();
		$GLOBALS['TSFE']->getConfigArray();
		$GLOBALS['TSFE']->includeTCA();

		$this->div = t3lib_div::makeInstance('Tx_PowermailCond_Utility_Div');
	}
}

$eid = t3lib_div::makeInstance('Tx_PowermailCond_Utility_EidRequiredField', $GLOBALS['TYPO3_CONF_VARS']);
echo $eid->main();