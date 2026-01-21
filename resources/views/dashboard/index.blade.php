<x-app-layout>

    {{-- TITLE --}}
    <x-slot name="title">
        {{ __('Dashboard CEPs') }}
    </x-slot>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="text-center">
            <h2 class="font-semibold text-2xl text-orange-500 leading-tight">
                {{ __('Dashboard de Consultas de CEP') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Visão geral das consultas realizadas no sistema
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- FILTRO DE PERÍODO --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form method="GET" class="flex flex-col sm:flex-row gap-4 items-center">
                    <label class="text-sm text-gray-600 dark:text-gray-300">
                        Período:
                    </label>

                    <select name="periodo"
                            onchange="this.form.submit()"
                            class="px-4 py-2 min-w-[220px] whitespace-nowrap
               border border-gray-300 rounded-md text-sm
               focus:ring-orange-500 focus:border-orange-500
               dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                        <option value="7" {{ $periodo == 7 ? 'selected' : '' }}>
                            Últimos 7 dias
                        </option>
                        <option value="30" {{ $periodo == 30 ? 'selected' : '' }}>
                            Últimos 30 dias
                        </option>
                    </select>
                </form>
            </div>

            {{-- GRAFICOS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- SUCESSO X ERRO --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">
                        Consultas com Sucesso x Erro
                    </h3>
                    <canvas id="graficoStatus"></canvas>
                </div>

                {{-- CONSULTAS POR DIA --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">
                        Consultas Totais por Dia
                    </h3>
                    <canvas id="graficoPorDia"></canvas>
                </div>

            </div>

        </div>
    </div>

    {{-- FOOTER --}}
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

    {{-- CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        /* ===============================
         * GRAFICO 1 - SUCESSO X ERRO
         * =============================== */
        new Chart(document.getElementById('graficoStatus'), {
            type: 'bar',
            data: {
                labels: ['Sucesso', 'Erro'],
                datasets: [{
                    data: [
                        {{ $consultasSucesso }},
                        {{ $consultasErro }}
                    ],
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.7)',
                        'rgba(239, 68, 68, 0.7)'
                    ],
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {legend: {display: false}},
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {precision: 0}
                    }
                }
            }
        });

        /* ===============================
         * GRAFICO 2 - CONSULTAS POR DIA
         * =============================== */
        new Chart(document.getElementById('graficoPorDia'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($consultasPorDia)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($consultasPorDia)) !!},
                    backgroundColor: 'rgba(249, 115, 22, 0.7)',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {legend: {display: false}},
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {precision: 0}
                    }
                }
            }
        });
    </script>

</x-app-layout>
