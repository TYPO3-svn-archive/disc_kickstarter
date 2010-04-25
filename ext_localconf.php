<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

$TYPO3_CONF_VARS['EXTCONF']['kickstarter']['sections']['tx_disckickstarter_section_cli'] = array(
	'classname' => 'tx_disckickstarter_section_cli',
	'filepath' => 'EXT:disc_kickstarter/sections/class.tx_disckickstarter_section_cli.php',
	'title' => 'CLI dispatch script',
	'description' => 'Create a Command Line Interface Script that kan be called via typo3/cli_dispatch.phpsh.',
	);

$TYPO3_CONF_VARS['EXTCONF']['kickstarter']['sections']['tx_disckickstarter_section_eid'] = array(
	'classname' => 'tx_disckickstarter_section_eid',
	'filepath' => 'EXT:disc_kickstarter/sections/class.tx_disckickstarter_section_eid.php',
	'title' => 'Extension ID (eID) script',
	'description' => 'Create a extension ID which will launch alternative output engine. Called via index.php?eID=tx_extname_eid1',
	);

$TYPO3_CONF_VARS['EXTCONF']['kickstarter']['sections']['tx_disckickstarter_section_scheduler'] = array(
	'classname' => 'tx_disckickstarter_section_scheduler',
	'filepath' => 'EXT:disc_kickstarter/sections/class.tx_disckickstarter_section_scheduler.php',
	'title' => 'Scheduled tasks',
	'description' => 'Creates the basic files for the Scheduler extension.',
	);

?>