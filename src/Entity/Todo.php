<?php

namespace App\Entity;

use App\Repository\TodoRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=TodoRepository::class)
 */
class Todo
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="listTodo")
     * @ORM\JoinColumn(nullable=false)
     * @MaxDepth(1)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
    public function serialise()
    {
        $FIELD_ID = "id";
        $FIELD_NAME="name";
        $FIELD_DESC ="description";
        $FIELD_USER = "user";
        /*return "{id:".$this->getId().",name:".$this->getName().",description:".$this->getDescription().",user".$this->getUser()->getId()."}";
        */
        $tabJson = array();
        $tabJson[$FIELD_ID] = $this->getId();
        $tabJson[$FIELD_NAME] = $this->getName();
        $tabJson[$FIELD_DESC] = $this->getDescription();
        $tabJson[$FIELD_USER] = $this->getUser()->getId();
        return $tabJson;

    }
    public function deserialise(UserRepository $userRepository,array $json)
    {
        $FIELD_NAME="name";
        $FIELD_DESC ="description";
        $FIELD_USER = "user";

        echo 5;
        echo gettype($json[$FIELD_DESC]);

        $this->setName($json[$FIELD_NAME]);
        $this->setDescription($json[$FIELD_DESC]);
        $this->setUser($userRepository->find($json[$FIELD_USER]));

    }
}
