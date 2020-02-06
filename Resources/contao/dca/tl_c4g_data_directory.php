<?php
/**
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package   	con4gis
 * @version    7
 * @author  	    con4gis contributors (see "authors.txt")
 * @license 	    LGPL-3.0-or-later
 * @copyright 	Küstenschmiede GmbH Software & Design
 * @link              https://www.con4gis.org
 *
 */


use con4gis\CoreBundle\Classes\DCA\DCA;
use con4gis\CoreBundle\Classes\DCA\Fields\IdField;
use con4gis\CoreBundle\Classes\DCA\Fields\NaturalField;
use con4gis\CoreBundle\Classes\DCA\Fields\SQLField;
use con4gis\CoreBundle\Classes\DCA\Fields\TextField;
use con4gis\CoreBundle\Classes\DCA\Fields\MultiCheckboxField;
use con4gis\DataBundle\Classes\Contao\Callbacks\DirectoryCallback;

$strName = 'tl_c4g_data_directory';
$cbClass = DirectoryCallback::class;

$dca = new DCA($strName);
$dca->config()->markAsCopy('name');
$list = $dca->list();
$list->sorting()->fields(['name']);
$list->sorting()->panelLayout('filter;sort,search,limit');
$list->label()->fields(['name']);
$list->addRegularOperations($dca);
$dca->palette()->default('{data_legend},name,types');

$id = new IdField('id', $dca);

$tStamp = new NaturalField('tstamp', $dca);

$name = new TextField('name', $dca);
$name->filter()
    ->search();
$name->eval()
    ->class('clr')
    ->mandatory();

$types = new MultiCheckboxField('types', $dca);
$types->optionsCallback($cbClass, 'loadTypeOptions');

$importId = new SQLField("importId", $dca, "int(20) unsigned NOT NULL default '0'");

