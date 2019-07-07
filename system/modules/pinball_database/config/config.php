<?php

/**
 * Walrus Pinball Database
 *
 * Copyright (C) 2018 Andrew Stevens
 *
 * @package    Walrus Pinball Database
 * @link       https://walruspinball.com
 */


/**
* Back end modules
*/
if (!is_array($GLOBALS['BE_MOD']['wp_archive']))
{
    array_insert($GLOBALS['BE_MOD'], 1, array('wp_archive' => array()));
}

array_insert($GLOBALS['BE_MOD']['wp_archive'], 0, array
(			
	'wp_archive_game' => array(
		'tables' 				=> array('tl_wp_archive_game'),
		'icon'   				=> 'system/modules/pinball_database/assets/icons/list.png',
		'regenerateAliases' 	=> array('WalrusPinball\Games', 'regenerateAliases'),
		'parseQueries' 			=> array('WalrusPinball\Games', 'parseQueries'),
		'parseMpu' 				=> array('WalrusPinball\Games', 'parseMpu'),
		'parseManufacturers' 	=> array('WalrusPinball\Games', 'parseManufacturers'),
		'export' 				=> array('WalrusPinball\Games', 'exportTable')
	)	
), $GLOBALS['BE_MOD']);


/**
* Front end modules
*/
$GLOBALS['FE_MOD']['wp_archive']['games_list'] 			= 'ModuleGamesList';
$GLOBALS['FE_MOD']['wp_archive']['games_reader'] 		= 'ModuleGamesReader';

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_wp_archive_game'] 				= 'WalrusPinball\Model\Game';

/**
 * Hooks
 */
//$GLOBALS['TL_HOOKS']['getPageIdFromUrl'][] = array('WalrusPinball\Api', 'loadReaderPageFromUrl');
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] 	= array('WalrusPinball\Frontend\Game', 'insertTags');