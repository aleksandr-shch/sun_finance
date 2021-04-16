<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ApplicationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(attributes={"normalizationContext"={"groups"={"read"}},"denormalizationContext"={"groups"={"write"}}})
 * @ORM\Entity(repositoryClass=ApplicationRepository::class)
 * @ORM\Table(name="applications", indexes={@ORM\Index(name="application_idx", columns={"client_id"})})
 */
class Application
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="applications", cascade={"persist"})
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * @ApiProperty(identifier=true)
     * @Groups({"read", "write"})
     */
    private Client $clientId;

    /**
     * @ORM\Column(type="integer", length=2)
     * @Groups({"read", "write"})
     * @Assert\NotBlank(message="Term cannot be blank!")
     * @Assert\Type(type="integer", message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\Range(min="10", max="30")
     */
    private ?int $term;

    /**
     * @ORM\Column(type="decimal", scale=2, precision=6)
     * @Groups({"read", "write"})
     * @Assert\NotBlank(message="Amount cannot be blank!")
     * @Assert\Type(type="numeric", message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\Range(min="100", max="5000")
     */
    private ?float $amount;

    /**
     * @ORM\Column(type="string", length=3)
     * @Groups({"read", "write"})
     * @Assert\NotBlank(message="Currency cannot be blank!")
     * @Assert\Regex(pattern="/[a-zA-Z]{3}/", match=true, message="Only latin characters of length three.")
     */
    private ?string $currency;

    /**
     * @return Client
     */
    public function getClientId(): Client
    {
        return $this->clientId;
    }

    /**
     * @param $clientId
     * @return $this
     */
    public function setClientId($clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTerm(): ?int
    {
        return $this->term;
    }

    /**
     * @param int $term
     * @return $this
     */
    public function setTerm(int $term): self
    {
        $this->term = $term;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return $this
     */
    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return $this
     */
    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }
}
