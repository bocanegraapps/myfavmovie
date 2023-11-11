<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Movie;
use App\Entity\User;
use App\Event\CommentCreatedEvent;
use App\Form\CommentType;
use App\Repository\MovieRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controller used to manage blog contents in the public part of the site.
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
#[Route('/myMovies')]
final class MovieController extends AbstractController
{
    /**
     * NOTE: For standard formats, Symfony will also automatically choose the best
     * Content-Type header for the response.
     *
     * See https://symfony.com/doc/current/routing.html#special-parameters
     */
    #[Route('/main_app_index', name: 'main_app_index', defaults: ['page' => '1', '_format' => 'html'], methods: ['GET'])]
    #[Cache(smaxage: 10)]
    public function index(Request $request, int $page, string $_format, MovieRepository $movies): Response
    {
        $allMovies = $movies->showAll();

        return $this->render('app/index.'.$_format.'.twig', ['allMovies' => $allMovies]);
    }
   
    #[Route('/movie_search', name: 'movie_search', methods: ['GET'])]
    #[Cache(smaxage: 10)]
    public function movie_search(Request $request): Response
    {
       return $this->render('app/movie_search.html.twig', ['results' => [],'cad_busqueda' => '']);
    }
    
    #[Route('/movie_search_results', name:"movie_search_results", methods: ['POST'])]
    public function movie_search_results(Request $r) : Response
    {
        $cad_busqueda = $r->request->get('buscar');
        $tmbd_apikey = "eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI3YWZhNzc0N2JjYjJmZGVhM2FjODRhZjhlNzk3YTliMCIsInN1YiI6IjY1NGY4ZTM1ZDRmZTA0MDEzOTdmNDc3NiIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.8SFMlFimhoLrjso7gqMFJ0wTH6yfFWh81tPCRfDjlK0";

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'http://api.themoviedb.org/3/search/movie?query='.$cad_busqueda.'&include_adult=false&language=es-ES', [
            'headers' => [
            'Authorization' => 'Bearer '.$tmbd_apikey,
            'accept' => 'application/json',
            ],
        ]);

        $results = json_decode($response->getBody()->getContents());
        
        return $this->render('app/movie_search.html.twig',['results' => $results->results, 'cad_busqueda' => $cad_busqueda]);
        
    }

    #[Route('/add_movie/{movieId}', name:"add_movie", methods:['GET'])]
    public function add_movie(array $_route_params, MovieRepository $movies) : Response
    {
        $movie_id = $_route_params['movieId'];
        //retrieve movie from API pass movie_id
        $tmbd_apikey = "eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI3YWZhNzc0N2JjYjJmZGVhM2FjODRhZjhlNzk3YTliMCIsInN1YiI6IjY1NGY4ZTM1ZDRmZTA0MDEzOTdmNDc3NiIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.8SFMlFimhoLrjso7gqMFJ0wTH6yfFWh81tPCRfDjlK0";
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'http://api.themoviedb.org/3/movie/'.$movie_id.'&include_adult=false&language=es-ES', [
            'headers' => [
            'Authorization' => 'Bearer '.$tmbd_apikey,
            'accept' => 'application/json',
            ],
        ]);

        $results = json_decode($response->getBody()->getContents());

        //persist in database

        $movie = $movies->searchByIdMovie($results->id);

        if (!$movie)
        {
            $movies->insertMovie($results);
            
        }

        $allMovies = $movies->showAll();
        return $this->render('app/index.html.twig', ['allMovies' => $allMovies]);

    }

    
}
