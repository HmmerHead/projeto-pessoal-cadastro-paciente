## Instacação do projeto

<br>
Projeto foi criado usando docker. Toda a estrutura do mesmo permite que o projeto seja instalado com o docker-compose após um git clone. Em seguida.


`cp .env.example .env`

No .env setar informações como Host do DB e do Redis para algum de sua preferẽncia.

`php artisan key:generate`

`php artisan migrate --seed`

Para utilizar o Horizon - `php artisan horizon` 

<br>
A consulta no endpoint endereço está usando Cache. Caso o mesmo cep seja usado, a consulta será armazenada por alguns minutos.

<br>

## Sobre o projeto

Criada uma aplicação, seguindo os requisitos necessários, onde será possível fazer o cadastro de um paciente através de um endpoint definido.
Também é possível realizar a importação de varios pacientes, através de um endpoint, enviando um arquivo CSV
Foi pedido também, a criação de um endpoint, que realiza buscas no ViaCep e faça o cache das informações

A estrutura do projeto foi feita usando alguns dos ensinamentos do Clean Architecture. Busquei deixar os principais entidade, o mais fora possível do framework, assim, qualquer mudança poderá ser feita, com poucas quebras no andamento do projeto. Alguns casos de uso também busquei criar separados. No src/Core há os UseCases e o Domain, sendo no primeiro, o caso de uso de cadastro do Paciente e alguns interfaces de como poderia ser feito o cadastro das entidades. Já em Domain, estão as entidades, alguns trait's e alguns objetos de valor para ajudar na criação das entidades.
Dentro do framework em app/Repository está por conta de realizar algumas ações, mas com o uso do framework para auxiliar. 

Endpoint's para a realização das ações (adição, edição, visualização e exclusão) estão em RESTful, com um documento das rotas junto a projeto.

### Estrutura do Banco
<br>
![image](https://user-images.githubusercontent.com/26328503/224576207-3c95c7d6-b081-43b0-a17a-24a79d9112e2.png)
<br>

