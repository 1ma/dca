<?php

declare(strict_types=1);

namespace UMA\DCA\Kraken;

use GuzzleHttp\Client;
use Monolog\Logger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use UMA\DCA\Kraken\Command\BuyCommand;
use UMA\DCA\Kraken\Command\WithdrawCommand;
use UMA\DCA\Contract\BuyerInterface;
use UMA\DCA\Contract\ConverterInterface;
use UMA\DCA\Contract\WithdrawerInterface;

class Provider implements ServiceProviderInterface
{
    public function register(Container $cnt)
    {
        $cnt[Auth::class] = function (Container $cnt): Auth {
            return new Auth(
                (string) $cnt['settings']['kraken']['api_key'],
                (string) $cnt['settings']['kraken']['secret']
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


        $cnt[Checker::class] = function (Container $cnt): Checker {
            return new Checker(
                $cnt[Auth::class],
                $cnt[Client::class]
            );
        };

        $cnt[Withdrawer::class] = function (Container $cnt): WithdrawerInterface {
            return new Withdrawer(
                $cnt[Auth::class],
                $cnt[Client::class]
            );
        };

        $cnt[BuyCommand::class] = function (Container $cnt): BuyCommand {
            return new BuyCommand(
                $cnt[Buyer::class],
                $cnt[Logger::class],
                $cnt[Checker::class]
            );
        };

        $cnt[WithdrawCommand::class] = function (Container $cnt): WithdrawCommand {
            return new WithdrawCommand(
                $cnt[Withdrawer::class],
                $cnt[Logger::class]
            );
        };
    }
}
