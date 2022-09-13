<?php

/**
 * Category entity.
 */

namespace App\Entity;

use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Category.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'categories')]
#[ORM\UniqueConstraint(name: 'uq_categories_title', columns: ['title'])]
#[UniqueEntity(fields: ['title'])]
class Category
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;
    /**
     * Created at.
     */
    #[ORM\Column(type: 'datetime_immutable')]
    private ?DateTimeImmutable $createdAt;
    /**
     * Updated at.
     */
    #[ORM\Column(type: 'datetime_immutable')]
    private ?DateTimeImmutable $updatedAt;
    /**
     * Title.
     */
    #[ORM\Column(length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    private ?string $title;

    #[ORM\OneToMany(targetEntity: Note::class, mappedBy: 'category')]
    private Collection $notes;
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'category')]
    private Collection $tasks;

    /**
     * Constructor.
     */
    #[Pure]
    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

       /**
        * Getter for Id.
        *
        * @return int|null Id
        */
    public function getId(): ?int
    {
        return $this->id;
    }

       /**
        * Getter for created at.
        *
        * @return DateTimeImmutable|null Created at
        */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

       /**
        * Setter for created at.
        *
        * @param DateTimeImmutable|null $createdAt Created at
        */
    public function setCreatedAt(?DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

       /**
        * Getter for updated at.
        *
        * @return DateTimeImmutable|null Updated at
        */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

       /**
        * Setter for updated at.
        *
        * @param DateTimeImmutable|null $updatedAt Updated at
        */
    public function setUpdatedAt(?DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

       /**
        * Getter for title.
        *
        * @return string|null Title
        */
    public function getTitle(): ?string
    {
        return $this->title;
    }

       /**
        * Setter for title.
        *
        * @param string|null $title Title
        */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

       /**
        * ToString function.
        *
        * @return string
        */
    public function __toString()
    {
        return (string) $this->title;
    }

       /**
        * @return Collection<int, Note>
        */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

       /**
        * @return Collection<int, Note>
        */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }
}
