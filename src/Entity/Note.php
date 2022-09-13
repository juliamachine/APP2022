<?php

/**
 * Note entity.
 */

namespace App\Entity;

use App\Repository\NoteRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Note.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: NoteRepository::class)]
#[ORM\Table(name: 'notes')]
class Note
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Title.
     */
    #[ORM\Column(length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    private ?string $content = null;

    /**
     * Created at.
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[ORM\Column(type: 'datetime_immutable')]
    private ?DateTimeImmutable $createdAt = null;

    /**
     * Updated at.
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[ORM\Column(type: 'datetime_immutable')]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'notes')]
    private Collection $tags;

    /**
     * Constructor.
     */
    #[Pure]
    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
     * Getter for title.
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for title.
     *
     * @param string $title Title
     *
     * @return Note
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get content function.
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

       /**
        * Setter for content.
        *
        * @param string $content
        *
        * @return $this
        */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
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
     * @param DateTimeImmutable $createdAt Created at
     *
     * @return Note
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
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
     * @param DateTimeImmutable $updatedAt Updated at
     *
     * @return Note
     */
    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Getter for category.
     *
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

       /**
        * Setter for category.
        *
        * @param Category|null $category
        *
        * @return $this
        */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

       /**
        * Getter for tags.
        *
        * @return Collection<int, Tag>
        */
    public function getTags(): Collection
    {
        return $this->tags;
    }

       /**
        * Adding tag.
        *
        * @param Tag $tag
        *
        * @return $this
        */
    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

       /**
        * Removing tag.
        *
        * @param Tag $tag
        *
        * @return $this
        */
    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
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
}
