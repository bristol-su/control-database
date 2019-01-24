<?php

namespace App\Listeners\ZapierWebhooks;


use App\Models\Group;
use Illuminate\Support\Facades\Log;
use App\Events\GroupActivated;

class AnyGroupActivated extends ZapierWebhookListener
{

    protected $eventSubscribeName = "AnyGroupActivated";

    /** @var Group */
    private $group;

    public function formatForZapier($filter)
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
     * - When a group is restored having been deactivated
     *
     * @param GroupActivated $event
     *
     * @return void
     */
    public function handle(GroupActivated $event)
    {
        $this->group = $event->group;
        $this->trigger();
    }
}
