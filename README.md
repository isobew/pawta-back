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

6. Rode os seeders para popular dados iniciais:
```bash
php artisan db:seed
```

7. Rode os testes:
```bash
php artisan test
```

---

## Funcionalidades implementadas até agora
- Autenticação via API com Laravel Breeze e Sanctum

- Registro de usuários

- Login e logout via token

- Proteção de rotas com middleware auth:sanctum

- Gestão de Tarefas

    - Criação, edição, visualização e exclusão
    - Exportação em CSV
    - Filtro por urgência (lembretes)

- Gestão de Quadros

    - Admin pode criar, editar, excluir quadros
    - Listagem geral ou por ID com tarefas associadas

- Controle de Acesso (roles)

    - is_admin = true: acesso completo
    - is_admin = false: acesso restrito às próprias tarefas

- Gestão de Usuários (admin)

    - Listagem paginada e completa
    - Atualização e exclusão

---

## Tecnologias e Pacotes
    - Laravel 11
    - Laravel Sanctum
    - Laravel Breeze (modo API)
    - maatwebsite/excel
    - PHPUnit
    - MySQL

---

## Documentação técnica
A documentação pode ser acessada em:
https://drive.google.com/file/d/1V6qDJhL7D4Hz7mRdoKtHA1z6cCsDHmbX/view?usp=sharing

---

## Figma
As telas estão disponíveis em:
https://www.figma.com/design/efdyorXhdBxhgaQwak6dRW/Pawta
