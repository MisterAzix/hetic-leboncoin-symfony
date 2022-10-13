<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    //Un Trait rajouter d’un seul coup tous les attributs (createdAt, updatedAt) et leur getters et setters
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @Gedmo\Slug(fields="name")
     */
    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $question = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $askedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $votes = 0;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Answer::class)]
    private Collection $answers;

    #[ORM\ManyToOne(inversedBy: 'question')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAskedAt(): ?\DateTimeInterface
    {
        return $this->askedAt;
    }

    public function setAskedAt(\DateTimeInterface $askedAt): self
    {
        $this->askedAt = $askedAt;

        return $this;
    }
//    Rajouter une méthode à notre entité
    public function getNameToUpperCase(): string {
        return strtoupper($this->name);
    }
//     retourne la valeur du vote sous forme de string avec un
//     « + » ou un « - » devant selon que le vote est positif ou
//    négatif
    public function getVotesString(): string {
        $prefix = $this->getVotes() >= 0 ? "+" : "-";
        return sprintf('%s %d', $prefix, abs($this->getVotes()));

    }

    //Rajoute deux méthodes de votes
    public function upVote(): int {
        return $this->votes++;
    }

    public function upDown(): int {
        return $this->votes--;
    }


    public function getVotes(): ?int
    {
        return $this->votes;
    }

    public function setVotes(?int $votes): self
    {
        $this->votes = $votes;

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

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

}

