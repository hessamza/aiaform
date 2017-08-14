<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="users")
 * @UniqueEntity(fields={"email"},message="این ایمیل وجود دارد",groups={"register","web","register-club","register-frontend-user","register-retailer","retailer-profile"})
 * @UniqueEntity(fields={"username"},message="این نام کاربری وجود دارد",groups={"register","web","register-club","register-retailer","retailer-profile"})
 */
class User implements UserInterface
{
    public function __construct()
    {
        $this->contracts=new ArrayCollection();
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"items","Default"})
     * @Serializer\Expose()
     */
    private $id;

    /**
     * @var ArrayCollection $costTravel
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Contract", mappedBy="owner",cascade={"persist", "remove", "merge"})
     * @Assert\Valid()
     */
    private $contracts;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\FileManager")
     * @ORM\JoinColumn(referencedColumnName="id",onDelete="SET NULL",nullable=true)
     * @Serializer\Expose()
     */
    private $report;
    /**
     * @var boolean $rememberMe
     */
    private $rememberMe = false;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Role")
     */
    private $role;


    /**
     * @ORM\Column(type="string", unique=true, length=60)
     * @Assert\NotBlank(message="نام کاربری الزامی است"  )
     * @Serializer\Groups({"items","Default"})
     * @Assert\Length(max=60,min=3,maxMessage="username length must be lesser than 60 characters",minMessage="username length must be more than 3 characters",groups={"register","login","register-frontend-user","register-retailer","retailer-profile"})
     * @Assert\Regex(pattern="/^[a-zA-Z](([\._\-][a-zA-Z0-9])|[a-zA-Z0-9])*[a-z0-9]$/",message="نام کاربری باید شامل حروف و اعداد لاتین باشد",groups={"register","register-retailer","retailer-profile"})
     */
    private $username;



    /**
     * @Assert\Email(message="فرمت ایمیل اشتباه است",groups={"register","updateUser","forgot_password","register-frontend-user","reset-password","register-retailer","retailer-profile"})
     * @Assert\NotBlank(message="ایمیل الزامی است",groups={"resetPassword","register"}  )
     * @ORM\Column(type="string", unique=true, length=255)
     */
    private $email;




    /**
     * @ORM\Column(type="string")
     * @Serializer\Exclude
     * @Assert\NotBlank(message="پسورد الزامی است",groups={"login","resetPassword","register"}  )
     */
    private $password;

    /**
     * @Assert\NotBlank(groups={"resetPassword"},message="تکرار پسورد الزامی است")
     */
    private $plainPassword;


    /**
     * @ORM\Column(type="boolean")
     * @Serializer\Type("boolean")
     */
    private $status = 0;


    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Exclude()
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Exclude()
     */
    private $updatedAt;


    /**
     * @ORM\Column(type="text", nullable=true)
     * @Serializer\Exclude()
     */
    private $forget_password_key;


    /**
     * @return mixed
     */
    public function getForgetPasswordKey()
    {
        return $this->forget_password_key;
    }

    /**
     * @param mixed $forget_password_key
     */
    public function setForgetPasswordKey($forget_password_key)
    {
        $this->forget_password_key = $forget_password_key;
    }


    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }






    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }











    /**
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param Role $role
     */
    public function setRole(Role $role)
    {
        $this->role = $role;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedAt(new \DateTime('now'));
        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    /**
     * @return array (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return [$this->getRole()->getName()];
    }

    /**
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string|null The salt
     */
    public function getSalt()
    {
        return;
    }

    public function eraseCredentials()
    {

    }




    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }


    /**
     * @return mixed
     */
    public function getRememberMe()
    {
        return $this->rememberMe;
    }

    /**
     * @param mixed $rememberMe
     */
    public function setRememberMe($rememberMe)
    {
        $this->rememberMe = $rememberMe;
    }

    /**
     * @return ArrayCollection
     */
    public function getContracts()
    {
        return $this->contracts;
    }

    /**
     * @param ArrayCollection $contracts
     */
    public function setContracts($contracts)
    {
        $this->contracts = $contracts;
    }

    /**
     * @return mixed
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * @param mixed $report
     */
    public function setReport($report)
    {
        $this->report = $report;
    }









}