<?php
session_start();
require_once '../config/db_connection.php'; // Inclui o arquivo de configuração

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Coleta os dados do formulário
    $nome = $_POST['name'];
    $email = $_POST['email'];
    $matricula = $_POST['registration'];
    $senha = $_POST['password'];
    $confirmar_senha = $_POST['confirm-password'];

    // Verifica se as senhas coincidem
    if ($senha !== $confirmar_senha) {
        echo "<script>alert('As senhas não coincidem!');</script>";
    } else {
        // Verifica se o e-mail já está cadastrado
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>alert('E-mail já cadastrado!');</script>";
        } else {
            // Faz o hash da senha
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            // Insere o usuário no banco de dados
            $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, matricula, senha, tipo_usuario) VALUES (?, ?, ?, ?, 'aluno')");
            $stmt->bind_param("ssss", $nome, $email, $matricula, $senha_hash);

            if ($stmt->execute()) {
                echo "<script>alert('Usuário cadastrado com sucesso!');</script>";
            } else {
                echo "<script>alert('Erro ao cadastrar usuário: " . $stmt->error . "');</script>";
            }
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Agendamento de Laboratórios</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/main_style.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <img src="../assets/img/LOGO.png" alt="Logo Principal">
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container">
        <div class="auth-container">
            <div class="auth-card">
                <h2 class="auth-title">Cadastro</h2>
                
                <form class="auth-form" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome completo</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="registration" class="form-label">Matrícula</label>
                        <input type="text" class="form-control" id="registration" name="registration" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="mb-4">
                        <label for="confirm-password" class="form-label">Confirmar senha</label>
                        <input type="password" class="form-control" id="confirm-password" name="confirm-password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100 mb-3">Cadastrar</button>
                    
                    <div class="text-center">
                        <span>Já tem uma conta? </span>
                        <a href="login.php" class="text-decoration-none">Faça login</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-4 text-center">
        <div class="container">
            <p class="mb-1">2CAW - Trabalho de Extensão</p>
            <p class="mb-1">FAETERJ-RIO</p>
            <p class="mb-0">&copy; 2024. Todos os direitos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>