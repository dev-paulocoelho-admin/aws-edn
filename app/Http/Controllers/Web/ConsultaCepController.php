<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConsultaCepRequest;
use App\Services\ConsultaCepServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
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
     * Exibe a listagem de CEPs consultados
     *
     * @return View
     */
    public function obterListagemCep(): View
    {
        $ceps = $this->cepService->listarCepsConsultados();

        return view('cep.index', compact('ceps'));
    }

    /**
     * Consulta o CEP informado na tela
     *
     * @param ConsultaCepRequest $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function consultarViaTela(ConsultaCepRequest $request): RedirectResponse
    {
        $resultado = $this->cepService->show($request->validated()['cep']);

        if (isset($resultado['message'])) {
            return redirect()
                ->route('cep.index')
                ->with('error', $resultado['message']);
        }

        return redirect()
            ->route('cep.index')
            ->with('success', 'CEP consultado e registrado com sucesso.');
    }
}
