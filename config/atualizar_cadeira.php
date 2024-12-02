<?php
require_once 'db_connection.php';

// Define o cabeçalho para JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $laboratorioId = isset($input['laboratorio_id']) ? intval($input['laboratorio_id']) : null;

    if ($laboratorioId) {
        try {
            $sql = "UPDATE cadeira SET estado = 'ocupado' WHERE laboratorio_id = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("i", $laboratorioId);

                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Sala agendada com sucesso.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Erro ao agendar a sala.']);
                }

                $stmt->close();
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao preparar a consulta.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID do laboratório inválido.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
}

$conn->close();
?>
