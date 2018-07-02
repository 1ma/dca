<?php

declare(strict_types=1);

namespace UMA\DCA\Kraken\Command;

use Psr\Log\LoggerInterface;
use UMA\DCA\Contract\BuyerInterface;
use UMA\DCA\Kraken\Checker;
use UMA\DCA\Model\Dollar;
use ZF\Console\Route;

class BuyCommand
{
    const KRAKEN = 'kraken';

    const PENDING = "pending";
    const CLOSED = 'closed';
    const CANCELED = 'canceled';
    const EXPIRED = 'expired';
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


        $txId = $ctx['response']['result']['txid'];


        $this->logger->notice(
            "Order appened to Kraken with txId: $txId, waiting to complete",
            $ctx
        );

        $isFinished = false;
        while (!$isFinished && 0 === count($response['error'])) {
            $response = json_decode($this->checker->check($txId), true);
            $isFinished = in_array($response['result'][$txId]['status'], [self::CLOSED, self::CANCELED, self::EXPIRED]);
            sleep(1);
        }

        $ctx = [
            'exchange' => self::KRAKEN,
            'response' => $response
        ];

        if (count($ctx['response']["error"])) {
            $this->logger->error(implode(" ", $ctx['response']['error']), $ctx);

            return 1;
        }

        if (self::CLOSED === $response['result'][$txId]['status']) {
            $this->logger->notice(
                "Bought {$ctx['response']->result->descr->order} BTC at \${$ctx['response']->price}",
                $ctx
            );
        } else {
            $this->logger->error(
                "The transaction $txId is {$response['result'][$txId]['status']}",
                $ctx
            );
        }

        return 0;
    }
}
