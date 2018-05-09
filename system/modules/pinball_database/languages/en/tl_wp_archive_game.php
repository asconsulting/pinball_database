<?php

/**
 * Walrus Pinball Database
 *
 * Copyright (C) 2018 Andrew Stevens
 *
 * @package    Walrus Pinball Database
 * @link       https://walruspinball.com
 * @license    All Rights Reserved
 */


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_wp_archive_game']['game_legend'] 		= 'Game Configuration';
$GLOBALS['TL_LANG']['tl_wp_archive_game']['detail_legend'] 		= 'Game Details';
$GLOBALS['TL_LANG']['tl_wp_archive_game']['pinside_legend'] 	= 'Pinside Data';
$GLOBALS['TL_LANG']['tl_wp_archive_game']['publish_legend'] 	= 'Publish';

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_wp_archive_game']['alias'] 				= array('Alias', 'Please enter an alias.');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['type'] 				= array('Type', 'Type');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['title'] 				= array('Title', 'Title');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['subtitle']			= array('Subtitle', 'Subtitle');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['ipdb_number'] 		= array('IPDB Number', 'IPDB Number');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['klov_number'] 		= array('KLOV Number', 'KLOV Number');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['pinside_number'] 	= array('Pinside number', 'Pinside Number');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['pinside_slug'] 		= array('Pinside slug', 'Pinside slug');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['release_year'] 		= array('Year of Release', 'Year of Release');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['manufacturer'] 		= array('Manufacturer', 'Manufacturer');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['image'] 				= array('Image', 'Image');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['thumbnail'] 			= array('Thumbnail', 'Thumbnail');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['pinside_thumb'] 		= array('Pinside Thumbnail', 'Url for Pinside Thumbnail');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['production'] 		= array('Production', 'Quantity of games produced.');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['system'] 			= array('System', 'System (MPU) that controls this game.');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['system_family'] 		= array('System Family', 'Family of MPUs this game belongs to.');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['system_version'] 	= array('System Version', 'Version of the System (MPU).');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['machine_type'] 		= array('Machine', 'Technology that powers this game.');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['published'] 			= array('Published', 'Show this record on the front end.');
 
 /**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_wp_archive_game']['new']    			= array('New record', 'Add a new record');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['show']   			= array('Record details', 'Show the details of record ID %s');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['edit']   			= array('Edit record', 'Edit record ID %s');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['copy']   			= array('Copy record', 'Copy record ID %s');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['delete'] 			= array('Delete record', 'Delete record ID %s');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['toggle'] 			= array('Toggle record', 'Toggle record ID %s');

$GLOBALS['TL_LANG']['tl_wp_archive_game']['regenerateAliases'] 	= array('Regenerate Aliases', 'Regenerate all game aliases');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['parseMpu'] 			= array('Parse MPU', 'Scrape MPU HTML files.');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['parseManufacturers'] = array('Parse Manufacturer', 'Scrape manufacturer HTML files.');
$GLOBALS['TL_LANG']['tl_wp_archive_game']['parseQueries'] 		= array('Parse Queries', 'Parse search JSON files.');
