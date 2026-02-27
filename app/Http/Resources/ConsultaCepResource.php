<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultaCepResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cep' => $this->cep,
            'logradouro' => $this->payload['logradouro'] ?? null,
            'bairro' => $this->payload['bairro'] ?? null,
            'localidade' => $this->payload['localidade'] ?? null,
            'uf' => $this->payload['uf'] ?? null,
            'complemento' => $this->payload['complemento'] ?? null,
            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
            ],
            'payload' => $this->payload,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
