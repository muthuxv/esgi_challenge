<?php

namespace App\Repository;

use App\Entity\Hero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Hero>
 *
 * @method Hero|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hero|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hero[]    findAll()
 * @method Hero[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hero::class);
    }

    public function save(Hero $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Hero $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //get number of completed missions
    public function getCompletedMissions($hero)
    {
        $qb = $this->createQueryBuilder('h');
        $qb->select('count(m.id)')
            ->join('h.missions', 'm')
            ->where('h.id = :hero')
            ->andWhere('m.status = :status')
            ->setParameter('hero', $hero)
            ->setParameter('status', 'completed');

        return $qb->getQuery()->getSingleScalarResult();
    }

    //update rank of hero based on number of completed missions and updated at
    public function updateRank($hero)
    {
        $completedMissions = $this->getCompletedMissions($hero);

        if ($completedMissions >= 0 && $completedMissions <= 10) {
            $rank = 'C';
        } elseif ($completedMissions >= 11 && $completedMissions <= 20) {
            $rank = 'B';
        } elseif ($completedMissions >= 21 && $completedMissions <= 30) {
            $rank = 'A';
        } elseif ($completedMissions >= 31) {
            $rank = 'S';
        }

        $qb = $this->createQueryBuilder('h');
        $qb->update()
            ->set('h.rank', ':rank')
            ->where('h.id = :hero')
            ->setParameter('rank', $rank)
            ->setParameter('hero', $hero);
                

        return $qb->getQuery()->execute();
    }

//    /**
//     * @return Hero[] Returns an array of Hero objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Hero
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
