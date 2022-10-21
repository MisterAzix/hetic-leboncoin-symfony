<?php

namespace App\Repository;

use App\Entity\Answer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Answer>
 *
 * @method Answer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answer[]    findAll()
 * @method Answer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    public function save(Answer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Answer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    /** La méthode pour n’afficher que les réponses qui ont le status approved
     * @return Criteria
     */
    public static function createApprovedCriteria(): Criteria
    {
        //filtrer une collection avant que la query ne soit faite en DB
        return Criteria::create()->andWhere(Criteria::expr()->eq('status', Answer::APPROVED));
    }

    // Réutiliser notre critère fraichement crée dans un queryBuilder
    /**
     * @return Answer[] Returns an array of Answer objects
     */
    public function findAllApproved(int $max = 10): array
    {
        return $this->createQueryBuilder('answer')
            ->andWhere('answer.status = :status')
            ->setParameter('status', Answer::APPROVED)
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }
    // Les réponses populaires, query custom, les jointures
    /**
     * @return Answer[] Returns an array of Answer objects
     */
    public function findMostPopular(string $search, int $max = 10): array
    {
        $QueryBuilder = $this->createQueryBuilder('answer')
            ->addCriteria(self::createApprovedCriteria())
            // Les jointures avec els données de answer.user et j'appelle l'entité créer 'user'
            ->innerJoin('answer.user', 'user')
            // sélectionner les données ramenées par la jointure
            ->addSelect('user')
            ->orderBy('answer.votes', 'DESC');

        //adapter la query pour qu’elle accepte une recherche
        if ($search !== null) {
            $QueryBuilder->innerJoin('answer.user', 'user')
                ->andWhere('answer.content LIKE :searchTerm 
                OR question.name LIKE :searchTerm 
                OR user.firstName LIKE :searchTerm 
                OR user.lastName LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $search . '%');
        }
        return $QueryBuilder
            //A commenter pour utiliser la pagination *
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }



    //    /**
    //     * @return Answer[] Returns an array of Answer objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Answer
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
