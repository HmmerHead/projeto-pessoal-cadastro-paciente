## Instalação do projeto

<br>
Projeto foi criado usando docker. Toda a estrutura do mesmo permite que o projeto seja instalado com o docker-compose após um git clone. Em seguida.


`cp .env.example .env`

No `.env`, defina senhas fortes e exclusivas para `DB_PASSWORD` e `POSTGRES_ADMIN_PASSWORD`, e nomes diferentes para `DB_USERNAME` e `POSTGRES_ADMIN_USER`. O usuário `DB_USERNAME` é criado com permissões limitadas para a aplicação; o usuário `POSTGRES_ADMIN_USER` é reservado para administração. O banco fica acessível apenas pela rede interna do Docker.

Para instalações com um volume PostgreSQL já existente, o script de inicialização não será executado novamente. Crie o usuário `DB_USERNAME` no banco e conceda a ele acesso ao banco e permissões de uso e criação no schema `public` antes de iniciar a aplicação.

`php artisan key:generate`

`php artisan migrate --seed`

Para utilizar o Horizon - `php artisan horizon` 

As rotas de pacientes e de importação exigem um token Sanctum. Crie um usuário e gere o token pelo Tinker (`php artisan tinker`), mantendo o token retornado em local seguro:

```php
$user = \App\Models\User::create([
    'name' => 'API',
    'email' => 'api@example.com',
    'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(40)),
]);
$user->createToken('api-client')->plainTextToken;
```

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
