<?php

namespace App\Repository;

use App\Authorization\Service\CurrentCustomer;
use App\Entity\News;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    /**
     * @var CurrentCustomer
     */
    private CurrentCustomer $currentCustomer;

    public function __construct(
        ManagerRegistry $registry,
        CurrentCustomer $currentCustomer
    )
    {
        parent::__construct($registry, News::class);
        $this->currentCustomer = $currentCustomer;
    }

    /**
     * @return array
     */
    public function getList(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.authorId = :authorId')
            ->setParameter('authorId', $this->currentCustomer->getCustomerId())
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(200)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);
    }

    /**
     * @param News $news
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveNews(News $news): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($news);
        $entityManager->flush();
    }

    /**
     * @param News $news
     * @throws EntityNotFoundException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateNews(News $news): void
    {
        if (!$news->getId()) {
            throw new EntityNotFoundException();
        }

        $newsFromDatabase = $this->findOneBy([
            'id' => $news->getId(),
            'authorId' => $this->currentCustomer->getCustomerId()
        ]);

        if (!$newsFromDatabase) {
            throw new EntityNotFoundException();
        }

        $newsFromDatabase->setName($news->getName());
        $newsFromDatabase->setDescription($news->getDescription());
        $newsFromDatabase->setUpdatedAt(new DateTime('now'));

        $entityManager = $this->getEntityManager();
        $entityManager->flush();
    }

    /**
     * @param int $id
     * @throws EntityNotFoundException
     * @throws ORMException
     */
    public function removeById(int $id): void
    {
        $news = $this->findOneBy([
            'id' => $id,
            'authorId' => $this->currentCustomer->getCustomerId()
        ]);

        if (!$news) {
            throw  new EntityNotFoundException(
                sprintf('News with id %s do not exists', $id)
            );
        }

        $entityManager = $this->getEntityManager();
        $entityManager->remove($news);
        $entityManager->flush();
    }
}
