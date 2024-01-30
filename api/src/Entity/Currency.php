<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CurrencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[
    ApiResource(
        collectionOperations: [
            "get"  => [
                "method" => "GET",
                "normalization_context" => ['groups' => ['get:collection:currency']]
            ],
            "post" => [
                "method"                  => "POST",
                "denormalization_context" => ['groups' => ['post:collection:currency']],
                "normalization_context"   => ["groups" => ["get:item:currency"]],
            ]
        ],
        itemOperations: [
            "get"    => [
                "method"                => "GET",
                "normalization_context" => ['groups' => ['get:item:currency']],
            ],
            "patch"  => [
                "method"                  => "PATCH",
                "denormalization_context" => ['groups' => ["patch:item:currency"]],
                "normalization_context"   => ['groups' => ['get:item:currency']],
            ],
            "delete" => [
                "method"   => "DELETE",
            ]
        ]
    )
]
#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency
{

    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        "get:item:currency",
        "get:collection:currency"
    ])]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups([
        "get:item:currency",
        "get:collection:currency",
        "post:collection:currency",
        "patch:item:currency"
    ])]
    private ?string $title = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 4)]
    #[Groups([
        "get:item:currency",
        "get:collection:currency",
        "post:collection:currency",
        "patch:item:currency"
    ])]
    private ?string $dollarCoef = null;

    /**
     * @var Collection
     */
    #[ORM\OneToMany(mappedBy: 'currency', targetEntity: Balance::class)]
    private Collection $balances;

    /**
     * Currency constructor
     */
    public function __construct()
    {
        $this->balances = new ArrayCollection();
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
    public function getDollarCoef(): ?string
    {
        return $this->dollarCoef;
    }

    /**
     * @param string $dollarCoef
     * @return $this
     */
    public function setDollarCoef(string $dollarCoef): self
    {
        $this->dollarCoef = $dollarCoef;

        return $this;
    }

    /**
     * @return Collection<int, Balance>
     */
    public function getBalances(): Collection
    {
        return $this->balances;
    }

    /**
     * @param Balance $balance
     * @return $this
     */
    public function addBalance(Balance $balance): self
    {
        if (!$this->balances->contains($balance)) {
            $this->balances->add($balance);
            $balance->setCurrency($this);
        }

        return $this;
    }

    /**
     * @param Balance $balance
     * @return $this
     */
    public function removeBalance(Balance $balance): self
    {
        if ($this->balances->removeElement($balance)) {
            // set the owning side to null (unless already changed)
            if ($balance->getCurrency() === $this) {
                $balance->setCurrency(null);
            }
        }

        return $this;
    }

}
