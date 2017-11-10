<?php

declare(strict_types=1);

namespace UMA\DCA\Bitstamp\Command;

use Symfony\Component\Console\Command\Command;
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

    public function __construct(WithdrawerInterface $withdrawer)
    {
        $this->withdrawer = $withdrawer;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('bitstamp:withdraw');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $response = $this->withdrawer->withdraw(
            Bitcoin::fromSatoshi((int) $input->getArgument('amount')),
            Address::fromString($input->getArgument('address'))
        );

        return 0;
    }
}
