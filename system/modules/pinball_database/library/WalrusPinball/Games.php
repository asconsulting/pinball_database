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
 
 
namespace WalrusPinball;
 
/**
 * Class WalrusPinball\Games
 */
 
class Games extends \Backend
{
	/**
     * Parse Queries
     */
	public function parseQueries(\DataContainer $dc)
	{
		$objFolder = new \Folder('files/import/queries');
		$strPath = TL_ROOT ."/" .$objFolder->path;
		$strReturn = "<strong>Searching:</strong> " .$strPath ."<br>";
		
		$arrFiles = scandir($strPath);
		
		$arrGames = array();
		
		$intFiles = 0;
		foreach ($arrFiles as $file) {
			if (substr($file, -5) == '.json') {
				$intFiles++;
				$strJson = file_get_contents(strPath ."/" .$file);
				$arrData = json_decode($strJson);
				if ($arrData->list) {
					foreach($arrData->list as $objGame) {
						$arrGame = array();
						$arrGame['name'] = $objGame->title;
						$intYear = FALSE;
						$strManufacturer = FALSE;
						if (preg_match('/, [0-9]{4}$/', $objGame->subtitle, $arrMatches)) {
							$intYear = intval(trim($arrMatches[0] ,', '));
							$strManufacturer = str_replace($arrMatches[0], '', $objGame->subtitle);
							$arrGame['manufacturer'] = $strManufacturer;
							$arrGame['year'] = $intYear;
							$arrGame['pinside_slug'] = $objGame->slug;
							$arrGame['image'] = $objGame->image;
						}
						
						if ($arrGame['manufacturer'] == 'Premier') {
							$arrGame['manufacturer'] = 'Gottlieb';
						}
						if ($arrGame['manufacturer'] == 'Bally Midway') {
							$arrGame['manufacturer'] = 'Bally';
						}
						if ($arrGame['manufacturer'] == 'Midway' && $arrGame['year'] > 1982) {
							$arrGame['manufacturer'] = 'Bally';
						}
						if ($arrGame['manufacturer'] == 'Spooky Pinball ...' || $arrGame['manufacturer'] == 'Spooky Pinball, LLC') {
							$arrGame['manufacturer'] = 'Spooky Pinball';
						}
						if ($arrGame['manufacturer'] == 'Chicago Gaming ...' || $arrGame['manufacturer'] == 'Chicago Gaming Company' || $arrGame['manufacturer'] == 'Chicago Gaming Co.') {
							$arrGame['manufacturer'] = 'Chicago Gaming';
						}
						if ($arrGame['manufacturer'] == 'Heighway Pinbal...' || $arrGame['manufacturer'] == 'Heighway Pinball, Ltd.') {
							$arrGame['manufacturer'] = 'Heighway Pinball';
						}
						
						$strAlias = str_replace('&', 'and', str_replace('&amp;', 'and', preg_replace('/, the$/i', '', str_ireplace("o'fun", 'o-fun', $arrGame['name'])) .'-' .$arrGame['manufacturer'] .'-' .$arrGame['year']));
						$strAlias = strtolower(str_replace('\\', '', str_replace('_', '-', str_replace(' ', '-', $strAlias))));
						$strAlias = preg_replace('/[^a-z0-9\-]/', '', $strAlias);
						while (stristr($strAlias, '--') !== FALSE) {
							$strAlias = str_replace('--','-',$strAlias);
						}
						
						$arrGame['slug'] = $strAlias;
						
						$arrGames[$arrGame['slug']] = $arrGame;						
					}
				}
				unlink(TL_FILES ."/import/queries/" .$file);
			}
		}
		
		$arrImport['games_found'] = count($arrGames);
		$arrImport['games_imported'] = 0;
		$arrImport['games_updated'] = 0;
		
		foreach ($arrGames as $arrGame) {
			$objCheck = \Database::getInstance()->prepare('SELECT COUNT(*) as gameCount FROM tl_wp_archive_game WHERE alias LIKE ?')->execute($arrGame['slug']);
				if ($objCheck->gameCount == 0) {
				if ($arrGame['name'] && $arrGame['manufacturer'] && $arrGame['year'] && $arrGame['slug']) {
					$objGame = \Database::getInstance()->prepare("INSERT INTO tl_wp_archive_game SET tstamp=?, title=?, subtitle=?, alias=?, manufacturer=?, release_year=?, type='pinball', pinside_slug=?, pinside_thumb=?")->execute(time(), $arrGame['name'], $arrGame['manufacturer'] .", " .$arrGame['year'], $arrGame['slug'], $arrGame['manufacturer'], $arrGame['year'], $arrGame['pinside_slug'], $arrGame['image']);
					$arrImport['games_imported']++;
				} else {
					$objGame = \Database::getInstance()->prepare("UPDATE tl_wp_archive_game SET tstamp=?, pinside_slug=?, pinside_thumb=? WHERE alias LIKE ?")->execute(time(), $arrGame['pinside_slug'], $arrGame['image'], $arrGame['slug']);
					$arrImport['games_updated']++;
				}
			}
			set_time_limit(15);
		}
		
		$strReturn .= "<strong>Files Found:</strong> " .$intFiles ."<br>";	
		$strReturn .= "<strong>Games Found:</strong> " .$arrImport['games_found'] ."<br>";
		$strReturn .= "<strong>Games Imported:</strong> " .$arrImport['games_imported'] ."<br>";
		$strReturn .= "<strong>Games Updated:</strong> " .$arrImport['games_updated'] ."<br>";

		return $strReturn;
	}

	
	/**
     * Parse Queries
     */
	public function regenerateAliases(\DataContainer $dc)
	{
		$this->correctManufacturers();
		$this->regenerateSubtitle();
		$objGame = \Database::getInstance()->execute('SELECT id, title, manufacturer, release_year FROM tl_wp_archive_game');
		while($objGame->next()) {
			$strAlias = str_replace('&', 'and', preg_replace('/, the$/i', '', str_ireplace("o'fun", 'o-fun', html_entity_decode($objGame->title))) .'-' .$objGame->manufacturer .'-' .$objGame->release_year);
			$strAlias = strtolower(str_replace('\\', '', str_replace('_', '-', str_replace(' ', '-', $strAlias))));
			$strAlias = preg_replace('/[^a-z0-9\-]/', '', $strAlias);
			while (stristr($strAlias, '--') !== FALSE) {
				$strAlias = str_replace('--','-',$strAlias);
			}
	
			$objLookup = \Database::getInstance()->prepare('SELECT id FROM tl_wp_archive_game WHERE id!=? AND alias=?')->execute($objGame->id, $strAlias);
			if ($objLookup->numRows > 0) {
				$strAlias .= '-' .$objGame->id;
			}
			if ($strAlias != '') {
				\Database::getInstance()->prepare('UPDATE tl_wp_archive_game SET alias=? WHERE id=?')->execute($strAlias, $objGame->id);
			}
		}
		
	}
	
	/**
     * Parse Queries
     */
	public function parseManufacturers(\DataContainer $dc)
	{
		$objFolder = new \Folder('files/import/manufacturers');
		$strPath = TL_ROOT ."/" .$objFolder->path;
		$strReturn = "<strong>Searching:</strong> " .$strPath ."<br>";
		
		$arrFiles = scandir($strPath);
		$arrGames = array();
		
		libxml_use_internal_errors(true);
		
		$arrImport = array();
		
		$intFiles = 0;
		foreach ($arrFiles as $file) {
			if (substr($file, -5) == '.html') {
				$intFiles++;
				
				$strHtml = file_get_contents($strPath ."/" .$file);
				
				$objHtml = new \DOMDocument();
				$objHtml->loadHTML($strHtml);
				$objTable = $objHtml->getElementById('gamelist');
				
				$rows = $objTable->getElementsByTagName('tr');
				foreach ($rows as $row) {
					$intColumn = 0;
					$arrGame = array();
					
					foreach($row->getElementsByTagName('td') as $node) {
				
						if ($intColumn === 0) {  // Date
							$intYear = intval(substr($node->nodeValue, 0, 4));
							if ($intYear > 0) {
								$arrGame['year'] = $intYear;
							}
						} else if ($intColumn == 1) {
							$strName = trim($node->childNodes[0]->nodeValue);
							$intIpdb = intval(str_replace('game', '', $node->childNodes[0]->getAttribute('target')));
							
							if (!$intIpdb) {
								$intIpdb = intval(str_replace('#', '', $node->childNodes[0]->getAttribute('href')));
							}
							
							if ($strName != '') {
								$arrGame['name'] = $strName;
							}
							if ($intIpdb > 0) {
								$arrGame['ipdb_number'] = $intIpdb;
							}
						} else if ($intColumn == 2) {
							$strManufacturer = trim($node->childNodes[0]->nodeValue);
							if ($strManufacturer != '') {
								$arrGame['manufacturer'] = $strManufacturer;
								if ($arrGame['manufacturer'] == 'Premier') {
									$arrGame['manufacturer'] = 'Gottlieb';
								}
								if ($arrGame['manufacturer'] == 'Bally Midway') {
									$arrGame['manufacturer'] = 'Bally';
								}
								if ($arrGame['manufacturer'] == 'Midway' && $arrGame['year'] > 1982) {
									$arrGame['manufacturer'] = 'Bally';
								}
								if ($arrGame['manufacturer'] == 'Spooky Pinball ...' || $arrGame['manufacturer'] == 'Spooky Pinball, LLC') {
									$arrGame['manufacturer'] = 'Spooky Pinball';
								}
								if ($arrGame['manufacturer'] == 'Chicago Gaming ...' || $arrGame['manufacturer'] == 'Chicago Gaming Company' || $arrGame['manufacturer'] == 'Chicago Gaming Co.') {
									$arrGame['manufacturer'] = 'Chicago Gaming';
								}
								if ($arrGame['manufacturer'] == 'Heighway Pinbal...' || $arrGame['manufacturer'] == 'Heighway Pinball, Ltd.') {
									$arrGame['manufacturer'] = 'Heighway Pinball';
								}
							}
						} else if ($intColumn == 4) {
							$intProduction = intval(preg_replace('/[^0-9]/', '', $node->nodeValue));
							if ($intProduction > 0) {
								$arrGame['production'] = $intProduction;
							}
						}
						$intColumn++;
					}
					
					$strAlias = str_replace('&', 'and', str_replace('&amp;', 'and', preg_replace('/, the$/i', '', str_ireplace("o'fun", 'o-fun', $arrGame['name'])) .'-' .$arrGame['manufacturer'] .'-' .$arrGame['year']));
					$strAlias = strtolower(str_replace('\\', '', str_replace('_', '-', str_replace(' ', '-', $strAlias))));
					$strAlias = preg_replace('/[^a-z0-9\-]/', '', $strAlias);
					while (stristr($strAlias, '--') !== FALSE) {
						$strAlias = str_replace('--','-',$strAlias);
					}
					
					$arrGame['slug'] = $strAlias;

					$arrGames[] = $arrGame;
				}
			}
		}
		
		$arrImport['games_found'] = count($arrGames);
		$arrImport['games_imported'] = 0;
		$arrImport['games_updated'] = 0;
		
		foreach ($arrGames as $arrGame) {
			if (!empty($arrGame)) {
				$objCheck = \Database::getInstance()->prepare('SELECT COUNT(*) as gameCount FROM tl_wp_archive_game WHERE alias LIKE ?')->execute($arrGame['slug']);
				if ($objCheck->gameCount == 0) {
					if ($arrGame['name'] && $arrGame['manufacturer'] && $arrGame['year'] && $arrGame['slug']) {
						$objGame = \Database::getInstance()->prepare("INSERT INTO tl_wp_archive_game SET tstamp=?, title=?, subtitle=?, ipdb_number=?, production=?, alias=?, manufacturer=?, release_year=?, type='pinball'")->execute(time(), $arrGame['name'], $arrGame['manufacturer'] .", " .intval($arrGame['year']), $arrGame['ipdb_number'], $arrGame['production'], $arrGame['slug'], $arrGame['manufacturer'], $arrGame['year']);
						$arrImport['games_imported']++;
					}
				} else {
					$objGame = \Database::getInstance()->prepare("UPDATE tl_wp_archive_game SET tstamp=?, title=?, subtitle=?, ipdb_number=?, production=?, manufacturer=?, release_year=?, type='pinball' WHERE alias LIKE ?")->execute(time(), $arrGame['name'], $arrGame['manufacturer'] .", " .$arrGame['year'], $arrGame['ipdb_number'], $arrGame['production'], $arrGame['manufacturer'], $arrGame['year'], $arrGame['slug']);
					$arrImport['games_updated']++;
				}
				set_time_limit(15);
			}
		}
		
		$strReturn .= "<strong>Files Found:</strong> " .$intFiles ."<br>";	
		$strReturn .= "<strong>Games Found:</strong> " .$arrImport['games_found'] ."<br>";
		$strReturn .= "<strong>Games Imported:</strong> " .$arrImport['games_imported'] ."<br>";
		$strReturn .= "<strong>Games Updated:</strong> " .$arrImport['games_updated'] ."<br>";

		return $strReturn;
	}	
	
	
	/**
     * Parse Mpu
     */
	public function parseMpu(\DataContainer $dc)
	{
		
		$objFolder = new \Folder('files/import/mpu');
		$strPath = TL_ROOT ."/" .$objFolder->path;
		$strReturn = "<strong>Searching:</strong> " .$strPath ."<br>";
		
		$arrFiles = scandir($strPath);
		$arrGames = array();
			
		libxml_use_internal_errors(true);
		
		$intFiles = 0;
		
		foreach ($arrFiles as $file) {
			if (substr($file, -5) == '.html') {
				$intFiles++;
				$strHtml = file_get_contents($strPath ."/" .$file);
				
				$objHtml = new \DOMDocument();
				$objHtml->loadHTML($strHtml);
				$objTable = $objHtml->getElementById('gamelist');
				if (!$objTable) {
					
				} else {
					$rows = $objTable->getElementsByTagName('tr');
					foreach ($rows as $row) {
						$intColumn = 0;
						$arrGame = array();
						
						foreach($row->getElementsByTagName('td') as $node) {
					
							if ($intColumn === 0) {  // Date
								$intYear = intval(substr($node->nodeValue, 0, 4));
								if ($intYear > 0) {
									$arrGame['year'] = $intYear;
								}
							} else if ($intColumn == 1) {
								$strName = trim($node->childNodes[0]->nodeValue);
								$intIpdb = intval(str_replace('game', '', $node->childNodes[0]->getAttribute('target')));
								
								if (!$intIpdb) {
									$intIpdb = intval(str_replace('#', '', $node->childNodes[0]->getAttribute('href')));
								}
								
								if ($strName != '') {
									$arrGame['name'] = $strName;
								}
								if ($intIpdb > 0) {
									$arrGame['ipdb_number'] = $intIpdb;
								}
							} else if ($intColumn == 2) {
								$strManufacturer = trim($node->childNodes[0]->nodeValue);
								if ($strManufacturer != '') {
									$arrGame['manufacturer'] = $strManufacturer;
									if ($arrGame['manufacturer'] == 'Premier') {
										$arrGame['manufacturer'] = 'Gottlieb';
									}
									if ($arrGame['manufacturer'] == 'Bally Midway') {
										$arrGame['manufacturer'] = 'Bally';
									}
									if ($arrGame['manufacturer'] == 'Midway' && $arrGame['year'] > 1982) {
										$arrGame['manufacturer'] = 'Bally';
									}
									if ($arrGame['manufacturer'] == 'Spooky Pinball ...' || $arrGame['manufacturer'] == 'Spooky Pinball, LLC') {
										$arrGame['manufacturer'] = 'Spooky Pinball';
									}
									if ($arrGame['manufacturer'] == 'Chicago Gaming ...' || $arrGame['manufacturer'] == 'Chicago Gaming Company' || $arrGame['manufacturer'] == 'Chicago Gaming Co.') {
										$arrGame['manufacturer'] = 'Chicago Gaming';
									}
									if ($arrGame['manufacturer'] == 'Heighway Pinbal...' || $arrGame['manufacturer'] == 'Heighway Pinball, Ltd.') {
										$arrGame['manufacturer'] = 'Heighway Pinball';
									}
									
								}
							} else if ($intColumn == 4) {
								$intProduction = intval(preg_replace('/[^0-9]/', '', $node->nodeValue));
								if ($intProduction > 0) {
									$arrGame['production'] = $intProduction;
								}
							}
							$intColumn++;
						}
						
						$arrGame['machine_type'] = 'SS';
						$arrGame['system'] = basename($file, '.html');
						switch($arrGame['system']) {
							case "Atari System 1":
								$arrGame['system_family'] = 'Atari - System 1';
								$arrGame['system_version'] = 'System 1';
							break;
							
							case "Atari System 2":
								$arrGame['system_family'] = 'Atari - System 2';
								$arrGame['system_version'] = 'System 2';
							break;
							
							case "Atari System 3":
								$arrGame['system_family'] = 'Atari - System 3';
								$arrGame['system_version'] = 'System 3';
							break;
							
							case "Bally 6803":
								$arrGame['system_family'] = 'Bally - 6803';
								$arrGame['system_version'] = '6803';
							break;
							
							case "Bally AS-2518-17":
								$arrGame['system_family'] = 'Bally - AS-2518';
								$arrGame['system_version'] = '-17';
							break;
							
							case "Bally AS-2518-35":
								$arrGame['system_family'] = 'Bally - AS-2518';
								$arrGame['system_version'] = '-35';
							break;
							
							case "Bally AS-2518-133":
								$arrGame['system_family'] = 'Bally - AS-2518';
								$arrGame['system_version'] = '-133';
							break;
							
							case "Data East - Sega - v1":
								$arrGame['system_family'] = 'Data East / Sega';
								$arrGame['system_version'] = 'v1';
							break;
							
							case "Data East - Sega - v2":
								$arrGame['system_family'] = 'Data East / Sega';
								$arrGame['system_version'] = 'v2';
							break;
							
							case "Data East - Sega - v3":
								$arrGame['system_family'] = 'Data East / Sega';
								$arrGame['system_version'] = 'v3';
							break;
							
							case "Data East - Sega - v3b":
								$arrGame['system_family'] = 'Data East / Sega';
								$arrGame['system_version'] = 'v3b';
							break;
							
							case "Game Plan MPU-1":
								$arrGame['system_family'] = 'Game Plan';
								$arrGame['system_version'] = 'MPU-1';
							break;
							
							case "Game Plan MPU-2":
								$arrGame['system_family'] = 'Game Plan';
								$arrGame['system_version'] = 'MPU-2';
							break;			
				
							case "Gottlieb System 1":
								$arrGame['system_family'] = 'Gottlieb - System 1';
								$arrGame['system_version'] = '1';
							break;
														
							case "Gottlieb System 3":
								$arrGame['system_family'] = 'Gottlieb - System 3';
								$arrGame['system_version'] = '3';
							break;
							
							case "Gottlieb System 80":
								$arrGame['system_family'] = 'Gottlieb - System 80';
								$arrGame['system_version'] = '80';
							break;
							
							case "Gottlieb System 80A":
								$arrGame['system_family'] = 'Gottlieb - System 80';
								$arrGame['system_version'] = '80A';
							break;
							
							case "Gottlieb System 80B":
								$arrGame['system_family'] = 'Gottlieb - System 80';
								$arrGame['system_version'] = '80B';
							break;
							
							case "Playmatic MPU 1":
								$arrGame['system_family'] = 'Playmatic';
								$arrGame['system_version'] = '1';
							break;
														
							case "Playmatic MPU 3":
								$arrGame['system_family'] = 'Playmatic';
								$arrGame['system_version'] = '3';
							break;
							
							case "Playmatic MPU 4":
								$arrGame['system_family'] = 'Playmatic';
								$arrGame['system_version'] = '4';
							break;
							
							case "Playmatic MPU 5":
								$arrGame['system_family'] = 'Playmatic';
								$arrGame['system_version'] = '5';
							break;
							
							case "Playmatic MPU-C":
								$arrGame['system_family'] = 'Playmatic';
								$arrGame['system_version'] = 'C';
							break;
							
							case "Recel System III":
								$arrGame['system_family'] = 'Recel - System III';
								$arrGame['system_version'] = 'System III';
							break;

							case "Sega 95534":
								$arrGame['system_family'] = 'Sega - Early';
								$arrGame['system_version'] = '95534';
							break;
							
							case "Sega 95680":
								$arrGame['system_family'] = 'Sega - Early';
								$arrGame['system_version'] = '95680';
							break;
							
							case "Sega 96054":
								$arrGame['system_family'] = 'Sega - Early';
								$arrGame['system_version'] = '96054';
							break;
							
							case "Stern SAM":
								$arrGame['system_family'] = 'Stern - SAM';
								$arrGame['system_version'] = 'SAM';
							break;
							
							case "Stern Spike":
								$arrGame['system_family'] = 'Stern - Spike';
								$arrGame['system_version'] = 'Spike';
							break;
							
							case "Stern Spike 2":
								$arrGame['system_family'] = 'Stern - Spike 2';
								$arrGame['system_version'] = 'Spike 2';
							break;
							
							case "Stern M-100":
								$arrGame['system_family'] = 'Stern - M';
								$arrGame['system_version'] = '100';
							break;
							
							case "Stern M-200":
								$arrGame['system_family'] = 'Stern - M';
								$arrGame['system_version'] = '200';
							break;		

							case "Williams Pinball 2000":
								$arrGame['system_family'] = 'Williams - Pinball 2000';
								$arrGame['system_version'] = 'Pinball 2000';
							break;							
							
							case "Williams System 11":
								$arrGame['system_family'] = 'Williams - System 11';
								$arrGame['system_version'] = '11';
							break;
							
							case "Williams System 11A":
								$arrGame['system_family'] = 'Williams - System 11';
								$arrGame['system_version'] = '11A';
							break;
							
							case "Williams System 11B":
								$arrGame['system_family'] = 'Williams - System 11';
								$arrGame['system_version'] = '11B';
							break;
							
							case "Williams System 11C":
								$arrGame['system_family'] = 'Williams - System 11';
								$arrGame['system_version'] = '11C';
							break;								
							
							case "Williams System 3":
								$arrGame['system_family'] = 'Williams - System 3-7';
								$arrGame['system_version'] = '3';
							break;							
							
							case "Williams System 4":
								$arrGame['system_family'] = 'Williams - System 3-7';
								$arrGame['system_version'] = '4';
							break;
							
							case "Williams System 6":
								$arrGame['system_family'] = 'Williams - System 3-7';
								$arrGame['system_version'] = '6';
							break;
							
							case "Williams System 6A":
								$arrGame['system_family'] = 'Williams - System 3-7';
								$arrGame['system_version'] = '6A';
							break;
							
							case "Williams System 7":
								$arrGame['system_family'] = 'Williams - System 3-7';
								$arrGame['system_version'] = '7';
							break;	
							
							case "Williams System 8":
								$arrGame['system_family'] = 'Williams - System 8';
								$arrGame['system_version'] = '8';
							break;
														
							case "Williams System 9":
								$arrGame['system_family'] = 'Williams - System 9';
								$arrGame['system_version'] = '9';
							break;
							
							case "Williams WPC - Alphanumeric":
								$arrGame['system_family'] = 'Williams - WPC';
								$arrGame['system_version'] = 'Alphanumeric';
							break;
							
							case "Williams WPC - DCS":
								$arrGame['system_family'] = 'Williams - WPC';
								$arrGame['system_version'] = 'DCS';
							break;								
							
							case "Williams WPC - Dot Matrix":
								$arrGame['system_family'] = 'Williams - WPC';
								$arrGame['system_version'] = 'Dot Matrix';
							break;							
							
							case "Williams WPC - Fliptronics 1":
								$arrGame['system_family'] = 'Williams - WPC';
								$arrGame['system_version'] = 'Fliptronics 1';
							break;
							
							case "Williams WPC - Fliptronics 2":
								$arrGame['system_family'] = 'Williams - WPC';
								$arrGame['system_version'] = 'Fliptronics 2';
							break;
							
							case "Williams WPC-S":
								$arrGame['system_family'] = 'Williams - WPC';
								$arrGame['system_version'] = 'Security';
							break;
							
							case "Williams WPC-95":
								$arrGame['system_family'] = 'Williams - WPC';
								$arrGame['system_version'] = '95';
							break;
							
							case "Zaccaria System 1":
								$arrGame['system_family'] = 'Zaccaria';
								$arrGame['system_version'] = '1';
							break;
							
							case "Zaccaria System 2":
								$arrGame['system_family'] = 'Zaccaria';
								$arrGame['system_version'] = '2';
							break;
							
							default:
								$arrGame['system_family'] = $arrGame['system'];
							break;
						}
						
						$strAlias = str_replace('&', 'and', str_replace('&amp;', 'and', preg_replace('/, the$/i', '', str_ireplace("o'fun", 'o-fun', $arrGame['name'])) .'-' .$arrGame['manufacturer'] .'-' .$arrGame['year']));
						$strAlias = strtolower(str_replace('\\', '', str_replace('_', '-', str_replace(' ', '-', $strAlias))));
						$strAlias = preg_replace('/[^a-z0-9\-]/', '', $strAlias);
						while (stristr($strAlias, '--') !== FALSE) {
							$strAlias = str_replace('--','-',$strAlias);
						}
						
						$arrGame['slug'] = $strAlias;

						$arrGames[] = $arrGame;					
					}
				}
			}
		}
		
		$arrImport['games_found'] = count($arrGames);
		$arrImport['games_imported'] = 0;
		$arrImport['games_updated'] = 0;
		
		foreach ($arrGames as $arrGame) {
			if (!empty($arrGame) && $arrGame['year']) {
				$objCheck = \Database::getInstance()->prepare('SELECT COUNT(*) as gameCount FROM tl_wp_archive_game WHERE alias LIKE ?')->execute($arrGame['slug']);
				if ($objCheck->gameCount == 0) {
					if ($arrGame['name'] && $arrGame['manufacturer'] && $arrGame['year'] && $arrGame['slug']) {
						$objGame = \Database::getInstance()->prepare("INSERT INTO tl_wp_archive_game SET tstamp=?, title=?, subtitle=?, ipdb_number=?, production=?, alias=?, manufacturer=?, release_year=?, system_family=?, system=?, system_version=?, type='pinball'")->execute(time(), $arrGame['name'], $arrGame['manufacturer'] .", " .$arrGame['year'], $arrGame['ipdb_number'], $arrGame['production'], $arrGame['slug'], $arrGame['manufacturer'], $arrGame['year'], $arrGame['system_family'], $arrGame['system'], $arrGame['system_version']);
						$arrImport['games_imported']++;
					}
				} else {
					$objGame = \Database::getInstance()->prepare("UPDATE tl_wp_archive_game SET tstamp=?, machine_type=?, system_family=?, system=?, system_version=? WHERE alias LIKE ?")->execute(time(), $arrGame['machine_type'], $arrGame['system_family'], $arrGame['system'], $arrGame['system_version'], $arrGame['slug']);
					$arrImport['games_updated']++;
				}
				set_time_limit(15);
			}
		}
		
		$strReturn .= "<strong>Files Found:</strong> " .$intFiles ."<br>";				
		$strReturn .= "<strong>Games Found:</strong> " .$arrImport['games_found'] ."<br>";
		$strReturn .= "<strong>Games Imported:</strong> " .$arrImport['games_imported'] ."<br>";
		$strReturn .= "<strong>Games Updated:</strong> " .$arrImport['games_updated'] ."<br>";

		return $strReturn;
	}
	
	
	protected function correctManufacturers() {
		\Database::getInstance()->execute('UPDATE tl_wp_archive_game SET manufacturer="Gottlieb" WHERE manufacturer LIKE "Premier"');
		\Database::getInstance()->execute('UPDATE tl_wp_archive_game SET manufacturer="Bally" WHERE manufacturer LIKE "Bally Midway"');
		\Database::getInstance()->execute('UPDATE tl_wp_archive_game SET manufacturer="Bally" WHERE manufacturer LIKE "Midway" AND release_year > 1982');
		\Database::getInstance()->execute('UPDATE tl_wp_archive_game SET manufacturer="Spooky Pinball" WHERE manufacturer LIKE "%Spooky%"');
		\Database::getInstance()->execute('UPDATE tl_wp_archive_game SET manufacturer="Chicago Gaming" WHERE manufacturer LIKE "%Chicago Gaming%"');
		\Database::getInstance()->execute('UPDATE tl_wp_archive_game SET manufacturer="Heighway Pinball" WHERE manufacturer LIKE "%Heighway%"');
	}

	protected function regenerateSubtitle() {
		\Database::getInstance()->execute("UPDATE tl_wp_archive_game SET subtitle=CONCAT(manufacturer, ', ', release_year) WHERE manufacturer!='' AND release_year!=''");
		\Database::getInstance()->execute("UPDATE tl_wp_archive_game SET subtitle=manufacturer WHERE manufacturer!='' AND (release_year='' OR release_year = 0)");
	}	
	 
    /**
     * Generate the module
     */
    protected function exportTable()
    {
		
		$objItem = $this->Database->prepare(
							"SELECT
									*
								FROM
									games"
								)->execute();
		
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=games_' .date('Y-m-d_Hi') .'.csv');
		$csv = fopen('php://output', 'w');
		
		$first = TRUE;
		// Generate List
		while ($row = $objItem->fetchAssoc())
		{
			if ($first) {
				$arrHeader = array();
				foreach($row as $key => $value) {
					$arrHeader[] = $key;
				}
				fputcsv($csv, $arrHeader);
				$first = FALSE;
			}
			fputcsv($csv, $row);
		}
 
		exit;
	}

} 
