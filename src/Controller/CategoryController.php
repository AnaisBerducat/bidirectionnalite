<?php

// src/Controller/CategoryController.php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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