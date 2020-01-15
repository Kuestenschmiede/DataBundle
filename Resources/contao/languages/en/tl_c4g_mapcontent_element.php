<?php

$strName = 'tl_c4g_mapcontent_element';

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strName]['name'] = ['Title', 'Enter a name for this element.'];
$GLOBALS['TL_LANG'][$strName]['description'] = ['Description', 'Enter a description for this element.'];
$GLOBALS['TL_LANG'][$strName]['type'] = ['Category', 'The category is displayed in the frontend and determines which fields are available.'];
$GLOBALS['TL_LANG'][$strName]['location'] = ['Location of the element', 'Select the location where the element is located.'];
$GLOBALS['TL_LANG'][$strName]['parentElement'] = ['Parent element', 'If the parent element has data that is missing here, that data is inherited from the parent.'];
$GLOBALS['TL_LANG'][$strName]['linkWizard'] = ['Additional Links', 'Additional links to be generated. The title field accepts HTML.'];
$GLOBALS['TL_LANG'][$strName]['linkTitle'] = ['Title', 'The visible text. Accepts HTML.'];
$GLOBALS['TL_LANG'][$strName]['linkHref'] = ['Address / URL', 'The address or URL the link points to.'];
$GLOBALS['TL_LANG'][$strName]['linkNewTab'] = ['New tab', 'If set, the link is opened in a new tab.'];

$GLOBALS['TL_LANG'][$strName]['businessHours'] = ['Opening hours', 'The opening hours in list form.'];
$GLOBALS['TL_LANG'][$strName]['businessHoursAdditionalInfo'] = ['Additional info', 'Additional info for the opening hours.'];
$GLOBALS['TL_LANG'][$strName]['dayFrom'] = ['Day from', ''];
$GLOBALS['TL_LANG'][$strName]['dayTo'] = ['Day to', ''];
$GLOBALS['TL_LANG'][$strName]['timeFrom'] = ['Time from', ''];
$GLOBALS['TL_LANG'][$strName]['timeTo'] = ['Time to', ''];
$GLOBALS['TL_LANG'][$strName]['timeCaption'] = '';

$GLOBALS['TL_LANG'][$strName]['contactData'] = ['Contact data', 'Contact data'];
$GLOBALS['TL_LANG'][$strName]['addressName'] = ['Name', 'Name as part of the address.'];
$GLOBALS['TL_LANG'][$strName]['addressStreet'] = ['Street', 'Street without house number.'];
$GLOBALS['TL_LANG'][$strName]['addressStreetNumber'] = ['House number', ''];
$GLOBALS['TL_LANG'][$strName]['addressZip'] = ['Zip code', ''];
$GLOBALS['TL_LANG'][$strName]['addressCity'] = ['City', ''];

$GLOBALS['TL_LANG'][$strName]['accessibility'] = ['Accessible', 'The element is accessible.'];
$GLOBALS['TL_LANG'][$strName]['osmId'] = ['OSM ID', 'The element\'s id in the Open Street Map.'];
$GLOBALS['TL_LANG'][$strName]['publishFrom'] = ['Publish from', 'The element is displayed in the frontend from this day on.'];
$GLOBALS['TL_LANG'][$strName]['publishTo'] = ['Publish until', 'The element is displayed in the frontend until this day.'];

$GLOBALS['TL_LANG'][$strName]['phone'] = ['Phone', ''];
$GLOBALS['TL_LANG'][$strName]['mobile'] = ['Mobile phone', ''];
$GLOBALS['TL_LANG'][$strName]['fax'] = ['Fax', ''];
$GLOBALS['TL_LANG'][$strName]['email'] = ['Email-Address', ''];
$GLOBALS['TL_LANG'][$strName]['website'] = ['Website', ''];
$GLOBALS['TL_LANG'][$strName]['image'] = ['Image', 'An image showing the element.'];
$GLOBALS['TL_LANG'][$strName]['imageMaxHeight'] = ['Maximum image height (pixels)', 'The aspect ratio is retained.'];
$GLOBALS['TL_LANG'][$strName]['imageMaxWidth'] = ['Maximum image width (pixels)', 'The aspect ratio is retained.'];
$GLOBALS['TL_LANG'][$strName]['imageLink'] = ['Image link', 'Link the image points to.'];
$GLOBALS['TL_LANG'][$strName]['loctype'] = ['Location type', 'Select the type of location.'];
$GLOBALS['TL_LANG'][$strName]['geox'] = ['Geo-X-Coordinate', 'X-coordinate (Longitude). Use the geopicker to fill both coordinates.'];
$GLOBALS['TL_LANG'][$strName]['geoy'] = ['Geo-Y-Coordinate', 'Y-coordinate (Latitude). Use the geopicker to fill both coordinates.'];
$GLOBALS['TL_LANG'][$strName]['geoJson'] = ['Geo-JSON', 'Enter the GeoJSON. When using the editor, the GeoJSON is created automatically.'];

/**
 * References
 */
$GLOBALS['TL_LANG'][$strName]['loctype_ref']['point'] = "Point";
$GLOBALS['TL_LANG'][$strName]['loctype_ref']['line'] = "Line";
$GLOBALS['TL_LANG'][$strName]['loctype_ref']['circle'] = "Circle";
$GLOBALS['TL_LANG'][$strName]['loctype_ref']['polygon'] = "Polygon";

/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strName]['data_legend'] = 'General data';
$GLOBALS['TL_LANG'][$strName]['businessHours_legend'] = 'Open Hours';
$GLOBALS['TL_LANG'][$strName]['address_legend'] = 'Address';
$GLOBALS['TL_LANG'][$strName]['contact_legend'] = 'Contact';
$GLOBALS['TL_LANG'][$strName]['description_legend'] = 'Description';
$GLOBALS['TL_LANG'][$strName]['image_legend'] = 'Image';
$GLOBALS['TL_LANG'][$strName]['location_legend'] = 'Coordinates';
$GLOBALS['TL_LANG'][$strName]['filter_legend'] = 'Filter data';
$GLOBALS['TL_LANG'][$strName]['accessibility_legend'] = 'Accessibility';
$GLOBALS['TL_LANG'][$strName]['linkWizard_legend'] = 'Additional Links';
$GLOBALS['TL_LANG'][$strName]['osm_legend'] = 'Open Street Map';
$GLOBALS['TL_LANG'][$strName]['publish_legend'] = 'Display';

/**
 * Operations
 */
$GLOBALS['TL_LANG'][$strName]['new'] = ["Create new map element", "Create new map element"];
$GLOBALS['TL_LANG'][$strName]['show'] = ["Show map element with ID %s", "Show map element with ID %s"];
$GLOBALS['TL_LANG'][$strName]['edit'] = ["Edit map element with ID %s", "Edit map element with ID %s"];
$GLOBALS['TL_LANG'][$strName]['copy'] = ["Copy map element with ID %s", "Copy map element with ID %s"];
$GLOBALS['TL_LANG'][$strName]['delete'] = ["Delete map element with ID %s", "Delete map element with ID %s"];

$GLOBALS['TL_LANG'][$strName]['day_reference'] = [
    '0' => 'Monday',
    '1' => 'Tuesday',
    '2' => 'Wednesday',
    '3' => 'Thursday',
    '4' => 'Friday',
    '5' => 'Saturday',
    '6' => 'Sunday'
];

$GLOBALS['TL_LANG'][$strName]['day_join'] = [
    'to' => 'to',
    'and' => 'and'
];

/** Frontend */
$GLOBALS['TL_LANG'][$strName]['address'] = ['Address', ''];