<?php

$strName = 'tl_c4g_mapcontent_custom_field';

$GLOBALS['TL_LANG'][$strName]['install_tool_hint'] = 'After a field has been defined or changed, it is necessary to clear the cache and to update the database.';

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strName]['name'] = ['Title', 'The field title in the form.'];
$GLOBALS['TL_LANG'][$strName]['alias'] = ['Database alias', 'The field title in the database. Is generated from the title.'];
$GLOBALS['TL_LANG'][$strName]['description'] = ['Description', 'The description directly below the field (i.e. this text).'];
$GLOBALS['TL_LANG'][$strName]['type'] = ['Type', 'The input type. Additional fields are available based on which type is chosen.'];
$GLOBALS['TL_LANG'][$strName]['legend'] = ['Legend', 'The legend is the headline under which the field is shown in the form.'];
$GLOBALS['TL_LANG'][$strName]['filter'] = ['Filterable', 'If checked, the field is available in the filter options above the list.'];
$GLOBALS['TL_LANG'][$strName]['search'] = ['Searchable', 'If checked, the field is available in the search options above the list.'];
$GLOBALS['TL_LANG'][$strName]['mandatory'] = ['Mandatory', 'If checked, the field is mandatory.'];
$GLOBALS['TL_LANG'][$strName]['maxLength'] = ['Maximum length', 'The maximum amount of characters allowed in the field.'];
$GLOBALS['TL_LANG'][$strName]['default'] = ['Default value', 'The default value.'];
$GLOBALS['TL_LANG'][$strName]['class'] = ['Column', 'The column position in the backend.'];
$GLOBALS['TL_LANG'][$strName]['margin'] = ['Upper margin', 'If checked, a margin of 12 pixels is added above the field.'];
$GLOBALS['TL_LANG'][$strName]['options'] = ['Options', 'The available options. The key is stored in the database, the translation is displayed to the user.'];
$GLOBALS['TL_LANG'][$strName]['key'] = ['Key', 'The value stored in the database. It should not be changed once set.'];
$GLOBALS['TL_LANG'][$strName]['value'] = ['Translation', 'This value is shown to the user. It can safely be changed at any time.'];
$GLOBALS['TL_LANG'][$strName]['frontendName'] = ['Frontend title', 'Can be defined as title in the frontend. If none is set, the normal title is used.'];
$GLOBALS['TL_LANG'][$strName]['frontendPopup'] = ['Display in popup', 'If checked, the field is displayed in the popup, if it contains a value.'];
$GLOBALS['TL_LANG'][$strName]['frontendList'] = ['Display in list', 'If checked, the field is displayed in the list, if it contains a value.'];
$GLOBALS['TL_LANG'][$strName]['frontendDetails'] = ['Display in list details', 'If checked, the field is displayed in the list details, if it contains a value.'];
$GLOBALS['TL_LANG'][$strName]['frontendFilter'] = ['Filterable in Frontend', 'If checked, the field is filterable in the frontend.'];
$GLOBALS['TL_LANG'][$strName]['linkTitle'] = ['Link title', 'The text displayed to the user.'];
$GLOBALS['TL_LANG'][$strName]['linkHref'] = ['URL', 'The URL the link points to.'];
$GLOBALS['TL_LANG'][$strName]['linkNewTab'] = ['Open in new tab', 'If checked, the link is opened in a new tab.'];
$GLOBALS['TL_LANG'][$strName]['icon'] = ['Font Awesome Icon', 'The icon to be shown.'];

/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strName]['data_legend'] = 'General data';
$GLOBALS['TL_LANG'][$strName]['filter_search_legend'] = 'Filter and Search';
$GLOBALS['TL_LANG'][$strName]['mandatory_legend'] = 'Mandatory Field';
$GLOBALS['TL_LANG'][$strName]['positioning_legend'] = 'Positioning';
$GLOBALS['TL_LANG'][$strName]['frontend_legend'] = 'Frontend Options';
$GLOBALS['TL_LANG'][$strName]['type_specific_legend'] = 'Type-specifc Options';

/**
 * Operations
 */
$GLOBALS['TL_LANG'][$strName]['new'] = ["Define new field", "Define new field"];
$GLOBALS['TL_LANG'][$strName]['show'] = ["Field details", "Show field with ID %s"];
$GLOBALS['TL_LANG'][$strName]['edit'] = ["Edit field with ID %s", "Edit field with ID %s"];
$GLOBALS['TL_LANG'][$strName]['copy'] = ["Copy field with ID %s", "Copy field with ID %s"];
$GLOBALS['TL_LANG'][$strName]['delete'] = ["Delete field with ID %s", "Delete field with ID %s"];

/**
 * Custom Field Types
 */

$GLOBALS['TL_LANG']['mapcontent_custom_field_types']['text'] = 'Single line of text';
$GLOBALS['TL_LANG']['mapcontent_custom_field_types']['textarea'] = 'Multiple lines of text';
$GLOBALS['TL_LANG']['mapcontent_custom_field_types']['texteditor'] = 'Multiple lines of text with editor';
$GLOBALS['TL_LANG']['mapcontent_custom_field_types']['natural'] = 'Natural Number';
$GLOBALS['TL_LANG']['mapcontent_custom_field_types']['int'] = 'Whole Number';
$GLOBALS['TL_LANG']['mapcontent_custom_field_types']['select'] = 'Select list';
$GLOBALS['TL_LANG']['mapcontent_custom_field_types']['checkbox'] = 'Checkbox';
$GLOBALS['TL_LANG']['mapcontent_custom_field_types']['icon'] = 'Icon';
$GLOBALS['TL_LANG']['mapcontent_custom_field_types']['multicheckbox'] = 'Multiple Checkboxes';
$GLOBALS['TL_LANG']['mapcontent_custom_field_types']['datepicker'] = 'Datepicker';
$GLOBALS['TL_LANG']['mapcontent_custom_field_types']['link'] = 'Link';
$GLOBALS['TL_LANG']['mapcontent_custom_field_types']['legend'] = 'Headline';

/**
 * Class Options
 */

$GLOBALS['TL_LANG']['mapcontent_custom_field_class_options']['w50'] = 'Single column';
$GLOBALS['TL_LANG']['mapcontent_custom_field_class_options']['w50 clr'] = 'Single column, left side';
$GLOBALS['TL_LANG']['mapcontent_custom_field_class_options']['clr'] = 'Multicolumn';
