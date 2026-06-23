<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    if ($id === (int) $_SESSION['user_id']) {
        die('Você não pode excluir o próprio usuário enquanto está logado.');
    }

    try {
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        header('Location: usuarios_listar.php?msg=excluido');
        exit;
    } catch (PDOException $e) {
        die('Erro ao excluir o usuário: ' . $e->getMessage());
    }
}

header('Location: usuarios_listar.php');
exit;
