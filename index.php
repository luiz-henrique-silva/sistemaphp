<?php
session_start();
include 'db.php';

// Busca os 3 projetos com mais avaliações
$stmt = $pdo->query("SELECT * FROM projetos ORDER BY avaliacao DESC LIMIT 3");
$top_projetos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Busca todos os projetos para a aba "Projetos da Escola"
$stmt = $pdo->query("SELECT * FROM projetos");
$todos_projetos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projetos de TCC</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="#slideshow">Melhores Projetos</a></li>
            <li><a href="projetos.php">Projetos da Escola</a></li>
        </ul>
    </nav>

    <div id="slideshow" class="slideshow-container">
        <?php foreach ($top_projetos as $projeto): ?>
            <div class="slide">
                <img src="<?= $projeto['imagem'] ?>" alt="<?= $projeto['tema'] ?>">
                <div class="text">
                    <h2><?= $projeto['tema'] ?></h2>
                    <p>Integrantes: <?= $projeto['nome_integrantes'] ?></p>
                    <a href="detalhes.php?id=<?= $projeto['id'] ?>" class="button">Ver Detalhes</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="script.js"></script>
</body>
</html>
