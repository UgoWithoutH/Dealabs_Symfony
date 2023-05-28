<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @extends ServiceEntityRepository<Utilisateur>
 *
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    private $passwordEncoder;

    public function __construct(ManagerRegistry $registry, UserPasswordHasherInterface $passwordEncoder)
    {
        parent::__construct($registry, Utilisateur::class);
        $this->passwordEncoder = $passwordEncoder;
    }

    public function save(Utilisateur $entity, bool $flush = false): void
    {
        $hashedPassword = $this->passwordEncoder->hashPassword(
            $entity,
            $entity->getMotDePasse()
        );
        $entity->setMotDePasse($hashedPassword);

        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Utilisateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findUserconnection(Utilisateur $entity): ?Utilisateur
    {
        $utilisateur = $this->findOneBy(['email' => $entity->getEmail()]);
        if($utilisateur == null){
            $utilisateur = $this->findOneBy(['pseudo' => $entity->getPseudo()]);
        }

        if ($utilisateur && $this->passwordEncoder->isPasswordValid($utilisateur, $entity->getMotDePasse())) {
            return $utilisateur;
        }

        return null;
    }

//    /**
//     * @return Utilisateur[] Returns an array of Utilisateur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Utilisateur
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
