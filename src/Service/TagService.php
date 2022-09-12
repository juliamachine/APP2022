<?php

/**
 * Tag service.
 */

namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class TagService.
 */
class TagService
{
    /**
     * Tag repository.
     */
    private TagRepository $tagRepository;
    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;
    /**
     * Constructor.
     *
     * @param TagRepository     $tagRepository Tag repository
     * @param PaginatorInterface $paginator      Paginator
     */
    private EntityManagerInterface $entityManager;
    public function __construct(
        TagRepository $tagRepository,
        PaginatorInterface $paginator,
        EntityManagerInterface $entityManager
    ) {
        $this->tagRepository = $tagRepository;
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
            $this->tagRepository->queryAll(),
            $page,
            TagRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Adds new tag.
     *
     * @param Tag $entity
     * @param bool $flush
     * @return void
     */
    public function add(Tag $entity, bool $flush = false): void
    {
        $this->entityManager->persist($entity);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * Removes tag.
     *
     * @param Tag $entity
     * @param bool $flush
     * @return void
     */
    public function remove(Tag $entity, bool $flush = false): void
    {
        $this->entityManager->remove($entity);

        if ($flush) {
            $this->entityManager->flush();
        }
    }
}
