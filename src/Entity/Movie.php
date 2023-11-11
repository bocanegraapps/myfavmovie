<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[ORM\Table(name: 'movies')]
#[UniqueEntity(fields: ['id_movie'], errorPath: 'title', message: 'movie.id_unique')]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id_movie = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id_movie_mdb = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $valoration = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $poster = null;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    public function getid_movie(): ?int
    {
        return $this->id_movie;
    }

    public function getid_movie_mdb(): ?int
    {
        return $this->id_movie_mdb;
    }

    public function setid_movie_mdb(?string $id_movie_mdb): void
    {
        $this->id_movie_mdb = $id_movie_mdb;
    }

    public function gettitle(): ?string
    {
        return $this->title;
    }

    public function settitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getvaloration(): ?int
    {
        return $this->valoration;
    }

    public function setvaloration(int $valoration): void
    {
        $this->valoration = $valoration;
    }

    public function getposter(): ?string
    {
        return $this->poster;
    }

    public function setposter(string $poster): void
    {
        $this->poster = $poster;
    }

    public function getcreated_at(): \DateTime
    {
        return $this->created_at;
    }

    public function setcreated_at(\DateTime $createdAt): void
    {
        $this->created_at = $createdAt;
    }

}
