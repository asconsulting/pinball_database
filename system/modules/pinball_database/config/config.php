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
$GLOBALS['BE_MOD'] = array_merge(
	array(
		'wp_archive' => array(
			'wp_archive_game' => array(
				'tables' 				=> array('tl_wp_archive_game'),
				'icon'   				=> 'system/modules/pinball_database/assets/icons/list.png',
				'regenerateAliases' 	=> array('WalrusPinball\Games', 'regenerateAliases'),
				'parseQueries' 			=> array('WalrusPinball\Games', 'parseQueries'),
				'parseMpu' 				=> array('WalrusPinball\Games', 'parseMpu'),
				'parseManufacturers' 	=> array('WalrusPinball\Games', 'parseManufacturers'),
				'export' 				=> array('WalrusPinball\Games', 'exportTable')
			)	
		)
	), $GLOBALS['BE_MOD']);


/**
* Front end modules
*/
$GLOBALS['FE_MOD']['wp_archive']['games_list'] 			= 'ModuleGamesList';
$GLOBALS['FE_MOD']['wp_archive']['games_reader'] 		= 'ModuleGamesReader';


/**
 * Hooks
 */
//$GLOBALS['TL_HOOKS']['getPageIdFromUrl'][] = array('WalrusPinball\Api', 'loadReaderPageFromUrl');
