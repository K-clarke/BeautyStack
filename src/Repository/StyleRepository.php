<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Style;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Style|null find($id, $lockMode = null, $lockVersion = null)
 * @method Style|null findOneBy(array $criteria, array $orderBy = null)
 * @method Style[]    findAll()
 * @method Style[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StyleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Style::class);
    }

    public function findBySearchCriteria($search): ?array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.description like :val')
            ->setParameter('val', '%' . $search . '%')
            ->getQuery()
            ->getResult();
    }
}
