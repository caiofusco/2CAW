<?php
session_start();
require_once '../config/db_connection.php';
require_once '../config/auth.php';

$auth = new Auth($conn);
$userData = $auth->checkAuth();

if (!$auth->hasPermission('aluno')) {
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

    <?php if (!empty($salas)): ?>
        <div class="cont-todos">
            <?php foreach ($salas as $sala): ?>
                <?php
                echo htmlspecialchars($sala['nome']);
                echo ' - ';
                echo htmlspecialchars($sala['localizacao']); ?>
                <div class="mt-2 mb-4 container-lab" sala-id="<?php echo $sala['id']; ?>">
                    <?php

                    foreach ($cadeiras as $cadeira) {

                        if ($cadeira['laboratorio_id'] == $sala['id']) {
                            $estado = htmlspecialchars($cadeira['estado']);
                            $corClasse = '';
                            if ($estado === 'disponível') {
                                $corClasse = 'verde';
                            } elseif ($estado === 'ocupado') {
                                $corClasse = 'vermelho';
                            } elseif ($estado === 'inoperante') {
                                $corClasse = 'cinza';
                            }
                            echo '<div class="lab mt-2 ' . $corClasse . '" 
          cadeira-id="' . $cadeira['id'] . '" 
          cadeira-estado="' . $estado . '" 
          cadeira-localizacao="' . htmlspecialchars($cadeira['nome'], ENT_QUOTES, 'UTF-8') . '">
          <span class="tooltip-custom">' . $estado . '</span>
      </div>';
                        }
                    }
                    ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Nenhuma sala encontrada.</p>
    <?php endif; ?>

    <!-- Modal para confirmar a cadeira -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmação de Reserva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Usuário: <?php echo htmlspecialchars($userData['nome']); ?></p>
                    <p>Laboratório: <span id="laboratorioNome"></span></p>

                    Você selecionou a cadeira com ID <span id="cadeiraId"></span>. Deseja confirmar esta ação?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmAction">Confirmar</button>
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
        // Captura o modal e elementos associados
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        const cadeiraIdSpan = document.getElementById('cadeiraId');
        const laboratorioNomeSpan = document.getElementById('laboratorioNome'); // Novo span para o nome do laboratório

        const confirmButton = document.getElementById('confirmAction');

        // Variável para armazenar o ID da cadeira selecionada
        let selectedCadeiraId = null;
        let selectedLaboratorioNome = null; // Novo campo para armazenar o nome do laboratório


        // Adiciona event listener para cliques nas divs com classe 'lab'
        document.addEventListener('click', function(event) {
            const target = event.target;

            // Verifica se o elemento clicado possui a classe 'lab'
            if (target.classList.contains('lab')) {
                const estado = target.getAttribute('cadeira-estado');


                if (estado === 'disponível') {
                    // Exibe o modal apenas para cadeiras disponíveis
                    selectedCadeiraId = target.getAttribute('cadeira-id');
                    selectedLaboratorioNome = target.getAttribute('cadeira-localizacao'); // Captura o nome do laboratório
                    cadeiraIdSpan.textContent = selectedCadeiraId;
                    laboratorioNomeSpan.textContent = selectedLaboratorioNome; // Atualiza o nome do laboratório no modal
                    modal.show();
                }
            }
        });

        document.addEventListener('mouseover', function(event) {
            const target = event.target;

            if (target.classList.contains('lab')) {
                const estado = target.getAttribute('cadeira-estado');
                const tooltip = target.querySelector('.tooltip-custom');
                if (tooltip) {
                    tooltip.textContent = estado.charAt(0).toUpperCase() + estado.slice(1); // Capitaliza o estado
                }

            }
        });

        // Evento para o botão "Confirmar" no modal
        confirmButton.addEventListener('click', function() {
            if (selectedCadeiraId) {
                // Envia o ID da cadeira para o servidor
                fetch('../config/update_cadeira.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `cadeira_id=${selectedCadeiraId}`,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log(data.message);
                            alert('Cadeira atualizada com sucesso!');
                            modal.hide();

                            // Atualiza o estado visual da cadeira na página
                            const cadeiraElement = document.querySelector(`.lab[cadeira-id="${selectedCadeiraId}"]`);
                            if (cadeiraElement) {
                                cadeiraElement.setAttribute('cadeira-estado', 'ocupado');
                                cadeiraElement.classList.remove('verde');
                                cadeiraElement.classList.add('vermelho');
                            }
                        } else {
                            console.error(data.message);
                            alert('Erro ao atualizar a cadeira.');
                        }
                    })
                    .catch(error => {
                        console.error('Erro na requisição:', error);
                        alert('Erro na comunicação com o servidor.');
                    });
            }
        });
    </script>

</body>
</html>