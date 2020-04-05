<?php

namespace App\Controller;

use App\Service\CustomerAccount;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * @Route("/customer")
 */
class CustomerController extends AbstractController
{

    /**
     * @var CustomerAccount
     */
    private CustomerAccount $customerAccount;
    /**
     * @var JsonDecode
     */
    private JsonDecode $jsonDecode;

    public function __construct(
        CustomerAccount $customerAccount,
        JsonDecode $jsonDecode
    )
    {

        $this->customerAccount = $customerAccount;
        $this->jsonDecode = $jsonDecode;
    }

    /**
     * @Route("/register", name="customer_register", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ExceptionInterface
     */
    public function register(Request $request)
    {
        $this->customerAccount->register($this->jsonDecode->decode(
            $request->getContent(), JsonEncoder::FORMAT, ['json_decode_associative' => true]
        ));

        return JsonResponse::create('Account created');
    }
}
