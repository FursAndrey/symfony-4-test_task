<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @UniqueEntity(fields={"name"},message="Product with this name already exists.")
 */
class Product
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
     *      minMessage = "Your product name must be at least {{ limit }} characters long.",
     *      maxMessage = "Your product name cannot be longer than {{ limit }} characters."
     * )
     * @Assert\Regex(
     *     pattern     = "/^[a-z 0-9абвгдеёжзийклмнопрстуфхцчшщьыъэюя]+$/i",
     *     message="Your product name can contain only numbers and letters."
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 1,
     *      max = 9,
     *      charset = "UTF-8",
     *      minMessage = "Your price must be at least {{ limit }} characters long.",
     *      maxMessage = "Your price cannot be longer than {{ limit }} characters."
     * )
     * @Assert\Regex(
     *     pattern     = "/^[0-9]+(\.([0-9]){1,2})?$/",
     *     message="Your price can contain only numbers."
     * )
     */
    private $price;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * Many Users have Many Products.
     * @ORM\ManyToMany(targetEntity="User", mappedBy="products")
     * @ORM\JoinTable(name="user_product")
     */
    private $users;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="products")
     */
    private $category;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @param Category $category
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function removeCategory(Category $category)
    {
        $this->category->removeElement($category);
        return $this->category;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers(User $user)
    {
        $this->users = $user;
        return $this;
    }

    /**
     * @param User $user
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
        return $this->users;
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }
}
