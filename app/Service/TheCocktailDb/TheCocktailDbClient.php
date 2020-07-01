<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 30/06/2020
 * Time: 16:31
 */

namespace App\Service\TheCocktailDb;


interface TheCocktailDbClient
{
    /**
     * @return array
     * @throws \Exception
     */
    public function getIngredientList(): array;

    /**
     * @param int $ingredientId
     * @return array
     * @throws \Exception
     */
    public function getIngredientById(int $ingredientId): array;

    /**
     * @param string $name
     * @return array
     * @throws \Exception
     */
    public function searchDrinkByName(string $name): array;

    /**
     * @param string $ingredientName
     * @return array
     * @throws \Exception
     */
    public function searchDrinkByIngredient(string $ingredientName): array;

    /**
     * @param int $drinkId
     * @return array
     * @throws \Exception
     */
    public function getDrinkById(int $drinkId): array;
}