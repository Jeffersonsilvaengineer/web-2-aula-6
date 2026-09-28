# Aula 6 - Cadastro e listagem de receitas em PHP

## O que foi feito

- `cadastrar.php`: formulário que recebe nome, ingredientes, modo de preparo e foto por POST.
- `receitas.md`: arquivo que recebe um registro JSON por linha (gerado automaticamente no primeiro cadastro).
- `imagens/`: pasta para as fotos enviadas.
- `listar.php`: lê `receitas.md` e exibe nome, foto, ingredientes e modo de preparo.
- `index.php`: abre o cadastro.

## Como executar

É necessário PHP 8.0 ou superior, com a extensão Fileinfo. Na pasta deste projeto, execute `php -S localhost:8000` e acesse `http://localhost:8000/`. A pasta do projeto precisa permitir gravação pelo processo PHP para criar `receitas.md` e salvar fotos em `imagens/`.

As fotos aceitas são JPG, PNG e WebP, até 5 MB. Em hospedagens, configure `upload_max_filesize` e `post_max_size` para permitir esse tamanho.
