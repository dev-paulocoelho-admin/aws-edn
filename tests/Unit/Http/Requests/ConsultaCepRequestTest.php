<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\ConsultaCepRequest;
use Tests\TestCase;

class ConsultaCepRequestTest extends TestCase
{
    /**
     * Testa que o CEP é obrigatório.
     */
    public function test_cep_obrigatorio(): void
    {
        $request = new ConsultaCepRequest();

        $this->assertTrue(
            $this->validates($request, [])['fails']
        );
    }

    /**
     * Testa que CEP válido com 8 dígitos passa na validação.
     */
    public function test_cep_valido_8_digitos(): void
    {
        $request = new ConsultaCepRequest();

        $this->assertFalse(
            $this->validates($request, ['cep' => '01310100'])['fails']
        );
    }

    /**
     * Testa que CEP com máscara 00000-000 passa na validação.
     */
    public function test_cep_valido_com_mascara(): void
    {
        $request = new ConsultaCepRequest();

        $this->assertFalse(
            $this->validates($request, ['cep' => '01310-100'])['fails']
        );
    }

    /**
     * Testa que CEP inválido falha na validação.
     */
    public function test_cep_invalido(): void
    {
        $request = new ConsultaCepRequest();

        $this->assertTrue(
            $this->validates($request, ['cep' => '123'])['fails']
        );
    }

    /**
     * Testa que CEP com letras falha na validação.
     */
    public function test_cep_com_letras(): void
    {
        $request = new ConsultaCepRequest();

        $this->assertTrue(
            $this->validates($request, ['cep' => 'ABCD1234'])['fails']
        );
    }

    /**
     * Helper para validar um request.
     */
    private function validates(ConsultaCepRequest $request, array $data): array
    {
        $validator = validator($data, $request->rules(), $request->messages());

        return [
            'fails' => $validator->fails(),
        ];
    }
}

