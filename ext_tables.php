<?php
if (!defined ('TYPO3_MODE'))     die ('Access denied.');

if (TYPO3_MODE=="BE")    {
	t3lib_extMgm::insertModuleFunction(
		"tools_em",        
		"tx_disckickstarter_modfunc1",
		t3lib_extMgm::extPath($_EXTKEY)."modfunc1/class.tx_disckickstarter_modfunc1.php",
		//"LLL:EXT:disc_kickstarter/locallang_db.xml:moduleFunction.tx_disckickstarter_modfunc1"
		"Clone Extension"
		);
}
?>
