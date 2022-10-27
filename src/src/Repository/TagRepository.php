<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 *
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function save(Tag $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Tag $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //filtrer les annnonces par 10 tags les plus recents
    //name
    //Opt 1: select * from ad where ad.id in (select ad.id from tag orderBy ad.id limite 10 )
    //Opt sfn
    // public function filterTag(string $search = null): array
    // {
    //     $queryBuilder = $this->createQueryBuilder('tag')
    //         ->innerJoin('ad.user', 'user')
    //         ->addSelect('user')
    //         ->orderBy('ad.title', 'DESC');

    //     if ($search !== null) {
    //         $queryBuilder
    //             ->andWhere('ad.description LIKE :searchterm
    //             OR ad.title LIKE :searchterm
    //             OR user.first_name LIKE :searchterm
    //             OR user.name LIKE :searchterm')
    //             ->setParameter('searchterm', '%' . $search . '%');
    //     }

    //     return $queryBuilder
    //         ->getQuery()
    //         ->getResult();
    // }

    //    /**
    //     * @return Tag[] Returns an array of Tag objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Tag
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
