<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contract
 * @ORM\Table(name="statistics")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity()
 */
class Statistics
{

    public function __construct()
    {
    }

    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="register", type="string", length=255)
     * @Assert\NotBlank(message="register is required")
     */
    private $register;

    /**
     * @var string
     * @ORM\Column(name="phone", type="string", length=255)
     * @Assert\NotBlank(message="phone is required")
     */
    private $phone;
    /**
     * @var string
     * @ORM\Column(name="telegram", type="string", length=255)
     * @Assert\NotBlank(message="telegram is required")
     */
    private $telegram;
    /**
     * @var string
     * @ORM\Column(name="continueSet", type="string", length=255)
     * @Assert\NotBlank(message="continueSet is required")
     */
    private $continueSet;

    /**
     * @var string
     * @ORM\Column(name="dropContinue", type="string", length=255)
     * @Assert\NotBlank(message="drop is required")
     */
     private $dropContinue;

    /**
     * @var string
     * @ORM\Column(name="pre_factor", type="string", length=255)
     * @Assert\NotBlank(message="preFactor is required")
     */
    private $preFactor;


    /**
     * @var string
     * @ORM\Column(name="file", type="string", length=255)
     * @Assert\NotBlank(message="file is required")
     */
    private $file;

    /**
     * @var string
     * @ORM\Column(name="period_contact", type="string", length=255)
     * @Assert\NotBlank(message="periodContact is required")
     */
    private $periodContact;

    /**
     * @var string
     * @ORM\Column(name="daily_followup", type="string", length=255)
     * @Assert\NotBlank(message="dailyFollowup is required")
     */
    private $dailyFollowup;







    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


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
     * @return string
     */
    public function getRegister()
    {
        return $this->register;
    }

    /**
     * @param string $register
     */
    public function setRegister($register)
    {
        $this->register = $register;
    }

    /**
     * @return string
     */
    public function getDropContinue()
    {
        return $this->dropContinue;
    }

    /**
     * @param string $dropContinue
     */
    public function setDropContinue($dropContinue)
    {
        $this->dropContinue = $dropContinue;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }



    /**
     * @return string
     */
    public function getPreFactor()
    {
        return $this->preFactor;
    }

    /**
     * @param string $preFactor
     */
    public function setPreFactor($preFactor)
    {
        $this->preFactor = $preFactor;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getPeriodContact()
    {
        return $this->periodContact;
    }

    /**
     * @param string $periodContact
     */
    public function setPeriodContact($periodContact)
    {
        $this->periodContact = $periodContact;
    }

    /**
     * @return string
     */
    public function getTelegram()
    {
        return $this->telegram;
    }

    /**
     * @param string $telegram
     */
    public function setTelegram($telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * @return string
     */
    public function getContinueSet()
    {
        return $this->continueSet;
    }

    /**
     * @param string $continueSet
     */
    public function setContinueSet($continueSet)
    {
        $this->continueSet = $continueSet;
    }




    /**
     * @return string
     */
    public function getDailyFollowup()
    {
        return $this->dailyFollowup;
    }

    /**
     * @param string $dailyFollowup
     */
    public function setDailyFollowup($dailyFollowup)
    {
        $this->dailyFollowup = $dailyFollowup;
    }



}

