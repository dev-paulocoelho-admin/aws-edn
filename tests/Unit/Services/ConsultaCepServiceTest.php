<?php

namespace Tests\Unit\Services;

use App\Repositories\ConsultaCepRepositoryInterface;
use App\Services\ConsultaCepService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class ConsultaCepServiceTest extends TestCase
{
    private ConsultaCepService $service;
    private MockObject|ConsultaCepRepositoryInterface $repositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = $this->createMock(ConsultaCepRepositoryInterface::class);
        $this->service = new ConsultaCepService($this->repositoryMock);
    }

    /**
     * Testa a consulta bem-sucedida de um CEP válido.
     */
    public function test_consulta_cep_valido_com_sucesso(): void
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
            'siafi' => '7107'
        ];

        Http::fake([
            'https://viacep.com.br/ws/*' => Http::response($payload, 200)
        ]);

        $this->repositoryMock
            ->expects($this->once())
            ->method('store')
            ->with($cep, $payload);

        $resultado = $this->service->show($cep);

        $this->assertEquals($payload, $resultado);
    }

    /**
     * Testa a consulta com CEP em formato com máscara.
     */
    public function test_consulta_cep_com_mascara(): void
    {
        $cepComMascara = '01310-100';
        $cepNormalizado = '01310100';
        $payload = [
            'cep' => '01310-100',
            'logradouro' => 'Avenida Paulista',
            'bairro' => 'Bela Vista',
            'localidade' => 'São Paulo',
            'uf' => 'SP',
        ];

        Http::fake([
            'https://viacep.com.br/ws/01310100/json/' => Http::response($payload, 200)
        ]);

        $this->repositoryMock
            ->expects($this->once())
            ->method('store')
            ->with($cepNormalizado, $payload);

        $resultado = $this->service->show($cepComMascara);

        $this->assertEquals($payload, $resultado);
    }

    /**
     * Testa a consulta com CEP inválido (formato incorreto).
     */
    public function test_consulta_cep_formato_invalido(): void
    {
        $cep = '123';

        $this->repositoryMock
            ->expects($this->once())
            ->method('store');

        $resultado = $this->service->show($cep);

        $this->assertArrayHasKey('message', $resultado);
        $this->assertEquals(
            'Não foi possível consultar o CEP informado.',
            $resultado['message']
        );
    }

    /**
     * Testa a consulta com CEP contendo caracteres especiais.
     */
    public function test_consulta_cep_com_caracteres_especiais(): void
    {
        $cep = 'ABC-DEF!!';

        $this->repositoryMock
            ->expects($this->once())
            ->method('store');

        $resultado = $this->service->show($cep);

        $this->assertArrayHasKey('message', $resultado);
    }

    /**
     * Testa a resposta quando o CEP não é encontrado na API.
     */
    public function test_consulta_cep_nao_encontrado(): void
    {
        $cep = '99999999';

        Http::fake([
            'https://viacep.com.br/ws/*' => Http::response(['erro' => true], 200)
        ]);

        $this->repositoryMock
            ->expects($this->once())
            ->method('store');

        $resultado = $this->service->show($cep);

        $this->assertArrayHasKey('message', $resultado);
        $this->assertEquals(
            'Não foi possível consultar o CEP informado.',
            $resultado['message']
        );
    }

    /**
     * Testa o comportamento quando a API retorna um payload inválido.
     */
    public function test_consulta_cep_payload_invalido(): void
    {
        $cep = '01310100';
        $payloadInvalido = [
            'cep' => '01310-100',
            // Faltam campos obrigatórios
        ];

        Http::fake([
            'https://viacep.com.br/ws/*' => Http::response($payloadInvalido, 200)
        ]);

        $this->repositoryMock
            ->expects($this->once())
            ->method('store');

        $resultado = $this->service->show($cep);

        $this->assertArrayHasKey('message', $resultado);
    }

    /**
     * Testa o comportamento quando a API retorna status 404.
     */
    public function test_consulta_cep_api_retorna_404(): void
    {
        $cep = '01310100';

        Http::fake([
            'https://viacep.com.br/ws/*' => Http::response([], 404)
        ]);

        $this->repositoryMock
            ->expects($this->once())
            ->method('store');

        $resultado = $this->service->show($cep);

        $this->assertArrayHasKey('message', $resultado);
    }

    /**
     * Testa o comportamento quando há erro de conexão com a API.
     */
    public function test_consulta_cep_erro_conexao_api(): void
    {
        $cep = '01310100';

        Http::fake(function () {
            throw new ConnectionException('Connection refused');
        });

        $this->repositoryMock
            ->expects($this->once())
            ->method('store');

        $resultado = $this->service->show($cep);

        $this->assertArrayHasKey('message', $resultado);
        $this->assertEquals(
            'Não foi possível consultar o CEP informado.',
            $resultado['message']
        );
    }

    /**
     * Testa a listagem de CEPs consultados.
     */
    public function test_listar_ceps_consultados(): void
    {
        $paginadorMock = $this->createMock(\Illuminate\Contracts\Pagination\Paginator::class);

        $this->repositoryMock
            ->expects($this->once())
            ->method('index')
            ->willReturn($paginadorMock);

        $resultado = $this->service->listarCepsConsultados();

        $this->assertSame($paginadorMock, $resultado);
    }

    /**
     * Testa que o log é chamado quando há erro de formato.
     */
    public function test_log_chamado_em_erro_formato(): void
    {
        $cep = '123';

        Log::shouldReceive('warning')
            ->withArgs(function ($message) {
                return $message === 'ViaCEP | Formato de CEP inválido';
            })
            ->once();

        $this->repositoryMock
            ->expects($this->once())
            ->method('store');

        $this->service->show($cep);
    }

    /**
     * Testa que o log é chamado quando há sucesso na consulta.
     */
    public function test_log_chamado_em_sucesso(): void
    {
        $cep = '01310100';
        $payload = [
            'cep' => '01310-100',
            'logradouro' => 'Avenida Paulista',
            'bairro' => 'Bela Vista',
            'localidade' => 'São Paulo',
            'uf' => 'SP',
        ];

        Http::fake([
            'https://viacep.com.br/ws/*' => Http::response($payload, 200)
        ]);

        Log::shouldReceive('info')
            ->twice(); // Uma no início e uma no fim

        $this->repositoryMock
            ->expects($this->once())
            ->method('store');

        $this->service->show($cep);
    }
}

