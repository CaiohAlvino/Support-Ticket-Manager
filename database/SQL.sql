-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11/06/2025 às 12:16
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
-- Banco de dados: `support_ticket_manager`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo` varchar(255) NOT NULL DEFAULT 'CNPJ' COMMENT 'CNPJ ou CPF',
  `documento` varchar(255) NOT NULL COMMENT 'xx.xxx.xxx/xxxx-xx ou xxx.xxx.xxx-xx',
  `razao_social` varchar(255) DEFAULT NULL,
  `nome_fantasia` varchar(255) NOT NULL,
  `telefone` varchar(20) NOT NULL COMMENT 'Formato: (XX) X XXXX-XXXX',
  `email` varchar(255) DEFAULT NULL,
  `responsavel_nome` varchar(255) NOT NULL,
  `responsavel_email` varchar(255) DEFAULT NULL,
  `responsavel_telefone` varchar(20) NOT NULL COMMENT 'Formato: (XX) X XXXX-XXXX',
  `responsavel_documento` varchar(18) NOT NULL COMMENT 'CPF: XXX.XXX.XXX-XX ou RG',
  `cep` varchar(10) DEFAULT NULL COMMENT 'Formato: XXXXX-XXX',
  `cidade` varchar(255) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `pais` varchar(255) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `numero` varchar(50) DEFAULT NULL,
  `bairro` varchar(255) DEFAULT NULL,
  `situacao` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 = INATIVO ou 1 = ATIVO',
  `cadastrado` timestamp NULL DEFAULT current_timestamp(),
  `alterado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`id`, `usuario_id`, `tipo`, `documento`, `razao_social`, `nome_fantasia`, `telefone`, `email`, `responsavel_nome`, `responsavel_email`, `responsavel_telefone`, `responsavel_documento`, `cep`, `cidade`, `estado`, `pais`, `endereco`, `numero`, `bairro`, `situacao`, `cadastrado`, `alterado`) VALUES
(1, 1, 'CPF', '', NULL, 'Caio Vendas', '(44) 9 9169-2589', NULL, 'CAIO', 'caio@caio.com', '(44) 9 9169-2589', '556.246.944-82', '87203-460', 'Cianorte', 'Paraná', NULL, '', '123', 'Jardim Universidade I', 1, '2025-05-17 21:37:41', '2025-06-08 03:22:22'),
(2, 3, 'CNPJ', '86.642.481/0001-54', 'HHM', 'oficinas de Saul Goodman & asociados', '(44) 9 9999-9999', 'saul.goodman@goodman.com', 'Saul Goodman', 'saul.goodman@gmail.com', '(64) 9 2486-3658', '266.365.221-88', '53425-460', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-06-03 12:06:23', '2025-06-10 23:20:36'),
(3, 7, 'CPF', '', NULL, 'Los Pollos Hermanos', '(32) 4 2725-6236', NULL, 'Gustavo Fring', 'gus.meth@LPH.com', '(23) 6 2625-6236', '651.978.767-19', '66630-580', 'Belém', 'Pará', NULL, 'Alameda Ananindeua', '48', 'Bengui', 1, '2025-06-08 03:28:50', '2025-06-08 03:28:50'),
(4, 8, 'CNPJ', '25.264.232/0001-88', 'Gray Matter Technologies LTDA', 'Gray Matter Technologies', '(25) 6 2562-4562', NULL, 'Elliott Schwartz', 'elliott.gray@matter.com', '(24) 5 2362-5656', '350.969.484-80', '60346-206', 'Fortaleza', 'Ceará', NULL, 'Vila Santa Cecília', '720', 'Jardim Guanabara', 1, '2025-06-08 03:31:17', '2025-06-08 03:31:17'),
(5, 9, 'CPF', '', NULL, 'A1A Car Wash', '(67) 6 7365-7356', NULL, 'Walter White', 'walter.white@a1a.com', '(35) 6 3457-5346', '183.326.832-62', '08081-150', 'São Paulo', 'São Paulo', NULL, 'Rua Bonito de Santa Fé', '34', 'Parque Paulistano', 1, '2025-06-08 03:33:27', '2025-06-08 03:33:27');

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa`
--

CREATE TABLE `empresa` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `situacao` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 = INATIVO ou 1 = ATIVO',
  `cadastrado` timestamp NULL DEFAULT current_timestamp(),
  `alterado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `empresa`
--

INSERT INTO `empresa` (`id`, `nome`, `situacao`, `cadastrado`, `alterado`) VALUES
(1, 'Venezia\'s', 1, '2025-06-01 14:10:29', '2025-06-10 23:18:33'),
(2, 'Caltech', 1, '2025-06-03 11:58:32', '2025-06-10 23:19:36'),
(3, 'methtech', 1, '2025-06-03 11:58:32', '2025-06-10 23:21:53'),
(4, 'AMC', 1, '2025-06-11 03:16:24', '2025-06-11 03:16:24'),
(5, 'Dos Hombres', 1, '2025-06-11 03:25:23', '2025-06-11 03:25:23');

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa_cliente`
--

CREATE TABLE `empresa_cliente` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `situacao` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 = INATIVO ou 1 = ATIVO',
  `cadastrado` timestamp NULL DEFAULT current_timestamp(),
  `alterado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `empresa_cliente`
--

INSERT INTO `empresa_cliente` (`id`, `empresa_id`, `cliente_id`, `situacao`, `cadastrado`, `alterado`) VALUES
(1, 3, 2, 1, '2025-06-03 12:09:12', '2025-06-03 12:09:12'),
(2, 1, 1, 1, '2025-06-03 12:09:12', '2025-06-03 12:09:12');

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa_servico`
--

CREATE TABLE `empresa_servico` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `servico_id` int(11) NOT NULL,
  `situacao` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 = INATIVO ou 1 = ATIVO',
  `cadastrado` timestamp NULL DEFAULT current_timestamp(),
  `alterado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `empresa_servico`
--

INSERT INTO `empresa_servico` (`id`, `empresa_id`, `servico_id`, `situacao`, `cadastrado`, `alterado`) VALUES
(1, 1, 1, 1, '2025-06-03 12:00:08', '2025-06-03 12:00:08'),
(2, 2, 2, 1, '2025-06-03 12:00:08', '2025-06-03 12:00:08'),
(3, 3, 1, 1, '2025-06-03 12:00:25', '2025-06-03 12:00:25'),
(4, 1, 2, 1, '2025-06-03 12:00:25', '2025-06-03 12:00:25'),
(5, 1, 3, 1, '2025-06-03 12:00:38', '2025-06-03 12:00:38'),
(6, 3, 4, 1, '2025-06-11 00:29:17', '2025-06-11 00:29:17');

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa_usuario`
--

CREATE TABLE `empresa_usuario` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `situacao` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 = INATIVO ou 1 = ATIVO',
  `cadastrado` timestamp NULL DEFAULT current_timestamp(),
  `alterado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `empresa_usuario`
--

INSERT INTO `empresa_usuario` (`id`, `usuario_id`, `empresa_id`, `situacao`, `cadastrado`, `alterado`) VALUES
(1, 1, 1, 1, '2025-06-01 14:21:25', '2025-06-01 14:21:25'),
(2, 1, 2, 1, '2025-06-03 12:01:10', '2025-06-03 12:01:10'),
(3, 1, 3, 1, '2025-06-03 12:01:10', '2025-06-03 12:01:10'),
(4, 3, 2, 1, '2025-06-03 12:02:47', '2025-06-03 12:02:47');

-- --------------------------------------------------------

--
-- Estrutura para tabela `grupo`
--

CREATE TABLE `grupo` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `situacao` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 = INATIVO ou 1 = ATIVO',
  `cadastrado` timestamp NULL DEFAULT current_timestamp(),
  `alterado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `grupo`
--

INSERT INTO `grupo` (`id`, `nome`, `situacao`, `cadastrado`, `alterado`) VALUES
(1, 'MASTER', 1, '2025-05-17 21:31:12', '2025-05-17 21:31:12'),
(2, 'CLIENTE', 1, '2025-05-17 21:32:17', '2025-05-17 21:33:00'),
(3, 'SUPORTE', 1, '2025-06-03 12:01:37', '2025-06-08 03:15:46'),
(4, 'SUPORTE 2', 1, '2025-06-08 03:16:03', '2025-06-08 03:16:03'),
(5, 'SUPORTE 3', 1, '2025-06-08 03:16:09', '2025-06-08 03:16:09'),
(6, 'SUPORTE 4', 1, '2025-06-11 03:59:44', '2025-06-11 03:59:44');

-- --------------------------------------------------------

--
-- Estrutura para tabela `servico`
--

CREATE TABLE `servico` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `situacao` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 = INATIVO ou 1 = ATIVO',
  `cadastrado` timestamp NULL DEFAULT current_timestamp(),
  `alterado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `servico`
--

INSERT INTO `servico` (`id`, `nome`, `situacao`, `cadastrado`, `alterado`) VALUES
(1, 'Site', 1, '2025-06-01 14:09:38', '2025-06-01 14:09:52'),
(2, 'Software', 1, '2025-06-03 11:59:28', '2025-06-03 11:59:28'),
(3, 'Marketing Orgânico', 1, '2025-06-03 11:59:28', '2025-06-03 11:59:28'),
(4, 'Lojas Virtuais', 1, '2025-06-11 00:29:17', '2025-06-11 00:29:17'),
(5, 'Caltech Insight', 1, '2025-06-11 00:38:59', '2025-06-11 00:38:59'),
(6, 'Banco de Dados', 1, '2025-06-11 03:25:45', '2025-06-11 03:26:19');

-- --------------------------------------------------------

--
-- Estrutura para tabela `suporte`
--

CREATE TABLE `suporte` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `servico_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL COMMENT 'Só é atribuído manualmente ou se interagir com o SUPORTE',
  `assunto` longtext NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'ABERTO' COMMENT 'ABERTO, FECHADO, RESPONDIDO, AGUARDANDO_SUPORTE',
  `situacao` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 = INATIVO ou 1 = ATIVO',
  `cadastrado` timestamp NULL DEFAULT current_timestamp(),
  `alterado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `suporte`
--

INSERT INTO `suporte` (`id`, `empresa_id`, `servico_id`, `cliente_id`, `usuario_id`, `assunto`, `status`, `situacao`, `cadastrado`, `alterado`) VALUES
(1, 1, 1, 2, NULL, 'Meu Site de caiu (https://salahmed-ctrlz.github.io/BetterCallSaul/#/)', 'ABERTO', 1, '2025-06-01 14:22:22', '2025-06-08 03:44:40'),
(2, 3, 2, 3, 1, 'O sistema da minha lanchonete está com defeito no Login', 'RESPONDIDO', 1, '2025-06-03 12:10:31', '2025-06-08 04:12:24'),
(3, 1, 3, 5, NULL, 'O marketing não está trazendo resultado', 'ABERTO', 1, '2025-06-03 12:10:31', '2025-06-08 03:49:48');

-- --------------------------------------------------------

--
-- Estrutura para tabela `suporte_mensagem`
--

CREATE TABLE `suporte_mensagem` (
  `id` int(11) NOT NULL,
  `suporte_id` int(11) NOT NULL,
  `mensagem` longtext NOT NULL,
  `proprietario` varchar(255) DEFAULT NULL COMMENT 'CLIENTE ou USUARIO',
  `respondido` tinyint(1) DEFAULT 1 COMMENT '0 = INATIVO ou 1 = ATIVO',
  `cadastrado` timestamp NULL DEFAULT current_timestamp(),
  `alterado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `suporte_mensagem`
--

INSERT INTO `suporte_mensagem` (`id`, `suporte_id`, `mensagem`, `proprietario`, `respondido`, `cadastrado`, `alterado`) VALUES
(1, 1, 'Poderia resolver isso rápido? Estou perdendo clientes assim.', 'CLIENTE', 0, '2025-06-01 14:23:18', '2025-06-08 03:51:03'),
(2, 2, 'Ninguém consegue fazer o login no sistema, estamos fazendo os pedidos e os lançamentos tudo pelo papel, pode resolver isso?', 'CLIENTE', 1, '2025-06-03 12:11:08', '2025-06-08 03:52:26'),
(3, 3, 'Preciso de mais pessoas vindo para meu Lava rápido, a polícia vai suspeitar se tiver vindo poucos clientes', 'CLIENTE', 1, '2025-06-03 12:12:13', '2025-06-08 03:54:51'),
(4, 2, 'Arrumamos o sistema e fizemos a atualização, poderia verificar se está tudo certo?', 'USUARIO', 1, '2025-06-08 03:14:38', '2025-06-08 03:53:36');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL COMMENT 'TOKEN PARA VALIDAÇÕES',
  `situacao` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 = INATIVO ou 1 = ATIVO',
  `cadastrado` timestamp NULL DEFAULT current_timestamp(),
  `alterado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id`, `grupo_id`, `nome`, `email`, `senha`, `token`, `situacao`, `cadastrado`, `alterado`) VALUES
(1, 1, 'Caio Alvino', 'caioh.alvino22@gmail.com', '$2y$10$kv32YhV1OWyVYIJQucLqa.Y7Em6LV0oY44a.hqBtIdDhuyij3l.0G', 'e511010881c1d6fca878a6faa1c258c2d07e92e30313fd4fdeffe9c61df9eada', 1, '2025-05-17 21:34:48', '2025-06-08 04:20:29'),
(2, 1, 'Admin', 'admin@admin.com', '$2y$10$kv32YhV1OWyVYIJQucLqa.Y7Em6LV0oY44a.hqBtIdDhuyij3l.0G', 'c3638d487cb17cc5d5cb5262d6cdbf00c0c9aa20a6cc4343250a4edb23868d63', 1, '2025-05-17 21:35:48', '2025-06-08 03:14:20'),
(3, 2, 'Saul Goodman', 'saul.goodman@goodman.com', '$2y$10$kv32YhV1OWyVYIJQucLqa.Y7Em6LV0oY44a.hqBtIdDhuyij3l.0G', NULL, 1, '2025-06-03 12:02:20', '2025-06-08 03:40:06'),
(4, 3, 'Suporte', 'suporte@suporte.com', '$2y$10$kv32YhV1OWyVYIJQucLqa.Y7Em6LV0oY44a.hqBtIdDhuyij3l.0G', '017ad891d1c124561479fe9885d05e8ad20933fc4416b8a2bcacb976bd620b15', 1, '2025-06-08 03:16:50', '2025-06-08 04:14:56'),
(5, 4, 'Suporte 2', 'suporte2@suporte.com', '$2y$10$kv32YhV1OWyVYIJQucLqa.Y7Em6LV0oY44a.hqBtIdDhuyij3l.0G', NULL, 1, '2025-06-08 03:17:14', '2025-06-08 03:18:16'),
(6, 5, 'Suporte 3', 'suporte3@suporte.com', '$2y$10$kv32YhV1OWyVYIJQucLqa.Y7Em6LV0oY44a.hqBtIdDhuyij3l.0G', NULL, 1, '2025-06-08 03:18:37', '2025-06-08 03:18:37'),
(7, 2, 'Gustavo Fring', 'gus.meth@LPH.com', '$2y$10$XHkPYYsa6/A/0A1dnFllzu7mve4ctmmgMyAbbo3SFCPyL5xE3gMIy', NULL, 1, '2025-06-08 03:28:50', '2025-06-08 03:28:50'),
(8, 2, 'Elliott Schwartz', 'elliott.gray@matter.com', '$2y$10$xN8V8UKRoTBdTLt4TPFpMebF3YNzpvj5AURtyDnkyFqID9FoFEqTm', NULL, 1, '2025-06-08 03:31:17', '2025-06-08 03:31:17'),
(9, 2, 'Walter White', 'walter.white@a1a.com', '$2y$10$vQoclea3qTyueg9fTuM83OG4a1reGpXQ09ZnvN2JRgkMbAWLztLc2', NULL, 1, '2025-06-08 03:33:27', '2025-06-08 03:33:27'),
(10, 3, 'Hyper Suporte', 'hyper.suporte@suporte.com', '123', NULL, 1, '2025-06-11 03:54:54', '2025-06-11 03:54:54');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cliente_usuario1_idx` (`usuario_id`);

--
-- Índices de tabela `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `empresa_cliente`
--
ALTER TABLE `empresa_cliente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_empresa_cliente_empresa1_idx` (`empresa_id`),
  ADD KEY `fk_empresa_cliente_cliente1_idx` (`cliente_id`);

--
-- Índices de tabela `empresa_servico`
--
ALTER TABLE `empresa_servico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_empresa_servico_servico1_idx` (`servico_id`),
  ADD KEY `fk_empresa_servico_empresa1_idx` (`empresa_id`);

--
-- Índices de tabela `empresa_usuario`
--
ALTER TABLE `empresa_usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_empresa_usuario_usuario1_idx` (`usuario_id`),
  ADD KEY `fk_empresa_usuario_empresa1_idx` (`empresa_id`);

--
-- Índices de tabela `grupo`
--
ALTER TABLE `grupo`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `servico`
--
ALTER TABLE `servico`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `suporte`
--
ALTER TABLE `suporte`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_suporte_empresa1_idx` (`empresa_id`),
  ADD KEY `fk_suporte_servico1_idx` (`servico_id`),
  ADD KEY `fk_suporte_cliente1_idx` (`cliente_id`),
  ADD KEY `fk_suporte_usuario1_idx` (`usuario_id`);

--
-- Índices de tabela `suporte_mensagem`
--
ALTER TABLE `suporte_mensagem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_suporte_mensagem_suporte1_idx` (`suporte_id`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuario_grupo_idx` (`grupo_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `empresa_cliente`
--
ALTER TABLE `empresa_cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `empresa_servico`
--
ALTER TABLE `empresa_servico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `empresa_usuario`
--
ALTER TABLE `empresa_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `grupo`
--
ALTER TABLE `grupo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `servico`
--
ALTER TABLE `servico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `suporte`
--
ALTER TABLE `suporte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `suporte_mensagem`
--
ALTER TABLE `suporte_mensagem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `fk_cliente_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `empresa_cliente`
--
ALTER TABLE `empresa_cliente`
  ADD CONSTRAINT `fk_empresa_cliente_cliente1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_empresa_cliente_empresa1` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `empresa_servico`
--
ALTER TABLE `empresa_servico`
  ADD CONSTRAINT `fk_empresa_servico_empresa1` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_empresa_servico_servico1` FOREIGN KEY (`servico_id`) REFERENCES `servico` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `empresa_usuario`
--
ALTER TABLE `empresa_usuario`
  ADD CONSTRAINT `fk_empresa_usuario_empresa1` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_empresa_usuario_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `suporte`
--
ALTER TABLE `suporte`
  ADD CONSTRAINT `fk_suporte_cliente1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_suporte_empresa1` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_suporte_servico1` FOREIGN KEY (`servico_id`) REFERENCES `servico` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_suporte_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `suporte_mensagem`
--
ALTER TABLE `suporte_mensagem`
  ADD CONSTRAINT `fk_suporte_mensagem_suporte1` FOREIGN KEY (`suporte_id`) REFERENCES `suporte` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_grupo` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
