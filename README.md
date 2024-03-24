# Projeto Alloy

Este é o projeto Alloy, uma aplicação construída com Laravel.

## Instalação

Antes de começar, certifique-se de ter o PHP e o Composer instalados em seu sistema.

1. Clone o repositório:

git clone https://github.com/seu-usuario/projeto-alloy.git


2. Navegue até o diretório do projeto:

cd projeto-alloy


3. Instale as dependências do Composer:


4. Copie o arquivo de configuração de ambiente:

cp .env.example .env


5. Gere a chave de aplicativo:

php artisan key:generate


6. Inicie o servidor de desenvolvimento:

php artisan serve


O servidor será iniciado em `http://localhost:8000`.

## Executando Testes

Para executar os testes automatizados, utilize o seguinte comando:

php artisan test --filter testReceberPedido