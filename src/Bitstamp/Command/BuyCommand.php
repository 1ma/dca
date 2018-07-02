<?php

declare(strict_types=1);

namespace UMA\DCA\Bitstamp\Command;

use Psr\Log\LoggerInterface;
use UMA\DCA\Model\BuyerInterface;
use UMA\DCA\Model\Dollar;
use ZF\Console\Route;

class BuyCommand
{
    /**
     * @var BuyerInterface
     */
    private $buyer;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(BuyerInterface $buyer, LoggerInterface $logger)
    {
        $this->buyer = $buyer;
        $this->logger = $logger;
    }

    public function __invoke(Route $route)
    {
        $response = $this->buyer->buy(
            Dollar::fromCents((int) $route->getMatchedParam('amount'))
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
            "Bought {$ctx['response']->amount} BTC at \${$ctx['response']->price}",
            $ctx
        );

        return 0;
    }
}
