<?php
function h(string $valor): string { return htmlspecialchars($valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
$receitas = [];
$caminho = __DIR__ . '/receitas.md';
if (is_file($caminho)) {
    $linhas = file($caminho, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($linhas !== false) {
        foreach ($linhas as $linha) {
            $receita = json_decode($linha, true);
            if (is_array($receita) && isset($receita['nome'], $receita['ingredientes'], $receita['preparo'], $receita['foto'])) {
                $receitas[] = $receita;
            }
        }
    }
}
$receitas = array_reverse($receitas);
?>
<!doctype html>
<html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Receitas cadastradas</title><link rel="stylesheet" href="estilo.css"></head>
<body><main class="container"><nav><a href="cadastrar.php">Cadastrar receita</a><a href="listar.php">Ver receitas</a></nav><h1>Receitas cadastradas</h1>
<?php if (!$receitas): ?><section class="card"><p>Nenhuma receita cadastrada ainda.</p><a href="cadastrar.php">Cadastrar a primeira receita</a></section><?php endif; ?>
<?php foreach ($receitas as $receita): ?>
<article class="card receita"><img src="<?= h($receita['foto']) ?>" alt="Foto de <?= h($receita['nome']) ?>" loading="lazy"><div><h2><?= h($receita['nome']) ?></h2><h3>Ingredientes</h3><p><?= nl2br(h($receita['ingredientes'])) ?></p><h3>Modo de preparo</h3><p><?= nl2br(h($receita['preparo'])) ?></p></div></article>
<?php endforeach; ?></main></body></html>
