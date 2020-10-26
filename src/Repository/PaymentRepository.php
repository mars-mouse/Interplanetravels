<?php

namespace App\Repository;

use App\Entity\Payment;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Payment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payment[]    findAll()
 * @method Payment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    /**
     * Permet de trouver un moyen de paiement déjà enregistré par l'utilisateur
     * en comparant avec les données du paiement en argument
     * Renvoie null ou le SavedPayment identique
     */
    public function findIdenticalPaymentFromUser(Payment $payment, User $user)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.savedPayment', 'sp')
            ->andWhere('sp.user = :u')
            ->setParameter('u', $user->getId())
            ->andWhere('p.addressBilling = :ab')
            ->setParameter('ab', $payment->getAddressBilling())
            ->andWhere('p.addressDelivery = :ad')
            ->setParameter('ad', $payment->getAddressDelivery())
            ->andWhere('p.cardNumber = :cn')
            ->setParameter('cn', $payment->getCardNumber())
            ->andWhere('p.cardType = :ct')
            ->setParameter('ct', $payment->getCardType())
            ->andWhere('p.crypto = :cp')
            ->setParameter('cp', $payment->getCrypto())
            ->andWhere('p.dateExpiration = :de')
            ->setParameter('de', $payment->getDateExpiration())
            ->andWhere('p.fullName = :fn')
            ->setParameter('fn', $payment->getFullName())
            ->getQuery()
            ->getResult();
    }
}
