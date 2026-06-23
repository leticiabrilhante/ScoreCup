<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/db.php';

$mensagem = '';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: usuarios_listar.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id, nome, email FROM users WHERE id = :id');
$stmt->execute([':id' => $id]);
$users = $stmt->fetch();

if (!$users) {
    header('Location: usuarios_listar.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($nome !== '' && $email !== '') {
        try {
            $stmt = $pdo->prepare('UPDATE users SET nome = :nome, email = :email WHERE id = :id');
            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':id' => $id,
            ]);
            header('Location: usuarios_listar.php?msg=editado');
            exit;
        } catch (PDOException $e) {
            $mensagem = 'Erro ao atualizar: ' . $e->getMessage();
        }
    } else {
        $mensagem = 'Preencha todos os campos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScoreCup - Editar Usuário</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">Editar Usuário #<?= (int) $usuario['id'] ?></h4>
                    </div>
                    <div class="card-body">
                        <?php if ($mensagem): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($mensagem) ?></div>
                        <?php endif; ?>

                        <form action="usuarios_editar.php?id=<?= (int) $usuario['id'] ?>" method="POST">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="usuarios_listar.php" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-warning">Atualizar Usuário</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
