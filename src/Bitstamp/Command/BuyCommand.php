<?php

declare(strict_types=1);

namespace UMA\DCA\Bitstamp\Command;

use Psr\Log\LoggerInterface;
use UMA\DCA\Model\Euro;
use UMA\DCA\Model\EuroBuyerInterface;
use ZF\Console\Route;

class BuyCommand
{
    /**
     * @var EuroBuyerInterface
     */
    private $buyer;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(EuroBuyerInterface $buyer, LoggerInterface $logger)
    {
        $this->buyer = $buyer;
        $this->logger = $logger;
    }

    public function __invoke(Route $route)
    {
        $response = $this->buyer->buy(
            Euro::fromCents((int) $route->getMatchedParam('amount'))
        );

        $ctx = [
            'exchange' => 'bitstamp',
            'response' => json_decode((string) $response->getBody())
        ];

        if (isset($ctx['response']->reason->__all__[0])) {
            $this->logger->error($ctx['response']->reason->__all__[0], $ctx);

            return 1;
        }

        $this->logger->notice(
            "Bought {$ctx['response']->amount} BTC at {$ctx['response']->price}€",
            $ctx
        );

        return 0;
    }
}
