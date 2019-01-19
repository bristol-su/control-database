<?php

namespace App\Listeners\ZapierWebhooks;
use App\Events\GroupCreated;
use App\Models\Group;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class AnyGroupCreated extends ZapierWebhookListener
{

    protected $eventSubscribeName = "AnyGroupCreated";

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
     * - A group is created
     *
     * @throws \Exception
     *
     * @param GroupCreated $event
     * @return void
     */
    public function handle(GroupCreated $event)
    {
        $this->group = $event->group;
        $this->trigger();
    }
}
