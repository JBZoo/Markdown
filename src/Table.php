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

class Table
{
    public const ALIGN_LEFT   = 'Left';
    public const ALIGN_CENTER = 'Center';
    public const ALIGN_RIGHT  = 'Right';

    /** @var string[] */
    private array $headers = [];

    /** @var string[] */
    private array $alignments = [];

    /** @var array[] */
    private array $rows = [];

    private array $autoIndexConfig = [];

    private int $minCellLength = 1;

    /**
     * @return $this
     */
    public function addAutoIndex(string $headerName = '#', int $startIndex = 1): self
    {
        $this->autoIndexConfig = [
            'header_name' => $headerName,
            'start_index' => $startIndex,
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
     * @return $this
     */
    public function setMinCellLength(int $minLength): self
    {
        $this->minCellLength = $minLength;

        return $this;
    }

    /**
     * @return $this
     */
    public function setHeaders(array $newHeaders): self
    {
        $this->headers = $newHeaders;

        return $this;
    }

    /**
     * @return $this
     */
    public function setAlignments(array $alignments): self
    {
        $this->alignments = $alignments;

        return $this;
    }

    /**
     * @return $this
     */
    public function appendRow(array $row): self
    {
        $this->rows[] = $row;

        return $this;
    }

    /**
     * @return $this
     */
    public function appendRows(array $rows): self
    {
        $this->rows = \array_merge($this->rows, $rows);

        return $this;
    }

    public function render(): string
    {
        $actualHeaders = $this->headers;
        $actualRows    = $this->rows;

        if ($this->isAutoIndexEnabled()) {
            \array_unshift($actualHeaders, $this->autoIndexConfig['header_name']);

            $indexStart = $this->autoIndexConfig['start_index'];

            foreach ($actualRows as $index => $actualRow) {
                \array_unshift($actualRow, $indexStart);
                $actualRows[$index] = $actualRow;

                $indexStart++;
            }
        }

        if (\count($actualRows) === 0 && \count($actualHeaders) === 0) {
            return '';
        }

        $widths = $this->calculateWidths($actualHeaders, $actualRows);
        if (\count($actualHeaders) === 0) {
            return $this->renderRows($widths, $actualRows);
        }

        return $this->renderHeaders($widths, $actualHeaders) . $this->renderRows($widths, $actualRows);
    }

    /**
     * @param array[] $actualRows
     */
    protected function calculateWidths(array $actualHeaders, array $actualRows): array
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
        return \array_map(fn (int $width): int => \max($width, $this->minCellLength), $widths);
    }

    /**
     * @param  int[]      $widths
     * @throws \Exception
     */
    protected function renderHeaders(array $widths, array $actualHeaders): string
    {
        $result = '| ';

        foreach (\array_keys($actualHeaders) as $colIndex) {
            $result .= self::renderCell(
                $actualHeaders[$colIndex],
                $this->getColumnAlign($colIndex),
                $widths[$colIndex],
            );

            $result .= ' | ';
        }

        return \rtrim($result, ' ') . \PHP_EOL . $this->renderAlignments($widths) . \PHP_EOL;
    }

    /**
     * @param  int[]      $widths
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
                    $widths[$colIndex],
                );

                $result .= ' | ';
            }

            $result = \rtrim($result, ' ') . \PHP_EOL;
        }

        return $result;
    }

    /**
     * @param int[] $widths
     */
    protected function renderAlignments(array $widths): string
    {
        $row = '|';

        foreach ($widths as $colIndex => $colIndexValue) {
            $cell  = \str_repeat('-', $colIndexValue + 2);
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
     * @param int|string $colIndex
     */
    protected function getColumnAlign($colIndex): string
    {
        $validAligns      = [self::ALIGN_LEFT, self::ALIGN_CENTER, self::ALIGN_RIGHT];
        $actualAlignments = $this->alignments;

        if ($this->isAutoIndexEnabled()) {
            \array_unshift($actualAlignments, self::ALIGN_RIGHT);
        }

        $result = $actualAlignments[$colIndex] ?? self::ALIGN_LEFT;

        if (!\in_array($result, $validAligns, true)) {
            throw new Exception("Invalid alignment for column index {$colIndex}: {$result}");
        }

        return $result;
    }

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

    private function isAutoIndexEnabled(): bool
    {
        return \count($this->autoIndexConfig) > 0;
    }
}
