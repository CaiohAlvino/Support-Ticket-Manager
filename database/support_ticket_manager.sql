-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25/04/2025 às 03:25
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
  `tipo` tinyint(1) NOT NULL,
  `documento` varchar(14) NOT NULL,
  `razao_social` varchar(255) DEFAULT NULL,
  `nome_fantasia` varchar(255) NOT NULL,
  `telefone` varchar(13) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `responsavel_nome` varchar(255) NOT NULL,
  `responsavel_email` varchar(255) DEFAULT NULL,
  `responsavel_telefone` varchar(14) NOT NULL,
  `responsavel_documento` varchar(11) NOT NULL,
  `cep` int(11) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `pais` varchar(255) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `numero` varchar(50) DEFAULT NULL,
  `bairro` varchar(255) DEFAULT NULL,
  `situacao` tinyint(1) DEFAULT NULL,
  `cadastrado` timestamp NULL DEFAULT current_timestamp(),
  `alterado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa`
--

CREATE TABLE `empresa` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `situacao` tinyint(1) NOT NULL,
  `cadastrado` timestamp NULL DEFAULT current_timestamp(),
  `alterado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa_cliente`
--

CREATE TABLE `empresa_cliente` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `situacao` tinyint(1) NOT NULL,
  `cadastrado` timestamp NULL DEFAULT current_timestamp(),
  `alterado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa_fornecedor`
--

CREATE TABLE `empresa_fornecedor` (
  `id` int(11) NOT NULL,
  `fornecedor_id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `situacao` tinyint(1) NOT NULL,
  `cadastrado` timestamp NULL DEFAULT current_timestamp(),
  `alterado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa_servico`
--

CREATE TABLE `empresa_servico` (
  `empresa_id` int(11) NOT NULL,
  `servico_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `situacao` tinyint(1) NOT NULL,
  `cadastrado` timestamp NULL DEFAULT current_timestamp(),
  `alterado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa_usuario`
--

CREATE TABLE `empresa_usuario` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `situacao` tinyint(1) NOT NULL,
  `cadastrado` timestamp NULL DEFAULT current_timestamp(),
  `alterado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedor`
--

CREATE TABLE `fornecedor` (
  `id` int(11) NOT NULL,
  `tipo` tinyint(1) NOT NULL,
  `documento` varchar(14) NOT NULL,
  `razao_social` varchar(255) DEFAULT NULL,
  `nome_fantasia` varchar(255) NOT NULL,
  `telefone` varchar(13) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `responsavel_nome` varchar(255) NOT NULL,
  `responsavel_email` varchar(255) DEFAULT NULL,
  `responsavel_telefone` varchar(14) NOT NULL,
  `responsavel_documento` varchar(11) NOT NULL,
  `cep` int(11) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `pais` varchar(255) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `numero` varchar(50) DEFAULT NULL,
  `bairro` varchar(255) DEFAULT NULL,
  `situacao` tinyint(1) DEFAULT NULL,
  `cadastrado` timestamp NULL DEFAULT current_timestamp(),
  `alterado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `grupo`
--

CREATE TABLE `grupo` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `situacao` tinyint(1) NOT NULL DEFAULT 1,
  `cadastrado` timestamp NULL DEFAULT current_timestamp(),
  `alterado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `grupo`
--

INSERT INTO `grupo` (`id`, `nome`, `situacao`, `cadastrado`, `alterado`) VALUES
(1, 'MASTER', 1, '2025-04-25 01:06:04', '2025-04-25 01:06:25');

-- --------------------------------------------------------

--
-- Estrutura para tabela `servico`
--

CREATE TABLE `servico` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `situacao` tinyint(1) NOT NULL,
  `cadastrado` timestamp NULL DEFAULT current_timestamp(),
  `alterado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `suporte`
--

CREATE TABLE `suporte` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `servico_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `assunto` longtext NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'ABERTO',
  `situacao` tinyint(1) DEFAULT NULL,
  `cadastrado` timestamp NULL DEFAULT current_timestamp(),
  `alterado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `suporte_mensagem`
--

CREATE TABLE `suporte_mensagem` (
  `id` int(11) NOT NULL,
  `suporte_id` int(11) NOT NULL,
  `mensagem` longtext NOT NULL,
  `proprietario` varchar(255) DEFAULT NULL,
  `respondido` tinyint(1) NOT NULL,
  `cadastrado` timestamp NULL DEFAULT NULL,
  `alterado` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
  `token` varchar(255) DEFAULT NULL,
  `situacao` tinyint(1) NOT NULL DEFAULT 1,
  `cadastrado` timestamp NULL DEFAULT current_timestamp(),
  `alterado` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id`, `grupo_id`, `nome`, `email`, `senha`, `token`, `situacao`, `cadastrado`, `alterado`) VALUES
(1, 1, 'CAIO', 'caioh.alvino22@gmail.com', '$2y$10$aB9cD8eF7gH6iJ5kL4mN3.OqRsTuVwXyZ2Y1W0vU9t8r7q6p5o4n3', '1', 1, '2025-04-25 01:08:04', '2025-04-25 01:24:22');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`);

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
-- Índices de tabela `empresa_fornecedor`
--
ALTER TABLE `empresa_fornecedor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_empresa_fornecedor_fornecedor1_idx` (`fornecedor_id`),
  ADD KEY `fk_empresa_fornecedor_empresa1_idx` (`empresa_id`);

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
-- Índices de tabela `fornecedor`
--
ALTER TABLE `fornecedor`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `fk_suporte_cliente1_idx` (`cliente_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `empresa_cliente`
--
ALTER TABLE `empresa_cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `empresa_fornecedor`
--
ALTER TABLE `empresa_fornecedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `empresa_servico`
--
ALTER TABLE `empresa_servico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `empresa_usuario`
--
ALTER TABLE `empresa_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fornecedor`
--
ALTER TABLE `fornecedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `grupo`
--
ALTER TABLE `grupo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `servico`
--
ALTER TABLE `servico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `suporte`
--
ALTER TABLE `suporte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `suporte_mensagem`
--
ALTER TABLE `suporte_mensagem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `empresa_cliente`
--
ALTER TABLE `empresa_cliente`
  ADD CONSTRAINT `fk_empresa_cliente_cliente1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_empresa_cliente_empresa1` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `empresa_fornecedor`
--
ALTER TABLE `empresa_fornecedor`
  ADD CONSTRAINT `fk_empresa_fornecedor_empresa1` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_empresa_fornecedor_fornecedor1` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

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
  ADD CONSTRAINT `fk_suporte_servico1` FOREIGN KEY (`servico_id`) REFERENCES `servico` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

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
