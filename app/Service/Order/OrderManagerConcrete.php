<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 29/06/2020
 * Time: 14:42
 */

namespace App\Service\Order;


use App\Database\BarTable\BarTableRepository;
use App\Database\Bartender\Bartender;
use App\Database\Bartender\BartenderRepository;
use App\Database\Order\Order;
use App\Database\Order\OrderRepository;
use App\Database\OrderDetail\OrderDetail;
use App\Database\Waiter\Waiter;
use App\Database\Waiter\WaiterRepository;
use App\Service\Order\Event\OrderStatusChangedEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderManagerConcrete implements OrderManager
{
    /** @var OrderRepository */
    protected $orderRepository;

    /** @var BarTableRepository */
    protected $barTableRepository;

    /** @var BartenderRepository */
    protected $bartenderRepository;

    /** @var WaiterRepository */
    protected $waiterRepository;

    /**
     * OrderManagerConcrete constructor.
     * @param OrderRepository $orderRepository
     * @param BarTableRepository $barTableRepository
     * @param BartenderRepository $bartenderRepository
     * @param WaiterRepository $waiterRepository
     */
    public function __construct(
        OrderRepository $orderRepository,
        BarTableRepository $barTableRepository,
        BartenderRepository $bartenderRepository,
        WaiterRepository $waiterRepository
    )
    {
        $this->orderRepository     = $orderRepository;
        $this->barTableRepository  = $barTableRepository;
        $this->bartenderRepository = $bartenderRepository;
        $this->waiterRepository    = $waiterRepository;
    }

    /**
     * @inheritdoc
     */
    public function create(int $tableNumber): Order
    {
        $barTable = $this->barTableRepository->getByTableNumber($tableNumber);
        if($barTable === null) {
            throw new \Exception(sprintf("BatTable number[%d] not found", $tableNumber));
        }

        $order = $this->orderRepository->getCustomerCompilingByTable($barTable);
        if($order !== null) {
            return $order;
        }

        $order = new Order();
        $order
            ->setBarTableId($barTable->getId())
            ->setStatus(Order::STATUS_CUSTOMER_COMPILING)
            ->save();

        $order->refresh();

        return $order;
    }

    /**
     * @inheritdoc
     */
    public function confirmedByCustomer(int $orderId): Order
    {
        $order = $this->orderRepository->getById($orderId);
        if($order === null) {
            throw new \Exception(sprintf("Order id[%d] not found", $orderId));
        }

        if($order->getStatus() !== Order::STATUS_CUSTOMER_COMPILING) {
            throw new \Exception(sprintf(
                'Cannot change Order id[%d] status to id[%d](%s), this is a closed order for customers',
                $orderId,
                Order::STATUS_READY_TO_BE_MADE,
                Order::getStatusName(Order::STATUS_READY_TO_BE_MADE)
            ));
        }

        $orderDetails = $order->getOrderDetails();
        if(count($orderDetails) === 0) {
            throw new \Exception(sprintf(
                'Cannot change Order id[%d] status to id[%d](%s), this is an empty Order',
                $orderId,
                Order::STATUS_READY_TO_BE_MADE,
                Order::getStatusName(Order::STATUS_READY_TO_BE_MADE)
            ));
        }

        $orderAmount = $this->calcOrderAmount($orderDetails);

        DB::beginTransaction();

        try {
            $order
                ->setAmount($orderAmount)
                ->save();

            $order = $this->changeStatus($orderId, Order::STATUS_READY_TO_BE_MADE);

            DB::commit();
        }
        catch(\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        return $order;
    }

    /**
     * @inheritdoc
     */
    public function takenChargeByBartender(int $orderId, int $bartenderId, $forceAssign = false): Order
    {
        $order = $this->orderRepository->getById($orderId);
        if($order === null) {
            throw new \Exception(sprintf("Order id[%d] not found", $orderId));
        }

        $bartender = $this->bartenderRepository->getById($bartenderId);
        if($bartender === null) {
            throw new \Exception(sprintf("Bartender id[%d] not found", $bartender));
        }

        DB::beginTransaction();

        try {
            if($order->getBartenderId() !== $bartenderId) {
                if($forceAssign === true) {
                    $this->assignBartender($order, $bartender);
                }
                else {
                    throw new \Exception(sprintf(
                        'Order id[%d] is already assigned to Bartender id[%d]',
                        $orderId,
                        $order->getBartenderId()
                    ));
                }
            }

            Log::debug(sprintf('Order id[%d] taken in charge by Bartender id[%d]', $orderId, $bartenderId));

            $order = $this->changeStatus($orderId, Order::STATUS_BARTENDER_MAKING);

            DB::commit();
        }
        catch(\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        return $order;
    }

    /**
     * @inheritdoc
     */
    public function takenChargeByWaiter(int $orderId, int $waiterId, $forceAssign = false): Order
    {
        $order = $this->orderRepository->getById($orderId);
        if($order === null) {
            throw new \Exception(sprintf("Order id[%d] not found", $orderId));
        }

        $waiter = $this->waiterRepository->getById($waiterId);
        if($waiter === null) {
            throw new \Exception(sprintf("Waiter id[%d] not found", $waiter));
        }

        DB::beginTransaction();

        try {
            if($order->getWaiterId() !== $waiterId) {
                if($forceAssign === true) {
                    $this->assignWaiter($order, $waiter);
                }
                else {
                    throw new \Exception(sprintf(
                        'Order id[%d] is already assigned to Waiter id[%d]',
                        $orderId,
                        $order->getWaiterId()
                    ));
                }
            }

            Log::debug(sprintf('Order id[%d] taken in charge by Waiter id[%d]', $orderId, $waiterId));

            $order = $this->changeStatus($orderId, Order::STATUS_WAITER_DELIVERING);

            DB::commit();
        }
        catch(\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        return $order;
    }

    /**
     * @inheritdoc
     */
    public function completedByBartender(int $orderId): Order
    {
        $order = $this->orderRepository->getById($orderId);
        if($order === null) {
            throw new \Exception(sprintf("Order id[%d] not found", $orderId));
        }

        if($order->getStatus() !== Order::STATUS_BARTENDER_MAKING) {
            throw new \Exception(sprintf(
                'Cannot change Order id[%d] status from id[%d](%s) to id[%d](%s)',
                $orderId,
                $order->getStatus(),
                Order::getStatusName($order->getStatus()),
                Order::STATUS_READY_TO_BE_DELIVERED,
                Order::getStatusName(Order::STATUS_READY_TO_BE_DELIVERED)
            ));
        }

        return $this->changeStatus($orderId, Order::STATUS_READY_TO_BE_DELIVERED);
    }

    /**
     * @inheritdoc
     */
    public function completedByWaiter(int $orderId): Order
    {
        $order = $this->orderRepository->getById($orderId);
        if($order === null) {
            throw new \Exception(sprintf("Order id[%d] not found", $orderId));
        }

        if($order->getStatus() !== Order::STATUS_WAITER_DELIVERING) {
            throw new \Exception(sprintf(
                'Cannot change Order id[%d] status from id[%d](%s) to id[%d](%s)',
                $orderId,
                $order->getStatus(),
                Order::getStatusName($order->getStatus()),
                Order::STATUS_DELIVERED,
                Order::getStatusName(Order::STATUS_DELIVERED)
            ));
        }

        return $this->changeStatus($orderId, Order::STATUS_DELIVERED);
    }

    /**
     * @inheritdoc
     */
    public function changeStatus(int $orderId, int $newStatus): Order
    {
        $order = $this->orderRepository->getById($orderId);
        if($order === null) {
            throw new \Exception(sprintf("Order id[%d] not found", $orderId));
        }

        DB::beginTransaction();

        try {
            $oldStatus = $order->getStatus();

            $order
                ->setStatus($newStatus)
                ->save();

            Log::debug(sprintf(
                'Order id[%d] changed status from id[%d](%s) to id[%d](%s)',
                $orderId,
                $oldStatus,
                Order::getStatusName($oldStatus),
                $newStatus,
                Order::getStatusName($newStatus)
            ));

            event(OrderStatusChangedEvent::class, new OrderStatusChangedEvent($order, $oldStatus));

            DB::commit();
        }
        catch(\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        return $order;
    }

    /**
     * @inheritdoc
     */
    public function manageOrderStatusChanged($orderId, $orderStatusOld): Order
    {
        $order = $this->orderRepository->getById($orderId);
        if($order === null) {
            throw new \Exception(sprintf("Order id[%d] not found", $orderId));
        }

        switch(true) {
            case $orderStatusOld === Order::STATUS_CUSTOMER_COMPILING && $order->getStatus() === Order::STATUS_READY_TO_BE_MADE:
                $bartenderAvailable = $this->bartenderRepository->getFirstAvailable();
                if($bartenderAvailable !== null) {
                    $this->assignBartender($order, $bartenderAvailable);
                    Log::debug(sprintf('Order id[%d] assigned to Bartender id[%d]', $orderId, $bartenderAvailable->getId()));
                }
                break;

            case $orderStatusOld === Order::STATUS_BARTENDER_MAKING && $order->getStatus() === Order::STATUS_READY_TO_BE_DELIVERED:
                /** @var Waiter $waiterAvailable */
                $waiterAvailable = $this->waiterRepository->getFirstAvailable();
                if($waiterAvailable !== null) {
                    $this->assignWaiter($order, $waiterAvailable);
                    Log::debug(sprintf('Order id[%d] assigned to Waiter id[%d]', $orderId, $waiterAvailable->getId()));
                }
                break;

            default:
//                throw new \Exception(sprintf(
//                    "Order status change from[%d](%s) to[%d](%s) not manageable",
//                    $orderStatusOld,
//                    Order::getStatusName($orderStatusOld),
//                    $order->getStatus(),
//                    Order::getStatusName($order->getStatus())
//                ));

                break;
        }

        return $order;
    }

    /**
     * @inheritdoc
     */
    public function delete($orderId): Order
    {
        $order = $this->orderRepository->getById($orderId);
        if($order === null) {
            throw new \Exception(sprintf("Order id[%d] not found", $orderId));
        }

        if($order->getStatus() !== Order::STATUS_CUSTOMER_COMPILING) {
            throw new \Exception(sprintf('Order id[%d] cannot be deleted', $orderId));
        }

        $order
            ->setBartenderId(null)
            ->setWaiterId(null)
            ->setDeletedAt(new \DateTime())
            ->setStatus(Order::STATUS_DELETED)
            ->save();

        Log::debug(sprintf('Order id[%d] deleted', $orderId));

        return $order;
    }


    /**
     * @param Order $order
     * @param Bartender $bartenderAvailable
     * @return Order
     */
    protected function assignBartender(Order $order, Bartender $bartenderAvailable): Order
    {
        $order
            ->setBartenderId($bartenderAvailable->getId())
            ->save();

        return $order;
    }

    /**
     * @param Order $order
     * @param Waiter $waiterAvailable
     * @return Order
     */
    protected function assignWaiter(Order $order, Waiter $waiterAvailable): Order
    {
        $order
            ->setWaiterId($waiterAvailable->getId())
            ->save();

        return $order;
    }

    /**
     * @param OrderDetail[] $orderDetails
     * @return float
     */
    protected function calcOrderAmount(array $orderDetails)
    {
        $amount = 0;
        foreach($orderDetails as $detail) {
            $amount += $detail->getPrice();
        }

        return $amount;
    }
}