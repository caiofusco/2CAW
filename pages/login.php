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
            header("Location: agendamentos.html"); // Redireciona para a página de dashboard ou outra página
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
