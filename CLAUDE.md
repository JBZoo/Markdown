# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

JBZoo Markdown is a PHP library that provides tools for rendering markdown text from PHP code. The library focuses on two main components:

- **Markdown class**: Static methods for generating common markdown elements (links, titles, images, badges, blockquotes, spoilers, code blocks)
- **Table class**: Fluent API for creating formatted markdown tables with auto-indexing, alignments, and customizable rendering

## Architecture

The codebase is minimal and well-structured:

- `src/Markdown.php` - Main utility class with static methods for markdown generation
- `src/Table.php` - Table builder with fluent interface and alignment support
- `src/Exception.php` - Custom exception class
- `tests/` - PHPUnit tests covering all functionality

Key dependencies:
- PHP 8.2+ (strict typing throughout)
- `jbzoo/utils` for string utilities
- `jbzoo/toolbox-dev` for development tools (PHPUnit, linters, etc.)

## Common Commands

### Dependencies and Setup
```bash
make update          # Install/update all dependencies via Composer
```

### Testing
```bash
make test           # Run PHPUnit tests
make test-all       # Run all tests and code quality checks
```

### Code Quality
```bash
make codestyle      # Run all linters and code style checks
make test-phpcs     # PHP CodeSniffer (PSR-12)
make test-phpstan   # Static analysis
make test-psalm     # Psalm static analysis
```

### Development Workflow
The project uses JBZoo's standard toolchain via `jbzoo/toolbox-dev`. All commands are available through the Makefile which includes the toolbox's init.Makefile.

## Testing Strategy

Tests are organized by component:
- `MarkdownTest.php` - Tests all static methods in Markdown class
- `MarkdownTableTest.php` - Tests Table class functionality
- `MarkdownPackageTest.php` - Package-level tests

The test suite covers edge cases like empty inputs, different alignment options, auto-indexing, and various markdown syntax scenarios.