<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Genre;

class MovieController extends AbstractController
{
//    #[Route('/movie', name: 'app_movie')]
//    public function index(): Response
//    {
//        return $this->render('movie/index.html.twig', [
//            'controller_name' => 'MovieController',
//        ]);
//    }
    #[Route('/movie', name: 'app_movie')]
    public function categorie(ManagerRegistry $doctrine):Response
    {
        $genres = $doctrine->getRepository(Genre::class)->findAll();
        return $this->render('movie/index.html.twig', [
          'genres' => $genres
        ]);
    }


}
