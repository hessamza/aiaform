<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * FileManager
 * @ORM\Table(name="file_manager")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity()
 */
class FileManager
{
    public function __construct()
    {
    }


    /**
     * @Serializer\Exclude()
     * @var array
     */
    public static $types = [
        'avatar' => [
            'path' => 'avatar',
            'validation_group' => 'avatar'
        ], 'image' => [
            'path' => 'images',
            'validation_group' => 'image'
        ], 'image-library' => [
            'path' => 'image-madules',
            'validation_group' => 'image'
        ], 'web-shop' => [
            'path' => 'pdf',
            'validation_group' => 'pdf'
        ], 'phase' => [
            'path' => 'phase',
            'validation_group' => 'phase'
        ], 'inspiration' => [
            'path' => 'inspiration',
            'validation_group' => 'inspiration'
        ]
    ];

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Expose()
     */
    private $id;

    /**
     * @var string $name
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter a name")
     * @Assert\File( maxSize = "8000k",mimeTypes={"image/jpeg","image/gif","image/png"},groups={"avatar"},mimeTypesMessage = "Only the file types image are allowed.", maxSizeMessage = "The maximum allowed file size is 5MB.",)
     * @Assert\File(mimeTypes={"image/jpeg","image/gif","image/png","image/jpeg"},groups={"image"})
     * @Assert\Image(minWidth="210",minHeight="210",groups={"avatar"})
     * @Assert\File(mimeTypes={"application/pdf"},groups={"inspiration"})
     */
    private $name;


    /**
     * @var datetime $created_at
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @Assert\NotBlank(message="Please enter a createdAt")
     */
    private $created_at;


    /**
     * @var datetime $updated_at
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     * @Assert\NotBlank(message="Please enter a updatedAt")
     */
    private $updated_at;


    /**
     * @var boolean $status
     * @ORM\Column(type="boolean", name="status", options={"default" = false})
     * @Assert\NotBlank(message="Please enter a status")
     */
    private $status;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @Assert\NotBlank(message="Please enter a user")
     * @ORM\JoinColumn(referencedColumnName="id",onDelete="CASCADE")
     * @Serializer\Exclude()
     */
    private $owner;




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
     * @var string $address
     * @ORM\Column(type="text", length=800)
     * @Serializer\Expose()
     * @Assert\NotBlank(message="Please enter a path")
     */
    private $path;


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
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param DateTime $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param DateTime $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return boolean
     */
    public function isStatus()
    {
        return $this->status;
    }

    /**
     * @param boolean $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }


    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }


    /**
     * Get id
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }




    /**
     * @ORM\PrePersist()
     * @ORM\PreRemove()
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedAt(new \DateTime("now"));
        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime("now"));
        }
    }
}

