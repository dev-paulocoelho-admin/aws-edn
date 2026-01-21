<?php

namespace App\Repositories;

use Carbon\Carbon;

interface DashboardRepositoryInterface
{
    /**
     * Retorna a quantidade de consultas realizadas no dia.
     * @param Carbon $inicio
     * @return int
     */
    public function contagemSucesso(Carbon $inicio): int;

    /**
     * Retorna a quantidade de consultas com erro no dia.
     * @param Carbon $inicio
     * @return int
     */
    public function contagemErro(Carbon $inicio): int;

    /**
     * Retorna as consultas realizadas por dia.
     * @param Carbon $inicio
     * @return array
     */
    public function consultasPorDia(Carbon $inicio): array;
}
