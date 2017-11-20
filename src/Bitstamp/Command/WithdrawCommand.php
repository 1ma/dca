<?php

declare(strict_types=1);

namespace UMA\DCA\Bitstamp\Command;

use Psr\Log\LoggerInterface;
use UMA\DCA\Model\Address;
use UMA\DCA\Model\Bitcoin;
use UMA\DCA\Contract\WithdrawerInterface;
use ZF\Console\Route;

class WithdrawCommand
{
    /**
     * @var WithdrawerInterface
     */
    private $withdrawer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(WithdrawerInterface $withdrawer, LoggerInterface $logger)
    {
        $this->withdrawer = $withdrawer;
        $this->logger = $logger;
    }

    public function __invoke(Route $route)
    {
        $response = $this->withdrawer->withdraw(
            $btc = Bitcoin::fromSatoshi((int) $route->getMatchedParam('amount')),
            $adr = Address::fromString((string) $route->getMatchedParam('address'))
        );

        $ctx = [
            'exchange' => 'bitstamp',
            'response' => json_decode((string) $response->getBody())
        ];

        if (!isset($ctx['response']->id)) {
            $this->logger->error('Failed withdrawal order', $ctx);

            return 1;
        }

        $this->logger->notice(
            "Withdrawn $btc BTC to $adr. Withdrawal ID is {$ctx['response']->id}",
            $ctx
        );

        return 0;
    }
}
