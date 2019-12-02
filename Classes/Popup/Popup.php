<?php

namespace con4gis\MapContentBundle\Classes\Popup;

class Popup
{
    protected $popupString = '';

    public function getPopupString() {
        return "<ul>".$this->popupString."</ul>";
    }

    protected function buildTag(string $tagName, string $content = '', array $attributes = [], bool $close = true) {
        foreach ($attributes as $key => $value) {
            if (trim($value) !== '') {
                $attributes[$key] = "$key=\"$value\"";
            }
        }
        $attributeString = implode(" ", $attributes);
        if ($close === true || $content !== '') {
            return "<$tagName $attributeString>$content</$tagName>";
        } else {
            return "<$tagName $attributeString>";
        }
    }

    public function addEntry(string $content, string $class) {
        $this->popupString .= $this->buildTag('li', $content, ['class' => $class]);
    }

    public function addLinkEntry(string $content, string $class, string $href, bool $newTab = true) {
        $attributes = [
            'href' => $href
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
                'rel' => 'noopener noreferrer'
            ]),
            ['class' => $class]
        );
    }

    public function addImageEntry(string $path, string $maxHeight, string $maxWidth, string $class, string $link = '') {
        if ($link !== '') {
            $attributes = [
                'href' => $link,
                'target' => '_blank',
                'rel' => 'noopener noreferrer'
            ];

            $this->popupString .= $this->buildTag(
                'li',
                $this->buildTag(
                    'a',
                    $this->buildTag(
                        'img',
                        '',
                        [
                            'maxHeight' => $maxHeight,
                            'maxWidth' => $maxWidth
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
                        'maxHeight' => $maxHeight,
                        'maxWidth' => $maxWidth
                    ],
                    false
                ),
                ['class' => $class]
            );
        }
    }


}