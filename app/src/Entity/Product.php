<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass:"App\Repository\ProductRepository")]
#[ORM\Table(name:"products")]
class Product
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id;

    #[ORM\Column(type:"string", length:255)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Type("string")]
    private $name;

    #[ORM\Column(type:"string", length:255)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Type("string")]
    private $description;

    #[ORM\Column(type:"float")]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Type("float")]
    #[Assert\PositiveOrZero]
    private $price;

    #[ORM\Column(type:"text")]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Type("string")]
    #[Assert\Url(message:"'{{ value }}' is not a valid url")]
    private $photo;

    #[ORM\ManyToOne(targetEntity:User::class, inversedBy:"products")]
    #[ORM\JoinColumn(nullable:false)]
    #[Ignore]
    private $user;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}