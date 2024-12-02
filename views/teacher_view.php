<?php
session_start();
require_once '../config/db_connection.php';
require_once '../config/auth.php';

$auth = new Auth($conn);
$userData = $auth->checkAuth();

if (!$auth->hasPermission('professor')) {
    header("Location: ../error.php");
    exit();
}

$salas = $auth->getLaboratorios();
$cadeiras = $auth->getCadeiras();


?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de assentos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="../assets/css/main_style.css">
    <link rel="stylesheet" href="../assets/css/agenda.css">
</head>

<style>
    .container-img {
        width: 180px;
        height: 230px;
    }

    .container-img img {
        width: 100%;
        height: 100%;
        border-radius: 8px;
    }

    .container-cards-salas {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 20px 0;
        padding: 20px;
        border-radius: 8px;
        background-color: #1E1E1E;
    }
</style>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <img src="../assets/img/LOGO.png" alt="Logo Principal">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Agendamentos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Sobre nós</a>
                    </li>
                </ul>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="d-flex gap-2">
                        <li class="nav-item-logado">
                            <span class="nav-link">Bem-vindo, <?php echo htmlspecialchars($userData['nome']); ?></span>
                        </li>
                        <li class="nav-item-logado">
                            <a class="btn btn-danger" href="desconectar.php">SAIR</a>
                        </li>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <h1 class="mt-4 titulo">Salas Disponíveis</h1>
    <div class="accordion" id="accordionPanelsStayOpenExample">
    <?php if (!empty($salas)): ?>
        <?php foreach ($salas as $index => $sala): ?>
            <?php
            // Verificar se a sala está disponível ou agendada
            $sql = "SELECT COUNT(*) as count FROM cadeira WHERE estado <> 'ocupado' AND laboratorio_id = ?";
            $stmt = $conn->prepare($sql);
            $isAvailable = true; // Padrão: disponível

            if ($stmt) {
                $stmt->bind_param("i", $sala['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $data = $result->fetch_assoc();

                if ($data['count'] == 0) {
                    $isAvailable = false; // Sala agendada
                }
                $stmt->close();
            }
            ?>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#panelsStayOpen-collapse<?php echo $index; ?>" 
                        aria-expanded="false" 
                        aria-controls="panelsStayOpen-collapse<?php echo $index; ?>">
                        <?php echo htmlspecialchars($sala['nome']); ?>
                        <span class="badge ms-2 <?php echo $isAvailable ? 'bg-success' : 'bg-danger'; ?>">
                            <?php echo $isAvailable ? 'Disponível' : 'Agendada'; ?>
                        </span>
                    </button>
                </h2>
                <div id="panelsStayOpen-collapse<?php echo $index; ?>" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        <p>Localização: <?php echo htmlspecialchars($sala['localizacao']); ?></p>
                        <p>Capacidade: 30 pessoas</p>
                        <p>Horário de funcionamento: 07:30 às 22:00</p>
                        <?php if ($isAvailable): ?>
                            <button class="btn btn-primary" data-bs-toggle="modal" 
                                data-bs-target="#confirmModal" 
                                data-nome="<?php echo htmlspecialchars($sala['nome']); ?>" 
                                data-localizacao="<?php echo htmlspecialchars($sala['localizacao']); ?>" 
                                data-laboratorio-id="<?php echo $sala['id']; ?>">
                                Reservar
                            </button>
                        <?php else: ?>
                            <button class="btn btn-secondary" disabled>Indisponível</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nenhuma sala encontrada.</p>
    <?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirmar Agendamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza de que deseja agendar esta sala?</p>
                <p><strong>Nome:</strong> <span id="modalSalaNome"></span></p>
                <p><strong>Localização:</strong> <span id="modalSalaLocalizacao"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="confirmReservation" class="btn btn-primary">Confirmar</button>
            </div>
        </div>
    </div>
</div>

    <!-- Footer -->
    <footer class="py-4 text-center">
        <div class="container">
            <p class="mb-1">2CAW - Trabalho de Extensão</p>
            <p class="mb-1">FAETERJ-RIO</p>
            <p class="mb-0">&copy; 2024. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const confirmModal = document.getElementById('confirmModal');
        const modalSalaNome = document.getElementById('modalSalaNome');
        const modalSalaLocalizacao = document.getElementById('modalSalaLocalizacao');
        let laboratorioId = null;

        // Adiciona os dados da sala ao modal
        confirmModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const nome = button.getAttribute('data-nome');
            const localizacao = button.getAttribute('data-localizacao');
            laboratorioId = button.getAttribute('data-laboratorio-id');

            modalSalaNome.textContent = nome;
            modalSalaLocalizacao.textContent = localizacao;
        });

        // Envia a requisição para atualizar o banco de dados
        const confirmButton = document.getElementById('confirmReservation');
        confirmButton.addEventListener('click', function () {
            if (laboratorioId) {
                fetch('../config/atualizar_cadeira.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ laboratorio_id: laboratorioId }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload(); // Recarrega a página para atualizar o estado
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Erro ao processar o agendamento.');
                    });
            } else {
                alert('ID do laboratório não encontrado.');
            }
        });
    });
</script>

</body>

</html>