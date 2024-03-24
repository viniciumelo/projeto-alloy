<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PedidoWebhookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('/webhook/pedidos', [PedidoWebhookController::class, 'receberPedido']);

Route::prefix('pedidos')->group(function () {
    Route::post('{pedido_ref}/pendente', [PedidoWebhookController::class, 'pedidoPendente']);
    Route::post('{pedido_ref}/cancelado', [PedidoWebhookController::class, 'pedidoCancelado']);
});