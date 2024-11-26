<?php
session_start();
require_once '../config/db_connection.php';
require_once '../config/auth.php';

$auth = new Auth($conn);
$userData = $auth->checkAuth();

if (!$auth->hasPermission('admin')) {
    header("Location: ../error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- [Headers anteriores permanecem iguais] -->
</head>
<body>
    <!-- Versão do admin - Controle total -->
    <main class="container my-4">
        <h2 class="section-title">Gerenciamento de Laboratórios</h2>
        <!-- [Interface completa com opções de edição] -->
    </main>
</body>
</html>