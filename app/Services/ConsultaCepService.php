<?php

namespace App\Services;

use App\Repositories\ConsultaCepRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ConsultaCepService implements ConsultaCepServiceInterface
{
    protected ConsultaCepRepositoryInterface $consultaCepRepository;

    public function __construct(ConsultaCepRepositoryInterface $consultaCepRepository)
    {
        $this->consultaCepRepository = $consultaCepRepository;
    }

    /**
     * @inheritDoc
     */
    public function show(string $cep): array
    {
        $cep = preg_replace('/\D/', '', $cep);

        if (!preg_match('/^\d{8}$/', $cep)) {
            Log::warning('ViaCEP | Formato de CEP inválido', ['cep' => $cep]);
            return $this->erroPadrao();
        }

        try {
            Log::info('ViaCEP | Iniciando consulta', ['cep' => $cep]);

            $response = Http::get("https://viacep.com.br/ws/$cep/json/");
        } catch (ConnectionException $e) {
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
            ]);

            return $this->erroPadrao();
        }

        $payload = $response->json();

        if (!empty($payload['erro']) || !$this->payloadValido($payload, $cep)) {
            return $this->erroPadrao();
        }

        $this->consultaCepRepository->store($cep, $payload);

        Log::info('ViaCEP | Consulta finalizada com sucesso', ['cep' => $cep]);

        return $payload;
    }

    /**
     * Mensagem de erro padrão.
     * @return string[]
     */
    private function erroPadrao(): array
    {
        return [
            'message' => 'erro ao consultar a api de cep, cep informado invalido',
        ];
    }

    /**
     * Valida o payload retornado pela API ViaCEP.
     * @param array $payload
     * @param string $cep
     * @return bool
     */
    private function payloadValido(array $payload, string $cep): bool
    {
        $validator = Validator::make($payload, [
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

            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function listarCepsConsultados(): Paginator
    {
        return $this->consultaCepRepository->index();
    }
}
