<?php

namespace App\Repository;

use App\Entity\SliderImages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SliderImages|null find($id, $lockMode = null, $lockVersion = null)
 * @method SliderImages|null findOneBy(array $criteria, array $orderBy = null)
 * @method SliderImages[]    findAll()
 * @method SliderImages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SliderImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SliderImages::class);
    }

    public function findAllByDisplayOrder()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.displayOrder', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByEnabled()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('s.displayOrder', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return SliderImages[] Returns an array of SliderImages objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SliderImages
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
