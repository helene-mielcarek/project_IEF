<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 * @Vich\Uploadable
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieu;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $limite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $img;

    /**
     * @Vich\UploadableField(mapping="event_images", fileNameProperty="img")
     * @var File
     */
    private $img_file;

    /**
     * @ORM\OneToMany(targetEntity=LibraryImg::class, mappedBy="relatedEvent")
     */
    private $LibrariesImg;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="events")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $author;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="eventsJoined")
     */
    private $famParticipants;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="events")
     */
    private $category;

    /**
     * @ORM\Column(type="boolean")
     */
    private $complet;

    // /**
    //  * @ORM\ManyToMany(targetEntity=Participant::class, mappedBy="event")
    //  */
    // private $participants;

    //  /**
    //  * @ORM\Column(type="integer")
    //  */
    // private $nbParticipants;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->complet = false;
        $this->LibrariesImg = new ArrayCollection();
        $this->limite = 0;
        $this->participants = new ArrayCollection();
        // $this->participants = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLimite(): ?int
    {
        return $this->limite;
    }

    public function setLimite(int $limite): self
    {
        $this->limite = $limite;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }
    
    /**
     * @return File|null
     */
    public function getImgFile(): ?File
    {
        return $this->img_file;
    }
    
    /**
     * @param File|null $img_file
     */
    public function setImgFile(File $image = null): self
    {
        $this->img_file = $image;

        if($image !== null){
            $this->updatedAt = new \DateTime('now');
        }
        return $this;
    }


    /**
     * @return Collection|LibraryImg[]
     */
    public function getLibrariesImg(): Collection
    {
        return $this->LibrariesImg;
    }

    public function addLibrariesImg(LibraryImg $librariesImg): self
    {
        if (!$this->LibrariesImg->contains($librariesImg)) {
            $this->LibrariesImg[] = $librariesImg;
            $librariesImg->setRelatedEvent($this);
        }

        return $this;
    }

    public function removeLibrariesImg(LibraryImg $librariesImg): self
    {
        if ($this->LibrariesImg->removeElement($librariesImg)) {
            // set the owning side to null (unless already changed)
            if ($librariesImg->getRelatedEvent() === $this) {
                $librariesImg->setRelatedEvent(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getFamParticipants(): Collection
    {
        return $this->famParticipants;
    }

    public function addFamParticipant(User $famParticipant): self
    {
        if (!$this->famParticipants->contains($famParticipant)) {
            $this->famParticipants[] = $famParticipant;
        }

        return $this;
    }

    public function removeFamParticipant(User $famParticipant): self
    {
        $this->famParticipants->removeElement($famParticipant);

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }

    public function getComplet(): ?bool
    {
        return $this->complet;
    }

    public function setComplet(bool $complet): self
    {
        $this->complet = $complet;

        return $this;
    } 

    // public function getNbParticipants(): ?int
    // {
    //     return $this->nbParticipants;
    // }

    // public function setNbParticipants(int $nbParticipants): self
    // {
    //     $this->nbParticipants = $nbParticipants;

    //     return $this;
    // } 

    // /**
    //  * @return Collection|Participant[]
    //  */
    // public function getParticipants(): Collection
    // {
    //     return $this->participants;
    // }

    // public function addParticipant(Participant $participant): self
    // {
    //     if (!$this->participants->contains($participant)) {
    //         $this->participants[] = $participant;
    //         $participant->addEvent($this);
    //     }

    //     return $this;
    // }

    // public function removeParticipant(Participant $participant): self
    // {
    //     if ($this->participants->removeElement($participant)) {
    //         $participant->removeEvent($this);
    //     }

    //     return $this;
    // }
}
