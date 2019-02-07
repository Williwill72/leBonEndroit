<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
    * @return User[] Returns an array of User objects
    */

    public function findByUserId(User $user)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :val')
            ->setParameter('val', $user)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    public function remove($id)
    {
        $dql = "DELETE App\Entity\Article a
                WHERE a.id = :id";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter(':id', $id);
        $query->execute();
    }

    public function findByFavorite()
    {
        //Le query builder sait que l'on utilise l'entity Question
        //car on est dans le QuestionRepository
        $qd = $this->createQueryBuilder('a');
        //Pour lui dire l'entity que l'on veut utiliser (même si c'est sous-entendue)
        //$qd->from(Question::class);

        $qd->join('a.users', 'u')
            ->join('u.favorite', 'f');

        $query = $qd->getQuery();
        $questions = $query->getResult();
        return $questions;
    }

}
