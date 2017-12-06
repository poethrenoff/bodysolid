<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\Category;

/**
 * ProductRepository
 */
class ProductRepository extends EntityRepository
{
    /**
     * @param Category $category
     * @param int $offset
     * @param int $limit
     * @return Paginator
     */
    public function findByCategory(Category $category, int $offset, int $limit): Paginator
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.category = :category')
            ->andWhere('p.active = :active')
            ->orderBy('p.price', 'asc')
            ->setParameter('category', $category->getId())
            ->setParameter('active', true)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return new Paginator($qb, false);
    }

    /**
     * @return array
     */
    public function findByBest(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->innerJoin('p.category', 'c')
            ->where('p.best = :best')
            ->andwhere('p.active = :active')
            ->andWhere('c.active = :active')
            ->setParameter('best', true)
            ->setParameter('active', true);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $text
     * @param int $offset
     * @param int $limit
     * @return Paginator
     */
    public function findByText(string $text, int $offset, int $limit): Paginator
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->innerJoin('p.category', 'c')
            ->where('p.active = :active')
            ->andWhere('c.active = :active')
            ->orderBy('p.price', 'asc')
            ->setParameter('active', true)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $words = $text !== '' ? preg_split('/\s+/isu', $text) : array();
        foreach ($words as $wordIndex => $word) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('lower(p.title)', 'lower(:word' . $wordIndex . ')'),
                    $qb->expr()->like('lower(p.description)', 'lower(:word' . $wordIndex . ')')
                )
            );
            $qb->setParameter('word' . $wordIndex, '%' . $word . '%');
        }

        return new Paginator($qb, false);
    }
}