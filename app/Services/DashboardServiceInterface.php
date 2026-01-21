<?php

namespace App\Services;

interface DashboardServiceInterface
{
    /**
     * Retorna o dashboard.
     * @param int $dias
     * @return array
     */
    public function obterDashboard(int $dias): array;
}
