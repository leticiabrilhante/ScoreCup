<?php
session_start();
require_once __DIR__ . '/db.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {
        $erro = 'Preencha e-mail e senha.';
    } else {
        $stmt = $pdo->prepare('SELECT id, nome, email, senha FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        $senhaCorreta = false;
        if ($user) {
            $senhaSalva = $user['senha'];
            $senhaCorreta = password_verify($senha, $senhaSalva) || hash_equals($senhaSalva, $senha);
        }

        if ($user && $senhaCorreta) {
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['nome'] = $user['nome'];
            header('Location: dashboard.php');
            exit;
        }

        $erro = 'E-mail ou senha inválidos!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.css">
    <title>ScoreCup - Login</title>
</head>
<body>
    <div class="login-box">
        <div class="logo-area">
            <img src="img/logo_taca_dourada.png" alt="Logo ScoreCup" class="logo-img">
        </div>

        <p class="subtitle">Acesse sua conta para fazer seus palpites</p>

        <?php if ($erro): ?>
            <div class="alert alert-danger py-2" role="alert">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">E-mail</label>
                <div class="input-wrapper">
                    <span class="input-icon">✉</span>
                    <input type="email" id="email" name="email" class="form-input" placeholder="nome@exemplo.com" required>
                </div>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <div class="input-wrapper">
                    <span class="input-icon">🔒</span>
                    <input type="password" id="senha" name="senha" class="form-input" placeholder="••••••••" required>
                </div>
            </div>

            <div class="form-extras">
                <label class="remember">
                    <input type="checkbox" name="lembrar"> Lembrar de mim
                </label>
                <a href="#" class="forgot">Esqueci a senha</a>
            </div>

            <button type="submit" class="btn-login">Entrar</button>
        </form>

        <p class="footer-text">©2026 ScoreCup</p>
    </div>
</body>
</html>
