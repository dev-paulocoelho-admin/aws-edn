<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConsultaCepRequest;
use App\Http\Resources\ConsultaCepResource;
use App\Services\ConsultaCepServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ConsultaCepController extends Controller
{
    /**
     * Construtor com injeção de dependência.
     */
    public function __construct(
        protected ConsultaCepServiceInterface $cepService
    ) {
    }

    /**
     * Lista o histórico de CEPs consultados
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $ceps = $this->cepService->listarCepsConsultados();

        return ConsultaCepResource::collection($ceps);
    }

    /**
     * Consulta um CEP específico
     *
     * @param ConsultaCepRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(ConsultaCepRequest $request): JsonResponse
    {
        try {
            $resultado = $this->cepService->show($request->validated()['cep']);

            if (isset($resultado['message'])) {
                return response()->json(
                    [
                        'message' => $resultado['message'],
                        'success' => false,
                    ],
                    Response::HTTP_NOT_FOUND
                );
            }

            return response()->json(
                [
                    'message' => 'CEP consultado com sucesso',
                    'data' => $resultado,
                    'success' => true,
                ],
                Response::HTTP_CREATED
            );
        } catch (Throwable $e) {
            return response()->json(
                [
                    'message' => 'Erro ao consultar CEP',
                    'error' => $e->getMessage(),
                    'success' => false,
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Busca um CEP específico
     *
     * @param ConsultaCepRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function show(ConsultaCepRequest $request): JsonResponse
    {
        $resultado = $this->cepService->show($request->validated()['cep']);

        if (isset($resultado['message'])) {
            return response()->json(
                [
                    'message' => $resultado['message'],
                    'success' => false,
                ],
                404
            );
        }

        return response()->json(
            [
                'data' => $resultado,
                'success' => true,
            ],
            Response::HTTP_OK
        );
    }
}
