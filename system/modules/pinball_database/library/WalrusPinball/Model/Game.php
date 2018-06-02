<?php
 
/**
 * Walrus Pinball Archive
 *
 * Copyright (C) 2018 Andrew Stevens Consulting
 *
 * @package    asconsulting/pinball_database
 * @link       https://andrewstevens.consulting
 */

 
namespace WalrusPinball\Model;

class Game extends \Model
{
	
	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_wp_archive_game';
	
	
	public static function findAllPublished(array $arrOptions=array())
	{
		$t = static::$strTable;
		return static::findBy(array("$t.published=?"), 1, $arrOptions);
	}

	public static function findByPinsideSlug(array $arrOptions=array())
	{
		// Try to load from the registry
		if (empty($arrOptions))
		{
			$objModel = \Model\Registry::getInstance()->fetch(static::$strTable, $varValue);

			if ($objModel !== null)
			{
				return $objModel;
			}
		}

		$arrOptions = array_merge
		(
			array
			(
				'limit'  => 1,
				'column' => 'pinside_slug',
				'value'  => $varValue,
				'return' => 'Model'
			),

			$arrOptions
		);

		return static::find($arrOptions);
	}
	
	public static function findByIpdbNumber(array $arrOptions=array())
	{
		// Try to load from the registry
		if (empty($arrOptions))
		{
			$objModel = \Model\Registry::getInstance()->fetch(static::$strTable, $varValue);

			if ($objModel !== null)
			{
				return $objModel;
			}
		}

		$arrOptions = array_merge
		(
			array
			(
				'limit'  => 1,
				'column' => 'ipdb_number',
				'value'  => int($varValue),
				'return' => 'Model'
			),

			$arrOptions
		);

		return static::find($arrOptions);
	}
	
}