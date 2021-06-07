<?php

// src/Controller/ProgramController.php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\ProgramType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;


class ProgramController extends AbstractController

{

   /**
    * Show all rows from Program's entity

    * @Route("/index", name="program_index")

    * @return Response A response instance

    */

   public function index(): Response

   {

       $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        return $this->render(
            'program/index.html.twig',
            ['programs' => $programs]
        );
   }

   /**
     *
     * @Route("/program/new", name="new_program")
     */
    public function new(Request $request) : Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted()) {
            // Get the Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();
            return $this->redirectToRoute('program_index');
        }
        // Render the form
        return $this->render('program/new.html.twig', ["form" => $form->createView()]);
    }

   /**

 * Getting a program by id

 *

 * @Route("/programs/{id}", name="program_show")

 * @return Response

 */

    public function show(int $id):Response

    {

        $program = $this->getDoctrine()

            ->getRepository(Program::class)

            ->findOneBy(['id' => $id]);


        if (!$program) {

            throw $this->createNotFoundException(

                'No program with id : '.$id.' found in program\'s table.'

            );

        }

        $seasons = $program->getSeasons();
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,

        ]);

    }


    /**
     *
     * @Route("/{program_id}/seasons/{season_id}",requirements={"season_id"="\d+" , "program_id"="\d+"}, name="season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program_id": "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"season_id": "id"}})
     * @return Response
     */

    public function showSeason(Program $program, Season $season): Response
    {

        if (!$program) {
            throw $this->createNotFoundException(
                "No program with id : " . $program . " found in program's table."
            );
        }


        $episodes = $season->getEpisodes();

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes,
        ]);

    }

    /**
     *
     * @Route("/programs/{program_id}/seasons/{season_id}/episodes/{episode_id}", name="program_episode_show")
      * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program_id": "id"}})
      * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"season_id": "id"}})
      * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episode_id": "id"}})
     * @return Response
     */

    public function showEpisode(Program $program, Season $season, Episode $episode)
    {

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }

}