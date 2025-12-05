<?php

namespace App\Repository;

use App\Entity\Resto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Resto>
 */
class RestoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Resto::class);
    }

    public function FindByStarsMax($etoile)
    {
        $queryBuilder = $this->createQueryBuilder("r");
        $queryBuilder->where('r.nbEtoile <= :etoile')
            ->setParameter('etoile', $etoile)
            ->orderBy('r.nbEtoile', 'desc');
        return $queryBuilder->getQuery()->getResult();
    }

    public function nbOfSup($etoile)
    {
        $queryBuilder = $this->createQueryBuilder("r");
        $queryBuilder->select('COUNT(r)')
            ->where("r.nbEtoile > :etoile")
            ->setParameter("etoile", $etoile);
        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function addAStar()
    {
        $query = $this->getEntityManager()->createQuery("UPDATE APP\Entity\Resto r SET r.nbEtoile = r.nbEtoile + 1 WHERE r.nbEtoile < 3");
        return $query->execute();
    }

    //    /**
    //     * @return Resto[] Returns an array of Resto objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Resto
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
