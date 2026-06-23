<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/db.php';

$stmt = $pdo->prepare('SELECT id, nome, email FROM users ORDER BY nome ASC');
$stmt->execute();
$usuarios = $stmt->fetchAll();

$mensagens = [
    'cadastrado' => 'Usuário cadastrado com sucesso.',
    'editado' => 'Usuário atualizado com sucesso.',
    'excluido' => 'Usuário excluído com sucesso.',
];
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScoreCup - Gerenciar Usuários</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="container py-5">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Gerenciamento de Usuários</h2>
            <div>
                <a href="usuarios_cadastrar.php" class="btn btn-primary">Cadastrar Novo Usuário</a>
                <a href="dashboard.php" class="btn btn-secondary">Voltar ao Dashboard</a>
            </div>
        </div>

        <?php if (isset($mensagens[$msg])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($mensagens[$msg]) ?></div>
        <?php endif; ?>

        <div class="table-responsive bg-white p-3 shadow-sm rounded-4">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($usuarios): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?= (int) $usuario['id'] ?></td>
                                <td><strong><?= htmlspecialchars($usuario['nome']) ?></strong></td>
                                <td><?= htmlspecialchars($usuario['email']) ?></td>
                                <td class="text-center">
                                    <a href="usuarios_editar.php?id=<?= (int) $usuario['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="usuarios_excluir.php?id=<?= (int) $usuario['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja remover este usuário?');">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">Nenhum usuário cadastrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
