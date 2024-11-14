-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 14/11/2024 às 02:03
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

CREATE DATABASE IF NOT EXISTS `db_laboratorios`;
USE `db_laboratorios`;

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
(5, 'Carlos Pereira', 'senha789', 'carlos.pereira@email.com', 'professor', 'MATRICULA003'),
(6, 'Ana Santos', 'senha321', 'ana.santos@email.com', 'aluno', 'MATRICULA004'),
(7, 'Fernanda Lima', 'senha654', 'fernanda.lima@email.com', 'professor', 'MATRICULA005'),
(8, 'teste', '$2y$10$ls0vE.I8DdroME8C5sTK8.F.GuvXlSFEZd0DKBQAWXKXac2AFKfW2', 'teste@gmail.com', 'admin', 'mat123123123'),
(9, 'cascas', '$2y$10$i7YL5osLeD2Xz7ol.ruwhOruXfNPU3hT0LZksw3wJJgvWJ0fxBdcm', 'caiodasfusco@gmail.com', 'aluno', 'dasdas'),
(10, 'Lucas Borges', '$2y$10$liuC5sTYWQ7vwJOpcK5/COEpie0ZFPVGbt3IICmVG.dr0kdWrMeHa', 'lucaszagueiro@outlook.com', 'aluno', '2320'),
(11, 'teste1', '$2y$10$HGYLBWYOsDHJbUHVZGw9rOAR1C/P8Bke36vldXwg.vRn1Wj2ad./2', 'teste1@gmail.com', 'aluno', 'mat123'),
(12, 'Caio Teste', '$2y$10$pJhnQ3iqLIpp/O61eWe8Q.Qr83GDciCKf1.jzzEJEta0/BwbO0nPi', 'testando@gmail.com', 'aluno', '123123123'),
(13, 'Robson', '$2y$10$2FPahOoXIHLI7Z/YYbfeludObKlV4GUD.tIKOhQTruA.FjVGCxCZi', 'robinho@gmail.com', 'aluno', '11111');

--
-- Índices para tabelas despejadas
--

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
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
