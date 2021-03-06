<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
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
    public function serialise()
    {
        $FIELD_ID = "id";
        $FIELD_LOGIN="login";
        $FIELD_PASSWORD ="password";
        $FIELD_PSEUDO = "pseudo";
        /*return "{id:".$this->getId().",name:".$this->getName().",description:".$this->getDescription().",user".$this->getUser()->getId()."}";
        */
        $tabJson = array();
        $tabJson[$FIELD_ID] = $this->getId();
        $tabJson[$FIELD_LOGIN] = $this->getLogin();
        $tabJson[$FIELD_PASSWORD] = $this->getPassword();
        $tabJson[$FIELD_PSEUDO] = $this->getPseudo();
        return $tabJson;

    }
    public function deserialise(array $json)
    {
        $FIELD_ID = "id";
        $FIELD_LOGIN="login";
        $FIELD_PASSWORD ="password";
        $FIELD_PSEUDO = "pseudo";



        $this->setLogin($json[$FIELD_LOGIN]);
        $this->setPassword($json[$FIELD_PASSWORD]);
        $this->setPseudo($json[$FIELD_PSEUDO]);



    }

    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
