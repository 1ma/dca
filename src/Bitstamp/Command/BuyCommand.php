<?php

declare(strict_types=1);

namespace UMA\DCA\Bitstamp\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UMA\DCA\Model\Euro;
use UMA\DCA\Model\EuroBuyerInterface;
use function json_decode;
use function sprintf;

#[AsCommand(
    name: 'bitstamp:buy',
    description: 'Buy BTC at market price at Bitstamp.'
)]
final class BuyCommand extends Command
{
    private EuroBuyerInterface $buyer;
    private LoggerInterface $logger;

    public function __construct(EuroBuyerInterface $buyer, LoggerInterface $logger)
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
            $euro = Euro::fromCents((int) $input->getArgument('amount'))
        );

        $ctx = [
            'exchange' => 'bitstamp',
            'response' => json_decode((string) $response->getBody())
        ];

        if (isset($ctx['response']->reason->__all__[0])) {
            $this->logger->error($ctx['response']->reason->__all__[0], $ctx);

            return 1;
        }

        $this->logger->notice(sprintf(
            'Bought %s€ of Bitcoin at %s€ (amount: %s)',
            (string) $euro, $ctx['response']->price, $ctx['response']->amount
        ));

        return 0;
    }
}
