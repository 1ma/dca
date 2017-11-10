<?php

declare(strict_types=1);

namespace UMA\DCA\Bitstamp;

use GuzzleHttp\Client;
use Monolog\Logger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use UMA\DCA\Bitstamp\Command\BuyCommand;
use UMA\DCA\Bitstamp\Command\WithdrawCommand;
use UMA\DCA\BuyerInterface;
use UMA\DCA\ConverterInterface;
use UMA\DCA\WithdrawerInterface;

class Provider implements ServiceProviderInterface
{
    public function register(Container $cnt)
    {
        $cnt[Auth::class] = function (Container $cnt): Auth {
            return new Auth(
                (string) $cnt['settings']['bitstamp']['api_key'],
                (string) $cnt['settings']['bitstamp']['customer_id'],
                (string) $cnt['settings']['bitstamp']['hmac_secret']
            );
        };

        $cnt[Converter::class] = function (Container $cnt): ConverterInterface {
            return new Converter($cnt[Client::class]);
        };

        $cnt[Buyer::class] = function (Container $cnt): BuyerInterface {
            return new Buyer(
                $cnt[Auth::class],
                $cnt[Client::class],
                $cnt[Converter::class]
            );
        };

        $cnt[Withdrawer::class] = function (Container $cnt): WithdrawerInterface {
            return new Withdrawer(
                $cnt[Auth::class],
                $cnt[Client::class]
            );
        };

        $cnt[BuyCommand::class] = function (Container $cnt): BuyCommand {
            return new BuyCommand($cnt[Buyer::class], $cnt[Logger::class]);
        };

        $cnt[WithdrawCommand::class] = function (Container $cnt): WithdrawCommand {
            return new WithdrawCommand($cnt[Withdrawer::class]);
        };
    }
}
