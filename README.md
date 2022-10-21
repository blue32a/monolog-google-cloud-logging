# monolog-google-cloud-logging

![Test](https://github.com/blue32a/monolog-google-cloud-logging/workflows/Test/badge.svg)

## About

[Google Cloud Logging](https://github.com/googleapis/google-cloud-php-logging) handler for [Monolog](https://github.com/Seldaek/monolog).

## Installation

```console
$ composer require blue32a/monolog-google-cloud-logging
```

## Usage

```php
use Blue32a\MonologGoogleCloudLoggingHandler\GoogleCloudLoggingHandler;

$logger = new \Monolog\Logger();

$config = ['projectId' => 'xxxxx'];
$loggingClient = GoogleCloudLoggingHandler::factoryLoggingClient($config);
$handler = new GoogleCloudLoggingHandler('logname', $loggingClient);
$logger->pushHandler($handler);

$logger->info('Hello World!');
```
