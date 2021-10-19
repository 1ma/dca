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
use UMA\DCA\Bitstamp;
use UMA\DCA\Monolog\SlackHandler;
use UMA\DIC\Container;
use UMA\DIC\ServiceProvider;
use ZF\Console\Application;
use ZF\Console\Dispatcher;

/**
 * Defines common services across bounded contexts, such
 * as the logger or the HTTP client.
 */
class SharedProvider implements ServiceProvider
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
            return (new Application(
                APP_NAME, APP_VERSION,
                [
                    [
                        'name' => 'bitstamp:buy <amount>',
                        'handler' => Bitstamp\Command\BuyCommand::class,
                        'short_description' => 'Buy BTC at market price at Bitstamp. The amount is given in USD cents.'
                    ],
                    [
                        'name' => 'bitstamp:withdraw <amount> <address>',
                        'handler' => Bitstamp\Command\WithdrawCommand::class,
                        'short_description' => 'Withdraw BTC from Bitstamp to the given address. The amount is given in satoshis.'
                    ],
                    [
                        'name' => 'kraken:buy <amount>',
                        'handler' => Kraken\Command\BuyCommand::class,
                        'short_description' => 'Buy BTC at market price at Kraken. The amount is given in USD cents.'
                    ],
                    [
                        'name' => 'kraken:withdraw <amount> <address>',
                        'handler' => Kraken\Command\WithdrawCommand::class,
                        'short_description' => 'Withdraw BTC from Kraken to the given address. The amount is given in satoshis. You must add an address on your admin panel'
                    ]
                ], null,
                new Dispatcher($c)
            ))->setDebug(true);
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
