<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Contract
 *
 * @ORM\Table(name="contract")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity()
 */
class Contract
{

    public function __construct()
    {
        $this->serviceItems = new ArrayCollection();
        $this->shareItems = new ArrayCollection();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="companyName", type="string", length=255)
     */
    private $companyName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="contract_date", type="datetimetz",nullable=true)
     */
    private $contractDate;



    /**
     * @var string
     *
     * @ORM\Column(name="userName", type="string", length=255)
     */
    private $userName;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('recharge','register','phone','telegram','direct')")
     * @Assert\Choice({"recharge","register","phone","telegram","direct"}, message="The contact type should be one of listed values.")
     */
    private $contractType;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('6month','12month','24month')")
     * @Assert\Choice({"6month","12month","24month"}, message="The Time type should be one of listed values.")
     */
    private $contractTime;
    /**
     * @ORM\Column(type="string", columnDefinition="enum('lastMonth','ago')")
     * @Assert\Choice({"lastMonth","ago"}, message="The value2 should be one of listed values.")
     */
     private $recharge;

     /**
     * @ORM\Column(type="string", columnDefinition="enum('lastMonth','ago')")
     * @Assert\Choice({"lastMonth","ago"}, message="The value2 should be one of listed values.")
     */
     private $register;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('lastMonth','ago')")
     * @Assert\Choice({"lastMonth","ago"}, message="The value2 should be one of listed values.")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('lastMonth','ago','advTelegram')")
     * @Assert\Choice({"lastMonth","ago","advTelegram"}, message="The value2 should be one of listed values.")
     */
    private $telegram;


    /**
     * @ORM\Column(type="string", columnDefinition="enum('oil96')")
     * @Assert\Choice({"oil96"}, message="The value2 should be one of listed values.")
     */
    private $direct;


    /**
     * @var ArrayCollection $serviceItems
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\ServiceItems", inversedBy="contracts")
     * @ORM\JoinTable(
     *     name="contract_service",
     *     joinColumns={
     *      @ORM\JoinColumn(name="contract_id", referencedColumnName="id",onDelete="CASCADE")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="service_id", referencedColumnName="id",onDelete="CASCADE")
     *  }
     * )
     */
    private $serviceItems;



    /**
     * @var ArrayCollection $shareItems
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\ShareItems", inversedBy="contracts")
     * @ORM\JoinTable(
     *     name="contract_share",
     *     joinColumns={
     *      @ORM\JoinColumn(name="contract_id", referencedColumnName="id",onDelete="CASCADE")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="share_id", referencedColumnName="id",onDelete="CASCADE")
     *  }
     * )
     */
    private $shareItems;



    /**
     * @ORM\Column(type="string", columnDefinition="enum('global','local','professional','local-professional')")
     * @Assert\Choice({"global","local","professional","local-professional"}, message="The value2 should be one of listed values.")
     */
    private $separate;


    /**
     * @var int
     *
     * @ORM\Column(name="contractPrice", type="string", length=255)
     */
    private $contractPrice;

    /**
     * @var int
     *
     * @ORM\Column(name="discount", type="string", length=255)
     */
    private $discount;

    /**
     * @var string.
     *
     * @ORM\Column(name="basePrice",type="string", length=255)
     */
    private $basePrice;


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
     * Set companyName
     *
     * @param string $companyName
     *
     * @return Contract
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set userName
     *
     * @param string $userName
     *
     * @return Contract
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set contractPrice
     *
     * @param string $contractPrice
     *
     * @return Contract
     */
    public function setContractPrice($contractPrice)
    {
        $this->contractPrice = $contractPrice;

        return $this;
    }

    /**
     * Get contractPrice
     *
     * @return string
     */
    public function getContractPrice()
    {
        return $this->contractPrice;
    }

    /**
     * Set discount
     *
     * @param integer $discount
     *
     * @return Contract
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return int
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set basePrice
     *
     * @param integer $basePrice
     *
     * @return Contract
     */
    public function setBasePrice($basePrice)
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    /**
     * Get basePrice
     *
     * @return int
     */
    public function getBasePrice()
    {
        return $this->basePrice;
    }

    /**
     * @return mixed
     */
    public function getRecharge()
    {
        return $this->recharge;
    }

    /**
     * @param mixed $recharge
     */
    public function setRecharge($recharge)
    {
        $this->recharge = $recharge;
    }

    /**
     * @return mixed
     */
    public function getRegister()
    {
        return $this->register;
    }

    /**
     * @param mixed $register
     */
    public function setRegister($register)
    {
        $this->register = $register;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getTelegram()
    {
        return $this->telegram;
    }

    /**
     * @param mixed $telegram
     */
    public function setTelegram($telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * @return mixed
     */
    public function getDirect()
    {
        return $this->direct;
    }

    /**
     * @param mixed $direct
     */
    public function setDirect($direct)
    {
        $this->direct = $direct;
    }

    /**
     * @return mixed
     */
    public function getSeparate()
    {
        return $this->separate;
    }

    /**
     * @param mixed $separate
     */
    public function setSeparate($separate)
    {
        $this->separate = $separate;
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
     * @return ArrayCollection
     */
    public function getServiceItems()
    {
        return $this->serviceItems;
    }

    /**
     * @param ArrayCollection $serviceItems
     */
    public function setServiceItems($serviceItems)
    {
        $this->serviceItems = $serviceItems;
    }


    /**
     * @param ServiceItems $serviceItem
     */
    public function addServiceItem(ServiceItems $serviceItem)
    {
        if ($this->serviceItems->contains($serviceItem)) {
            return;
        }
        $this->serviceItems->add($serviceItem);
        $serviceItem->addContract($this);
    }
    /**
     * @param ServiceItems $serviceItem
     */
    public function removeServiceItem(ServiceItems $serviceItem)
    {
        if (!$this->serviceItems->contains($serviceItem)) {
            return;
        }
        $this->serviceItems->removeElement($serviceItem);
        $serviceItem->removeContract($this);
    }





    /**
     * @return ArrayCollection
     */
    public function getShareItems()
    {
        return $this->shareItems;
    }

    /**
     * @param ArrayCollection $shareItems
     */
    public function setShareItems($shareItems)
    {
        $this->shareItems = $shareItems;
    }


    /**
     * @param ShareItems $shareItem
     */
    public function addShareItem(ShareItems $shareItem)
    {
        if ($this->shareItems->contains($shareItem)) {
            return;
        }
        $this->shareItems->add($shareItem);
        $shareItem->addContract($this);
    }
    /**
     * @param ShareItems $shareItem
     */
    public function removeShareItem(ShareItems $shareItem)
    {
        if (!$this->shareItems->contains($shareItem)) {
            return;
        }
        $this->shareItems->removeElement($shareItem);
        $shareItem->removeContract($this);
    }

    /**
     * @return mixed
     */
    public function getContractType()
    {
        return $this->contractType;
    }

    /**
     * @param mixed $contractType
     */
    public function setContractType($contractType)
    {
        $this->contractType = $contractType;
    }

    /**
     * @return \DateTime
     */
    public function getContractDate()
    {
        return $this->contractDate;
    }

    /**
     * @param \DateTime $contractDate
     */
    public function setContractDate($contractDate)
    {
        $this->contractDate = $contractDate;
    }

    /**
     * @return mixed
     */
    public function getContractTime()
    {
        return $this->contractTime;
    }

    /**
     * @param mixed $contractTime
     */
    public function setContractTime($contractTime)
    {
        $this->contractTime = $contractTime;
    }



}

