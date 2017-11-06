<?php

declare (strict_types=1);

namespace UMA\DCA\Bitstamp\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WithdrawCommand extends Command
{
    protected function configure()
    {
        $this->setName('bitstamp:withdraw');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return 0;
    }
}
