<?php

namespace App\Repository;

use App\Entity\HwackImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HwackImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method HwackImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method HwackImage[]    findAll()
 * @method HwackImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HwackImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HwackImage::class);
    }

    // /**
    //  * @return HwackImage[] Returns an array of HwackImage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HwackImage
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
