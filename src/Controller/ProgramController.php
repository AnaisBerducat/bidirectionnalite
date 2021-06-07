<?php

// src/Controller/ProgramController.php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @Route("/{programId}/seasons/{seasonId}",requirements={"seasonId"="\d+" , "programId"="\d+"}, name="season_show")
     * @return Response
     */

    public function showSeason(int $programId, int $seasonId): Response
    {
        $program = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findOneBy(['id' => $programId]);

        if (!$program) {
            throw $this->createNotFoundException(
                "No program with id : " . $programId . " found in program's table."
            );
        }

        $season = $this->getDoctrine()
        ->getRepository(Season::class)
        ->findOneBy(['program' => $program]);

        $episodes = $this->getDoctrine()
        ->getRepository(Episode::class)
        ->findBy(['season' => $season]);

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes,
        ]);

    }

    /**
     *
     * @Route("/programs/{programId}/seasons/{seasonId}/episodes/{episodeId}", name="program_episode_show")
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