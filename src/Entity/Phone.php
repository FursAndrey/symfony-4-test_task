<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\PhoneRepository")
 */
class Phone
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     * @Assert\Length(
     *      min = 6,
     *      max = 13,
     *      charset = "UTF-8",
     *      minMessage = "Your phone number must be at least {{ limit }} characters long.",
     *      maxMessage = "Your phone number cannot be longer than {{ limit }} characters."
     * )
     * @Assert\Regex(
     *     pattern     = "/^[0-9]{6,13}$/",
     *     message="Your phone number entered is not correct."
     * )
     */
    private $phonenumber;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="User", inversedBy="phone")
     */
    private $users;

    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers(User $user)
    {
        $this->users = $user;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(string $phonenumber): self
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }
}
