<?php

namespace App\Services;

use App\Repositories\DashboardRepository;
use Carbon\Carbon;

class DashboardService implements DashboardServiceInterface
{
    protected DashboardRepository $repository;

    public function __construct(DashboardRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function obterDashboard(int $dias): array
    {
        $inicio = Carbon::now()
            ->subDays($dias)
            ->startOfDay();

        return [
            'sucesso' => $this->repository->contagemSucesso($inicio),
            'erro' => $this->repository->contagemErro($inicio),
            'porDia' => $this->repository->consultasPorDia($inicio),
        ];
    }
}
