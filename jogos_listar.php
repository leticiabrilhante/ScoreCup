<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/db.php';

$stmt = $pdo->prepare('SELECT id, time_a, time_b, data_jogo, status FROM jogos ORDER BY data_jogo ASC, id ASC');
$stmt->execute();
$jogos = $stmt->fetchAll();

$mensagens = [
    'cadastrado' => 'Jogo cadastrado com sucesso.',
    'editado' => 'Jogo atualizado com sucesso.',
    'excluido' => 'Jogo excluído com sucesso.',
];
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScoreCup - Jogos</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="container py-5">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">CRUD de Jogos</h2>
                <p class="text-muted mb-0">Cadastre, edite e exclua os jogos da Copa.</p>
            </div>
            <div>
                <a href="dashboard.php" class="btn btn-secondary">Voltar ao Dashboard</a>
                <a href="jogos_cadastrar.php" class="btn btn-success">Cadastrar Jogo</a>
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
                        <th>Time A</th>
                        <th>Time B</th>
                        <th>Data/Hora</th>
                        <th>Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($jogos): ?>
                        <?php foreach ($jogos as $jogo): ?>
                            <tr>
                                <td><?= (int) $jogo['id'] ?></td>
                                <td><strong><?= htmlspecialchars($jogo['time_a']) ?></strong></td>
                                <td><strong><?= htmlspecialchars($jogo['time_b']) ?></strong></td>
                                <td><?= date('d/m/Y H:i', strtotime($jogo['data_jogo'])) ?></td>
                                <td><span class="badge text-bg-primary"><?= htmlspecialchars(ucfirst($jogo['status'])) ?></span></td>
                                <td class="text-center">
                                    <a href="jogos_editar.php?id=<?= (int) $jogo['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                                    <form action="jogos_excluir.php" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este jogo?');">
                                        <input type="hidden" name="id" value="<?= (int) $jogo['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Nenhum jogo cadastrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
