<?php

namespace App\Repository;

use App\Entity\Buku;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method Buku|null find($id, $lockMode = null, $lockVersion = null)
 * @method Buku|null findOneBy(array $criteria, array $orderBy = null)
 * @method Buku[]    findAll()
 * @method Buku[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BukuRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Buku::class);
    }

    public function listBook()
    {
        $builder = $this->getEntityManager()->getRepository(Buku::class);
        $getAllData = $builder->findAll();

        return $getAllData;
    }

    // /**
    //  * @return Buku[] Returns an array of Buku objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Buku
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
