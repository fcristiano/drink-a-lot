<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 29/06/2020
 * Time: 14:42
 */

namespace App\Service\OrderDetail;


use App\Database\Order\Order;
use App\Database\Order\OrderRepository;
use App\Database\OrderDetail\OrderDetail;
use App\Database\OrderDetail\OrderDetailRepository;
use App\Service\TheCocktailDb\TheCocktailDbClient;

class OrderDetailManagerConcrete implements OrderDetailManager
{
    /** @var TheCocktailDbClient */
    private $theCocktailDbClient;

    /** @var OrderRepository */
    private $orderRepository;

    /** @var OrderDetailRepository */
    private $orderDetailRepository;

    /**
     * OrderManagerConcrete constructor.
     * @param TheCocktailDbClient $theCocktailDbClient
     * @param OrderRepository $orderRepository
     * @param OrderDetailRepository $orderDetailRepository
     */
    public function __construct(
        TheCocktailDbClient $theCocktailDbClient,
        OrderRepository $orderRepository,
        OrderDetailRepository $orderDetailRepository
    )
    {
        $this->theCocktailDbClient   = $theCocktailDbClient;
        $this->orderRepository       = $orderRepository;
        $this->orderDetailRepository = $orderDetailRepository;
    }

    /**
     * @inheritdoc
     */
    public function create(int $orderId, int $drinkId): OrderDetail
    {
        $order = $this->orderRepository->getById($orderId);
        if($order === null) {
            throw new \Exception(sprintf("Order id[%d] not found", $orderId));
        }

        if($order->getStatus() !== Order::STATUS_CUSTOMER_COMPILING) {
            throw new \Exception(sprintf("Order id[%d] is closed for customer modifications", $orderId));
        }

        $drinkData = $this->theCocktailDbClient->getDrinkById($drinkId);
        if($drinkData === null) {
            throw new \Exception(sprintf("Drink id[%d] not found", $drinkId));
        }

        $orderDetail = new OrderDetail();
        $orderDetail
            ->setOrderId($orderId)
            ->setDrinkExtId($drinkId)
            ->setPrice(self::DRINK_DEFAULT_PRICE)
            ->save();

        return $orderDetail;
    }

    /**
     * @inheritdoc
     */
    public function delete($orderDetailId): OrderDetail
    {
        $orderDetail = $this->orderDetailRepository->getById($orderDetailId);
        if($orderDetail === null) {
            throw new \Exception(sprintf("OrderDetail id[%d] not found", $orderDetailId));
        }

        $order = $orderDetail->getOrder();
        if($order->getStatus() !== Order::STATUS_CUSTOMER_COMPILING) {
            throw new \Exception(sprintf("Order id[%d] is closed for customer modifications", $order->getId()));
        }

        $orderDetail->delete();

        return $orderDetail;
    }

}