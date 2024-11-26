<?php
class Auth {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login($email, $password) {
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['senha'])) {
                $_SESSION['user_id'] = $user['id'];
                return true;
            }
        }
        return false;
    }

    public function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }

        return $this->getUserData($_SESSION['user_id']);
    }

    public function getUserData($userId) {
        $sql = "SELECT id, nome, tipo_usuario FROM usuarios WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getLaboratorios() {
        $sql = "SELECT * FROM laboratorio";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function hasPermission($requiredType) {
        $userData = $this->getUserData($_SESSION['user_id']);
        if ($userData['tipo_usuario'] === 'admin') {
            return true; // Admin tem todas as permissões
        }
        return $userData['tipo_usuario'] === $requiredType;
    }

    public function redirectBasedOnType() {
        $userData = $this->getUserData($_SESSION['user_id']);

        switch ($userData['tipo_usuario']) {
            case 'aluno':
                header("Location: student_view.php");
                break;
            case 'professor':
                header("Location: teacher_view.php");
                break;
            case 'admin':
                header("Location: admin_view.php");
                break;
            default:
                header("Location: error.php");
        }
        exit();
    }
}
?>
