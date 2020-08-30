<?php namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Store\FlockStore;

abstract class AbstractCommand extends Command
{
    protected Logger $logger;
    protected EntityManagerInterface $em;
    protected string $projectDir;

    public function setEm(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }

    public function setProjectDir(string $projectDir): void
    {
        $this->projectDir = $projectDir;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->info("Start command: {$this->getName()}");

        $store = new FlockStore(sprintf('%s/var/db', $this->projectDir));
        $factory = new LockFactory($store);
        $lock = $factory->createLock(md5(serialize($input->getArguments())));

        if (!$lock->acquire()) {
            $this->logger->critical('Sorry, cannot lock file');

            return 1;
        }
        $result = $this->do($input, $output);
        $this->logger->info("Command has successfully finished");

        return $result;
    }

    abstract protected function do(InputInterface $input, OutputInterface $output);
}
