<?php
/*
 * Register necessary class names with autoloader
 *
 * $Id: ext_autoload.php 6536 2009-11-25 14:07:18Z stucki $
 */

return array(
	'tx_cimaintainance_scheduler_formhandlerchangestask' => t3lib_extMgm::extPath('ci_maintainance', 'Classes/Scheduler/FormhandlerChanges.php'),
	'tx_cimaintainance_scheduler_contentupdatestask' => t3lib_extMgm::extPath('ci_maintainance', 'Classes/Scheduler/ContentUpdates.php'),
);
?>
