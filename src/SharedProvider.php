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
use Pimple\Psr11\Container as Psr11Container;
use Pimple\ServiceProviderInterface;
use Psr\Log\LoggerInterface;
use UMA\DCA\Bitstamp;
use UMA\DCA\Monolog\SlackHandler;
use ZF\Console\Application;
use ZF\Console\Dispatcher;

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
                new Dispatcher(new Psr11Container($cnt))
            ))->setDebug(true);
        };

        $cnt[Logger::class] = function (Container $cnt): LoggerInterface {
            $logger = new Logger(
                APP_NAME,
                [new StreamHandler('php://stdout', Logger::DEBUG)]
            );

            if (null !== $webhookUrl = $cnt['settings']['slack']['webhook_url']) {
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
