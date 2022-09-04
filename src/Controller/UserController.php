<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/change_password', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function change_password(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $hasher): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $current_user = $this->getUser();
            $password = $hasher->hashPassword($current_user, $form->get('password')->getData());
            $current_user->setPassword($password);
            $userRepository->add($current_user, true);

            return $this->renderForm('user/change_password.html.twig', [
                'form' => $form,
                'info' => 'Your password has been changed.',
            ]);
        }

        return $this->renderForm('user/change_password.html.twig', [
            'form' => $form,
            'info' => '',
        ]);
    }
}
