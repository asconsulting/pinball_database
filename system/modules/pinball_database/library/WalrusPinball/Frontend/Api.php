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

use Contao\Environment;
use Contao\Input;
use Contao\System;
use Contao\Frontend as Contao_Frontend;

class Api extends Contao_Frontend {

    protected $store_id = 0;

    protected $strCookie = 'FE_USER_AUTH';

    public function initializeApi() {
        if (substr(Environment::get('request'), 0, 4) == "wp_api/") {

            $arrRequest = explode('/', Environment::get('request'));
            switch($arrRequest[1]) {
                case "game":
					if (Input::get('search')) {
						$arrGames = array();
						
						header('Content-Type: application/json');
						
						$objGame = Game::findPublishedByPartialTitle(Input::get('search'));
						if ($objGame) {
							while ($objGame->next()) {
								$arrRow = $objGame->row();
								if (Input::get('game_type')) {
									if ($objGame->type == Input::get('game_type')) {
										$arrGames[] = $arrRow;
									}
								} else {
									$arrGames[] = $arrRow;
								}
							}
						}
						
						echo json_encode(array('games'=>$arrGames), JSON_NUMERIC_CHECK);
					}
                break;
            }
            exit();
        }
    }
}
