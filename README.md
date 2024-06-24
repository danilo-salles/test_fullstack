# Test Fullstack

Este é um projeto fullstack desenvolvido com Laravel e Vue.js. Ele serve como um exemplo de aplicação completa, incluindo configuração, uso de APIs e uma interface de usuário interativa.

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
   
