<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use InvalidArgumentException;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    use TimestampableEntity;

    //Utiliser des constantes à la place des enum
    public const APPROVED = 'approved';
    public const NEEDS_APPROVAL = 'approved';
    public const SPAM = 'spam';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(nullable: true)]
    private ?int $votes = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?question $question = null;

    #[ORM\ManyToOne(inversedBy: 'answer')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $status = self::NEEDS_APPROVAL;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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

    public function getQuestion(): ?question
    {
        return $this->question;
    }

    public function setQuestion(?question $question): self
    {
        $this->question = $question;

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

    public function upVote(): int
    {
        return $this->votes++;
    }

    public function upDown(): int
    {
        return $this->votes--;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    //S’assurer que notre entité ne puisse pas avoir une valeur différente que celles définies dans les constantes
    public function setStatus(string $status): self
    {
        $vailable_status = [
            self::NEEDS_APPROVAL,
            self::APPROVED,
            self::SPAM
        ];

        if (!in_array($status, $vailable_status)) {
            throw new InvalidArgumentException('Le status demanddé est invalide');
        }
        $this->status = $status;

        return $this;
    }

    //La  méthod va renvoyer un booléen si la réponse est approuvée
    public function isApproved(): bool
    {
        return $this->status === self::APPROVED;
    }
}
