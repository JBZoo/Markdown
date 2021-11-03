# JBZoo / Markdown

[![Coverage Status](https://coveralls.io/repos/JBZoo/Markdown/badge.svg)](https://coveralls.io/github/JBZoo/Markdown)    [![Psalm Coverage](https://shepherd.dev/github/JBZoo/Markdown/coverage.svg)](https://shepherd.dev/github/JBZoo/Markdown)    
[![Stable Version](https://poser.pugx.org/jbzoo/markdown/version)](https://packagist.org/packages/jbzoo/markdown)    [![Latest Unstable Version](https://poser.pugx.org/jbzoo/markdown/v/unstable)](https://packagist.org/packages/jbzoo/markdown)    [![Dependents](https://poser.pugx.org/jbzoo/markdown/dependents)](https://packagist.org/packages/jbzoo/markdown/dependents?order_by=downloads)    [![GitHub Issues](https://img.shields.io/github/issues/jbzoo/markdown)](https://github.com/JBZoo/Markdown/issues)    [![Total Downloads](https://poser.pugx.org/jbzoo/markdown/downloads)](https://packagist.org/packages/jbzoo/markdown/stats)    [![GitHub License](https://img.shields.io/github/license/jbzoo/markdown)](https://github.com/JBZoo/Markdown/blob/master/LICENSE)



### Installing

```sh
composer require jbzoo/markdown
```


### Usage

#### Rendering Table

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


#### Rendering other tags
```php
<?php declare(strict_types=1);

use JBZoo\Markdown\Markdown;

// Page Navigation
Markdown::title('Page Name', 1));   // # Page Name\n
Markdown::title('Title', 2));       // ## Title\n
Markdown::title('Sub Title', 3));   // ### Sub Title\n

// [Google](https://google.com)
Markdown::url('Google', 'https://google.com');

// [![Status](https://travis-ci.org/)](https://travis-ci.org/Status)
Markdown::badge('Status', 'https://travis-ci.org/', 'https://travis-ci.org/Status'); //

// ![Logo](https://google.com/example.jpg)
Markdown::image('https://google.com/example.jpg', 'Logo');

// > Quote LIne 1
// > Quote LIne 2
// > Quote LIne 3
Markdown::blockquote(["Quote LIne 1\nQuote LIne 2\nQuote LIne 3"]);
Markdown::blockquote(['Quote LIne 1', 'Quote LIne 2', 'Quote LIne 3'])

// <details>
//   <summary>Quote Text</summary>
//   
//   Some hidden text
//   
// </details>
Markdown::spoiler('Quote Text', 'Some hidden text');

// ```php
// <?php
// echo 1;
// 
// ```
Markdown::code("<?php\necho 1;\n", 'php');

```


## Unit tests and check code style
```sh
make update
make test-all
```


### License

MIT
