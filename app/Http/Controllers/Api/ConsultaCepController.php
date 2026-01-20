<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ConsultaCepServiceInterface;
use Illuminate\Http\JsonResponse;
use Throwable;

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
     * @throws Throwable
     */
    public function show(string $cep): JsonResponse
    {
        $cep = $this->cepService->show($cep);

        return response()->json(['data' => $cep]);
    }

    /**
     * Lista os ceps consultados
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $ceps = $this->cepService->listarCepsConsultados();

        return response()->json(['data' => $ceps]);
    }
}
