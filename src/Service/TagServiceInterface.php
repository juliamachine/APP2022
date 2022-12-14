<?php

/**
 * Tag service interface.
 */

namespace App\Service;

use App\Entity\Tag;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface TagServiceInterface.
 */
interface TagServiceInterface
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
     * Adds new tag.
     *
     * @param Tag  $entity Tag entity
     * @param bool $flush  Flush function
     */
    public function add(Tag $entity, bool $flush = false): void;

    /**
     * Removes tag.
     *
     * @param Tag  $entity Tag entity
     * @param bool $flush  Flush function
     */
    public function remove(Tag $entity, bool $flush = false): void;
}
