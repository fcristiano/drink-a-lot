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
use App\Database\OrderDetail\OrderDetail;
use App\Database\OrderDetail\OrderDetailRepository;
use App\Service\OrderDetail\OrderDetailManager;
use App\ViewModel\ApplicationErrorViewModel;
use App\ViewModel\ExceptionViewModel;
use App\ViewModel\OrderDetailViewModel;
use Fcristiano\LaravelCommon\Services\Repository\Factory\RepositoryFactory;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class OrderDetailController extends BaseController
{
    use ValidatesRequests;

    /** @var OrderDetailManager */
    private $orderDetailManager;

    /** @var OrderRepository */
    private $orderRepository;

    /** @var OrderDetailRepository */
    private $orderDetailRepository;


    /**
     * OrderController constructor.
     * @param OrderDetailManager $orderDetailManager
     * @param RepositoryFactory $repositoryFactory
     */
    public function __construct(OrderDetailManager $orderDetailManager, RepositoryFactory $repositoryFactory)
    {
        $this->orderDetailManager    = $orderDetailManager;
        $this->orderRepository       = $repositoryFactory->get(Order::class);
        $this->orderDetailRepository = $repositoryFactory->get(OrderDetail::class);
    }


    /**
     * @param Request $request
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request, $orderId)
    {
        $validator = $this->getValidationFactory()->make($request->all(), [
            'drink_id' => 'bail|required|integer'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status'    => 500,
                'error'     => ApplicationErrorViewModel::default(array_values($validator->errors()->getMessages())[0][0]),
            ], 500);
        }


        $drinkId = (int) $request->get('drink_id');

        try {
            $orderDetail = $this->orderDetailManager->create((int) $orderId, $drinkId);
        }
        catch(\Exception $e) {
            return response()->json([
                'status'    => 500,
                'error'     => ExceptionViewModel::slim($e),
            ], 500);
        }

        return response()->json([
            'status'    => 200,
            'payload'   => OrderDetailViewModel::default($orderDetail),
        ], 200);
    }


    /**
     * @param Request $request
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request, $orderId)
    {
        $order = $this->orderRepository->getById($orderId);
        if($order === null) {
            return response()->json([
                'status'    => 500,
                'error'     => ApplicationErrorViewModel::default(sprintf('Order id[%d] not found', $orderId)),
            ], 500);
        }

        $orderDetails = $order->getOrderDetails();

        return response()->json([
            'status'    => 200,
            'payload'   => OrderDetailViewModel::defaultList($orderDetails),
        ], 200);
    }


    /**
     * @param Request $request
     * @param $orderId
     * @param $detailId
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $orderId, $detailId)
    {
        try {
            $orderDetail = $this->orderDetailManager->delete((int) $detailId);
        }
        catch(\Exception $e) {
            return response()->json([
                'status'    => 500,
                'error'     => ExceptionViewModel::slim($e),
            ], 500);
        }

        return response()->json([
            'status'    => 200,
            'payload'   => OrderDetailViewModel::default($orderDetail),
        ], 200);
    }
}
