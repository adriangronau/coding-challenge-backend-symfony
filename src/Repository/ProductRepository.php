<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function persist(Product $product): void
    {
        $this->getEntityManager()->persist($product);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    ///////////////////////////////////////////////////////////////////////////
    // The following methods are declared explicitly so that the reader can be
    // ensured what the return types are (without using an IDE that would infer
    // that). For an actual project, these methods wouldn't be needed.
    ///////////////////////////////////////////////////////////////////////////
    public function findProduct($id, $lockMode = null, $lockVersion = null): ?Product
    {
        return $this->find($id, $lockMode, $lockVersion);
    }
}
