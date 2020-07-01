<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 29/06/2020
 * Time: 14:20
 */

namespace App\Http\Controllers\Api\Drink;

use App\Service\TheCocktailDb\TheCocktailDbClient;
use App\ViewModel\ExceptionViewModel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class IngredientController extends BaseController
{
    /** @var TheCocktailDbClient */
    private $theCocktailDbClient;


    /**
     * IngredientController constructor.
     * @param TheCocktailDbClient $theCocktailDbClient
     */
    public function __construct(TheCocktailDbClient $theCocktailDbClient)
    {
        $this->theCocktailDbClient = $theCocktailDbClient;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        try {
            $ingredientsData = $this->theCocktailDbClient->getIngredientList();
        }
        catch(\Exception $e) {
            return response()->json([
                'status'    => 500,
                'payload'   => ExceptionViewModel::slim($e),
            ], 500);
        }

        return response()->json([
            'status'    => 200,
            'payload'   => $ingredientsData,
        ], 200);
    }


    public function get(Request $request, $ingredientId)
    {
        try {
            $ingredientData = $this->theCocktailDbClient->getIngredientById((int) $ingredientId);
        }
        catch(\Exception $e) {
            return response()->json([
                'status'    => 500,
                'payload'   => ExceptionViewModel::slim($e),
            ], 500);
        }

        return response()->json([
            'status'    => 200,
            'payload'   => $ingredientData,
        ], 200);
    }
}
