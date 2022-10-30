<?php

namespace App\Entity;

use App\Repository\VoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoteRepository::class)]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $from_user_id = null;

    #[ORM\Column(length: 255)]
    private ?string $to_user_id = null;

    #[ORM\Column(length: 255)]
    private ?string $direction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromUserId(): ?string
    {
        return $this->from_user_id;
    }

    public function setFromUserId(string $from_user_id): self
    {
        $this->from_user_id = $from_user_id;

        return $this;
    }

    public function getToUserId(): ?string
    {
        return $this->to_user_id;
    }

    public function setToUserId(string $to_user_id): self
    {
        $this->to_user_id = $to_user_id;

        return $this;
    }

    public function getDirection(): ?string
    {
        return $this->direction;
    }

    public function setDirection(string $direction): self
    {
        $this->direction = $direction;

        return $this;
    }
}
