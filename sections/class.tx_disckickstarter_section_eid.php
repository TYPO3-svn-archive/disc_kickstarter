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
/**
* @author	Jon Langeland <kemo@discworld.dk>
*/

require_once(t3lib_extMgm::extPath('kickstarter').'class.tx_kickstarter_sectionbase.php');

class tx_disckickstarter_section_eid extends tx_kickstarter_sectionbase {
	var $sectionID = 'tx_disckickstarter_section_eid';
	
	/**
	* Renders the form in the kickstarter
	*
	* @return	string		wizard
	*/
	function render_wizard() {
		$lines = array();
		
		$action = explode(':',$this->wizard->modData['wizAction']);
		if ($action[0] == 'edit')	{
			$this->regNewEntry($this->sectionID, $action[1]);
			$lines = $this->catHeaderLines(
				$lines,
				$this->sectionID,
				$this->wizard->options[$this->sectionID],
				'&nbsp;',
				$action[1]
				);
			$piConf   = $this->wizard->wizArray[$this->sectionID][$action[1]];
			$ffPrefix ='['.$this->sectionID.']['.$action[1].']';
			
			
			
			$subContent='<strong>Enter a title for the eID script:</strong><br />'.
			#$this->renderStringBox('title',$ffPrefix,$piConf);
			$this->renderStringBox($ffPrefix.'[title]',$piConf['title']);
			$lines[]='<tr'.$this->bgCol(3).'><td>'.$this->fw($subContent).'</td></tr>';
			
									
			
		}
		
		/* HOOK: Place a hook here, so additional output can be integrated */
		if(is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kickstarter']['add_cat_ts'])) {
			foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kickstarter']['add_cat_ts'] as $_funcRef) {
				$lines = t3lib_div::callUserFunction($_funcRef, $lines, $this);
			}
		}
		
		$content = '<table border="0" cellpadding="2" cellspacing="2">'.implode('',$lines).'</table>';
		return $content;
	}
	
	
	
	
	/**
	* Renders the extension PHP code; this was
	*
	* @param	string		$k: module name key
	* @param	array		$config: module configuration
	* @param	string		$extKey: extension key
	* @return	void
	*/
	function render_extPart($k,$config,$extKey) {
		
		$WOP = '[eID]['.$k.']';
		#$cliPath = t3lib_basicFileFunctions::cleanFileName($config['cName']);
		$eIDDir = 'eid'.$k;
		
		$className = 'tx_'.$extKey.'_eid'.$k;
		
		
		
		
		#$pathSuffix = 'static/'.$tsPath.'/';
		
		#$this->addFileToFileArray(
		#	$pathSuffix.'constants.txt', 
		#	$config['constants']
		#	);
		#$this->addFileToFileArray(
		#	$pathSuffix.'setup.txt',
		#	$config['setup']
		#	);
		
		
		$file = $this->render_eIDClassFile($k,$config,$extKey);
		//$file = 'eID file';
		
		$this->addFileToFileArray(
			$eIDDir.'/eid.'.$className.'.php',
			$file
			);
		
		
		
		
		// add TS definition to ext_tables.php
		
		$this->wizard->ext_localconf[] = $this->sPS(
			$this->WOPcomment('WOP:'.$WOP).chr(10).
			'$TYPO3_CONF_VARS[\'FE\'][\'eID_include\'][\'tx_handelsfinans_faq_eid1\'] = \'EXT:\'.$_EXTKEY.\'/eid1/eid.tx_handelsfinans_faq.php\';'.
			'$TYPO3_CONF_VARS[\'SC_OPTIONS\'][\'GLOBAL\'][\'cliKeys\'][$_EXTKEY] = array(\'EXT:\'.$_EXTKEY.\'/'.$cliDir.'/class.'.$className.'.php\',\'_CLI_'.$config['uName'].'\');',0);
		
	}
	
	function render_eIDClassFile($k,$config,$extKey){
		
		$file = trim($this->sPS('
			<?php
			/***************************************************************
			*  Copyright notice
			*
			*  (c) '.date('Y').' '.$this->userField('name').' <'.$this->userField('email').'>
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
			
		'));
		
		
		$file .= "\n";
		
		
		$file .=trim($this->sPS('
			/**
			 * '.$descr.'
			 *
			 * @author	'.$this->userField('name').' <'.$this->userField('email').'>
			 * @package	TYPO3
			 * @subpackage	'. $this->returnName($extKey, 'class') .'
			 */',
			0
		));
		
		
		$file .=trim($this->sPS('
			
			require_once(\'class.ext_tslib_eidtools.php\');
			
			#tslib_eidtools::connectDb();
			#ext_tslib_eidtools::cObjOnTSFE();
			
			class '.$this->returnName($extKey, 'fields','request_handler').'{
				private $reply;
			
				public function __construct(){
					
				}
			
				public function handleRequest(){
					
				}
			
			
				public function get_reply(){
					return $this->reply;
				}
			}
			
			$handler = new '.$this->returnName($extKey, 'fields','request_handler').'();
			$handler->handleRequest();
			echo $handler->get_reply();
			
			
			?>
			',
			0
		));
			
			
		
		



		
		
		
		
		
		
		return $file;
		
		
	}
	
	
	
}

// Include ux_class extension?
#if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tcdmaildevel/sections/class.tx_tcdmaildevel_section_targets.php']) {
#	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tcdmaildevel/sections/class.tx_tcdmaildevel_section_targets.php']);
#}


?>
