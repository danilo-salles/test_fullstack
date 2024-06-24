# Usar uma imagem base do Node.js
FROM node:14

# Definir o diretÃ³rio de trabalho dentro do contÃªiner
WORKDIR /app

# Copiar o arquivo package.json e package-lock.json para o diretÃ³rio de trabalho
COPY package*.json ./

# Instalar dependÃªncias
RUN npm install

# Copiar todos os arquivos do projeto para o diretÃ³rio de trabalho do contÃªiner
COPY . .

# Compilar a aplicaÃ§Ã£o Vue.js
RUN npm run build

# Usar uma imagem base do Nginx para servir a aplicaÃ§Ã£o
FROM nginx:1.19

# Copiar os arquivos compilados do diretÃ³rio de trabalho para o diretÃ³rio padrÃ£o do Nginx
COPY --from=0 /app/dist /usr/share/nginx/html

# Expor a porta que o Nginx vai usar
EXPOSE 8081

# Comando para iniciar o Nginx
CMD ["nginx", "-g", "daemon off;"]