<?php

namespace App\Repositories;

use App\Models\ConsultaCep;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConsultaCepRepository implements ConsultaCepRepositoryInterface
{
    protected ConsultaCep $model;

    public function __construct(ConsultaCep $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    public function index(): Paginator
    {
        return $this->model
            ->newQuery()
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(7);
    }

    /**
     * @inheritDoc
     */
    public function store(string $cep, array $payload = []): void
    {
        DB::transaction(function () use ($cep, $payload) {
            $this->model
                ->newQuery()
                ->create([
                    'cep' => $cep,
                    'payload' => $payload,
                    'user_id' => Auth::id(),
                ]);
        });
    }
}
