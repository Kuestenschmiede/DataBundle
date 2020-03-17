<?php

$strName = 'tl_c4g_data_element';

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strName]['id'] = ['ID', ''];
$GLOBALS['TL_LANG'][$strName]['name'] = ['Bezeichnung', 'Geben Sie die Bezeichnung für dieses Element ein.'];
$GLOBALS['TL_LANG'][$strName]['description'] = ['Beschreibung', 'Geben Sie einen Beschreibungstext für dieses Element ein.'];
$GLOBALS['TL_LANG'][$strName]['location'] = ['Lokation des Elements', 'Wählen Sie die Lokation aus, in der das Element liegt.'];
$GLOBALS['TL_LANG'][$strName]['type'] = ['Kategorie', 'Die Kategorie wird im Frontend dargestellt und bestimmt, welche Felder zur Verfügung stehen.'];
$GLOBALS['TL_LANG'][$strName]['parentElement'] = ['Elternelement', 'Wenn im Elternelement Daten eingetragen sind, die hier fehlen, erbt dieses Element die Daten vom Elternelement.'];
$GLOBALS['TL_LANG'][$strName]['linkWizard'] = ['Weitere Links', 'Weitere Links, die generiert werden sollen. Das Titelfeld akzeptiert HTML.'];
$GLOBALS['TL_LANG'][$strName]['linkTitle'] = ['Titel', 'Der sichtbare Text. Akzeptiert HTML.'];
$GLOBALS['TL_LANG'][$strName]['linkHref'] = ['Adresse / URL', 'Die Adresse bzw. URL, auf die der Link zeigt.'];
$GLOBALS['TL_LANG'][$strName]['linkNewTab'] = ['Neuer Tab', 'Falls gesetzt, wird der Link in einem neuen Tab geöffnet.'];

$GLOBALS['TL_LANG'][$strName]['businessHours'] = ['Öffnungszeiten', 'Die Öffnungszeiten in Listenform.'];
$GLOBALS['TL_LANG'][$strName]['businessHoursAdditionalInfo'] = ['Zusätzliche Angaben', 'Weitere Angaben zu den Öffnungszeiten.'];
$GLOBALS['TL_LANG'][$strName]['dayFrom'] = ['Tag von', ''];
$GLOBALS['TL_LANG'][$strName]['dayTo'] = ['Tag bis', ''];
$GLOBALS['TL_LANG'][$strName]['timeFrom'] = ['Uhrzeit von', ''];
$GLOBALS['TL_LANG'][$strName]['timeTo'] = ['Uhrzeit bis', ''];
$GLOBALS['TL_LANG'][$strName]['timeCaption'] = ' Uhr';

$GLOBALS['TL_LANG'][$strName]['contactData'] = ['Kontaktdaten', 'Kontaktdaten'];
$GLOBALS['TL_LANG'][$strName]['address'] = ['Adresse', 'Die vollständige Adresse.'];
$GLOBALS['TL_LANG'][$strName]['addressName'] = ['Name', 'Namensbestandteil der Adresse (Firma, Inhaber, etc.)'];
$GLOBALS['TL_LANG'][$strName]['addressStreet'] = ['Straße', 'Straße ohne Hausnummer.'];
$GLOBALS['TL_LANG'][$strName]['addressStreetNumber'] = ['Hausnummer', ''];
$GLOBALS['TL_LANG'][$strName]['addressZip'] = ['Postleitzahl', ''];
$GLOBALS['TL_LANG'][$strName]['addressCity'] = ['Ort', ''];
$GLOBALS['TL_LANG'][$strName]['addressState'] = ['Bundesland', ''];
$GLOBALS['TL_LANG'][$strName]['addressCountry'] = ['Staat', ''];

$GLOBALS['TL_LANG'][$strName]['accessibility'] = ['Barrierefrei', 'Das Element ist barrierefrei.'];
$GLOBALS['TL_LANG'][$strName]['osmId'] = ['OSM ID', 'Die ID des Elements in der Open Street Map. Falls gesetzt, werden die Daten aus der Open Street Map geladen. Gegebenenfalls werden eingegebene Daten dann ignoriert.'];
$GLOBALS['TL_LANG'][$strName]['publishFrom'] = ['Anzeigen ab', 'Das Element wird ab diesem Tag im Frontend angezeigt.'];
$GLOBALS['TL_LANG'][$strName]['publishTo'] = ['Anzeigen bis', 'Das Element wird bis zu diesem Tag im Frontend angezeigt.'];
$GLOBALS['TL_LANG'][$strName]['published'] = ['Veröffentlicht', 'Falls gesetzt, wird das Element öffentlich im Frontend angezeigt.'];
$GLOBALS['TL_LANG'][$strName]['datePublished'] = ['Zeitpunkt der Veröffentlichung', 'Der Zeitpunkt der Veröffentlichung, wenn die Kategorie dieses Feature unterstützt.'];
$GLOBALS['TL_LANG'][$strName]['ownerGroupId'] = ['Benutzergruppe (Eigentümer)', 'Die Benutzergruppe, dem das Element gehört.'];

$GLOBALS['TL_LANG'][$strName]['phone'] = ['Telefonnummer', ''];
$GLOBALS['TL_LANG'][$strName]['mobile'] = ['Mobiltelefonnummer', ''];
$GLOBALS['TL_LANG'][$strName]['fax'] = ['Fax', ''];
$GLOBALS['TL_LANG'][$strName]['email'] = ['Email-Adresse', ''];
$GLOBALS['TL_LANG'][$strName]['website'] = ['Website (Adresse / URL)', 'Die Adresse bzw. URL der Website.'];
$GLOBALS['TL_LANG'][$strName]['websiteLabel'] = ['Website (Titel)', 'Der sichtbare Text.'];
$GLOBALS['TL_LANG'][$strName]['image'] = ['Bild', 'Ein Bild, das das Element zeigt.'];
$GLOBALS['TL_LANG'][$strName]['imageMaxHeight'] = ['Maximale Bildhöhe (Pixel)', 'Das Seitenverhältnis wird beibehalten.'];
$GLOBALS['TL_LANG'][$strName]['imageMaxWidth'] = ['Maximale Bildbreite (Pixel)', 'Das Seitenverhältnis wird beibehalten.'];
$GLOBALS['TL_LANG'][$strName]['imageLink'] = ['Bildlink', 'Link, zu dem das Bild führt.'];
$GLOBALS['TL_LANG'][$strName]['imageLightBox'] = ['Lightbox', 'Falls gesetzt, wird das Bild bei einem Klick in einer Lightbox dargestellt. (Ist ein Link definiert, hat er Vorrang.)'];
$GLOBALS['TL_LANG'][$strName]['loctype'] = ['Lokationstyp', 'Wählen Sie die Art der Lokation aus.'];
$GLOBALS['TL_LANG'][$strName]['geox'] = ['Geo-X-Koordinate', 'Wählen Sie die X-Koordinate (Longitude) aus. Mit Verwendung des Geopickers werden beide Felder automatisch befüllt.'];
$GLOBALS['TL_LANG'][$strName]['geoy'] = ['Geo-Y-Koordinate', 'Wählen Sie die Y-Koordinate (Latitude) aus. Mit Verwendung des Geopickers werden beide Felder automatisch befüllt.'];
$GLOBALS['TL_LANG'][$strName]['geoJson'] = ['Geo-JSON', 'Geben Sie das GeoJSON an. Wenn Sie den Editor verwenden, wird das GeoJSON automatisch erzeugt und hier eingetragen.'];

/**
 * References
 */
$GLOBALS['TL_LANG'][$strName]['loctype_ref']['point'] = "Punkt (Koordinaten)";
$GLOBALS['TL_LANG'][$strName]['loctype_ref']['line'] = "Strecke (GeoJson)";
$GLOBALS['TL_LANG'][$strName]['loctype_ref']['circle'] = "Kreis (GeoJson)";
$GLOBALS['TL_LANG'][$strName]['loctype_ref']['polygon'] = "Polygon (GeoJson)";

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
$GLOBALS['TL_LANG'][$strName]['accessibility_legend'] = 'Barrierefreiheit';
$GLOBALS['TL_LANG'][$strName]['linkWizard_legend'] = 'Weitere Links';
$GLOBALS['TL_LANG'][$strName]['osm_legend'] = 'Open Street Map';
$GLOBALS['TL_LANG'][$strName]['publish_legend'] = 'Anzeige';
$GLOBALS['TL_LANG'][$strName]['published_legend'] = 'Veröffentlichungsstatus';

/**
 * Operations
 */
$GLOBALS['TL_LANG'][$strName]['new'] = ["Neues Kartenelement erstellen", "Neues Kartenelement erstellen"];
$GLOBALS['TL_LANG'][$strName]['show'] = ["Kartenelementdetails", "Kartenelement mit ID %s anzeigen"];
$GLOBALS['TL_LANG'][$strName]['edit'] = ["Kartenelement mit ID %s bearbeiten", "Kartenelement mit ID %s bearbeiten"];
$GLOBALS['TL_LANG'][$strName]['copy'] = ["Kartenelement mit ID %s kopieren", "Kartenelement mit ID %s kopieren"];
$GLOBALS['TL_LANG'][$strName]['delete'] = ["Kartenelement mit ID %s löschen", "Kartenelement mit ID %s löschen"];

$GLOBALS['TL_LANG'][$strName]['notice_already_max_published_elements'] = "Diese Gruppe hat bereits die maximale Anzahl veröffentlichter Elemente errreicht.";

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

$GLOBALS['TL_LANG']['con4gis']['data']['frontend']['MoreButtonPublish'] = 'Veröffentlichen';
$GLOBALS['TL_LANG']['con4gis']['data']['frontend']['MoreButtonUnPublish'] = 'Unveröffentlichen';
$GLOBALS['TL_LANG']['con4gis']['data']['frontend']['moreButtonField'] = 'Aktionen';
$GLOBALS['TL_LANG']['con4gis']['data']['frontend']['MoreButtonButtonTitle'] = '';
$GLOBALS['TL_LANG']['con4gis']['data']['frontend']['yes'] = 'Ja';
$GLOBALS['TL_LANG']['con4gis']['data']['frontend']['no'] = 'Nein';

$GLOBALS['TL_LANG']['con4gis']['data']['frontend']['element_published_title'] = 'Erfolg';
$GLOBALS['TL_LANG']['con4gis']['data']['frontend']['element_published_message'] = 'Das Element ist veröffentlicht.';
$GLOBALS['TL_LANG']['con4gis']['data']['frontend']['element_unpublished_title'] = 'Erfolg';
$GLOBALS['TL_LANG']['con4gis']['data']['frontend']['element_unpublished_message'] = 'Das Element ist nicht mehr veröffentlicht.';
$GLOBALS['TL_LANG']['con4gis']['data']['frontend']['element_maximum_title'] = 'Maximale Anzahl Anzeigen';
$GLOBALS['TL_LANG']['con4gis']['data']['frontend']['element_maximum_message'] = 'Die maximale Anzahl Anzeigen ist bereits erreicht.';
$GLOBALS['TL_LANG']['con4gis']['data']['frontend']['element_added_in_frontend'] = 'Im Frontend eingetragen';

$GLOBALS['TL_LANG']['con4gis']['data']['frontend']['filter_by_directory'] = 'Nach Verzeichnis filtern:';
$GLOBALS['TL_LANG']['con4gis']['data']['frontend']['filter_by_category'] = 'Nach Kategorie filtern:';


