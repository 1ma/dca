<?php

declare(strict_types=1);

namespace UMA\DCA\Bitstamp\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UMA\DCA\Address;
use UMA\DCA\Bitcoin;
use UMA\DCA\WithdrawerInterface;

class WithdrawCommand extends Command
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

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('bitstamp:withdraw')
            ->addArgument(
                'amount',
                InputArgument::REQUIRED,
                'Amount of BTC to withdraw, in satoshis (e.g. 0.5 BTC = 50000000')
            ->addArgument(
                'address',
                InputArgument::REQUIRED,
                'Address where the coins must be sent'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $response = $this->withdrawer->withdraw(
            $btc = Bitcoin::fromSatoshi((int) $input->getArgument('amount')),
            $adr = Address::fromString($input->getArgument('address'))
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
