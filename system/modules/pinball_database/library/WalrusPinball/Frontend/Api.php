<?php

/**
 * Walrus Pinball Database
 *
 * Copyright (C) 2018 Andrew Stevens
 *
 * @package    Walrus Pinball Database
 * @link       https://walruspinball.com
 */



namespace WalrusPinball\Frontend;


use WalrusPinball\Model\Game;


use Contao\Model\Collection;
use Contao\Database;
use Contao\Environment;
use Contao\Input;
use Contao\System;
use Contao\Frontend as Contao_Frontend;

class Api extends Contao_Frontend {

    protected $store_id = 0;

    protected $strCookie = 'FE_USER_AUTH';

    public function initializeApi() {
        if (substr(Environment::get('request'), 0, 7) == "wp_api/") {

            $arrRequest = explode('/', Environment::get('request'));
;
            switch($arrRequest[1]) {
                case "game":
					if (Input::get('search')) {
						$arrGames = array();
						
						header('Content-Type: application/json');
						
						$count = 0;
						
						$strType = trim(Input::get('type'));
						if (strtolower($strType) == "undefined") {
							$strType = false;
						}
						if ($strType == "") {
							$strType = false;
						}
						
						
						$objResConnection = System::getContainer()->get('database_connection');
						if ($strType) {
							$objDatabase = Database::getInstance()->prepare("SELECT id FROM tl_wp_archive_game WHERE published='1' AND type=? AND title LIKE '%" .trim($objResConnection->quote(Input::get('search')), "'") ."%'")->execute($strType);
						} else {
							$objDatabase = Database::getInstance()->execute("SELECT id FROM tl_wp_archive_game WHERE published='1' AND title LIKE '%" .trim($objResConnection->quote(Input::get('search')), "'") ."%'");
						}
						if ($objDatabase) {
							$objGame = Collection::createFromDbResult($objDatabase, 'tl_wp_archive_game');
							
							//$objGame = Game::findPublishedByPartialTitle(Input::get('search'), $strType);
							if ($objGame) {
								while ($objGame->next()) {
									$arrRow = $objGame->row();
									if (Input::get('game_type')) {
										if ($objGame->type == Input::get('game_type')) {
											$arrGames[] = $arrRow;
											$count++;
										}
									} else {
										$arrGames[] = $arrRow;
										$count++;
									}
									if ($count == (Input::get('limit') ? Input::get('limit') : 10)) {
										break 1;
									}
								}
						}
						}
						
						echo json_encode($arrGames, JSON_NUMERIC_CHECK);
					}
                break;
            }
            exit();
        }
    }
}
