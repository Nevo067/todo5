<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
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
    private $login;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pseudo;

    /**
     * @ORM\OneToMany(targetEntity=Todo::class, mappedBy="user", orphanRemoval=true)
     * @MaxDepth(1)
     */
    private $listTodo;

    public function __construct()
    {
        $this->listTodo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection|Todo[]
     */
    public function getListTodo(): Collection
    {
        return $this->listTodo;
    }

    public function addListTodo(Todo $listTodo): self
    {
        if (!$this->listTodo->contains($listTodo)) {
            $this->listTodo[] = $listTodo;
            $listTodo->setUser($this);
        }

        return $this;
    }

    public function removeListTodo(Todo $listTodo): self
    {
        if ($this->listTodo->removeElement($listTodo)) {
            // set the owning side to null (unless already changed)
            if ($listTodo->getUser() === $this) {
                $listTodo->setUser(null);
            }
        }

        return $this;
    }
}
