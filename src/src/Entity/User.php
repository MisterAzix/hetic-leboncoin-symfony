<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
/**
 * @method string getUserIdentifier()
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createDate = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: question::class, orphanRemoval: true)]
    private Collection $question;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: answer::class)]
    private Collection $answer;

    #Attributs pour authentificaton/autorisation
    #[ORM\Column(type: "json")]
    private  $roles = [];

    #[ORM\Column(type: "string")]
    private  $password;

    //Implémenter les méthodes d'authen/autorisation

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        //Être sur que tous les utilisateurs ont au moins un rôle
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
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return  null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        //clean sensitive user data
        //$this->>plainPassword = null
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function __construct()
    {
        $this->question = new ArrayCollection();
        $this->answer = new ArrayCollection();
    }


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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }


    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(?\DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }
    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * @return Collection<int, question>
     */
    public function getQuestion(): Collection
    {
        return $this->question;
    }

    public function addQuestion(question $question): self
    {
        if (!$this->question->contains($question)) {
            $this->question->add($question);
            $question->setUser($this);
        }

        return $this;
    }

    public function removeQuestion(question $question): self
    {
        if ($this->question->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getUser() === $this) {
                $question->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, answer>
     */
    public function getAnswer(): Collection
    {
        return $this->answer;
    }

    public function addAnswer(answer $answer): self
    {
        if (!$this->answer->contains($answer)) {
            $this->answer->add($answer);
            $answer->setUser($this);
        }

        return $this;
    }

    public function removeAnswer(answer $answer): self
    {
        if ($this->answer->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getUser() === $this) {
                $answer->setUser(null);
            }
        }

        return $this;
    }
}
