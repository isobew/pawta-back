# Pawta Back - API Laravel para To-Do List

Este é o backend da aplicação Pawta, uma API construída com Laravel para gerenciar listas de tarefas (to-do list) com autenticação via Laravel Breeze (API).

---

## Requisitos

- PHP >= 8.1
- Composer
- MySQL

---

## Instalação

1. Clone este repositório:

```bash
git clone https://github.com/isobew/pawta-back.git
cd pawta-back
```
2. Instale as dependências PHP via Composer:

```bash
composer install
```

3. Copie o arquivo de ambiente e configure as variáveis do banco de dados:

```bash
cp .env.example .env
```

4. Gere a chave da aplicação

```bash
php artisan key:generate
```

5. Execute as migrations para criar as tabelas no banco:

```bash
php artisan migrate
```

---

## Funcionalidades implementadas até agora
- Autenticação via API com Laravel Breeze e Sanctum

    - Registro de usuários

    - Login e logout via token

    - Proteção de rotas com middleware auth:sanctum

---
