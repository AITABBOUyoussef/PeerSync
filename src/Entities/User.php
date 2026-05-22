<?php
namespace App\Entities;
class User
{
    private int $id;
    private string $name;
    private string $email;
    private string $role;
    private int $points;
    private array $tags = [];


public function __construct(int $id, string $name, string $email, string $role, int $points = 0)
{
    $this->id = $id;
    $this->name = $name;
    $this->email = $email;
    $this->role = $role;
    $this->points = $points;
}

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }
    public function getRole(): string { return $this->role; }
    public function getPoints(): int { return $this->points; }
    public function getTags(): array { return $this->tags; }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }
    public function addPoints(int $amount): void
    {
        $this->points += $amount;
    }
    public function addTag(Tag $tag): void
    {
        $this->tags[] = $tag;
    }
}