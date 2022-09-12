<?php

/**
 * Task service.
 */

namespace App\Service;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class TaskService.
 *
 * @extends ServiceEntityRepository<Task>
 *
 * @psalm-suppress LessSpecificImplementedReturnType
 */
class TaskService
{
    /**
     * Task repository.
     */
    private TaskRepository $taskRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param TaskRepository     $taskRepository Task repository
     * @param PaginatorInterface $paginator      Paginator
     */
    private EntityManagerInterface $entityManager;
    public function __construct(TaskRepository $taskRepository, PaginatorInterface $paginator, EntityManagerInterface $entityManager)
    {
        $this->taskRepository = $taskRepository;
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
            $this->taskRepository->queryAll(),
            $page,
            TaskRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Removes task.
     *
     * @param Task $entity
     * @param bool $flush
     * @return void
     */
    public function remove(Task $entity, bool $flush = false): void
    {
        $this->entityManager->remove($entity);
        if ($flush) {
            $this->entityManager->flush();
        }
    }
}
