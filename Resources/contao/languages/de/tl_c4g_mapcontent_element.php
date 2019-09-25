<?php

$strName = 'tl_c4g_mapcontent_element';

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strName]['name'] = ['Name des Elements', 'Geben Sie einen Namen für dieses Element ein.'];
$GLOBALS['TL_LANG'][$strName]['description'] = ['Beschreibung', 'Geben Sie einen Beschreibungstext für dieses Element ein.'];
$GLOBALS['TL_LANG'][$strName]['location'] = ['Lokation des Elements', 'Wählen Sie die Lokation aus, in der das Element liegt.'];
$GLOBALS['TL_LANG'][$strName]['type'] = ['Elementtyp', 'Wählen Sie den Elementtyp aus.'];
$GLOBALS['TL_LANG'][$strName]['tags'] = ['Tags', 'Wählen Sie die Tags aus, die Sie diesem Element zuweisen möchten.'];

$GLOBALS['TL_LANG'][$strName]['businessHours'] = ['Geschäftszeiten', 'Die Geschäftszeiten in Listenform.'];
$GLOBALS['TL_LANG'][$strName]['dayFrom'] = ['Tag von', ''];
$GLOBALS['TL_LANG'][$strName]['dayTo'] = ['Tag bis', ''];
$GLOBALS['TL_LANG'][$strName]['timeFrom'] = ['Uhrzeit von', ''];
$GLOBALS['TL_LANG'][$strName]['timeTo'] = ['Uhrzeit bis', ''];

$GLOBALS['TL_LANG'][$strName]['addressName'] = ['Name', 'Firma, Inhaber, etc.'];
$GLOBALS['TL_LANG'][$strName]['addressStreet'] = ['Straße', 'Straße ohne Hausnummer.'];
$GLOBALS['TL_LANG'][$strName]['addressStreetNumber'] = ['Hausnummer', ''];
$GLOBALS['TL_LANG'][$strName]['addressZip'] = ['Postleitzahl', ''];
$GLOBALS['TL_LANG'][$strName]['addressCity'] = ['Ort', ''];

$GLOBALS['TL_LANG'][$strName]['phoneNumber'] = ['Telefonnummer', ''];
$GLOBALS['TL_LANG'][$strName]['faxNumber'] = ['Fax', ''];
$GLOBALS['TL_LANG'][$strName]['email'] = ['Email-Adresse', ''];

/**
 * References
 */

/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strName]['data_legend'] = 'Allgemeine Daten';
$GLOBALS['TL_LANG'][$strName]['businessHours_legend'] = 'Geschäftszeiten';
$GLOBALS['TL_LANG'][$strName]['address_legend'] = 'Adresse';
$GLOBALS['TL_LANG'][$strName]['contact_legend'] = 'Kontakt';

/**
 * Operations
 */
$GLOBALS['TL_LANG'][$strName]['new'] = ["Neues Element erstellen", "Neues Element erstellen"];
$GLOBALS['TL_LANG'][$strName]['show'] = ["Element ID %s anzeigen", "Element ID %s anzeigen"];
$GLOBALS['TL_LANG'][$strName]['edit'] = ["Element ID %s bearbeiten", "Element ID %s bearbeiten"];
$GLOBALS['TL_LANG'][$strName]['copy'] = ["Element ID %s kopieren", "Element ID %s kopieren"];
$GLOBALS['TL_LANG'][$strName]['delete'] = ["Element ID %s löschen", "Element ID %s löschen"];

$GLOBALS['TL_LANG'][$strName]['day_reference'] = [
    '0' => 'Montag',
    '1' => 'Dienstag',
    '2' => 'Mittwoch',
    '3' => 'Donnerstag',
    '4' => 'Freitag',
    '5' => 'Samstag',
    '6' => 'Sonntag'
];