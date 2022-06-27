<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route('/search', name: 'search', methods: 'GET')]
    public function search(RecipeRepository $recipeRepository, Request $request): Response
    {
        $searchValue =  $request->get('q');
        \dd($searchValue);
        $recipeSearch = $recipeRepository->findSearchByTitle($searchValue);
        \dd($recipeSearch);
        return $this->render('home/home.html.twig', [
            'recipiesPublic' => $recipeRepository->findPubicRecipes(3),
        ]);
    }
}
