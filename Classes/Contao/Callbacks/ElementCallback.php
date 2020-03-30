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

use con4gis\DataBundle\Resources\contao\models\DataElementModel;
use con4gis\DataBundle\Resources\contao\models\DataTypeModel;
use con4gis\GroupsBundle\Resources\contao\models\MemberGroupModel;
use con4gis\MapsBundle\Classes\Utils;
use Contao\Backend;
use Contao\Database;
use Contao\DataContainer;
use Contao\Image;

class ElementCallback extends Backend
{
    private $dcaName = 'tl_c4g_data_element';

    public function loadTypes()
    {
        $arrTypes = [];
        $arrTypes[''] = '-';
        $database = Database::getInstance();
        $stmt = $database->prepare("SELECT id, name FROM tl_c4g_data_type WHERE name != '' ORDER BY name");
        $types = $stmt->execute()->fetchAllAssoc();
        foreach ($types as $type) {
            $arrTypes[$type['id']] = $type['name'];
        }

        return $arrTypes;
    }

    public function loadParentOptions(DataContainer $dc)
    {
        $options = [];
        $id = $dc->activeRecord->id;
        if (!$id) {
            return [];
        }
        $models = DataElementModel::findAll();
        foreach ($models as $model) {
            if ($model->id !== $id) {
                $options[$model->id] = $model->name;
            }
        }

        return $options;
    }

    public function getLabel($arrRow)
    {
        $label['name'] = $arrRow['name'];
        $label['type'] = DataTypeModel::findByPk($arrRow['type'])->name;

        return $label;
    }

    public function getDay($dc)
    {
        return $GLOBALS['con4gis']['data']['day_option'];
    }

    public function changeFileBinToUuid($fieldValue, DataContainer $dc)
    {
        return \StringUtil::binToUuid($fieldValue);
    }

    public function loadMemberGroupData($value, $dc)
    {
        $id = $dc->activeRecord->id;
        $memberGroupModel = MemberGroupModel::findByPk($value);
        if ($memberGroupModel !== null) {
            $database = Database::getInstance();
            $stmt = $database->prepare('UPDATE tl_c4g_data_element SET phone = ?, mobile = ?, email = ? WHERE id = ? AND ownerGroupId != ?');
            $stmt->execute(strval($memberGroupModel->phone), strval($memberGroupModel->mobile), strval($memberGroupModel->email), $id, $value);
        } else {
            $database = Database::getInstance();
            $stmt = $database->prepare("UPDATE tl_c4g_data_element SET phone = '', mobile = '', email = '' WHERE id = ?");
            $stmt->execute($id);
        }

        return $value;
    }

    /**
     * Validate Location Lon
     */
    public function setLocLon($varValue, DataContainer $dc)
    {
        if (!Utils::validateLon($varValue)) {
            throw new \Exception($GLOBALS['TL_LANG']['c4g_maps']['geox_invalid']);
        }

        return $varValue;
    }

    /**
     * Validate Location Lat
     */
    public function setLocLat($varValue, DataContainer $dc)
    {
        if (!Utils::validateLat($varValue)) {
            throw new \Exception($GLOBALS['TL_LANG']['c4g_maps']['geoy_invalid']);
        }

        return $varValue;
    }

    public function validatePublishing($value, DataContainer $dc)
    {
        if ($value) {
            if (intval($dc->activeRecord->ownerGroupId) > 0) {
                $database = Database::getInstance();
                $select = 'SELECT count(*) as current FROM tl_c4g_data_element where ownerGroupId = ? AND published = 1 AND type = ? AND id != ?';
                $stmt = $database->prepare($select);
                $current = $stmt->execute($dc->activeRecord->ownerGroupId, $dc->activeRecord->type, $dc->activeRecord->id)->fetchAllAssoc()[0]['current'];
                $stmt = $database->prepare('SELECT numberElements as allowed FROM `tl_member_group` where id = ?');
                $allowed = $stmt->execute($dc->activeRecord->ownerGroupId)->fetchAllAssoc()[0]['allowed'];
                if ($current >= $allowed) {
                    throw new \Exception($GLOBALS['TL_LANG']['tl_c4g_data_element']['notice_already_max_published_elements']);
                }
            }
        }

        return $value;
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

        // Only show button if type supports it
        $typeModel = DataTypeModel::findByPk($row['type']);
        if ($typeModel !== null) {
            if (strval($typeModel->allowPublishing) !== '1') {
                return '';
            }
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
