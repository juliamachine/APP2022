<?php

/**
 * Category service interface.
 */

namespace App\Service;

use App\Entity\Category;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface CategoryServiceInterface.
 */
interface CategoryServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Adds a new category.
     *
     * @param Category $entity Category entity
     * @param bool     $flush  Flush function
     */
    public function add(Category $entity, bool $flush = false): void;

    /**
     * Removes a category.
     *
     * @param Category $entity Category entity
     * @param bool     $flush  Flush function
     */
    public function remove(Category $entity, bool $flush = false): void;
}
