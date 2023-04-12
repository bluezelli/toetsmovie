<?php

namespace App\Controller;

use App\Form\GenreType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Genre;
use App\Repository\MovieRepository;
//use App\Repository\GenreRepository;
//use Doctrine\ORM\EntityManagerInterface;

//onderste is test pas alleen de entityauto en app/form/auto aan en de repo van auto naar genre
//use App\Entity\Auto;
//use App\Form\AutoFormType;
//use App\Repository\AutoRepository;
//use Doctrine\ORM\EntityManagerInterface;
//use Doctrine\Persistence\ManagerRegistry;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Routing\Annotation\Route;


class MovieController extends AbstractController
{
    #[Route('/movie', name: 'app_movie')]
    public function index(): Response
    {
        return $this->render('movie/index.html.twig', [
            'controller_name' => 'MovieController',
        ]);
    }
    #[Route('/genre', name: 'app_genre')]
    public function categorie(ManagerRegistry $doctrine):Response
    {
        $genres = $doctrine->getRepository(Genre::class)->findAll();
        return $this->render('movie/index.html.twig', [
          'genre' => $genres
        ]);
    }

    #[Route('/movie/{id}', name: 'app_movie')]
    public function showMovies(MovieRepository $movieRepository , Genre $genre):Response
    {
        $genreName = $genre->getName();
        $movies = $movieRepository->findBy(['Genre', $genre]);
        return $this->render('movie/film.html.twig', [
            'movie' => $movies,
            'genrenames', $genreName
        ]);




    }

//    #[Route('/delete/{id}', name: 'insert')]
//    public function update(request $request, Genre $genreclass, EntityManagerInterface $entityManager, GenreRepository $genreRepository)
//    {
//        //pakt de id uit Genre entity de class Genre dus, deze id willen meegeven aan Genrerepository
//        $id = $genreclass->getId();
//        $genre = new Genre();
//        $genre = $genreRepository->find($id);
//        $form = $this->renderForm(GenreType::class, $genre);
//        $form->handleRequest($genre);
//
//        if($form->issubmitted() && $form->isvalid()){
//            $genre = $form->getData();
//            $entityManager->persist($genre);
//            $entityManager->flush();
//            return $this->redirectToRoute('home');
//        }
//            return $this->renderForm('update.html.twig',[
//                'form'=> $form
//            ]);
//
//    }


    #[Route('/delete/{id}', name: 'insert')]
    public function delete($id, GenreRepository $genreRepository, EntityManagerInterface $entityManager)
    {
        //de id is afkomstig uit de id dat meegegeven is aan de delete knop hierdoor verwijder je niet alles maar alleen hetgeen wat gekoppeld is aan dat specifieke id

        $id = $genreRepository->find($id);
        $entityManager->remove($id);
        $entityManager->flush();
        return $this->redirectToRoute('home');

    }


    #[Route('/insert', name: 'insert')]
    public function insert( Request $request, GenreRepository $genreRepository)
    {

        $genre = new Genre();
        //$genre is op dit moment een leeg object, deze vul je later in wanneer je de form gaat checken op submit en validation, met behulp van form-data
        $form = $this->createForm(GenreType::class , $genre);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $genre = $form->getData();
            //nadat genre is upgedate met de ingevulde gegevens van form, willen we het sturen naar de genre in db, met behulp van Genrerepository
            $genreRepository->save($genre);
            return $this->redirectToRoute('index'); //verwijzing naar eerte route van controller

        }

        return $this->renderForm('movie/insert.html.twig', [
            'form'=> $form
        ]);




    }

}
