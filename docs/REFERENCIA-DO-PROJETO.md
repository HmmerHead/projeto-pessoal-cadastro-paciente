# Referência do projeto para pessoas e modelos de IA

Este documento descreve o que está implementado no repositório, com base no código e nas configurações atuais. Quando a intenção sugerida pelos nomes ou pelo README divergir do comportamento do código, a divergência é indicada. Revise este documento junto com o código quando houver mudanças.

## Visão geral

Aplicação de cadastro de pacientes construída com Laravel. A API permite operações CRUD de pacientes, importação de registros por CSV e consulta de CEP pelo ViaCEP. Há uma página inicial simples, mas não há uma interface de gestão de pacientes implementada no frontend.

A estrutura combina Laravel em `app/` com entidades, objetos de valor e interfaces de caso de uso em `src/Core/`. O fluxo de paciente é orquestrado por `Core\UseCase\Paciente\PacienteUseCase`, com persistência delegada aos repositórios de `app/Repository/`.

## Versões e tecnologias

Não há uma versão semântica própria do produto declarada no repositório. Os números a seguir são versões de plataforma/dependências:

| Componente | Versão declarada ou travada |
|---|---|
| PHP | `^8.2`; imagem Docker `php:8.2-fpm` |
| Laravel Framework | `^12.0`; `composer.lock` fixa `v12.70.1` |
| Laravel Sanctum | `^4.0`; `composer.lock` fixa `v4.3.3` |
| Laravel Horizon | `^5.15`; `composer.lock` fixa `v5.50.1` |
| PHPUnit | `^11.5.50`; `composer.lock` fixa `11.5.57` |
| Banco de dados | PostgreSQL, conforme `.env.example` e Docker |
| Cache e fila | Redis disponível no Docker; configurações padrão do `.env.example` ainda indicam cache `file` e fila `sync` |
| Frontend/build | Vite; o `package.json` declara Vite `^8.3.5` e Laravel Vite Plugin `^3.2.0` |

As versões travadas acima correspondem ao `composer.lock` consultado e podem mudar quando as dependências forem atualizadas.

## Rotas

Rotas da API são prefixadas por `/api`. Não há prefixo de versão como `/api/v1`.

| Método | Caminho | Acesso | Comportamento |
|---|---|---|---|
| `GET` | `/api/pacientes` | Sanctum | Lista pacientes paginados. |
| `POST` | `/api/pacientes` | Sanctum | Cria paciente. |
| `GET` | `/api/pacientes/{paciente}` | Sanctum | Retorna um paciente. |
| `PUT`, `PATCH` | `/api/pacientes/{paciente}` | Sanctum | Atualiza paciente. |
| `DELETE` | `/api/pacientes/{paciente}` | Sanctum | Exclui logicamente paciente e registros relacionados. |
| `POST` | `/api/importarPacientes` | Sanctum | Recebe o CSV e despacha um job por registro. |
| `GET` | `/api/ConsultaEnderecoViaCep?cep={cep}` | Pública | Consulta endereço pelo ViaCEP e mantém o resultado em cache Redis por 300 segundos. |
| `GET` | `/` | Pública (web) | Renderiza a view `welcome`. |

As rotas CRUD são registradas por `Route::apiResource`. O acesso às rotas de pacientes e de importação está agrupado em `auth:sanctum`; o lookup de CEP fica fora desse grupo. O README descreve a criação de um usuário e de um token pessoal Sanctum pelo Tinker.

O controller de paciente retorna HTTP `201` na criação, mas também define `201` para `show` e `update`; não assuma os códigos REST convencionais `200` nessas duas operações sem conferir/alterar o código. A exclusão retorna `200` com recurso quando o caso de uso conclui a operação.

### Formato e regras do cadastro

O request de criação (`CreateRequest`) exige os campos abaixo. `nome` e `nomeMae` também têm tamanho mínimo de 3 e máximo de 255 caracteres. `foto` precisa ser um arquivo no cadastro.

- `nome`, `nomeMae`, `cpf`, `nascimento`, `cns`, `foto`
- `cep`, `endereco`, `numero`, `complemento`, `bairro`, `cidade`, `estado`

O request de atualização (`UpadateRequest`, com esse nome no código) exige os mesmos campos, mas declara `foto` apenas como `required`, sem a regra `file`. Apesar de a implementação de endereço aceitar valores ausentes como fallback, a validação do request exige todos os campos; não se trata de uma atualização parcial na prática.

O caso de uso valida o CPF por `Core\Domain\ValueObject\Documento` e verifica o formato do CEP por expressão regular. O CNS é validado por `Core\Domain\ValueObject\CNS`. A regra de CPF rejeita valores com tamanho diferente de 11 dígitos, sequências de dígitos repetidos e dígitos verificadores inválidos.

Os campos `cns`, `foto` e endereço são guardados em tabelas relacionadas; o recurso JSON `PacienteResource` devolve somente `id`, `nome`, `nomeMae`, `cpf` e `nascimento`. A resposta não inclui CNS, foto ou endereço.

### Listagem

O caso de uso lê os parâmetros `filter`, `order`, `page` e `totalPage`; os padrões são filtro vazio, ordem `DESC`, página `1` e 15 itens por página. A resposta inclui os metadados `total`, `current_page`, `last_page`, `first_page`, `per_page`, `to` e `from`.

O repositório implementa filtro por `nome` ou `cpf` usando `LIKE`, e ordenação pelo `id`. A implementação usa `current($filter)` como valor de ambos os filtros; portanto, não assuma que filtros combinados ou com formato arbitrário se comportam como uma API de busca robusta sem antes corrigir/testar essa lógica. `order` é passado à consulta sem validação explícita no repositório.

## Importação CSV

O endpoint lê o arquivo recebido no campo `file` com `league/csv`, usa a primeira linha como cabeçalho e despacha um `ImportacaoArquivosJob` por registro. Cada job chama `PacienteUseCase::salvarPaciente`.

O arquivo de exemplo `importacao-paciente.csv` pode servir para conferir o cabeçalho esperado. Os nomes de cabeçalho precisam corresponder às chaves acessadas pelo caso de uso, em especial `nome`, `nomeMae`, `cpf`, `nascimento`, `cns` e os campos de endereço. A foto é tratada no caso de uso apenas se o valor for um `UploadedFile`; um CSV não fornece naturalmente esse tipo, então não presuma que a importação associe fotos.

Pontos relevantes do comportamento atual:

- O controlador de importação recebe `Request` diretamente e não aplica `importarPacientesRequest`.
- A regra desse FormRequest está escrita como `require|file` (e não `required|file`) e o FormRequest não é usado pela rota.
- A importação não valida cada linha com um request HTTP; falhas no processamento podem ocorrer nos jobs após a resposta inicial.
- A fila está configurada como `sync` no `.env.example`, mas `ImportacaoArquivosJob` implementa `ShouldQueue`. Para processamento realmente assíncrono, configure um driver de fila apropriado e execute um worker (ou Horizon conforme a infraestrutura).

## Persistência e regras de domínio

As migrações criam:

- `paciente`: UUID, nome, nome da mãe, CPF, nascimento, timestamps e soft delete.
- `CNS`: UUID, número CNS, `paciente_id`, timestamps, soft delete e chave estrangeira para paciente.
- `foto`: UUID, caminho/identificador da foto, `paciente_id`, timestamps, soft delete e chave estrangeira.
- `endereco`: UUID, CEP e campos de endereço, `paciente_id`, timestamps, soft delete e chave estrangeira.

O cadastro e a atualização são executados em transação de banco de dados. A exclusão também percorre paciente, CNS, foto e endereço em uma transação; os modelos usam `SoftDeletes`, então a remoção é lógica. A foto é salva/removida por meio de `FotoRepository`.

O domínio está parcialmente separado do framework: entidades e value objects estão em `src/Core/Domain`, e as interfaces de repositório em `src/Core/UseCase/Repository`. Os repositórios Eloquent e controllers vivem em `app/`. O principal caso de uso de paciente está em `src/Core/UseCase/Paciente/PacienteUseCase.php`.

## Ambiente e execução local

O `docker-compose.yml` define serviços para PHP-FPM da aplicação, Nginx (porta local `8000`), PostgreSQL e Redis. O Docker Compose constrói com dependências de desenvolvimento (`COMPOSER_NO_DEV=false`); a imagem `Dockerfile` usa `COMPOSER_NO_DEV=true` como padrão.

Fluxo descrito no README:

1. Copiar `.env.example` para `.env` e configurar credenciais fortes; o Compose exige `DB_USERNAME`, `DB_PASSWORD`, `POSTGRES_ADMIN_USER` e `POSTGRES_ADMIN_PASSWORD`.
2. Gerar a chave com `php artisan key:generate`.
3. Executar migrações e seeders com `php artisan migrate --seed`.
4. Criar credencial Sanctum para consumir as rotas protegidas.

Para processar filas com Horizon, o README indica `php artisan horizon`. A aplicação também inclui configuração/provedor do Horizon. A consulta de CEP depende do cache Redis explicitamente no controller, mesmo que o `.env.example` declare `CACHE_DRIVER=file`; configure Redis acessível pela aplicação para esse endpoint.

## Testes e pontos de atenção para manutenção

Os testes existentes ficam em `tests/Feature` e `tests/Unit`. Há teste feature ativo verificando que rotas protegidas exigem autenticação, e testes unitários de entidade/objetos de valor. O teste de criação em `PacienteTest` está nomeado `atest_criacao_paciente` e, portanto, não é descoberto como teste PHPUnit normal.

Ao alterar a API, confira em conjunto:

- `routes/api.php` e autenticação Sanctum;
- requests e controllers em `app/Http`;
- `PacienteUseCase`, entidades e value objects em `src/Core`;
- repositórios Eloquent em `app/Repository`;
- migrations, resource JSON, jobs e testes correspondentes.

O `Clinica.postman_collection.json` contém material de apoio para chamadas da API. Consulte o código como fonte de verdade caso haja divergência entre ele, a collection e este guia.
