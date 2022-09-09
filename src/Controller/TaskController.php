<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;

#[Route('/task')]
class TaskController extends AbstractController
{
    /**
     * Index function of task.
     *
     * @param Request $request
     * @param TaskRepository $taskRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/', name: 'app_task_index', methods: ['GET'])]
    public function index(Request $request, TaskRepository $taskRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $taskRepository->queryAll(),
            $request->query->getInt('page', 1),
            TaskRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render('task/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Creating new task.
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/new', name: 'app_task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setCreatedAt(new \DateTimeImmutable());
            $task->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    /**
     * Function for showing task.
     *
     * @param Task $task
     * @return Response
     */
    #[Route('/{id}', name: 'app_task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    /**
     * Edit task.
     *
     * @param Request $request
     * @param Task $task
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    /**
     * Delete action.
     *
     * @param Request  $request  HTTP request
     * @param Task $task Task entity
     * @param TaskRepository $taskRepository Task Repository
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'app_task_delete', requirements: ['id' => '[1-9]\d*'], methods: ['GET', 'POST'])]
    public function delete(Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(FormType::class, $task, [
            'action' => $this->generateUrl('app_task_delete', ['id' => $task->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->isSubmitted() && $form->isValid()) {

                $taskRepository->remove($task, true);

                return $this->redirectToRoute('app_task_index');
            }

            $taskRepository->remove($task, true);

            return $this->redirectToRoute('app_task_index');
        }

        return $this->render(
            'task/delete.html.twig',
            [
                'form' => $form->createView(),
                'task' => $task,
            ]
        );
    }
}
