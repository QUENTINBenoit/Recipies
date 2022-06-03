<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    /**
     * Methode permattant d'afficher la liste de Users
     *
     * @param UserRepository $userRepository
     * @return Response
     */
    #[Route('/list', name: 'list')]
    public function listUser(UserRepository $userRepository): Response
    {
        dump($userRepository->findAll());
        return $this->render('user/list.html.twig', [
            'listUsers' => $userRepository->findAll(),
        ]);
    }
}
