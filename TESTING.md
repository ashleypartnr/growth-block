# Testing Guide

This document provides comprehensive testing documentation for the GreenGrowth Impact Showcase plugin.

## Table of Contents

- [Overview](#overview)
- [Quick Start](#quick-start)
- [PHP Testing](#php-testing)
- [JavaScript Testing](#javascript-testing)
- [Code Quality](#code-quality)
- [Continuous Integration](#continuous-integration)
- [Coverage Reports](#coverage-reports)
- [Writing Tests](#writing-tests)
- [Troubleshooting](#troubleshooting)

## Overview

The plugin uses a comprehensive testing strategy:

- **PHPUnit** for PHP unit and integration tests
- **Jest** for JavaScript unit tests
- **PHPCS** for PHP code standards
- **ESLint** for JavaScript code standards
- **GitHub Actions** for continuous integration

### Coverage Goals

- **PHP**: 70%+ overall, 90%+ for critical paths
- **JavaScript**: 70%+ overall, 90%+ for critical paths
- **Code Standards**: 100% compliance with WordPress Coding Standards

## Quick Start

### Install Dependencies

```bash
# PHP dependencies
composer install

# JavaScript dependencies
npm install
```

### Run All Tests

```bash
# Run everything
npm test

# Or separately
npm run test:js
composer test
```

## PHP Testing

### Setup

1. Install WordPress test suite:

```bash
bash bin/install-wp-tests.sh wordpress_test root 'password' localhost latest
```

Replace the database credentials with your local MySQL credentials.

2. Set environment variable (optional):

```bash
export WP_PHPUNIT__DIR=/tmp/wordpress-tests-lib
```

### Running Tests

```bash
# Run all PHP tests
composer test

# Run with coverage report
composer test:coverage

# Run specific test file
phpunit tests/test-projects-manager.php

# Run specific test method
phpunit --filter test_singleton_instance
```

### Test Files

- `tests/test-projects-manager.php` - Tests for Projects Manager singleton
- `tests/test-post-type.php` - Tests for CPT and taxonomy registration
- `tests/bootstrap.php` - PHPUnit bootstrap file

### Writing PHP Tests

```php
<?php
class Test_My_Feature extends WP_UnitTestCase {

    public function test_feature_works() {
        // Arrange
        $post_id = $this->factory->post->create([
            'post_type' => 'gg_project'
        ]);

        // Act
        $result = my_function( $post_id );

        // Assert
        $this->assertTrue( $result );
    }

    public function tearDown(): void {
        // Cleanup happens automatically
        parent::tearDown();
    }
}
```

## JavaScript Testing

### Setup

Dependencies are installed via npm:

```bash
npm install
```

### Running Tests

```bash
# Run all JavaScript tests
npm run test:js

# Run in watch mode (for development)
npm run test:js:watch

# Run with coverage
npm run test:js:coverage
```

### Test Files

- `src/__tests__/view.test.js` - Tests for Interactivity API implementation
- `tests/setup.js` - Jest global setup
- `tests/mocks/styleMock.js` - CSS/SCSS import mock

### Writing JavaScript Tests

```javascript
describe('Feature Name', () => {
    beforeEach(() => {
        // Setup DOM
        document.body.innerHTML = `
            <div class="my-component"></div>
        `;
    });

    test('should do something', () => {
        // Arrange
        const element = document.querySelector('.my-component');

        // Act
        const result = doSomething(element);

        // Assert
        expect(result).toBe(true);
    });
});
```

## Code Quality

### PHP Code Standards

```bash
# Check code standards
composer lint

# Auto-fix code standards
composer lint:fix

# Check specific file
vendor/bin/phpcs src/includes/class-projects-manager.php
```

Standards: WordPress Coding Standards (WordPress-Extra + WordPress-Docs)

### JavaScript Code Standards

```bash
# Check JavaScript code standards
npm run lint:js

# Auto-fix JavaScript code standards
npm run format
```

Standards: WordPress JavaScript Coding Standards (via @wordpress/scripts)

### CSS Code Standards

```bash
# Check CSS code standards
npm run lint:css
```

## Continuous Integration

Tests run automatically via GitHub Actions on:

- **Pull Requests** to main/develop branches
- **Pushes** to main/develop branches

### Workflow Jobs

1. **PHPUnit** - Tests on PHP 7.4, 8.0, 8.1, 8.2 with WP 6.9 and latest
2. **Jest** - JavaScript tests with coverage upload
3. **PHPCS** - PHP code standards check
4. **ESLint** - JavaScript code standards check

View workflow: `.github/workflows/tests.yml`

## Coverage Reports

### PHP Coverage

```bash
# Generate HTML coverage report
composer test:coverage

# View report
open coverage/index.html
```

### JavaScript Coverage

```bash
# Generate coverage report
npm run test:js:coverage

# View report
open coverage/lcov-report/index.html
```

### Coverage Badges

Coverage reports are automatically uploaded to Codecov on CI runs.

## Writing Tests

### Best Practices

1. **Test Behavior, Not Implementation**
   - Focus on what the code does, not how it does it
   - Test from the user's perspective

2. **One Assertion Per Test** (when possible)
   - Makes failures easier to diagnose
   - Tests remain focused and clear

3. **Use Descriptive Names**
   - Test names should describe what is being tested
   - Use the format: `test_<condition>_<expected_result>`

4. **Arrange-Act-Assert Pattern**
   ```php
   // Arrange - Set up test data
   $data = create_test_data();

   // Act - Execute the code being tested
   $result = function_under_test( $data );

   // Assert - Verify the result
   $this->assertEquals( $expected, $result );
   ```

5. **Clean Up After Tests**
   - Use tearDown() methods
   - Don't leave test data in the database
   - WP_UnitTestCase handles most cleanup automatically

6. **Mock External Dependencies**
   - Don't rely on network calls
   - Mock WordPress functions when needed
   - Use fixtures for consistent test data

### What to Test

**High Priority:**
- Public API methods
- User-facing functionality
- Data transformations
- Error handling
- Security (nonce verification, sanitization, escaping)

**Medium Priority:**
- Filter and action hooks
- Edge cases
- Performance-critical code

**Lower Priority:**
- Private methods (test via public API)
- Simple getters/setters
- Framework code

### Test Organization

```
tests/
├── bootstrap.php           # PHPUnit bootstrap
├── setup.js               # Jest setup
├── README.md              # Testing documentation
├── mocks/                 # Mock files
│   └── styleMock.js
├── test-projects-manager.php
├── test-post-type.php
└── test-...php            # Additional test files

src/
└── __tests__/             # JavaScript tests
    └── view.test.js
```

## Troubleshooting

### Common Issues

**"Class 'WP_UnitTestCase' not found"**

```bash
# Ensure WordPress test suite is installed
bash bin/install-wp-tests.sh wordpress_test root 'password' localhost latest

# Set environment variable
export WP_PHPUNIT__DIR=/tmp/wordpress-tests-lib
```

**"Cannot find module" (JavaScript)**

```bash
# Reinstall dependencies
rm -rf node_modules package-lock.json
npm install
```

**"Database connection failed"**

```bash
# Check MySQL is running
mysql -u root -p -e "SHOW DATABASES;"

# Recreate test database
mysql -u root -p -e "DROP DATABASE IF EXISTS wordpress_test; CREATE DATABASE wordpress_test;"
```

**PHPCS not finding standards**

```bash
# Install PHPCS with standards
composer install

# Verify standards are installed
vendor/bin/phpcs -i
```

### Debug Mode

**PHP Tests:**
```bash
# Run with verbose output
phpunit --debug

# Stop on first failure
phpunit --stop-on-failure
```

**JavaScript Tests:**
```bash
# Run in debug mode
node --inspect-brk node_modules/.bin/jest --runInBand

# Use browser debugger (Chrome)
chrome://inspect
```

## Resources

- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [WordPress PHPUnit Handbook](https://make.wordpress.org/core/handbook/testing/automated-testing/phpunit/)
- [Jest Documentation](https://jestjs.io/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [Testing Library](https://testing-library.com/)

## Getting Help

- Check existing tests for examples
- Review test output carefully - errors often indicate the exact problem
- Search the WordPress Coding Standards documentation
- Ask in the team Slack channel

---

**Happy Testing!** 🧪
