<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Twig\Components;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

/**
 * Live component to display instant search for Posts.
 *
 * See https://symfony.com/bundles/ux-live-component/current/index.html
 *
 * @author Romain Monteil <monteil.romain@gmail.com>
 */
#[AsLiveComponent(name: 'movie_search')]
final class MovieSearchComponent
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $query = '';

    public function __construct(
        private readonly MovieRepository $movieRepository,
    ) {
    }

    public function getPosts(): array
    {
        return $this->movieRepository->findBySearchQuery($this->query);
    }
}
