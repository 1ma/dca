<?php

declare (strict_types=1);

namespace UMA\DCA\Bitstamp\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UMA\DCA\Bitstamp\Buyer;
use UMA\DCA\Dollar;

class BuyCommand extends Command
{
    /**
     * @var Buyer
     */
    private $buyer;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(Buyer $buyer, LoggerInterface $logger)
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

        $context = [
            'exchange' => 'bitstamp',
            'response' => json_decode((string) $response->getBody())
        ];

        if (isset($context['response']->reason->__all__[0])) {
            $this->logger->error($context['response']->reason->__all__[0], $context);

            return 1;
        }

        $this->logger->notice(
            "Bought {$context['response']->amount} BTC at \${$context['response']->price}",
            $context
        );

        return 0;
    }
}
