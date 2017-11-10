<?php

declare(strict_types=1);

namespace UMA\DCA\Bitstamp\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UMA\DCA\BuyerInterface;
use UMA\DCA\Dollar;

class BuyCommand extends Command
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

        parent::__construct(null);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('bitstamp:buy')
            ->addArgument('amount', InputArgument::REQUIRED, 'Amount to buy, in USD cents (e.g. 500 = $5.00)');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $response = $this->buyer->buy(
            Dollar::fromCents((int) $input->getArgument('amount'))
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
