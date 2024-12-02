<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cadeiraId = isset($_POST['cadeira_id']) ? intval($_POST['cadeira_id']) : null;

    if ($cadeiraId) {
        try {
            // Prepara a consulta
            $sql = "UPDATE cadeira SET estado = 'ocupado' WHERE id = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                // Vincula o parâmetro
                $stmt->bind_param("i", $cadeiraId);

                // Executa a consulta
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Cadeira atualizada com sucesso.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar a cadeira.']);
                }

                // Fecha a declaração preparada
                $stmt->close();
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao preparar a consulta.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID da cadeira inválido.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
}

// Fecha a conexão com o banco de dados
$conn->close();