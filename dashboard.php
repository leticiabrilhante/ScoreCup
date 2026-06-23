<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/db.php';

$totalJogos = (int) $pdo->query('SELECT COUNT(*) FROM jogos')->fetchColumn();
$totalPalpites = (int) $pdo->query('SELECT COUNT(*) FROM palpites')->fetchColumn();
$totalUsuarios = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();

$ranking = $pdo->query('
    SELECT
        u.nome,
        COALESCE(SUM(p.pontos), 0) AS pontos
    FROM users u
    LEFT JOIN palpites p ON p.user_id = u.id
    GROUP BY u.id, u.nome
    ORDER BY pontos DESC, u.nome ASC
    LIMIT 5
')->fetchAll();

$proximosJogos = $pdo->query("\n    SELECT id, time_a, time_b, data_jogo, status\n    FROM jogos\n    WHERE status = 'pendente'\n    ORDER BY data_jogo ASC\n    LIMIT 5\n")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScoreCup - Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <main class="container dashboard-container py-4">
        <header class="dashboard-header mb-4">
            <div>
                <p class="eyebrow mb-1">ScoreCup</p>
                <h1 class="mb-1">Bem-vindo, <?= htmlspecialchars($_SESSION['nome'] ?? 'Usuário') ?> 👋</h1>
                <p class="text-muted mb-0">Acompanhe as estatísticas da Copa e gerencie o sistema.</p>
            </div>
            <div class="dashboard-actions">
                <a href="jogos_listar.php" class="btn btn-primary">Gerenciar Jogos</a>
                <a href="usuarios_listar.php" class="btn btn-outline-primary">Usuários</a>
                <a href="logout.php" class="btn btn-danger">Sair</a>
            </div>
        </header>

        <section class="row g-3">
            <div class="col-md-4">
                <div class="stat-card">
                    <span>Jogos cadastrados</span>
                    <strong><?= $totalJogos ?></strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <span>Palpites realizados</span>
                    <strong><?= $totalPalpites ?></strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <span>Usuários</span>
                    <strong><?= $totalUsuarios ?></strong>
                </div>
            </div>
        </section>

        <section class="row g-4 mt-2">
            <div class="col-lg-7">
                <div class="content-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="section-title mb-0">⚽ Próximos Jogos</h2>
                        <a href="jogos_cadastrar.php" class="btn btn-sm btn-success">Novo jogo</a>
                    </div>

                    <?php if ($proximosJogos): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($proximosJogos as $jogo): ?>
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span>
                                        <strong><?= htmlspecialchars($jogo['time_a']) ?></strong>
                                        <span class="text-muted">vs</span>
                                        <strong><?= htmlspecialchars($jogo['time_b']) ?></strong>
                                    </span>
                                    <span class="badge text-bg-light">
                                        <?= date('d/m/Y H:i', strtotime($jogo['data_jogo'])) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">Nenhum jogo pendente cadastrado.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="content-card">
                    <h2 class="section-title">🏆 Ranking</h2>
                    <?php if ($ranking): ?>
                        <ol class="ranking-list">
                            <?php foreach ($ranking as $user): ?>
                                <li>
                                    <span><?= htmlspecialchars($user['nome']) ?></span>
                                    <strong><?= (int) $user['pontos'] ?> pts</strong>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    <?php else: ?>
                        <p class="text-muted mb-0">Nenhum ranking disponível.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
