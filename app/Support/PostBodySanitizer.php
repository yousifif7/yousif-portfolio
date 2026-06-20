<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

class PostBodySanitizer
{
    /** @var list<string> */
    private const ALLOWED_TAGS = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'span',
        'h2', 'h3', 'h4', 'ul', 'ol', 'li', 'a', 'blockquote',
    ];

    public static function clean(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        if (trim(strip_tags($html)) === '') {
            return '';
        }

        libxml_use_internal_errors(true);

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadHTML(
            '<?xml encoding="UTF-8"><div>'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        libxml_clear_errors();

        $container = $dom->getElementsByTagName('div')->item(0);
        if (! $container instanceof DOMElement) {
            return '';
        }

        self::sanitizeChildren($container);

        $result = '';
        foreach ($container->childNodes as $child) {
            $result .= $dom->saveHTML($child);
        }

        return trim($result);
    }

    private static function sanitizeChildren(DOMElement $parent): void
    {
        for ($i = $parent->childNodes->length - 1; $i >= 0; $i--) {
            $node = $parent->childNodes->item($i);
            if (! $node instanceof DOMNode) {
                continue;
            }

            if ($node->nodeType === XML_TEXT_NODE) {
                continue;
            }

            if ($node->nodeType !== XML_ELEMENT_NODE || ! $node instanceof DOMElement) {
                $parent->removeChild($node);
                continue;
            }

            $tag = strtolower($node->nodeName);

            if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                while ($node->firstChild) {
                    $parent->insertBefore($node->firstChild, $node);
                }
                $parent->removeChild($node);
                continue;
            }

            self::stripAttributes($node, $tag);
            self::sanitizeChildren($node);
        }
    }

    private static function stripAttributes(DOMElement $element, string $tag): void
    {
        if ($tag === 'a') {
            $href = $element->getAttribute('href');

            while ($element->attributes->length > 0) {
                $element->removeAttribute($element->attributes->item(0)->nodeName);
            }

            if (self::isSafeUrl($href)) {
                $element->setAttribute('href', $href);
                $element->setAttribute('rel', 'noopener noreferrer');

                if (self::isExternalUrl($href)) {
                    $element->setAttribute('target', '_blank');
                }
            }

            return;
        }

        if ($tag === 'span') {
            $style = self::sanitizeStyle($element->getAttribute('style'));

            while ($element->attributes->length > 0) {
                $element->removeAttribute($element->attributes->item(0)->nodeName);
            }

            if ($style !== '') {
                $element->setAttribute('style', $style);
            }

            return;
        }

        while ($element->attributes->length > 0) {
            $element->removeAttribute($element->attributes->item(0)->nodeName);
        }
    }

    private static function sanitizeStyle(string $style): string
    {
        if (trim($style) === '') {
            return '';
        }

        $safe = [];

        foreach (explode(';', $style) as $rule) {
            $rule = trim($rule);
            if ($rule === '' || ! str_contains($rule, ':')) {
                continue;
            }

            [$property, $value] = array_map('trim', explode(':', $rule, 2));
            $property = strtolower($property);

            if (! in_array($property, ['color', 'background-color', 'background'], true)) {
                continue;
            }

            if ($property === 'background') {
                $property = 'background-color';
            }

            if (self::isSafeColor($value)) {
                $safe[] = $property.': '.$value;
            }
        }

        return implode('; ', $safe);
    }

    private static function isSafeColor(string $value): bool
    {
        $value = trim($value);

        return (bool) preg_match(
            '/^(#[0-9a-f]{3,8}|rgb\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}\s*\)|rgba\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*(0|0?\.\d+|1)\s*\))$/i',
            $value
        );
    }

    private static function isSafeUrl(string $url): bool
    {
        $url = trim($url);

        if ($url === '' || $url === '#') {
            return true;
        }

        if (str_starts_with($url, '/')) {
            return ! str_starts_with($url, '//');
        }

        return (bool) preg_match('#^https?://#i', $url) || str_starts_with(strtolower($url), 'mailto:');
    }

    private static function isExternalUrl(string $url): bool
    {
        return (bool) preg_match('#^https?://#i', trim($url));
    }
}
