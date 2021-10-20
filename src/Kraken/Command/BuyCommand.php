<?php

declare(strict_types=1);

namespace UMA\DCA\Kraken\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UMA\DCA\Model\BuyerInterface;
use UMA\DCA\Model\Dollar;
use function count;
use function implode;
use function json_decode;

#[AsCommand(
    name: 'kraken:buy',
    description: 'Buy BTC at market price at Kraken.'
)]
final class BuyCommand extends Command
{
    private const KRAKEN = 'kraken';

    private BuyerInterface $buyer;
    private LoggerInterface $logger;

    public function __construct(BuyerInterface $buyer, LoggerInterface $logger)
    {
        $this->buyer = $buyer;
        $this->logger = $logger;
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('amount', InputArgument::REQUIRED, 'Amount of BTC to buy, in USD cents');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $response = $this->buyer->buy(
            Dollar::fromCents((int) $input->getArgument('amount'))
        );

        $ctx = [
            'exchange' => self::KRAKEN,
            'response' => json_decode((string) $response->getBody(), true)
        ];

        if (count($ctx['response']['error'])) {
            $this->logger->error(implode(' ', $ctx['response']['error']), $ctx);

            return 1;
        }

        $this->logger->notice(
            "Order appended to Kraken to buy {$ctx['response']->result->descr->order} BTC at \${$ctx['response']->price}",
            $ctx
        );

        return 0;
    }
}
