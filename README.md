# Fintech Gastos Challenge

Aplicação full stack para controle de gastos pessoais, com backend em Laravel 12, autenticação via Sanctum, API para categorias e despesas, e frontend em Vue 3 com Vite.

## Descrição breve do projeto

O sistema permite:

- cadastrar e autenticar usuários;
- criar, editar e excluir categorias;
- criar, editar e excluir despesas;
- visualizar um dashboard com o total gasto no mês atual, as 5 últimas despesas e o resumo por categoria.

## Pré-requisitos

Teste realizado com o seguinte ambiente:

- PHP 8.2 ou superior
- Node.js 20 ou superior
- NPM 10 ou superior
- PostgreSQL 15 ou superior
- Composer 2.x

Observação: o projeto também usa Laravel 12 e Vue 3.

## Instalação das dependências

### Backend

```bash
composer install
```

### Frontend

```bash
cd frontend
npm install
```

## Como configurar o `.env`

1. Copie o arquivo de exemplo:

```bash
copy .env.example .env
```

2. Gere a chave da aplicação:

```bash
php artisan key:generate
```

3. Ajuste as variáveis de banco para PostgreSQL no arquivo `.env`:

```env
APP_NAME="Fintech Gastos Challenge"
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=fintech_gastos
DB_USERNAME=postgres
DB_PASSWORD=sua_senha
```

4. Se precisar consumir a API do frontend em outro endereço, ajuste também a URL usada pelo Vite:

```env
VITE_API_URL=http://127.0.0.1:8000/api
```

## Como rodar migrations e seeders

Execute as migrations:

```bash
php artisan migrate
```

Execute os seeders:

```bash
php artisan db:seed
```

Se quiser popular o ambiente de desenvolvimento com os dados padrão do projeto, rode também o seeder de desenvolvimento:

```bash
php artisan db:seed --class=DevSeeder
```

## Como iniciar o servidor

### Backend

```bash
php artisan serve
```

### Frontend

Em outro terminal:

```bash
cd frontend
npm run dev
```

## Link do deploy público

- O deploy público está preparado para Render com um único serviço web Laravel servindo o frontend Vue no mesmo domínio.
- Blueprint do deploy: [`render.yaml`](render.yaml)
 - URL pública: https://fintech-gastos-challenge-wf6w.onrender.com/


## Credenciais do usuário seed para teste

Use o usuário criado pelo seeder de desenvolvimento:

- E-mail: `enzo@example.com`
- Senha: `senha1234`

## Observações úteis

- A API usa autenticação por token com Sanctum.
- O dashboard consome o endpoint autenticado `/api/auth/dashboard`.
- As datas de despesas são exibidas no formato `DD/MM/AAAA`.
- Para rodar tudo em sequência no projeto raiz, você também pode usar os scripts definidos no `composer.json`.

## Executando os testes

Os testes usam PHPUnit via o wrapper do Laravel (`php artisan test`) e o arquivo `phpunit.xml` está configurado para usar um banco SQLite em memória, portanto não é necessário configurar um banco externo para executar a suíte básica.

Comandos úteis:

```bash
# instalar dependências PHP (se ainda não):
composer install

# rodar toda a suíte via Artisan (recomendado):
php artisan test

# rodar via phpunit diretamente:
vendor/bin/phpunit

# rodar apenas um teste de feature:
php artisan test --filter AuthTest

# rodar um único método de teste:
php artisan test --filter test_register_and_login_and_me
```

Notas e solução de problemas:
- O `phpunit.xml` define `DB_CONNECTION=sqlite` e `DB_DATABASE=:memory:` — habilite a extensão `pdo_sqlite` do PHP se aparecer erro relacionado ao driver.
- Se preferir usar um banco real para testes, exporte as variáveis `DB_*` apropriadas antes de rodar os testes.
- Para ver saída mais detalhada: `php artisan test --verbose`.

Testes incluídos
---------------

O projeto contém testes de Feature que cobrem fluxos importantes. Atualmente os testes presentes são:

- `Tests/Feature/AuthTest.php`: cobre registro, login e o endpoint `me` (autenticação via Sanctum).
- `Tests/Feature/ExpenseValidationTest.php`: validações do `ExpenseRequest` (valores inválidos, datas, etc.).
- `Tests/Feature/ExpenseRulesTest.php`: regras de negócio/autorizações, por exemplo impedir ações com categorias de outro usuário.
- `Tests/Feature/DashboardTest.php`: valida o endpoint `/api/auth/dashboard` (total do mês, últimas despesas e resumo por categoria).

Você pode executar um teste específico usando o filtro do Artisan, por exemplo:

```bash
php artisan test --filter DashboardTest
```

Observação: os testes usam a trait `RefreshDatabase` e o banco em memória (`sqlite`) por padrão.

## Configuração de e-mail (SMTP)

Por padrão o projeto está configurado para `MAIL_MAILER=log` no arquivo `.env`, ou seja, os e-mails não são enviados externamente — eles são gravados nos logs em `storage/logs/laravel.log`. Isso facilita o desenvolvimento quando você não tem um servidor SMTP configurado.

Se você deseja que os e-mails sejam realmente enviados (por exemplo para receber o e-mail de redefinição de senha), ajuste as variáveis abaixo no seu `.env` para usar um provedor SMTP.

Exemplo — Mailtrap (desenvolvimento):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=SEU_USER_MAILTRAP
MAIL_PASSWORD=SEU_PASS_MAILTRAP
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME="Fintech Gastos"
```

Exemplo — Gmail (use App Password se tiver 2FA):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu.email@gmail.com
MAIL_PASSWORD=SUA_APP_PASSWORD
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu.email@gmail.com
MAIL_FROM_NAME="Fintech Gastos"
```

Exemplo — SendGrid (SMTP):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=SEU_SENDGRID_API_KEY
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@seudominio.com
MAIL_FROM_NAME="Fintech Gastos"
```

Após alterar o `.env`, limpe o cache de configuração:

```bash
php artisan config:clear
php artisan cache:clear
```

Como testar o envio

- Ver logs (modo padrão `log`):

```bash
# Windows (PowerShell)
Get-Content .\storage\logs\laravel.log -Tail 120

# macOS / Linux
tail -n 120 storage/logs/laravel.log
```

Integração Contínua (GitHub Actions)
----------------------------------

Este repositório inclui um workflow de CI baseado em GitHub Actions localizado em `.github/workflows/php-tests.yml`.

O que o workflow faz:

- Roda os testes PHP (`php artisan test`) em cada push e pull request nas branches `main`/`master`.
- Configura PHP (8.2) e as extensões necessárias (`pdo_sqlite`) para executar a suíte usando um banco SQLite em memória.

Como funciona localmente vs CI:

- Local: você pode executar `php artisan test` para reproduzir o que o CI roda.
- CI: o Actions fará checkout do código, instalará dependências via Composer, ajustará um `.env` temporário para usar SQLite em memória e executará os testes.

Se quiser expandir o workflow para também buildar o frontend (Vite) ou publicar artefatos, veja o arquivo do workflow e adicione passos para `cd frontend && npm ci` e `cd frontend && npm run build`.


- Enviar um e-mail de teste via Tinker (notificação custom já instalada):

```bash
php artisan tinker
>>> $user = App\Models\User::where('email','enzo@example.com')->first();
>>> $token = Illuminate\Support\Facades\Password::broker()->createToken($user);
>>> $user->notify(new App\Notifications\CustomResetPassword($token));
```

Observações

- O endpoint de `forgot-password` também retorna `reset_token` no JSON quando o e-mail existe — isso é deliberado para facilitar testes no contexto deste desafio. Mesmo assim o app enviará o e-mail com o link/token quando `MAIL_MAILER` estiver configurado para `smtp`.
  
	Observação: para fins do desafio técnico, o frontend exibe o `reset_token` diretamente na tela na página "Esqueci minha senha" após o envio, e também oferece um link direto para a tela de redefinição usando esse token. Não deixe essa exposição ativa em produção.
- Se não receber e-mails com SMTP configurado, verifique `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD` e `MAIL_ENCRYPTION`, além de portas bloqueadas pelo firewall.
