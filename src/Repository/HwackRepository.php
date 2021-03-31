<?php

namespace App\Repository;

use App\Entity\Hwack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Hwack|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hwack|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hwack[]    findAll()
 * @method Hwack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HwackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hwack::class);
    }

    /**
     * @param $value
     * @return Hwack[] Returns an array of Hwack objects
     */
    public function findByContentLike(String $value): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.content LIKE %:val% and isPrivate=true')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Hwack
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
