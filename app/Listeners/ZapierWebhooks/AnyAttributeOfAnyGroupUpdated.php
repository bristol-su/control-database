<?php

namespace App\Listeners\ZapierWebhooks;

use App\Models\Group;
use Illuminate\Support\Facades\Log;
use Venturecraft\Revisionable\Revision;

class AnyAttributeOfAnyGroupUpdated extends ZapierWebhookListener
{

    protected $eventSubscribeName = 'AnyAttributeOfAnyGroupUpdated';

    private $group;

    public function formatForZapier($filter)
    {
        return $this->group;
    }

    protected function passFilter($filter)
    {
        return $this->group instanceof Group;
    }


    /**
     * Handle the event.
     *
     * Will update the webhooks in the following conditions:
     *
     * When one of the following attributes of any group is updated.
     * - name
     * - email
     * - unioncloud_id
     *
     *
     * @param Group $group
     * @param array $revisions
     *
     * @throws \Exception
     *
     * @return void
     */
    public function handle($group, $revisions)
    {
        $this->group = $group;
        $this->trigger();
    }
}
