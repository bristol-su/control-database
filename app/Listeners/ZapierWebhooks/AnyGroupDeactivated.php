<?php

namespace App\Listeners\ZapierWebhooks;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Events\GroupDeactivated;

class AnyGroupDeactivated extends ZapierWebhookListener
{

    protected $eventSubscribeName = 'AnyGroupDeactivated';
    protected $group;

    protected function formatForZapier($filter)
    {
        return $this->group->toArray();
    }

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * Will update the webhooks in the following conditions:
     * - Any group is deactivated
     *
     * @param GroupDeactivated $event
     * @return void
     */
    public function handle(GroupDeactivated $event)
    {
        $this->group = $event->group;
        $this->trigger();
    }
}
