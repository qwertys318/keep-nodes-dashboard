<?php namespace App\Command;

use App\Entity\Node;
use App\Keep\KeepBondingChecker;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateNodeDataCommand extends AbstractCommand
{
    private KeepBondingChecker $keepBondingChecker;

    public function __construct(
        LoggerInterface $logger,
        KeepBondingChecker $keepBondingChecker
    )
    {
        $this->logger = $logger;
        $this->keepBondingChecker = $keepBondingChecker;
        parent::__construct();
    }

    protected function do(InputInterface $input, OutputInterface $output): int
    {
        $nodeRepository = $this->em->getRepository(Node::class);
        /** @var Node[] $nodes */
        $nodes = $nodeRepository->findAll();
        $this->logger->info(sprintf('Fetched %d nodes', count($nodes)));
        foreach ($nodes as $node) {
            $this->logger->info(sprintf('Check #%d', $node->getId()));
            $unbondedValue = $this->keepBondingChecker->getUnbondedValue($node->getAddress());
            $this->logger->info(sprintf('Result: %s', $unbondedValue));
            $node->setUnbondedValue($unbondedValue);
            $this->em->persist($node);
        }

        $this->em->flush();

        return 0;
    }
}
