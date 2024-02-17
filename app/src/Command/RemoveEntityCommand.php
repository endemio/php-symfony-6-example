<?php

namespace App\Command;

use App\Entity\Country;
use App\Entity\President;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:remove-entity',
    description: 'Remove entity item by ID',
)]
class RemoveEntityCommand extends EntityListCommand
{

    protected function configure(): void
    {
        $this
            ->addArgument('entity', InputArgument::REQUIRED, 'Entity class name')
            ->addArgument('id', InputArgument::REQUIRED, 'Entity ID (int)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $entity = $input->getArgument('entity');

        if (!in_array(self::IMPORT_ENTITY . $entity, [Country::class, President::class])) {
            throw new \ValueError(sprintf('Wrong entity name -> %s', self::IMPORT_ENTITY . $entity));
        }

        $item = $this->entityManager->getRepository(self::IMPORT_ENTITY . $entity)->find(intval($input->getArgument('id')));

        if (!$item){
            throw new \ValueError(sprintf('Entity not found for "%s" [%s]', $entity, $input->getArgument('id')));
        }

        $this->entityManager->remove($item);
        $this->entityManager->flush();
        $this->entityManager->clear();

        $io->success('Entity item was successfully removed');

        return Command::SUCCESS;
    }
}
