# GreenGrowth Impact Showcase - Testing

This directory contains the test suite for the GreenGrowth Impact Showcase plugin.

## Test Structure

- `tests/` - PHP unit tests (PHPUnit)
- `src/__tests__/` - JavaScript tests (Jest)
- `tests/bootstrap.php` - PHPUnit bootstrap file
- `tests/setup.js` - Jest setup file
- `tests/mocks/` - Mock files for testing

## Setup

### PHP Tests (PHPUnit)

1. Install PHP dependencies:
```bash
composer install
```

2. Set up WordPress test environment:
```bash
# Set the path to your WordPress test suite
export WP_PHPUNIT__DIR=/path/to/wordpress-develop/tests/phpunit
```

3. Run tests:
```bash
# Run all tests
composer test

# Run tests with coverage
composer test:coverage

# Run specific test file
phpunit tests/test-projects-manager.php
```

### JavaScript Tests (Jest)

1. Install JavaScript dependencies:
```bash
npm install
```

2. Run tests:
```bash
# Run all tests
npm run test:js

# Run tests in watch mode
npm run test:js:watch

# Run tests with coverage
npm run test:js:coverage
```

### Run All Tests

```bash
npm test
```

## Writing Tests

### PHP Tests

PHP tests use PHPUnit and WordPress's test framework. Tests should:

- Extend `WP_UnitTestCase`
- Use WordPress factory methods for creating test data
- Clean up after themselves (handled automatically by `WP_UnitTestCase`)
- Test one thing per method
- Have descriptive method names starting with `test_`

Example:
```php
class Test_Example extends WP_UnitTestCase {
    public function test_something_works() {
        $result = do_something();
        $this->assertTrue( $result );
    }
}
```

### JavaScript Tests

JavaScript tests use Jest and Testing Library. Tests should:

- Mock WordPress globals (handled in `tests/setup.js`)
- Test user interactions and behaviors
- Focus on functionality, not implementation details
- Use descriptive test names

Example:
```javascript
describe('Feature', () => {
    test('should do something', () => {
        const result = doSomething();
        expect(result).toBe(true);
    });
});
```

## Coverage Goals

- **PHP**: Aim for 70%+ coverage
- **JavaScript**: Aim for 70%+ coverage
- **Critical paths**: 90%+ coverage (filtering, infinite scroll, data fetching)

## Continuous Integration

Tests run automatically on:
- Pull requests
- Main branch commits
- Pre-release builds

## Troubleshooting

### PHP Tests

**"Class 'WP_UnitTestCase' not found"**
- Ensure `WP_PHPUNIT__DIR` is set correctly
- Verify WordPress test suite is installed

**Database connection errors**
- Check your `wp-tests-config.php` settings
- Ensure test database exists and is accessible

### JavaScript Tests

**Module not found errors**
- Run `npm install` to ensure all dependencies are installed
- Check file paths in import statements

**WordPress globals undefined**
- Verify `tests/setup.js` is being loaded
- Check Jest configuration in `jest.config.js`

## Resources

- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [WordPress Plugin Unit Tests](https://make.wordpress.org/core/handbook/testing/automated-testing/phpunit/)
- [Jest Documentation](https://jestjs.io/docs/getting-started)
- [Testing Library](https://testing-library.com/docs/)
