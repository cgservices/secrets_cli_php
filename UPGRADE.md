# PHP 8.4 Upgrade Guide

This document describes the changes made to upgrade the project from PHP 5.5+ to PHP 8.4, including the addition of PHPUnit testing, Docker support, and CI/CD pipeline.

## Overview of Changes

| Component | Before | After |
|-----------|--------|-------|
| PHP Version | >=5.5.0 | ^8.4 |
| PHPUnit | Not configured | 11.5 |
| Docker | Basic | Full testing environment |
| CI/CD | None | GitHub Actions |
| Makefile | Basic | Extended with test commands |

---

## 1. Composer Dependencies

### Updated `composer.json`

```json
{
    "require": {
        "php": "^8.4",
        "corneltek/cliframework": "^2.5",
        "jippi/vault-php-sdk": "^3.0",
        "vlucas/phpdotenv": "^5.6",
        "ext-json": "*"
    },
    "require-dev": {
        "phpunit/phpunit": "^11.5"
    }
}
```

### Key Changes:
- **PHP**: Upgraded from `>=5.5.0` to `^8.4`
- **vlucas/phpdotenv**: Upgraded from `2.4.0` to `^5.6` (major version change)
- **PHPUnit**: Added as dev dependency

### Regenerating Dependencies
The `composer.lock` file was removed and regenerated. Run:
```bash
make install
```

---

## 2. PHPUnit Configuration

### New File: `phpunit.xml`

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/11.5/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true"
         cacheDirectory=".phpunit.cache"
         beStrictAboutCoverageMetadata="true"
         beStrictAboutOutputDuringTests="false"
         failOnRisky="false"
         failOnWarning="true"
         displayDetailsOnTestsThatTriggerDeprecations="false"
         displayDetailsOnTestsThatTriggerWarnings="true">
    <testsuites>
        <testsuite name="SecretsCli Test Suite">
            <directory>tests</directory>
        </testsuite>
    </testsuites>
    <source>
        <include>
            <directory>src</directory>
        </include>
    </source>
</phpunit>
```

### Configuration Notes:
- `beStrictAboutOutputDuringTests="false"` - Disabled because `Application::secrets_key()` outputs to stdout
- `failOnRisky="false"` - Allows tests that produce output
- `displayDetailsOnTestsThatTriggerDeprecations="false"` - Suppresses deprecation details from third-party libraries

---

## 3. Test Suite

### Test Structure
```
tests/
└── Unit/
    ├── ApplicationTest.php
    ├── CommandTest.php
    ├── VaultTest.php
    ├── Auth/
    │   └── GithubTest.php
    ├── Command/
    │   ├── InitCommandTest.php
    │   ├── PoliciesCommandTest.php
    │   ├── PullCommandTest.php
    │   ├── PushCommandTest.php
    │   └── ReadCommandTest.php
    └── Vault/
        └── ServiceFactoryTest.php
```

### Test Coverage
| Class | Tests | Notes |
|-------|-------|-------|
| Application | 4 | Constants, secrets_key() variants |
| Command | 2 | Class existence, inheritance |
| Vault | 3 | Instantiation, seal() exception |
| Auth\Github | 2 | Instantiation with/without client |
| Vault\ServiceFactory | 4 | Service retrieval, invalid service |
| Command\InitCommand | 3 | Class, inheritance, brief() |
| Command\PullCommand | 3 | Class, inheritance, brief() |
| Command\PushCommand | 3 | Class, inheritance, brief() |
| Command\ReadCommand | 3 | Class, inheritance, brief() |
| Command\PoliciesCommand | 1 | Placeholder (class is commented out) |

### Running Tests
```bash
# Via Docker (recommended)
make test

# With filter
make test-filter FILTER=ApplicationTest

# With coverage
make test-coverage
```

---

## 4. Docker Configuration

### Updated `Dockerfile`
```dockerfile
FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zip \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY composer.json ./
RUN composer update --no-interaction --no-scripts --no-autoloader --prefer-dist
COPY . .
RUN composer dump-autoload --optimize

CMD ["./vendor/bin/phpunit"]
```

### New File: `.dockerignore`
Excludes unnecessary files from the Docker build context.

---

## 5. Makefile Commands

| Command | Description |
|---------|-------------|
| `make help` | Display all available commands |
| `make build` | Build the Docker image |
| `make install` | Install composer dependencies |
| `make update` | Update composer dependencies |
| `make test` | Run PHPUnit tests in Docker |
| `make test-coverage` | Run tests with coverage report |
| `make test-filter FILTER=<name>` | Run specific test |
| `make shell` | Open shell in container |
| `make composer CMD="<cmd>"` | Run composer command |
| `make lint` | Run PHP linting |
| `make clean` | Remove Docker image |

---

## 6. GitHub Actions CI/CD

### Workflow File: `.github/workflows/ci.yml`

Two jobs run on every push/PR to `main`, `master`, or `develop`:

#### Job 1: `test` (Native PHP)
1. Sets up PHP 8.4 with extensions
2. Validates `composer.json`
3. Caches dependencies
4. Runs `composer update`
5. Executes PHPUnit
6. Generates coverage report
7. Uploads to Codecov (optional)

#### Job 2: `docker`
1. Builds Docker image
2. Runs tests inside container

### Enabling CI
Push to GitHub and the workflow runs automatically. Check the **Actions** tab.

---

## 7. Known Issues & Deprecations

### Third-Party Library Deprecations
The following packages trigger PHP 8.4 deprecation warnings:

| Package | Issue |
|---------|-------|
| `corneltek/cliframework` | Implicitly nullable parameters |
| `guzzlehttp/guzzle` (6.x) | Implicitly nullable parameters |
| `guzzlehttp/promises` | Implicitly nullable parameters |
| `jippi/vault-php-sdk` | Implicitly nullable parameters, abandoned |

**Impact**: These are warnings only. All functionality works correctly.

**Recommendation**: Monitor for updated versions or consider alternative packages for `jippi/vault-php-sdk` (marked as abandoned).

### Application Code Notes
- `Application::secrets_key('')` calls `die()` instead of throwing an exception
- Consider refactoring to throw exceptions for better testability

---

## 8. Files Added/Modified

### New Files
- `phpunit.xml` - PHPUnit configuration
- `.dockerignore` - Docker build exclusions
- `.github/workflows/ci.yml` - GitHub Actions workflow
- `tests/Unit/*.php` - All test files

### Modified Files
- `composer.json` - Updated dependencies
- `Dockerfile` - PHP 8.4 with composer
- `Makefile` - Extended with test commands
- `.gitignore` - Added PHPUnit cache entries

### Removed Files
- `composer.lock` - Regenerated with new dependencies

---

## 9. Quick Start for New Developers

```bash
# Clone the repository
git clone <repository-url>
cd secrets_cli_php

# Build Docker image
make build

# Install dependencies
make install

# Run tests
make test

# Open shell for debugging
make shell
```

---

## 10. Future Recommendations

1. **Replace abandoned packages**: `jippi/vault-php-sdk` is abandoned
2. **Upgrade Guzzle**: Consider upgrading to Guzzle 7.x when dependencies allow
3. **Add integration tests**: Current tests are unit tests only
4. **Refactor die() calls**: Replace with exceptions for better error handling
5. **Enable PoliciesCommand**: Currently commented out in source
6. **Add code coverage requirements**: Set minimum coverage thresholds
