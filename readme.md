# Tecnologias Utilizadas\*\*

-   PHP 7.4.2
-   Lumen 6.2.0
-   Composer
-   Postman

# Como instalar\*\*

-   Baixar/Clonar repositório
-   Executar o comando:
    -   composer dump-autoload.

# Rotas

Rota 1: [GET] .../cnpj/{cnpj}
http://localhost/lumen-desafio/public/cnpj/04748181000947
● Objetivo: Consultar e retornar os dados de uma empresa através de um CNPJ informado.
● Entrada: Receber um número de CNPJ através da sua rota

Rota 2: [POST] .../quote
http://localhost/lumen-desafio/public/quote
● Objetivo: Criar uma rota para receber dados de entrada e realizar uma cotação fictícia
com a API da Frete Rápido (os valores e transportadoras retornadas não são reais);
● Entrada: Receber um JSON com os dados de volumes e destinatário, conforme exemplo
abaixo:

```
{
"destinatario": {
"endereco": {
"cep": "01311000"
}
},
"volumes": [
{
"tipo": 7,
"quantidade": 1,
"peso": 5,
"valor": 349,
"sku": "abc-teste-123",
"altura": 0.2,
"largura": 0.2,
"comprimento": 0.2
},
{
"tipo": 7,
"quantidade": 2,
"peso": 4,
"valor": 556,
"sku": "abc-teste-527",
"altura": 0.4,
"largura": 0.6,
"comprimento": 0.15
}
]
}
```
