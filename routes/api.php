<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\DrawController;
use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\PushController;

Route::prefix('v1')->group(function () {

    Route::get('status', function () {
        return response()->json(['status' => 'API V1 is alive!'], 200);
    });


    // Rotas públicas de autenticação
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Rota pública para visualizar convite
    Route::get('/invitations/{invite_code}', [InvitationController::class, 'show']);


    // Rotas protegidas (exigem autenticação)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::post('/push/subscribe', [PushController::class, 'subscribe']);
        Route::post('/push/unsubscribe', [PushController::class, 'unsubscribe']);

        // Grupos
        Route::apiResource('groups', GroupController::class);

        // Sorteio
        Route::post('/groups/{group}/draw', [DrawController::class, 'store']);
        Route::get('/groups/{group}/draw', [DrawController::class, 'show']);

        
        // Convites
        Route::post('/invitations/{invite_code}/accept', [InvitationController::class, 'accept']);

        // Lista de Desejos (Wishlist)
        Route::apiResource('participants.wishlist', WishlistController::class)
            ->only(['store', 'update', 'destroy']);

        
        // Atualizar perfil do participante (notas, endereço)
        Route::put('/participants/{participant}', [WishlistController::class, 'updateProfile']);
    });

});