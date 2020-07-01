<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 30/06/2020
 * Time: 10:52
 */

namespace App\Service\Order\Event\Listener;


use App\Database\OrderStatusLog\OrderStatusLog;
use App\Service\Order\Event\OrderStatusChangedEvent;
use App\Service\Order\Job\ManageOrderStatusChangedJob;
use App\Service\Order\Job\OrderStatusLogJob;
use App\Service\Order\OrderManager;
use Illuminate\Queue\QueueManager;
use Illuminate\Support\Facades\DB;


class OrderStatusChangedListener
{

    /**
     * @param OrderStatusChangedEvent $event
     * @throws \Exception
     */
    public function handle(OrderStatusChangedEvent $event)
    {
        /** @var QueueManager $queue */
        $queue = resolve(QueueManager::class);
        $queue
            ->connection('database')
            ->push(new OrderStatusLogJob($event->getOrder()->getId(), $event->getOrder()->getStatus()));

        $queue
            ->connection('database')
            ->push(new ManageOrderStatusChangedJob($event->getOrder()->getId(), $event->getOrderStatusOld()));

    }
}