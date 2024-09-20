<?php
session_start();
include 'db.php';

// Busca todos os projetos
$stmt = $pdo->query("SELECT * FROM projetos");
$projetos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Projetos da Escola</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Projetos da Escola</h1>
    <ul>
        <?php foreach ($projetos as $projeto): ?>
            <li>
                <h2><?= $projeto['tema'] ?></h2>
                <p>Integrantes: <?= $projeto['nome_integrantes'] ?></p>
                <a href="detalhes.php?id=<?= $projeto['id'] ?>" class="button">Ver Detalhes</a>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="index.php">Voltar para a página inicial</a>
</body>
</html>
