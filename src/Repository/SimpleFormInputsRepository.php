<?php

namespace App\Repository;

use App\Entity\SimpleFormInput;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Order;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SimpleFormInput>
 */
class SimpleFormInputsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SimpleFormInput::class);
    }

    public function save(SimpleFormInput $simpleFormInput): SimpleFormInput
    {
        $em = $this->getEntityManager();
        $em->persist($simpleFormInput);
        $em->flush();

        return $simpleFormInput;
    }

    public function getInputs()
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.id', Order::Descending->value)
            ->getQuery()
            ->getResult();
    }
}
