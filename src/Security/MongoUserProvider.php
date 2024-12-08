<?php

// src/Security/MongoUserProvider.php
namespace App\Security;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class MongoUserProvider implements UserProviderInterface
{
    private $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        $user = $this->documentManager->getRepository(User::class)->findOneBy(['email' => $username]);
        if (!$user) {
            throw new Exception("User not found");
        }

        return $user;
    }

    /**
     * @throws Exception
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass(string $class): bool
    {
        return $class === User::class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->documentManager->getRepository(User::class)->findOneBy(['email' => $identifier]);
    }
}
