<?php

namespace App\Action;

use App\Entity\Balance;
use App\Entity\Currency;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class CreateBalanceAction
{

    /**
     * @var Security
     */
    private Security $security;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Balance $data
     * @return Balance
     */
    public function __invoke(Balance $data): Balance
    {

        /** @var User $user */
        $user = $this->security->getUser();

        if (!$user) {
            throw new AccessDeniedException("You must be authorized");
        }

        if (!$data->getCurrency()) {
            $data->setCurrency($this->entityManager->getRepository(Currency::class)->findOneBy([
                'id' => 1
            ]));
        }

        $data->setUser($user);

        return $data;
    }

}