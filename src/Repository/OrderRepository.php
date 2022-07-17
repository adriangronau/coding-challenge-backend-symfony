<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function persist(Order $order): void
    {
        $this->getEntityManager()->persist($order);
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
    /**
     * @return Order[]
     */
    public function findAllOrders(): array
    {
        return $this->findAll();
    }

    /**
     * @return Order[]
     */
    public function findOrdersBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->findBy($criteria, $orderBy, $limit, $offset);
    }
}
