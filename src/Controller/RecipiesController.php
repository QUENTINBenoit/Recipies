<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/recipies', name: 'recipies_', requirements: ['id' => '\d+'])]
class RecipiesController extends AbstractController
{
    /**
     * This controller displays all recipies 
     *
     * @param RecipeRepository $recipeRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/', name: 'list', methods: ['GET'])]
    public function index(
        RecipeRepository $recipeRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $recipie = $paginator->paginate(
            $recipeRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('recipies/list.html.twig', [
            'recipiesViews' => $recipie,
        ]);
    }

    /**
     * This method displays the form for adding a recipie
     *
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function addRecipie(Request $request, ManagerRegistry $doctrine): Response
    {
        $recipie = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // \dd($form->getData());
            $em = $doctrine->getManager();
            $em->persist($recipie);
            $em->flush();
            // Petit message flash qui va bien 
            $this->addFlash(
                'badge rounded-pill bg-info mt-2 text-white text-center',
                'la recette ' . $recipie->getName() . ' a bien été ajouté.'
            );
            return $this->redirectToRoute('recipies_list');
        }

        return $this->render('recipies/add.html.twig', [
            'formRecipe' => $form->createView()
        ]);
    }

    /**
     * This method displays the form for updating a recipie
     *
     * @param Recipe $recipe
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function editRecipe(
        Recipe $recipe,
        Request $request,
        ManagerRegistry $doctrine
    ): Response {

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();
            $this->addFlash(
                'badge rounded-pill bg-info mt-2 text-white text-center',
                'La recette ' . $recipe->getName() . 'a bien été mise jour.'
            );
            return $this->redirectToRoute('recipies_list');
        }
        return $this->render('recipies/add.html.twig', [
            'formRecipe' => $form->createView()
        ]);
    }
    /**
     * This method delete a Recipe 
     *
     * @param Ingredient $ingredient
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/delete/{id}', name: 'delete', methods: ['GET', 'POST'])]
    public function deleteIngrdient(
        Recipe $recipe,
        ManagerRegistry $doctrine
    ): Response {
        $em = $doctrine->getManager();
        $em->remove($recipe);
        $em->flush();
        $this->addFlash(
            'badge rounded-pill bg-info mt-2 text-white text-center',
            'La recette ' . $recipe->getName() . '  a bien été supprimé.'
        );
        return $this->redirectToRoute('recipies_list');
    }
}
