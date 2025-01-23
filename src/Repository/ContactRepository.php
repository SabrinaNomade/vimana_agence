<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    // Exemple de méthode personnalisée pour trouver un contact par nom
    public function findByNom($nom)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.nom = :nom')
            ->setParameter('nom', $nom)
            ->getQuery()
            ->getResult();
    }
}
