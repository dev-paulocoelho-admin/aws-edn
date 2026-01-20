<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\Paginator;
use Throwable;

interface ConsultaCepRepositoryInterface
{
    /**
     * Lista os CEPs consultados.
     * @return Paginator
     */
    public function index(): Paginator;

    /**
     * Registra a consulta de CEP no banco de dados.
     *
     * @param string $cep
     * @param array $payload
     * @throws Throwable
     */
    public function store(string $cep, array $payload = []): void;
}
