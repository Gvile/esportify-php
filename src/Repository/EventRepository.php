<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findEventsByFilter(array $filter): array
    {
        $qb = $this->createQueryBuilder('e');

        // Filtrer par titre
        if (!empty($filter['title'])) {
            $qb->andWhere('e.title LIKE :title')
                ->setParameter('title', '%' . $filter['title'] . '%');
        }

        // Filtrer par nombre maximum de joueurs
        if (!empty($filter['maxUser'])) {
            $qb->andWhere('e.maxUser <= :maxUser')
                ->setParameter('maxUser', $filter['maxUser']);
        }

        // Filtrer par date de début
        if (!empty($filter['startDate'])) {
            $qb->andWhere('e.startDate >= :startDate')
                ->setParameter('startDate', new \DateTime($filter['startDate']));
        }

        // Filtrer par date de fin
        if (!empty($filter['endDate'])) {
            $qb->andWhere('e.endDate <= :endDate')
                ->setParameter('endDate', new \DateTime($filter['endDate']));
        }

        return $qb->getQuery()->getResult();
    }


    //    /**
    //     * @return Event[] Returns an array of Event objects
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

    //    public function findOneBySomeField($value): ?Event
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findUpcomingAndOngoingEvents(\DateTimeInterface $currentDate): array
    {
        $qb = $this->createQueryBuilder('e');

        // Query for upcoming events with 'is_validated' = true
        $upcomingEvents = $qb
            ->andWhere('e.startDate > :currentDate')
            ->andWhere('e.isValidated = :isValidated') // Condition pour 'is_validated' = true
            ->setParameter('currentDate', $currentDate)
            ->setParameter('isValidated', true) // Définir la valeur à true
            ->orderBy('e.startDate', 'ASC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();

        // Query for ongoing events with 'is_validated' = true
        $ongoingEvents = $this->createQueryBuilder('e2')
            ->andWhere(':currentDate BETWEEN e2.startDate AND e2.endDate')
            ->andWhere('e2.isValidated = :isValidated') // Condition pour 'is_validated' = true
            ->setParameter('currentDate', $currentDate)
            ->setParameter('isValidated', true) // Définir la valeur à true
            ->orderBy('e2.startDate', 'ASC')
            ->setMaxResults(2)
            ->getQuery()
            ->getResult();

        return [
            'upcoming' => $upcomingEvents,
            'ongoing' => $ongoingEvents,
        ];
    }
}
