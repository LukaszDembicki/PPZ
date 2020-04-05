<?php

declare(strict_types=1);

namespace App\Model;

use App\Authorization\Service\CurrentCustomer;
use App\Entity\News as NewsEntity;
use App\Repository\NewsRepository;
use App\Utilities\Serializer\Normalizer;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * Class News
 * @package App\Model
 */
class News
{
    /**
     * @var NewsRepository
     */
    private NewsRepository $newsRepository;
    /**
     * @var Normalizer
     */
    private Normalizer $normalizer;
    /**
     * @var CurrentCustomer
     */
    private CurrentCustomer $currentCustomer;

    /**
     * News constructor.
     * @param Normalizer $normalizer
     * @param NewsRepository $newsRepository
     * @param CurrentCustomer $currentCustomer
     */
    public function __construct
    (
        Normalizer $normalizer,
        NewsRepository $newsRepository,
        CurrentCustomer $currentCustomer
    )
    {
        $this->normalizer = $normalizer;
        $this->newsRepository = $newsRepository;
        $this->currentCustomer = $currentCustomer;
    }

    /**
     * @return News[]
     */
    public function getList(): array
    {
        return $this->newsRepository->getList();
    }

    /**
     * @param array $newsData
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ExceptionInterface
     */
    public function saveNews(array $newsData)
    {
        /** @var \App\Entity\News $newsEntity */
        $newsEntity = $this->normalizer->denormalize($newsData, NewsEntity::class);
        $newsEntity->setAuthorId($this->currentCustomer->getCustomer());

        $this->newsRepository->saveNews($newsEntity);
    }

    public function updateNews(array $newsData)
    {
        /** @var \App\Entity\News $newsEntity */
        $newsEntity = $this->normalizer->denormalize($newsData, NewsEntity::class);
        $newsEntity->setAuthorId($this->currentCustomer->getCustomer());

        $this->newsRepository->updateNews($newsEntity);
    }

    /**
     * @param int $id
     * @throws EntityNotFoundException
     * @throws ORMException
     */
    public function removeNewsById(int $id)
    {
        $this->newsRepository->removeById($id);
    }
}