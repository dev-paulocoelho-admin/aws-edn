<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ConsultaCepServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ConsultaCepController extends Controller
{
    protected ConsultaCepServiceInterface $cepService;

    public function __construct(ConsultaCepServiceInterface $cepService)
    {
        $this->cepService = $cepService;
    }

    /**
     * Exibe a listagem de CEPs consultados
     */
    public function obterListagemCep(): View
    {
        $ceps = $this->cepService->listarCepsConsultados();

        return view('cep.index', compact('ceps'));
    }

    /**
     * Consulta o CEP informado na tela
     * @param Request $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function consultarViaTela(Request $request): RedirectResponse
    {
        $request->validate([
            'cep' => ['required'],
        ]);

        $resultado = $this->cepService->show($request->cep);

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
