<?php

namespace RtxLabs\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * RtxLabs\UserBundle\Entity\User
 *
 * @ORM\Table(name="rtxlabs_user")
 * @ORM\Entity(repositoryClass="RtxLabs\UserBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $personnelNumber
     *
     * @ORM\Column(name="personnel_number", type="string", length=10, unique=true)
     */
    private $personnelNumber;

    /**
     * @var string $username
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * not persisted, only for validation
     */
    protected $plainPassword;


    /**
     * @var string $firstname
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var string $lastname
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    private $lastname;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(name="roles", type="array")
     * @var ArrayCollection $roles
     */
    protected $roles;

    /**
     * @ORM\Column(type="string", length="5", nullable=true)
     * @var String
     */
    protected $locale;

    /**
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
     * @var \DateTime
     *
     * @ORM\column(name="deleted_at", type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length="255")
     */
    protected $salt;

    /**
     * @var \DateTime
     *
     * @ORM\column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * Random string sent to the user email address in order to verify it
     *
     * @var string
     * @ORM\Column(name="confirmation_token", type="string", length="255", nullable=true)
     */
    protected $confirmationToken;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set personnelNumber
     *
     * @param string $personnelNumber
     */
    public function setPersonnelNumber($personnelNumber)
    {
        $this->personnelNumber = $personnelNumber;
    }

    /**
     * Get personnelNumber
     *
     * @return string 
     */
    public function getPersonnelNumber()
    {
        return $this->personnelNumber;
    }

    /**
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

/**
     * @ORM\PrePersist
     * @return void
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

    /**
     * @ORM\PreUpdate
     * @return void
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime('now');
    }

    /**
     * Constructs a new instance of User
     */
    public function __construct()
    {
        $this->roles = array();
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }

    /**
     * Implementing the UserInterface interface
     */
    public function __toString()
    {
        return $this->getUsername();
    }

    /**
     * equals.
     *
     * @param UserInterface $account
     * @return bool
     */
    public function equals(UserInterface $account)
    {
        if ($account->getUsername() != $this->getUsername()) {
            return false;
        }
        if ($account->getEmail() != $this->getEmail()) {
            return false;
        }
        return true;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * Set roles
     *
     * @param array $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

     /**
     * Gets an array of roles.
     *
     * @return array An array of Role objects
     */
    public function getRoles()
    {
        $roles = $this->roles;

        // make sure to have at least one role
        $roles[] = 'ROLE_DEFAULT';

        return array_unique($roles);
    }

    /**
     * Adds a role to the user.
     *
     * @param string $role
     */
    public function addRole($role)
    {
        $role = strtoupper($role);
        if ($role === 'ROLE_DEFAULT') {
            return;
        }

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
    }

    /**
     * @param string $rolename
     * @return boolean
     */
    public function hasRole($rolename)
    {
        return in_array(strtoupper($rolename), $this->getRoles(), true);
    }

    /**
     * Removes a role to the user.
     *
     * @param string $role
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }
    }

    /**
     * Set locale
     *
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set lastLogin
     *
     * @param datetime $lastLogin
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * Get lastLogin
     *
     * @return datetime 
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set deletedAt
     *
     * @param datetime $deletedAt
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * Get deletedAt
     *
     * @return datetime 
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set confirmationToken
     *
     * @param string $confirmationToken
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
    }

    /**
     * Get confirmationToken
     *
     * @return string 
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->hasRole('ROLE_ADMIN');
    }

    public function setAdmin($value)
    {
        if ($value != $this->isAdmin()) {
            if ($value == true) {
                $this->addRole('ROLE_ADMIN');
            }
            else {
                $this->removeRole('ROLE_ADMIN');
            }

        }
    }
}