<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
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

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Product $entity, bool $flush = true): void
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
    public function remove(Product $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Requête qui me permet de récupérer les produits en fonction de la recherche de l'utilisateur 
     * @return Product[]
     */
    public function findWitchSearch(Search $search)
    {
        $query = $this //requête qui permet d'appeler les tables product (p) et category (c) 
            ->createQueryBuilder('p') 
            ->select('c', 'p')
            ->join('p.category', 'c');

        if(!empty($search->categories)) //si une recherche est lancé par l'utilisateur avec les checbox
        { //chercher les resultats en fonction de la recherche par categories
            $query = $query
            ->andWhere('c.id IN (:categories)')
            ->setParameter('categories',$search->categories);
        }

        if(!empty($search->string)) //si une recherche est lancé par l'utilisateur avec un mot entré dans la barre de recherche
        { //chercher les resultats en fonction de la recherche dans l'input
            $query = $query
            ->andWhere('p.name LIKE :string')
            ->setParameter('string',"%{$search->string}%"); //permet de faire une recherche partiel 
        }

        return $query->getQuery()->getResult(); //on affiche les resultats
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
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
}
