<?php

namespace App\Repository;

use App\Entity\SongsList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;



/**
 * @extends ServiceEntityRepository<SongsList>
 *
 * @method SongsList|null find($id, $lockMode = null, $lockVersion = null)
 * @method SongsList|null findOneBy(array $criteria, array $orderBy = null)
 * @method SongsList[]    findAll()
 * @method SongsList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SongsListRepository extends ServiceEntityRepository
{

    private $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, SongsList::class);
        $this->security = $security;
    }
    
    public function save(SongsList $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SongsList $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   

    public function getCurrentUserId(): ?int
    {
        $user = $this->security->getUser();
        
        if ($user) {
            return $user->getId();
        }
        
        return null;
    }


    public function findByCurrentUser(): array
{
    $userId = $this->getCurrentUserId();

    if (!$userId) {
        return [];
    }

    return $this->createQueryBuilder('s')
        ->join('s.iduser', 'u') // Modificar 'user' a 'iduser'
        ->andWhere('u.id = :userId')
        ->setParameter('userId', $userId)
        ->getQuery()
        ->getResult();
}



//    /**
//     * @return SongsList[] Returns an array of SongsList objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SongsList
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
