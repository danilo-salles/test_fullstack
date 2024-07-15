# Test Fullstack

Este é um projeto fullstack desenvolvido com Laravel e Vue.js

## Estrutura do Projeto

- **Backend:** Laravel
- **Frontend:** Vue.js

## Requisitos

- PHP = php:8.1
- Composer
- Node.js
- NPM
- MySQL 

## Configuração

### Backend (Laravel)

1. Clone o repositório:
    ```sh
    git clone -b master https://github.com/SallesDanilo/test_fullstack.git
    cd test_fullstack
    ```
2. Suba o container docker:
    ```sh
    docker compose up --build
    ```

4. Instale as dependências do Laravel:
    ```sh
    composer install
    ```
5. Copie o arquivo `.env.example` para `.env` e configure as variáveis de ambiente, especialmente a configuração do banco de dados:
```sh
cp .env.example .env
```

5. Gere a chave da aplicação Laravel:
    ```sh
    php artisan key:generate
    ```

6. Execute as migrações e seeders para configurar o banco de dados:
    ```sh
    php artisan migrate --seed
    ```
### Frontend (Vue.js)

1. Navegue até o diretório do frontend:
    ```sh
    cd frontend
    ```

2. Instale as dependências do Node.js:
    ```sh
    npm install

3. Compile os arquivos estáticos para desenvolvimento:
    ```sh
    npm run serve

## Uso

Após seguir os passos de configuração, a aplicação estará disponível em:
- Backend: [http://localhost:8000]
- Frontend: [http://localhost:8081]

## Testes

### Testes Backend

1. Execute os testes do Laravel:
 - php artisan test

## Funcionalidades

1. **Navegação Entre Telas**:
   - A interface inicial contém botões que redirecionam para as telas de "Desenvolvedores", "Níveis" e "Home".

2. **CRUD de Desenvolvedores e Níveis**:
   - Ambas as telas começam com um botão "Adicionar" e uma lista vazia (caso não haja itens adicionados ainda).
   - Conforme os dados são adicionados, uma tabela é exibida contendo as entradas.
   - A tabela inclui botões para "Editar" e "Remover" itens.

3. **Validação de Campos**:
   - **Níveis**:
     - Ao criar um nível, se um nível com o mesmo nome já existir (por exemplo, "Junior"), o sistema não permitirá a duplicação e exibirá uma mensagem de erro.
     - Ao tentar atualizar um nível para um nome que já existe, a operação também será barrada.
   - **Desenvolvedores**:
     - Ao adicionar um desenvolvedor, é necessário fornecer o nome, sexo, data de nascimento, hobby e o ID do nível associado.
     - A data de nascimento é validada para garantir que o desenvolvedor tenha pelo menos 18 anos.
     - Validações adicionais garantem a integridade dos dados.

4. **Confirmação de Deleção**:
   - Os botões de "Deletar" em ambas as telas abrem um modal de confirmação para evitar remoções acidentais.

5. **Contagem de Desenvolvedores por Nível**:
   - Na tela de níveis, é realizado um GET na API para obter a contagem de desenvolvedores associados a cada nível.

6. **Ordenação**:
   - A lista pode ser ordenada por qualquer coluna, exceto pelos botões de ação.

### Exemplo de Fluxo

- **Adicionando um Nível**:
  1. Clique no botão "Adicionar".
  2. Preencha o campo de nome do nível.
  3. Se um nível com o mesmo nome já existir, será exibida uma mensagem de erro.
  4. Clique em "Salvar" para adicionar o novo nível.

- **Editando um Nível**:
  1. Clique no botão "Editar" ao lado do nível que deseja modificar.
  2. Faça as alterações necessárias.
  3. Se tentar alterar para um nome que já existe, a operação será barrada.
  4. Clique em "Salvar" para aplicar as alterações.

- **Removendo um Nível**:
  1. Clique no botão "Deletar" ao lado do nível que deseja remover.
  2. Confirme a remoção no modal que será exibido.

- **Adicionando um Desenvolvedor**:
  1. Clique no botão "Adicionar".
  2. Preencha os campos: nome, sexo, data de nascimento, hobby e o ID do nível associado.
  3. A data de nascimento deve ser válida e o desenvolvedor deve ter pelo menos 18 anos.
  4. Clique em "Salvar" para adicionar o novo desenvolvedor.

- **Editando um Desenvolvedor**:
  1. Clique no botão "Editar" ao lado do desenvolvedor que deseja modificar.
  2. Faça as alterações necessárias em qualquer campo.
  3. Clique em "Salvar" para aplicar as alterações.

- **Removendo um Desenvolvedor**:
  1. Clique no botão "Deletar" ao lado do desenvolvedor que deseja remover.
  2. Confirme a remoção no modal que será exibido.


  ** Documentação da API
 ```sh 
    Níveis
GET - http://localhost:8000/api/niveis
Retorna todos os níveis cadastrados.
Exemplo de retorno 200:
{
    "data": [
        {
            "id": 8,
            "nivel": "veritatis"
        },
        {
            "id": 9,
            "nivel": "debitis"
        }
    ]
}
Exemplo de retorno 400:
{
    "error": "Erro específico indicando o motivo do erro"
}
POST - http://localhost:8000/api/niveis
Cadastra um novo nível.
Exemplo de body:
{
  "nivel": "junior"
}
Exemplo de retorno 200:
{
    "success": true,
    "message": "Nível criado com sucesso.",
    "data": {
        "nivel": "junior",
        "id": 17
    }
}
Exemplo de retorno 400:
{
    "error": {
        "nivel": [
            "O campo nivel é obrigatório."
        ]
    }
}
PUT - http://localhost:8000/api/niveis/17
Atualiza um nível existente.
Exemplo de body:
{
    "nivel": "juniorfs"
}
Exemplo de retorno 200:
{
    "success": true,
    "message": "Nível atualizado com sucesso.",
    "data": {
        "id": 17,
        "nivel": "juniorfs"
    }
}
Exemplo de retorno 400:
{
    "error": {
        "nivel": [
            "O campo nivel é obrigatório."
        ]
    }
}
Exemplo de retorno 404:
{
    "error": "Nível não encontrado"
}
DELETE - http://localhost:8000/api/niveis/17
Deleta um nível pelo ID.
Exemplo de retorno 200:
{
    "success": true,
    "message": "Nível deletado com sucesso."
}
Exemplo de retorno 404:
{
    "error": "Nível não encontrado para deletar"
}
Desenvolvedores
GET - http://localhost:8000/api/desenvolvedores
Retorna todos os desenvolvedores cadastrados.
Exemplo de retorno 404:
{
    "message": "Não há desenvolvedores cadastrados"
}
Exemplo de retorno 200:
{
    "data": [
        {
            "id": 6,
            "nome": "Natalia Robert",
            "sexo": "F",
            "data_nascimento": "01-05-1987",
            "idade": 37,
            "hobby": "Lutar",
            "nivel": {
                "id": 16,
                "nivel": "quia"
            }
        }
    ],
    "meta": {
        "total": 1,
        "per_page": 10,
        "current_page": 1,
        "last_page": 1
    }
}
POST - http://localhost:8000/api/desenvolvedores
Cadastra um novo desenvolvedor.
Exemplo de body:
{
  "nivel_id": 16,
  "nome": "Natalia Robert",
  "sexo": "F",
  "data_nascimento": "1987-05-01",
  "hobby": "Lutar"
}
Exemplo de retorno 201:
{
    "success": true,
    "message": "Desenvolvedor criado com sucesso.",
    "data": {
        "nivel_id": 16,
        "nome": "Natalia Robert",
        "sexo": "F",
        "data_nascimento": "1987-05-01",
        "hobby": "Lutar",
        "id": 6
    }
}
Exemplo de retorno 400:
{
    "error": "Desenvolvedor já existe com os mesmos dados"
}
ou
{
    "error": {
        "sexo": [
            "O campo sexo é obrigatório."
        ]
    }
}
PUT - http://localhost:8000/api/desenvolvedores/6
Atualiza um desenvolvedor existente.
Exemplo de body:
{
    "nivel_id": 16,
    "nome": "Natalia Robert Junior",
    "sexo": "F",
    "data_nascimento": "1987-05-01",
    "hobby": "Lutar"
}
Exemplo de retorno 200:
{
    "success": true,
    "message": "Desenvolvedor atualizado com sucesso.",
    "data": {
        "id": 6,
        "nivel_id": 16,
        "nome": "Natalia Robert Junior",
        "sexo": "F",
        "hobby": "Lutar",
        "data_nascimento": "1987-05-01",
        "created_at": "2024-06-24T14:56:36.000000Z",
        "updated_at": "2024-06-24T15:01:14.000000Z"
    }
}
Exemplo de retorno 404:
{
    "error": "Desenvolvedor não encontrado"
}
DELETE - http://localhost:8000/api/desenvolvedores/6
Deleta um desenvolvedor pelo ID.
Exemplo de retorno 200:
{
    "success": true,
    "message": "Desenvolvedor deletado com sucesso."
}
Exemplo de retorno 404:
{
    "error": "Desenvolvedor não encontrado"
}

GET  - http://localhost:8000/api/niveis/desenvolvedores/count
Retorna os desenvolvedores cadastrados por nivel
Exemplo de retorno 200:
{
    "data": [
        {
            "id": 8,
            "nivel": "junior",
            "quantidade": 1
        }```
    ]
}Exemplo de retorno 404:
{
    "message": "Não há desenvolvedores cadastrados por nível"
}

