<?php

namespace Tests\Unit\Repositories;

use App\Models\ConsultaCep;
use App\Models\User;
use App\Repositories\ConsultaCepRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ConsultaCepRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ConsultaCepRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ConsultaCepRepository(new ConsultaCep());
    }

    /**
     * Testa o armazenamento de uma consulta de CEP com sucesso.
     */
    public function test_store_consulta_cep_com_sucesso(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $cep = '01310100';
        $payload = [
            'cep' => '01310-100',
            'logradouro' => 'Avenida Paulista',
            'bairro' => 'Bela Vista',
            'localidade' => 'São Paulo',
            'uf' => 'SP',
        ];

        $this->repository->store($cep, $payload);

        $this->assertDatabaseHas('consulta_ceps', [
            'cep' => $cep,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Testa o armazenamento de uma consulta de CEP sem payload.
     */
    public function test_store_consulta_cep_sem_payload(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $cep = '01310100';

        $this->repository->store($cep);

        $this->assertDatabaseHas('consulta_ceps', [
            'cep' => $cep,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Testa o armazenamento de uma consulta de CEP com erro.
     */
    public function test_store_consulta_cep_com_erro(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $cep = '12345678';
        $payload = [
            'erro' => true,
            'mensagem' => 'Falha na consulta do CEP',
            'cep_original' => '12345-678',
        ];

        $this->repository->store($cep, $payload);

        $this->assertDatabaseHas('consulta_ceps', [
            'cep' => $cep,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Testa a listagem de CEPs consultados.
     */
    public function test_index_lista_ceps_paginados(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Criar 10 consultas
        for ($i = 0; $i < 10; $i++) {
            ConsultaCep::factory()->create(['user_id' => $user->id]);
        }

        $paginador = $this->repository->index();

        $this->assertCount(7, $paginador->items()); // 7 por página
        $this->assertEquals(1, $paginador->currentPage());
    }

    /**
     * Testa a paginação de CEPs consultados.
     */
    public function test_index_paginacao_multiplas_paginas(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Criar 15 consultas
        for ($i = 0; $i < 15; $i++) {
            ConsultaCep::factory()->create(['user_id' => $user->id]);
        }

        $paginador = $this->repository->index();

        $this->assertTrue($paginador->hasPages());
        $this->assertEquals(15, $paginador->total());
    }

    /**
     * Testa que a listagem retorna os registros ordenados por data (mais recentes primeiro).
     */
    public function test_index_ordenacao_por_data_descrescente(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $cep1 = ConsultaCep::factory()->create([
            'user_id' => $user->id,
            'cep' => '01310100'
        ]);

        sleep(1);

        $cep2 = ConsultaCep::factory()->create([
            'user_id' => $user->id,
            'cep' => '20040020'
        ]);

        $paginador = $this->repository->index();
        $items = $paginador->items();

        // O item mais recente deve vir primeiro
        $this->assertEquals($cep2->id, $items[0]->id);
        $this->assertEquals($cep1->id, $items[1]->id);
    }

    /**
     * Testa que a listagem inclui os dados do usuário relacionado.
     */
    public function test_index_inclui_relacao_usuario(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        ConsultaCep::factory()->create(['user_id' => $user->id]);

        $paginador = $this->repository->index();
        $consulta = $paginador->items()[0];

        $this->assertNotNull($consulta->user);
        $this->assertEquals($user->id, $consulta->user->id);
    }

    /**
     * Testa o armazenamento em transação garante integridade.
     */
    public function test_store_usa_transacao(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $cep = '01310100';
        $payload = [
            'logradouro' => 'Avenida Paulista',
        ];

        $this->repository->store($cep, $payload);

        // Se chegou aqui, a transação foi bem-sucedida
        $this->assertDatabaseHas('consulta_ceps', [
            'cep' => $cep,
        ]);
    }

    /**
     * Testa que múltiplas consultas são armazenadas corretamente.
     */
    public function test_store_multiplas_consultas(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $ceps = ['01310100', '20040020', '30140071'];

        foreach ($ceps as $cep) {
            $this->repository->store($cep, []);
        }

        foreach ($ceps as $cep) {
            $this->assertDatabaseHas('consulta_ceps', [
                'cep' => $cep,
                'user_id' => $user->id,
            ]);
        }
    }

    /**
     * Testa que os campos obrigatórios são salvos corretamente.
     */
    public function test_store_salva_campos_obrigatorios(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $cep = '01310100';
        $payload = [
            'cep' => '01310-100',
            'logradouro' => 'Avenida Paulista',
            'bairro' => 'Bela Vista',
            'localidade' => 'São Paulo',
            'uf' => 'SP',
        ];

        $this->repository->store($cep, $payload);

        $consulta = ConsultaCep::where('cep', $cep)->first();

        $this->assertNotNull($consulta);
        $this->assertEquals($cep, $consulta->cep);
        $this->assertEquals($payload, $consulta->payload);
        $this->assertEquals($user->id, $consulta->user_id);
    }
}

