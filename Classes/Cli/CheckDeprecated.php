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

class Tx_CiMaintainance_Cli_CheckDeprecated extends t3lib_cli {
	
	protected $deprecatedLog;
	protected $deprecatedFunctions;
	
	function __construct() {
		$this->deprecatedLog = array();
		$this->deprecatedFunctions = array();
	}
	
	public function execute($filename) {
		$this->getDeprecatedFunctions($filename)
		$this->getExtensions();
		$this->checkForDeprecatedFunctions();
		$this->writeResultsToFile();
	}
	
	private function getDeprecatedFunctions($filename) {
		if( t3lib_div::validPathStr($filename) ) {
			if(!t3lib_div::isAbsPath($filename)) {
				$filename = t3lib_div::getFileAbsFileName($filename);
			}
		} else {
			$this->cli_echo('The specified filename "'.$filename.'" is not valid!'.LF);
			exit(0);
		}
		
		$fileAr = file($filename);
		foreach($fileAr as $line) {
			$lineAr = explode(";", $line);
			if( count($lineAr) > 2) {
				$this->deprecatedFunctions[ $lineAr[0] ] = $lineAr[1];
			} else {
				$this->cli_echo('Line could not be parsed with value "'.$line.'"'.LF);
				exit(0);
			}		
		}
		return true;
	}
	
	private function getExtensions() {
		$fileArr = array();
		$extDirs = t3lib_div::get_dirs(PATH_typo3conf.'ext');
		foreach($extDirs as $dir) {
			$this->deprecatedLog[$dir] = array();
		}	
	}
	
	private function checkForDeprecatedFunctions() {
		foreach($this->deprecatedLog as $extKey => $logAr ) {
			$this->cli_echo('Evaluating extension: "'.$extKey.'"'.LF);
			foreach($this->deprecatedFunctions as $function => $comment) {
				$fileArr = array();
				$path = t3lib_extMgm::extPath($extKey);
				t3lib_div::getAllFilesAndFoldersInPath($fileArr,$path,'php');
				foreach($fileArr as $phpFile) {
					$fileContents = file_get_contents($phpFile);
					if(stripos($fileContents, $function) !== false) 
					{
					   $this->deprecatedLog[$extKey][$function] = $comment;
					}
				}
			}
		}
	}
	
	private function writeResultsToFile() {
		$file = fopen("deprecated-extensions.txt","w");
		$this->cli_echo('Writing results to deprecated-extensions.txt...'.LF);
		foreach($this->deprecatedLog as $extKey => $logAr) {
			$line = "[".$extKey."]".chr(10);
			fwrite($file, $line);
			foreach($logAr as $function => $comment) {
				$line = $function." ## ".$comment.chr(10);
				fwrite($file, $line);
			}
		}
		fclose($file);
		$this->cli_echo('Work is done!'.LF);
	}
}