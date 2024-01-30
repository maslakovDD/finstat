<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RecordRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[
    ApiResource(
        collectionOperations: [
            "get"  => [
                "method" => "GET",
                "normalization_context" => ['groups' => ['get:collection:record']]
            ],
            "post" => [
                "method"                  => "POST",
                "denormalization_context" => ['groups' => ['post:collection:record']],
                "normalization_context"   => ["groups" => ["get:item:record"]],
            ]
        ],
        itemOperations: [
            "get"    => [
                "method"                => "GET",
                "normalization_context" => ['groups' => ['get:item:record']],
            ],
            "patch"  => [
                "method"                  => "PATCH",
                "denormalization_context" => ['groups' => ["patch:item:record"]],
                "normalization_context"   => ['groups' => ['get:item:record']],
            ],
            "delete" => [
                "method"   => "DELETE",
            ]
        ]
    )
]
#[ORM\Entity(repositoryClass: RecordRepository::class)]
class Record
{

    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        "get:item:record",
        "get:collection:record"
    ])]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "get:item:record",
        "get:collection:record",
        "post:collection:record",
        "patch:item:record"
    ])]
    private ?string $title = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([
        "get:item:record",
        "get:collection:record",
        "post:collection:record",
        "patch:item:record"
    ])]
    private ?string $description = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    #[Groups([
        "get:item:record",
        "get:collection:record",
        "post:collection:record",
        "patch:item:record"
    ])]
    private ?string $sum = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    #[Groups([
        "get:item:record",
        "get:collection:record",
        "post:collection:record"
    ])]
    private ?string $createdDate = null;

    /**
     * @var Category|null
     */
    #[ORM\ManyToOne(inversedBy: 'records')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        "get:item:record",
        "get:collection:record",
        "post:collection:record",
        "patch:item:record"
    ])]
    private ?Category $category = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSum(): ?string
    {
        return $this->sum;
    }

    /**
     * @param string $sum
     * @return $this
     */
    public function setSum(string $sum): self
    {
        $this->sum = $sum;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCreatedDate(): ?string
    {
        return $this->createdDate;
    }

    /**
     * @param string|null $createdDate
     * @return $this
     */
    public function setCreatedDate(?string $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return $this
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
