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

namespace con4gis\DataBundle\Classes\Popup;

use con4gis\CoreBundle\Classes\C4GUtils;
use con4gis\CoreBundle\Resources\contao\models\C4gLogModel;
use con4gis\DataBundle\Classes\Models\DataCustomFieldModel;
use con4gis\DataBundle\Classes\Models\DataTypeModel;
use Contao\FilesModel;
use Contao\StringUtil;

class Popup
{
    protected $popupString = '';

    public function getPopupString()
    {
        return '<ul>' . $this->popupString . '</ul>';
    }
    public function generatePopup($typeElement, $availableFields)
    {
        \System::loadLanguageFile('tl_c4g_data_element');

        $this->addEntry(strval($typeElement['name']), 'name');

        $typeModel = DataTypeModel::findByPk($typeElement['type']);
        $this->addEntry(strval($typeModel->name), 'type');
        if (strval($typeElement['description']) !== '') {
            $this->addEntry(strval($typeElement['description']), 'description');
        }
        $addressIsSet = false;

        foreach ($availableFields as $fieldKey => $availableField) {
            if (($availableField === 'addressName' ||
                $availableField === 'addressStreet' ||
                $availableField === 'addressStreetNumber' ||
                $availableField === 'addressZip' ||
                $availableField === 'addressCity')
            ) {
                if ($addressIsSet === false) {
                    $addressIsSet = true;
                    $address = [];
                    if ($typeElement['addressName'] !== '') {
                        $address[] = $typeElement['addressName'];
                    }
                    if ($typeElement['addressStreet'] !== '') {
                        if ($typeElement['addressStreetNumber'] !== '0') {
                            $address[] = $typeElement['addressStreet'] . ' ' .
                                $typeElement['addressStreetNumber'];
                        } else {
                            $address[] = $typeElement['addressStreet'];
                        }
                    }
                    if ($typeElement['addressZip'] !== '' && $typeElement['addressCity'] !== '') {
                        $address[] = $typeElement['addressZip'] . ' ' . $typeElement['addressCity'];
                    }
                    $this->addEntry(implode(', ', $address), 'address');
                }
            } elseif ($availableField === 'image') {
                if (is_string($typeElement['image']) === true) {
                    $fileModel = FilesModel::findByUuid($typeElement['image']);
                    if ($fileModel !== null) {
                        $this->addImageEntry($fileModel->path, $typeElement['imageMaxHeight'], $typeElement['imageMaxWidth'], 'image', strval($typeElement['imageLink']));
                    } else {
                        C4gLogModel::addLogEntry('data', 'Popupimage of element ' . $typeElement['id'] . ' with uuid ' . $typeElement['image'] . ' not found.');
                    }
                }
            } elseif ($availableField === 'businessHours') {
                $businessTimes = \StringUtil::deserialize($typeElement['businessHours']);
                if (!empty($businessTimes) || $typeElement['businessHoursAdditionalInfo'] !== '') {
                    $timeString = [];
                    $showBusinessTimes = false;
                    foreach ($businessTimes as $key => $time) {
                        $timeString[$key] = '';
                        if ($time['dayFrom'] !== '' && $time['timeFrom'] !== '' && $time['timeTo'] !== '') {
                            $timeString[$key] .= $GLOBALS['TL_LANG']['tl_c4g_data_element']['day_reference'][$time['dayFrom']];
                            if ($time['dayTo'] !== $time['dayFrom'] && $time['dayTo'] !== '') {
                                if (abs(intval($time['dayTo']) - intval($time['dayFrom'])) > 1) {
                                    $join = $GLOBALS['TL_LANG']['tl_c4g_data_element']['day_join']['to'];
                                } else {
                                    $join = $GLOBALS['TL_LANG']['tl_c4g_data_element']['day_join']['and'];
                                }

                                $timeString[$key] .= " $join " . $GLOBALS['TL_LANG']['tl_c4g_data_element']['day_reference'][$time['dayTo']];
                            }
                            if ($time['timeFrom'] && $time['timeTo']) {
                                $timeString[$key] .= ': ' . date('H:i', intval($time['timeFrom'])) .
                                    $GLOBALS['TL_LANG']['tl_c4g_data_element']['timeCaption'] .
                                    ' - ' . date('H:i', intval($time['timeTo'])) .
                                    $GLOBALS['TL_LANG']['tl_c4g_data_element']['timeCaption'];
                            }

                            $showBusinessTimes = true;
                        }
                    }
                    if ($showBusinessTimes === true) {
                        $bH = [];
                        $entries = [];
                        foreach ($timeString as $string) {
                            $explode = explode(': ', $string);
                            $key = $explode[0];
                            if (isset($bH[$key]) === true) {
                                $bH[$key] .= ', ' . $explode[1];
                            } else {
                                $bH[$key] = $explode[1];
                            }
                        }
                        foreach ($bH as $k => $v) {
                            if (!empty($v)) {
                                $entries[] = $k . ': ' . $v;
                            } else {
                                $entries[] = $k;
                            }
                        }
                        foreach ($entries as $entry) {
                            $this->addEntry(strval($entry), 'businessHours');
                        }
                    }
                    if ($typeElement['businessHoursAdditionalInfo'] !== '') {
                        $this->addEntry(strval($typeElement['businessHoursAdditionalInfo']), 'businessHours');
                    }
                }
            } elseif ($availableField === 'linkWizard') {
                foreach (StringUtil::deserialize($typeElement['linkWizard']) as $link) {
                    $this->addLinkEntry(strval($link['linkTitle']), 'link', strval($link['linkHref']), $link['linkNewTab']);
                }
            } elseif ($availableField === 'phone') {
                if ($typeElement['phone'] !== '') {
                    $list['linkHref'] = 'tel:' . $typeElement['phone'];
                    $list['linkTitle'] = $typeElement['phone'];
                    $this->addLinkEntry(strval($list['linkTitle']), 'phone', strval($list['linkHref']));
                }
            } elseif ($availableField === 'mobile') {
                if ($typeElement['mobile'] !== '') {
                    $list['linkHref'] = 'tel:' . $typeElement['mobile'];
                    $list['linkTitle'] = 'Mobil: ' . $typeElement['mobile'];
                    $this->addLinkEntry(strval($list['linkTitle']), 'mobile', strval($list['linkHref']));
                }
            } elseif ($availableField === 'fax') {
                if ($typeElement['fax'] !== '') {
                    $list['linkHref'] = '';
                    $list['linkTitle'] = 'FAX: ' . $typeElement['fax'];
                    $this->addEntry(strval($list['linkTitle']), 'fax');
                }
            } elseif ($availableField === 'accessibility') {
                if ($typeElement['accessibility'] === '1') {
                    $this->addEntry($GLOBALS['TL_LANG']['con4gis']['data']['frontend']['yes'], 'accessibility');
                } else {
                    $this->addEntry($GLOBALS['TL_LANG']['con4gis']['data']['frontend']['no'], 'accessibility');
                }
            } elseif ($availableField === 'email') {
                if ($typeElement['email'] !== '') {
                    $list['linkHref'] = 'mailto:' . $typeElement['email'];
                    $list['linkTitle'] = $typeElement['email'];
                    $this->addLinkEntry(strval($list['linkTitle']), 'email', strval($list['linkHref']));
                }
            } elseif ($availableField === 'website') {
                if (!C4GUtils::startsWith($typeElement['website'], 'http')) {
                    $list['linkHref'] = 'http://' . $typeElement['website'];
                } else {
                    $list['linkHref'] = $typeElement['website'];
                }
                $list['linkHref'] = $typeElement['website'];

                $title = $typeElement['websiteLabel'] ? $typeElement['websiteLabel'] : $typeElement['website'];
                $list['linkTitle'] = $title;
                $this->addLinkEntry(strval($list['linkTitle']), 'website', strval($list['linkHref']));
            } else {
                $model = DataCustomFieldModel::findBy('alias', $availableField);
                if ($model !== null) {
                    if ($model->published === '1' && $model->frontendPopup === '1') {
                        if (strval($model->type) === 'legend' && (strval($model->frontendName) !== '' || strval($model->name) !== '')) {
                            $i = $fieldKey + 1;
                            while ($i < count($availableFields)) {
                                $legendModel = DataCustomFieldModel::findBy('alias', $availableFields[$i]);
                                if ($legendModel !== null && $legendModel->published === '1' && $legendModel->type === 'legend') {
                                    break;
                                }
                                if (C4GUtils::endsWith($availableFields[$i], '_legend') === true) {
                                    break;
                                }
                                $i += 1;
                            }
                            $n = $fieldKey + 1;
                            $show = false;
                            while ($i > $n) {
                                if (strval($typeElement[$availableFields[$n]]) !== ''
                                    && intval($typeElement[$availableFields[$n]]) !== 0) {
                                    $show = true;
                                }
                                $n += 1;
                            }
                            if ($show === true) {
                                $this->addEntry(strval($model->frontendName ?: $model->name), 'legend');
                            }
                        } elseif (strval($model->type) !== 'legend') {
                            switch ($model->type) {
                                case 'select':
                                    $options = StringUtil::deserialize($model->options);
                                    if (is_array($options)) {
                                        foreach ($options as $option) {
                                            if ($option['key'] === $typeElement[$availableField]) {
                                                $this->addEntry($option['value'], $availableField);

                                                break;
                                            }
                                        }
                                    }

                                    break;
                                case 'checkbox':
                                    if ($typeElement[$availableField] === '1') {
                                        $this->addEntry($GLOBALS['TL_LANG']['con4gis']['data']['frontend']['yes'], $availableField);

                                        break;
                                    }
                                    $this->addEntry($GLOBALS['TL_LANG']['con4gis']['data']['frontend']['no'], $availableField);

                                    break;
                                case 'icon':
                                    if ($typeElement[$availableField] === '1' ) {
                                        if (strval($model->icon) !== '') {
                                            $this->addEntry(strval($model->icon), $availableField);
                                        }
                                        else if ($model->customIcon && FilesModel::findByUuid($model->customIcon)) {
                                            $img = FilesModel::findByUuid($model->customIcon)->path;
                                            $content = '<img src="'. $img .'"></img>';
                                            $this->addEntry($content, $availableField);
                                        }

                                        break;
                                    }

                                    break;
                                case 'link':
                                    if ($typeElement[$availableField] === '1' && strval($model->linkTitle) !== '' && strval($model->linkHref) !== '') {
                                        $this->addLinkEntry(strval($model->linkTitle), strval($model->alias), strval($model->linkHref), true);

                                        break;
                                    }

                                    break;
                                case 'multicheckbox':
                                    $options = StringUtil::deserialize($model->options);
                                    $values = StringUtil::deserialize($typeElement[$availableField]);
                                    $display = [];
                                    if (is_array($options) && is_array($values)) {
                                        foreach ($values as $value) {
                                            foreach ($options as $option) {
                                                if ($value === $option['key']) {
                                                    $display[] = $option['value'];

                                                    break;
                                                }
                                            }
                                        }
                                    }
                                    if (!empty($display)) {
                                        $this->addEntry($model->name . ': ' . implode(', ', $display), $availableField);
                                    }

                                    break;
                                case 'datepicker':
                                    $this->addEntry(date('d.m.Y', $typeElement[$availableField]), $availableField);

                                    break;
                                default:
                                    $this->addEntry(strval($typeElement[$availableField]), $availableField);
                            }
                        }
                    }
                } else {
                    if (C4GUtils::endsWith($availableField, '_legend') === true) {
                        switch ($availableField) {
                            case 'address_legend':
                            case 'image_legend':
                            case 'linkWizard_legend':
                            case 'publish_legend':
                                break;
                            default:
                                $i = $fieldKey + 1;
                                while ($i < count($availableFields)) {
                                    $legendModel = DataCustomFieldModel::findBy('alias', $availableFields[$i]);
                                    if ($legendModel !== null && $model->published === '1' && $legendModel->type === 'legend') {
                                        break;
                                    }
                                    if (C4GUtils::endsWith($availableFields[$i], '_legend') === true) {
                                        break;
                                    }
                                    $i += 1;
                                }
                                $n = $fieldKey + 1;
                                $show = false;
                                while ($i > $n) {
                                    if (($availableFields[$n] === 'accessibility') || (strval($typeElement[$availableFields[$n]]) !== ''
                                            && intval($typeElement[$availableFields[$n]]) !== 0)) {
                                        $show = true;
                                    }
                                    $n += 1;
                                }
                                if ($show === true) {
                                    if (strval($GLOBALS['TL_LANG']['tl_c4g_data_element'][$availableField]) !== '') {
                                        $this->addEntry(
                                            strval($GLOBALS['TL_LANG']['tl_c4g_data_element'][$availableField]),
                                            $availableField
                                        );
                                    }
                                }

                                break;
                        }
                    } elseif (strval($typeElement[$availableField]) !== '' &&
                        intval($typeElement[$availableField]) !== 0) {
                        $this->addEntry(
                            strval($typeElement[$availableField]),
                            $availableField);
                    }
                }
            }
        }
    }

    protected function buildTag(string $tagName, string $content = '', array $attributes = [], bool $close = true)
    {
        foreach ($attributes as $key => $value) {
            if (trim($value) !== '') {
                $attributes[$key] = "$key=\"$value\"";
            }
        }
        $attributeString = implode(' ', $attributes);
        if ($close === true || $content !== '') {
            return "<$tagName $attributeString>$content</$tagName>";
        }

        return "<$tagName $attributeString>";
    }

    public function addEntry(string $content, string $class)
    {
        if (trim($content) === '') {
            return;
        }
        $this->popupString .= $this->buildTag('li', $content, ['class' => $class]);
    }

    public function addLinkEntry(string $content, string $class, string $href, bool $newTab = true)
    {
        if (trim($content) === '' || trim($href) === '') {
            return;
        }

        if (!C4GUtils::startsWith($href, 'http') && !C4GUtils::startsWith($href, 'mailto') && !C4GUtils::startsWith($href, 'tel')) {
            $href = 'http://' . $href;
        }

        $attributes = [
            'href' => $href,
        ];
        if ($newTab === true) {
            $attributes['target'] = '_blank';
            $attributes['rel'] = 'noopener noreferrer';
        }
        $this->popupString .= $this->buildTag(
            'li',
            $this->buildTag('a', $content, [
                'href' => $href,
                'target' => '_blank',
                'rel' => 'noopener noreferrer',
            ]),
            ['class' => $class]
        );
    }

    public function addImageEntry(string $path, string $maxHeight, string $maxWidth, string $class, string $link = '')
    {
        if (trim($path) === '' || trim($maxHeight) === '' || trim($maxWidth) === '') {
            return;
        }

        if ($link !== '') {
            $attributes = [
                'href' => $link,
                'target' => '_blank',
                'rel' => 'noopener noreferrer',
            ];

            $this->popupString .= $this->buildTag(
                'li',
                $this->buildTag(
                    'a',
                    $this->buildTag(
                        'img',
                        '',
                        [
                            'src' => $path,
                            'style' => "max-height: $maxHeight\px;max-width: $maxWidth\px;",
                        ],
                        false
                    ),
                    $attributes),
                ['class' => $class]
            );
        } else {
            $this->popupString .= $this->buildTag(
                'li',
                $this->buildTag(
                    'img',
                    '',
                    [
                        'src' => $path,
                        'style' => "max-height: $maxHeight\px;max-width: $maxWidth\px;",
                    ],
                    false
                ),
                ['class' => $class]
            );
        }
    }
}
