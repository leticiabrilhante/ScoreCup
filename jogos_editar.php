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
    header('Location: jogos_listar.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id, time_a, time_b, data_jogo, status FROM jogos WHERE id = :id');
$stmt->execute([':id' => $id]);
$jogo = $stmt->fetch();

if (!$jogo) {
    header('Location: jogos_listar.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $time_a = trim($_POST['time_a'] ?? '');
    $time_b = trim($_POST['time_b'] ?? '');
    $data_jogo = $_POST['data_jogo'] ?? '';
    $status = $_POST['status'] ?? 'pendente';

    if ($time_a !== '' && $time_b !== '' && $data_jogo !== '') {
        try {
            $stmt = $pdo->prepare('UPDATE jogos SET time_a = :time_a, time_b = :time_b, data_jogo = :data_jogo, status = :status WHERE id = :id');
            $stmt->execute([
                ':time_a' => $time_a,
                ':time_b' => $time_b,
                ':data_jogo' => $data_jogo,
                ':status' => $status,
                ':id' => $id,
            ]);
            header('Location: jogos_listar.php?msg=editado');
            exit;
        } catch (PDOException $e) {
            $mensagem = 'Erro ao atualizar no banco: ' . $e->getMessage();
        }
    } else {
        $mensagem = 'Por favor, preencha todos os campos obrigatórios.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScoreCup - Editar Jogo</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-7">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">Editar Jogo #<?= (int) $jogo['id'] ?></h4>
                    </div>
                    <div class="card-body">
                        <?php if ($mensagem): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($mensagem) ?></div>
                        <?php endif; ?>

                        <form action="jogos_editar.php?id=<?= (int) $jogo['id'] ?>" method="POST">
                            <div class="mb-3">
                                <label for="time_a" class="form-label">Time da Casa (A)</label>
                                <input type="text" class="form-control" id="time_a" name="time_a" value="<?= htmlspecialchars($jogo['time_a']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="time_b" class="form-label">Time Visitante (B)</label>
                                <input type="text" class="form-control" id="time_b" name="time_b" value="<?= htmlspecialchars($jogo['time_b']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="data_jogo" class="form-label">Data e Horário do Jogo</label>
                                <input type="datetime-local" class="form-control" id="data_jogo" name="data_jogo" value="<?= date('Y-m-d\TH:i', strtotime($jogo['data_jogo'])) ?>" required>
                            </div>
                            <div class="mb-4">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <?php foreach (['pendente' => 'Pendente', 'finalizado' => 'Finalizado', 'cancelado' => 'Cancelado'] as $valor => $rotulo): ?>
                                        <option value="<?= $valor ?>" <?= $jogo['status'] === $valor ? 'selected' : '' ?>><?= $rotulo ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="jogos_listar.php" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-warning">Atualizar Jogo</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
