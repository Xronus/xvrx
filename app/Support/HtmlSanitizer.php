<?php

namespace App\Support;

class HtmlSanitizer
{
    private const ALLOWED_TAGS = [
        'a', 'b', 'blockquote', 'br', 'code', 'div', 'em', 'h2', 'h3', 'h4',
        'hr', 'i', 'img', 'li', 'ol', 'p', 'pre', 'span', 'strong', 'table',
        'tbody', 'td', 'th', 'thead', 'tr', 'u', 'ul',
    ];

    private const ALLOWED_ATTRIBUTES = [
        'a' => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'title', 'loading', 'width', 'height'],
    ];

    private const URL_ATTRIBUTES = ['href', 'src'];
    private const ALLOWED_URL_SCHEMES = ['http', 'https', 'mailto'];

    public static function clean(?string $html): string
    {
        $html = trim((string) $html);

        if ($html === '') {
            return '';
        }

        $document = new \DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);

        $document->loadHTML(
            '<?xml encoding="UTF-8">'.$html,
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        self::sanitizeNode($document);

        return str_replace('<?xml encoding="UTF-8">', '', $document->saveHTML());
    }

    private static function sanitizeNode(\DOMNode $node): void
    {
        if ($node instanceof \DOMElement) {
            $tag = strtolower($node->tagName);

            if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                self::unwrapNode($node);
                return;
            }

            self::sanitizeAttributes($node, $tag);
        }

        foreach (iterator_to_array($node->childNodes) as $child) {
            self::sanitizeNode($child);
        }
    }

    private static function sanitizeAttributes(\DOMElement $node, string $tag): void
    {
        $allowed = self::ALLOWED_ATTRIBUTES[$tag] ?? [];

        foreach (iterator_to_array($node->attributes) as $attribute) {
            $name = strtolower($attribute->name);
            $value = trim($attribute->value);

            if (! in_array($name, $allowed, true) || str_starts_with($name, 'on')) {
                $node->removeAttributeNode($attribute);
                continue;
            }

            if (in_array($name, self::URL_ATTRIBUTES, true) && ! self::isSafeUrl($value)) {
                $node->removeAttributeNode($attribute);
                continue;
            }

            if ($tag === 'a' && $name === 'target' && $value === '_blank') {
                $node->setAttribute('rel', 'noopener noreferrer');
            }
        }
    }

    private static function isSafeUrl(string $value): bool
    {
        if ($value === '') {
            return false;
        }

        if (str_starts_with($value, '/') && ! str_starts_with($value, '//')) {
            return true;
        }

        $scheme = strtolower((string) parse_url($value, PHP_URL_SCHEME));

        return in_array($scheme, self::ALLOWED_URL_SCHEMES, true);
    }

    private static function unwrapNode(\DOMNode $node): void
    {
        $parent = $node->parentNode;

        if (! $parent) {
            return;
        }

        while ($node->firstChild) {
            $parent->insertBefore($node->firstChild, $node);
        }

        $parent->removeChild($node);
    }
}
