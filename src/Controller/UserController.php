<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user_', requirements: ['id' => '\d+'])]
class UserController extends AbstractController
{
    /**
     * Methode permettant d'afficher la liste de Users
     *
     * @param UserRepository $userRepository
     * @return Response
     */
    #[Route('/list', name: 'list', methods: ['GET'])]
    public function listUser(UserRepository $userRepository): Response
    {
        return $this->render('user/list.html.twig', [
            'listUsers' => $userRepository->findAll(),
        ]);
    }
    /**
     * Méthode permettant de mettre a jour  d'un utilisateur 
     *
     * @param Request $request
     * @param User $user
     * @param EntityManagerInterface $doctrine
     * @param UserPasswordHasherInterface $userPasswordHasherInterface
     * @return Response
     */
    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function newUser(
        Request $request,
        User $user,
        EntityManagerInterface $doctrine,
        UserPasswordHasherInterface $userPasswordHasherInterface
    ): Response {
        // \dd('formulaire d\'ajout');
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Je récuère la mot de passe en clair
            $passowrd = $form->get('password')->getData();
            // Je Hash le mot de passe
            $hashPassword = $userPasswordHasherInterface->hashPassword($user, $passowrd);
            $user->setPassword($hashPassword);
            // sauvegarde en BDD
            $doctrine->persist($user);
            $doctrine->flush();
            $this->addFlash(
                'badge rounded-pill bg-info mt-2 text-white text-center',
                'Les informations de l\'utilitsateur ' . $user->getFullName() . ' a bien été mise à jour'
            );
            return $this->redirectToRoute('user_list', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'formUser' => $form,
        ]);
    }
    /**
     * Méthode permettant de supprimer un ultilisateur 
     *
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $doctrine
     * @return Response
     */
    #[Route('/{id}', name: 'delete',  methods: ['GET', 'POST'])]
    public function deleteUser(
        User $user,
        Request $request,
        EntityManagerInterface $doctrine
    ): Response {

        $doctrine->remove($user);
        $doctrine->flush();
        $this->addFlash(
            'badge rounded-pill bg-info mt-2 text-white text-center',
            'L\'utilitsateur ' . $user->getFullName() . ' a bien été suprimé'
        );

        return $this->redirectToRoute('user_list', [], Response::HTTP_SEE_OTHER);
    }
}
