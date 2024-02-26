<?php

namespace App\Command;

use App\Entity\Clients;
use App\Entity\SessionConfigurations;
use App\Entity\SessionMembers;
use App\Entity\Sessions;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[AsCommand(
    name: 'app:show-entities',
    description: 'Show all items in target entity class',
)]
class ShowEntitiesCommand extends EntityListCommand
{

    protected function configure(): void
    {
        $this
            ->addArgument('entity', InputArgument::REQUIRED, 'Entity class name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entity = $input->getArgument('entity');

        if (!in_array(self::IMPORT_ENTITY . $entity, [Clients::class, SessionConfigurations::class, Sessions::class, SessionMembers::class])) {
            throw new \ValueError(sprintf('Wrong entity name -> %s', self::IMPORT_ENTITY . $entity));
        }

        $all = $this->entityManager->getRepository(self::IMPORT_ENTITY . $entity)->findAll();

        # Fetch only non-relation Entity properties
        $class = ClassUtils::getRealClass(self::IMPORT_ENTITY . $entity);
        $props = (new \ReflectionClass($class))->getProperties(\ReflectionProperty::IS_PRIVATE);

        $header = [];
        foreach ($props as $item) {
            $rp = new \ReflectionProperty(self::IMPORT_ENTITY . $entity, $item->getName());
            if (!str_starts_with($rp->getType()->getName(), self::IMPORT_ENTITY)) {
                array_push($header, $item->getName());
            }
        }

        // Blocking circular reference more then
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (object $object): string {
                return $object->getName();
            },
        ];

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter(), null, null, null, null, $defaultContext);
        $serializer = new Serializer([new DateTimeNormalizer([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), $normalizer], [$encoder]);

        $result = [];
        foreach ($all as $item) {
            array_push($result, $serializer->normalize($item, 'json'));
        }

        $this->drawTable($header, $result);

        return Command::SUCCESS;
    }
}
