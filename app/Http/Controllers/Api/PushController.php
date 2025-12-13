<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PushController extends Controller
{
    /**
     * Store the PushSubscription.
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required',
        ]);
        $endpoint = $request->endpoint;
        $token = $request->keys['auth'];
        $key = $request->keys['p256dh'];
        $user = $request->user();
        // Atualiza ou cria a inscrição para este usuário
        $user->updatePushSubscription(
            $endpoint,
            $key,
            $token
        );
        return response()->json(['success' => true], 200);
    }
    
    /**
     * (Opcional) Unsubscribe
     */
    public function unsubscribe(Request $request)
    {
        $request->validate(['endpoint' => 'required']);
        
        $request->user()->deletePushSubscription($request->endpoint);
        
        return response()->json(null, 204);
    }
}
