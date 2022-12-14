<?php

/**
 * Category service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CategoryService.
 */
class CategoryService implements CategoryServiceInterface
{
    /**
     * Category repository.
     */
    private CategoryRepository $categoryRepository;
    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;
    /**
     * Constructor.
     *
     * @param CategoryRepository $categoryRepository Category repository
     * @param PaginatorInterface $paginator          Paginator
     */
    private EntityManagerInterface $entityManager;

    /**
     * Construct function.
     *
     * @param CategoryRepository     $categoryRepository Category repository
     * @param PaginatorInterface     $paginator          Paginator
     * @param EntityManagerInterface $entityManager      Entity manager
     */
    public function __construct(CategoryRepository $categoryRepository, PaginatorInterface $paginator, EntityManagerInterface $entityManager)
    {
        $this->categoryRepository = $categoryRepository;
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
            $this->categoryRepository->queryAll(),
            $page,
            CategoryRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Adds a new category.
     *
     * @param Category $entity Category entity
     * @param bool     $flush  Flush function
     */
    public function add(Category $entity, bool $flush = false): void
    {
        $this->entityManager->persist($entity);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * Removes a category.
     *
     * @param Category $entity Category entity
     * @param bool     $flush  Flush function
     */
    public function remove(Category $entity, bool $flush = false): void
    {
        $this->entityManager->remove($entity);

        if ($flush) {
            $this->entityManager->flush();
        }
    }
}
