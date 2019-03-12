<?php

namespace App\Listeners\ZapierWebhooks;

use App\ZapierWebhook;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Cast\Object_;

class ZapierWebhookListener implements ShouldQueue
{

    public function __construct()
    {

    }

    /**
     * Name for the webhook to subscribe to
     *
     * @var string
     */
    protected $eventSubscribeName = '';

    /**
     * Send webhooks to Zapier
     *
     * @param object A class to be used in the formatForZapier function
     *
     * @throws \Exception
     *
     * @return boolean
     */
    public function trigger()
    {
        $event = $this->getEventFromListener();

        $webhooks = $this->getWebhooksSubscribedToEvent($event);

        if(count($webhooks) === 0)
        {
            return true;
        }

        $this->sendWebhooks($webhooks);
    }

    /**
     * Takes an array of data held in the filter column, and
     * returns data to return to Zapier.
     *
     * Return false if the data shouldn't be sent.
     *
     * @param array $filter
     *
     * @return array
     */
    protected function formatForZapier($filter)
    {
        return $filter;
    }

    protected function passFilter($filter)
    {
        return true;
    }

    /**
     * @param array $filter
     *
     * @return array|bool
     */
    private function getDataForZapier($filter)
    {
        return $this->formatForZapier($filter);
    }
    /**
     * Gets the event name from the listener, assuming the listener has
     * a protected property $eventSubscribeName
     *
     * @return string
     *
     * @throws \Exception
     */
    private function getEventFromListener()
    {
        return $this->eventSubscribeName;
    }

    /**
     * Search the database for webhooks subscribed to the
     * event name given
     *
     * @param string $event
     *
     * @return Collection
     */
    private function getWebhooksSubscribedToEvent($event)
    {
        return ZapierWebhook::where('event', $event)->get();
    }

    /**
     * Send an array of webhooks.
     *
     * Zapier will recieve the result of $callback, given
     * an associative array of the filer.
     *
     * @param Collection $webhooks

     *
     * @return bool
     */
    private function sendWebhooks($webhooks)
    {
        foreach($webhooks as $webhook)
        {
            if($this->passFilter($webhook->filter) !== true)
            {
                continue;
            }

            try
            {

                $data = $this->getDataForZapier($webhook->filter);
                $webhook->fire($data);

            } catch (\Exception $e)
            {
            }
        }

        return true;
    }
}