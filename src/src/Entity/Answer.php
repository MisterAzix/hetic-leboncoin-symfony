<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    public const APPROVED = 'approved';
    public const NEEDS_APPROVAL = 'needs_approval';
    public const SPAM = 'spam';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\Column(length: 255)]
    private ?string $answer = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $answered_at = null;

    #[ORM\Column(length: 255)]
    private ?string $status = self::NEEDS_APPROVAL;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getAnsweredAt(): ?\DateTimeInterface
    {
        return $this->answered_at;
    }

    public function setAnsweredAt(\DateTimeInterface $answered_at): self
    {
        $this->answered_at = $answered_at;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

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

    public function isApproved(): bool
    {
        return $this->status === self::APPROVED;
    }
}
