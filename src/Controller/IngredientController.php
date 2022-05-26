<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/ingredient', name: 'ingredient_')]
class IngredientController extends AbstractController
{

    /**
     * This function display all ingredients
     *
     * @param IngredientRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/', name: 'list', methods: ['GET'])]
    public function listIngredient(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {

        $ingredients = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('ingredient/list.html.twig', [
            'ingredients' => $ingredients
        ]);
    }

    /**
     * This function create an ingredient
     *
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function addIngredient(Request $request, ManagerRegistry $doctrine): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //\dd($form->getData()); récupération des infos de mon formulaire
            $em = $doctrine->getManager();
            $em->persist($ingredient);
            $em->flush();
            // petit  message flash pour indiquer la réussite de la création d'un nouvel ingrédient
            $this->addFlash('badge rounded-pill bg-info mt-2 text-white', 'l\'ingrédient ' . $ingredient->getName() . 'a bien été ajouté.');
            return $this->redirectToRoute('ingredient_list');
        }
        // sinon j'affiche un formulaire d'ajout d'un ingredient
        return $this->render('ingredient/add.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * This function update an ingredient
     *
     * @param Ingredient $ingredient
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function editIngredient(Ingredient $ingredient, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $doctrine->getManager();
            $em->flush();
            $this->addFlash('info', 'l\'ingrédient ' . $ingredient->getName() . ' a bien été mis a jour.');
            return $this->redirectToRoute('ingredient_list');
        }
        return $this->render('ingredient/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
