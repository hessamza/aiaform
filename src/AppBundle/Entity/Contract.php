<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Contract
 *
 * @ORM\Table(name="contract")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContractRepository")
 */
class Contract
{

    public function __construct()
    {
        $this->serviceItems = new ArrayCollection();
        $this->shareItems = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"items","Default"})
     */
    private $id;


    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User",inversedBy="contract")
     * @Serializer\Groups({"items"})
     */
    private $owner;

    /**
     * @var string
     *
     * @ORM\Column(name="companyName", type="string", length=255)
     * @Serializer\Groups({"items","Default"})
     */
    private $companyName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="contract_date", type="datetimetz",nullable=true)
     * @Serializer\Groups({"items","Default"})
     */
    private $contractDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="contract_start_date", type="datetimetz",nullable=true)
     * @Serializer\Groups({"items","Default"})
     */
    private $contractStartDate;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="contract_end_date", type="datetimetz",nullable=true)
     * @Serializer\Groups({"items","Default"})
     */
    private $contractEndDate;


    /**
     * @var string
     *
     * @ORM\Column(name="contract_man", type="string", length=255)
     * @Serializer\Groups({"items","Default"})
     */
    private $ContractMan;

    /**
     * @var string
     *
     * @ORM\Column(name="userName", type="string", length=255)
     * @Serializer\Groups({"items","Default"})
     */
    private $userName;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('recharge','register','phone','telegram','direct','exhibition','adv')")
     * @Assert\Choice({"recharge","register","phone","telegram","direct","exhibition","adv"}, message="The contact type should be one of listed values.")
     * @Serializer\Groups({"items","Default"})
     */
    private $contractType;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('6month','12month')")
     * @Assert\Choice({"6month","12month"}, message="The Time type should be one of listed values.")
     * @Serializer\Groups({"items","Default"})
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
    private $exhibition;
    /**
     * @ORM\Column(type="string", columnDefinition="enum('site','email','telegram','sms')")
     * @Assert\Choice({"site","email","telegram","sms"}, message="The value2 should be one of listed values.")
     */
    private $adv;

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
     * @var ArrayCollection $posts
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Posts", inversedBy="contracts")
     * @Serializer\Groups({"items","Default"})
     * @ORM\JoinTable(
     *     name="contract_post",
     *     joinColumns={
     *      @ORM\JoinColumn(name="contract_id", referencedColumnName="id",onDelete="CASCADE")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="post_id", referencedColumnName="id",onDelete="CASCADE")
     *  }
     * )
     */
    private $posts;

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
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="شماره تلفن اجباری است")
     */
    private $customerPhone;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="آدرس اجباری است")
     */
    private $customerAddress;


    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $shareString;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $serviceString;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $servicePrice;




    /**
     * @ORM\Column(type="boolean")
     */
    private $haveExtraContractPrice;

    /**
     * @var string.
     *
     * @ORM\Column(name="extra_contract_price",type="string", length=255,nullable=true)
     */
    private $extraContractPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="extra_description",type="text", nullable=true)
     */
    private $extraDescription;


    /**
     * @ORM\Column(type="boolean")
     */
    private $items;



    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $itemDescription;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $description;


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

    /**
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return string
     */
    public function getContractMan()
    {
        return $this->ContractMan;
    }

    /**
     * @param string $ContractMan
     */
    public function setContractMan($ContractMan)
    {
        $this->ContractMan = $ContractMan;
    }

    /**
     * @return mixed
     */
    public function getExhibition()
    {
        return $this->exhibition;
    }

    /**
     * @param mixed $exhibition
     */
    public function setExhibition($exhibition)
    {
        $this->exhibition = $exhibition;
    }

    /**
     * @return mixed
     */
    public function getAdv()
    {
        return $this->adv;
    }

    /**
     * @param mixed $adv
     */
    public function setAdv($adv)
    {
        $this->adv = $adv;
    }

    /**
     * @return \DateTime
     */
    public function getContractStartDate()
    {
        return $this->contractStartDate;
    }

    /**
     * @param \DateTime $contractStartDate
     */
    public function setContractStartDate($contractStartDate)
    {
        $this->contractStartDate = $contractStartDate;
    }

    /**
     * @return \DateTime
     */
    public function getContractEndDate()
    {
        return $this->contractEndDate;
    }

    /**
     * @param \DateTime $contractEndDate
     */
    public function setContractEndDate($contractEndDate)
    {
        $this->contractEndDate = $contractEndDate;
    }

    /**
     * @return mixed
     */
    public function getCustomerPhone()
    {
        return $this->customerPhone;
    }

    /**
     * @param mixed $customerPhone
     */
    public function setCustomerPhone($customerPhone)
    {
        $this->customerPhone = $customerPhone;
    }


    /**
     * @return mixed
     */
    public function getCustomerAddress()
    {
        return $this->customerAddress;
    }

    /**
     * @param mixed $customerAddress
     */
    public function setCustomerAddress($customerAddress)
    {
        $this->customerAddress = $customerAddress;
    }

    /**
     * @return string
     */
    public function getExtraContractPrice()
    {
        return $this->extraContractPrice;
    }

    /**
     * @param string $extraContractPrice
     */
    public function setExtraContractPrice($extraContractPrice)
    {
        $this->extraContractPrice = $extraContractPrice;
    }

    /**
     * @return string
     */
    public function getExtraDescription()
    {
        return $this->extraDescription;
    }

    /**
     * @param string $extraDescription
     */
    public function setExtraDescription($extraDescription)
    {
        $this->extraDescription = $extraDescription;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }


    /**
     * @return mixed
     */
    public function getItemDescription()
    {
        return $this->itemDescription;
    }

    /**
     * @param mixed $itemDescription
     */
    public function setItemDescription($itemDescription)
    {
        $this->itemDescription = $itemDescription;
    }

    /**
     * @return mixed
     */
    public function getHaveExtraContractPrice()
    {
        return $this->haveExtraContractPrice;
    }

    /**
     * @param mixed $haveExtraContractPrice
     */
    public function setHaveExtraContractPrice($haveExtraContractPrice)
    {
        $this->haveExtraContractPrice = $haveExtraContractPrice;
    }

    /**
     * @return mixed
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param mixed $posts
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
    }


    /**
     * @param Posts $post
     */
    public function addPost(Posts $post)
    {
        if ($this->posts->contains($post)) {
            return;
        }
        $this->posts->add($post);
        $post->addContract($this);
    }
    /**
     * @param Posts $post
     */
    public function removePost(Posts $post)
    {
        if (!$this->posts->contains($post)) {
            return;
        }
        $this->posts->removeElement($post);
        $post->removeContract($this);
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getShareString()
    {
        return $this->shareString;
    }

    /**
     * @param mixed $shareString
     */
    public function setShareString($shareString)
    {
        $this->shareString = $shareString;
    }

    /**
     * @return mixed
     */
    public function getServiceString()
    {
        return $this->serviceString;
    }

    /**
     * @param mixed $serviceString
     */
    public function setServiceString($serviceString)
    {
        $this->serviceString = $serviceString;
    }

    /**
     * @return mixed
     */
    public function getServicePrice()
    {
        return $this->servicePrice;
    }

    /**
     * @param mixed $servicePrice
     */
    public function setServicePrice($servicePrice)
    {
        $this->servicePrice = $servicePrice;
    }


}

