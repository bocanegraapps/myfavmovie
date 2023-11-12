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


#[Route('/myMovies')]
final class MovieController extends AbstractController
{
    //ruta principal de la aplicación, index
    #[Route('/main_app_index', name: 'main_app_index', defaults: ['page' => '1', '_format' => 'html'], methods: ['GET'])]
    #[Cache(smaxage: 10)]
    public function index(Request $request, int $page, string $_format, MovieRepository $movies): Response
    {
        $allMovies = $movies->showAll();

        return $this->render('app/index.'.$_format.'.twig', ['allMovies' => $allMovies]);
    }
   
    //ruta para la búsqueda de pelis en TMDB
    #[Route('/movie_search', name: 'movie_search', methods: ['GET'])]
    #[Cache(smaxage: 10)]
    public function movie_search(Request $request): Response
    {
       return $this->render('app/movie_search.html.twig', ['results' => [],'cad_busqueda' => '']);
    }
    
    //ruta para muestra de resultados de la búsqueda
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

    //ruta que persiste la peli elegida en la base de datos local
    #[Route('/add_movie/{movieId}', name:"add_movie", methods:['GET'])]
    public function add_movie(array $_route_params, MovieRepository $movies) : Response
    {
        $movie_id = $_route_params['movieId'];
        //recuperar datos de la película seleccionada
        $tmbd_apikey = "eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI3YWZhNzc0N2JjYjJmZGVhM2FjODRhZjhlNzk3YTliMCIsInN1YiI6IjY1NGY4ZTM1ZDRmZTA0MDEzOTdmNDc3NiIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.8SFMlFimhoLrjso7gqMFJ0wTH6yfFWh81tPCRfDjlK0";
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'http://api.themoviedb.org/3/movie/'.$movie_id.'&include_adult=false&language=es-ES', [
            'headers' => [
            'Authorization' => 'Bearer '.$tmbd_apikey,
            'accept' => 'application/json',
            ],
        ]);

        $results = json_decode($response->getBody()->getContents());

        //verificar si ya existe dicha película, si existe no se añade 2 veces.
        $movie = $movies->searchByIdMovie($results->id);
        if (!$movie)
        {
            //persistir en la base de datos
            $movies->insertMovie($results);
        }

        //volvemos a la vista inicial de lista de películas en BD local.
        $allMovies = $movies->showAll();
        return $this->render('app/index.html.twig', ['allMovies' => $allMovies]);

    }

    #[Route('/kill_movie/{movieId}', name:"kill_movie", methods:['GET'])]
    public function kill_movie(array $_route_params, MovieRepository $movies) : Response
    {
        $movie_id = $_route_params['movieId'];
        //borrar peli de la BD
        $movies->killMovie($movie_id);
        $allMovies = $movies->showAll();
        return $this->render('app/index.html.twig', ['allMovies' => $allMovies]);

    }

    #[Route('/update_valoration/{movieId}', name:"update_valoration", methods:['POST'])]
    public function update_valoration(Request $r, array $_route_params, MovieRepository $movies) : Response
    {
        $movie_id = $_route_params['movieId'];
        //actualziar mi valoracion
        $movies->updateValoration($r->request->get('valoration'), $movie_id);
        $allMovies = $movies->showAll();
        return $this->render('app/index.html.twig', ['allMovies' => $allMovies]);

    }

    
}
