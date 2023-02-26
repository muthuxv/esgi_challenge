<?php

namespace App\Repository;

use App\Entity\EventPayment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventPayment>
 *
 * @method EventPayment|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventPayment|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventPayment[]    findAll()
 * @method EventPayment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventPaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventPayment::class);
    }

    public function save(EventPayment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EventPayment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function showPayments()
    {
        return $this->createQueryBuilder('ep')
            ->select('ep.id', 'e.price as eventPrice', 'e.name as eventName', 'u.firstname as userFirstname', 'u.lastname as userLastname')
            ->leftJoin('ep.event', 'e')
            ->leftJoin('ep.user', 'u')
            ->getQuery()
            ->getResult();
        ;
    }

//    /**
//     * @return EventPayment[] Returns an array of EventPayment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EventPayment
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
