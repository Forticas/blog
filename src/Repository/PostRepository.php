<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Post $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Post $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function countByCategory(Category $category, bool $isPublished = true)
    {
        $query = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->andWhere(':category_id MEMBER OF p.categories')
            ->setParameter('category_id', $category->getId());
        $query->andWhere('p.isPublished = true');
        return $query->getQuery()->getSingleScalarResult();
    }
    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findByCategory(Category $category, bool $isPublished = true, int $limit = null, int $offset = null): array
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere(':category_id MEMBER OF p.categories')
            ->setParameter('category_id', $category->getId())
            ->orderBy('p.createdAt', 'DESC');
        if ($isPublished) $query->andWhere('p.isPublished = true');
        if ($limit) $query->setMaxResults($limit);
        if ($offset) $query->setFirstResult($offset);


        return $query->getQuery()->getResult();
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findAllByPagination(int $limit = null, int $offset = null, bool $isPublished = true): array
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC');
        if ($isPublished) $query->andWhere('p.isPublished = true');
        if ($limit) $query->setMaxResults($limit);
        if ($offset) $query->setFirstResult($offset);


        return $query->getQuery()->getResult();
    }

    // add count by tag
    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function countByTag(Tag $tag, bool $isPublished = true)
    {
        $query = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->andWhere(':tag_id MEMBER OF p.tags')
            ->setParameter('tag_id', $tag->getId());
        if ($isPublished) $query->andWhere('p.isPublished = true');
        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findByTag(Tag $tag, bool $isPublished = true, int $limit = null, int $offset = null): array
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere(':tag_id MEMBER OF p.tags')
            ->setParameter('tag_id', $tag->getId())
            ->orderBy('p.createdAt', 'DESC');
        if ($isPublished) $query->andWhere('p.isPublished = true');
        if ($limit) $query->setMaxResults($limit);
        if ($offset) $query->setFirstResult($offset);

        return $query->getQuery()->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
