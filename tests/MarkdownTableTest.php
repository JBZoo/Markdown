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

use JBZoo\Markdown\Table;

/**
 * Class MarkdownTableTest
 * @package JBZoo\PHPUnit
 */
class MarkdownTableTest extends PHPUnit
{
    public function testEmptyOne()
    {
        $table = (new Table());
        isSame('', $table->render());
    }

    public function testOneAndOne()
    {
        $table = (new Table())
            ->setHeaders(['Header #1'])
            ->appendRow(['123']);

        isSame(implode("\n", [
            '| Header #1 |',
            '|:----------|',
            '| 123       |',
            '',
        ]), $table->render());
    }

    public function testNoRowsTable()
    {
        $table = (new Table())
            ->setHeaders(['Header #1', 'Header #2']);

        isSame(implode("\n", [
            '| Header #1 | Header #2 |',
            '|:----------|:----------|',
            '',
        ]), $table->render());
    }

    public function testNoHeadersTable()
    {
        $table = (new Table())
            ->appendRow(['123', 456]);

        isSame("| 123 | 456 |\n", $table->render());
    }

    public function testOneRowTable()
    {
        $table = (new Table())
            ->setHeaders(['Header #1', 'Header #2'])
            ->appendRow(['123', '456']);

        isSame(implode("\n", [
            '| Header #1 | Header #2 |',
            '|:----------|:----------|',
            '| 123       | 456       |',
            '',
        ]), $table->render());
    }

    public function testMultiRowTable()
    {
        $table = (new Table())
            ->setHeaders(['Header #1', 'Header #2'])
            ->appendRow(['123', '456'])
            ->appendRows([['789_1', '9871'], ['789_2', '']]);

        isSame(implode("\n", [
            '| Header #1 | Header #2 |',
            '|:----------|:----------|',
            '| 123       | 456       |',
            '| 789_1     | 9871      |',
            '| 789_2     |           |',
            '',
        ]), $table->render());
    }

    public function testAutoIndexDefault()
    {
        $table = (new Table())
            ->addAutoIndex()
            ->setHeaders(['Header #1', 'Header #2'])
            ->appendRow(['123', '456'])
            ->appendRows([['789_1', '9871'], ['789_2', '']]);

        isSame(implode("\n", [
            '| # | Header #1 | Header #2 |',
            '|--:|:----------|:----------|',
            '| 1 | 123       | 456       |',
            '| 2 | 789_1     | 9871      |',
            '| 3 | 789_2     |           |',
            '',
        ]), $table->render());
    }

    public function testAutoIndexCustom()
    {
        $table = (new Table())
            ->addAutoIndex('Index', 999)
            ->setHeaders(['Header #1', 'Header #2'])
            ->appendRow(['123', '456'])
            ->appendRows([['789_1', '9871'], ['789_2', '']]);

        isSame(implode("\n", [
            '| Index | Header #1 | Header #2 |',
            '|------:|:----------|:----------|',
            '|   999 | 123       | 456       |',
            '|  1000 | 789_1     | 9871      |',
            '|  1001 | 789_2     |           |',
            '',
        ]), $table->render());
    }

    public function testRemoveAutoIndex()
    {
        $table = (new Table())
            ->addAutoIndex('Index', 999)
            ->setHeaders(['Header #1', 'Header #2'])
            ->appendRow(['123', '456'])
            ->appendRows([['789_1', '9871'], ['789_2', '']]);

        isSame(implode("\n", [
            '| Index | Header #1 | Header #2 |',
            '|------:|:----------|:----------|',
            '|   999 | 123       | 456       |',
            '|  1000 | 789_1     | 9871      |',
            '|  1001 | 789_2     |           |',
            '',
        ]), $table->render());

        $table->removeAutoIndex();

        isSame(implode("\n", [
            '| Header #1 | Header #2 |',
            '|:----------|:----------|',
            '| 123       | 456       |',
            '| 789_1     | 9871      |',
            '| 789_2     |           |',
            '',
        ]), $table->render());
    }

    public function testAligments()
    {
        $table = (new Table())
            ->addAutoIndex('Index', 999)
            ->setAlignments([Table::ALIGN_CENTER, Table::ALIGN_RIGHT])
            ->setHeaders(['Header #1', 'Header #2'])
            ->appendRow(['123', '456'])
            ->appendRows([['789_1', '9871'], ['789_2', '']]);

        isSame(implode("\n", [
            '| Index | Header #1 | Header #2 |',
            '|------:|:---------:|----------:|',
            '|   999 |    123    |       456 |',
            '|  1000 |   789_1   |      9871 |',
            '|  1001 |   789_2   |           |',
            '',
        ]), $table->render());

        $table->removeAutoIndex();

        isSame(implode("\n", [
            '| Header #1 | Header #2 |',
            '|:---------:|----------:|',
            '|    123    |       456 |',
            '|   789_1   |      9871 |',
            '|   789_2   |           |',
            '',
        ]), $table->render());
    }
}
