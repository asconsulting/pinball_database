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
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Base Classes
    'WalrusPinball\Api' 						=> 'system/modules/pinball_database/library/WalrusPinball/Api.php',
    'WalrusPinball\Games' 						=> 'system/modules/pinball_database/library/WalrusPinball/Games.php',
	
	// Module Classes
	'WalrusPinball\Modules\ModuleGamesList' 	=> 'system/modules/pinball_database/library/WalrusPinball/Modules/ModuleGamesList.php',
	'WalrusPinball\Modules\ModuleGamesReader' 	=> 'system/modules/pinball_database/library/WalrusPinball/Modules/ModuleGamesReader.php',
		
	// Models
	'WalrusPinball\Model\Game' 					=> 'system/modules/pinball_database/library/WalrusPinball/Model/Game.php'
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	// Module templates
	'mod_games_list' 					=> 'system/modules/pinball_database/templates/modules',
	
	// Item Templates
	'item_games_default' 				=> 'system/modules/pinball_database/templates/items',
));
