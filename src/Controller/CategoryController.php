<?php

// src/Controller/CategoryController.php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CategoryType;

class CategoryController extends AbstractController

{

   /**
    * Show all rows from Category's entity

    * @Route("/categories/", name="category_index")

    * @return Response A response instance

    */

   public function index(): Response

   {

       $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render(
            'category/index.html.twig',
            ['categories' => $categories]
        );
   }

    /**
     *
     * @Route("/category/new", name="category_new")
     */
    public function new(Request $request) : Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted()) {
            // Get the Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('category_index');
        }
        // Render the form
        return $this->render('category/new.html.twig', ["form" => $form->createView()]);
    }

   /**

 * Getting a category by Name

 *

 * @Route("/categories/{categoryName}", name="category_show")

 * @return Response

 */

    public function show(string $categoryName):Response

    {

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);

            $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category], ['id' => 'DESC'], 3);
        if (!$category) {
            throw $this->createNotFoundException(
                'No program with id : '.$categoryName.' found in category\'s table.'
            );
        }
        return $this->render('category/show.html.twig', [

            'category' => $category,
            'programs' => $programs

        ]);

    }


}