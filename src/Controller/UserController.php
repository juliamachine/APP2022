<?php

/**
 * User Controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * UserController class.
 */
#[Route('/user')]
class UserController extends AbstractController
{
    /**
     * Change password function.
     */
    #[Route('/change_password', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function changePassword(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $hasher, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentUser = $this->getUser();
            $password = $hasher->hashPassword($currentUser, $form->get('password')->getData());
            $currentUser->setPassword($password);
            $userRepository->add($currentUser, true);

            return $this->renderForm('user/change_password.html.twig', [
                'form' => $form,
                'info' => $translator->trans('password_changed'),
            ]);
        }

        return $this->renderForm('user/change_password.html.twig', [
            'form' => $form,
            'info' => '',
        ]);
    }
}
