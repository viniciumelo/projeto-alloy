<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessarPedido;


class PedidoWebhookController extends Controller
{
    public function receberPedido(Request $request)
    {
        $dadosPedido = $request->all();

        // Despachar a Job para processar o pedido de forma assíncrona
        ProcessarPedido::dispatch($dadosPedido);

        // Retornar uma resposta adequada ao serviço que enviou o webhook
        return response()->json(['mensagem' => 'Pedido recebido com sucesso'], 200);
    }

    public function pedidoPendente($pedido_ref)
    {
        try {
            return response()->json(['mensagem' => 'Pedido pendente recebido com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['erro' => 'Erro ao processar o pedido pendente', 'detalhes' => $e->getMessage()], 500);
        }
    }

    public function pedidoCancelado($pedido_ref)
    {
        try {
            return response()->json(['mensagem' => 'Pedido cancelado recebido com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['erro' => 'Erro ao processar o pedido cancelado', 'detalhes' => $e->getMessage()], 500);
        }
    }
}
