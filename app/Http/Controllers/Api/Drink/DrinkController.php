<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 29/06/2020
 * Time: 14:20
 */

namespace App\Http\Controllers\Api\Drink;


use App\Service\TheCocktailDb\TheCocktailDbClient;
use App\ViewModel\ApplicationErrorViewModel;
use App\ViewModel\ExceptionViewModel;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class DrinkController extends BaseController
{
    use ValidatesRequests;

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
        $validator = $this->getValidationFactory()->make($request->all(), [
            'name_query'        => 'bail|required_without:ingredient_name|string|min:2',
            'ingredient_name'   => 'bail|required_without:name_query|string|min:2'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status'    => 500,
                'error'     => ApplicationErrorViewModel::default(array_values($validator->errors()->getMessages())[0][0]),
            ], 500);
        }


        $nameQuery      = $request->get('name_query', null);
        $ingredientName = $request->get('ingredient_name', null);


        try {
            switch(true) {
                case $nameQuery !== null && $ingredientName === null:
                    $drinks = $this->theCocktailDbClient->searchDrinkByName($nameQuery);
                    break;

                case $nameQuery === null && $ingredientName !== null:
                    $drinks = $this->theCocktailDbClient->searchDrinkByIngredient($ingredientName);
                    break;

                default:
                    throw new \Exception(sprintf('You have to specify one of ingredient_name and name_query'));
                    break;
            }
        }
        catch(\Exception $e) {
            return response()->json([
                'status'    => 500,
                'payload'   => ExceptionViewModel::slim($e),
            ], 500);
        }

        return response()->json([
            'status'    => 200,
            'payload'   => $drinks,
        ], 200);
    }


    public function get(Request $request, $drinkId)
    {
        try {
            $drink = $this->theCocktailDbClient->getDrinkById((int) $drinkId);
        }
        catch(\Exception $e) {
            return response()->json([
                'status'    => 500,
                'payload'   => ExceptionViewModel::slim($e),
            ], 500);
        }

        return response()->json([
            'status'    => 200,
            'payload'   => $drink,
        ], 200);
    }
}
