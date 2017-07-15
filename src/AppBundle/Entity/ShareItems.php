<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * ShareItems
 *
 * @ORM\Table(name="share_item")
 * @ORM\Entity()
 */
class ShareItems
{
    public function __construct()
    {
        $this->contracts = new ArrayCollection();
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var ArrayCollection $contracts ;
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Contract",mappedBy="shareItems")
     * @Serializer\Exclude()
     * @ORM\JoinTable(
     *     name="contract_share",
     *     joinColumns={
     *      @ORM\JoinColumn(name="share_id", referencedColumnName="id",onDelete="CASCADE")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="contract_id", referencedColumnName="id",onDelete="CASCADE")
     *  }
     * )
     */
    public $contracts;


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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return mixed
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updatedTimestamps() {
        $this->setUpdatedAt(new \DateTime('now'));
        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
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
     * @param Contract $contract
     */
    public function addContract(Contract $contract)
    {
        if ($this->contracts->contains($contract)) {
            return;
        }
        $this->contracts->add($contract);
        $contract->addShareItem($this);
    }
    /**
     * @param Contract $contract
     */
    public function removeContract(Contract $contract)
    {
        if (!$this->contracts->contains($contract)) {
            return;
        }
        $this->contracts->removeElement($contract);
        $contract->removeShareItem($this);
    }
}

