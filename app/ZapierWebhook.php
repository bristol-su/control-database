<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ZapierWebhook extends Model
{
    public $fillable = ['url', 'event', 'user_id', 'filter'];

    protected $casts = [
        'filter' => 'array'
    ];

    public function fire($data)
    {
        Log::info('Triggering webhook '.$this->id.' with data from the event '.$this->event);

        $client = new Client();

        $response = "";

        try {
            $response = $client->request('POST', $this->url, [
                'json' => $data
            ]);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
        return $response;
    }
}
