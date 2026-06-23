<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/db.php';

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id) {
    try {
        $stmt = $pdo->prepare('DELETE FROM jogos WHERE id = :id');
        $stmt->execute([':id' => $id]);
        header('Location: jogos_listar.php?msg=excluido');
        exit;
    } catch (PDOException $e) {
        die('Erro ao excluir o jogo: ' . $e->getMessage());
    }
}

header('Location: jogos_listar.php');
exit;
