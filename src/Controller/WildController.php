<?php

// src/Controller/WildController.php
namespace App\Controller;

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
        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Séries',
        ]);
    }

    /**
     * @param  string $slug
     * @Route("wild/show/{slug}", requirements={"slug"="[a-z0-9-]+$"}, defaults={"slug" = null}, name="wild_show")
     * @return Response
     */
    public function show($slug) :Response
    {
        $slugDash = str_replace("-", " ", $slug);
        $slugMaj = ucwords($slugDash);
        return $this->render('wild/show.html.twig',
            ['slug' => $slugMaj]
        );
    }
}