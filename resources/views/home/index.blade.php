<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>AWS EDN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        :root {
            --primary: #ff9900;
            --dark: #0f172a;
            --gray: #334155;
            --light: #f8fafc;
            --card: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #020617, #0f172a);
            color: var(--light);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        header {
            text-align: center;
            margin-bottom: 50px;
        }

        header h1 {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 10px;
        }

        header p {
            font-size: 1.2rem;
            color: #cbd5f5;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
        }

        .card {
            background: var(--card);
            color: var(--gray);
            padding: 24px;
            border-radius: 14px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
            transition: transform .3s ease, box-shadow .3s ease;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.35);
        }

        .card h3 {
            color: var(--dark);
            margin-bottom: 12px;
            font-size: 1.2rem;
        }

        .card p {
            line-height: 1.6;
            font-size: 0.95rem;
        }

        footer {
            text-align: center;
            margin-top: 60px;
            color: #94a3b8;
            font-size: 0.9rem;
        }

        .card-wide {
            grid-column: span 3;
        }

        @media (max-width: 768px) {
            .card-wide {
                grid-column: span 1;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <header>
        <h1>AWS EDN</h1>
        <p>Bem-vindo ao aplicativo AWS EDN 🚀</p>
    </header>

    <section class="cards">

        <div class="card">
            <h3>🎉 Implantação concluída com sucesso</h3>
            <p>
                Parabéns por concluir a implantação da sua primeira aplicação na AWS.
                Este ambiente foi configurado seguindo boas práticas de infraestrutura,
                segurança e organização.
            </p>
        </div>

        <div class="card">
            <h3>☁️ Infraestrutura AWS</h3>
            <p>
                O ambiente foi provisionado utilizando serviços essenciais da AWS,
                incluindo EC2, VPC e Grupos de Segurança, garantindo isolamento,
                controle de acesso e escalabilidade.
            </p>
        </div>

        <div class="card">
            <h3>⚙️ Stack do Servidor</h3>
            <p>
                O servidor foi preparado com PHP, Git e Composer, criando uma base
                sólida e moderna para aplicações PHP e Laravel em produção.
            </p>
        </div>

        <div class="card">
            <h3>📦 Aplicação</h3>
            <p>
                A aplicação foi clonada diretamente do GitHub, configurada com
                variáveis de ambiente, banco de dados e ajustes do Laravel,
                garantindo funcionamento correto e seguro.
            </p>
        </div>

        <div class="card">
            <h3>🌐 Servidor Web</h3>
            <p>
                O Apache foi configurado para servir a aplicação corretamente,
                integrando o Laravel ao ambiente web de forma eficiente e estável.
            </p>
        </div>

        <div class="card">
            <h3>🚀 Próximos Passos</h3>
            <p>
                Agora o ambiente está pronto para evoluir: novos módulos,
                integrações, escalabilidade, CI/CD e tudo o que uma aplicação
                profissional exige.
            </p>
        </div>

        @if (Route::has('login'))
            <div class="card card-wide">
                <h3>🔐 Acesso ao Sistema</h3>

                @auth
                    <p>
                        Você já está autenticado no sistema.
                    </p>

                    <div style="margin-top:16px; display:flex; gap:12px; flex-wrap:wrap;">
                        <a href="{{ url('/dashboard') }}"
                           style="
                               padding:10px 16px;
                               background:#ff9900;
                               color:#0f172a;
                               border-radius:8px;
                               text-decoration:none;
                               font-size:0.9rem;
                               font-weight:600;
                           ">
                            Acessar Dashboard
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    style="
                                        padding:10px 16px;
                                        background:#dc2626;
                                        color:#fff;
                                        border:none;
                                        border-radius:8px;
                                        font-size:0.9rem;
                                        cursor:pointer;
                                    ">
                                Sair
                            </button>
                        </form>
                    </div>
                @else
                    <p>
                        Utilize sua conta para acessar o sistema ou crie um novo cadastro.
                    </p>

                    <div style="margin-top:16px; display:flex; gap:12px; flex-wrap:wrap;">
                        <a href="{{ route('login') }}"
                           style="
                               padding:10px 16px;
                               background:#0f172a;
                               color:#fff;
                               border-radius:8px;
                               text-decoration:none;
                               font-size:0.9rem;
                           ">
                            Login
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               style="
                                   padding:10px 16px;
                                   background:#ff9900;
                                   color:#0f172a;
                                   border-radius:8px;
                                   text-decoration:none;
                                   font-size:0.9rem;
                                   font-weight:600;
                               ">
                                Registrar
                            </a>
                        @endif
                    </div>
                @endauth
            </div>
        @endif

    </section>

    <footer style="text-align:center; margin-top:60px; color:#ffffff; font-size:0.9rem;">
        <p>AWS EDN • Ambiente provisionado e aplicação no ar com sucesso</p>

        <p style="margin-top:6px;">
            Repositório no GitHub:
            <a href="https://github.com/dev-paulocoelho-admin/aws-edn"
               target="_blank"
               style="color:#ff9900; text-decoration:none; font-weight:600;">
                dev-paulocoelho-admin/aws-edn
            </a>
        </p>
    </footer>

</div>

</body>
</html>
