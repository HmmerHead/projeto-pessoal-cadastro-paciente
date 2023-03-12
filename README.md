## Instacação do projeto

Projeto foi criado usando docker. Toda a estrutura do mesmo permite que o projeto seja instalado com o docker-compose após um git clone

Ao realizar essa ação, 

cp .env.example .env

No .env setar informações como Host para algum de sua preferẽncia

php artisan key:generate

php artisan migrate --seed

Para utilizar o Horizon - `php artisan horizon` 

A consulta a algum endereço está usando Cache. Caso o mesmo cep seja usado, a consulta será armazenada por alguns minutos.


## Sobre o projeto

Criada uma aplicação, seguindo os requisitos necessários, onde será possível fazer o cadastro de um paciente através de um endpoint definido.
Também é possível realizar a importação de varios pacientes, através de um endpoint, enviando um arquivo CSV
Foi pedido também, a criação de um endpoint, que realiza buscas no ViaCep e faça o cache das informações

A estrutura do projeto foi feita usando alguns dos ensinamentos do Clean Architecture. Busquei deixar os principais entidade, o mais fora possível do framework, assim, qualquer mudança poderá ser feita, com poucas quebras no andamento do projeto. Alguns casos de uso também busquei criar separados. No src/Core há os UseCases e o Domain, sendo no primeiro, o caso de uso de cadastro do Paciente e alguns interfaces de como poderia ser feito o cadastro das entidades. Já em Domain, estão as entidades, alguns trait's e alguns objetos de valor para ajudar na criação das entidades.
Dentro do framework em app/Repository está por conta de realizar algumas ações, mas com o uso do framework para auxiliar. 

### Estrutura do Banco
<br>
![image](https://user-images.githubusercontent.com/26328503/224576207-3c95c7d6-b081-43b0-a17a-24a79d9112e2.png)
<br>

### Pontos Realizados (Resumo) 


<br>

Endpoint's para a realização das ações (adição, edição, visualização e exclusão) estão em RESTful, com um documento das rotas junto a projeto.





<br>
- [Simple, fast routing engine](https://laravel.com/docs/routing).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
