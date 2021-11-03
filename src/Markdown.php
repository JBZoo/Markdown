<?php

/**
 * JBZoo Toolbox - Markdown
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    Markdown
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/Markdown
 */

declare(strict_types=1);

namespace JBZoo\Markdown;

use JBZoo\Utils\Str;

/**
 * Class Markdown
 * @package JBZoo\Markdown
 */
class Markdown
{
    /**
     * @param string|null $title
     * @param string|null $url
     * @return string
     */
    public static function url(?string $title = null, ?string $url = null): string
    {
        $title = trim((string)$title);
        $url = trim((string)$url);

        if (empty($title) && !empty($url)) {
            return "[$url]($url)";
        }

        if (!empty($title) && empty($url)) {
            return $title;
        }

        if (empty($title) && empty($url)) {
            return '';
        }

        return "[{$title}]({$url})";
    }

    /**
     * @param string $name
     * @param string $svgUrl
     * @param string $serviceUrl
     * @return string
     */
    public static function badge(string $name, string $svgUrl, string $serviceUrl): string
    {
        return self::url(self::image($svgUrl, $name), $serviceUrl);
    }

    /**
     * @param string $title
     * @param int    $level
     * @return string
     */
    public static function title(string $title, int $level = 2): string
    {
        $maxLevel = 1;
        $minLevel = 6;

        if ($level < $maxLevel) {
            $level = $maxLevel;
        }
        if ($level > $minLevel) {
            $level = $minLevel;
        }

        return str_repeat('#', $level) . " {$title}\n";
    }

    /**
     * @param string|null $url
     * @param string|null $altText
     * @return string
     */
    public static function image(?string $url, ?string $altText = null): string
    {
        $altText = trim((string)$altText);
        $altText = $altText ?: 'Image';

        $url = trim((string)$url);

        return "![{$altText}]({$url})";
    }

    /**
     * @param string[]|string $quoteLines
     * @return string
     */
    public static function blockquote($quoteLines): string
    {
        if (!is_array($quoteLines) && strpos($quoteLines, "\n") !== false) {
            $quoteLines = Str::parseLines($quoteLines, false);
        }

        if (is_array($quoteLines)) {
            $result = '';
            foreach ($quoteLines as $quoteLine) {
                $result .= self::blockquote($quoteLine);
            }

            return $result;
        }

        return "> {$quoteLines}\n";
    }

    /**
     * @param string $title
     * @param string $body
     * @return string
     */
    public static function spoiler(string $title, string $body): string
    {
        $result = [
            '<details>',
            "  <summary>{$title}</summary>",
            '  ',
            "  {$body}",
            '  ',
            '</details>',
            '',
        ];

        return implode("\n", $result);
    }

    /**
     * @param string $code
     * @param string $language
     * @return string
     */
    public static function code(string $code, string $language = ''): string
    {
        $result = [
            "```{$language}",
            $code,
            '```',
            '',
        ];

        return implode("\n", $result);
    }
}
