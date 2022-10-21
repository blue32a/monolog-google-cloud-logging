<?php

declare(strict_types=1);

namespace Tests;

use Blue32a\MonologGoogleCloudLoggingHandler\GoogleCloudLoggingHandler;
use DateTimeImmutable;
use Google\Cloud\Logging\LoggingClient;
use Google\Cloud\Logging\PsrLogger;
use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GoogleCloudLoggingHandlerTest extends TestCase
{
    /**
     * @return MockObject&PsrLogger
     */
    private function createPsrLoggerMock()
    {
        return $this->createMock(PsrLogger::class);
    }

    /**
     * @return MockObject&LoggingClient
     */
    private function createLoggingClientMock()
    {
        return $this->createMock(LoggingClient::class);
    }

    /**
     * @param array<string, mixed> $context
     * @return array<string, mixed>
     */
    private function factoryRecode(string $message, int $level, array $context): array
    {
        return [
            'message' => $message,
            'context' => $context,
            'level' => $level,
            'level_name' => Logger::getLevelName($level),
            'channel' => 'test',
            'datetime' => new DateTimeImmutable(),
            'extra' => [],
        ];
    }

    /**
     * @test
     */
    public function testHandle(): void
    {
        $logName = 'testlog';
        $format  = '%level_name%: %message% %context%';
        $recode  = $this->factoryRecode(
            'test handle',
            Logger::DEBUG,
            ['a' => 'fuga']
        );

        $loggerMock = $this->createPsrLoggerMock();
        $loggerMock
            ->expects($this->once())
            ->method('log')
            ->with(
                $this->equalTo($recode['level_name']),
                $this->equalTo('DEBUG: test handle {"a":"fuga"}'),
                $this->equalTo($recode['context'])
            );

        $clientMock = $this->createLoggingClientMock();
        $clientMock
            ->expects($this->once())
            ->method('psrLogger')
            ->with($this->equalTo($logName))
            ->willReturn($loggerMock);

        $handler = new GoogleCloudLoggingHandler($logName, $clientMock);
        $handler->setFormatter(new LineFormatter($format));

        $handler->handle($recode);
    }

    /**
     * @test
     */
    public function testFactoryLoggingClient(): void
    {
        $config = ['projectId' => 'xxxxx'];

        $loggingClient = GoogleCloudLoggingHandler::factoryLoggingClient($config);

        $this->assertInstanceOf(LoggingClient::class, $loggingClient);
    }
}
