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

$GLOBALS['TL_LANG']['tl_c4g_data_type'] = [
    'name' => ['Name of category', 'The name of the category in the frontend.'],
    'locstyle' => ['Location style', 'The location style determines the icon used to display the elements in this category.'],
    'availableFields' => ['Available fields', 'These fields are available to elements of this category..'],
    'categorySort' => ['Sort order', 'Determines the sort order in the filter.'],
    'showLabels' => ['Show titles', 'If checked, element titles are shown in the map.'],
    'searchEngine_legend' => 'Search engine options (optional)',
    'itemType' => ['Schema', 'The schema by which search engines will interpret the element.'],
    'new' => ["Create new category", "Create new category"],
    'show' => ["Category details", "Show category with ID %s"],
    'edit' => ["Edit category with ID %s", "Edit category with ID %s"],
    'copy' => ["Copy category with ID %s", "Copy category with ID %s"],
    'delete' => ["Delete category with ID %s", "Delete category with ID %s"],
];

$GLOBALS['TL_LANG']['tl_c4g_data_type']['data_legend'] = 'General data';

$GLOBALS['TL_LANG']['tl_c4g_data_type']['legend'] = 'Headline: ';

$GLOBALS['TL_LANG']['tl_c4g_data_type']['itemTypeOptions'] = [
    'https://schema.org/LocalBusiness' => 'Lokales Geschäft oder Praxis',
    'https://schema.org/Restaurant' => 'Restaurant',
    'https://schema.org/TouristAttraction' => 'sight or tourist attraction',
    'https://schema.org/Place' => 'parking lots, camping sites, bus stops',
    'https://schema.org/VoteAction' => 'Polling booths ',
    'https://schema.org/AssessAction' => 'AssessAction',
    'https://schema.org/NewsArticle' => 'news articles, postings',
    'https://schema.org/SocialMediaPosting' => 'Social media contributions',
    'https://schema.org/Clip' => 'Short films ',
    'https://schema.org/HowTo' => 'Do it Your self guides',
    'https://schema.org/Map' => 'Maps',
    'https://schema.org/Movie' => 'Movies',
    'https://schema.org/SoftwareApplication' => 'Apps, Games and Web Applications',
    'https://schema.org/WebPage' => 'Homepage',
    'https://schema.org/WebSite' => 'Websites',
    'https://schema.org/Event' => 'Events',
    'https://schema.org/School' => 'Schools',
    'https://schema.org/AutomotiveBusiness' => 'automotive parts, repairs or services',
    'https://schema.org/EmergencyService' => 'Police, hospital or fire department',
    'https://schema.org/EntertaimentBusiness' => 'Art gallery, discos, nightclubs or casinos',
    'https://schema.org/BarOrPub' => 'Bars or Pubs',
    'https://schema.org/Libary' => 'Libary',
    'https://schema.org/MedicalBusiness' => 'pharmacies, medical supply stores, physiotherapy or doctors',
    'https://schema.org/SportActivityLocation' => 'Gym, bowling alley or swimming pools',
    'https://schema.org/Store' => 'Business',
    'https://schema.org/Person' => 'persons, shopkeepers or persons of public life',
    'https://schema.org/Accommodation' => 'Hotels, holiday apartments or camping site'

];
