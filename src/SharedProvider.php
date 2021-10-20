<?php

declare(strict_types=1);

namespace UMA\DCA;

use Composer\CaBundle\CaBundle;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Monolog\ErrorHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use UMA\DCA\Bitstamp;
use UMA\DCA\Monolog\SlackHandler;
use UMA\DIC\Container;
use UMA\DIC\ServiceProvider;

/**
 * Defines common services across bounded contexts, such
 * as the logger or the HTTP client.
 */
final class SharedProvider implements ServiceProvider
{
    public function provide(Container $c): void
    {
        $c->set(Client::class, static function (Container $c): Client {
            $httpSettings = $c->get('settings')['http'];

            $config = [
                RequestOptions::CONNECT_TIMEOUT => $httpSettings['connect_timeout'],
                RequestOptions::TIMEOUT => $httpSettings['response_timeout'],
                RequestOptions::VERIFY => CaBundle::getBundledCaBundlePath()
            ];

            if (null !== $proxy = $httpSettings['proxy']) {
                $config[RequestOptions::PROXY] = $proxy;
            }

            return new Client($config);
        });

        $c->set(Application::class, static function (Container $c): Application {
            $app = new Application(APP_NAME, APP_VERSION);
            $app->setAutoExit(false);
            $app->setCommandLoader(new ContainerCommandLoader($c, [
                'bitstamp:buy' => Bitstamp\Command\BuyCommand::class,
                'bitstamp:withdraw' => Bitstamp\Command\WithdrawCommand::class,
                'kraken:buy' => Kraken\Command\BuyCommand::class,
                'kraken:withdraw' => Kraken\Command\WithdrawCommand::class,
            ]));

            return $app;
        });

        $c->set(Logger::class, static function (Container $c): LoggerInterface {
            $logger = new Logger(
                APP_NAME,
                [new StreamHandler('php://stdout', Logger::DEBUG)]
            );

            if (null !== $webhookUrl = $c->get('settings')['slack']['webhook_url']) {
                $logger->pushHandler(
                    new SlackHandler(
                        $c[Client::class],
                        $webhookUrl,
                        Logger::NOTICE
                    )
                );
            }

            return $logger;
        });

        ErrorHandler::register($c->get(Logger::class), [], [], Logger::CRITICAL);
    }
}
