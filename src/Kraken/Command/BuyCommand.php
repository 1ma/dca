<?php

declare(strict_types=1);

namespace UMA\DCA\Kraken\Command;

use Psr\Log\LoggerInterface;
use UMA\DCA\Kraken\Checker;
use UMA\DCA\Model\BuyerInterface;
use UMA\DCA\Model\Dollar;
use ZF\Console\Route;

class BuyCommand
{
    const KRAKEN = 'kraken';

    /**
     * @var BuyerInterface
     */
    private $buyer;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Checker
     */
    private $checker;

    public function __construct(BuyerInterface $buyer, LoggerInterface $logger, Checker $checker)
    {
        $this->buyer = $buyer;
        $this->checker = $checker;
        $this->logger = $logger;
    }

    public function __invoke(Route $route)
    {
        $response = $this->buyer->buy(
            Dollar::fromCents((int) $route->getMatchedParam('amount'))
        );

        $ctx = [
            'exchange' => self::KRAKEN,
            'response' => json_decode((string) $response->getBody(), true)
        ];


        if (count($ctx['response']["error"])) {
            $this->logger->error(implode(" ", $ctx['response']['error']), $ctx);

            return 1;
        }


        $this->logger->notice(
            "Order appended to Kraken to buy {$ctx['response']->result->descr->order} BTC at \${$ctx['response']->price}",
            $ctx
        );

        return 0;
    }
}
