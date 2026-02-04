<x-app-layout>

    <x-slot name="title">
        {{ __('CEPs Consultados') }}
    </x-slot>

    <x-slot name="header">
        <div class="text-center">
            <h2 class="font-semibold text-2xl text-orange-500 leading-tight">
                {{ __('CEPs Consultados') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Histórico de consultas realizadas no sistema
            </p>
            {{-- FLASH MESSAGES --}}
            @if (session('success'))
                <div
                    x-data="{ show: true }"
                    x-init="setTimeout(() => show = false, 5000)"
                    x-show="show"
                    x-transition
                    class="mb-4 rounded-md bg-green-100 border border-green-300 px-4 py-3 text-sm text-green-800"
                >
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div
                    x-data="{ show: true }"
                    x-init="setTimeout(() => show = false, 5000)"
                    x-show="show"
                    x-transition
                    class="mb-4 rounded-md bg-red-100 border border-red-300 px-4 py-3 text-sm text-red-800"
                >
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- CARD --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                {{-- FORM DE CONSULTA --}}
                <form method="GET"
                      action="{{ url('/cep/consultar') }}"
                      class="flex flex-col sm:flex-row gap-4 items-center mb-6">

                    <input
                        type="text"
                        name="cep"
                        value="{{ trim(request('cep')) }}"
                        placeholder="Digite o CEP (ex: 01010000)"
                        maxlength="9"
                        class="w-full sm:flex-1 px-4 py-2 border border-gray-300 rounded-md shadow-sm
                               focus:outline-none focus:ring-1 focus:ring-orange-500
                               dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 text-sm"
                    >

                    <button
                        type="submit"
                        class="inline-flex items-center px-6 py-2 bg-orange-500 border border-transparent
                               rounded-md font-semibold text-sm text-gray-900
                               hover:bg-orange-600 focus:outline-none focus:ring-2
                               focus:ring-orange-400 focus:ring-offset-2
                               dark:focus:ring-offset-gray-800 transition"
                    >
                        Consultar CEP
                    </button>
                </form>

                {{-- TABELA --}}
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-3">ID</th>
                            <th class="px-6 py-3">CEP</th>
                            <th class="px-6 py-3">Logradouro</th>
                            <th class="px-6 py-3">Bairro</th>
                            <th class="px-6 py-3">Cidade / UF</th>
                            <th class="px-6 py-3">Usuário</th>
                            <th class="px-6 py-3">Data</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($ceps as $consulta)
                            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ $consulta->id }}
                                </td>
                                <td class="px-6 py-4 font-medium">
                                    @if(!empty($consulta->payload['erro']))
                                        <span class="text-red-600 font-semibold">
                                            {{ $consulta->cep }}
                                        </span>
                                        <div class="text-xs text-gray-400">
                                            erro na consulta
                                        </div>
                                    @else
                                        <span class="text-gray-900 dark:text-white">
                                            {{ $consulta->cep }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    {{ $consulta->payload['logradouro'] ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $consulta->payload['bairro'] ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $consulta->payload['localidade'] ?? '-' }}
                                    /
                                    {{ $consulta->payload['uf'] ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $consulta->user?->name ?? 'Anônimo' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $consulta->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">
                                    Nenhuma consulta registrada.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINAÇÃO (IDÊNTICA À DE INSTRUTORES) --}}
                <div class="mt-6">
                    {{ $ceps->links() }}
                </div>

            </div>
        </div>
    </div>

    <footer class="text-center mt-10 text-sm text-gray-400">
        <p>AWS EDN • Ambiente provisionado e aplicação no ar com sucesso</p>
        <p class="mt-1">
            Repositório no GitHub:
            <a href="https://github.com/dev-paulocoelho-admin/aws-edn"
               target="_blank"
               class="text-orange-500 font-semibold hover:underline">
                dev-paulocoelho-admin/aws-edn
            </a>
        </p>
    </footer>

</x-app-layout>
