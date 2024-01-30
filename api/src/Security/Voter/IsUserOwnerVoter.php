<?php

namespace App\Security\Voter;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class IsUserOwnerVoter extends Voter
{

    public const IS_USER_OWNER_CHECK = "IS_USER_OWNER_CHECK";

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::IS_USER_OWNER_CHECK;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param JWTUserToken $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {

        /** @var User $user */
        $user = $token->getUser();

        if (!$user) {
            return false;
        }

        if ($user->getId() == $subject->getId()) {
            return true;
        }

        return false;
    }

}