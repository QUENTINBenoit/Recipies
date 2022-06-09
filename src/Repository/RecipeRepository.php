<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function add(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Recipe[] Returns an array of Recipe objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recipe
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // public function findByRecipiesUser($id)
    // {
    //     $qb = $this->createQueryBuilder('ig');
    //     // Je cible les ingrédients demandés($id)
    //     $qb->where('ig.id = id');
    //     $qb->setParameter(':id', $id);
    //     $qb->leftJoin('ig.user', 'user');
    //     $qb->addSelect('user');
    //     $query = $qb->getQuery();
    //     return $query->getOneOrNullResult();
    // }

    /**
     * Cette méthode permet de récupérer les recettes publiques par ordre décroissant 
     *
     * @param integer $nbRecipes
     * @return Recipe|null
     */
    public function findPubicRecipes(?int $nbRecipes): array
    {
        $qb = $this->createQueryBuilder('recipes')
            ->Where('recipes.isPublic = 1')
            ->orderBy('recipes.createdAt', 'DESC');

        if ($nbRecipes !== 0 || $nbRecipes !== null) {
            $qb->setMaxResults($nbRecipes);
        }
        return $qb->getQuery()
            ->getResult();
    }
}
