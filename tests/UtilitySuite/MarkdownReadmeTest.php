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

/**
 * Class MarkdownReadmeTest
 *
 * @package JBZoo\PHPUnit
 */
class MarkdownReadmeTest extends AbstractReadmeTest
{
    /**
     * @var string
     */
    protected $packageName = 'Markdown';

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->params['travis'] = false;
    }
}
