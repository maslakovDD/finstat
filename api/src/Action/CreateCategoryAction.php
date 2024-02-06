<?php

namespace App\Action;

use App\Entity\Category;
use App\Entity\User;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class CreateCategoryAction
{

    /**
     * @var Security
     */
    private Security $security;

    /**
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param Category $data
     * @return Category
     */
    public function __invoke(Category $data): Category
    {

        /** @var User $user */
        $user = $this->security->getUser();

        if (!$user) {
            throw new AccessDeniedException("You must be authorized");
        }

        $data->setUser($user);

        return $data;
    }

}