<?php
session_start();
require_once '../config/db_connection.php';
require_once '../config/auth.php';

$auth = new Auth($conn);
$userData = $auth->checkAuth();

if (!$auth->hasPermission('admin')) {
    header("Location: ../error.php");
    exit();
}
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
    <main class="container my-4">
        <h2 class="section-title">Agendamento de Laboratórios</h2>

        <!-- Seleção de Data e Hora -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="lab-info">
                    <h5>Selecione a Data</h5>
                    <input type="text" id="datePicker" class="form-control bg-dark text-white" placeholder="Selecione a data">
                </div>
            </div>
            <div class="col-md-6">
                <div class="lab-info">
                    <h5>Duração do Agendamento</h5>
                    <select id="duration" class="form-select bg-dark text-white">
                        <option value="1">1 hora</option>
                        <option value="2">2 horas</option>
                        <option value="3">3 horas</option>
                        <option value="4">4 horas</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Legenda -->
        <div class="lab-info mb-4">
            <h5>Legenda</h5>
            <div class="d-flex flex-wrap">
                <div class="legend-item">
                    <div class="legend-color bg-success"></div>
                    <span>Disponível</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color bg-danger"></div>
                    <span>Ocupado</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color bg-secondary"></div>
                    <span>Inoperante</span>
                </div>
            </div>
        </div>

        <!-- Laboratories -->
        <div class="row">
            <!-- Lab 1 -->
            <div class="col-12 mb-4">
                <div class="card lab-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Laboratório 1</h5>
                            <small class="text-muted">Horário: 08:00 - 22:00</small>
                        </div>
                        <div class="lab-layout">
                            <div class="screen"></div>
                            <div class="instructor-desk">Mesa do Professor</div>
                            <div id="lab1Layout"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lab 2 -->
            <div class="col-12 mb-4">
                <div class="card lab-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Laboratório 2</h5>
                            <small class="text-muted">Horário: 07:00 - 18:00</small>
                        </div>
                        <div class="lab-layout">
                            <div class="screen"></div>
                            <div class="instructor-desk">Mesa do Professor</div>
                            <div id="lab2Layout"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div class="modal fade" id="confirmationModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmar Agendamento</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Deseja confirmar o agendamento?</p>
                        <p id="bookingDetails"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success" id="confirmBooking">Confirmar</button>
                    </div>
                </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize date picker
        flatpickr("#datePicker", {
            minDate: "today",
            dateFormat: "Y-m-d",
        });
    
        // Lab configurations
        const labs = {
            lab1: {
                rows: 4,
                seatsPerDesk: 2,
                desksPerRow: 3,
                hours: { start: 8, end: 22 }
            },
            lab2: {
                rows: 3,
                seatsPerDesk: 2,
                desksPerRow: 3,
                hours: { start: 7, end: 18 }
            }
        };
    
        // Generate lab layout
        function generateLabLayout(labId, config) {
            const container = document.getElementById(`${labId}Layout`);
            let seatCount = 0;
    
            for (let row = 0; row < config.rows; row++) {
                const deskRow = document.createElement('div');
                deskRow.className = 'desk-row';
    
                for (let desk = 0; desk < config.desksPerRow; desk++) {
                    const deskElement = document.createElement('div');
                    deskElement.className = 'desk';
    
                    for (let seat = 0; seat < config.seatsPerDesk; seat++) {
                        seatCount++;
                        const seatElement = document.createElement('div');
                        seatElement.className = 'seat available';
                        seatElement.textContent = seatCount;
                        
                        // Add tooltip with position information
                        seatElement.setAttribute('data-tooltip', `Fila ${row + 1}, Mesa ${desk + 1}, Assento ${seat + 1}`);
    
                        // Randomly set some seats as occupied or inoperative for demonstration
                        const random = Math.random();
                        if (random < 0.2) {
                            seatElement.className = 'seat occupied';
                        } else if (random < 0.3) {
                            seatElement.className = 'seat inoperative';
                        }
    
                        seatElement.addEventListener('click', () => handleSeatClick(labId, seatCount, seatElement));
                        deskElement.appendChild(seatElement);
                    }
    
                    deskRow.appendChild(deskElement);
                }
    
                container.appendChild(deskRow);
            }
        }
    
        // Handle seat selection
        function handleSeatClick(labId, seatNumber, seatElement) {
            if (seatElement.classList.contains('available')) {
                const date = document.getElementById('datePicker').value;
                const duration = document.getElementById('duration').value;
                
                if (!date) {
                    alert('Por favor, selecione uma data primeiro.');
                    return;
                }
    
                const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                document.getElementById('bookingDetails').innerHTML = `
                    Laboratório: ${labId.toUpperCase()}<br>
                    Cadeira: ${seatNumber}<br>
                    Posição: ${seatElement.getAttribute('data-tooltip')}<br>
                    Data: ${date}<br>
                    Duração: ${duration} hora(s)
                `;
                modal.show();
    
                document.getElementById('confirmBooking').onclick = () => {
                    seatElement.className = 'seat occupied';
                    modal.hide();
                    alert('Agendamento confirmado com sucesso!');
                };
            }
        }
    
        // Initialize labs
        generateLabLayout('lab1', labs.lab1);
        generateLabLayout('lab2', labs.lab2);

        // Helper functions
        function formatTimeSlot(hour) {
            return `${hour.toString().padStart(2, '0')}:00`;
        }

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Responsive adjustments
        function adjustLayoutForScreenSize() {
            const labLayouts = document.querySelectorAll('.lab-layout');
            if (window.innerWidth < 768) {
                labLayouts.forEach(layout => {
                    layout.style.transform = 'scale(0.8)';
                });
            } else {
                labLayouts.forEach(layout => {
                    layout.style.transform = 'scale(1)';
                });
            }
        }

        // Event listeners
        window.addEventListener('resize', adjustLayoutForScreenSize);
        window.addEventListener('load', adjustLayoutForScreenSize);

        // Date validation
        document.getElementById('datePicker').addEventListener('change', function(e) {
            const selectedDate = new Date(e.target.value);
            const today = new Date();
            
            if (selectedDate < today) {
                alert('Por favor, selecione uma data futura.');
                e.target.value = '';
            }
        });

        // Save booking data to localStorage
        function saveBooking(bookingData) {
            let bookings = JSON.parse(localStorage.getItem('labBookings')) || [];
            bookings.push(bookingData);
            localStorage.setItem('labBookings', JSON.stringify(bookings));
        }

        // Check if seat is already booked
        function isSeatBooked(labId, seatNumber, date) {
            const bookings = JSON.parse(localStorage.getItem('labBookings')) || [];
            return bookings.some(booking => 
                booking.labId === labId && 
                booking.seatNumber === seatNumber && 
                booking.date === date
            );
        }

        // Error handling
        window.addEventListener('error', function(e) {
            console.error('Erro na aplicação:', e.message);
            alert('Ocorreu um erro. Por favor, tente novamente mais tarde.');
        });

        // Clean up old bookings
        function cleanupOldBookings() {
            let bookings = JSON.parse(localStorage.getItem('labBookings')) || [];
            const today = new Date();
            bookings = bookings.filter(booking => new Date(booking.date) >= today);
            localStorage.setItem('labBookings', JSON.stringify(bookings));
        }

        // Run cleanup on page load
        cleanupOldBookings();

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = bootstrap.Modal.getInstance(document.getElementById('confirmationModal'));
                if (modal) modal.hide();
            }
        });

        // Mobile detection
        function isMobile() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        }

        // Adjust UI for mobile devices
        if (isMobile()) {
            document.querySelectorAll('.desk-row').forEach(row => {
                row.style.gap = '1rem';
            });
        }
    </script>
</body>
</html>