<?php

namespace Drupal\movies;

use Drupal\Core\Http\ClientFactory;
use Drupal\movies\Form\MoviesApiConfigForm;
use GuzzleHttp\Exception\RequestException;


class MovieAPIConnector{
    private $client;

    public function __construct(ClientFactory $client){
        $movie_api_config = \Drupal::state()->get(MoviesApiConfigForm::MOVIES_API_CONFIG);
        $api_url = $movie_api_config['api_base_url'] ?: '';
        $api_key = $movie_api_config['api_key'] ?: '';
        $query = [
            'api_key' => $api_key,
        ];

        $this->client = $client->fromOptions([
            'base_uri' => $api_url,
            'query' => $query,
        ]);
    }

    public function getMovies(){
        $endpoint = '/3/discover/movie';
        $data = [];
        try{
            $request = $this->client->get($endpoint);
            $res = $request->getBody()->getContents();
            $data = json_decode($res);
        }catch(RequestException $e){
            \Drupal::logger('movies')->error($e->getMessage());
        }

        return $data;
    }

    public function getImageUrl($image_path){
        $image_base_url = 'https://image.tmdb.org/t/p/w500';
        return $image_base_url . $image_path;
    }
}