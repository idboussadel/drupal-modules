<?php
namespace Drupal\movies\Controller;

use Drupal\Core\Controller\ControllerBase;

class MoviesListings extends ControllerBase{
    public function view(){
        $hello_name = \Drupal::config('movies.settings')->get('hello.name');
        dump($hello_name);

        return [
            '#theme' => 'movies-listings',
            '#content' => [
                'movies' => $this->createMoviesCards(),
            ],
            '#attached' => [
                'library' => [
                    'movies/movies-styling',
                ],
            ],
        ];
    }

    public function listMovies(){
        /** @var \Drupal\movies\MovieAPIConnector $movie_api_connector_service */
        $movie_api_connector_service = \Drupal::service('movies.api_connector');

        $movies = $movie_api_connector_service->getMovies();
        dump($movies);

        return $movies;
    }

    public function createMoviesCards(){
        /** @var \Drupal\movies\MovieAPIConnector $movie_api_connector_service */
        $movie_api_connector_service = \Drupal::service('movies.api_connector');

        $movies = $this->listMovies();
        $movies_cards = [];

        if(!$movies){
            return [];
        }

        foreach($movies->results as $movie){
            $movies_cards[] = [
                    '#theme' => 'movies-card',
                    '#content' => [
                        'title' => $movie->title,
                        'description' => $movie->overview,
                        'release_date' => $movie->release_date,
                        'vote' => $movie->vote_average,
                        'image' => $movie_api_connector_service->getImageUrl($movie->poster_path),
                    ],
            ];
        }
        return $movies_cards;
    }
}