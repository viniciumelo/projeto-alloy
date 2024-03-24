<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class ProcessarPedido implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pedido;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($pedido)
    {
        $this->pedido = $pedido;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pedidos = $this->pedido['pedidos'];
    
        foreach ($pedidos as $pedido) {
            // Verificar se a chave 'ref' está definida antes de usá-la
            if (isset($pedido['ref'])) {
                // Verificar o status do pedido
                if (isset($pedido['status']) && $pedido['status'] == 0) {
                    // Enviar o pedido para o endpoint de cancelamento
                    $this->enviarPedidoParaEndpointCancelado($pedido);
                } elseif (isset($pedido['status']) && $pedido['status'] == 1) {
                    // Enviar o pedido para o endpoint pendente
                    $this->enviarPedidoParaEndpointPendente($pedido);
                }
    
                // Registrar a ação no Redis
                Redis::set("pedido_{$pedido['ref']}_status", $pedido['status']);
            } else {
                // Se 'ref' não estiver definido, registrar um log de erro
                Log::error('A chave "ref" não está definida no pedido:', $pedido);
            }
        }
    }
    


    protected function enviarPedidoParaEndpointCancelado($pedido)
    {
        try {
            // Lógica para enviar o pedido para o endpoint de cancelamento
            $response = Http::post('/pedidos/' . $pedido['ref'] . '/cancelado', $pedido);

            // Verifica se a resposta foi bem-sucedida
            if ($response->successful()) {
                // Retorna uma mensagem de sucesso
                return $response->json(['mensagem' => 'Pedido cancelado enviado com sucesso']);
            } else {
                // Retorna uma mensagem de erro se a resposta não foi bem-sucedida
                return response()->json(['erro' => 'Erro ao enviar pedido cancelado', 'detalhes' => $response->body()], $response->status());
            }
        } catch (\Exception $e) {
            // Captura e retorna uma resposta para erros de exceção
            return response()->json(['erro' => 'Erro ao enviar pedido cancelado', 'detalhes' => $e->getMessage()], 500);
        }
    }

    protected function enviarPedidoParaEndpointPendente($pedido)
    {
        try {
            // Lógica para enviar o pedido para o endpoint de pendente
            $response = Http::post('/pedidos/' . $pedido['ref'] . '/pendente', $pedido);
        
            // Verifica se a resposta foi bem-sucedida
            if ($response->successful()) {
                // Retorna uma mensagem de sucesso
                return $response->json(['mensagem' => 'Pedido pendente enviado com sucesso']);
            } else {
                // Retorna uma mensagem de erro se a resposta não foi bem-sucedida
                return response()->json(['erro' => 'Erro ao enviar pedido pendente', 'detalhes' => $response->body()], $response->status());
            }
        } catch (\Exception $e) {
            // Captura e retorna uma resposta para erros de exceção
            return response()->json(['erro' => 'Erro ao enviar pedido pendente', 'detalhes' => $e->getMessage()], 500);
        }
    }
}
