<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Action\CreateCategoryAction;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

//TODO: add security for patch and delete only if user owned this category (add voters)
#[
    ApiResource(
        collectionOperations: [
            "get"  => [
                "method"                => "GET",
                "normalization_context" => ['groups' => ['get:collection:category']]
            ],
            "post" => [
                "method"                  => "POST",
                "denormalization_context" => ['groups' => ['post:collection:category']],
                "normalization_context"   => ["groups" => ["get:item:category"]],
                "security"                => "is_granted('" . User::ROLE_USER . "')",
                "controller"              => CreateCategoryAction::class
            ]
        ],
        itemOperations: [
            "get"    => [
                "method"                => "GET",
                "normalization_context" => ['groups' => ['get:item:category']],
            ],
            "patch"  => [
                "method"                  => "PATCH",
                "denormalization_context" => ['groups' => ["patch:item:category"]],
                "normalization_context"   => ['groups' => ['get:item:category']],
            ],
            "delete" => [
                "method" => "DELETE",
            ]
        ]
    )
]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{

    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        "get:item:category",
        "get:collection:category"
    ])]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "get:item:category",
        "get:collection:category",
        "post:collection:category",
        "patch:collection:category"
    ])]
    private ?string $title = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([
        "get:item:category",
        "get:collection:category",
        "post:collection:category",
        "patch:collection:category"
    ])]
    private ?string $picture = null;

    /**
     * @var User|null
     */
    #[ORM\ManyToOne(inversedBy: 'categories')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        "get:item:category",
        "get:collection:category",
    ])]
    private ?User $user = null;

    /**
     * @var Collection
     */
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Record::class)]
    private Collection $records;

    /**
     * Category constructor
     */
    public function __construct()
    {
        $this->records = new ArrayCollection();
    }

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
    public function getPicture(): ?string
    {
        return $this->picture;
    }

    /**
     * @param string|null $picture
     * @return $this
     */
    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Record>
     */
    public function getRecords(): Collection
    {
        return $this->records;
    }

    /**
     * @param Record $record
     * @return $this
     */
    public function addRecord(Record $record): self
    {
        if (!$this->records->contains($record)) {
            $this->records->add($record);
            $record->setCategory($this);
        }

        return $this;
    }

    /**
     * @param Record $record
     * @return $this
     */
    public function removeRecord(Record $record): self
    {
        if ($this->records->removeElement($record)) {
            // set the owning side to null (unless already changed)
            if ($record->getCategory() === $this) {
                $record->setCategory(null);
            }
        }

        return $this;
    }

}
