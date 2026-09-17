<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Reduces rich editor output to inline HTML so it can be rendered inside
 * headings and other phrasing-only elements.
 */
class InlineHtml
{
    private const string ALLOWED_TAGS = '<em><strong><u><s><br>';

    public static function from(?string $html): string
    {
        if (blank($html)) {
            return '';
        }

        // Turn block boundaries into line breaks before the block tags are stripped.
        $html = preg_replace('#</(p|div|h[1-6]|li|blockquote)>\s*<(p|div|h[1-6]|li|blockquote)\b[^>]*>#i', '<br>', $html);

        $html = strip_tags($html, self::ALLOWED_TAGS);

        // strip_tags keeps attributes on allowed tags, so drop them explicitly.
        $html = preg_replace('#<(em|strong|u|s|br)\b[^>]*>#i', '<$1>', $html);

        $html = preg_replace('#^(\s|<br>)+|(\s|<br>)+$#i', '', $html);

        return $html;
    }
}
