<?php

namespace con4gis\MapContentBundle\Classes\Popup;

class Popup
{
    protected $popupString = '';

    public function addName(string $name, string $class = 'name') : Popup {
        $this->popupString .= "<div class=\"$class\">$name</div>";
        return $this;
    }

    public function addAddress(array $entries, string $class = 'address') {
        return $this->addList($entries, '', $class);
    }

    public function addBusinessHours(array $entries, string $class = 'business_hours') {
        return $this->addList($entries, '', $class);
    }

    public function addContactInfo(string $phone, string $fax, string $email, string $class = 'contact') {
        $list = [];
        if ($phone !== '') {
            $list[] = 'Tel.: '.$phone;
        }

        if ($fax !== '') {
            $list[] = 'Fax: '.$fax;
        }

        if ($email !== '') {
            $list[] = $email;
        }

        if ($list !== []) {
            return $this->addList($list, '', $class);
        }
        return $this;
    }

    public function addFilter(array $entries, string $class = 'filter') {
        return $this->addList($entries, '', $class);
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

    protected function addList(array $entries, string $title = '', string $class = 'list') {
        if (!empty($entries)) {
            $this->popupString .= "<div class=\"$class\">$title<ul>";
            foreach($entries as $entry) {
                if ($entry !== '') {
                    $this->popupString .= "<li>$entry</li>";
                }
            }
            $this->popupString .= "</ul></div>";
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