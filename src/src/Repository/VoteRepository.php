<?php

namespace App\Repository;

use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vote>
 *
 * @method Vote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vote[]    findAll()
 * @method Vote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vote::class);
    }

    public function save(Vote $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Vote $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function hasVote(string $fromUserId, string $toUserId, string $direction)
    {
        $hasVote = false;

        $result =  $this->createQueryBuilder('v')
            ->select('count(v.to_user_id)')
            ->andWhere('v.to_user_id = :toUser')
            ->andWhere('v.from_user_id = :fromUser')
            ->andWhere('v.direction = :direction')
            ->setParameters(new ArrayCollection([
                new Parameter('toUser', $toUserId),
                new Parameter('fromUser', $fromUserId),
                new Parameter('direction', $direction)
            ]))
            ->getQuery()
            ->getSingleScalarResult();

        if ($result >= 1) {
            $hasVote = true;
        }
        return $hasVote;
    }

    public function upDateVote(string $fromUserId, string $toUserId, string $direction)
    {
        $hasVote = false;

        $result =  $this->createQueryBuilder('v')
            ->update(Vote::class, 'v')
            ->set('v.direction', ':direction')
            ->andWhere('v.to_user_id = :toUser')
            ->andWhere('v.from_user_id = :fromUser')
            ->setParameters(new ArrayCollection([
                new Parameter('toUser', $toUserId),
                new Parameter('fromUser', $fromUserId),
                new Parameter('direction', $direction),
            ]))
            ->getQuery()
            ->execute();
        if ($result >= 1) {
            $hasVote = true;
        }
        return $hasVote;
    }
}
