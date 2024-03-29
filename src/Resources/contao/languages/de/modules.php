<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2022, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

use con4gis\DataBundle\Controller\MemberEditableController;
use con4gis\DataBundle\Controller\PublicNonEditableController;

$GLOBALS['TL_LANG']['MOD']['c4g_data_type'] = ['Kategorien', 'Kategorien bearbeiten'];
$GLOBALS['TL_LANG']['MOD']['c4g_data_element'] = ['Datenerfassung', 'Kartenelemente bearbeiten'];
$GLOBALS['TL_LANG']['MOD']['c4g_data_custom_field'] = ['Eigene Felder', 'Benutzerdefinierte Felder bearbeiten'];
$GLOBALS['TL_LANG']['MOD']['c4g_data_directory'] = ['Verzeichnisse', 'Verzeichnisse bearbeiten'];

$GLOBALS['TL_LANG']['FMD'][PublicNonEditableController::TYPE] = ['(data) Auflistung ohne Schreibzugriff', 'Darstellung der Elemente ohne Schreibzugriff'];
$GLOBALS['TL_LANG']['FMD'][MemberEditableController::TYPE] = ['(data) Auflistung mit Schreibzugriff für Mitglieder', 'Darstellung der Elemente mit Schreibzugriff'];