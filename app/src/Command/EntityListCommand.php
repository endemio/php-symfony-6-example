<?php


namespace App\Command;


use Doctrine\ORM\EntityManagerInterface;
use jc21\CliTable;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:entities-list',
    description: 'List of entities',
)]
class EntityListCommand extends Command
{

    const IMPORT_ENTITY = 'App\\Entity\\';

    public function __construct(protected EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->drawTable(['entity'], $this->listEntities());

        return Command::SUCCESS;
    }

    public function listEntities(): array
    {
        $entityNames = $this->entityManager->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();

        return array_map(function ($item) {
            return ['entity' => $item];
        }, array_filter($entityNames, function ($className) {
            return str_starts_with($className, self::IMPORT_ENTITY);
        }));
    }

    protected function drawTable(array $header, array $data): void
    {
        $table = new CliTable();
        foreach ($header as $item) {
            $table->addField(ucfirst($item), $item, false);
        }
        $table->injectData($data);
        $table->display();
    }
}