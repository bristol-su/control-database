<?php

namespace App\Listeners\ZapierWebhooks;

use App\Models\Group;
use Illuminate\Support\Facades\Log;
use Venturecraft\Revisionable\Revision;

class SpecificAttributeOfAnyGroupUpdated extends ZapierWebhookListener
{

    protected $eventSubscribeName = 'SpecificAttributeOfAnyGroupUpdated';

    private $group;
    private $revision;

    public function formatForZapier($filter)
    {
        return [
            'group_name' => $this->group->name,
            'group_unioncloud_id' => $this->group->uninocloud_id,
            'group_general_email' => $this->group->email,
            'group_information_updated' => $this->revision['key'],
            'old_group_information' => $this->revision['old_value']
        ];
    }

    public function passFilter($filter)
    {

        if($this->revision['key'] === $filter['attribute'])
        {
            return $this->group instanceof Group;
        }
        return false;
    }
    /**
     * Handle the event.
     *
     * Will update the webhooks in the following conditions:
     *
     * - Any group has one of the following attributes updated.
     * i.e. if a webhook wants to listen for the 'name' attribute,
     * only when the 'name' attribute is updated will the webhook trigger.
     * - name
     * - email
     * - unioncloud_id
     *
     *
     * @param Group $group
     * param array $revisions
     *
     * @throws \Exception
     *
     * @return void
     */
    public function handle($group, $revisions)
    {
        $this->group = $group;
        foreach($revisions as $revision)
        {
            if(in_array($revision['key'], ['name', 'unioncloud_id', 'email']))
            {
                $this->revision = $revision;
                $this->trigger();
            }
        }
    }
}
