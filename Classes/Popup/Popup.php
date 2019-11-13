<?php

namespace con4gis\MapContentBundle\Classes\Popup;

use con4gis\CoreBundle\Resources\contao\classes\C4GUtils;

class Popup
{
    protected $popupString = '';

    public function addName(string $name, string $class = 'name') : Popup {
        $this->popupString .= "<div class=\"$class\">$name</div>";
        return $this;
    }

    public function addAddress(array $entries, string $class = 'address') {
        return $this->addStringFromArray($entries, $class);
    }

    public function addBusinessHours(array $entries, string $title = '', string $class = 'business_hours') {
        return $this->addList($entries, $title, $class);
    }

    public function addContactInfo(string $phone,
                                   string $mobile,
                                   string $fax,
                                   string $email,
                                   string $website,
                                   string $title = '',
                                   string $class = 'contact',
                                   bool $newTab = false
    ) {
        $list = [];
        if ($phone !== '') {
            $href = 'tel:'.$phone;
            $list[] = "<a href=\"$href\">Tel.: $phone</a>";
        }

        if ($mobile !== '') {
            $href = 'tel:'.$mobile;
            $list[] = "<a href=\"$href\">Mobil: $mobile</a>";
        }

        if ($fax !== '') {
            $list[] = 'Fax: '.$fax;
        }

        if ($email !== '') {
            $href = 'mailto:'.$email;
            $list[] = "<a href=\"$href\">$email</a>";
        }

        if ($website !== '') {
            if (!C4GUtils::startsWith($website, 'http')) {
                $href = 'http://'.$website;
            } else {
                $href = $website;
            }

            if ($newTab) {
                $rel = "target=\"_blank\" rel=\"noopener noreferrer\" ";
            } else {
                $rel = '';
            }

            $list[] = "<a $rel href=\"$href\">$website</a>";
        }

        if ($list !== []) {
            return $this->addList($list, $title, $class);
        }
        return $this;
    }

    public function addFilter(array $entries, string $title = '', string $class = 'filter') {
        if (!empty($entries)) {
            $entries[0] = $title . ': ' . $entries[0];
            return $this->addStringFromArray($entries, $class);
        }
        return $this;
    }

    public function addTags(array $entries, string $class = 'tags') {
        return $this->addStringFromArray($entries, $class);
    }

    public function addDescription(string $description, string $class = 'description') {
        $this->popupString .= "<div class=\"$class\">$description</div>";
        return $this;
    }

    public function addImage(string $path, string $maxHeight, string $maxWidth, string $class = 'image') {
        $this->popupString .= "<img class=\"$class\" src=\"" . $path . "\" " . "style=\"max-height: " .
            $maxHeight . "px;max-width: " . $maxWidth . "px;\">";
        return $this;
    }

    public function addLinks(array $links, string $class = 'links') {
        $this->popupString .= "<div class=\"$class\"><ul>";
        foreach ($links as $link) {
            if ($link['linkNewTab']) {
                $rel = " target=\"_blank\" rel=\"noopener noreferrer\"";
            } else {
                $rel = '';
            }
            $this->popupString .= "<li><a href=\"" . $link['linkHref'] . "\"$rel>" . $link['linkTitle'] . "</a></li>";
        }
        $this->popupString .= "</ul></div>";
    }

    public function addString(string $string, string $class = '') {
        $this->popupString .= "<div class=\"$class\">$string</div>";
    }

    protected function addList(array $entries, string $title = '', string $class = 'list') {
        if (!empty($entries)) {
            $listString = '';
            foreach($entries as $entry) {
                if (trim($entry) !== '') {
                    $listString .= "<li>$entry</li>";
                }
            }

            if ($listString !== '') {
                $this->popupString .= "<div class=\"$class\">$title<ul>";
                $this->popupString .= $listString;
                $this->popupString .= "</ul></div>";
            }
        }
        return $this;
    }

    protected function addStringFromArray(array $array, string $class = '') {
        $this->popupString .= "<div class=\"$class\">".implode(', ', $array)."</div>";
        return $this;
    }

    public function getPopupString() {
        return $this->popupString;
    }
}