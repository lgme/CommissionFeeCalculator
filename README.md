# Fee Calculator Application

## Requirements
- PHP 8.1 or higher

## Autoloading
This project uses PSR-4 autoloading with Composer. The autoloading configuration is defined in the `composer.json` file.

## Packages
- **guzzlehttp/guzzle**: PHP HTTP client used to make requests to exchange rates API.
- **php-di/php-di**: DI container for PHP used to manage and inject dependencies throughout the application.
- **slim/slim**: PHP micro-framework.
- **phpunit/phpunit**: Testing framework for PHP.
- **phpdotenv**: PHP library that loads environment variables from a `.env` file.

## How to execute project
1. Rename the `.env.example` file to `.env`.
2. Set the `EXCHANGERATES_API_KEY` in the `.env` file.
3. Run `composer install`.
4. Open a terminal and run `php public/index.php`

## How to run tests
1. Open a terminal.
2. Navigate to the project directory.
3. Run `./bin/phpunit` or `composer run test`
   
## Main components
### ClientFeeCalculatorService
The `ClientFeeCalculatorService` class handles the fee calculation and validation of data from the CSV file. It extends a base class and implements the `validate` and `process` methods to provide client-specific fee calculation logic.

### ExchangeRatesService
The `ExchangeRatesService` class is responsible for interacting with third-party APIs to provide currency exchange rates.

### FileManagers
The `FileManagers` classes provide input for the data coming from files. These classes are responsible for reading data from various file formats such as CSV, Excel, and PDF. The `CsvManagerService` class, for example, reads data from CSV files and provides it to the fee calculator service.

### CSV File Location
The CSV file is located in the `/storage` directory.

## Time estimation
- Setting up the project - 1h
- Development - 5-6h
- Refactoring/cleaning code - 1h
- Write unit test - 30-40m
- Documentation/readme - 1h
