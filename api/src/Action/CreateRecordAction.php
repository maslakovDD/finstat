<?php

namespace App\Action;

use App\Entity\Category;
use App\Entity\Record;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateRecordAction
{

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
     * @param Record $data
     * @return Record
     */
    public function __invoke(Record $data): Record
    {

        /** @var Category $category */
        $category = $this->entityManager->getRepository(Category::class)->findOneBy([
            'id' => $data->getCategory()
        ]);

        if (!$category) {
            throw new NotFoundHttpException("Category not found");
        }

        $data
            ->setCategory($category)
            ->setCreatedDate(time());

        return $data;
    }

}