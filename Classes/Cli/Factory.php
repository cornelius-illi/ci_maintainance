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
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 * The shell call is
 * /www/typo3/php cli_dispatch.phpsh EXTKEY TASK
 * 
 * @author	Cornelius Illi <Cornelius.Illi@student.hpi.uni-potsdam.de>
 * @package TYPO3
 */

if (!defined('TYPO3_cliMode')) {
	die('Access denied: CLI only.');
}

$_EXTKEY = 'ci_maintainance';

require_once(t3lib_extMgm::extPath($_EXTKEY) .'Classes/Cli/ArchiveSyslog.php');

class Tx_CiMaintainance_Cli_Factory extends t3lib_cli {
	
	protected $archiveSyslog;
	
	function __construct() {
		// Running parent class constructor
        parent::t3lib_cli();
		$this->cli_options = array_merge($this->cli_options, array());
		$this->cli_help = array_merge(
			$this->cli_help,
			array(
				'name' => 'CLI-Factory',
				'synopsis' => $this->extKey . ' command [archiveSyslog/ check_deprecated] ###OPTIONS###',
				'description' => 'Factory for maintainance scripts.',
				'examples' => 'typo3/cli_dispatch.phpsh',
				'author' => '(c) 2011 - Cornelius Illi'
			)
		);
		$this->archiveSyslog = t3lib_div::makeInstance('Tx_CiMaintainance_Cli_ArchiveSyslog');
		$this->checkDeprecated = t3lib_div::makeInstance('Tx_CiMaintainance_Cli_CheckDeprecated');
     }
	
	/**
     * CLI engine
     *
     * @param array Command line arguments
     * @return string
     */
    function cli_main($argv) {
        $task = strtolower( (string)$this->cli_args['_DEFAULT'][1] );
        $filename = (string)$this->cli_args['_DEFAULT'][2];
      	
      	switch ($task) {
            case 'archive_syslog':
    			$this->archiveSyslog->execute($filename); 
            break;
			
			case 'check_deprecated'
				$this->checkDeprecated->execute($filename);
            
    		default:
    			$this->cli_validateArgs();
            	$this->cli_help();
            exit;
        }
    }
}
$factory = t3lib_div::makeInstance('Tx_CiMaintainance_Cli_Factory');
/* @var $factory Tx_CiMaintainance_Cli_Factory */
$factory->cli_main($_SERVER['argv']);