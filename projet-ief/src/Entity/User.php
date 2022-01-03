<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Email()
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @Assert\NotBlank()
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    
    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=LibraryImg::class, mappedBy="author")
     */
    private $LibrariesImg;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="author", orphanRemoval=true)
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="author", orphanRemoval=false)
     */
    private $events;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="famParticipants")
     */
    private $eventsJoined;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbChildren;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    // /**
    //  * @ORM\ManyToMany(targetEntity=Participant::class, mappedBy="user")
    //  */
    // private $participantIn;


    public function __construct()
    {
        $this->LibrariesImg = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->eventsJoined = new ArrayCollection();
        // $this->participantIn = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $librariesImg->setAuthor($this);
        }

        return $this;
    }

    public function removeLibrariesImg(LibraryImg $librariesImg): self
    {
        if ($this->LibrariesImg->removeElement($librariesImg)) {
            // set the owning side to null (unless already changed)
            if ($librariesImg->getAuthor() === $this) {
                $librariesImg->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAuthor($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getAuthor() === $this) {
                $image->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setAuthor($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getAuthor() === $this) {
                $event->setAuthor(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection|Event[]
     */
    public function getEventsJoined(): Collection
    {
        return $this->eventsJoined;
    }

    public function addEventsJoined(Event $eventsJoined): self
    {
        if (!$this->eventsJoined->contains($eventsJoined)) {
            $this->eventsJoined[] = $eventsJoined;
            $eventsJoined->addFamParticipant($this);
        }

        return $this;
    }

    public function removeEventsJoined(Event $eventsJoined): self
    {
        if ($this->eventsJoined->removeElement($eventsJoined)) {
            $eventsJoined->removeFamParticipant($this);
        }

        return $this;
    }

    public function getNbChildren(): ?int
    {
        return $this->nbChildren;
    }

    public function setNbChildren(int $nbChildren): self
    {
        $this->nbChildren = $nbChildren;

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

    // /**
    //  * @return Collection|Participant[]
    //  */
    // public function getParticipantIn(): Collection
    // {
    //     return $this->participantIn;
    // }

    // public function addParticipantIn(Participant $participantIn): self
    // {
    //     if (!$this->participantIn->contains($participantIn)) {
    //         $this->participantIn[] = $participantIn;
    //         $participantIn->addUser($this);
    //     }

    //     return $this;
    // }

    // public function removeParticipantIn(Participant $participantIn): self
    // {
    //     if ($this->participantIn->removeElement($participantIn)) {
    //         $participantIn->removeUser($this);
    //     }

    //     return $this;
    // }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }
}
