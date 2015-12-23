<?php

namespace JayaTest\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
 
/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer $id
     */
    protected $id;
 
    /**
     * @ORM\Column(type="string", length=255, name="user_name")
     *
     * @var string userName
     */
    protected $userName;

    /**
     * @ORM\Column(type="string", length=255, name="user_email")
     *
     * @var string userEmail
     */
    protected $userEmail;
 
    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string password
     */
    protected $password;
 
    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string salt
     */
    protected $salt;
 
    /**
     * @ORM\ManyToMany(targetEntity="Role")
     * @ORM\JoinTable(name="user_role",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     *
     * @var ArrayCollection $userRoles
     */
    protected $userRoles;
 
    /**
     * @ORM\Column(type="datetime", name="created_at")
     *
     * @var DateTime $createdAt
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer $status
     */
    protected $status;

    /**
     * Геттер для id.
     *
     * @return integer The id.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Геттер для имени пользователя.
     *
     * @return string The userName.
     */
    public function getUserName()
    {
        return $this->userName;
    }
 
    /**
     * Сеттер для имени пользователя.
     *
     * @param string $value The userName.
     */
    public function setUserName($value)
    {
        $this->userName = $value;
    }

    /**
     * Геттер для электронки
     *
     * @return string The userEmail.
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }
 
    /**
     * Сеттер для электронки
     *
     * @param string $value The userEmail.
     */
    public function setUserEmail($value)
    {
        $this->userEmail = $value;
    }

    /**
     * Геттер для даты создания
     *
     * @return string The createdAt.
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
 
    /**
     * Сеттер для даты создания
     *
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();
    }
 
    /**
     * Геттер для пароля.
     *
     * @return string The password.
     */
    public function getPassword()
    {
        return $this->password;
    }
 
    /**
     * Сеттер для пароля.
     *
     * @param string $value The password.
     */
    public function setPassword($value)
    {
        $this->password = $value;
    }
 
    /**
     * Геттер для статуса.
     *
     * @return string The status.
     */
    public function getStatus()
    {
        return $this->status;
    }
 
    /**
     * Сеттер для статуса.
     *
     * @param string $value The status.
     */
    public function setStatus($value)
    {
        $this->status = $value;
    }

    /**
     * Геттер для соли к паролю.
     *
     * @return string The salt.
     */
    public function getSalt()
    {
        return $this->salt;
    }
 
    /**
     * Сеттер для соли к паролю.
     *
     * @param string $value The salt.
     */
    public function setSalt($value)
    {
        $this->salt = $value;
    }
 
    /**
     * Геттер для ролей пользователя.
     *
     * @return ArrayCollection A Doctrine ArrayCollection
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }
 
    /**
     * Конструктор класса User
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }
 
    /**
     * Сброс прав пользователя.
     */
    public function eraseCredentials()
    {
 
    }
 
    /**
     * Геттер для массива ролей.
     * 
     * @return array An array of Role objects
     */
    public function getRoles()
    {
        return $this->getUserRoles()->toArray();
    }
 
    /**
     * Сравнивает пользователя с другим пользователем и определяет
     * один и тот же ли это человек.
     * 
     * @param UserInterface $user The user
     * @return boolean True if equal, false othwerwise.
     */
    public function equals(UserInterface $user)
    {
        return md5($this->getUserName()) == md5($user->getUserName());
    }
}