<?php

/**
 * JBZoo Toolbox - Markdown.
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @see        https://github.com/JBZoo/Markdown
 */

declare(strict_types=1);

namespace JBZoo\Markdown;

use JBZoo\Utils\Str;

use function JBZoo\Utils\isStrEmpty;

class Markdown
{
    /**
     * Insert link to Markdown text.
     */
    public static function url(?string $title = null, ?string $url = null): ?string
    {
        $title = \trim((string)$title);
        $url   = \trim((string)$url);

        if (isStrEmpty($title) && !isStrEmpty($url)) {
            return "[{$url}]({$url})";
        }

        if (!isStrEmpty($title) && isStrEmpty($url)) {
            return $title;
        }

        if (isStrEmpty($title) && isStrEmpty($url)) {
            return null;
        }

        return "[{$title}]({$url})";
    }

    /**
     * Insert badge to Markdown text.
     */
    public static function badge(string $name, string $svgUrl, string $serviceUrl): ?string
    {
        return self::url(self::image($svgUrl, $name), $serviceUrl);
    }

    /**
     * Insert title to Markdown text.
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

        return \str_repeat('#', $level) . " {$title}\n";
    }

    /**
     * Insert image to Markdown text.
     */
    public static function image(?string $url, ?string $altText = null): string
    {
        $altText = \trim((string)$altText);
        $altText = $altText === '' ? 'Image' : $altText;

        $url = \trim((string)$url);

        return "![{$altText}]({$url})";
    }

    /**
     * @param string|string[] $quoteLines
     */
    public static function blockquote(array|string $quoteLines): string
    {
        if (!\is_array($quoteLines) && \str_contains($quoteLines, "\n")) {
            $quoteLines = Str::parseLines($quoteLines, false);
        }

        if (\is_array($quoteLines)) {
            $result = '';

            foreach ($quoteLines as $quoteLine) {
                $result .= self::blockquote($quoteLine);
            }

            return $result;
        }

        return "> {$quoteLines}\n";
    }

    /**
     * Render HTML block to hide text under spoiler.
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

        return \implode("\n", $result);
    }

    /**
     * Show code block as part of documentation.
     */
    public static function code(string $code, string $language = ''): string
    {
        $result = [
            "```{$language}",
            $code,
            '```',
            '',
        ];

        return \implode("\n", $result);
    }
}
