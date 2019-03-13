<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email", message="A user with this email is already registered")
 * @ORM\EntityListeners({"App\EventListener\UserListener"})
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
     * @Assert\Email(
     *     message= "The address {{ value }} is not a valid email address !"
     * )
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserRoles", inversedBy="users")
     */
    private $roles;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\EqualTo(
     *     propertyPath = "password",
     *     message = "The passwords are not identical"
     * )
     */
    public $confirmPassword;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserProfile", mappedBy="user", cascade={"persist", "remove"})
     */
    private $userProfile;

    public function getId(): ?int
    {
        return $this->id;
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
        return (string) $this->email;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        /*
        return ($this->roles == '1')? ['ROLE_ROOT']:
               ($this->roles == '2')? ['ROLE_SUPER_ADMIN']:
               ($this->roles == '3')? ['ROLE_SONATA_ADMIN']:
               ($this->roles == '4')? ['ROLE_ADMIN']:
               ($this->roles == '5')? ['ROLE_MODERATOR']: ['ROLE_USER']
            ;*/
        if($this->roles == '1') return ['ROLE_ROOT'];
        elseif ($this->roles == '2') return ['ROLE_SUPER_ADMIN'];
        elseif ($this->roles == '3') return ['ROLE_SONATA_ADMIN'];
        elseif ($this->roles == '4') return ['ROLE_ADMIN'];
        elseif ($this->roles == '5') return ['ROLE_MODERATOR'];
        else return ['ROLE_USER'];
    }

    /**
     * @param mixed $roles
     * @return User
     */
    public function setRoles($roles)
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

    public function getUserProfile(): ?UserProfile
    {
        return $this->userProfile;
    }

    public function setUserProfile(UserProfile $userProfile): self
    {
        $this->userProfile = $userProfile;

        // set the owning side of the relation if necessary
        if ($this !== $userProfile->getUser()) {
            $userProfile->setUser($this);
        }

        return $this;
    }
}
