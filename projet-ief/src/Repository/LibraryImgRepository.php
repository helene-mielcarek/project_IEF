<?php

namespace App\Repository;

use App\Entity\LibraryImg;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LibraryImg|null find($id, $lockMode = null, $lockVersion = null)
 * @method LibraryImg|null findOneBy(array $criteria, array $orderBy = null)
 * @method LibraryImg[]    findAll()
 * @method LibraryImg[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibraryImgRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LibraryImg::class);
    }

    // /**
    //  * @return LibraryImg[] Returns an array of LibraryImg objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LibraryImg
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
