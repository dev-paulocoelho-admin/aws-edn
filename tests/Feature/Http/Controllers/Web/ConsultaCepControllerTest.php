<?php

namespace Tests\Feature\Http\Controllers\Web;

use App\Models\ConsultaCep;
use App\Models\User;
use App\Services\ConsultaCepServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class ConsultaCepControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Testa se a página de listagem de CEPs é exibida com sucesso.
     */
    public function test_obter_listagem_cep_exibe_view(): void
    {
        ConsultaCep::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->get(route('cep.index'));

        $response->assertStatus(200);
        $response->assertViewIs('cep.index');
        $response->assertViewHas('ceps');
    }

    /**
     * Testa se a listagem de CEPs retorna dados paginados.
     */
    public function test_obter_listagem_cep_retorna_dados(): void
    {
        $consultas = ConsultaCep::factory(5)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->get(route('cep.index'));

        $response->assertStatus(200);
        $response->assertViewHas('ceps');
    }

    /**
     * Testa a consulta de CEP com dados válidos.
     */
    public function test_consultar_via_tela_cep_valido(): void
    {
        $this->mock(ConsultaCepServiceInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('show')
                ->once()
                ->with('01310100')
                ->andReturn([
                    'cep' => '01310-100',
                    'logradouro' => 'Avenida Paulista',
                    'bairro' => 'Bela Vista',
                    'localidade' => 'São Paulo',
                    'uf' => 'SP',
                ]);
        });

        $response = $this->actingAs($this->user)
            ->get(route('cep.consultar', ['cep' => '01310100']));

        $response->assertRedirectToRoute('cep.index');
        $response->assertSessionHas('success', 'CEP consultado e registrado com sucesso.');
    }

    /**
     * Testa a consulta com CEP inválido retorna mensagem de erro.
     */
    public function test_consultar_via_tela_cep_invalido(): void
    {
        $this->mock(ConsultaCepServiceInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('show')
                ->once()
                ->with('99999999')
                ->andReturn([
                    'message' => 'Não foi possível consultar o CEP informado.',
                ]);
        });

        $response = $this->actingAs($this->user)
            ->get(route('cep.consultar', ['cep' => '99999999']));

        $response->assertRedirectToRoute('cep.index');
        $response->assertSessionHas('error', 'Não foi possível consultar o CEP informado.');
    }

    /**
     * Testa validação obrigatória do campo CEP.
     */
    public function test_consultar_via_tela_cep_obrigatorio(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('cep.consultar', ['cep' => '']));

        $response->assertSessionHasErrors('cep');
    }

    /**
     * Testa que usuário não autenticado é redirecionado.
     */
    public function test_consultar_via_tela_usuario_nao_autenticado(): void
    {
        $response = $this->get(route('cep.consultar', ['cep' => '01310100']));

        $response->assertRedirectToRoute('login');
    }

    /**
     * Testa que listagem requer autenticação.
     */
    public function test_obter_listagem_cep_requer_autenticacao(): void
    {
        $response = $this->get(route('cep.index'));

        $response->assertRedirectToRoute('login');
    }

    /**
     * Testa consulta com CEP em formato com máscara.
     */
    public function test_consultar_via_tela_cep_com_mascara(): void
    {
        $this->mock(ConsultaCepServiceInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('show')
                ->once()
                ->with('01310-100')
                ->andReturn([
                    'cep' => '01310-100',
                    'logradouro' => 'Avenida Paulista',
                    'bairro' => 'Bela Vista',
                    'localidade' => 'São Paulo',
                    'uf' => 'SP',
                ]);
        });

        $response = $this->actingAs($this->user)
            ->get(route('cep.consultar', ['cep' => '01310-100']));

        $response->assertRedirectToRoute('cep.index');
        $response->assertSessionHas('success');
    }

    /**
     * Testa múltiplas consultas pelo mesmo usuário.
     */
    public function test_multiplas_consultas_mesmo_usuario(): void
    {
        $this->mock(ConsultaCepServiceInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('show')
                ->andReturn([
                    'cep' => '01310-100',
                    'logradouro' => 'Avenida Paulista',
                    'bairro' => 'Bela Vista',
                    'localidade' => 'São Paulo',
                    'uf' => 'SP',
                ]);
        });

        $response1 = $this->actingAs($this->user)
            ->get(route('cep.consultar', ['cep' => '01310100']));

        $response2 = $this->actingAs($this->user)
            ->get(route('cep.consultar', ['cep' => '20040020']));

        $response1->assertSessionHas('success');
        $response2->assertSessionHas('success');
    }

    /**
     * Testa erro quando serviço retorna mensagem.
     */
    public function test_consultar_via_tela_servico_retorna_erro(): void
    {
        $this->mock(ConsultaCepServiceInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('show')
                ->once()
                ->andReturn([
                    'message' => 'Erro ao conectar com a API ViaCEP',
                ]);
        });

        $response = $this->actingAs($this->user)
            ->get(route('cep.consultar', ['cep' => '01310100']));

        $response->assertRedirectToRoute('cep.index');
        $response->assertSessionHas('error', 'Erro ao conectar com a API ViaCEP');
    }

    /**
     * Testa que dados vazios causam redirecionamento com erro.
     */
    public function test_consultar_via_tela_sem_dados(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('cep.consultar'));

        $response->assertSessionHasErrors();
    }

    /**
     * Testa se a listagem é paginada.
     */
    public function test_obter_listagem_cep_paginacao(): void
    {
        // Criar mais de 7 registros para testar paginação
        ConsultaCep::factory(10)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->get(route('cep.index'));

        $response->assertStatus(200);
        $ceps = $response->viewData('ceps');
        $this->assertTrue(method_exists($ceps, 'hasPages'));
    }
}

