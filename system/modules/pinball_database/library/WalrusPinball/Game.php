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

use WalrusPinball\Model\Game as GameModel;
 
/**
 * Class WalrusPinball\Games
 */
 
class Game extends \Frontend
{
	public function insertTags($strTag) 
	{
		if (stristr($strTag, "::") === FALSE) {
			return false;
		}
		
		$arrTag = explode("::", $strTag);
		
		if (substr($arrTag[0], 0, 4) != 'game' && substr($arrTag[0], 0, 9) != 'game_data') {
			return false;
		}
		
		$strReturn = false;
		
		return 'game ' .$arrTag[1];
		
		switch($arrTag[0]) {
			case 'game':
				
				$objGame = GameModel::findByIdOrAlias($arrTag[1]);
				if (!$objGame) {
					return false;
				}
				
				$strTemplate = 'item_game_inserttag';
				
				if ($arrTag[2]) {
					$strTemplate = $arrTag[2];
				}
				
				$objTemplate = new \FrontendTemplate($strTemplate);
				if (!$objTemplate) { 
					$objTemplate = new \FrontendTemplate('item_game_inserttag');
				}
				$objTemplate->setData($objGame->row());
				
				return $objTemplate->parse();
				
			break;
			
			case 'game_data':
				
				$objGame = GameModel::findByIdOrAlias($arrTag[1]);
				if (!$objGame) {
					return false;
				}
				
				if (!$arrTag[2]) {
					return false;
				}
				
				$arrData = $objGame->row();
				
				return $arrData[$arrTag[2]];
				
			break;
		}
		
	}

} 
