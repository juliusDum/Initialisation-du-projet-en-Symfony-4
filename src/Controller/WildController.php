<?php

// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Entity\Episode;
use App\Entity\Season;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
     */
    public function index() : Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs){
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }

        return $this->render(
            'wild/index.html.twig',
            ['programs' => $programs]
        );
    }

    /**
     * @param string $slug The slugger
     * @Route("wild/show/{slug<^[a-z0-9-]+$>}", defaults={"slug" = null}, name="show")
     * @return Response
     */
    public function show(?string $slug) :Response
    {
        if (!$slug){
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }

        $slug = preg_replace('/-/', ' ', ucwords(trim(strip_tags($slug)), "-"));

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program){
            throw $this->createNotFoundException('No program with '.$slug.' title, found in program\'s table.');
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug
            ]);
    }

    /**
     * @Route("wild/showByCategory/{categoryName}", name="show_category")
     * @param $categoryName
     * @return Response
     */

    public function showByCategory(string $categoryName) :Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => mb_strtolower($categoryName)]);

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category], ['id' => 'DESC'], 3);

        if (!$category){
            throw $this->createNotFoundException(
                'No program with category '.$categoryName.', found in program\'s table.'
            );
        }

        return $this->render('wild/category.html.twig',[
            'programs' => $programs,
            'categoryName' => $categoryName
        ]);
    }

    /**
     * @param $slug
     * @Route("wild/showProgram/{slug}", defaults={"slug" = null}, name="show_program")
     * @return Response
     */
    public function showByProgram($slug): Response
    {
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        $program = $this->getDoctrine()->getRepository(Program::class)->findOneBy(['title' => mb_strtolower($slug)]);
        $seasons = $program->getSeasons();
        return $this->render('wild/program.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
            'slug'  => $slug,
        ]);
    }

    /**
     * @Route("wild/showBySeason/{id}", name="wild_season")
     * @param $id
     * @return Response
     */
    public function showBySeason(int $id):Response
    {
        $seasons = $this->getDoctrine()->getRepository(Season::class)->find($id);
        $program = $seasons->getProgram();
        $episode = $seasons->getEpisodes();
        return $this->render('wild/season.html.twig', [
            'seasons' => $seasons,
            'program' => $program,
            'episodes' => $episode,
        ]);
    }

    /**
     * @route("wild/showEpisode/{id}", name="show_episode")
     * @param Episode $episode
     * @return Response
     */
    public function showEpisode(Episode $episode) :Response
    {
        $season = $episode->getSeason();
        $program = $season->getProgram();

        return $this->render('wild/episode.html.twig', [
            'episode' => $episode,
            'season' => $season,
            'program' => $program
        ]);
    }
}