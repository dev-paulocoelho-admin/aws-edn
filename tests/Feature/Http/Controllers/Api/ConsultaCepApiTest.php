<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ConsultaCepApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Testa a busca de CEP via API com sucesso.
     */
    public function test_buscar_cep_via_api_sucesso(): void
    {
        $cep = '01310100';
        $payload = [
            'cep' => '01310-100',
            'logradouro' => 'Avenida Paulista',
            'complemento' => 'lado par',
            'bairro' => 'Bela Vista',
            'localidade' => 'São Paulo',
            'uf' => 'SP',
            'ibge' => '3550308',
            'gia' => '',
            'ddd' => '11',
            'siafi' => '7107',
        ];

        Http::fake([
            'https://viacep.com.br/ws/*' => Http::response($payload, 200),
        ]);

        $response = $this->actingAs($this->user)
            ->post('/api/consulta-cep', ['cep' => $cep]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'cep',
                'logradouro',
                'bairro',
                'localidade',
                'uf',
            ],
        ]);
    }

    /**
     * Testa a busca de CEP inválido via API.
     */
    public function test_buscar_cep_invalido_via_api(): void
    {
        Http::fake([
            'https://viacep.com.br/ws/*' => Http::response(['erro' => true], 200),
        ]);

        $response = $this->actingAs($this->user)
            ->post('/api/consulta-cep', ['cep' => '99999999']);

        $response->assertStatus(404);
    }

    /**
     * Testa que CEP é obrigatório na requisição.
     */
    public function test_buscar_cep_cep_obrigatorio(): void
    {
        $response = $this->actingAs($this->user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/consulta-cep', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('cep');
    }

    /**
     * Testa que autenticação é obrigatória.
     */
    public function test_buscar_cep_requer_autenticacao(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post('/api/consulta-cep', ['cep' => '01310100']);

        $response->assertStatus(401);
    }

    /**
     * Testa o histórico de CEPs consultados via API.
     */
    public function test_listar_historico_cep_via_api(): void
    {
        $response = $this->actingAs($this->user)
            ->get('/api/consulta-cep');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [],
        ]);
    }

    /**
     * Testa erro de conexão com ViaCEP.
     */
    public function test_erro_conexao_viacep(): void
    {
        Http::fake([
            'https://viacep.com.br/ws/*' => Http::response([], 500),
        ]);

        $response = $this->actingAs($this->user)
            ->post('/api/consulta-cep', ['cep' => '01310100']);

        $response->assertStatus(404);
    }

    /**
     * Testa CEP com máscara.
     */
    public function test_buscar_cep_com_mascara_via_api(): void
    {
        $payload = [
            'cep' => '01310-100',
            'logradouro' => 'Avenida Paulista',
            'bairro' => 'Bela Vista',
            'localidade' => 'São Paulo',
            'uf' => 'SP',
        ];

        Http::fake([
            'https://viacep.com.br/ws/*' => Http::response($payload, 200),
        ]);

        $response = $this->actingAs($this->user)
            ->post('/api/consulta-cep', ['cep' => '01310-100']);

        $response->assertStatus(201);
    }
}

