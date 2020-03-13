<?php
/**
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package   	con4gis
 * @version    7
 * @author  	    con4gis contributors (see "authors.txt")
 * @license 	    LGPL-3.0-or-later
 * @copyright 	Küstenschmiede GmbH Software & Design
 * @link              https://www.con4gis.org
 */
namespace con4gis\DataBundle\Classes\Contao\Callbacks;

use Contao\Backend;
use Contao\Database;
use Contao\DataContainer;
use Contao\Image;
use Contao\StringUtil;
use Contao\Message;

class CustomFieldCallback extends Backend
{
    private $dcaName = 'tl_c4g_data_custom_field';

    public function addHint()
    {
        $db = Database::getInstance();

        $fieldNames = [
            'Field != \'id\'',
            'Field != \'tstamp\'',
            'Field != \'name\'',
            'Field != \'type\'',
            'Field != \'parentElement\'',
            'Field != \'loctype\'',
            'Field != \'geox\'',
            'Field != \'geoy\'',
            'Field != \'geoJson\'',
            'Field != \'description\'',
            'Field != \'businessHours\'',
            'Field != \'businessHoursAdditionalInfo\'',
            'Field != \'dayFrom\'',
            'Field != \'dayTo\'',
            'Field != \'timeFrom\'',
            'Field != \'timeTo\'',
            'Field != \'addressName\'',
            'Field != \'addressStreet\'',
            'Field != \'addressStreetNumber\'',
            'Field != \'addressZip\'',
            'Field != \'addressCity\'',
            'Field != \'addressState\'',
            'Field != \'addressCountry\'',
            'Field != \'phone\'',
            'Field != \'mobile\'',
            'Field != \'fax\'',
            'Field != \'email\'',
            'Field != \'website\'',
            'Field != \'websiteLabel\'',
            'Field != \'image\'',
            'Field != \'imageMaxHeight\'',
            'Field != \'imageMaxWidth\'',
            'Field != \'imageLink\'',
            'Field != \'imageLightBox\'',
            'Field != \'accessibility\'',
            'Field != \'linkWizard\'',
            'Field != \'linkTitle\'',
            'Field != \'linkHref\'',
            'Field != \'linkNewTab\'',
            'Field != \'osmId\'',
            'Field != \'publishFrom\'',
            'Field != \'publishTo\'',
            'Field != \'importId\'',
            'Field != \'ownerGroupId\'',
            'Field != \'published\'',
            'Field != \'datePublished\'',
        ];

        $stmt = $db->prepare('SHOW COLUMNS FROM tl_c4g_data_element WHERE ' . implode(' AND ', $fieldNames));
        $dbColumns = $stmt->execute()->fetchAllAssoc();

        $stmt = $db->prepare("SELECT * FROM tl_c4g_data_custom_field WHERE type != 'legend' AND type != ''");
        $customFields = $stmt->execute()->fetchAllAssoc();

        $columnNames = [];
        $columnTypes = [];
        $columnDefaults = [];
        foreach ($dbColumns as $dbColumn) {
            $columnNames[] = $dbColumn['Field'];
            $columnTypes[$dbColumn['Field']] = $dbColumn['Type'];
            $columnDefaults[$dbColumn['Field']] = $dbColumn['Default'];
        }

        $customFieldNames = [];
        $customFieldTypes = [];
        $customFieldDefaults = [];
        foreach ($customFields as $customField) {
            $customFieldNames[] = $customField['alias'];
            switch ($customField['type']) {
                case 'text':
                    $customFieldTypes[$customField['alias']] = 'varchar(' . $customField['maxLength'] . ')';
                    $customFieldDefaults[$customField['alias']] = $customField['defaultText'];

                    break;
                case 'textarea':
                    $customFieldTypes[$customField['alias']] = 'text';
                    $customFieldDefaults[$customField['alias']] = $customField['defaultTextArea'];

                    break;
                case 'texteditor':
                    $customFieldTypes[$customField['alias']] = 'text';
                    $customFieldDefaults[$customField['alias']] = $customField['defaultTextEditor'];

                    break;
                case 'natural':
                    $customFieldTypes[$customField['alias']] = 'int(10) unsigned';
                    $customFieldDefaults[$customField['alias']] = $customField['defaultNatural'];

                    break;
                case 'int':
                    $customFieldTypes[$customField['alias']] = 'int(10) signed';
                    $customFieldDefaults[$customField['alias']] = $customField['defaultInt'];

                    break;
                case 'select':
                    $customFieldTypes[$customField['alias']] = 'varchar(255)';
                    $customFieldDefaults[$customField['alias']] = $customField['defaultSelect'];

                    break;
                case 'checkbox':
                case 'link':
                case 'icon':
                    $customFieldTypes[$customField['alias']] = 'char(1)';
                    $customFieldDefaults[$customField['alias']] = $customField['defaultCheckbox'];

                    break;
                case 'multicheckbox':
                case 'filtermulticheckbox':
                    $customFieldTypes[$customField['alias']] = 'text';
                    $customFieldDefaults[$customField['alias']] = null;

                    break;
                case 'datepicker':
                    $customFieldTypes[$customField['alias']] = 'varchar(10)';
                    $customFieldDefaults[$customField['alias']] = $customField['defaultDatePicker'];

                    break;
                case 'foreignKey':
                    $customFieldTypes[$customField['alias']] = 'int(10)';
                    $customFieldDefaults[$customField['alias']] = '0';

                    break;
                default:
                    break;
            }
        }

        foreach ($columnNames as $columnName) {
            if (!in_array($columnName, $customFieldNames)) {
                Message::addInfo($GLOBALS['TL_LANG'][$this->dcaName]['install_tool_hint']);

                return;
            }
        }

        foreach ($customFieldNames as $customFieldName) {
            if (!in_array($customFieldName, $columnNames)) {
                Message::addInfo($GLOBALS['TL_LANG'][$this->dcaName]['install_tool_hint']);

                return;
            } elseif ($customFieldTypes[$customFieldName] !== $columnTypes[$customFieldName]) {
                Message::addInfo($GLOBALS['TL_LANG'][$this->dcaName]['install_tool_hint']);

                return;
            } elseif ($customFieldDefaults[$customFieldName] !== $columnDefaults[$customFieldName]) {
                Message::addInfo($GLOBALS['TL_LANG'][$this->dcaName]['install_tool_hint']);

                return;
            }
        }
    }

    public function getLabels($row)
    {
        $labels['name'] = $row['name'];
        $labels['type'] = $GLOBALS['TL_LANG']['data_custom_field_types'][$row['type']];

        return $labels;
    }

    public function loadTypes($dca)
    {
        return $GLOBALS['TL_LANG']['data_custom_field_types'];
    }

    public function loadClassOptions($dca)
    {
        return $GLOBALS['TL_LANG']['data_custom_field_class_options'];
    }

    public function loadDefaultOptions($dca)
    {
        $options = StringUtil::deserialize($dca->activeRecord->options);
        $formattedOptions = [];
        foreach ($options as $option) {
            $formattedOptions[$option['key']] = $option['value'];
        }

        return $formattedOptions;
    }

    public function loadFrontendFilterCheckboxStylingOptions($dca)
    {
        return $GLOBALS['TL_LANG']['data_custom_field_frontend_filter_checkbox_styling_options'];
    }

    public function saveAlias($value, DataContainer $dca)
    {
        if (strval($value) !== '') {
            return str_replace([' ', 'ä', 'ö', 'ü', 'Ä', 'Ö', 'Ü'], ['_', 'ae', 'oe', 'ue', 'ae', 'oe', 'ue'], strtolower($value));
        }

        return str_replace([' ', 'ä', 'ö', 'ü', 'Ä', 'Ö', 'Ü'], ['_', 'ae', 'oe', 'ue', 'ae', 'oe', 'ue'], strtolower($dca->activeRecord->name));
    }

    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        $this->import('BackendUser', 'User');

        if (strlen($this->Input->get('tid'))) {
            $this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') != 1));
            $this->redirect($this->getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->User->isAdmin && !$this->User->hasAccess($this->dcaName . '::published', 'alexf')) {
            return '';
        }

        $href .= '&amp;id=' . $this->Input->get('id') . '&amp;tid=' . $row['id'] . '&amp;state=' . $row[''];

        if (!$row['published']) {
            $icon = 'invisible.svg';
        }

        return '<a href="' . $this->addToUrl($href) . '" title="' . specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ';
    }
    public function toggleVisibility($id, $published)
    {
        // Check permissions to publish
        if (!$this->User->isAdmin && !$this->User->hasAccess($this->dcaName . '::published', 'alexf')) {
            $this->redirect('contao/main.php?act=error');
        }

        // Update the database
        $this->Database->prepare('UPDATE ' . $this->dcaName . ' SET tstamp=' . time() . ", published='" . ($published ? '0' : '1') . "' WHERE id=?")
            ->execute($id);
    }
}
