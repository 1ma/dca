<?php

declare(strict_types=1);

namespace UMA\DCA\Bitstamp;

use GuzzleHttp\Client;
use Monolog\Logger;
use UMA\DCA\Bitstamp\Command\BuyCommand;
use UMA\DCA\Bitstamp\Command\WithdrawCommand;
use UMA\DCA\Model\ConverterInterface;
use UMA\DCA\Model\EuroBuyerInterface;
use UMA\DCA\Model\WithdrawerInterface;
use UMA\DIC\Container;
use UMA\DIC\ServiceProvider;

final class Provider implements ServiceProvider
{
    public function provide(Container $c): void
    {
        $c->set(NonceGeneratorInterface::class, static function() {
            return new NonceGenerator();
        });

        $c->set(Auth::class, static function (Container $c): Auth {
            return new Auth(
                (string) $c->get('settings')['bitstamp']['api_key'],
                (string) $c->get('settings')['bitstamp']['customer_id'],
                (string) $c->get('settings')['bitstamp']['hmac_secret'],
                $c->get(NonceGeneratorInterface::class)
            );
        });

        $c->set(Converter::class, static function (Container $c): ConverterInterface {
            return new Converter($c->get(Client::class));
        });

        $c->set(Buyer::class, static function (Container $c): EuroBuyerInterface {
            return new Buyer(
                $c->get(Auth::class),
                $c->get(Client::class),
                $c->get(Converter::class)
            );
        });

        $c->set(Withdrawer::class, static function (Container $c): WithdrawerInterface {
            return new Withdrawer(
                $c->get(Auth::class),
                $c->get(Client::class)
            );
        });

        $c->set(BuyCommand::class, static function (Container $c): BuyCommand {
            return new BuyCommand(
                $c->get(Buyer::class),
                $c->get(Logger::class)
            );
        });

        $c->set(WithdrawCommand::class, static function (Container $c): WithdrawCommand {
            return new WithdrawCommand(
                $c->get(Withdrawer::class),
                $c->get(Logger::class)
            );
        });
    }
}
