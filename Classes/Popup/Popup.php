<?php

namespace con4gis\MapContentBundle\Classes\Popup;

use con4gis\CoreBundle\Resources\contao\classes\C4GUtils;

class Popup
{
    protected $popupString = '';

    public function getPopupString()
    {
        return '<ul>' . $this->popupString . '</ul>';
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

        if (!C4GUtils::startsWith($href, 'http')) {
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
