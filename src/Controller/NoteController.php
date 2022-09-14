<?php

/**
 * Note Controller.
 */

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use App\Service\NoteService;
use App\Service\NoteServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NoteController.
 */
#[Route('/note')]
class NoteController extends AbstractController
{
    /**
     * Note service.
     *
     * @param NoteService $noteService Note service
     */
    private NoteServiceInterface $noteService;

    /**
     * Constructor.
     *
     * @param NoteService $noteService Note service
     */
    public function __construct(NoteServiceInterface $noteService)
    {
        $this->noteService = $noteService;
    }

    /**
     * Index function for note.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/', name: 'app_note_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $pagination = $this->noteService->getPaginatedList(
            $request->query->getInt('page', 1),
        );

        return $this->render('note/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Adding new note.
     *
     * @param Request                $request       HTTP request
     * @param EntityManagerInterface $entityManager Entity manager interface
     *
     * @return Response HTTP response
     */
    #[Route('/new', name: 'app_note_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $note->setCreatedAt(new \DateTimeImmutable());
            $note->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->persist($note);
            $entityManager->flush();

            return $this->redirectToRoute('app_note_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('note/new.html.twig', [
            'note' => $note,
            'form' => $form,
        ]);
    }

    /**
     * Show note action.
     *
     * @param Note $note Note entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}', name: 'app_note_show', methods: ['GET'])]
    public function show(Note $note): Response
    {
        return $this->render('note/show.html.twig', [
            'note' => $note,
        ]);
    }

    /**
     * Editing function.
     *
     * @param Request                $request       HTTP request
     * @param Note                   $note          Note entity
     * @param EntityManagerInterface $entityManager Entity manager interface
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'app_note_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Note $note, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_note_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('note/edit.html.twig', [
            'note' => $note,
            'form' => $form,
        ]);
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Note    $note    Note entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'app_note_delete', requirements: ['id' => '[1-9]\d*'], methods: ['GET', 'POST'])]
    public function delete(Request $request, Note $note): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(FormType::class, $note, [
            'action' => $this->generateUrl('app_note_delete', ['id' => $note->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->isSubmitted() && $form->isValid()) {
                $this->noteService->remove($note, true);

                return $this->redirectToRoute('app_note_index');
            }

            $this->noteService->remove($note, true);

            return $this->redirectToRoute('app_note_index');
        }

        return $this->render(
            'note/delete.html.twig',
            [
                'form' => $form->createView(),
                'note' => $note,
            ]
        );
    }
}
