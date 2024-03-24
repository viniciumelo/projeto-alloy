<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class PedidoWebhookTest extends TestCase
{
    /**
     * Testa a função receberPedido da controller PedidoWebhookController.
     *
     * @return void
     */
    public function testReceberPedido()
    {
        $payload = file_get_contents(public_path('payload_processo_seletivo.json'));

        $response = $this->postJson('/webhook/pedidos', json_decode($payload, true));

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                     'mensagem' => 'Pedido recebido com sucesso'
                 ]);
    }
}

