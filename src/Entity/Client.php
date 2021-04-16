<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(attributes={"normalizationContext"={"groups"={"read"}},"denormalizationContext"={"groups"={"write"}}})
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @ORM\Table(name="clients", indexes={@ORM\Index(name="client_idx", columns={"email"})})
 * @UniqueEntity(fields={"email"}, errorPath="email", message="The {{ fields }} is already in use.")
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @ApiProperty(identifier=true)
     * @Groups({"read"})
     */
    private ?int $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Application", mappedBy="clientId", orphanRemoval=true, cascade={"persist"})
     */
    private Collection $applications;

    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank(message="FirstName cannot be blank!")
     * @Assert\Regex(pattern="/[a-zA-Z]{2,32}/", match=true, message="Only latin characters from 2 to 32.")
     * @Groups({"read", "write"})
     */
    private ?string $firstName;

    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank(message="LastName cannot be blank!")
     * @Assert\Regex(pattern="/[a-zA-Z]{2,32}/", match=true, message="Only latin characters from 2 to 32.")
     * @Groups({"read", "write"})
     */
    private ?string $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Email cannot be blank!")
     * @Assert\Email(message="Email is not valid.")
     * @Groups({"read", "write"})
     */
    private ?string $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="PhoneNumber cannot be blank!")
     * @AssertPhoneNumber
     * @Groups({"read", "write"})
     */
    private ?string $phoneNumber;

    /**
     * Client constructor.
     */
    public function __construct()
    {
        $this->applications = new ArrayCollection();
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
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     * @return $this
     */
    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    /**
     * @param Application $application
     */
    public function addApplication(Application $application): void
    {
        $this->applications->add($application);
    }

    /**
     * @param Application $application
     */
    public function removeApplication(Application $application): void
    {
        $this->applications->removeElement($application);
    }
}
