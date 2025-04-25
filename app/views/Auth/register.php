<!-- public/register.php -->

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body>

<div class="container">
  <div class="login-container">
    <h2 class="form-title">Criar Conta</h2>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/Auth/login/cadastrar" method="post">
      <div class="mb-3">
        <label for="email" class="form-label">E-mail:</label>
        <input type="email" name="email" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Senha:</label>
        <input type="password" name="password" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirmar Senha:</label>
        <input type="password" name="confirm_password" class="form-control" required>
      </div>

      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-success">Cadastrar</button>
      </div>
    </form>

    <div class="form-link">
      <a href="<?= BASE_URL ?>/Auth/login">Já tenho uma conta</a>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
