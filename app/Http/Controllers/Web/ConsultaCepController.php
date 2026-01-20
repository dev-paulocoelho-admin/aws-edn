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
    public function consultarViaTela(Request $request)
    {
        $request->validate([
            'cep' => ['required'],
        ]);

        $this->cepService->show($request->cep);

        return redirect()->route('cep.index');
    }
}
