-- phpMyAdmin SQL Dump CORRIGIDO
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 03/06/2025 às 14:12 (CORRIGIDO)
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
(1, 1, 'CPF', '121.969.859-88', NULL, 'Teste Fantasia', '(44) 9 9169-2589', NULL, 'CAIO HENRIQUE ALMEIDA ALVINO', '', '(44) 9 9169-2589', '121.969.859-88', '87203-460', 'Cianorte', 'Paraná', NULL, NULL, '390', 'Jardim Universidade I', 1, '2025-05-17 21:37:41', '2025-06-03 11:51:35'),
(2, 3, 'CNPJ', '86.642.481/0001-54', 'Luiza Vende', 'Magazine Luiza', '(44) 9 9999-9999', 'magazine@gmail.com', 'Luiza', 'luiza@gmail.com', '(44) 8 8888-8888', '266.365.221-88', '53425-460', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-06-03 12:06:23', '2025-06-03 12:06:23');

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
(1, 'WDevel', 0, '2025-06-01 14:10:29', '2025-06-01 14:10:29'),
(2, 'Clinora', 1, '2025-06-03 11:58:32', '2025-06-03 11:58:32'),
(3, 'O Topo Digital', 1, '2025-06-03 11:58:32', '2025-06-03 11:58:32');

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
(5, 1, 3, 1, '2025-06-03 12:00:38', '2025-06-03 12:00:38');

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
(3, 'SUPORTE CLINORA', 1, '2025-06-03 12:01:37', '2025-06-03 12:01:37');

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
(3, 'Marketing Orgânico', 1, '2025-06-03 11:59:28', '2025-06-03 11:59:28');

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
(1, 1, 1, 1, NULL, 'Quebrou o cell', 'ABERTO', 1, '2025-06-01 14:22:22', '2025-06-01 14:22:22'),
(2, 3, 1, 2, 1, 'Rank do Google', 'ABERTO', 1, '2025-06-03 12:10:31', '2025-06-03 12:10:31'),
(3, 1, 2, 1, 1, 'Site bugado', 'ABERTO', 1, '2025-06-03 12:10:31', '2025-06-03 12:10:31');

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
(1, 1, 'Meu celular quebrou', 'USUARIO', 0, '2025-06-01 14:23:18', '2025-06-01 14:23:18'),
(2, 2, 'Meu site não está na primeira página do Google', 'CLIENTE', 1, '2025-06-03 12:11:08', '2025-06-03 12:11:08'),
(3, 3, 'Meu site está tudo quebrado', 'CLIENTE', 1, '2025-06-03 12:12:13', '2025-06-03 12:12:13');

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
(1, 1, 'Admin', 'caioh.alvino22@gmail.com', '$2y$10$kv32YhV1OWyVYIJQucLqa.Y7Em6LV0oY44a.hqBtIdDhuyij3l.0G', 'c5042246e6cd589fd9aeaa4bc71c4ed71ab7ea6df9ee9a6088d7a53c898522da', 1, '2025-05-17 21:34:48', '2025-06-03 11:54:56'),
(2, 2, 'Cliente', 'cliente@gmail.com', '$2y$10$kv32YhV1OWyVYIJQucLqa.Y7Em6LV0oY44a.hqBtIdDhuyij3l.0G', '6d74b53ffa9491f7a70782363a0ae1452cfac24f41ebd09f0b476cdcfab969f8', 1, '2025-05-17 21:35:48', '2025-06-03 11:55:39'),
(3, 3, 'Luiz', 'luiz@gmail.com', '$2y$10$kv32YhV1OWyVYIJQucLqa.Y7Em6LV0oY44a.hqBtIdDhuyij3l.0G', NULL, 1, '2025-06-03 12:02:20', '2025-06-03 12:02:20');

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
-- AUTO_INCREMENT para tabelas despejadas (RESETADOS)
--

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `empresa_cliente`
--
ALTER TABLE `empresa_cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `empresa_servico`
--
ALTER TABLE `empresa_servico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `empresa_usuario`
--
ALTER TABLE `empresa_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `grupo`
--
ALTER TABLE `grupo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `servico`
--
ALTER TABLE `servico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `suporte`
--
ALTER TABLE `suporte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `suporte_mensagem`
--
ALTER TABLE `suporte_mensagem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
