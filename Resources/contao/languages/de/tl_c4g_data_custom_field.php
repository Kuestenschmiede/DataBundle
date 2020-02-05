<?php

$strName = 'tl_c4g_data_custom_field';

$GLOBALS['TL_LANG'][$strName]['install_tool_hint'] = 'Nach der Definition eines neuen Feldes sowie nach Änderungen ist es erforderlich, den Cache zu leeren und die Datenbank zu aktualisieren.';

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strName]['name'] = ['Bezeichnung', 'Die Bezeichnung des Feldes im Formular.'];
$GLOBALS['TL_LANG'][$strName]['alias'] = ['Datenbank-Alias', 'Die Feldbezeichnung in der Datenbank. Wird aus der Bezeichnung generiert.'];
$GLOBALS['TL_LANG'][$strName]['description'] = ['Beschreibung', 'Die Beschreibung direkt unterhalb des Eingabefeldes (dieser Text).'];
$GLOBALS['TL_LANG'][$strName]['type'] = ['Typ', 'Der Typ bestimmt die Art der Eingabe. Je nach Typ stehen weitere Optionen zur Verfügung.'];
$GLOBALS['TL_LANG'][$strName]['legend'] = ['Legende', 'Die Legende ist die Überschrift, unter der das Feld im Formular erscheint.'];
$GLOBALS['TL_LANG'][$strName]['filter'] = ['Filterbar', 'Falls gesetzt, ist das Feld in den Filtereinstellungen oberhalb der Tabelle verfügbar.'];
$GLOBALS['TL_LANG'][$strName]['search'] = ['Suchbar', 'Falls gesetzt, ist das Feld in den Sucheinstellungen oberhalb der Tabelle verfügbar.'];
$GLOBALS['TL_LANG'][$strName]['mandatory'] = ['Pflichtfeld', 'Falls gesetzt, ist das Feld ein Pflichtfeld.'];
$GLOBALS['TL_LANG'][$strName]['maxLength'] = ['Maximale Länge', 'Die maximale Anzahl Stellen.'];
$GLOBALS['TL_LANG'][$strName]['default'] = ['Vorgabewert', 'Der Vorgabewert des Feldes.'];
$GLOBALS['TL_LANG'][$strName]['class'] = ['Spalte', 'Die Spaltenpositionierung im Backend.'];
$GLOBALS['TL_LANG'][$strName]['margin'] = ['Oberer Rand', 'Falls gesetzt, wird über dem Feld ein Rand von 12 Pixeln eingefügt.'];
$GLOBALS['TL_LANG'][$strName]['options'] = ['Optionen', 'Die Optionen, die zur Verfügung stehen. Der Schlüssel wird in der Datenbank gespeichert, die Übersetzung dem Nutzer ausgegeben.'];
$GLOBALS['TL_LANG'][$strName]['key'] = ['Schlüssel', 'Der in der Datenbank gespeicherte Wert. Der Schlüssel sollte nachträglich nicht mehr geändert werden.'];
$GLOBALS['TL_LANG'][$strName]['value'] = ['Übersetzung', 'Dieser Wert wird dem Nutzer angezeigt. Er kann nachträglich jederzeit geändert werden.'];
$GLOBALS['TL_LANG'][$strName]['frontendName'] = ['Frontendbezeichnung', 'Kann als Bezeichnung im Frontend definiert werden. Wenn kein Wert eingetragen ist, wird die normale Bezeichnung verwendet.'];
$GLOBALS['TL_LANG'][$strName]['frontendPopup'] = ['Im Popup zeigen', 'Falls gesetzt, wird das Feld im Popup dargestellt, wenn ein Wert vorhanden ist.'];
$GLOBALS['TL_LANG'][$strName]['frontendList'] = ['In der Liste zeigen', 'Falls gesetzt, wird das Feld in der Liste dargestellt, wenn ein Wert vorhanden ist.'];
$GLOBALS['TL_LANG'][$strName]['frontendDetails'] = ['In den Listendetails zeigen', 'Falls gesetzt, wird das Feld in den Listendetails dargestellt, wenn ein Wert vorhanden ist.'];
$GLOBALS['TL_LANG'][$strName]['frontendFilter'] = ['In der Karte (Frontend) filterbar', 'Falls gesetzt, ist das Feld im Filter über der Karte verfügbar.'];
$GLOBALS['TL_LANG'][$strName]['frontendFilterList'] = ['In der Liste (Frontend) filterbar', 'Falls gesetzt, ist das Feld im Filter über der Liste verfügbar.'];
$GLOBALS['TL_LANG'][$strName]['frontendFilterCheckboxStyling'] = ['Gestaltung des Frontendfilters', 'Bestimmt, wie der Filter oberhalb der Liste dargestellt wird.'];
$GLOBALS['TL_LANG'][$strName]['frontendFilterCheckboxButtonLabelOn'] = ['Button Text für aktiven Filter', 'Der Button Text, wenn der Filter aktiv ist. Erlaubt HTML.'];
$GLOBALS['TL_LANG'][$strName]['frontendFilterCheckboxButtonLabelOff'] = ['Button Text für inaktiven Filter', 'Der Button Text, wenn der Filter nicht aktiv ist. Erlaubt HTML.'];
$GLOBALS['TL_LANG'][$strName]['linkTitle'] = ['Linkbezeichnung', 'Der Text, der dem Nutzer ausgegeben wird.'];
$GLOBALS['TL_LANG'][$strName]['linkHref'] = ['URL', 'Die URL, auf die der Link zeigt.'];
$GLOBALS['TL_LANG'][$strName]['linkNewTab'] = ['In neuem Tab öffnen', 'Falls gesetzt, wird der Link in einem neuen Tab geöffnet.'];
$GLOBALS['TL_LANG'][$strName]['icon'] = ['Font Awesome Icon', 'Das anzuzeigende Icon.'];
$GLOBALS['TL_LANG'][$strName]['foreignTable'] = ['Tabelle', 'Die Tabelle, auf die der Fremdschlüssel zeigt.'];
$GLOBALS['TL_LANG'][$strName]['foreignField'] = ['Label', 'Das Feld, anhand dessen die Zeile identifiziert wird.'];

/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strName]['data_legend'] = 'Allgemeine Daten';
$GLOBALS['TL_LANG'][$strName]['filter_search_legend'] = 'Filtern und Suchen';
$GLOBALS['TL_LANG'][$strName]['mandatory_legend'] = 'Pflichtfeld';
$GLOBALS['TL_LANG'][$strName]['positioning_legend'] = 'Positionierung';
$GLOBALS['TL_LANG'][$strName]['frontend_legend'] = 'Frontendoptionen';
$GLOBALS['TL_LANG'][$strName]['type_specific_legend'] = 'Typspezifische Optionen';

/**
 * Operations
 */
$GLOBALS['TL_LANG'][$strName]['new'] = ["Neues Feld definieren", "Neues Feld definieren"];
$GLOBALS['TL_LANG'][$strName]['show'] = ["Felddetails", "Feld mit ID %s anzeigen"];
$GLOBALS['TL_LANG'][$strName]['edit'] = ["Feld mit ID %s bearbeiten", "Feld mit ID %s bearbeiten"];
$GLOBALS['TL_LANG'][$strName]['copy'] = ["Feld mit ID %s kopieren", "Feld mit ID %s kopieren"];
$GLOBALS['TL_LANG'][$strName]['delete'] = ["Feld mit ID %s löschen", "Feld mit ID %s löschen"];

/**
 * Custom Field Types
 */

$GLOBALS['TL_LANG']['data_custom_field_types']['text'] = 'Einzeiliger Text';
$GLOBALS['TL_LANG']['data_custom_field_types']['textarea'] = 'Mehrzeiliger Text';
$GLOBALS['TL_LANG']['data_custom_field_types']['texteditor'] = 'Mehrzeiliger Text mit Editor';
$GLOBALS['TL_LANG']['data_custom_field_types']['natural'] = 'Natürliche Zahl';
$GLOBALS['TL_LANG']['data_custom_field_types']['int'] = 'Ganze Zahl';
$GLOBALS['TL_LANG']['data_custom_field_types']['select'] = 'Auswahlliste';
$GLOBALS['TL_LANG']['data_custom_field_types']['checkbox'] = 'Checkbox';
$GLOBALS['TL_LANG']['data_custom_field_types']['icon'] = 'Icon';
$GLOBALS['TL_LANG']['data_custom_field_types']['multicheckbox'] = 'Mehrere Checkboxen';
$GLOBALS['TL_LANG']['data_custom_field_types']['datepicker'] = 'Datumsauswahl';
$GLOBALS['TL_LANG']['data_custom_field_types']['link'] = 'Verlinkung';
$GLOBALS['TL_LANG']['data_custom_field_types']['legend'] = 'Überschrift';
$GLOBALS['TL_LANG']['data_custom_field_types']['foreignKey'] = 'Fremdschlüssel';

/**
 * Class Options
 */

$GLOBALS['TL_LANG']['data_custom_field_class_options']['w50'] = 'Einspaltig';
$GLOBALS['TL_LANG']['data_custom_field_class_options']['w50 clr'] = 'Einspaltig, Links';
$GLOBALS['TL_LANG']['data_custom_field_class_options']['clr'] = 'Zweispaltig';

/**
 *
 */

$GLOBALS['TL_LANG']['data_custom_field_frontend_filter_checkbox_styling_options']['checkbox'] = 'als Checkbox';
$GLOBALS['TL_LANG']['data_custom_field_frontend_filter_checkbox_styling_options']['button'] = 'als Button';