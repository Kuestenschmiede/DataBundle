<?php

$strName = 'tl_c4g_mapcontent_location';

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strName]['name'] = ['Name der Lokation', 'Geben Sie einen Namen für die Lokation ein.'];
$GLOBALS['TL_LANG'][$strName]['loctype'] = ['Lokationstyp', 'Wählen Sie die Art der Lokation aus.'];
$GLOBALS['TL_LANG'][$strName]['geox'] = ['Geo-X-Koordinate', ''];
$GLOBALS['TL_LANG'][$strName]['geoy'] = ['Geo-Y-Koordinate', ''];
$GLOBALS['TL_LANG'][$strName]['geoJson'] = ['Geo-JSON', ''];

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

/**
 * Operations
 */
$GLOBALS['TL_LANG'][$strName]['new'] = ["Neue Lokation erstellen", "Neue Lokation erstellen"];
$GLOBALS['TL_LANG'][$strName]['show'] = ["Lokation ID %s anzeigen", "Lokation ID %s anzeigen"];
$GLOBALS['TL_LANG'][$strName]['edit'] = ["Lokation ID %s bearbeiten", "Lokation ID %s bearbeiten"];
$GLOBALS['TL_LANG'][$strName]['copy'] = ["Lokation ID %s kopieren", "Lokation ID %s kopieren"];
$GLOBALS['TL_LANG'][$strName]['delete'] = ["Lokation ID %s löschen", "Lokation ID %s löschen"];