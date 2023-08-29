<?php

namespace App\Repository;

use App\Entity\ProductOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductOption>
 *
 * @method ProductOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductOption[]    findAll()
 * @method ProductOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductOption::class);
    }

    public function save(ProductOption $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductOption $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    /**
     * @return ProductOption[] Returns an array of ProductOption objects
     */
    public function findByExampleField($productoptions): array
    {
        return $this->createQueryBuilder('p')
            ->select('c','p')
            ->join('p.product', 'c')
            ->andWhere('c.id in (:product)')
            ->setParameter('product', $productoptions)
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * @return ProductOption[] Returns an array of ProductOption objects
     */
    public function getProductOptionsByProductId($productId)
{
    return $this->createQueryBuilder('po')
        ->join('po.product', 'p')
        ->where('p.id = :productId')
        ->setParameter('productId', $productId)
        ->getQuery()
        ->getResult();
}

    

//    public function findOneBySomeField($value): ?ProductOption
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
