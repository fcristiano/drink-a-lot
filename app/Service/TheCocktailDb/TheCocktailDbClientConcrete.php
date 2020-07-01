<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 30/06/2020
 * Time: 16:31
 */

namespace App\Service\TheCocktailDb;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class TheCocktailDbClientConcrete implements TheCocktailDbClient
{
    const THE_COCKTAIL_DB_BASE_URI = 'https://www.thecocktaildb.com/api/json/v1/1';

    /** @var Client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritdoc
     */
    public function getIngredientList(): array
    {
        $response = $this
            ->client
            ->get(self::THE_COCKTAIL_DB_BASE_URI.'/list.php', [
                'query' => ['i' => 'list']
            ]);

        $jsonResponse = json_decode($response->getBody(), true);

        if(!isset($jsonResponse['drinks']) || !is_array($jsonResponse['drinks'])) {
            Log::error(sprintf('API Call to[%s] failed', self::THE_COCKTAIL_DB_BASE_URI.'/list.php'));
            Log::error('Params');
            Log::error(json_encode(['i' => 'list']));
            Log::error('Response');
            Log::error($response->getBody());

            throw new \Exception(sprintf('API Call to[%s] failed', self::THE_COCKTAIL_DB_BASE_URI.'/list.php'));
        }

        return $jsonResponse['drinks'];
    }


    /**
     * @inheritdoc
     */
    public function getIngredientById(int $ingredientId): array
    {
        $response = $this
            ->client
            ->get(self::THE_COCKTAIL_DB_BASE_URI.'/lookup.php', [
                'query' => ['iid' => $ingredientId]
            ]);

        $jsonResponse = json_decode($response->getBody(), true);

        if(!isset($jsonResponse['ingredients'])) {
            Log::error(sprintf('API Call to[%s] failed', self::THE_COCKTAIL_DB_BASE_URI.'/lookup.php'));
            Log::error('Params');
            Log::error(json_encode(['iid' => $ingredientId]));
            Log::error('Response');
            Log::error($response->getBody());

            throw new \Exception(sprintf('API Call to[%s] failed', self::THE_COCKTAIL_DB_BASE_URI.'/lookup.php'));
        }

        return $jsonResponse['ingredients'];
    }


    /**
     * @inheritdoc
     */
    public function searchDrinkByName(string $name): array
    {
        $response = $this
            ->client
            ->get(self::THE_COCKTAIL_DB_BASE_URI.'/search.php', [
                'query' => ['s' => $name]
            ]);

        $jsonResponse = json_decode($response->getBody(), true);

        if(!isset($jsonResponse['drinks']) || !is_array($jsonResponse['drinks'])) {
            Log::error(sprintf('API Call to[%s] failed', self::THE_COCKTAIL_DB_BASE_URI.'/search.php'));
            Log::error('Params');
            Log::error(json_encode(['i' => $name]));
            Log::error('Response');
            Log::error($response->getBody());

            throw new \Exception(sprintf('API Call to[%s] failed', self::THE_COCKTAIL_DB_BASE_URI.'/search.php'));
        }

        return $jsonResponse['drinks'];
    }


    /**
     * @inheritdoc
     */
    public function searchDrinkByIngredient(string $ingredientName): array
    {
        $response = $this
            ->client
            ->get(self::THE_COCKTAIL_DB_BASE_URI.'/filter.php', [
                'query' => ['i' => $ingredientName]
            ]);

        $jsonResponse = json_decode($response->getBody(), true);

        if(!isset($jsonResponse['drinks']) || !is_array($jsonResponse['drinks'])) {
            Log::error(sprintf('API Call to[%s] failed', self::THE_COCKTAIL_DB_BASE_URI.'/filter.php'));
            Log::error('Params');
            Log::error(json_encode(['i' => $ingredientName]));
            Log::error('Response');
            Log::error($response->getBody());

            throw new \Exception(sprintf('API Call to[%s] failed', self::THE_COCKTAIL_DB_BASE_URI.'/filter.php'));
        }

        return $jsonResponse['drinks'];
    }


    /**
     * @inheritdoc
     */
    public function getDrinkById(int $drinkId): array
    {
        $response = $this
            ->client
            ->get(self::THE_COCKTAIL_DB_BASE_URI.'/lookup.php', [
                'query' => ['i' => $drinkId]
            ]);

        $jsonResponse = json_decode($response->getBody(), true);

        if(!isset($jsonResponse['drinks']) && !is_array($jsonResponse['drinks'])) {
            Log::error(sprintf('API Call to[%s] failed', self::THE_COCKTAIL_DB_BASE_URI.'/lookup.php'));
            Log::error('Params');
            Log::error(json_encode(['i' => $drinkId]));
            Log::error('Response');
            Log::error($response->getBody());

            throw new \Exception(sprintf('API Call to[%s] failed', self::THE_COCKTAIL_DB_BASE_URI.'/lookup.php'));
        }

        return $jsonResponse['drinks'][0];
    }


}