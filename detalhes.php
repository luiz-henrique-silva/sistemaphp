<?php
session_start();
include 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Projeto não encontrado.");
}

// Busca o projeto específico
$stmt = $pdo->prepare("SELECT * FROM projetos WHERE id = ?");
$stmt->execute([$id]);
$projeto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$projeto) {
    die("Projeto não encontrado.");
}

// Verifica se o usuário já avaliou o projeto
$user_id = $_SESSION['user_id'] ?? null;
$ja_avaliou = false;

if ($user_id) {
    $stmt = $pdo->prepare("SELECT * FROM avaliacoes WHERE projeto_id = ? AND usuario_id = ?");
    $stmt->execute([$id, $user_id]);
    $ja_avaliou = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Avaliação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$ja_avaliou) {
    $nota = (int)$_POST['avaliacao'];

    // Insere a nova avaliação
    $stmt = $pdo->prepare("INSERT INTO avaliacoes (projeto_id, usuario_id, nota) VALUES (?, ?, ?)");
    $stmt->execute([$id, $user_id, $nota]);

    // Atualiza a média da avaliação do projeto
    $total_avaliacoes = $projeto['total_avaliacoes'] + 1;
    $nova_avaliacao = (($projeto['avaliacao'] * $projeto['total_avaliacoes']) + $nota) / $total_avaliacoes;

    $stmt = $pdo->prepare("UPDATE projetos SET avaliacao = ?, total_avaliacoes = ? WHERE id = ?");
    $stmt->execute([$nova_avaliacao, $total_avaliacoes, $id]);

    header("Location: detalhes.php?id=$id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= $projeto['tema'] ?></title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="estrelas.css">
</head>
<body>
    <h1><?= $projeto['tema'] ?></h1>
    <img src="<?= $projeto['imagem'] ?>" alt="<?= $projeto['tema'] ?>">
    <p>Integrantes: <?= $projeto['nome_integrantes'] ?></p>
    <p>Avaliação: <?= number_format($projeto['avaliacao'], 1) ?> (<?= $projeto['total_avaliacoes'] ?> avaliações)</p>

    <?php if (isset($_SESSION['user_id'])): ?>
        <?php if ($ja_avaliou): ?>
            <p>Você já avaliou este projeto.</p>
        <?php else: ?>
            <form method="POST">
                <label>Avalie o projeto:</label>
                <div class="estrelas">
                    <input type="radio" name="avaliacao" value="1" id="star1" required>
                    <label for="star1" class="star">★</label>
                    <input type="radio" name="avaliacao" value="2" id="star2">
                    <label for="star2" class="star">★</label>
                    <input type="radio" name="avaliacao" value="3" id="star3">
                    <label for="star3" class="star">★</label>
                    <input type="radio" name="avaliacao" value="4" id="star4">
                    <label for="star4" class="star">★</label>
                    <input type="radio" name="avaliacao" value="5" id="star5">
                    <label for="star5" class="star">★</label>
                </div>
                <button type="submit">Enviar Avaliação</button>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <p><a href="login.php">Faça login para avaliar</a></p>
    <?php endif; ?>

    <h2>Imagens Adicionais</h2>
    <div class="imagens-adicionais">
        <?php foreach ($imagens_adicionais as $imagem): ?>
            <img src="<?= $imagem['caminho_imagem'] ?>" alt="Imagem adicional" style="max-width: 300px; margin: 10px;">
        <?php endforeach; ?>
    </div>

    <a href="index.php">Voltar</a>
</body>
</html>
