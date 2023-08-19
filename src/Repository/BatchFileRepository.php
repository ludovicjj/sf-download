<?php

namespace App\Repository;

use App\Entity\Main\BatchFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BatchFile>
 *
 * @method BatchFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method BatchFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method BatchFile[]    findAll()
 * @method BatchFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BatchFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BatchFile::class);
    }

//    /**
//     * @return BatchFile[] Returns an array of BatchFile objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BatchFile
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
