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

class Tx_CiMaintainance_Cli_ArchiveSyslog extends t3lib_cli {
	
	protected $typo_db_username;
	protected $typo_db_password;
	protected $typo_db_host;
	protected $typo_db;
   
	function __construct() {
		$this->typo_db_username = 'root';     //  Modified or inserted by TYPO3 Install Tool.
		$this->typo_db_password = '0uD6WBRQ'; //  Modified or inserted by TYPO3 Install Tool.
		$this->typo_db_host = 'localhost';    //  Modified or inserted by TYPO3 Install Tool.
		$this->typo_db = 'hpitypo3';  //  Modified or inserted by TYPO3 Install Tool.
		
	}
	
	public function execute($filename) {
		$no_error = true;
		// check if writepermissions exist
		$tstamp = $this->getNextArchiveTstamp();
		$no_error = $this->archiveSyslog($tstamp, $filename);
		if($no_error) {
			$no_error = $this->deleteArchivedLogs($tstamp);
		}
		
		if($no_error) {
			$this->cli_echo(LF.'Syslog archived until: '.strftime('%d.%m.%Y %H:%M:%S', $tstamp).LF);
		} else {
			$this->cli_echo('Execution aborded due to previous errors!'.LF);
		}
	}
	
	protected function getNextArchiveTstamp() {
		// 20XX-01-01 00:00:00 of last year
		return mktime( 0, 0, 0, 1, 1, date("Y")-1 );
	}

	
	protected function archiveSyslog( $tstamp = NULL, $filename ) {
		if(intval($tstamp) === 0 ) {
			$this->cli_echo('Timestamp not correct'.LF);
			return false;
		}
		
		if( t3lib_div::validPathStr($filename) ) {
			if(!t3lib_div::isAbsPath($filename)) {
				$filename = t3lib_div::getFileAbsFileName($filename);
			}
		} else {
			$this->cli_echo('The specified filename "'.$filename.'" is not valid!'.LF);
			return false;
		}
		
		$path = dirname($filename);
		if(!is_writable($path)) {
			$this->cli_echo('File "'.$filename.'" cannot be written! Check permissions!'.LF);
			return false;
		}
		
		if(file_exists($filename) ) {
			$go = $this->cli_keyboardInput_yes('File already exists! Overwrite?');
    		if(!$go) {
    			$this->cli_echo("Archiving aborded by user!".LF);
    			return false;
    		}
		}
		
		$where = 'tstamp < '.$tstamp;
		$cmd = '/usr/bin/mysqldump --opt -u '.$this->typo_db_username.' --password="'.$this->typo_db_password.'"';
		$cmd .= ' --where="'.$where.'" '.$this->typo_db.' sys_log | gzip > '.$filename;	
		$r = system($cmd); //$lastLine = t3lib_utility_Command::exec($cmd);
		if($r !== FALSE) {
			$this->cli_echo('sys_log-dump successfully completed!'.LF);
			return true;
		} else {
			$this->cli_echo('An error occured while dumping the syslog!'.LF);
			$this->cli_echo('The following command has been executed:'.LF.$cmd.LF);
			return false;
		}
	}
	
	protected function deleteArchivedLogs( $tstamp = NULL ) {
		if(intval($tstamp) === 0 ) {
			$this->cli_echo('Timestamp not correct');
			return false;
		}
		
		$go = $this->cli_keyboardInput_yes('Delete sys_log entries?');
		
		if($go) {
			$sql = 'DELETE FROM sys_log WHERE tstamp < '.$tstamp;
			
			$res = $GLOBALS['TYPO3_DB']->admin_query($sql);
			$rows = $GLOBALS['TYPO3_DB']->sql_affected_rows();
			$GLOBALS['TYPO3_DB']->sql_free_result($res);
			
			$this->cli_echo($rows.' rows have been deleted!'.LF);
		}
		return true;
	}

}
    
?>