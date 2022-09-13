<?php

/**
 * Task service interface.
 */

namespace App\Service;

use App\Entity\Task;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface TaskServiceInterface.
 */
interface TaskServiceInterface
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
     * Removes task.
     */
    public function remove(Task $entity, bool $flush = false): void;
}
