<?php

namespace App\Http\Controllers\Dashboard;

use App\Enum\DashboardPeriodoEnum;
use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Exibe o dashboard com as consultas realizadas
     * @param Request $request
     * @return Factory|\Illuminate\Contracts\View\View|View
     */
    public function index(Request $request)
    {
        $dias = (int)$request->get('periodo', DashboardPeriodoEnum::SETE_DIAS->value);

        if (!in_array($dias, [DashboardPeriodoEnum::SETE_DIAS->value, DashboardPeriodoEnum::TRINTA_DIAS->value])) {
            $dias = DashboardPeriodoEnum::SETE_DIAS->value;
        }

        $dados = $this->dashboardService->obterDashboard($dias);

        return view('dashboard.index', [
            'consultasSucesso' => $dados['sucesso'],
            'consultasErro' => $dados['erro'],
            'consultasPorDia' => $dados['porDia'],
            'periodo' => $dias,
        ]);
    }
}
