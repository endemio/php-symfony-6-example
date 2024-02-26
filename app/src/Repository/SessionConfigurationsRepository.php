<?php

namespace App\Repository;

use App\Entity\SessionConfigurations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SessionConfigurations>
 *
 * @method SessionConfigurations|null find($id, $lockMode = null, $lockVersion = null)
 * @method SessionConfigurations|null findOneBy(array $criteria, array $orderBy = null)
 * @method SessionConfigurations[]    findAll()
 * @method SessionConfigurations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionConfigurationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SessionConfigurations::class);
    }

//    /**
//     * @return SessionConfigurations[] Returns an array of SessionConfigurations objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SessionConfigurations
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
