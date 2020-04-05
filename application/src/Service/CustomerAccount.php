<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Utilities\Serializer\Normalizer;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * Class CustomerAccount
 * @package App\Service
 */
class CustomerAccount
{
    /**
     * @var Normalizer
     */
    private Normalizer $normalizer;

    /**
     * @var CustomerRepository
     */
    private CustomerRepository $customerRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * CustomerAccount constructor.
     * @param Normalizer $normalizer
     * @param CustomerRepository $customerRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        Normalizer $normalizer,
        CustomerRepository $customerRepository,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->normalizer = $normalizer;
        $this->customerRepository = $customerRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param array $customerData
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ExceptionInterface
     */
    public function register(array $customerData): void
    {
        /** @var Customer $customerObject */
        $customerObject = $this->normalizer->denormalize($customerData, Customer::class);
        $customerObject->setPassword(
            $this->passwordEncoder->encodePassword($customerObject, $customerObject->getPassword())
        );

        $this->customerRepository->saveCustomer($customerObject);
    }
}