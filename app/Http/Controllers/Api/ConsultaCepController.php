<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ConsultaCepServiceInterface;
use Illuminate\Http\JsonResponse;

class ConsultaCepController extends Controller
{
    protected ConsultaCepServiceInterface $cepService;

    public function __construct(ConsultaCepServiceInterface $cepService)
    {
        $this->cepService = $cepService;
    }

    /**
     * @param string $cep
     * @return JsonResponse
     */
    public function consultarApiCep(string $cep): JsonResponse
    {
        $cep = $this->cepService->consultar($cep);
        return response()->json(['data' => $cep]);
    }
}
