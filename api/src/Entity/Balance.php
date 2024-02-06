<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Action\CreateBalanceAction;
use App\Repository\BalanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[
    ApiResource(
        collectionOperations: [
            "get"  => [
                "method"                => "GET",
                "normalization_context" => ['groups' => ['get:collection:balance']]
            ],
            "post" => [
                "method"                  => "POST",
                "denormalization_context" => ['groups' => ['post:collection:balance']],
                "normalization_context"   => ["groups" => ["get:item:balance"]],
                "controller"              => CreateBalanceAction::class
            ]
        ],
        itemOperations: [
            "get"    => [
                "method"                => "GET",
                "normalization_context" => ['groups' => ['get:item:balance']],
            ],
            "patch"  => [
                "method"                  => "PATCH",
                "denormalization_context" => ['groups' => ["patch:item:balance"]],
                "normalization_context"   => ['groups' => ['get:item:balance']],
            ],
            "delete" => [
                "method" => "DELETE",
            ]
        ]
    )
]
#[ORM\Entity(repositoryClass: BalanceRepository::class)]
class Balance
{

    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        "get:item:balance",
        "get:collection:balance"
    ])]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "get:item:balance",
        "get:collection:balance",
        "post:collection:balance",
        "patch:item:balance"
    ])]
    private ?string $title = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    #[Groups([
        "get:item:balance",
        "get:collection:balance",
        "post:collection:balance",
        "patch:item:balance"
    ])]
    private ?string $amount = null;

    /**
     * @var Currency|null
     */
    #[ORM\ManyToOne(inversedBy: 'balances')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        "get:item:balance",
        "get:collection:balance",
        "patch:item:balance"
    ])]
    private ?Currency $currency = null;

    /**
     * @var User|null
     */
    #[ORM\ManyToOne(inversedBy: 'balances')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        "get:item:balance",
        "get:collection:balance"
    ])]
    private ?User $user = null;

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
    public function getAmount(): ?string
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     * @return $this
     */
    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return Currency|null
     */
    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    /**
     * @param Currency|null $currency
     * @return $this
     */
    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

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

}
