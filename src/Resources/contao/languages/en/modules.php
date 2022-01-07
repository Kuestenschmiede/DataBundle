<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2021, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

use con4gis\DataBundle\Controller\MemberEditableController;
use con4gis\DataBundle\Controller\PublicNonEditableController;

$GLOBALS['TL_LANG']['MOD']['c4g_data_type'] = ['Categories', 'Edit Categories'];
$GLOBALS['TL_LANG']['MOD']['c4g_data_element'] = ['Data entry', 'Edit map elements'];
$GLOBALS['TL_LANG']['MOD']['c4g_data_custom_field'] = ['Custom fields', 'Edit custom fields'];
$GLOBALS['TL_LANG']['MOD']['c4g_data_directory'] = ['Directories', 'Edit directories'];

$GLOBALS['TL_LANG']['FMD'][PublicNonEditableController::TYPE] = ['(data) Listing without write access', 'List of elements without write access'];
$GLOBALS['TL_LANG']['FMD'][MemberEditableController::TYPE] = ['(data) Listing with write access for members', 'List of elements with write access'];