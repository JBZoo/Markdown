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

namespace JBZoo\PHPUnit;

use JBZoo\Markdown\Markdown;

/**
 * Class MarkdownTest
 * @package JBZoo\PHPUnit
 */
class MarkdownTest extends PHPUnit
{
    public function testUrl()
    {
        isSame('[123](https://google.com)', Markdown::url('123', 'https://google.com'));
        isSame('123', Markdown::url('123'));
        isSame('123', Markdown::url('123', null));
        isSame('[https://google.com](https://google.com)', Markdown::url('', 'https://google.com'));
        isSame('', Markdown::url('', ''));
        isSame('', Markdown::url(null, null));
        isSame('', Markdown::url());
    }

    public function testBadge()
    {
        isSame(
            '[![Status](https://travis-ci.org/)](https://travis-ci.org/Status)',
            Markdown::badge('Status', 'https://travis-ci.org/', 'https://travis-ci.org/Status')
        );
    }

    public function testTitle()
    {
        isSame("# Title\n", Markdown::title('Title', 0));
        isSame("# Title\n", Markdown::title('Title', 1));
        isSame("## Title\n", Markdown::title('Title', 2));
        isSame("### Title\n", Markdown::title('Title', 3));
        isSame("#### Title\n", Markdown::title('Title', 4));
        isSame("##### Title\n", Markdown::title('Title', 5));
        isSame("###### Title\n", Markdown::title('Title', 6));
        isSame("###### Title\n", Markdown::title('Title', 7));
        isSame("###### Title\n", Markdown::title('Title', 10000));
    }

    public function testImage()
    {
        isSame('![Image](https://google.com/example.jpg)', Markdown::image('https://google.com/example.jpg'));
        isSame('![Image](https://google.com/example.jpg)', Markdown::image('https://google.com/example.jpg', ' '));
        isSame('![Logo](https://google.com/example.jpg)', Markdown::image('https://google.com/example.jpg', 'Logo'));
    }

    public function testBlockquotes()
    {
        isSame("> Quote Text\n", Markdown::blockquote('Quote Text'));
        isSame("> Quote Text\n", Markdown::blockquote(['Quote Text']));
        isSame(
            "> Quote LIne 1\n> Quote LIne 2\n> Quote LIne 3\n",
            Markdown::blockquote(['Quote LIne 1', 'Quote LIne 2', 'Quote LIne 3'])
        );

        isSame(
            "> Quote LIne 1\n> Quote LIne 2\n> Quote LIne 3\n",
            Markdown::blockquote(["Quote LIne 1\nQuote LIne 2\nQuote LIne 3"])
        );
    }

    public function testSpoiler()
    {
        isSame(implode("\n", [
            '<details>',
            '  <summary>Quote Text</summary>',
            '  ',
            '  Some hidden text',
            '  ',
            '</details>',
            ''
        ]), Markdown::spoiler('Quote Text', 'Some hidden text'));
    }

    public function testCode()
    {
        isSame(implode("\n", [
            '```',
            'echo 1',
            '```',
            ''
        ]), Markdown::code('echo 1'));

        isSame(implode("\n", [
            '```php',
            '<?php',
            'echo 1;',
            '',
            '```',
            ''
        ]), Markdown::code("<?php\necho 1;\n", 'php'));
    }
}
