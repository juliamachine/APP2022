<?php

/**
 * Note service.
 */

namespace App\Service;

use App\Entity\Note;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class NoteService.
 */
class NoteService
{
    /**
     * Note repository.
     */
    private NoteRepository $noteRepository;
    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param NoteRepository     $noteRepository Note repository
     * @param PaginatorInterface $paginator      Paginator
     */

    /**
     *Constructor.
     *
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    public function __construct(
        NoteRepository $noteRepository,
        PaginatorInterface $paginator,
        EntityManagerInterface $entityManager
    ) {
        $this->noteRepository = $noteRepository;
        $this->paginator = $paginator;
        $this->entityManager = $entityManager;
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->noteRepository->queryAll(),
            $page,
            NoteRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Removes note.
     *
     * @param Note $entity
     * @param bool $flush
     * @return void
     */
    public function remove(Note $entity, bool $flush = false): void
    {
        $this->entityManager->remove($entity);
        if ($flush) {
            $this->entityManager->flush();
        }
    }
}
