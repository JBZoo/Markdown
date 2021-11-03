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

/**
 * Class Table
 * @package JBZoo\Markdown
 */
class Table
{
    public const ALIGN_LEFT   = 'Left';
    public const ALIGN_CENTER = 'Center';
    public const ALIGN_RIGHT  = 'Right';

    public const CELL_MIN_LENGTH = 1;

    /**
     * @var string[]
     */
    private $headers = [];

    /**
     * @var string[]
     */
    private $alignments = [];

    /**
     * @var array[]
     */
    private $rows = [];

    /**
     * @var array
     */
    private $autoIndexConfig = [];

    /**
     * @param string $headerName
     * @param int    $startIndex
     * @return $this
     */
    public function addAutoIndex(string $headerName = '#', int $startIndex = 1): self
    {
        $this->autoIndexConfig = [
            'header_name' => $headerName,
            'start_index' => $startIndex
        ];

        return $this;
    }

    /**
     * @return $this
     */
    public function removeAutoIndex(): self
    {
        $this->autoIndexConfig = [];
        return $this;
    }

    /**
     * @param array $newHeaders
     * @return $this
     */
    public function setHeaders(array $newHeaders): self
    {
        $this->headers = $newHeaders;
        return $this;
    }

    /**
     * @param array $alignments
     * @return $this
     */
    public function setAlignments(array $alignments): self
    {
        $this->alignments = $alignments;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function appendRow(array $row): self
    {
        $this->rows[] = $row;
        return $this;
    }

    /**
     * @param array $rows
     * @return $this
     */
    public function appendRows(array $rows): self
    {
        $this->rows = \array_merge($this->rows, $rows);
        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $actualHeaders = $this->headers;
        $actualRows = $this->rows;

        if ($this->isAutoIndexEnabled()) {
            array_unshift($actualHeaders, $this->autoIndexConfig['header_name']);

            $indexStart = $this->autoIndexConfig['start_index'];
            foreach ($actualRows as $index => $actualRow) {
                array_unshift($actualRow, $indexStart);
                $actualRows[$index] = $actualRow;

                $indexStart++;
            }
        }

        if (count($actualRows) === 0 && count($actualHeaders) === 0) {
            return '';
        }

        $widths = self::calculateWidths($actualHeaders, $actualRows);
        if (count($actualHeaders) === 0) {
            return $this->renderRows($widths, $actualRows);
        }

        return $this->renderHeaders($widths, $actualHeaders) . $this->renderRows($widths, $actualRows);
    }

    /**
     * @param array   $actualHeaders
     * @param array[] $actualRows
     * @return array
     */
    protected static function calculateWidths(array $actualHeaders, array $actualRows): array
    {
        $widths = [];

        foreach (\array_merge([$actualHeaders], $actualRows) as $row) {
            $max = \count($row);

            for ($colIndex = 0; $colIndex < $max; $colIndex++) {
                $iWidth = \strlen((string)$row[$colIndex]);

                if ((!\array_key_exists($colIndex, $widths)) || $iWidth > $widths[$colIndex]) {
                    $widths[$colIndex] = $iWidth;
                }
            }
        }

        // all columns must be at least 3 wide for the markdown to work
        return \array_map(static function (int $width): int {
            return $width >= self::CELL_MIN_LENGTH ? $width : self::CELL_MIN_LENGTH;
        }, $widths);
    }

    /**
     * @param int[] $widths
     * @param array $actualHeaders
     * @return string
     * @throws \Exception
     */
    protected function renderHeaders(array $widths, array $actualHeaders): string
    {
        $result = '| ';

        foreach (\array_keys($actualHeaders) as $colIndex) {
            $result .= self::renderCell(
                $actualHeaders[$colIndex],
                $this->getColumnAlign($colIndex),
                $widths[$colIndex]
            );

            $result .= ' | ';
        }

        return \rtrim($result, ' ') . \PHP_EOL . $this->renderAlignments($widths) . \PHP_EOL;
    }

    /**
     * @param int[] $widths
     * @param array $actualRows
     * @return string
     * @throws \Exception
     */
    protected function renderRows(array $widths, array $actualRows): string
    {
        $result = '';

        foreach ($actualRows as $row) {
            $result .= '| ';

            /** @var string $colIndex */
            foreach (\array_keys($row) as $colIndex) {
                $result .= self::renderCell(
                    (string)$row[$colIndex],
                    $this->getColumnAlign($colIndex),
                    $widths[$colIndex]
                );

                $result .= ' | ';
            }

            $result = \rtrim($result, ' ') . \PHP_EOL;
        }

        return $result;
    }

    /**
     * @param string $contents
     * @param string $alignment
     * @param int    $width
     * @return string
     */
    protected static function renderCell(string $contents, string $alignment, int $width): string
    {
        $map = [
            self::ALIGN_LEFT   => \STR_PAD_RIGHT,
            self::ALIGN_CENTER => \STR_PAD_BOTH,
            self::ALIGN_RIGHT  => \STR_PAD_LEFT,
        ];

        $padType = $map[$alignment] ?? \STR_PAD_LEFT;

        return \str_pad($contents, $width, ' ', $padType);
    }

    /**
     * @param int[] $widths
     * @return string
     */
    protected function renderAlignments(array $widths): string
    {
        $row = '|';

        foreach ($widths as $colIndex => $colIndexValue) {
            $cell = \str_repeat('-', $colIndexValue + 2);
            $align = $this->getColumnAlign($colIndex);

            if ($align === self::ALIGN_CENTER) {
                $cell = ':' . \substr($cell, 2) . ':';
            }

            if ($align === self::ALIGN_RIGHT) {
                $cell = \substr($cell, 1) . ':';
            }

            if ($align === self::ALIGN_LEFT) {
                $cell = ':' . \substr($cell, 1);
            }

            $row .= $cell . '|';
        }

        return $row;
    }

    /**
     * @param string|int $colIndex
     * @return string
     */
    protected function getColumnAlign($colIndex): string
    {
        $validAligns = [self::ALIGN_LEFT, self::ALIGN_CENTER, self::ALIGN_RIGHT];
        $actualAlignments = $this->alignments;

        if ($this->isAutoIndexEnabled()) {
            array_unshift($actualAlignments, self::ALIGN_RIGHT);
        }

        $result = $actualAlignments[$colIndex] ?? self::ALIGN_LEFT;

        if (!\in_array($result, $validAligns, true)) {
            throw new \Exception("Invalid alignment for column index {$colIndex}: {$result}");
        }

        return $result;
    }

    /**
     * @return bool
     */
    private function isAutoIndexEnabled(): bool
    {
        return count($this->autoIndexConfig) > 0;
    }
}
