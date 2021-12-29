<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Event::class);
        $this->paginator = $paginator;
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return Event[] Returns an array of Event objects
     */
    public function findLastFiveForHome()
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.createdAt', 'ASC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
     * @return Event[] Returns an array of Event objects
    */
    public function findLastFiveParticipant($userId)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.date < :now')
            ->setParameter('now', new \DateTime('now'))
            ->innerJoin('e.famParticipants', 'f')
            ->addSelect('f')
            ->andWhere('f.id = :id')
            ->setParameter('id', $userId)
            ->orderBy('e.date', 'ASC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Event[] Returns an array of Event objects
    */
    public function findNextFiveParticipant($userId)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.date > :now')
            ->setParameter('now', new \DateTime('now'))
            ->innerJoin('e.famParticipants', 'f')
            ->addSelect('f')
            ->andWhere('f.id = :id')
            ->setParameter('id', $userId)
            ->orderBy('e.date', 'ASC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }

    // public function findByIdForRead($id): ?Event
    // {
    //     return $this->createQueryBuilder('e')
    //         ->andWhere('e.id = :id')
    //         ->setParameter('id', $id)
    //         // ->innerJoin('e.participants', 'f')
    //         // ->addSelect('f')
    //         // ->innerJoin('e.category', 'c')
    //         ->getQuery()
    //         ->getOneOrNullResult()
    //         ;
    // }

    /**
     * @return 
     */
    // public function findAllEventPagination(SearchData $search)
    // {
    //     $query= $this->createQueryBuilder('e')
    //     ->orderBy('e.date')
    //     ->select('a', 'e')
    //     ->join('e.author', 'a')
    //     ->select('f', 'e')
    //     ->join('e.famParticipants', 'f')
    //     ->join('e.category', 'c')
    //     // ->select('c', 'e')
    //     ;
    //     return $this->paginator->paginate(
    //         $query,
    //         $search->page,
    //         6,
    //         ['wrap-quieries'=> true]
    //     )

    //     // ->getQuery()
    //     // ->getResult()
    //     ;
    // }

    /**
     * @param [type] $search
     * @return 
     */
    public function findAllEventPaginationWithSearch(SearchData $search)
    {
        $query = $this->createQueryBuilder('e');

        if (isset($search->q)){
            $query = $query
                ->andWhere('e.title LIKE :q OR e.description LIKe :q')
                ->setParameter('q', "%$search->q%");
        }

        if(isset($search->limite)){
            if ($search->limite == false){
                $query = $query
                ->andWhere('e.complet = 0');
            }
            if ($search->limite == true){
                $query = $query
                ->andWhere('e.complet = 1');
            }
        }

        if(isset($search->date)){
            if ($search->date == false){
                // dd($search);
                $query = $query
                ->andWhere('e.date > :now')
                ->setParameter('now', new \DateTime('now'));
            }
            else if ($search->date == true){
                $query = $query
                ->andWhere('e.date < :now')
                ->setParameter('now', new \DateTime('now'));
            }
        }
        
        if (!empty ($search->categories)){
            // dd($search);
            $query = $query 
                ->leftJoin('e.category', 'c')
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories);
        } else {
            $query = $query->join('e.category', 'c');

        }
       $query = $query
        ->orderBy('e.date');
        
        return $this->paginator->paginate(
            $query,
            $search->page,
            6
        );

    }

    /**
     * @param [type] $search
     * @return 
     */
    public function findAllEventForUserPaginationWithSearch(SearchData $search, int $id)
    {
        $query = $this->createQueryBuilder('e');

        if (isset($search->q)){
            $query = $query
                ->andWhere('e.title LIKE :q OR e.description LIKe :q')
                ->leftJoin('e.author','a')
                ->select('a')
                ->andWhere('a.id' == $id)
                ->setParameter('q', "%$search->q%")
                ->setParameter('id', $id);
        }

        if(isset($search->limite)){
            if ($search->limite == false){
                $query = $query
                ->andWhere('e.complet = 0');
            }
            if ($search->limite == true){
                $query = $query
                ->andWhere('e.complet = 1');
            }
        }

        if(isset($search->date)){
            if ($search->date == false){
                // dd($search);
                $query = $query
                ->andWhere('e.date > :now')
                ->setParameter('now', new \DateTime('now'));
            }
            else if ($search->date == true){
                $query = $query
                ->andWhere('e.date < :now')
                ->setParameter('now', new \DateTime('now'));
            }
        }
        
        if (!empty ($search->categories)){
            // dd($search);
            $query = $query 
                ->leftJoin('e.category', 'c')
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories);
        } else {
            $query = $query->join('e.category', 'c');

        }
       $query = $query
        ->leftJoin('e.author','a')
        // ->select('a')
        ->andWhere('a.id = :id')
        ->setParameter('id', $id)
        ->orderBy('e.date');
        // ->getQuery()
        // ->getResult()
        
        return $this->paginator->paginate(
            $query,
            $search->page,
            6
        );

    }

    // public function findEventWithAll($id){

    //     return $this->createQueryBuilder('e')
    //     ->innerJoin('e.participants', 'p')
    //     ->addSelect('p')
    //     ->innerJoin('p.user', 'u')
    //     ->addSelect('u')
    //     ->andWhere('e.id = :id')
    //     ->setParameter('id', $id)
    //     ->join('e.category', 'c')
    //     ->addSelect('c')
    //     ->getQuery()
    //     ->getOneOrNullResult()
    //     ;
    // }


    // public function findByCategory($id, $page){
    //     $query = $this->createQueryBuilder('e') 
    //             ->leftJoin('e.category', 'c')
    //             ->andWhere('c.id = :id')
    //             ->setParameter('id', $id)
    //             ->orderBy('e.date');
    //     return $this->paginator->paginate(
    //         $query,
    //         $page,
    //         6
    //     );
    // }
}
