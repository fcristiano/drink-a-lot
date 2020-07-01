<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 30/06/2020
 * Time: 22:33
 */

namespace App\Console\Commands;


use App\Database\Order\Order;
use App\Database\Order\OrderRepository;
use App\Database\OrderStatusLog\OrderStatusLog;
use App\Service\Order\OrderManager;
use Fcristiano\LaravelCommon\Services\Repository\Factory\RepositoryFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SuperEfficientStaffSimulationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulation:super-efficient-staff
                            {--speed= : Choose Staff speed from really-fast, fast, not-so-fast. Default is fast}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts a simulation where the staff processes orders super efficiently. You can change the speed if you want.';



    /** @var OrderRepository */
    private $orderRepository;

    /** @var OrderManager */
    private $orderManager;

    /** @var float */
    private $timeMultiplier;

    /**
     * Create a new command instance.
     * @param RepositoryFactory $repositoryFactory
     * @param OrderManager $orderManager
     */
    public function __construct(RepositoryFactory $repositoryFactory, OrderManager $orderManager)
    {
        $this->orderRepository  = $repositoryFactory->get(Order::class);
        $this->orderManager     = $orderManager;

        parent::__construct();
    }

    /**
     * Execute the console command.
     * @throws \Exception
     */
    public function handle()
    {
        $this->alert('If you want to stop this simulation, simply use the powerful Ctrl^C');

        $speed = $this->option('speed');
        $speed = $speed !== null ? $speed : 'fast';

        switch($speed) {
            case "really-fast": $this->timeMultiplier = 0.5; break;
            case "fast":        $this->timeMultiplier = 1;   break;
            case "not-so-fast": $this->timeMultiplier = 3;   break;
            default:
                Log::debug(sprintf('Speed option "%s" not supported, used default', $speed));
                break;
        }


        while(true) {
            $orderProcessableList = $this->orderRepository->getProcessableList();

            foreach($orderProcessableList as $order) {
                $this->staffDoThings($order);
            }

            usleep(1000000 * $this->timeMultiplier);
        }
    }

    /**
     * @param Order $order
     * @return Order
     * @throws \Exception
     */
    private function staffDoThings(Order $order)
    {
        $lastOrderStatus = $order->getLastOrderStatus();
        if($lastOrderStatus === null) {
            return $order;
        }

        $isStatusCompleted = $this->decideIfStatusIsCompleted($lastOrderStatus);
        if(!$isStatusCompleted) {
            return $order;
        }

        Log::debug(sprintf(
            'Order id[%d] ready to leave from status id[%d](%s)',
            $order->getId(),
            $order->getStatus(),
            Order::getStatusName($order->getStatus())
        ));

        try {
            switch($order->getStatus()) {
                case Order::STATUS_CUSTOMER_COMPILING: // to Order::STATUS_DELETED (in case of an abandoned order)
                    $this->line('STATUS_CUSTOMER_COMPILING -> STATUS_DELETED');
                    $this->orderManager->delete($order->getId());
                    break;

                case Order::STATUS_READY_TO_BE_MADE: // to Order::STATUS_BARTENDER_MAKING
                    if($order->getBartenderId() === null) {
                        return $order;
                    }
                    $this->line('STATUS_READY_TO_BE_MADE -> STATUS_BARTENDER_MAKING');
                    $this->orderManager->takenChargeByBartender($order->getId(), $order->getBartenderId());
                    break;

                case Order::STATUS_BARTENDER_MAKING: // to Order::STATUS_READY_TO_BE_DELIVERED
                    $this->line('STATUS_BARTENDER_MAKING -> STATUS_READY_TO_BE_DELIVERED');
                    $this->orderManager->completedByBartender($order->getId());
                    break;

                case Order::STATUS_READY_TO_BE_DELIVERED: // to Order::STATUS_DELIVERING
                    if($order->getWaiterId() === null) {
                        return $order;
                    }
                    $this->line('STATUS_READY_TO_BE_DELIVERED -> STATUS_DELIVERING');
                    $this->orderManager->takenChargeByWaiter($order->getId(), $order->getWaiterId());
                    break;

                case Order::STATUS_WAITER_DELIVERING: // to Order::STATUS_DELIVERED
                    $this->line('STATUS_WAITER_DELIVERING -> STATUS_DELIVERED');
                    $this->orderManager->completedByWaiter($order->getId());
                    break;

                case Order::STATUS_DELIVERED: // to Order::STATUS_PAYED
                    $this->line('STATUS_DELIVERED -> STATUS_PAYED');
                    $this->orderManager->changeStatus($order->getId(), Order::STATUS_PAYED);
                    break;

                default:
                    break;
            }
        }
        catch(\Exception $e)
        {
            Log::error(sprintf(
                'Order id[%d] cannot be automatically processed from status id[%d](%s)',
                $order->getId(),
                $order->getStatus(),
                Order::getStatusName($order->getStatus())
            ));

            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            $this->orderManager->changeStatus($order->getId(), Order::STATUS_DISCARDED);
        }

        return $order;
    }

    /**
     * @param OrderStatusLog $lastOrderStatus
     * @return bool
     */
    private function decideIfStatusIsCompleted(OrderStatusLog $lastOrderStatus): bool
    {
        switch($lastOrderStatus->getStatus()) {
            case Order::STATUS_CUSTOMER_COMPILING: // to Order::STATUS_DELETED (in case of an abandoned order)
                return $this->hasRandomTimePassed(1800, 3600, $lastOrderStatus->getCreatedAt());

            case Order::STATUS_READY_TO_BE_MADE: // to Order::STATUS_BARTENDER_MAKING
                return $this->hasRandomTimePassed(4, 12, $lastOrderStatus->getCreatedAt());

            case Order::STATUS_BARTENDER_MAKING: // to Order::STATUS_READY_TO_BE_DELIVERED
                return $this->hasRandomTimePassed(8, 30, $lastOrderStatus->getCreatedAt());

            case Order::STATUS_READY_TO_BE_DELIVERED: // to Order::STATUS_DELIVERING
                return $this->hasRandomTimePassed(4, 12, $lastOrderStatus->getCreatedAt());

            case Order::STATUS_WAITER_DELIVERING: // to Order::STATUS_DELIVERED
                return $this->hasRandomTimePassed(8, 20, $lastOrderStatus->getCreatedAt());

            case Order::STATUS_DELIVERED: // to Order::STATUS_PAYED
                return $this->hasRandomTimePassed(8, 14, $lastOrderStatus->getCreatedAt());

            default:
                return false;
        }
    }

    /**
     * @param int $secFrom
     * @param int $secTo
     * @param \DateTime $date
     * @return bool
     */
    private function hasRandomTimePassed(int $secFrom, int $secTo, \DateTime $date): bool
    {
        $now = new \DateTime();

        $seconds = mt_rand($secFrom * $this->timeMultiplier, $secTo * $this->timeMultiplier);
        $refDate = clone $date;
        $refDate->modify(sprintf('+%s seconds', $seconds));

        return $now > $refDate;
    }

}