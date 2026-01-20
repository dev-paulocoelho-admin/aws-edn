<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\Paginator;
use Throwable;

interface ConsultaCepServiceInterface
{
    /**
     * Lista os CEPs consultados.
     * @return Paginator
     */
    public function listarCepsConsultados(): Paginator;

    /**
     * Consulta o CEP no webservice ViaCep.com.br.
     * @param string $cep
     * @return array
     * @throws Throwable
     */
    public function show(string $cep): array;
}
