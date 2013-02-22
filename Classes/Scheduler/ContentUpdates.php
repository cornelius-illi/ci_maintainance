<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Cornelius Illi <cornelius.illi@student.hpi.uni-potsdam.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
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
 * Class "tx_CiMaintainance_Scheduler_ContentUpdatesTask" provides a task that reports changes in formhandler-records
 *
 * @author		Cornelius Illi <cornelius.illi@student.hpi.uni-potsdam.de>
 * @package		TYPO3
 * @subpackage	tx_scheduler
 *
 * $Id: ContentUpdates.php $
 */

$_EXTKEY = 'ci_maintainance';

require_once(PATH_typo3.'sysext/scheduler/interfaces/interface.tx_scheduler_additionalfieldprovider.php');
class tx_CiMaintainance_Scheduler_ContentUpdatesTask
	extends tx_scheduler_Task implements tx_scheduler_AdditionalFieldProvider {
	
	public function execute() {
                return;
        }

        public function getAdditionalFields (array &$taskInfo, $task, tx_scheduler_Module $schedulerModule) {
                return;
        }

        public function validateAdditionalFields (array &$submittedData, tx_scheduler_Module $schedulerModule) {
                return;
        }

        public function saveAdditionalFields (array $submittedData, tx_scheduler_Task $task) {
                return;
        }	
}

?>
