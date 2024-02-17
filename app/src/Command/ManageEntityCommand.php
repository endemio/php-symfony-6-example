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
    name: 'app:manage-entity',
    description: 'Adding new entity item or set relation between Entities',
)]
class ManageEntityCommand extends EntityListCommand
{

    protected function configure(): void
    {
        $this
            ->addArgument('values', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'Values');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $values = $input->getArgument('values');

        if (count($values) < 1) {
            $io->note(sprintf('Please, use next format => Value1 Value2... '));
            return Command::FAILURE;
        } elseif (count($values) == 1) {
            $io->note(sprintf('Adding Country entity'));
            $this->addCountry($values[0]);
        } elseif (count($values) == 2) {
            $io->note(sprintf('Adding President entity'));
            $this->addPresident($values[0], $values[1]);
        } elseif (count($values) == 3) {
            $io->note(sprintf('Linking between Country and President'));
            $this->linkCountryAndPresident($values[0], intval($values[1]), intval($values[2]));
        }

        $io->success('Entity item was successfully created');

        return Command::SUCCESS;
    }

    /**
     * @param string $title
     */
    private function addCountry(string $title)
    {
        $entity = new Country();
        $entity->setTitle($title);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    /**
     * @param string $name
     * @param string $surname
     */
    private function addPresident(string $name, string $surname)
    {
        $entity = new President();
        $entity
            ->setName($name)
            ->setSurname($surname);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    /**
     * @param string $entity
     * @param int $id1
     * @param int $id2
     */
    private function linkCountryAndPresident(string $entity, int $id1, int $id2)
    {

        if (!in_array(self::IMPORT_ENTITY . $entity, [Country::class, President::class])) {
            throw new \ValueError('Wrong entity name');
        }

        /** @var Country $country */
        $country = ($entity === Country::class)
            ? $this->entityManager->getRepository(Country::class)->find($id1)
            : $this->entityManager->getRepository(Country::class)->find($id2);

        /** @var President $president */
        $president = ($entity === President::class)
            ? $this->entityManager->getRepository(President::class)->find($id1)
            : $this->entityManager->getRepository(President::class)->find($id2);

        $country->setPresident($president);
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

}
