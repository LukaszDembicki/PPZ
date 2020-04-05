<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\News;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * @Route("/news")
 */
class NewsController extends AbstractController
{
    /**
     * @var News
     */
    private News $news;
    /**
     * @var JsonDecode
     */
    private JsonDecode $jsonDecode;

    /**
     * NewsController constructor.
     * @param JsonDecode $jsonDecode
     * @param News $news
     */
    public function __construct(
        JsonDecode $jsonDecode,
        News $news
    )
    {
        $this->news = $news;
        $this->jsonDecode = $jsonDecode;
    }

    /**
     * @Route("/", name="news", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        return JsonResponse::create($this->news->getList());
    }

    /**
     * @Route("/save", name="news_save", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function saveNews(Request $request)
    {
        try {
            $this->news->saveNews($this->jsonDecode->decode(
                $request->getContent(), JsonEncoder::FORMAT, ['json_decode_associative' => true]
            ));
        } catch (Exception $exception) {
            return JsonResponse::create($exception->getMessage());
        }

        return JsonResponse::create('ok');
    }

    /**
     * @Route("/update", name="news_update", methods={"PUT"})
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function updateNews(Request $request)
    {
        try {
            $this->news->updateNews($this->jsonDecode->decode(
                $request->getContent(), JsonEncoder::FORMAT, ['json_decode_associative' => true]
            ));
        } catch (Exception $exception) {
            return JsonResponse::create($exception->getMessage());
        }

        return JsonResponse::create('ok');
    }

    /**
     * @Route("/remove/{id}", name="news_remove", methods={"DELETE"})
     * @param Request $request
     * @return JsonResponse
     */
    public function removeNews(Request $request)
    {
        try {
            $this->news->removeNewsById((int)$request->get('id'));
        } catch (Exception $exception) {
            return JsonResponse::create($exception->getMessage());
        }

        return JsonResponse::create('ok');
    }
}