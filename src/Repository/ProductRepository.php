<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * Class ProductRepository
 * @package App\Repository
 */

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param $category_id
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findProducForCategory($category_id):array
    {
        $conn=$this->getEntityManager()->getConnection();

        $sql='
        SELECT name_product, text FROM Product p
        WHERE p.category_id = :id
        ';

        $stmt=$conn->prepare($sql);
        $stmt->execute(['id' => $category_id]);

        return $stmt->fetchAll();
    }

    /**
     * @param $product_slug
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findProducText($product_slug):array
    {
        $conn=$this->getEntityManager()->getConnection();

        $sql='
        SELECT text FROM Product p
        WHERE p.name_product = :product_slug
        ';

        $stmt=$conn->prepare($sql);
        $stmt->execute(['product_slug' => $product_slug]);

        return $stmt->fetchAll();
    }
}
