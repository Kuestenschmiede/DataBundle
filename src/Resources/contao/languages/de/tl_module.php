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

/**
 * con4gis - the gis-kit
 *
 * @version   php 7
 * @package   east_frisia
 * @author    contributors (see "authors.txt")
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2018
 * @link      https://www.kuestenschmiede.de
 */

$GLOBALS['TL_LANG']['tl_module']['c4g_data_mode'] = ['Lademodus', 'Bestimmt, welche Elemente in diesem Modul geladen werden.'];
$GLOBALS['TL_LANG']['tl_module']['c4g_data_type'] = ['Kategorie', 'Nur Elemente dieser Kategorie werden angezeigt.'];
$GLOBALS['TL_LANG']['tl_module']['c4g_data_directory'] = ['Verzeichnis', 'Nur Elemente in Kategorien aus diesem Verzeichnis werden angezeigt.'];
$GLOBALS['TL_LANG']['tl_module']['mapPage'] = ['Kartenseite', ''];
$GLOBALS['TL_LANG']['tl_module']['redirectPage'] = ['Weiterleitungsseite', ''];
$GLOBALS['TL_LANG']['tl_module']['fieldForRedirect'] = ['Identifier für Weiterleitung', ''];
$GLOBALS['TL_LANG']['tl_module']['captionPlural'] = ['Listentitel', 'Der Titel oberhalb der Liste.'];
$GLOBALS['TL_LANG']['tl_module']['caption'] = ['Detailtitel', 'Der Titel oberhalb der Listendetails.'];
$GLOBALS['TL_LANG']['tl_module']['showSelectFilter'] = ['Zeige Kategoriefilter in der Liste', 'Falls gesetzt, wird über der Liste ein Filterfeld angezeigt, mit dem der Nutzer die Liste nach Kategorie filtern kann.'];
$GLOBALS['TL_LANG']['tl_module']['selectFilterLabel'] = ['Bezeichnung des Kategoriefilters', 'Ist kein Wert angegeben, wird die Standardbezeichnung gewählt.'];
$GLOBALS['TL_LANG']['tl_module']['showDirectorySelectFilter'] = ['Zeige Verzeichnisflter in der Liste', 'Falls gesetzt, wird über der Liste ein Filterfeld angezeigt, mit dem der Nutzer die Liste nach Verzeichnis filtern kann (nur wenn Elemente nach Verzeichnis geladen werden).'];
$GLOBALS['TL_LANG']['tl_module']['directorySelectFilterLabel'] = ['Bezeichnung des Verzeichnisfilters', 'Ist kein Wert angegeben, wird die Standardbezeichnung gewählt.'];
$GLOBALS['TL_LANG']['tl_module']['labelMode'] = ['Bezeichnungsmodus', 'Bestimmt, wie die Bezeichnung im Frontend angezeigt wird.'];
$GLOBALS['TL_LANG']['tl_module']['showFilterResetButton'] = ['Button zum Zurücksetzen der Filteroptionen anzeigen', 'Falls gesetzt, wird über der Liste ein Button angezeigt, mit dem der Nutzer sämtliche Filteroptionen zurücksetzen kann.'];
$GLOBALS['TL_LANG']['tl_module']['filterResetButtonCaption'] = ['Text des Buttons', 'Der Text des Buttons.'];
$GLOBALS['TL_LANG']['tl_module']['hideDetails'] = ['Listendetails nicht anzeigen', 'Falls gesetzt, werden beim Klick auf ein Listenelement nicht dessen Details angezeigt.'];
$GLOBALS['TL_LANG']['tl_module']['showLabelsInList'] = ['In der Liste Labels anzeigen', 'Falls gesetzt, werden in der Liste die Feldnamen mit angezeigt.'];
$GLOBALS['TL_LANG']['tl_module']['phoneLabel'] = ['Telefonfeldbezeichnung', 'Wird vor der Telefonnummer angezeigt. Akzeptiert HTML. Ist nichts angegeben, wird ein Standardtext ausgegeben.'];
$GLOBALS['TL_LANG']['tl_module']['mobileLabel'] = ['Mobiltelefonfeldbezeichnung', 'Wird vor der Mobiltelefonnummer angezeigt. Akzeptiert HTML. Ist nichts angegeben, wird ein Standardtext ausgegeben.'];
$GLOBALS['TL_LANG']['tl_module']['faxLabel'] = ['Faxfeldbezeichnung', 'Wird vor der Faxnummer angezeigt. Akzeptiert HTML. Ist nichts angegeben, wird ein Standardtext ausgegeben.'];
$GLOBALS['TL_LANG']['tl_module']['emailLabel'] = ['Emailfeldbezeichnung', 'Wird vor der E-Mailadresse angezeigt. Akzeptiert HTML. Ist nichts angegeben, wird ein Standardtext ausgegeben.'];
$GLOBALS['TL_LANG']['tl_module']['websiteLabel'] = ['Websitefeldbezeichnung', 'Wird vor dem Link zur Website angezeigt. Akzeptiert HTML. Ist nichts angegeben, wird ein Standardtext ausgegeben.'];
$GLOBALS['TL_LANG']['tl_module']['availableFieldsList'] = ['Verfügbare Felder (Liste)', 'Die Felder, die in der Liste angezeigt werden.'];
$GLOBALS['TL_LANG']['tl_module']['availableFieldsListNonEditable'] = ['Unbearbeitbare Felder', 'Diese Felder können nicht bearbeitet werden, werden aber in der Liste gezeigt.'];
$GLOBALS['TL_LANG']['tl_module']['allowCreateRows'] = ['Anlegen neuer Datensätze verweigern', 'Falls gesetzt, können Datensätze nicht angelegt werden.'];
$GLOBALS['TL_LANG']['tl_module']['allowDeleteRows'] = ['Löschen von Datensätzen verweigern', 'Falls gesetzt, können Datensätze nicht gelöscht werden.'];
$GLOBALS['TL_LANG']['tl_module']['c4g_order_by_fields'] = ['Ordnen nach', 'Ordnet die Liste nach den angegebenen Feldern.'];
$GLOBALS['TL_LANG']['tl_module']['authorizedGroups'] = ['Authorisierte Gruppen', 'Diese Gruppen dürfen im Frontend Elemente anlegen.'];

$GLOBALS['TL_LANG']['tl_module']['c4g_data_type_legend'] = 'Kategorie- & Verzeichnisoptionen';
$GLOBALS['TL_LANG']['tl_module']['c4g_expert_legend'] = 'Experten-Einstellungen';
$GLOBALS['TL_LANG']['tl_module']['mapPage_legend'] = 'Kartenseite';
$GLOBALS['TL_LANG']['tl_module']['caption_legend'] = 'Titel';
$GLOBALS['TL_LANG']['tl_module']['c4g_authorized_groups_legend'] = 'Authorisierte Gruppen';

$GLOBALS['TL_LANG']['tl_module']['c4g_data_mode_option']['0'] = 'Alle Elemente laden';
$GLOBALS['TL_LANG']['tl_module']['c4g_data_mode_option']['1'] = 'Elemente nach Kategorien laden';
$GLOBALS['TL_LANG']['tl_module']['c4g_data_mode_option']['2'] = 'Elemente nach Verzeichnissen laden';

$GLOBALS['TL_LANG']['tl_module']['labelMode_option']['0'] = 'Außerhalb der Auswahl';
$GLOBALS['TL_LANG']['tl_module']['labelMode_option']['1'] = 'Als erste Option';
$GLOBALS['TL_LANG']['tl_module']['labelMode_option']['2'] = 'Außerhalb der Auswahl und als erste Option';