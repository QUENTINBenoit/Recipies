<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Affiche les trois premieres recettes publique 
     *
     * @param RecipeRepository $recipeRepository
     * @return Response
     */
    #[Route('/', name: 'home', methods: 'GET')]
    public function home(RecipeRepository $recipeRepository): Response
    {
        return $this->render('home/home.html.twig', [
            'recipiesPublic' => $recipeRepository->findPubicRecipes(3),
        ]);
    }
}
