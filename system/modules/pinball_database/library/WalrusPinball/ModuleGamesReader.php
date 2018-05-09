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
 
 
namespace WalrusPinball\Modules;
 
/**
 * Class ModuleGamesReader
 *
 * Front end module "games Reader".
 */
 
class ModuleGamesReader extends \Module
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
 
            $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['games_reader'][0]) . ' ###';
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
		
		$objItem = $this->Database->prepare(
							"SELECT
									id,
									tstamp,
									alias,
									type,
									name,
									ipdb_number,
									klov_number,
									release_year,
									manufacturer,
									image,
									thumbnail,
									published
								FROM
									tl_wp_archive_game		
								WHERE 
									published >= 1 
										AND
									alias LIKE ?"
								)->execute(\Input::get('alias'));
	 
		// Return if no pending items were found
		if (!$objItem->numRows)
		{
			$this->Template->empty = 'No Records Found';
			return;
		}


		$arrItems = array();
 
		// Generate List
		while ($objItem->next())
		{
			$arrItem = array(
				'id'		=> $objItem->id,
				'alias'		=> $objItem->alias,
				'tstamp'	=> $objItem->tstamp,
				'timetamp'	=> \Date::parse(\Config::get('datimFormat'), $objItem->tstamp),
				'published' => $objItem->published
			);
			
			if ($this->jumpTo) {
				$objTarget = $this->objModel->getRelated('jumpTo');
				$arrItem['link'] = $this->generateFrontendUrl($objTarget->row()) .'?alias=' .$objItem->alias;
			}
			
				$arrItem['type'] = $objItem->type;
				$arrItem['name'] = $objItem->name;
				$arrItem['ipdb_number'] = $objItem->ipdb_number;
				$arrItem['klov_number'] = $objItem->klov_number;
				$arrItem['release_year'] = $objItem->release_year;
				$arrItem['manufacturer'] = $objItem->manufacturer;
				$arrItem['image'] = array();
				if ($objItem->image != '') {
					$arrRecord = deserialize($objItem->image);
					foreach($arrRecord as $strFile) {
						$uuid = \StringUtil::binToUuid($strFile);
						$objFile = \FilesModel::findByUuid($uuid);
						$arrItem['image'][] = $objFile;
					}
				}
				$arrItem['thumbnail'] = array();
				if ($objItem->thumbnail != '') {
					$arrRecord = deserialize($objItem->thumbnail);
					foreach($arrRecord as $strFile) {
						$uuid = \StringUtil::binToUuid($strFile);
						$objFile = \FilesModel::findByUuid($uuid);
						$arrItem['thumbnail'][] = $objFile;
					}
				}


			$strItemTemplate = ($this->games_customItemTpl != '' ? $this->games_customItemTpl : 'item_games_generated');
			$objTemplate = new \FrontendTemplate($strItemTemplate);
			$objTemplate->item = $arrItem;
			$arrItems[] = $objTemplate->parse();
		}

		$this->Template->items = $arrItems;

	}

}
