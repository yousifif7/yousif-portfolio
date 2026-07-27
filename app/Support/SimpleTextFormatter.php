<?php

namespace App\Support;

/**
 * Lightweight plain-text → HTML formatter for admin textareas.
 *
 * Supports:
 * - Bullet lists: lines starting with "* " or "- "
 * - Numbered lists: lines starting with "1- ", "1. ", or "1) "
 * - Auto-linked http(s) and www. URLs
 * - Paragraphs / line breaks for remaining text
 */
class SimpleTextFormatter
{
    public static function toHtml(?string $text): string
    {
        if ($text === null || trim($text) === '') {
            return '';
        }

        $lines = preg_split("/\r\n|\r|\n/", $text) ?: [];
        $html = [];
        $listType = null;
        $listItems = [];
        $paragraphLines = [];

        $flushList = function () use (&$html, &$listType, &$listItems): void {
            if ($listType === null) {
                return;
            }

            $items = '';
            foreach ($listItems as $item) {
                $items .= '<li>'.$item.'</li>';
            }

            $html[] = '<'.$listType.'>'.$items.'</'.$listType.'>';
            $listType = null;
            $listItems = [];
        };

        $flushParagraph = function () use (&$html, &$paragraphLines): void {
            if ($paragraphLines === []) {
                return;
            }

            $html[] = '<p>'.implode('<br>', $paragraphLines).'</p>';
            $paragraphLines = [];
        };

        foreach ($lines as $line) {
            $trimmed = rtrim($line);

            if (preg_match('/^\s*[\*\-]\s+(.+)$/u', $trimmed, $match)) {
                $flushParagraph();
                if ($listType !== 'ul') {
                    $flushList();
                    $listType = 'ul';
                }
                $listItems[] = self::formatInline($match[1]);
                continue;
            }

            if (preg_match('/^\s*\d+[\-\.\)]\s+(.+)$/u', $trimmed, $match)) {
                $flushParagraph();
                if ($listType !== 'ol') {
                    $flushList();
                    $listType = 'ol';
                }
                $listItems[] = self::formatInline($match[1]);
                continue;
            }

            $flushList();

            if (trim($trimmed) === '') {
                $flushParagraph();
                continue;
            }

            $paragraphLines[] = self::formatInline($trimmed);
        }

        $flushList();
        $flushParagraph();

        return implode("\n", $html);
    }

    private static function formatInline(string $text): string
    {
        $links = [];

        $withPlaceholders = preg_replace_callback(
            '/\b((?:https?:\/\/|www\.)[^\s<>"\']+)/iu',
            function (array $match) use (&$links): string {
                $raw = $match[1];
                $trailing = '';

                if (preg_match('/^(.*?)([.,;:!?)\]\}]+)$/u', $raw, $parts)) {
                    $raw = $parts[1];
                    $trailing = $parts[2];
                }

                if ($raw === '') {
                    return $match[0];
                }

                $href = preg_match('/^www\./i', $raw) ? 'https://'.$raw : $raw;

                if (! preg_match('/^https?:\/\//i', $href)) {
                    return $match[0];
                }

                $id = count($links);
                $links[$id] = sprintf(
                    '<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
                    htmlspecialchars($href, ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars($raw, ENT_QUOTES, 'UTF-8')
                );

                return "\x1AURL{$id}\x1A".$trailing;
            },
            $text
        ) ?? $text;

        $escaped = htmlspecialchars($withPlaceholders, ENT_QUOTES, 'UTF-8');

        return preg_replace_callback(
            '/\x1AURL(\d+)\x1A/',
            static fn (array $match): string => $links[(int) $match[1]] ?? '',
            $escaped
        ) ?? $escaped;
    }
}
