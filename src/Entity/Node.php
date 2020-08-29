<?php

namespace App\Entity;

use App\Form\Constraint as CustomConstraint;
use App\Repository\NodeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Constraint;
use Web3\Utils;

/**
 * @ORM\Entity(repositoryClass=NodeRepository::class)
 * @UniqueEntity("address", message="This address is already in list.")
 */
class Node
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true, length=42)
     * @Constraint\NotBlank
     * @CustomConstraint\EthAddress
     */
    private $address;

    /**
     * @ORM\Column(type="decimal", precision=32, scale=18)
     */
    private $unbondedValue;

    /**
     * @ORM\Column(type="text")
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdTs;

    public function __construct()
    {
        $this->createdTs = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = Utils::toChecksumAddress($address);

        return $this;
    }

    public function getUnbondedValue(): ?string
    {
        return $this->unbondedValue;
    }

    public function setUnbondedValue(string $unbondedValue): self
    {
        $this->unbondedValue = $unbondedValue;

        return $this;
    }

    public function getCreatedTs(): ?\DateTimeInterface
    {
        return $this->createdTs;
    }

    public function setCreatedTs(\DateTimeInterface $createdTs): self
    {
        $this->createdTs = $createdTs;

        return $this;
    }

    public function compileListView()
    {
        return [
            'id' => $this->id,
            'address' => $this->address,
            'addressView' => sprintf('%s...%s', substr($this->address, 0, 8), substr($this->address, -5)),
            'unbondedValue' => bcadd($this->unbondedValue, '0', 8),
            'dateCreated' => $this->createdTs->format('Y-m-d H:i'),
            'img' => $this->image,
        ];
    }

    public function setImage($image): void
    {
        $this->image = $image;
    }
}
