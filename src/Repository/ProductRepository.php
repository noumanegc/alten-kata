<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Récupère la liste des produits avec pagination
     * 
     * @return array{
     *     items: list<Product>,
     *     total: int,
     *     page: int,
     *     limit: int,
     *     lastPage: int
     * }
     */
    public function getPaginatedList(?int $page = 1, ?int $limit = 10): array
    {
        $page = $page ?? 1;
        $limit = $limit ?? 10;
        $offset = ($page - 1) * $limit;

        /** @var list<Product> $products */
        $products = $this->createQueryBuilder('p')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();

        $total = $this->count([]);

        return [
            'items' => $products,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'lastPage' => (int) ceil($total / $limit)
        ];
    }
}
