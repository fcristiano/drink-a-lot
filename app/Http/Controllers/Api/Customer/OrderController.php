<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 29/06/2020
 * Time: 14:20
 */

namespace App\Http\Controllers\Api\Customer;

use App\Database\Order\Order;
use App\Database\Order\OrderRepository;
use App\Service\Order\OrderManager;
use App\ViewModel\ApplicationErrorViewModel;
use App\ViewModel\ExceptionViewModel;
use App\ViewModel\OrderViewModel;
use Fcristiano\LaravelCommon\Services\Repository\Factory\RepositoryFactory;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class OrderController extends BaseController
{
    use ValidatesRequests;

    /** @var OrderManager */
    private $orderManager;

    /** @var OrderRepository */
    private $orderRepository;


    /**
     * OrderController constructor.
     * @param OrderManager $orderManager
     * @param RepositoryFactory $repositoryFactory
     */
    public function __construct(OrderManager $orderManager, RepositoryFactory $repositoryFactory)
    {
        $this->orderManager     = $orderManager;
        $this->orderRepository  = $repositoryFactory->get(Order::class);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validator = $this->getValidationFactory()->make($request->all(), [
           'table_number' => 'bail|required|integer'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status'    => 500,
                'error'     => ApplicationErrorViewModel::default(array_values($validator->errors()->getMessages())[0][0]),
            ], 500);
        }


        $tableNumber = (int) $request->get('table_number');

        try {
            $order = $this->orderManager->create($tableNumber);
        }
        catch(\Exception $e) {
            return response()->json([
                'status'    => 500,
                'error'     => ExceptionViewModel::slim($e),
            ], 500);
        }

        return response()->json([
            'status'    => 200,
            'payload'   => OrderViewModel::default($order),
        ], 200);
    }


    /**
     * @param Request $request
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, $orderId)
    {
        $order = $this->orderRepository->getById($orderId);
        if($order === null) {
            return response()->json([
                'status'    => 500,
                'error'     => ApplicationErrorViewModel::default(sprintf('Order id[%d] not found', $orderId)),
            ], 500);
        }

        return response()->json([
            'status'    => 200,
            'payload'   => OrderViewModel::default($order),
        ], 200);
    }


    /**
     * @param Request $request
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $orderId)
    {
        $validator = $this->getValidationFactory()->make($request->all(), [
            'status' => 'bail|required|string'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status'    => 500,
                'error'     => ApplicationErrorViewModel::default(array_values($validator->errors()->getMessages())[0][0]),
            ], 500);
        }

        $status = $request->get('status');
        if($status !== 'customer_confirmed') {
            return response()->json([
                'status'    => 500,
                'error'     => ApplicationErrorViewModel::default(sprintf('Status "%s" not allowed', $status)),
            ], 500);
        }


        try {
            $order = $this->orderManager->confirmedByCustomer((int) $orderId);
        }
        catch(\Exception $e) {
            return response()->json([
                'status'    => 500,
                'error'     => ExceptionViewModel::slim($e),
            ], 500);
        }


        return response()->json([
            'status'    => 200,
            'payload'   => OrderViewModel::default($order),
        ], 200);
    }


    /**
     * @param Request $request
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $orderId)
    {
        try {
            $order = $this->orderManager->delete((int) $orderId);
        }
        catch(\Exception $e) {
            return response()->json([
                'status'    => 500,
                'error'     => ExceptionViewModel::slim($e),
            ], 500);
        }

        return response()->json([
            'status'    => 200,
            'payload'   => OrderViewModel::default($order),
        ], 200);
    }
}
