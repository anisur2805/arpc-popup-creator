# Load More Posts by Ajax

## Coding Standards

This project uses [WordPress Coding Standards (WPCS)](https://github.com/WordPress/WordPress-Coding-Standards) to ensure code quality and consistency.

### Installation

1. If you don't have Composer installed, download and install it from [getcomposer.org](https://getcomposer.org/download/).
2. Install the project dependencies, including WPCS:
   ```bash
   composer install
   ```

### Usage

#### Check for Violations

To check your code for any coding standards violations, run the following command:

```bash
composer lint
```

This will display a list of any issues found.

#### Automatically Fix Violations

To automatically fix many common coding standards violations, run:

```bash
composer format
```

It's recommended to run `composer lint` again after formatting to see if any issues remain that require manual fixing.
