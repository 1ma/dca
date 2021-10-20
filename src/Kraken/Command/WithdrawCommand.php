<?php

declare(strict_types=1);

namespace UMA\DCA\Kraken\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UMA\DCA\Model\Address;
use UMA\DCA\Model\Bitcoin;
use UMA\DCA\Model\WithdrawerInterface;
use function json_decode;

#[AsCommand(
    name: 'kraken:withdraw',
    description: 'Withdraw BTC from Kraken to the given address.'
)]
final class WithdrawCommand extends Command
{
    private WithdrawerInterface $withdrawer;
    private LoggerInterface $logger;

    public function __construct(WithdrawerInterface $withdrawer, LoggerInterface $logger)
    {
        $this->withdrawer = $withdrawer;
        $this->logger = $logger;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addArgument('amount', InputArgument::REQUIRED, 'Amount of BTC to withdraw, given in satoshis')
            ->addArgument('address', InputArgument::REQUIRED, 'Bitcoin address where to send the BTC');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $response = $this->withdrawer->withdraw(
            $btc = Bitcoin::fromSatoshi((int) $input->getArgument('amount')),
            $adr = Address::fromString((string) $input->getArgument('address'))
        );

        $ctx = [
            'exchange' => 'kraken',
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
