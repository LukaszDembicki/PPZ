<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\PeopleCountryChart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method PeopleCountryChart|null find($id, $lockMode = null, $lockVersion = null)
 * @method PeopleCountryChart|null findOneBy(array $criteria, array $orderBy = null)
 * @method PeopleCountryChart[]    findAll()
 * @method PeopleCountryChart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeopleCountryChartRepository extends ServiceEntityRepository
{
    /**
     * PeopleCountryChartRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PeopleCountryChart::class);
    }

    /**
     * @param string $countryName
     * @return PeopleCountryChart|null
     */
    public function getRowByCountry(string $countryName): PeopleCountryChart
    {
        $row = $this->findOneBy([
            'countryName' => $countryName
        ]);

        if (!$row) {
            return null;
        }

        return $row;
    }

    /**
     * @param PeopleCountryChart $peopleCountryData
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(PeopleCountryChart $peopleCountryData): void
    {
        $entityManger = $this->getEntityManager();
        $entityManger->persist($peopleCountryData);
        $entityManger->flush();
    }

    /**
     * @return array
     */
    public function getPeopleCountryData(): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.countryName', 'c.numberOfPeople')
            ->setMaxResults(200)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);
    }
}
