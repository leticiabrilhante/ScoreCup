<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/db.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $time_a = trim($_POST['time_a'] ?? '');
    $time_b = trim($_POST['time_b'] ?? '');
    $data_jogo = $_POST['data_jogo'] ?? '';
    $status = $_POST['status'] ?? 'pendente';

    if ($time_a !== '' && $time_b !== '' && $data_jogo !== '') {
        try {
            $stmt = $pdo->prepare('INSERT INTO jogos (time_a, time_b, data_jogo, status) VALUES (:time_a, :time_b, :data_jogo, :status)');
            $stmt->execute([
                ':time_a' => $time_a,
                ':time_b' => $time_b,
                ':data_jogo' => $data_jogo,
                ':status' => $status,
            ]);
            header('Location: jogos_listar.php?msg=cadastrado');
            exit;
        } catch (PDOException $e) {
            $mensagem = 'Erro ao salvar no banco: ' . $e->getMessage();
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
    <title>ScoreCup - Cadastrar Jogo</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-7">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">Cadastrar Novo Jogo</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($mensagem): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($mensagem) ?></div>
                        <?php endif; ?>

                        <form action="jogos_cadastrar.php" method="POST">
                            <div class="mb-3">
                                <label for="time_a" class="form-label">Time da Casa (A)</label>
                                <input type="text" class="form-control" id="time_a" name="time_a" required>
                            </div>
                            <div class="mb-3">
                                <label for="time_b" class="form-label">Time Visitante (B)</label>
                                <input type="text" class="form-control" id="time_b" name="time_b" required>
                            </div>
                            <div class="mb-3">
                                <label for="data_jogo" class="form-label">Data e Horário do Jogo</label>
                                <input type="datetime-local" class="form-control" id="data_jogo" name="data_jogo" required>
                            </div>
                            <div class="mb-4">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="pendente">Pendente</option>
                                    <option value="finalizado">Finalizado</option>
                                    <option value="cancelado">Cancelado</option>
                                </select>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="jogos_listar.php" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-success">Salvar Jogo</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
