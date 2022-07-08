<?php

namespace App\Repository;

use App\Entity\Preparing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Preparing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Preparing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Preparing[]    findAll()
 * @method Preparing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PreparingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Preparing::class);
    }

    /**
     * @param Preparing $entity
     * @param bool $flush
     */
    public function add(Preparing $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param Preparing $entity
     * @param bool $flush
     */
    public function remove(Preparing $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return int|mixed|string
     */
    public function findLastMonthPreparings()
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.events', 'events')
            ->orderBy('events.date', 'ASC')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Preparing[] Returns an array of Preparing objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Preparing
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
