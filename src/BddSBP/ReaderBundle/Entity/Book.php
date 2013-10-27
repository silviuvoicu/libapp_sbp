<?php

namespace BddSBP\ReaderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use BddSBP\ReaderBundle\Entity\Reader;
use Symfony\Component\Security\Acl\Model\DomainObjectInterface;
use Iphp\FileStoreBundle\Mapping\Annotation as FileStore;
use Symfony\Component\HttpFoundation\File\File;
/**
 * Book
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="books")
 * @ORM\Entity()
 * @FileStore\Uploadable
 */
class Book implements DomainObjectInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id; 
    /**
     * @var string
     *
     * @ORM\Column()
     * @Assert\NotBlank(message="Book without a title is pretty useless")
     */
    private $title;
    /**
     * @var string
     *
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank(message="Book should have a number of pages")
     */
    private $pages;
    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Book should have a description")
     */
    private $description;
    /**
     * @var string
     *
     * @ORM\Column()
     * @Assert\NotBlank(message="Book should have an author")
     */
    private $author;
    
    /**
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @FileStore\UploadableField(mapping="book")
     * @ORM\Column(name="cover",type="array",nullable=true)
     * 
     */
    protected $image;

   
    
     /**
      * @ORM\Column(type="datetime")
      */
    private $createdAt;
    
    /**
      * @ORM\Column(type="datetime")
      */
    private $updatedAt;
    
    /**
     * @ORM\ManyToOne(targetEntity="Reader", inversedBy="books")
     * @ORM\JoinColumn(name="reader_id", referencedColumnName="id")
     * 
     * @var Reader $reader
     */
    private $reader;
    
    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
       return $this->title;
    }

    public function setPages($pages)
    {
        $this->pages = $pages;
    }

    public function getPages()
    {
        return $this->pages;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getAuthor()
    {
       return $this->author;
    }
    
     public function __toString() {
        return $this->getTitle();
    }
     /**
     * Gets the reader who created the book.
     * 
     * @return Reader A Reader object
     */
    public function getReader()
    {
        return $this->reader;
    }
    
    /**
     * Sets the reader who created the book.
     * 
     * @param Reader $value The reader
     */
    public function setReader( Reader $value )
    {
        $this->reader = $value;
    }
    
    /**
     * @ORM\PrePersist
     */
   public function setCreatedAtValue()
   {
    $this->createdAt = new \DateTime();
   }
   
   /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
   public function setUpdatedAtValue()
   {
    $this->updatedAt = new \DateTime();
   }
   
    public function getObjectIdentifier() {
        return $this->getId();
    }
    

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Book
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Book
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set image
     *
     * @param array $image
     * @return Book
     */
    public function setImage($image)
    {
        $this->image = $image;
    
        return $this;
    }

    /**
     * Get image
     *
     * @return array 
     */
    public function getImage()
    {
        return $this->image;
    }
}