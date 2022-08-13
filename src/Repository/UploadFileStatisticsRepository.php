<?php

namespace Dokobit\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Dokobit\Entity\UploadFileStatistics;

/**
 * @extends ServiceEntityRepository<UploadFileStatistics>
 *
 * @method UploadFileStatistics|null find($id, $lockMode = null, $lockVersion = null)
 * @method UploadFileStatistics|null findOneBy(array $criteria, array $orderBy = null)
 * @method UploadFileStatistics[]    findAll()
 * @method UploadFileStatistics[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UploadFileStatisticsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UploadFileStatistics::class);
    }
}
