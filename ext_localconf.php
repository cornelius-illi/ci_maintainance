<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

if (TYPO3_MODE=='BE')    {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['GLOBAL']['cliKeys'][$_EXTKEY] = array('EXT:'.$_EXTKEY.'/Classes/Cli/Factory.php','_CLI_lowlevel');
	
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_CiMaintainance_Scheduler_FormhandlerChangesTask'] = array (
		'extension' => $_EXTKEY,
		'title' => 'Report form changes (formhandler)',
		'description' => 'Checks for new and updates forms and sends mail to specified address',
		'additionalFields' => 'tx_CiMaintainance_Scheduler_FormhandlerChangesTask'
	);
	
	 $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_CiMaintainance_Scheduler_ContentUpdatesTask'] = array (
		'extension' => $_EXTKEY,
		'title' => 'Report missing content-updates',
		'description' => 'Checks updated tt_content records for translations and reports, if their update has been forgotten.',
		'additionalFields' => 'tx_CiMaintainance_Scheduler_ContentUpdatesTask'
	);
}
?>