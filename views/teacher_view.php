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
    <style>
        :root {
            --bs-body-font-family: 'Poppins', sans-serif;
            --bs-body-bg: #111111;
            --bs-body-color: #fff;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: #1E1E1E !important;
            padding: 1rem;
        }

        .navbar-brand img {
            width: 90px;
            height: 25px;
        }

        .navbar-nav .nav-link {
            color: var(--bs-body-color) !important;
            padding: 0.5rem 1rem;
        }

        .section-title {
            position: relative;
            margin: 2rem 0;
            text-align: center;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 40%;
            height: 2px;
            background-color: #fff;
            margin: 1rem auto;
        }

        .lab-card {
            background-color: #1E1E1E;
            border: none;
            margin-bottom: 1rem;
        }

        .lab-layout {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
            position: relative;
        }

        .instructor-desk {
            width: 120px;
            height: 40px;
            background-color: #444;
            margin-bottom: 3rem;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }

        .desk-row {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1.5rem;
            width: 100%;
        }

        .desk {
            background-color: #333;
            padding: 10px;
            border-radius: 5px;
            display: flex;
            gap: 10px;
        }

        .seat {
            width: 35px;
            height: 35px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            position: relative;
        }

        .seat.available {
            background-color: #198754;
        }

        .seat.occupied {
            background-color: #dc3545;
            cursor: not-allowed;
        }

        .seat.inoperative {
            background-color: #6c757d;
            cursor: not-allowed;
        }

        .seat:hover.available {
            transform: scale(1.1);
        }

        .seat::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            padding: 4px 8px;
            background-color: rgba(0, 0, 0, 0.8);
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .seat:hover::after {
            visibility: visible;
            opacity: 1;
        }

        .lab-info {
            background-color: #1E1E1E;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .screen {
            width: 80%;
            height: 5px;
            background: linear-gradient(to right, transparent, #fff, transparent);
            margin-bottom: 3rem;
            position: relative;
        }

        .screen::after {
            content: 'QUADRO';
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.8rem;
            color: #888;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-right: 1rem;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 3px;
            margin-right: 0.5rem;
        }

        footer {
            background-color: #1E1E1E;
            margin-top: auto;
        }
    </style>

    <link rel="stylesheet" href="../assets/css/agenda.css">

<style>
    .seat {
      width: 50px;
      height: 50px;
      margin: 5px;
      border: 2px solid #6c757d;
      border-radius: 5px;
      text-align: center;
      line-height: 50px;
      cursor: pointer;
    }
    .seat.selected {
      background-color: #28a745;
      color: white;
    }
    .seat.occupied {
      background-color: #dc3545;
      color: white;
      cursor: not-allowed;
    }
    .screen {
      background: #333;
      color: white;
      text-align: center;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 20px;
    }
  </style>
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
    <h1>Salas Disponíveis</h1>
    <?php if (!empty($salas)): ?>
        <div class="cont-todos">
            <?php foreach ($salas as $sala): ?>
            <div class="mt-5 container-lab">
            <?php

                for ($i = 1; $i <= $sala['qtd_cadeiras']; $i++) {
                    
                    echo '<div class="lab mt-2"></div>';
                }
            ?><?php echo htmlspecialchars($sala['qtd_cadeiras']); ?>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Nenhuma sala encontrada.</p>
    <?php endif; ?>

    <div class="container mt-5">
   
    

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














    
</body>
</html>