<?php
/**
 * Created by PhpStorm.
 * User: Fabio
 * Date: 30/06/2020
 * Time: 11:26
 */
namespace App\Service\Order\Job;


use App\Database\Order\Order;
use App\Database\OrderStatusLog\OrderStatusLog;
use App\Service\Order\OrderManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ManageOrderStatusChangedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /** @var int */
    private $orderId;

    /** @var int */
    private $orderStatusOld;

    /**
     * MessageFromFakeManuallyAddedJob constructor.
     * @param int $orderId
     * @param int $orderStatusOld
     */
    public function __construct(int $orderId, int $orderStatusOld)
    {
        $this->orderId = $orderId;
        $this->orderStatusOld = $orderStatusOld;
    }

    public function handle()
    {
        if($this->attempts() > 9) {
            $now = new \DateTime();

            $this->fail(new \Exception(sprintf(
                'Max tries 9 reached [at %s]',
                $now->format('Y-m-d H:i:s'),
                $now->format('Y-m-d')
            )));
        }

        try {
            /** @var OrderManager $orderManager */
            $orderManager = resolve(OrderManager::class);
            $orderManager->manageOrderStatusChanged($this->orderId, $this->orderStatusOld);
        }
        catch(\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            $this->fail($e);
        }
    }
}