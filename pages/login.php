<?php
session_start(); // Inicia a sessão

// Inclui a conexão com o banco de dados
require_once '../config/db_connection.php'; // Ajuste o caminho se necessário

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtem os dados do formulário
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verifica se o usuário existe
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // O usuário existe, agora vamos verificar a senha
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['senha'])) { // Use password_verify se a senha estiver criptografada
            // Senha correta, inicia a sessão do usuário
            $_SESSION['user_id'] = $row['id']; // Supondo que a tabela tenha uma coluna 'id'
            $_SESSION['user_email'] = $email;
            header("Location: agendamentos.php"); // Redireciona para a página de dashboard ou outra página
            exit();
        } else {
            // Senha incorreta
            echo "<script>alert('Senha incorreta!'); window.location.href='login.php';</script>";
        }
    } else {
        // Usuário não encontrado
        echo "<script>alert('Usuário não encontrado!'); window.location.href='login.php';</script>";
    }

    $stmt->close();
}

$conn->close(); // Fecha a conexão
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Agendamento de Laboratórios</title>
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
                <h2 class="auth-title">Login</h2>
                
                <form class="auth-form" method="POST" action="login.php">
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                        <div class="form-text text-end">
                            <a href="#" class="text-decoration-none">Esqueceu a senha?</a>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-3">Entrar</button>
                    
                    <div class="text-center">
                        <span>Não tem uma conta? </span>
                        <a href="cadastro.php" class="text-decoration-none">Cadastre-se</a>
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
