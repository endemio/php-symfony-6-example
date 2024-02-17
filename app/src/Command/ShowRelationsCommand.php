<?php

namespace App\Command;

use App\Entity\Country;
use App\Entity\President;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[AsCommand(
    name: 'app:show-relations',
    description: 'Show all entities with relation',
)]
class ShowRelationsCommand extends EntityListCommand
{

    protected function configure(): void
    {
        $this
            ->addArgument('entity1', InputArgument::REQUIRED, 'Parent Entity class name')
            ->addArgument('entity2', InputArgument::REQUIRED, 'Related Entity class name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $parent = $input->getArgument('entity1');
        $mapped = $input->getArgument('entity2');

        if (!in_array('App\Entity\\' . $parent, [Country::class, President::class])) {
            throw new \ValueError(sprintf('Wrong entity name -> %s', self::IMPORT_ENTITY . $parent));
        }

        $all = $this->entityManager->getRepository(self::IMPORT_ENTITY . $parent)->findAll();

        # Fetch only non-relation Entity properties
        $class = ClassUtils::getRealClass(self::IMPORT_ENTITY . $parent);
        $props = (new \ReflectionClass($class))->getProperties(\ReflectionProperty::IS_PRIVATE);

        $target = null;
        foreach ($props as $item) {
            $rp = new \ReflectionProperty(self::IMPORT_ENTITY . $parent, $item->getName());
            if (str_starts_with($rp->getType()->getName(), self::IMPORT_ENTITY . $mapped)) {
                $target = $item->getName();
                break;
            }
        }

        // Blocking circular reference more then
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (object $object, string $format, array $context): string {
                return $object->getRelationName();
            },
        ];

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [$encoder]);

        $result = [];
        foreach ($all as $item) {
            $temp = $serializer->normalize($item, 'json');
            if (is_array($temp[$target])) {
                array_push($result, [
                        'id' => $temp['id'],
                        $parent => $temp['relationName'],
                        $mapped => $temp[$target]['relationName']
                    ]
                );
            }
        }

        $this->drawTable(['id', $parent, $mapped], $result);

        return Command::SUCCESS;
    }
}
