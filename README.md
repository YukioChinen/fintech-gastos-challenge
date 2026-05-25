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

- Link do deploy público: ainda não publicado.

## Credenciais do usuário seed para teste

Use o usuário criado pelo seeder de desenvolvimento:

- E-mail: `enzo@example.com`
- Senha: `senha1234`

## Observações úteis

- A API usa autenticação por token com Sanctum.
- O dashboard consome o endpoint autenticado `/api/auth/dashboard`.
- As datas de despesas são exibidas no formato `DD/MM/AAAA`.
- Para rodar tudo em sequência no projeto raiz, você também pode usar os scripts definidos no `composer.json`.
