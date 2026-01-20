<?php

namespace App\Services;

interface ConsultaCepServiceInterface
{
    public function consultar(string $cep): array;
}
