<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @UniqueEntity(fields={"name"},message="Category with this name already exists.")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 15,
     *      charset = "UTF-8",
     *      minMessage = "Your category name must be at least {{ limit }} characters long.",
     *      maxMessage = "Your category name cannot be longer than {{ limit }} characters."
     * )
     * @Assert\Regex(
     *     pattern     = "/^[АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЭЮЯ]*[a-z 0-9абвгдеёжзийклмнопрстуфхцчшщьыъэюя]+$/i",
     *     message="Your category name can contain only numbers and letters."
     * )
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\ManyToMany(targetEntity = "Product", mappedBy = "category")
     * @ORM\JoinTable(name="category_product")
     */
    private $products;

    /**
     * @return Collection|Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function __toString()
    {
        return $this->getName();
    }
}
