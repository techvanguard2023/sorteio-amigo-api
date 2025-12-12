# Sorteio Amigo API

## Descrição

API Backend para o sistema de Amigo Secreto ("Sorteio Amigo"). Desenvolvida em Laravel, esta API fornece toda a lógica para autenticação, gerenciamento de grupos, realização de sorteios e listas de desejos.

O frontend deste projeto reside em um repositório separado (cliente React/Mobile).

## Tecnologias

- **PHP** >= 8.2
- **Laravel Framework**
- **SQLite** (Banco de dados padrão)
- **Sanctum** (Autenticação)

## Instalação

Siga os passos abaixo para rodar o projeto localmente:

1. **Clone o repositório**
   ```bash
   git clone <url-do-repositorio>
   cd sorteio-amigo-api
   ```

2. **Instale as dependências do PHP**
   ```bash
   composer install
   ```

3. **Configure as variáveis de ambiente**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure o Banco de Dados (SQLite)**
   Por padrão, o projeto usa SQLite. Certifique-se de criar o arquivo do banco:
   ```bash
   # Linux/Mac
   touch database/database.sqlite
   
   # Windows (PowerShell)
   New-Item -ItemType File -Path database/database.sqlite
   ```

5. **Rode as migrações**
   ```bash
   php artisan migrate
   ```

6. **Inicie o servidor**
   ```bash
   php artisan serve
   ```
   O servidor estará rodando em `http://localhost:8000`.

## Rotas da API (V1)

Todas as rotas são prefixadas com `/api/v1`.

### Autenticação (Públicas)
- `POST /register` - Criar nova conta
- `POST /login` - Autenticar usuário
- `GET /invitations/{invite_code}` - Visualizar convite público

### Rotas Protegidas (Requer Header `Authorization: Bearer <token>`)

#### Usuário
- `GET /user` - Dados do usuário logado
- `POST /logout` - Encerrar sessão

#### Grupos
- `GET /groups` - Listar grupos do usuário
- `POST /groups` - Criar novo grupo
- `GET /groups/{group}` - Detalhes do grupo
- `PUT /groups/{group}` - Atualizar grupo
- `DELETE /groups/{group}` - Excluir grupo

#### Sorteio
- `POST /groups/{group}/draw` - Realizar o sorteio (apenas dono/admin)
- `GET /groups/{group}/draw` - Ver resultado do sorteio (quem eu tirei)

#### Convites
- `POST /invitations/{invite_code}/accept` - Aceitar convite para entrar no grupo

#### Participantes & Wishlist
- `PUT /participants/{participant}` - Atualizar perfil no grupo (tamanho de camisa, notas, etc.)
- `POST /participants/wishlist` - Adicionar item à lista de desejos
- `PUT /participants/wishlist/{wishlist_item}` - Atualizar item
- `DELETE /participants/wishlist/{wishlist_item}` - Remover item

## Estrutura do Projeto

- `app/Http/Controllers/Api`: Controladores da API
- `routes/api.php`: Definição de rotas
- `database/migrations`: Definição do esquema do banco de dados

## Licença

Este projeto é open-source e licenciado sob a [MIT license](https://opensource.org/licenses/MIT).
