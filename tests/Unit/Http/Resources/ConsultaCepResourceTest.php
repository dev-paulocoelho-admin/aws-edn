<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\ConsultaCepResource;
use App\Models\ConsultaCep;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultaCepResourceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Testa a transformação do recurso em array.
     */
    public function test_recurso_transformado_em_array(): void
    {
        $user = User::factory()->create();
        $consulta = ConsultaCep::factory()
            ->forUser($user)
            ->create([
                'cep' => '01310100',
                'payload' => [
                    'cep' => '01310-100',
                    'logradouro' => 'Avenida Paulista',
                    'bairro' => 'Bela Vista',
                    'localidade' => 'São Paulo',
                    'uf' => 'SP',
                    'complemento' => 'lado par',
                ],
            ]);

        $resource = new ConsultaCepResource($consulta);
        $array = $resource->resolve();

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('cep', $array);
        $this->assertArrayHasKey('logradouro', $array);
        $this->assertArrayHasKey('bairro', $array);
        $this->assertArrayHasKey('localidade', $array);
        $this->assertArrayHasKey('uf', $array);
        $this->assertArrayHasKey('user', $array);
        $this->assertArrayHasKey('payload', $array);
        $this->assertArrayHasKey('created_at', $array);
        $this->assertArrayHasKey('updated_at', $array);
    }

    /**
     * Testa que o recurso contém os dados corretos.
     */
    public function test_recurso_contem_dados_corretos(): void
    {
        $user = User::factory()->create([
            'name' => 'João Silva',
            'email' => 'joao@example.com',
        ]);

        $consulta = ConsultaCep::factory()
            ->forUser($user)
            ->create([
                'cep' => '01310100',
                'payload' => [
                    'logradouro' => 'Avenida Paulista',
                    'bairro' => 'Bela Vista',
                ],
            ]);

        $resource = new ConsultaCepResource($consulta);
        $array = $resource->resolve();

        $this->assertEquals('01310100', $array['cep']);
        $this->assertEquals('Avenida Paulista', $array['logradouro']);
        $this->assertEquals('Bela Vista', $array['bairro']);
        $this->assertEquals('João Silva', $array['user']['name']);
        $this->assertEquals('joao@example.com', $array['user']['email']);
    }

    /**
     * Testa que o recurso trata payload nulo corretamente.
     */
    public function test_recurso_trata_payload_nulo(): void
    {
        $user = User::factory()->create();
        $consulta = ConsultaCep::factory()
            ->forUser($user)
            ->create([
                'cep' => '99999999',
                'payload' => [],
            ]);

        $resource = new ConsultaCepResource($consulta);
        $array = $resource->resolve();

        $this->assertNull($array['logradouro']);
        $this->assertNull($array['bairro']);
        $this->assertNull($array['localidade']);
        $this->assertNull($array['uf']);
    }

    /**
     * Testa que as datas estão em formato ISO8601.
     */
    public function test_datas_em_formato_iso8601(): void
    {
        $user = User::factory()->create();
        $consulta = ConsultaCep::factory()->forUser($user)->create();

        $resource = new ConsultaCepResource($consulta);
        $array = $resource->resolve();

        // Verificar se está em formato ISO8601
        $this->assertMatchesRegularExpression(
            '/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/',
            $array['created_at']
        );

        $this->assertMatchesRegularExpression(
            '/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/',
            $array['updated_at']
        );
    }
}

