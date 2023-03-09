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

namespace JBZoo\PHPUnit;

use JBZoo\Markdown\Table;

class MarkdownTableTest extends PHPUnit
{
    public function testEmptyOne(): void
    {
        $table = (new Table());
        isSame('', $table->render());
    }

    public function testOneAndOne(): void
    {
        $table = (new Table())
            ->setHeaders(['Header #1'])
            ->appendRow(['123']);

        isSame(\implode("\n", [
            '| Header #1 |',
            '|:----------|',
            '| 123       |',
            '',
        ]), $table->render());
    }

    public function testNoRowsTable(): void
    {
        $table = (new Table())
            ->setHeaders(['Header #1', 'Header #2']);

        isSame(\implode("\n", [
            '| Header #1 | Header #2 |',
            '|:----------|:----------|',
            '',
        ]), $table->render());
    }

    public function testNoHeadersTable(): void
    {
        $table = (new Table())
            ->appendRow(['123', 456]);

        isSame("| 123 | 456 |\n", $table->render());
    }

    public function testOneRowTable(): void
    {
        $table = (new Table())
            ->setHeaders(['Header #1', 'Header #2'])
            ->appendRow(['123', '456']);

        isSame(\implode("\n", [
            '| Header #1 | Header #2 |',
            '|:----------|:----------|',
            '| 123       | 456       |',
            '',
        ]), $table->render());
    }

    public function testMultiRowTable(): void
    {
        $table = (new Table())
            ->setHeaders(['Header #1', 'Header #2'])
            ->appendRow(['123', '456'])
            ->appendRows([['789_1', '9871'], ['789_2', '']]);

        isSame(\implode("\n", [
            '| Header #1 | Header #2 |',
            '|:----------|:----------|',
            '| 123       | 456       |',
            '| 789_1     | 9871      |',
            '| 789_2     |           |',
            '',
        ]), $table->render());
    }

    public function testAutoIndexDefault(): void
    {
        $table = (new Table())
            ->addAutoIndex()
            ->setHeaders(['Header #1', 'Header #2'])
            ->appendRow(['123', '456'])
            ->appendRows([['789_1', '9871'], ['789_2', '']]);

        isSame(\implode("\n", [
            '| # | Header #1 | Header #2 |',
            '|--:|:----------|:----------|',
            '| 1 | 123       | 456       |',
            '| 2 | 789_1     | 9871      |',
            '| 3 | 789_2     |           |',
            '',
        ]), $table->render());
    }

    public function testAutoIndexCustom(): void
    {
        $table = (new Table())
            ->addAutoIndex('Index', 999)
            ->setHeaders(['Header #1', 'Header #2'])
            ->appendRow(['123', '456'])
            ->appendRows([['789_1', '9871'], ['789_2', '']]);

        isSame(\implode("\n", [
            '| Index | Header #1 | Header #2 |',
            '|------:|:----------|:----------|',
            '|   999 | 123       | 456       |',
            '|  1000 | 789_1     | 9871      |',
            '|  1001 | 789_2     |           |',
            '',
        ]), $table->render());
    }

    public function testRemoveAutoIndex(): void
    {
        $table = (new Table())
            ->addAutoIndex('Index', 999)
            ->setHeaders(['Header #1', 'Header #2'])
            ->appendRow(['123', '456'])
            ->appendRows([['789_1', '9871'], ['789_2', '']]);

        isSame(\implode("\n", [
            '| Index | Header #1 | Header #2 |',
            '|------:|:----------|:----------|',
            '|   999 | 123       | 456       |',
            '|  1000 | 789_1     | 9871      |',
            '|  1001 | 789_2     |           |',
            '',
        ]), $table->render());

        $table->removeAutoIndex();

        isSame(\implode("\n", [
            '| Header #1 | Header #2 |',
            '|:----------|:----------|',
            '| 123       | 456       |',
            '| 789_1     | 9871      |',
            '| 789_2     |           |',
            '',
        ]), $table->render());
    }

    public function testAligments(): void
    {
        $table = (new Table())
            ->addAutoIndex('Index', 999)
            ->setAlignments([Table::ALIGN_CENTER, Table::ALIGN_RIGHT])
            ->setHeaders(['Header #1', 'Header #2'])
            ->appendRow(['123', '456'])
            ->appendRows([['789_1', '9871'], ['789_2', '']]);

        isSame(\implode("\n", [
            '| Index | Header #1 | Header #2 |',
            '|------:|:---------:|----------:|',
            '|   999 |    123    |       456 |',
            '|  1000 |   789_1   |      9871 |',
            '|  1001 |   789_2   |           |',
            '',
        ]), $table->render());

        $table->removeAutoIndex();

        isSame(\implode("\n", [
            '| Header #1 | Header #2 |',
            '|:---------:|----------:|',
            '|    123    |       456 |',
            '|   789_1   |      9871 |',
            '|   789_2   |           |',
            '',
        ]), $table->render());
    }

    public function testMinimalLength(): void
    {
        $table = (new Table())
            ->addAutoIndex()
            ->setMinCellLength(5)
            ->setHeaders(['Header #1', 'Header #2'])
            ->appendRow(['123', '456'])
            ->appendRows([['789_1', '9871'], ['789_2', '']]);

        isSame(\implode("\n", [
            '|     # | Header #1 | Header #2 |',
            '|------:|:----------|:----------|',
            '|     1 | 123       | 456       |',
            '|     2 | 789_1     | 9871      |',
            '|     3 | 789_2     |           |',
            '',
        ]), $table->render());
    }
}
