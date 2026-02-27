<?php

namespace App\Services;

use App\Repositories\ConsultaCepRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

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
        $cepOriginal = $cep;
        $cep = preg_replace('/\D/', '', $cep);

        if (!preg_match('/^\d{8}$/', $cep)) {
            Log::warning('ViaCEP | Formato de CEP inválido', [
                'cep' => $cepOriginal,
            ]);

            $this->registrarErro($cepOriginal, $cep);

            return $this->erroPadrao();
        }

        try {
            Log::info('ViaCEP | Iniciando consulta', [
                'cep' => $cep,
            ]);

            $response = Http::get("https://viacep.com.br/ws/$cep/json/");
        } catch (ConnectionException $e) {
            Log::error('ViaCEP | Erro de conexão', [
                'cep' => $cep,
                'exception' => $e->getMessage(),
            ]);

            $this->registrarErro($cepOriginal, $cep);

            return $this->erroPadrao();
        }

        if (!$response->successful()) {
            Log::error('ViaCEP | Status inválido', [
                'cep' => $cep,
                'status' => $response->status(),
            ]);

            $this->registrarErro($cepOriginal, $cep);

            return $this->erroPadrao();
        }

        $payload = $response->json();

        if (!empty($payload['erro']) || !$this->payloadValido($payload, $cep)) {
            Log::warning('ViaCEP | Payload inválido ou CEP inexistente', [
                'cep' => $cep,
                'payload' => $payload,
            ]);

            $this->registrarErro($cepOriginal, $cep);

            return $this->erroPadrao();
        }

        $this->consultaCepRepository->store($cep, $payload);

        Log::info('ViaCEP | Consulta finalizada com sucesso', [
            'cep' => $cep,
        ]);

        return $payload;
    }

    /**
     * Registra uma tentativa de consulta com erro.
     * @param string $cepOriginal
     * @param string $cepNormalizado
     * @return void
     * @throws Throwable
     */
    private function registrarErro(string $cepOriginal, string $cepNormalizado): void
    {
        $this->consultaCepRepository->store(
            $cepNormalizado,
            [
                'erro'         => true,
                'mensagem'     => 'Falha na consulta do CEP',
                'cep_original' => $cepOriginal,
            ]
        );
    }

    /**
     * Mensagem de erro padrão.
     * @return string[]
     */
    private function erroPadrao(): array
    {
        return [
            'message' => 'Não foi possível consultar o CEP informado.',
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
