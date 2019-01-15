<?php

namespace App\Http\Controllers;

use App\ZapierWebhook;
use Illuminate\Http\Request;

class ZapierWebhookController extends Controller
{

    public function subscribe(Request $request) {
        $input = $request->all();

        $webhook = ZapierWebhook::create([
            "tenant_id" => auth()->user()->id,
            "url" => $input["target_url"],
            "event" => $input["event"]
        ]);

        return $webhook;
    }

    public function delete($id) {
        $webhook = ZapierWebhook::find($id);
        $webhook->delete();

        return response()->json([
            "success" => "success"
        ]);
    }

}
