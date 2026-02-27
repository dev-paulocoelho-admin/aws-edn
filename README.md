# 🚀 AWS EDN - Aplicação Laravel com Consulta de CEP

Bem-vindo ao **AWS EDN**! Uma aplicação moderna construída com Laravel, desenvolvida como ambiente de estudos e validação de conceitos em uma infraestrutura AWS. O projeto integra autenticação de usuários, gerenciamento de perfis e consulta de informações de CEP através de uma API externa.

---

## 📋 Índice

- [Visão Geral](#visão-geral)
- [Características Principais](#características-principais)
- [Requisitos do Sistema](#requisitos-do-sistema)
- [Instalação e Configuração](#instalação-e-configuração)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Funcionalidades](#funcionalidades)
- [Arquitetura e Padrões](#arquitetura-e-padrões)
- [Variáveis de Ambiente](#variáveis-de-ambiente)
- [Desenvolvimento](#desenvolvimento)
- [Testes](#testes)
- [Deployment em AWS](#deployment-em-aws)
- [Contribuições](#contribuições)
- [Licença](#licença)

---

## 🎯 Visão Geral

O **AWS EDN** é uma aplicação Laravel 12 desenvolvida como projeto educacional para demonstrar:

- ✅ Implementação de autenticação e autorização de usuários
- ✅ Integração com APIs externas (Consulta de CEP)
- ✅ Arquitetura em camadas (Controllers, Services, Repositories)
- ✅ Padrão de injeção de dependências
- ✅ Banco de dados SQLite para prototipagem rápida
- ✅ Interface responsiva com Tailwind CSS e Alpine.js
- ✅ Deploy em infraestrutura AWS (EC2, VPC, Security Groups)
- ✅ Validação de dados e testes unitários

---

## ⚡ Características Principais

### 🔐 Autenticação e Segurança
- Sistema completo de autenticação com Laravel Breeze
- Login e registro de usuários
- Verificação de email
- Gerenciamento de senhas com hash BCRYPT (12 rounds)
- Tokens de acesso pessoal (Laravel Sanctum)

### 🏢 Dashboard
- Painel de controle personalizado após login
- Visualização de estatísticas por período
- Filtros dinâmicos de data

### 📍 Consulta de CEP
- Busca de informações de CEP através de API externa
- Histórico de consultas realizadas
- Validação de CEP em tempo real
- Interface intuitiva para pesquisa

### 🎨 Interface Moderna
- Frontend responsivo com **Tailwind CSS**
- Componentes interativos com **Alpine.js**
- Build otimizado com **Vite**
- Design mobile-first

---

## 💻 Requisitos do Sistema

### Local/Desenvolvimento
- **PHP** 8.5 ou superior
- **Composer** para gerenciamento de dependências PHP
- **Node.js** 18+ e **npm** para dependências frontend
- **Git** para controle de versão
- **SQLite** ou outro banco de dados relacional

### Produção (AWS)
- **EC2** (t2.micro ou superior)
- **Ubuntu 22.04 LTS** ou similar
- **VPC** com grupos de segurança configurados
- **PHP 8.5**, **Apache/Nginx**, **Composer**, **Git**

---

## 🛠️ Instalação e Configuração

### 1️⃣ Clone o Repositório

```bash
git clone https://github.com/dev-paulocoelho-admin/aws-edn.git
cd aws-edn
```

### 2️⃣ Instalação Rápida (Recomendado)

Execute o script de setup que configura tudo automaticamente:

```bash
composer run setup
```

Este comando irá:
- ✅ Instalar dependências PHP (Composer)
- ✅ Copiar arquivo `.env.example` para `.env`
- ✅ Gerar a chave da aplicação
- ✅ Executar migrações do banco de dados
- ✅ Instalar dependências Node.js
- ✅ Compilar assets do frontend

### 2️⃣ Instalação Passo a Passo

Se preferir maior controle:

```bash
# Instalar dependências PHP
composer install

# Copiar arquivo de environment
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate

# Criar banco de dados e executar migrações
php artisan migrate

# Instalar dependências JavaScript
npm install

# Compilar assets para produção
npm run build
```

### 3️⃣ Configuração de Variáveis de Ambiente

Edite o arquivo `.env` com seus dados:

```env
APP_NAME="AWS EDN"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

MAIL_MAILER=log
```

---

## 📁 Estrutura do Projeto

```
aws-edn/
├── app/
│   ├── Enum/                          # Enumerações (DashboardPeriodoEnum)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/                   # Controladores de API
│   │   │   ├── Auth/                  # Controladores de autenticação
│   │   │   ├── Dashboard/             # Dashboard
│   │   │   ├── Index/                 # Página inicial
│   │   │   ├── Web/                   # Controladores web (CEP)
│   │   │   └── ProfileController.php  # Gerenciamento de perfil
│   │   └── Requests/                  # Form Requests (validação)
│   ├── Models/
│   │   ├── ConsultaCep.php            # Model de consultas de CEP
│   │   └── User.php                   # Model de usuário
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   ├── ConsultaCepServiceProvider.php
│   │   └── DashboardServiceProvider.php
│   ├── Repositories/                  # Camada de repositório (persistência)
│   │   ├── ConsultaCepRepository.php
│   │   ├── ConsultaCepRepositoryInterface.php
│   │   ├── DashboardRepository.php
│   │   └── DashboardRepositoryInterface.php
│   ├── Services/                      # Camada de serviço (lógica de negócio)
│   │   ├── ConsultaCepService.php
│   │   ├── ConsultaCepServiceInterface.php
│   │   ├── DashboardService.php
│   │   └── DashboardServiceInterface.php
│   └── View/Components/               # Componentes Laravel Blade
├── bootstrap/                         # Arquivos de bootstrap da aplicação
├── config/                            # Arquivos de configuração
├── database/
│   ├── factories/                     # Model factories para testes
│   ├── migrations/                    # Migrations de banco de dados
│   └── seeders/                       # Seeders para popular dados
├── public/                            # Raiz pública (servida pelo servidor web)
│   └── build/                         # Assets compilados
├── resources/
│   ├── css/                           # Arquivos CSS (Tailwind)
│   ├── js/                            # Arquivos JavaScript (Alpine.js)
│   └── views/                         # Templates Blade
│       ├── auth/                      # Autenticação
│       ├── cep/                       # Consulta de CEP
│       ├── dashboard/                 # Dashboard
│       ├── home/                      # Página inicial
│       ├── profile/                   # Perfil do usuário
│       └── layouts/                   # Layouts principais
├── routes/
│   ├── api.php                        # Rotas de API
│   ├── auth.php                       # Rotas de autenticação
│   ├── console.php                    # Comandos de console
│   └── web.php                        # Rotas web
├── storage/                           # Armazenamento de aplicação
├── tests/                             # Testes automatizados
├── vendor/                            # Dependências PHP (Composer)
├── .env.example                       # Template de variáveis de ambiente
├── composer.json                      # Dependências PHP
├── composer.lock                      # Lock file de dependências
├── package.json                       # Dependências Node.js
├── phpunit.xml                        # Configuração do PHPUnit
├── vite.config.js                     # Configuração do Vite
├── tailwind.config.js                 # Configuração do Tailwind CSS
└── README.md                          # Este arquivo
```

---

## 🎨 Funcionalidades

### 🏠 Página Inicial
- Visualização pública da aplicação
- Link para login/registro
- Informações sobre o projeto

### 🔐 Autenticação
- **Registro**: Formulário com validação de email e senha
- **Login**: Autenticação com email e senha
- **Recuperação de Senha**: Sistema de reset seguro
- **Verificação de Email**: Confirmação de email após registro

### 📊 Dashboard
- Painel personalizado após autenticação
- Estatísticas de consultas por período
- Filtros dinâmicos (Diário, Semanal, Mensal)
- Visualização de dados agregados

### 🔍 Consulta de CEP
- **Busca**: Campo de input para consultar CEP
- **Resultado**: Exibição de logradouro, bairro, cidade e estado
- **Histórico**: Lista de consultas realizadas
- **Validação**: Verificação em tempo real de CEP válido
- **Tratamento de Erros**: Mensagens amigáveis para CEPs não encontrados

### 👤 Perfil do Usuário
- Edição de dados pessoais
- Atualização de email
- Alteração de senha
- Deletar conta

---

## 🏗️ Arquitetura e Padrões

### Padrão Arquitetural: MVC + Repositories + Services

A aplicação segue uma arquitetura em camadas para melhor manutenibilidade:

```
┌─────────────────────────────────────────┐
│      Controllers (HTTP)                 │
├─────────────────────────────────────────┤
│      Services (Lógica de Negócio)       │
├─────────────────────────────────────────┤
│      Repositories (Persistência)        │
├─────────────────────────────────────────┤
│      Models (Eloquent ORM)              │
├─────────────────────────────────────────┤
│      Database                           │
└─────────────────────────────────────────┘
```

### Princípios Aplicados

- **SOLID**: Separação de responsabilidades
- **Dependency Injection**: Injeção de dependências via constructores
- **Interface Segregation**: Interfaces específicas por contrato
- **DRY**: Don't Repeat Yourself

### Exemplo: Consulta de CEP

```php
// 1. Controller recebe a requisição
ConsultaCepController->consultarViaTela()

// 2. Service executa a lógica de negócio
ConsultaCepService->consultar($cep)

// 3. Repository persiste/recupera dados
ConsultaCepRepository->create($dados)

// 4. Model interage com o banco
ConsultaCep->save()
```

---

## 🌍 Variáveis de Ambiente

Crie um arquivo `.env` na raiz do projeto com as seguintes variáveis:

```env
# Aplicação
APP_NAME="AWS EDN"
APP_ENV=local
APP_KEY=base64:... (gera-se com php artisan key:generate)
APP_DEBUG=true
APP_URL=http://localhost

# Banco de Dados
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# Email
MAIL_MAILER=log

# Cache
CACHE_STORE=database

# Session
SESSION_DRIVER=database

# Fila
QUEUE_CONNECTION=database

# APIs Externas (se necessário)
CEP_API_URL=https://api.exemplo.com
```

---

## 👨‍💻 Desenvolvimento

### Servidor de Desenvolvimento

Inicie todos os processos necessários:

```bash
composer run dev
```

Este comando executa em paralelo:
- Laravel dev server na porta `8000`
- Queue listener para filas
- Pail para logs em tempo real
- Vite dev server para assets

### Servidor Separado (Alternativa)

```bash
# Terminal 1: Servidor PHP
php artisan serve

# Terminal 2: Compilação de assets
npm run dev
```

### Comandos Úteis

```bash
# Criar nova migration
php artisan make:migration create_tabela_name --create=tabelas

# Executar migrações
php artisan migrate

# Desfazer última migration
php artisan migrate:rollback

# Resetar banco de dados
php artisan migrate:fresh --seed

# Criar novo controller
php artisan make:controller NomeController

# Limpar cache
php artisan cache:clear
php artisan config:clear

# Gerar chave de aplicação
php artisan key:generate
```

---

## 🧪 Testes

### Executar Testes

```bash
# Executar todos os testes
composer test

# Executar com coverage
php vendor/bin/phpunit --coverage-html

# Executar arquivo específico
php vendor/bin/phpunit tests/Feature/AuthTest.php

# Executar método específico
php vendor/bin/phpunit --filter testLogin
```

### Estrutura de Testes

```
tests/
├── Feature/          # Testes de funcionalidade (HTTP)
├── Unit/             # Testes unitários
└── TestCase.php      # Classe base para testes
```

### Exemplo de Teste

```php
namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ConsultaCepTest extends TestCase
{
    public function test_usuario_autenticado_pode_consultar_cep()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->post('/api/cep', ['cep' => '01310100']);
        
        $response->assertStatus(200)
            ->assertJsonStructure(['logradouro', 'bairro', 'cidade']);
    }
}
```

---

## ☁️ Deployment em AWS

### Infraestrutura Configurada

- **EC2**: Instância com PHP 8.5 e Apache
- **VPC**: Isolamento de rede
- **Security Groups**: Portas 80 (HTTP) e 443 (HTTPS) abertas
- **Database**: SQLite ou RDS (conforme necessário)

### Passos para Deploy

#### 1. Conectar à Instância EC2

```bash
ssh -i sua-chave.pem ubuntu@seu-ip-ec2
```

#### 2. Configurar Servidor

```bash
# Atualizar sistema
sudo apt update && sudo apt upgrade -y

# Instalar dependências
sudo apt install -y php8.5 php8.5-{cli,fpm,common,mysql,zip,gd,mbstring,curl,xml} \
                    apache2 libapache2-mod-php8.5 git composer

# Habilitar módulos do Apache
sudo a2enmod rewrite
sudo a2enmod php8.5
```

#### 3. Clonar e Configurar Aplicação

```bash
# Clonar repositório
cd /var/www
sudo git clone https://github.com/seu-usuario/aws-edn.git

# Configurar permissões
sudo chown -R www-data:www-data aws-edn
sudo chmod -R 775 aws-edn/storage aws-edn/bootstrap/cache

# Instalar dependências
cd aws-edn
composer install --no-dev --optimize-autoloader

# Configurar ambiente
sudo cp .env.example .env
sudo nano .env  # Editar configurações

# Gerar chave e migrar banco
php artisan key:generate
php artisan migrate --force
```

#### 4. Configurar Apache

Crie `/etc/apache2/sites-available/aws-edn.conf`:

```apache
<VirtualHost *:80>
    ServerName seu-dominio.com
    DocumentRoot /var/www/aws-edn/public

    <Directory /var/www/aws-edn/public>
        AllowOverride All
        Require all granted
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^ index.php [QSA,L]
        </IfModule>
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/aws-edn-error.log
    CustomLog ${APACHE_LOG_DIR}/aws-edn-access.log combined
</VirtualHost>
```

Abilitar site:

```bash
sudo a2ensite aws-edn
sudo systemctl reload apache2
```

#### 5. SSL com Let's Encrypt (Recomendado)

```bash
sudo apt install certbot python3-certbot-apache -y
sudo certbot --apache -d seu-dominio.com
```

---

## 🚀 Próximos Passos

Ideias para evolução do projeto:

### 🎯 Curto Prazo
- [ ] Adicionar testes de integração
- [ ] Implementar validação mais robusta de CEP
- [ ] Cache de consultas de CEP
- [ ] Paginação no histórico de consultas

### 📈 Médio Prazo
- [ ] CI/CD com GitHub Actions
- [ ] Docker para containerização
- [ ] API REST com documentação Swagger
- [ ] Autenticação OAuth2
- [ ] Notificações por email

### 🏢 Longo Prazo
- [ ] Escalabilidade com load balancer
- [ ] Microserviços para diferentes módulos
- [ ] Análise de dados e relatórios avançados
- [ ] Integração com outros serviços AWS (S3, Lambda, etc)
- [ ] Mobile app (React Native/Flutter)

---

## 📚 Stack Tecnológico

### Backend
- **Laravel 12**: Framework PHP moderno
- **PHP 8.5**: Linguagem
- **SQLite**: Banco de dados
- **Eloquent ORM**: Abstração de dados
- **Blade**: Engine de template

### Frontend
- **Tailwind CSS 3**: Framework CSS utilitário
- **Alpine.js**: JavaScript reativo leve
- **Vite**: Build tool moderno
- **PostCSS**: Processamento CSS

### Infraestrutura
- **AWS EC2**: Computação
- **AWS VPC**: Rede virtual
- **Apache/Nginx**: Servidor web
- **Git**: Controle de versão

### Qualidade
- **PHPUnit**: Framework de testes
- **Laravel Pint**: Code style (PSR-12)
- **PHP CodeSniffer**: Análise estática

---

## 🤝 Contribuições

Contribuições são bem-vindas! Por favor:

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

### Diretrizes
- Siga o padrão PSR-12 de código
- Adicione testes para novas funcionalidades
- Atualize a documentação conforme necessário

---

## 📄 Licença

Este projeto está licenciado sob a **Licença MIT** - veja o arquivo [LICENSE](LICENSE) para detalhes.

---

## 📞 Suporte

Para dúvidas ou problemas:

- 📧 **Email**: seu-email@exemplo.com
- 🐛 **Issues**: [GitHub Issues](https://github.com/seu-usuario/aws-edn/issues)
- 💬 **Discussões**: [GitHub Discussions](https://github.com/seu-usuario/aws-edn/discussions)

---

## 📝 Changelog

### Versão 1.0.0 (Atual)
- ✅ Autenticação e registro de usuários
- ✅ Consulta de CEP via API
- ✅ Dashboard com estatísticas
- ✅ Gerenciamento de perfil
- ✅ Interface responsiva com Tailwind CSS
- ✅ Deploy em AWS

---

## 🎓 Aprendizados

Este projeto foi desenvolvido como estudo de:

- Arquitetura em camadas (MVC + Services + Repositories)
- Padrões de design (Dependency Injection, Repository Pattern)
- Segurança em aplicações web (CSRF, Password Hashing, SQL Injection prevention)
- Deploy em infraestrutura cloud (AWS)
- Boas práticas de desenvolvimento (Testing, Code Style, Version Control)
- Desenvolvimento full-stack (Backend + Frontend)

---

**Desenvolvido com ❤️ como projeto educacional**

Última atualização: *2026*

