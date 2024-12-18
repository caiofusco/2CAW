<?php
session_start();

require_once './config/db_connection.php';

// Verifica se o usuário está logado e se o ID do usuário está na sessão
if (isset($_SESSION['user_id'])) {
    // ID do usuário da sessão
    $userId = $_SESSION['user_id'];
    
    // Consulta para buscar o nome do usuário no banco de dados
    $sql = "SELECT nome FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($userName);
    
    // Verifica se a consulta obteve um resultado
    if ($stmt->fetch()) {
        // Armazena o nome do usuário na sessão para uso futuro, se necessário
        $_SESSION['user_name'] = $userName;
    } else {
        // Caso não encontre o usuário, encerra a sessão como precaução
        session_unset();
        session_destroy();
        header("Location: pages/login.php");
        exit;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento de Laboratórios</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/main_style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="assets/img/LOGO.png" alt="Logo Principal">
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
                        <a class="nav-link" href="pages/agendamentos.php">Agendamentos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Sobre nós</a>
                    </li>
                </ul>
                <?php if (!isset($_SESSION['user_id'])): ?>
                <!-- Se o usuário NÃO estiver logado, mostra os botões de Login e Cadastro -->
                <div class="d-flex gap-2">
                    <a href="views/login.php" class="btn btn-primary">Login</a>
                    <a href="views/cadastro.php" class="btn btn-success">Cadastro</a>
                </div>
                <?php else: ?>
                <!-- Se o usuário estiver logado, mostra um botão de Logout e a saudação -->
                <div class="d-flex gap-2">
                    <li class="nav-item-logado">
                        <span class="nav-link">Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    </li>
                    <li class="nav-item-logado">
                        <a class="btn btn-danger" href="views/desconectar.php">SAIR</a>
                    </li>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container">
        <!-- Labs Section -->
        <h2 class="section-title">Conheça nossos Laboratórios</h2>

        <!-- Carousel -->
        <div id="mainCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="assets/img/carousel-img-01.png" class="d-block w-100" alt="Laboratório 1">
                    <div class="carousel-caption">
                        <h5>Laboratórios Modernos e Equipados</h5>
                        <p>Nossos laboratórios estão equipados com computadores de última geração.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="assets/img/carousel-img-02.png" class="d-block w-100" alt="Laboratório 2">
                    <div class="carousel-caption">
                        <h5>Espaço Perfeito para Aulas</h5>
                        <p>Salas ideais para cursos práticos e treinamentos técnicos.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="assets/img/carousel-img-03.png" class="d-block w-100" alt="Laboratório 3">
                    <div class="carousel-caption">
                        <h5>Infraestrutura Completa</h5>
                        <p>Espaços climatizados com Wi-Fi e tecnologia atualizada.</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
                <span class="visually-hidden">Próximo</span>
            </button>
        </div>

        <!-- Featured Labs -->
        <h2 class="section-title">Laboratórios em Destaque</h2>
        
        <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/card-img-01.png" class="card-img-top" alt="Aluguel">
                    <div class="card-body">
                        <h5 class="card-title">Aluguel Simples e Rápido</h5>
                        <p class="card-text">Alugue salas de laboratório de forma prática e rápida.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/card-img-02.png" class="card-img-top" alt="Suporte">
                    <div class="card-body">
                        <h5 class="card-title">Suporte Técnico Dedicado</h5>
                        <p class="card-text">Nossa equipe técnica está à disposição para ajudar.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/card-img-03.png" class="card-img-top" alt="Localização">
                    <div class="card-body">
                        <h5 class="card-title">Localização Conveniente</h5>
                        <p class="card-text">Aproveite a proximidade e comodidade dos laboratórios.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mb-5">
            <a href="#" class="btn btn-primary btn-lg">VER TODOS LABORATÓRIOS →</a>
        </div>

        <!-- Location -->
        <h2 class="section-title">Nossa localidade</h2>
        
        <div class="location-container">
            <img src="assets/img/img-localidade.jpg" alt="Localização">
            <div class="location-overlay">
                <h5 class="mb-2">SÃO JOÃO DE MERITI</h5>
                <p class="mb-0">RUA XXX, XX, 291.</p>
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