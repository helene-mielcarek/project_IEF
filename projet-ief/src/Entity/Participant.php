<?php

// namespace App\Entity;

// use App\Repository\ParticipantRepository;
// use Doctrine\Common\Collections\ArrayCollection;
// use Doctrine\Common\Collections\Collection;
// use Doctrine\ORM\Mapping as ORM;

// /**
//  * @ORM\Entity(repositoryClass=ParticipantRepository::class)
//  */
// class Participant
// {
//     /**
//      * @ORM\Id
//      * @ORM\GeneratedValue
//      * @ORM\Column(type="integer")
//      */
//     private $id;

//     /**
//      * @ORM\ManyToMany(targetEntity=User::class, inversedBy="participantIn")
//      */
//     private $user;

//     /**
//      * @ORM\ManyToMany(targetEntity=Event::class, inversedBy="participants")
//      */
//     private $event;

//     /**
//      * @ORM\Column(type="integer")
//      */
//     private $number;

//     public function __construct()
//     {
//         $this->user = new ArrayCollection();
//         $this->event = new ArrayCollection();
//     }

//     // public function __toString()
//     // {
//     //     foreach($this->user as $user){
//     //         // dd($user->getparticipantIn());
//     //         foreach($user->getparticipantIn() as $event){
//     //             $string = $user->getName();
//     //             return $string;
//     //         }
            
//     //     }
//     // }

//     public function getId(): ?int
//     {
//         return $this->id;
//     }

//     /**
//      * @return Collection|User[]
//      */
//     public function getUser(): Collection
//     {
//         return $this->user;
//     }

//     public function addUser(User $user): self
//     {
//         if (!$this->user->contains($user)) {
//             $this->user[] = $user;
//         }

//         return $this;
//     }

//     public function removeUser(User $user): self
//     {
//         $this->user->removeElement($user);

//         return $this;
//     }

//     /**
//      * @return Collection|Event[]
//      */
//     public function getEvent(): Collection
//     {
//         return $this->event;
//     }

//     public function addEvent(Event $event): self
//     {
//         if (!$this->event->contains($event)) {
//             $this->event[] = $event;
//         }

//         return $this;
//     }

//     public function removeEvent(Event $event): self
//     {
//         $this->event->removeElement($event);

//         return $this;
//     }

//     public function getNumber(): ?int
//     {
//         return $this->number;
//     }

//     public function setNumber(int $number): self
//     {
//         $this->number = $number;

//         return $this;
//     }
// }
