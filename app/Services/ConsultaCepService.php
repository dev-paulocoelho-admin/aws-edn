<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ConsultaCepService implements ConsultaCepServiceInterface
{
    public function consultar(string $cep): array
    {
        Log::info('ViaCEP | Iniciando consulta', [
            'cep' => $cep,
            'endpoint' => 'https://viacep.com.br/ws/{cep}/json/',
            'method' => 'GET',
        ]);

        try {
            $response = Http::get("https://viacep.com.br/ws/$cep/json/");
        } catch (ConnectionException|RequestException $e) {
            Log::error('ViaCEP | Erro de conexão', [
                'cep' => $cep,
                'exception' => $e->getMessage(),
            ]);

            return $this->erroPadrao();
        }

        if (!$response->successful()) {
            Log::error('ViaCEP | Status inválido', [
                'cep' => $cep,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return $this->erroPadrao();
        }

        $dadosEndereco = $response->json();

        if (!empty($dadosEndereco['erro'])) {
            Log::warning('ViaCEP | CEP inexistente', [
                'cep' => $cep,
                'payload' => $dadosEndereco,
            ]);

            return $this->erroPadrao();
        }

        Log::info('ViaCEP | Validando payload');

        $validator = Validator::make($dadosEndereco, [
            'cep' => 'required|string|size:9',
            'uf' => 'required|string|size:2',
            'estado' => 'required|string|max:255',
            'localidade' => 'required|string|max:255',
            'logradouro' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            Log::warning('ViaCEP | Payload inválido', [
                'cep' => $cep,
                'errors' => $validator->errors()->toArray(),
            ]);

            return $this->erroPadrao();
        }

        Log::info('ViaCEP | Consulta finalizada com sucesso', [
            'cep' => $cep,
        ]);

        return $dadosEndereco;
    }

    private function erroPadrao(): array
    {
        return [
            'message' => 'erro ao consultar a api de cep, cep informado invalido',
        ];
    }
}
