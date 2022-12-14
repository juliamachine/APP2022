<?php

/**
 * Tag entity.
 */

namespace App\Entity;

use App\Repository\TagRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tag Class.
 */
#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 64)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\ManyToMany(targetEntity: Task::class, mappedBy: 'tags')]
    private Collection $tasks;

    #[ORM\ManyToMany(targetEntity: Note::class, mappedBy: 'tags')]
    private Collection $notes;

    /**
     * Constructor.
     */
    #[Pure]
    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->notes = new ArrayCollection();
    }

    /**
     * Getter for ID.
     *
     * @return int|null Returns ID/int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for created at.
     *
     * @return DateTimeImmutable|null Returns date format
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
     * @return $this Returns date format
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Getter for updated at.
     *
     * @return DateTimeImmutable|null Returns date format
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
     * @return $this Returns itself
     */
    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Getter for title.
     *
     * @return string|null String
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
     * @return $this Returns itself
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Getter for task.
     *
     * @return Collection<int, Task> Gets tasks
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    /**
     * Add task function.
     *
     * @param Task $task Task entity
     *
     * @return $this Returns itself
     */
    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->addTag($this);
        }

        return $this;
    }

    /**
     * Remove task.
     *
     * @param Task $task Task entity
     *
     * @return $this Returns itself
     */
    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            $task->removeTag($this);
        }

        return $this;
    }

    /**
     * Getter for notes.
     *
     * @return Collection<int, Note> Gets many notes
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    /**
     * Add note function.
     *
     * @param Note $note Note entity
     *
     * @return $this Returns itself
     */
    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->addTag($this);
        }

        return $this;
    }

    /**
     * Remove note.
     *
     * @param Note $note Note entity
     *
     * @return $this Returns itself
     */
    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            $note->removeTag($this);
        }

        return $this;
    }

    /**
     * ToString function.
     *
     * @return string Returns itself
     */
    public function __toString()
    {
        return (string) $this->title;
    }
}
