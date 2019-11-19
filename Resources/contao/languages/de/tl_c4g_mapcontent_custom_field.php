<?php

$strName = 'tl_c4g_mapcontent_custom_field';

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strName]['name'] = ['Bezeichnung', 'Die Bezeichnung des Feldes im Formular.'];
$GLOBALS['TL_LANG'][$strName]['description'] = ['Beschreibung', 'Die Beschreibung direkt unterhalb des Eingabefeldes (dieser Text).'];
$GLOBALS['TL_LANG'][$strName]['type'] = ['Typ', 'Der Typ bestimmt die Art der Eingabe. Je nach Typ stehen weitere Optionen zur Verfügung.'];
$GLOBALS['TL_LANG'][$strName]['legend'] = ['Legende', 'Die Legende ist die Überschrift, unter der das Feld im Formular erscheint.'];
$GLOBALS['TL_LANG'][$strName]['filter'] = ['Filterbar', 'Falls gesetzt, ist das Feld in den Filtereinstellungen oberhalb der Tabelle verfügbar.'];
$GLOBALS['TL_LANG'][$strName]['search'] = ['Suchbar', 'Falls gesetzt, ist das Feld in den Sucheinstellungen oberhalb der Tabelle verfügbar.'];
$GLOBALS['TL_LANG'][$strName]['mandatory'] = ['Pflichtfeld', 'Falls gesetzt, ist das Feld ein Pflichtfeld.'];
$GLOBALS['TL_LANG'][$strName]['maxLength'] = ['Maximale Länge', 'Die maximale Anzahl Stellen.'];
$GLOBALS['TL_LANG'][$strName]['default'] = ['Vorgabewert', 'Der Vorgabewert des Feldes.'];
$GLOBALS['TL_LANG'][$strName]['rte'] = ['Rich Text Editor', 'Falls gesetzt, bietet das Feld erweiterte Funktionen, z.B. Fett- und Kursivschrift.'];
$GLOBALS['TL_LANG'][$strName]['class'] = ['Class', 'Beschreibung fehlt.'];
$GLOBALS['TL_LANG'][$strName]['margin'] = ['Oberer Rand', 'Falls gesetzt, wird über dem Feld ein Rand von 12 Pixeln eingefügt.'];

/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strName]['data_legend'] = 'Allgemeine Daten';
$GLOBALS['TL_LANG'][$strName]['filter_search_legend'] = 'Filtern und Suchen';
$GLOBALS['TL_LANG'][$strName]['mandatory_default_legend'] = 'Pflichtfeld und Vorgabewert';
$GLOBALS['TL_LANG'][$strName]['positioning_legend'] = 'Positionierung';
$GLOBALS['TL_LANG'][$strName]['type_specific_legend'] = 'Typspezifische Optionen';

/**
 * Operations
 */
$GLOBALS['TL_LANG'][$strName]['new'] = ["Neues Feld definieren", "Neues Feld definieren"];

/**
 * Custom Field Types
 */

$GLOBALS['TL_LANG']['mapcontent_custom_field_types']['text'] = 'Einzeiliger Text';
$GLOBALS['TL_LANG']['mapcontent_custom_field_types']['textarea'] = 'Mehrzeiliger Text';
$GLOBALS['TL_LANG']['mapcontent_custom_field_types']['natural'] = 'Positive Ganzzahl';
$GLOBALS['TL_LANG']['mapcontent_custom_field_types']['int'] = 'Positive oder Negative Ganzzahl';
$GLOBALS['TL_LANG']['mapcontent_custom_field_types']['decimal'] = 'Positive oder Negative Zahl mit Nachkommastellen';
$GLOBALS['TL_LANG']['mapcontent_custom_field_types']['select'] = 'Auswahl';
$GLOBALS['TL_LANG']['mapcontent_custom_field_types']['checkbox'] = 'Checkbox';
$GLOBALS['TL_LANG']['mapcontent_custom_field_types']['multicheckbox'] = 'Mehrere Checkboxen';
$GLOBALS['TL_LANG']['mapcontent_custom_field_types']['datepicker'] = 'Datumsauswahl';

/**
 * Class Options
 */

$GLOBALS['TL_LANG']['mapcontent_custom_field_class_options']['w50'] = 'w50';
$GLOBALS['TL_LANG']['mapcontent_custom_field_class_options']['w50 clr'] = 'w50 clr';
$GLOBALS['TL_LANG']['mapcontent_custom_field_class_options']['clr'] = 'clr';
