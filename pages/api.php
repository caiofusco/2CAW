<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$host = 'localhost';
$dbname = 'lab_scheduling';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$route = $_GET['route'] ?? '';

switch ($route) {
    case 'seats':
        if ($method === 'GET') {
            $lab_id = $_GET['lab_id'] ?? null;
            if ($lab_id) {
                $stmt = $pdo->prepare("SELECT * FROM seats WHERE laboratory_id = ?");
                $stmt->execute([$lab_id]);
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
        }
        break;

    case 'bookings':
        if ($method === 'GET') {
            $date = $_GET['date'] ?? null;
            if ($date) {
                $stmt = $pdo->prepare("
                    SELECT b.*, s.seat_number, s.row_number, s.desk_number 
                    FROM bookings b 
                    JOIN seats s ON b.seat_id = s.id 
                    WHERE b.booking_date = ? AND b.status = 'active'
                ");
                $stmt->execute([$date]);
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
        } elseif ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Verificar se o assento está disponível
            $stmt = $pdo->prepare("
                SELECT id FROM bookings 
                WHERE seat_id = ? AND booking_date = ? AND status = 'active'
                AND ((start_time <= ? AND ADDTIME(start_time, SEC_TO_TIME(duration * 3600)) > ?)
                OR (start_time < ADDTIME(?, SEC_TO_TIME(? * 3600)) AND start_time >= ?))
            ");
            
            $stmt->execute([
                $data['seat_id'],
                $data['booking_date'],
                $data['start_time'],
                $data['start_time'],
                $data['start_time'],
                $data['duration'],
                $data['start_time']
            ]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(['error' => 'Horário indisponível']);
                exit;
            }

            // Criar o agendamento
            $stmt = $pdo->prepare("
                INSERT INTO bookings (user_id, seat_id, booking_date, start_time, duration)
                VALUES (?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $data['user_id'],
                $data['seat_id'],
                $data['booking_date'],
                $data['start_time'],
                $data['duration']
            ]);

            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
        }
        break;
}
?>