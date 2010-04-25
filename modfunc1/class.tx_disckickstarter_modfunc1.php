<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Jon Langeland <kemo@discworld.dk>
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

require_once(PATH_t3lib.'class.t3lib_extobjbase.php');

/**
* Module extension (addition to function menu) 'Clone Extension' for the 'disc_kickstarter' extension.
*
* @author    Jon Langeland <kemo@discworld.dk>
* @package    TYPO3
* @subpackage    tx_disckickstarter
*/
class tx_disckickstarter_modfunc1 extends t3lib_extobjbase {
	
	/**
	* Main method of the module
	*
	* @return    HTML
	*/
	function main()    {
		// Initializes the module. Done in this function because we may need to re-initialize if data is submitted!
		global $SOBE,$BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;
		
		
		
		if (t3lib_div::_GP('submit')=='') {
			
			$extList = $this->getExtList();
			
			$option = '<option value="">Select:</option>';
			foreach($extList AS $extItem){
				$option .= '<option value="'.$extItem.'">'.$extItem.'</option>';
			}
			
			
			$content='<form>';
			$content.='<input type="hidden" name="SET[function]" value="tx_disckickstarter_modfunc1">';
			$content.='<select name="actualkey">';
			$content.=$option;
			$content.='</select>';
			
			
			
			$content.='Copy to key:<input name="newkey">';
			$content.='<input type="submit" name="submit" value="do it"></form>';
			
			$content .='<h3>What it does</h3><ul><li>All files and folders are copied to the new extension folder: typo3conf/ext/****</li>';
			$content .='<li>In every file the appearance of the extensionname is replaced:<ul><li>old_key -> new_key</li><li>tx_oldkey -> tx_newkey</li></ul></ul>';
			
		}
		else {
			$actualkey=t3lib_div::_GP('actualkey');
			$newkey=t3lib_div::_GP('newkey');
			if (strcmp($newkey,'') && strcmp($actualkey,''))	{
				//$from=t3lib_extMgm::extPath($actualkey);
				$from=PATH_site.'typo3conf/ext/'.$actualkey.'/';
				$to=PATH_site.'typo3conf/ext/'.$newkey.'/';
				$renames=array();
				$renames['tx_'.str_replace('_','',$actualkey)]='tx_'.str_replace('_','',$newkey);
				$renames[$actualkey]=$newkey;
				
				$content.='From: '.$from.' To:'.$to;
				
				$content.=$this->copyAllFilesWithRename($from,$to,$renames);
				$content.='<hr>';
				foreach($this->debug as $debug) {
					$content.=$debug.'<br>';
				}
			}
			else {
				$content.='Please enter two valid Extensionkeys, and be sure that the actual extension is installed';
			}
		}		
		
		
		
		$theOutput.=$this->pObj->doc->spacer(5);
		$theOutput.=$this->pObj->doc->section($LANG->getLL("title"),$content,0,1);
		
		
		/*
		$menu=array();
		$menu[]=t3lib_BEfunc::getFuncCheck($this->wizard->pObj->id,"SET[tx_disckickstarter_modfunc1_check]",$this->wizard->pObj->MOD_SETTINGS["tx_disckickstarter_modfunc1_check"]).$LANG->getLL("checklabel");
		$theOutput.=$this->pObj->doc->spacer(5);
		$theOutput.=$this->pObj->doc->section("Menu",implode(" - ",$menu),0,1);
		*/
		
		
		return $theOutput;
	}
	
	
	function getExtList(){
		$extpath = PATH_site.'typo3conf/ext/';
		return t3lib_div::get_dirs($extpath); 
	}
	
	
	function copyAllFilesWithRename($from,$to,$renames) {
		if (!is_dir($to))
			mkdir($to);
		//Now go to Directory and load all Files
		$handle=@opendir($from);
		if (!$handle) {
			die ("error - directory $from not existsststst");						
			return;
		}
		
		while (false !== ($file = readdir ($handle))) {					
			if (is_dir($from.$file) && $file != "." && $file != "..") {				
				$this->copyAllFilesWithRename($from.$file.'/',$to.$file.'/',$renames);
			}					
			elseif (is_file($from.$file)) {								
				$filenew=$this->help_renameString($file,$renames);
				$ft=fopen($to.$filenew,"w");
				$fs=fopen($from.$file,"r");
				while (!feof($fs)) {
					$buffer = fgets($fs, 4096);
					$buffer=$this->help_renameString($buffer,$renames);									
					fwrite($ft, $buffer);										
				}
				$this->debug[]='Write '.$to.$filenew;
				fclose ($fs); 
				fclose ($ft); 
			}
		}
		closedir($handle);
		
	}
	
	function help_renameString($string,$renames) {
		foreach ($renames as $k=>$v) {
			$string=str_replace($k,$v,$string);
		}
		return $string;
	}
	
	
	
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/disc_kickstarter/modfunc1/class.tx_disckickstarter_modfunc1.php'])    {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/disc_kickstarter/modfunc1/class.tx_disckickstarter_modfunc1.php']);
}

?>
