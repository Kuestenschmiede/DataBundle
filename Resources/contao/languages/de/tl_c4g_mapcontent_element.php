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

$GLOBALS['TL_LANG'][$strName]['businessHours'] = ['Öffnungszeiten', 'Die Öffnungszeiten in Listenform.'];
$GLOBALS['TL_LANG'][$strName]['businessHoursAdditionalInfo'] = ['Zusätzliche Angaben', 'Weitere Angaben zu den Öffnungszeiten.'];
$GLOBALS['TL_LANG'][$strName]['dayFrom'] = ['Tag von', ''];
$GLOBALS['TL_LANG'][$strName]['dayTo'] = ['Tag bis', ''];
$GLOBALS['TL_LANG'][$strName]['timeFrom'] = ['Uhrzeit von', ''];
$GLOBALS['TL_LANG'][$strName]['timeTo'] = ['Uhrzeit bis', ''];

$GLOBALS['TL_LANG'][$strName]['addressName'] = ['Name', 'Namensbestandteil der Adresse (Firma, Inhaber, etc.)'];
$GLOBALS['TL_LANG'][$strName]['addressStreet'] = ['Straße', 'Straße ohne Hausnummer.'];
$GLOBALS['TL_LANG'][$strName]['addressStreetNumber'] = ['Hausnummer', ''];
$GLOBALS['TL_LANG'][$strName]['addressZip'] = ['Postleitzahl', ''];
$GLOBALS['TL_LANG'][$strName]['addressCity'] = ['Ort', ''];

$GLOBALS['TL_LANG'][$strName]['phone'] = ['Telefonnummer', ''];
$GLOBALS['TL_LANG'][$strName]['mobile'] = ['Mobiltelefonnummer', ''];
$GLOBALS['TL_LANG'][$strName]['fax'] = ['Fax', ''];
$GLOBALS['TL_LANG'][$strName]['email'] = ['Email-Adresse', ''];
$GLOBALS['TL_LANG'][$strName]['website'] = ['Website', ''];
$GLOBALS['TL_LANG'][$strName]['image'] = ['Bild', 'Ein Bild, das das Element zeigt.'];
$GLOBALS['TL_LANG'][$strName]['imageMaxHeight'] = ['Maximale Bildhöhe (Pixel)', 'Das Seitenverhältnis wird beibehalten.'];
$GLOBALS['TL_LANG'][$strName]['imageMaxWidth'] = ['Maximale Bildbreite (Pixel)', 'Das Seitenverhältnis wird beibehalten.'];
$GLOBALS['TL_LANG'][$strName]['loctype'] = ['Lokationstyp', 'Wählen Sie die Art der Lokation aus.'];
$GLOBALS['TL_LANG'][$strName]['geox'] = ['Geo-X-Koordinate', 'Wählen Sie die X-Koordinate aus. Mit Verwendung des Geopickers werden beide Felder automatisch befüllt.'];
$GLOBALS['TL_LANG'][$strName]['geoy'] = ['Geo-Y-Koordinate', 'Wählen Sie die Y-Koordinate aus. Mit Verwendung des Geopickers werden beide Felder automatisch befüllt.'];
$GLOBALS['TL_LANG'][$strName]['geoJson'] = ['Geo-JSON', 'Geben Sie das GeoJSON an. Wenn Sie den Editor verwenden, wird das GeoJSON automatisch erzeugt und hier eingetragen.'];

/**
 * References
 */
$GLOBALS['TL_LANG'][$strName]['loctype_ref']['point'] = "Punkt";
$GLOBALS['TL_LANG'][$strName]['loctype_ref']['line'] = "Strecke";
$GLOBALS['TL_LANG'][$strName]['loctype_ref']['circle'] = "Kreis";
$GLOBALS['TL_LANG'][$strName]['loctype_ref']['polygon'] = "Polygon";

/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strName]['data_legend'] = 'Allgemeine Daten';
$GLOBALS['TL_LANG'][$strName]['businessHours_legend'] = 'Öffnungszeiten';
$GLOBALS['TL_LANG'][$strName]['address_legend'] = 'Adresse';
$GLOBALS['TL_LANG'][$strName]['contact_legend'] = 'Kontakt';
$GLOBALS['TL_LANG'][$strName]['description_legend'] = 'Beschreibung';
$GLOBALS['TL_LANG'][$strName]['image_legend'] = 'Bild';
$GLOBALS['TL_LANG'][$strName]['location_legend'] = 'Koordinaten';
$GLOBALS['TL_LANG'][$strName]['filter_legend'] = 'Filterdaten';

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

$GLOBALS['TL_LANG'][$strName]['day_join'] = [
    'to' => 'bis',
    'and' => 'und'
];

/** Frontend */
$GLOBALS['TL_LANG'][$strName]['address'] = ['Adresse', ''];
