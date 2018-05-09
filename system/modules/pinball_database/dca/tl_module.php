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
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['games_list'] 	= '{title_legend},name,headline,type,showGameTypes;{template_legend:hide},customTpl,customItemTpl;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['games_reader']	= '{title_legend},name,headline,type;{template_legend:hide},customTpl;{expert_legend:hide},guests,cssID,space';

/**
 * Add field to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['customItemTpl'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['customItemTpl'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_pintastic', 'getItemTemplates'),
	'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
	'sql'                     => "varchar(64) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['showGameTypes'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['showGameTypes'],
	'exclude'                 => true,
	'default'				  => 'all',
	'inputType'               => 'select',
	'options'				  => array('pinball'=>'Pinball Machines', 'arcade'=>'Arcade Machines', 'redeption'=>'Redeption Machines', 'other'=>'Other Machines'),
	'eval'                    => array('includeBlankOption'=>true, 'multiple'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);


class tl_module_wp_archive extends Backend
{
	/**
	 * Return all item templates as array
	 *
	 * @return array
	 */
	public function getItemTemplates()
	{
		return $this->getTemplateGroup('item_');
	}

}