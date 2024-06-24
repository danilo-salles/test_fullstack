# Test Fullstack

Este é um projeto fullstack desenvolvido com Laravel e Vue.js para a empresa GAZIN TECH

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
    git clone https://github.com/SallesDanilo/test_fullstack.git
    cd test_fullstack
    ```

2. Instale as dependências do Laravel:
    ```sh
    composer install
    ```

3. Execute as migrações e seeders para configurar o banco de dados:
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
