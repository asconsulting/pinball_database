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
 * Table tl_wp_archive_game
 */
$GLOBALS['TL_DCA']['tl_wp_archive_game'] = array
(
 
    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'enableVersioning'            => true,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'alias' => 'index'
            )
        )
    ),
 
    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 1,
            'fields'                  => array('title'),
            'flag'                    => 1,
            'panelLayout'             => 'filter;search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('title', 'subtitle'),
            'format'                  => '%s (%s)'
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            ),
            'regenerateAliases' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['regenerateAliases'],
                'href'                => 'key=regenerateAliases',
                'class'               => 'header_regenerate_aliases',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"',
				'icon'  			  => 'system/modules/pinball_database/assets/icons/code.png',
            ),
            'parseMpu' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['parseMpu'],
                'href'                => 'key=parseMpu',
                'class'               => 'header_parse_mpu',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"',
				'icon'  			  => 'system/modules/pinball_database/assets/icons/code.png',
            ),
            'parseManufacturers' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['parseManufacturers'],
                'href'                => 'key=parseManufacturers',
                'class'               => 'header_parse_html',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"',
				'icon'  			  => 'system/modules/pinball_database/assets/icons/code.png',
            ),
            'parseQueries' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['parseQueries'],
                'href'                => 'key=parseQueries',
                'class'               => 'header_parse_queries',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"',
				'icon'  			  => 'system/modules/pinball_database/assets/icons/code.png',
            ),
            'export' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['export'],
                'href'                => 'key=export',
                'class'               => 'header_export_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"',
				'icon'  			  => 'system/modules/pinball_database/assets/icons/export.png',
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_wp_archive_game', 'toggleIcon')
			),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),
 
    // Palettes
    'palettes' => array
    (
		'__selector__'				  => array('type', 'is_custom'),
        'default'                     => '{game_legend},title,subtitle,alias,type',
		'pinball' 					  => '{game_legend},title,subtitle,alias,type,ipdb_number,pinside_number,pinside_slug;{detail_legend},manufacturer,release_year,production,limited_edition,machine_type,system_family,system,system_version;{custom_legend},is_custom;{media_legend},pinside_thumb,image,thumbnail;{publish_legend},published;',
		'pitch' 					  => '{game_legend},title,subtitle,alias,type,ipdb_number;{detail_legend},manufacturer,release_year,image,thumbnail;{custom_legend},customized;{publish_legend},published;',
		'arcade' 					  => '{game_legend},title,subtitle,alias,type,klov_number;{detail_legend},manufacturer,release_year,image,thumbnail;{custom_legend},customized;{publish_legend},published;',
		'redemption' 				  => '{game_legend},title,subtitle,alias,type;{detail_legend},manufacturer,release_year,image,thumbnail;{custom_legend},customized;{publish_legend},published;',
		'other' 					  => '{game_legend},title,subtitle,alias,type;{detail_legend},manufacturer,release_year,image,thumbnail;{custom_legend},customized;{publish_legend},published;'
    ),
	
	// Subpalettes
	'subpalettes' => array
	(
		'is_custom'                  => 'customized,custom_details,game_customized',
	),
 
    // Fields
    'fields' => array
    (
	
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['title'],
			'inputType'               => 'text',
			'default'				  => '',
			'search'                  => true,
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'clr w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'subtitle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['subtitle'],
			'inputType'               => 'text',
			'default'				  => '',
			'eval'                    => array('tl_class'=>'clr w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['alias'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'search'                  => true,
			'eval'                    => array('unique'=>true, 'rgxp'=>'alias', 'doNotCopy'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_wp_archive_game', 'generateAlias')
			),
			'sql'                     => "varchar(128) COLLATE utf8_bin NOT NULL default ''"

		),
		'type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['type'],
			'inputType'               => 'select',
			'filter'				  => true,
			'options'				  => array('pinball'=>'Pinball', 'pitch'=>'Pitch and Bat', 'arcade'=>'Video Arcade', 'redemption'=>'Redemption', 'other'=>'Other'),
			'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'clr w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'ipdb_number' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['ipdb_number'],
			'inputType'               => 'text',
			'default'				  => '',
			'search'                  => true,
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'klov_number' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['klov_number'],
			'inputType'               => 'text',
			'default'				  => '',
			'search'                  => true,
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'pinside_number' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['pinside_number'],
			'inputType'               => 'text',
			'default'				  => '',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'clr w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'pinside_slug' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['pinside_slug'],
			'inputType'               => 'text',
			'default'				  => '',
			'search'                  => true,
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'pinside_thumb' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['pinside_thumb'],
			'inputType'               => 'text',
			'default'				  => '',
			'eval'                    => array('tl_class'=>'clr w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'manufacturer' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['manufacturer'],
			'inputType'               => 'text',
			'default'				  => '',
			'eval'                    => array('tl_class'=>'clr w50'),
			'filter'                  => true,
			'search'                  => true,	
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'release_year' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['release_year'],
			'inputType'               => 'text',
			'default'				  => '',
			'filter'                  => true,
			'search'                  => true,
			'eval'                    => array('rgxp'=>'natural', 'maxlength'=>4, 'minlength'=>4, 'tl_class'=>'w50'),
			'sql'                     => "varchar(4) NOT NULL default ''"
		),
		'production' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['production'],
			'inputType'               => 'text',
			'default'				  => '',
			'eval'                    => array('rgxp'=>'digit', 'maxlength'=>6, 'minlength'=>1, 'tl_class'=>'clr w50'),
			'sql'                     => "varchar(6) NULL"
		),
		'machine_type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['machine_type'],
			'inputType'               => 'select',
			'filter'				  => true,
			'options'				  => array('EM'=>'Electro-Mechanical', 'SS'=>'Solid State'),
			'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'clr w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'system_family' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['system_family'],
			'inputType'               => 'text',
			'default'				  => '',
			'eval'                    => array('tl_class'=>'w50'),
			'filter'                  => true,
			'sql'                     => "varchar(255) NULL"
		),
		'system' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['system'],
			'inputType'               => 'text',
			'default'				  => '',
			'eval'                    => array('tl_class'=>'clr w50'),
			'filter'                  => true,
			'sql'                     => "varchar(255) NULL"
		),
		'system_version' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['system_version'],
			'inputType'               => 'text',
			'default'				  => '',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "varchar(255) NULL"
		),
		'image' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['image'],
			'inputType'               => 'fileTree',
			'eval'                    => array(),
			'sql'                     => "blob NULL"
		),
		'thumbnail' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['thumbnail'],
			'inputType'               => 'fileTree',
			'eval'                    => array(),
			'sql'                     => "blob NULL"
		),
		'limited_edition' => array
		(
			'label'                   => $GLOBALS['TL_LANG']['tl_wp_archive_game']['limited_edition'],
			'inputType'               => 'checkbox',
			'filter'				  => true,
			'eval'                    => array('tl_class'=>'w50 m12'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'is_custom' => array
		(
			'label'                   => $GLOBALS['TL_LANG']['tl_wp_archive_game']['is_custom'],
			'inputType'               => 'checkbox',
			'filter'				  => true,
			'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'clr w50 m12'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'customized' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['customized'],
			'inputType'               => 'select',
			'filter'				  => true,
			'options'				  => array('complete'=>'Complete Custom Game', 'retheme'=>'Custom Re-Themed Game', 'art_sound'=>'Custom Art and/or Sound'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'custom_details' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['custom_details'],
			'inputType'               => 'textarea',
			'eval'                    => array('rows'=>4, 'cols'=>40, 'tl_class'=>'clr long'),
			'sql'                     => "mediumtext NULL"
		),
		'game_customized' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['game_customized'],
			'inputType'               => 'select',
			'foreignKey'              => 'tl_wp_archive_game.id',
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
			'relation'                => array('type'=>'hasOne', 'load'=>'lazy'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wp_archive_game']['published'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true, 'doNotCopy'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		)		
    )
);

/**
 * Class tl_wp_archive_game
 */
class tl_wp_archive_game extends Backend
{
	/**
	 * Auto-generate an article alias if it has not been set yet
	 * @param mixed
	 * @param \DataContainer
	 * @return string
	 * @throws \Exception
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;
		$strFieldToAlias = '';

		// Generate an alias if there is none
		if ($varValue == '')
		{
			$autoAlias = true;
			if ($strFieldToAlias != '') {
				$varValue = standardize(StringUtil::restoreBasicEntities($dc->activeRecord->{$strFieldToAlias}));
			} else {
				$varValue = standardize(StringUtil::restoreBasicEntities(md5(uniqid() .$dc->activeRecord->id)));
			}
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_wp_archive_game WHERE id=? OR alias=?")
								   ->execute($dc->id, $varValue);

		// Check whether the page alias exists
		if ($objAlias->numRows > 1)
		{
			if (!$autoAlias)
			{
				throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
			}

			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}
	

	/**
	 * Return the "toggle visibility" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(Input::get('tid')))
		{
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
	}	
	
	
	/**
	 * Disable/enable a user group
	 * @param integer
	 * @param boolean
	 * @param \DataContainer
	 */
	public function toggleVisibility($intId, $blnVisible, DataContainer $dc=null)
	{
		$objVersions = new Versions('tl_wp_archive_game', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_wp_archive_game']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_wp_archive_game']['fields']['published']['save_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, ($dc ?: $this));
				}
				elseif (is_callable($callback))
				{
					$blnVisible = $callback($blnVisible, ($dc ?: $this));
				}
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_wp_archive_game SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$objVersions->create();
		$this->log('A new version of record "tl_wp_archive_game.id='.$intId.'" has been created'.$this->getParentEntries('tl_wp_archive_game', $intId), __METHOD__, TL_GENERAL);
	}		
		
}
