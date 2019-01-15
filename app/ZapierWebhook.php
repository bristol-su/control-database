<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ZapierWebhook extends Model
{
    protected $table = 'webhooks';
    public $fillable = ['id', 'url', 'event', 'tenant_id'];

    public function fire($data)
    {
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
