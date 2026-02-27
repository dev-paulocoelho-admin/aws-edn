<?php

namespace Database\Factories;

use App\Models\ConsultaCep;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ConsultaCep>
 */
class ConsultaCepFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<ConsultaCep>
     */
    protected $model = ConsultaCep::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ceps = [
            '01310100', '20040020', '30140071', '50010000', '70040902',
            '80010000', '88015100', '04543010', '13565000', '22250040',
        ];

        return [
            'cep' => $this->faker->randomElement($ceps),
            'payload' => [
                'cep' => $this->faker->numerify('###########'),
                'logradouro' => $this->faker->streetName(),
                'complemento' => '',
                'bairro' => $this->faker->word(),
                'localidade' => $this->faker->city(),
                'uf' => $this->faker->stateAbbr(),
                'ibge' => $this->faker->numerify('######'),
                'gia' => '',
                'ddd' => $this->faker->numerify('##'),
                'siafi' => $this->faker->numerify('#####'),
            ],
            'user_id' => User::factory(),
        ];
    }

    /**
     * Estado para consulta com erro.
     */
    public function withError(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'payload' => [
                    'erro' => true,
                    'mensagem' => 'CEP não encontrado',
                ],
            ];
        });
    }

    /**
     * Estado para um CEP específico.
     */
    public function withCep(string $cep): self
    {
        return $this->state(function (array $attributes) use ($cep) {
            return [
                'cep' => $cep,
            ];
        });
    }

    /**
     * Estado para um usuário específico.
     */
    public function forUser(User $user): self
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }
}

