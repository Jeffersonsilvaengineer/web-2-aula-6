<?php
$erro = '';
$sucesso = isset($_GET['sucesso']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $ingredientes = trim($_POST['ingredientes'] ?? '');
    $preparo = trim($_POST['preparo'] ?? '');

    if ($nome === '' || $ingredientes === '' || $preparo === '') {
        $erro = 'Preencha todos os campos da receita.';
    } elseif (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        $erro = 'Selecione uma foto válida (até 5 MB).';
    } elseif ($_FILES['foto']['size'] > 5 * 1024 * 1024) {
        $erro = 'A foto deve ter no máximo 5 MB.';
    } else {
        $tipos = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($_FILES['foto']['tmp_name']);
        if (!isset($tipos[$mime]) || @getimagesize($_FILES['foto']['tmp_name']) === false) {
            $erro = 'Envie uma imagem JPG, PNG ou WebP.';
        } else {
            $pasta = __DIR__ . '/imagens';
            if (!is_dir($pasta) && !mkdir($pasta, 0775, true)) {
                $erro = 'Não foi possível criar a pasta de imagens.';
            } else {
                $arquivo = bin2hex(random_bytes(16)) . '.' . $tipos[$mime];
                $destino = $pasta . '/' . $arquivo;
                if (!move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                    $erro = 'Não foi possível salvar a foto.';
                } else {
                    // Uma receita JSON por linha mantém o arquivo .md legível e evita
                    // que quebras de linha nos campos confundam a leitura.
                    $registro = json_encode([
                        'nome' => $nome,
                        'ingredientes' => $ingredientes,
                        'preparo' => $preparo,
                        'foto' => 'imagens/' . $arquivo,
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    if ($registro === false || file_put_contents(__DIR__ . '/receitas.md', $registro . "\n", FILE_APPEND | LOCK_EX) === false) {
                        unlink($destino);
                        $erro = 'Não foi possível gravar receitas.md. Verifique a permissão da pasta.';
                    } else {
                        header('Location: cadastrar.php?sucesso=1');
                        exit;
                    }
                }
            }
        }
    }
}
function h(string $valor): string { return htmlspecialchars($valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
?>
<!doctype html>
<html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Cadastrar receita</title><link rel="stylesheet" href="estilo.css"></head>
<body><main class="container"><nav><a href="cadastrar.php">Cadastrar receita</a><a href="listar.php">Ver receitas</a></nav><section class="card"><h1>Nova receita</h1><p>Preencha os dados e escolha uma foto.</p>
<?php if ($sucesso): ?><div class="aviso ok">Receita cadastrada com sucesso! <a href="listar.php">Ver receitas</a></div><?php endif; ?>
<?php if ($erro): ?><div class="aviso erro"><?= h($erro) ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data">
<label for="nome">Nome da receita</label><input id="nome" name="nome" maxlength="150" required value="<?= h($nome ?? '') ?>">
<label for="ingredientes">Ingredientes</label><textarea id="ingredientes" name="ingredientes" rows="7" required><?= h($ingredientes ?? '') ?></textarea>
<label for="preparo">Modo de preparo</label><textarea id="preparo" name="preparo" rows="7" required><?= h($preparo ?? '') ?></textarea>
<label for="foto">Foto</label><input id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp" required><small>JPG, PNG ou WebP; até 5 MB.</small>
<button type="submit">Salvar receita</button></form></section></main></body></html>
