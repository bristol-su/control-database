<?php

namespace App\Http\Controllers;

use App\ZapierWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ZapierWebhookController extends Controller
{

    public function subscribe(Request $request) {
        Log::info(json_encode($request->all()));
        $input = $request->all();
        // TODO Validate subscribe requests

        $webhook = ZapierWebhook::create([
            "user_id" => auth()->user()->id,
            "url" => $input["url"],
            "event" => $input["event"],
            "filter" => ($request->has('filter')?$input["filter"]:[])
        ]);

        return $webhook;
    }

    public function delete($id) {
        $webhook = ZapierWebhook::findOrFail($id);
        $webhook->delete();

        return response('', 204);
    }

}
