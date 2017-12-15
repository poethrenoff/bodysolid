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
     * @return array
     */
    public function findByCategory(Category $category): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.category = :category')
            ->andWhere('p.active = :active')
            ->orderBy('p.price', 'asc')
            ->addOrderBy('p.priceUsd', 'asc')
            ->setParameter('category', $category->getId())
            ->setParameter('active', true);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $text
     * @return array
     */
    public function findByText(string $text): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->innerJoin('p.category', 'c')
            ->where('p.active = :active')
            ->andWhere('c.active = :active')
            ->orderBy('p.title', 'asc')
            ->setParameter('active', true);

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

        return $qb->getQuery()->getResult();
    }
}