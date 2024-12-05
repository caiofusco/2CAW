-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04/12/2024 às 21:52
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_laboratorios`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cadeira`
--

CREATE TABLE `cadeira` (
  `id` int(11) NOT NULL,
  `laboratorio_id` int(11) NOT NULL,
  `estado` enum('ocupado','disponível','inoperante') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cadeira`
--

INSERT INTO `cadeira` (`id`, `laboratorio_id`, `estado`) VALUES
(14, 1, 'ocupado'),
(15, 1, 'ocupado'),
(16, 1, 'ocupado'),
(17, 1, 'ocupado'),
(18, 1, 'ocupado'),
(19, 1, 'ocupado'),
(20, 1, 'ocupado'),
(21, 1, 'ocupado'),
(22, 1, 'ocupado'),
(23, 1, 'ocupado'),
(24, 2, 'ocupado'),
(25, 2, 'ocupado'),
(26, 2, 'ocupado'),
(27, 2, 'ocupado'),
(28, 2, 'ocupado'),
(29, 2, 'ocupado'),
(30, 2, 'ocupado'),
(31, 2, 'ocupado'),
(32, 2, 'ocupado'),
(33, 2, 'ocupado'),
(34, 2, 'ocupado'),
(35, 2, 'ocupado'),
(36, 2, 'ocupado'),
(37, 2, 'ocupado'),
(38, 2, 'ocupado'),
(39, 3, 'ocupado'),
(40, 3, 'ocupado'),
(41, 3, 'ocupado'),
(42, 3, 'ocupado'),
(43, 3, 'ocupado'),
(44, 3, 'ocupado'),
(45, 3, 'ocupado'),
(46, 3, 'ocupado'),
(47, 3, 'ocupado'),
(48, 3, 'ocupado'),
(49, 3, 'ocupado'),
(50, 3, 'ocupado'),
(51, 3, 'ocupado'),
(52, 3, 'ocupado'),
(53, 3, 'ocupado'),
(54, 3, 'ocupado'),
(55, 3, 'ocupado'),
(56, 3, 'ocupado'),
(57, 3, 'ocupado'),
(58, 3, 'ocupado'),
(59, 4, 'disponível'),
(60, 4, 'ocupado'),
(61, 4, 'disponível'),
(62, 4, 'inoperante'),
(63, 4, 'disponível'),
(64, 4, 'ocupado'),
(65, 4, 'disponível'),
(66, 4, 'inoperante'),
(67, 4, 'ocupado'),
(68, 4, 'disponível'),
(69, 5, 'ocupado'),
(70, 5, 'ocupado'),
(71, 5, 'disponível'),
(72, 5, 'ocupado'),
(73, 5, 'ocupado'),
(74, 5, 'ocupado'),
(75, 5, 'disponível'),
(76, 5, 'inoperante'),
(77, 5, 'ocupado'),
(78, 5, 'disponível');

-- --------------------------------------------------------

--
-- Estrutura para tabela `laboratorio`
--

CREATE TABLE `laboratorio` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `localizacao` varchar(255) NOT NULL,
  `qtd_cadeiras` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `laboratorio`
--

INSERT INTO `laboratorio` (`id`, `nome`, `localizacao`, `qtd_cadeiras`) VALUES
(1, 'Laboratório de Informática', 'Bloco A - Sala 101', 30),
(2, 'Laboratório de Química', 'Bloco B - Sala 202', 20),
(3, 'Laboratório de Física', 'Bloco C - Sala 303', 25),
(4, 'Laboratório de Matemática', 'Bloco A - Sala 211', 50),
(5, 'Laboratório de Português', 'Bloco B - Sala 208', 32);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tipo_usuario` enum('admin','professor','aluno') NOT NULL,
  `matricula` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `senha`, `email`, `tipo_usuario`, `matricula`) VALUES
(3, 'João Silva', 'senha123', 'joao.silva@email.com', 'aluno', 'MATRICULA001'),
(4, 'Maria Oliveira', 'senha456', 'maria.oliveira@email.com', 'admin', 'MATRICULA002'),
(5, 'Carlos Pereira', 'senha7891', 'carlos.pereira@email.com', 'professor', 'MATRICULA003'),
(6, 'Ana Santos', 'senha321', 'ana.santos@email.com', 'aluno', 'MATRICULA004'),
(7, 'Fernanda Lima', 'senha654', 'fernanda.lima@email.com', 'professor', 'MATRICULA005'),
(8, 'teste', '$2y$10$ls0vE.I8DdroME8C5sTK8.F.GuvXlSFEZd0DKBQAWXKXac2AFKfW2', 'teste@gmail.com', 'admin', 'mat123123123'),
(9, 'cascas', '$2y$10$i7YL5osLeD2Xz7ol.ruwhOruXfNPU3hT0LZksw3wJJgvWJ0fxBdcm', 'caiodasfusco@gmail.com', 'aluno', 'dasdas'),
(10, 'Lucas Borges', '$2y$10$liuC5sTYWQ7vwJOpcK5/COEpie0ZFPVGbt3IICmVG.dr0kdWrMeHa', 'lucaszagueiro@outlook.com', 'aluno', '2320'),
(11, 'teste1', '$2y$10$HGYLBWYOsDHJbUHVZGw9rOAR1C/P8Bke36vldXwg.vRn1Wj2ad./2', 'teste1@gmail.com', 'aluno', 'mat123'),
(12, 'Caio Teste', '$2y$10$pJhnQ3iqLIpp/O61eWe8Q.Qr83GDciCKf1.jzzEJEta0/BwbO0nPi', 'testando@gmail.com', 'aluno', '123123123'),
(13, 'Robson', '$2y$10$2FPahOoXIHLI7Z/YYbfeludObKlV4GUD.tIKOhQTruA.FjVGCxCZi', 'robinho@gmail.com', 'aluno', '11111'),
(14, 'Caio Aluno', '$2y$10$i40BS62fXRnoX2oqsVCKSOulMi5K4FPXsknLCyOCSoOJr/laAJTgW', 'testealuno@gmail.com', 'aluno', 'aluno001'),
(15, '', '', '', '', ''),
(16, 'Caio Professor', '123', 'caioprof@gmail.com', 'professor', '88888'),
(19, 'cprf', '$2y$10$YkJE6fx2u0X.pIRk076Z7ex3lXdA8feyuueadjKCSTbggOlF6HU66', 'proffff@gmail.com', 'professor', '00000000'),
(20, 'çlçlç', '$2y$10$3sDYNAzBh5RF1yOwOGekTeYLbKQVOZL18GhCgqd70ni4hD4KJxr5i', 'ppp@gmail.com', '', '5655656'),
(21, 'dasdsad', '$2y$10$OSPq7YOfSecj14GP39F46uQ8/laeUjcanXJdEPgLj22SluE2vZE1G', 'asdas@gmail.com', 'admin', '312321'),
(22, 'mdm', '$2y$10$e9on0OtcPwsOktyb3vTtfO1iMSeMO67vHGlQ3wCyi4s9Qw9xhXPgW', 'admnsc@gmail.com', 'admin', '31212312565'),
(23, 'adsasdasdasdsadas', '$2y$10$YIwSK0upSqljTuSoIO6UPeww/VOvykiQGKpuOw3RlPjQHowH1j1Fu', 'dasdasdasdasc@gmail.com', 'aluno', '13212312312e1edca'),
(24, 'qwqas csacascsa', '$2y$10$jpwLnE6N0UAZjbofrXBuvu7XpOt9F0Qkn2Vuogz9HS6AZXgpXVxN.', 'dasdcacacacasdasdasc@gmail.com', 'professor', '21312dsa'),
(25, 'professor', '$2y$10$Hwkass8ZcDZ47DOeffgMguFhwVF/b3AEonXwVdIS6wAh22GwKfDK6', 'professor@gmail.com', 'professor', '12312344'),
(26, 'Aluno', '$2y$10$NhsFd1d5hzKD2xsbqM7ee.N89hP2oh/kdc3X//pHwaiM86L56lbJi', 'aluno@gmail.com', 'aluno', '13212325345443'),
(27, 'administrador', '$2y$10$Xv4KzfmNGKzbvOewKc9IDOp.JYJwSN6R353kSL14dgAS8dB/nZfBS', 'admin@gmail.com', 'admin', '12354723423'),
(28, 'TesteAluno', '$2y$10$/ufTrilQk6onKd3eoE4cdOpKNcA5oeJzA.4lyUYoBCRtXnTSDhiFq', 'testevideo@gmail.com', 'aluno', '12312312312444');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cadeira`
--
ALTER TABLE `cadeira`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laboratorio_id` (`laboratorio_id`);

--
-- Índices de tabela `laboratorio`
--
ALTER TABLE `laboratorio`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `matricula` (`matricula`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cadeira`
--
ALTER TABLE `cadeira`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT de tabela `laboratorio`
--
ALTER TABLE `laboratorio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `cadeira`
--
ALTER TABLE `cadeira`
  ADD CONSTRAINT `cadeira_ibfk_1` FOREIGN KEY (`laboratorio_id`) REFERENCES `laboratorio` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
