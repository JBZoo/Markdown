# JBZoo / Markdown

[![CI](https://github.com/JBZoo/Markdown/actions/workflows/main.yml/badge.svg?branch=master)](https://github.com/JBZoo/Markdown/actions/workflows/main.yml?query=branch%3Amaster)    [![Coverage Status](https://coveralls.io/repos/github/JBZoo/Markdown/badge.svg?branch=master)](https://coveralls.io/github/JBZoo/Markdown?branch=master)    [![Psalm Coverage](https://shepherd.dev/github/JBZoo/Markdown/coverage.svg)](https://shepherd.dev/github/JBZoo/Markdown)    [![Psalm Level](https://shepherd.dev/github/JBZoo/Markdown/level.svg)](https://shepherd.dev/github/JBZoo/Markdown)    [![CodeFactor](https://www.codefactor.io/repository/github/jbzoo/markdown/badge)](https://www.codefactor.io/repository/github/jbzoo/markdown/issues)
[![Stable Version](https://poser.pugx.org/jbzoo/markdown/version)](https://packagist.org/packages/jbzoo/markdown/)    [![Total Downloads](https://poser.pugx.org/jbzoo/markdown/downloads)](https://packagist.org/packages/jbzoo/markdown/stats)    [![Dependents](https://poser.pugx.org/jbzoo/markdown/dependents)](https://packagist.org/packages/jbzoo/markdown/dependents?order_by=downloads)    [![GitHub License](https://img.shields.io/github/license/jbzoo/markdown)](https://github.com/JBZoo/Markdown/blob/master/LICENSE)

Tools to render markdown text from PHP code. This library provides a simple and fluent API for generating markdown elements programmatically, including tables with advanced formatting options.

## Features

- **Markdown Elements**: Generate links, titles, images, badges, blockquotes, spoilers, and code blocks
- **Advanced Tables**: Create markdown tables with auto-indexing, custom alignments, and flexible rendering
- **Type Safety**: Full PHP 8.2+ compatibility with strict typing
- **Fluent API**: Chainable methods for intuitive table building
- **Zero Dependencies**: Lightweight with minimal external requirements

## Requirements

- PHP 8.2 or higher
- Composer

## Installing

```sh
composer require jbzoo/markdown
```


## Usage

### Table Generation

```php
<?php declare(strict_types=1);

use JBZoo\Markdown\Table;

echo (new Table())
    ->addAutoIndex('Index', 999)
    ->setHeaders(['Header #1', 'Header #2'])
    ->setAlignments([Table::ALIGN_CENTER, Table::ALIGN_RIGHT])
    ->appendRow(['123', '456'])
    ->appendRows([
        ['789_1', '9871'],
        ['789_2', '']
    ])
    ->render();
```

Result

```
| Index | Header #1 | Header #2 |
|------:|:---------:|----------:|
|   999 |    123    |       456 |
|  1000 |   789_1   |      9871 |
|  1001 |   789_2   |           |
```

<details>
  <summary>See Live Example</summary>

  | Index | Header #1 | Header #2 |
  |------:|:---------:|----------:|
  |   999 |    123    |       456 |
  |  1000 |   789_1   |      9871 |
  |  1001 |   789_2   |           |

</details>


### Markdown Elements

```php
<?php declare(strict_types=1);

use JBZoo\Markdown\Markdown;

// Page Navigation
echo Markdown::title('Page Name', 1);    // # Page Name\n
echo Markdown::title('Title', 2);        // ## Title\n
echo Markdown::title('Sub Title', 3);    // ### Sub Title\n

// Links
echo Markdown::url('Google', 'https://google.com');
// Output: [Google](https://google.com)

// Badges
echo Markdown::badge('Status', 'https://travis-ci.org/badge.svg', 'https://travis-ci.org/');
// Output: [![Status](https://travis-ci.org/badge.svg)](https://travis-ci.org/)

// Images
echo Markdown::image('https://example.com/logo.jpg', 'Logo');
// Output: ![Logo](https://example.com/logo.jpg)

// Blockquotes
echo Markdown::blockquote(['Quote Line 1', 'Quote Line 2', 'Quote Line 3']);
// Output:
// > Quote Line 1
// > Quote Line 2
// > Quote Line 3

// Spoiler (collapsible content)
echo Markdown::spoiler('Click to expand', 'Hidden content here');
// Output:
// <details>
//   <summary>Click to expand</summary>
//
//   Hidden content here
//
// </details>

// Code blocks
echo Markdown::code("<?php\necho 'Hello World';\n", 'php');
// Output:
// ```php
// <?php
// echo 'Hello World';
//
// ```
```

### Table Features

The Table class supports various advanced features:

```php
<?php declare(strict_types=1);

use JBZoo\Markdown\Table;

// Basic table
$table = new Table();
$table->setHeaders(['Name', 'Age', 'City'])
      ->appendRow(['John', '25', 'New York'])
      ->appendRow(['Jane', '30', 'London']);

echo $table->render();

// Table with alignments
$table = new Table();
$table->setHeaders(['Left', 'Center', 'Right'])
      ->setAlignments([Table::ALIGN_LEFT, Table::ALIGN_CENTER, Table::ALIGN_RIGHT])
      ->appendRow(['Text', 'Text', 'Text']);

echo $table->render();

// Table with auto-indexing
$table = new Table();
$table->addAutoIndex('#', 1)
      ->setHeaders(['Item', 'Price'])
      ->appendRows([
          ['Apple', '$1.00'],
          ['Banana', '$0.50'],
          ['Orange', '$0.75']
      ]);

echo $table->render();
```


## Development

### Setup
```sh
make update          # Install/update dependencies
```

### Testing
```sh
make test           # Run PHPUnit tests
make test-all       # Run all tests and code quality checks
make codestyle      # Run linters and code style checks
```

### Available Make Targets
Run `make help` to see all available commands including:
- Code quality tools (PHPStan, Psalm, PHPCS, etc.)
- Performance testing
- Report generation
- Build tools

## License

MIT
