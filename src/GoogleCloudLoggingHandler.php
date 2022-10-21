<?php

declare(strict_types=1);

namespace Blue32a\MonologGoogleCloudLoggingHandler;

use Google\Cloud\Logging\LoggingClient;
use Google\Cloud\Logging\PsrLogger;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class GoogleCloudLoggingHandler extends AbstractProcessingHandler
{
    protected PsrLogger $logger;

    /**
     * @param array<string, mixed> $options Logger options
     */
    public function __construct(
        string $name,
        LoggingClient $client,
        array $options = [],
        int $level = Logger::DEBUG,
        bool $bubble = true
    ) {
        parent::__construct($level, $bubble);

        $this->logger = $client->psrLogger($name, $options);
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record): void
    {
        $this->logger->log(
            $record['level_name'],
            $record['formatted'],
            $record['context']
        );
    }
}
