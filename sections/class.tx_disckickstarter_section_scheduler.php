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

class tx_disckickstarter_section_scheduler extends tx_kickstarter_sectionbase {
	var $sectionID = 'tx_disckickstarter_section_scheduler';
	
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
			
			
			
			// Enter a title for the scheduler task
			$subContent='<strong>Enter a title for the scheduler task:</strong><br />'.
			#$this->renderStringBox('title',$ffPrefix,$piConf);
			$this->renderStringBox($ffPrefix.'[title]',$piConf['title']);
			$lines[]='<tr'.$this->bgCol(3).'><td>'.$this->fw($subContent).'</td></tr>';
			
			
			// Enter a description for the scheduler task
			$subContent='<strong>Enter a description for the scheduler task:</strong><br />'.
			#$this->renderStringBox('title',$ffPrefix,$piConf);
			$this->renderStringBox($ffPrefix.'[desc]',$piConf['desc']);
			$lines[]='<tr'.$this->bgCol(3).'><td>'.$this->fw($subContent).'</td></tr>';
			
			// additionalFields
			$subContent='<strong>additionalFields:</strong><br />'.
			#$this->renderStringBox('title',$ffPrefix,$piConf);
			$this->renderStringBox($ffPrefix.'[additionalFields]',$piConf['additionalFields']);
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
		
		$WOP = '[cli]['.$k.']';
		#$cliPath = t3lib_basicFileFunctions::cleanFileName($config['cName']);
		$cliDir = 'cli'.$k;
		
		$className = 'tx_'.$extKey.'_cli'.$k;
		
		
		
		
		#$pathSuffix = 'static/'.$tsPath.'/';
		
		#$this->addFileToFileArray(
		#	$pathSuffix.'constants.txt', 
		#	$config['constants']
		#	);
		#$this->addFileToFileArray(
		#	$pathSuffix.'setup.txt',
		#	$config['setup']
		#	);
		
		
		//$file = $this->render_CLIClassFile($k,$config,$extKey);
		$file = 'TEXT';
		
		$this->addFileToFileArray(
			$cliDir.'/class.'.$className.'.php',
			$file
			);
		
		
		
		
		// add TS definition to ext_tables.php
		
		$this->wizard->ext_localconf[] = $this->sPS(
			$this->WOPcomment('WOP:'.$WOP).chr(10).
			'$TYPO3_CONF_VARS[\'SC_OPTIONS\'][\'GLOBAL\'][\'cliKeys\'][$_EXTKEY] = array(\'EXT:\'.$_EXTKEY.\'/'.$cliDir.'/class.'.$className.'.php\',\'_CLI_'.$config['uName'].'\');',0);
		
	}
	
	function render_CLIClassFile($k,$config,$extKey){
		
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
			
			if (!defined(\'TYPO3_cliMode\'))  die(\'You cannot run this script directly!\');
			
			// Include basis cli class
			require_once(PATH_t3lib.\'class.t3lib_cli.php\');
			
			
			/**
			* Enter description here...
			*
			*/
			class tx_cliexample_cli extends t3lib_cli {
				
				/**
				* Constructor
				*
				* @return tx_cliexample_cli
				*/
				function tx_mfcarticletocontent_cli () {
					
					// Running parent class constructor
					parent::t3lib_cli();
					
					// Setting help texts:
					$this->cli_help[\'name\'] = "Name of script";
					$this->cli_help[\'synopsis\'] = "###OPTIONS###";
					$this->cli_help[\'description\'] = "Class with basic functionality for CLI scripts";
					$this->cli_help[\'examples\'] = "/.../cli_dispatch.phpsh EXTKEY TASK";
					$this->cli_help[\'author\'] = "Julian Kleinhans, (c) 2008";
				}
			
				/**
				* CLI engine
				*
				* @param    array        Command line arguments
				* @return    string
				*/
				function cli_main($argv) {
					
					// get task (function)
					$task = (string)$this->cli_args[\'_DEFAULT\'][1];
					
					if (!$task){
						$this->cli_validateArgs();
						$this->cli_help();
						exit;
					}
					
					if ($task == \'myFunction\') {
						$this->cli_echo("\n\nmyFunction will be called:\n\n");
						$this->myFunction();            
					}
					
					/**
					* Or other tasks
					* Which task shoud be called can you define in the shell command
					* /www/typo3/cli_dispatch.phpsh cli_example otherTask
					*/
					if ($task == \'otherTask\') {
						// ...         
					}
				}
				
				/**
				* myFunction which is called over cli
				*
				*/
				function myFunction(){
					
					// Output
					$this->cli_echo("Whats your name:");
					
					// Input
					$input = $this->cli_keyboardInput();
					$this->cli_echo("\n\nHi ".$input.", your CLI script works :)\n\n");
					
					// Input yes/no
					$input = $this->cli_keyboardInput_yes(\'You want money?\');
					if($b){
						$this->cli_echo("\nHaha.. go working! :)\n");
					}else{
						$this->cli_echo("\nOh ok.. are you ill?\n");
					}
				}
			
			}
			
			// Call the functionality
			$cleanerObj = t3lib_div::makeInstance(\'tx_cliexample_cli\');
			$cleanerObj->cli_main($_SERVER[\'argv\']);
			
			
			
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
