<?php
$servername = "localhost"; // Host do banco de dados
$username = "admin";  // Usuário do banco de dados
$password = "admin";    // Senha do banco de dados
$dbname = "db_laboratorios"; // Nome do banco de dados

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
