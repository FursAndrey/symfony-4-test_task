<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 15,
     *      charset = "UTF-8",
     *      minMessage = "Your login must be at least {{ limit }} characters long.",
     *      maxMessage = "Your login cannot be longer than {{ limit }} characters."
     * )
     * @Assert\Regex(
     *     pattern     = "/^[a-z0-9]+$/i",
     *     message="Your login can contain only numbers and letters."
     * )
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 15,
     *      charset = "UTF-8",
     *      minMessage = "Your nickname must be at least {{ limit }} characters long.",
     *      maxMessage = "Your nickname cannot be longer than {{ limit }} characters."
     * )
     * @Assert\Regex(
     *     pattern     = "/^[a-z0-9]+$/i",
     *     message="Your nickname can contain only numbers and letters."
     * )
     */
    private $nickname;

    /**
     * @ORM\Column(type="string", length=180)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 180,
     *      charset = "UTF-8",
     *      maxMessage = "Your nickname cannot be longer than {{ limit }} characters."
     * )
     * @Assert\Email()
     * @Assert\Regex(
     *     pattern     = "/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/i",
     *     message="Your email is not correct."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 32,
     *      charset = "UTF-8",
     *      minMessage = "Your password must be at least {{ limit }} characters long.",
     *      maxMessage = "Your password cannot be longer than {{ limit }} characters."
     * )
     * @Assert\Regex(
     *     pattern     = "/^[a-z0-9]+$/i",
     *     message="Your password can contain only numbers and letters."
     * )
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @Assert\GreaterThan("1900-01-01")
     * @ORM\Column(type="date", nullable=true)
     * @Assert\NotBlank()
     * @Assert\Date
     * @var string A "Y-m-d" formatted value
     */
    private $birthdate;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * Many Users have Many Products.
     * @ORM\ManyToMany(targetEntity="Product", inversedBy="users")
     * @ORM\JoinTable(name="user_product")
     */
    private $products;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Phone", mappedBy="users", orphanRemoval=true)
     */
    private $phone;

    /**
     * @return Collection|Phone[]
     */
    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone(Phone $phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->phone = new ArrayCollection();
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    public function setProducts(Product $product)
    {
        $this->products = $product;
        return $this;
    }

    /**
     * @param Product $product
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function removeProduct(Product $product)
    {
        $this->products->removeElement($product);
        return $this->products;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->nickname;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
//        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getBirthdate()
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
