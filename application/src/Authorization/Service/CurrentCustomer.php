<?php

declare(strict_types=1);

namespace App\Authorization\Service;

use App\Entity\Customer;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class CurrentCustomer
 * @package App\Authorization\Service
 */
class CurrentCustomer
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(
        TokenStorageInterface $tokenStorage
    )
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return int
     */
    public function getCustomerId(): int
    {
        return (int)$this->tokenStorage->getToken()->getUser()->getId();
    }

    /**
     * @return TokenInterface|null
     */
    public function getToken(): ?TokenInterface
    {
        if (!$this->tokenStorage->getToken()) {
            throw new AccessDeniedException('Token do not exists');
        }

        return $this->tokenStorage->getToken();
    }

    /**
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}
