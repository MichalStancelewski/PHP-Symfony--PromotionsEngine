<?php

namespace App\Tests;

use App\Repository\AuthKeyRepository;
use App\Service\ServiceException;
use App\Service\ServiceExceptionData;
use phpDocumentor\Reflection\Types\Boolean;

class AuthorizationValidation
{
    public function __construct(private AuthKeyRepository $authKeyRepository)
    {
    }

    public function validate(string $authorizationKey): bool
    {
        if (!($authorizationKey == $this->getKey())) {
            $accessExceptionData = new ServiceExceptionData(403, 'Access denied.');
            throw new ServiceException($accessExceptionData);
        }
        return true;
    }

    private function getKey(): string
    {
        return $this->authKeyRepository->find(1)->getKeyName();
    }
}