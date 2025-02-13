<?php

namespace App\Repository;

use App\Entity\Email;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Email|null find($id, $lockMode = null, $lockVersion = null)
 * @method Email|null findOneBy(array $criteria, array $orderBy = null)
 * @method Email[]    findAll()
 * @method Email[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Email::class);
    }

    // Méthode pour sauvegarder un email dans la base de données
    public function saveEmail($recipient, $subject, $content)
    {
        $email = new Email();
        $email->setRecipient($recipient);
        $email->setSubject($subject);
        $email->setContent($content);
        $email->setSentAt(new \DateTime());

        $this->_em->persist($email);
        $this->_em->flush();
    }
}
