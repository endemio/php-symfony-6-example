<?php

namespace App\Repository;

use App\Entity\SessionMembers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SessionMembers>
 *
 * @method SessionMembers|null find($id, $lockMode = null, $lockVersion = null)
 * @method SessionMembers|null findOneBy(array $criteria, array $orderBy = null)
 * @method SessionMembers[]    findAll()
 * @method SessionMembers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionMembersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SessionMembers::class);
    }

//    /**
//     * @return SessionMembers[] Returns an array of SessionMembers objects
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

//    public function findOneBySomeField($value): ?SessionMembers
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
