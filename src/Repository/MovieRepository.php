<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\String\u;

class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function showAll() : array
    {
        $qb = $this->createQueryBuilder('m')
        ->orderBy('m.id_movie', 'DESC');
        return $qb->getQuery()->getResult();
    }

    public function searchByIdMovie($id_movie): array
    {
        $qb = $this->createQueryBuilder('m')
        ->where('m.id_movie_mdb = '. $id_movie);
        return $qb->getQuery()->getResult();
    }

    public function insertMovie($movie)
    {
        $new_movie = new Movie();
        $new_movie->setid_movie_mdb($movie->id);
        $new_movie->settitle($movie->title);
        $new_movie->setvaloration("5");
        $new_movie->setposter($movie->poster_path);
        $em = $this->getEntityManager();
        $em->persist($new_movie);
        $em->flush();
        return true;
    }

    public function killMovie($id_movie)
    {
        $movie = $this->find($id_movie);
        if ($movie)
        {
            $em = $this->getEntityManager();
            $em->remove($movie);
            $em->flush();
        }
        return true;
    }

    public function updateValoration($valoration, $id_movie)
    {
        $movie = $this->find($id_movie);
        if ($movie)
        {
            $movie->setvaloration($valoration);
            $em = $this->getEntityManager();
            $em->persist($movie);
            $em->flush();
        }
        return true;
    }

    
}
