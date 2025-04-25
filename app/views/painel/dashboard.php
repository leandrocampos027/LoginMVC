<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Olá, <?= $_SESSION['usuario_nome'] ?>! Seja bem-vindo ao sistema.</h1>
    <a href="<?= BASE_URL ?>/Auth/login/sair">Sair</a>
</body>
</html>
