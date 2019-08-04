<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        /** @var User $user */
        $user = new User();

        $form = $this->createForm(LoginType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $doctrine = $this->getDoctrine();

            /** @var UserRepository $userRepository */
            $userRepository = $doctrine->getRepository(User::class);

            /** @var User $dbUser */
            $dbUser = $userRepository->findBy([
                'email' => $user->getEmail(),
            ]);

            if ($dbUser !== null) {

                return $this->render('home/index.html.twig', [
                    'ok' => 'ok',
                ]);
            }

            return $this->render('home/index.html.twig', [
                'ko' => 'ko',
            ]);
        }

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
