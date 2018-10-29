<?php

/**
 * Walrus Pinball Database
 *
 * Copyright (C) 2018 Andrew Stevens
 *
 * @package    Walrus Pinball Database
 * @link       https://walruspinball.com
 */
 
 
namespace WalrusPinball\Modules;
 
/**
 * Class ModuleGamesList
 *
 * Front end module "games List".
 */
 
class ModuleGamesList extends \Module
{
 
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_games_list';
 
    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');
 
            $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['games_list'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&table=tl_module&act=edit&id=' . $this->id;
 
            return $objTemplate->parse();
        }
 
        return parent::generate();
    }
 
 
    /**
     * Generate the module
     */
    protected function compile()
    {

		$arrTypes = deserialize($this->showGameTypes);
		if (!is_array($arrTypes)) {$arrTypes = array();}
		
		$objGame = $this->Database->execute(
							"SELECT
									games.id,
									games.title,
									games.subtitle,
									games.alias,
									games.type,
									games.ipdb_number,
									games.klov_number,
									games.pinside_number,
									games.pinside_slug,
									games.manufacturer,
									games.release_year,
									games.production,
									games.system_family,
									games.system,
									games.system_version,
									games.image,
									games.thumbnail,
									games.pinside_thumb
								FROM
									tl_wp_archive_game AS games
								WHERE 
									games.published >= 1" .(!empty($arrTypes) ? " AND games.type IN ('" .implode("','", $arrTypes) ."')" : "") . "
								ORDER BY games.type DESC, games.title, games.subtitle");
	 	 
		// Return if no pending items were found
		if (!$objGame->numRows)
		{
			$this->Template->empty = 'No Records Found';
			return;
		}

		$arrGames = array();
		$arrGamesRaw = array();
		
		$strSection = FALSE;
 
		// Generate List
		while ($objGame->next())
		{
			$arrGame = array(
				'id'				=> $objGame->id,
				'alias'				=> $objGame->alias,
				'title'				=> $objGame->title,
				'subtitle'			=> $objGame->subtitle,
				'type'				=> $objGame->type,
				'ipdb_number'		=> $objGame->ipdb_number,
				'klov_number'		=> $objGame->klov_number,
				'pinside_number'	=> $objGame->pinside_number,
				'pinside_slug'		=> $objGame->pinside_slug,
				'manufacturer'		=> $objGame->manufacturer,
				'release_year'		=> $objGame->release_year,
				'machine_count' 	=> intval($objGame->machine_count)
			);
			
			if ($this->jumpTo) {
				$objTarget = $this->objModel->getRelated('jumpTo');
				if (intval($arrGame['ipdb_number']) > 0) {$arrGame['link'] = $this->generateFrontendUrl($objTarget->row()) .'?ipdb=' .$objGame->ipdb_number;}
				else if (intval($arrGame['klov_number']) > 0) {$arrGame['link'] = $this->generateFrontendUrl($objTarget->row()) .'?klov=' .$objGame->klov_number;}
				else if ($arrGame['pinside_slug'] != '') {$arrGame['link'] = $this->generateFrontendUrl($objTarget->row()) .'?pinside=' .$objGame->pinside_slug;}
				else if (intval($arrGame['pinside_number']) > 0) {$arrGame['link'] = $this->generateFrontendUrl($objTarget->row()) .'?psnum=' .$objGame->pinside_number;}
				else {$arrGame['link'] = $this->generateFrontendUrl($objTarget->row()) .'?id=' .$objGame->id;}
			}

			$arrGame['image'] = array();
			if ($objGame->image != '') {
				$arrRecord = deserialize($objGame->image);
				foreach($arrRecord as $strFile) {
					$uuid = \StringUtil::binToUuid($strFile);
					$objFile = \FilesModel::findByUuid($uuid);
					$arrGame['image'][] = $objFile;
				}
			}
			$arrGame['thumbnail'] = array();
			if ($objGame->thumbnail != '') {
				$arrRecord = deserialize($objGame->thumbnail);
				foreach($arrRecord as $strFile) {
					$uuid = \StringUtil::binToUuid($strFile);
					$objFile = \FilesModel::findByUuid($uuid);
					$arrGame['thumbnail'][] = $objFile;
				}
			}

			if ($strSection != $objGame->type) {
				$arrGames[] = "<h1>" .ucwords(str_replace("_", " ", $objGame->type)) ."</h1>";
				$strSection = $objGame->type;
			}
			
			$strItemTemplate = ($this->customItemTpl != '' ? $this->customItemTpl : 'item_game_default');
			$objTemplate = new \FrontendTemplate($strItemTemplate);
			$objTemplate->setData($arrGame);
			$arrGamesRaw[] = $arrGame;
			$arrGames[] = $objTemplate->parse();
		}

		if (\Input::get('format') == 'json')
		{
			$strReturn = json_encode($arrGamesRaw);
			echo $strReturn;
			exit();
		}
		
		$this->Template->games = $arrGames;
	
	}

} 
