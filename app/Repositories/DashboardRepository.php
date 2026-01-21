<?php

namespace App\Repositories;

use App\Models\ConsultaCep;
use Carbon\Carbon;

class DashboardRepository implements DashboardRepositoryInterface
{
    protected ConsultaCep $model;

    public function __construct(ConsultaCep $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    public function contagemSucesso(Carbon $inicio): int
    {
        return $this->model
            ->newQuery()
            ->where('created_at', '>=', $inicio)
            ->whereRaw("JSON_EXTRACT(payload, '$.erro') IS NULL")
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function contagemErro(Carbon $inicio): int
    {
        return $this->model
            ->newQuery()
            ->where('created_at', '>=', $inicio)
            ->whereRaw("JSON_EXTRACT(payload, '$.erro') = true")
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function consultasPorDia(Carbon $inicio): array
    {
        return $this->model
            ->newQuery()
            ->selectRaw('
                DATE(created_at) as dia,
                COUNT(*) as total
            ')
            ->where('created_at', '>=', $inicio)
            ->groupBy('dia')
            ->orderBy('dia')
            ->pluck('total', 'dia')
            ->toArray();
    }
}
