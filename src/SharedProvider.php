<?php

declare(strict_types=1);

namespace UMA\DCA;

use Composer\CaBundle\CaBundle;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Monolog\ErrorHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;
use UMA\DCA\Bitstamp;
use UMA\DCA\Monolog\SlackHandler;

/**
 * Defines common services across bounded contexts, such
 * as the logger or the HTTP client.
 */
class SharedProvider implements ServiceProviderInterface
{
    public function register(Container $cnt)
    {
        $cnt[Client::class] = function (Container $cnt): Client {
            $config = [
                RequestOptions::CONNECT_TIMEOUT => $cnt['settings']['http']['connect_timeout'],
                RequestOptions::TIMEOUT => $cnt['settings']['http']['response_timeout'],
                RequestOptions::VERIFY => CaBundle::getBundledCaBundlePath()
            ];

            if (null !== $proxy = $cnt['settings']['http']['proxy']) {
                $config[RequestOptions::PROXY] = $proxy;
            }

            return new Client($config);
        };

        $cnt[Application::class] = function (Container $cnt): Application {
            $cli = new Application(APP_NAME, APP_VERSION);

            $cli->add($cnt[Bitstamp\Command\BuyCommand::class]);
            $cli->add($cnt[Bitstamp\Command\WithdrawCommand::class]);

            return $cli;
        };

        $cnt[Logger::class] = function (Container $cnt): LoggerInterface {
            $logger = new Logger(
                APP_NAME,
                [new StreamHandler('php://stdout', Logger::DEBUG)]
            );

            $webhookUrl = $cnt['settings']['slack']['webhook_url'];
            $channel = $cnt['settings']['slack']['channel'];

            if (null !== $webhookUrl && null !== $channel) {
                $logger->pushHandler(
                    new SlackHandler(
                        $cnt[Client::class],
                        $webhookUrl,
                        Logger::NOTICE
                    )
                );
            }

            return $logger;
        };

        ErrorHandler::register($cnt[Logger::class], [], Logger::CRITICAL);
    }
}
