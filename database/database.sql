-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS `sistema_suporte` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sistema_suporte`;

-- Tabela grupo
CREATE TABLE IF NOT EXISTS `grupo` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `nome` VARCHAR(255) NOT NULL,
    `situacao` TINYINT(1) DEFAULT 1,
    `cadastrado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela usuario
CREATE TABLE IF NOT EXISTS `usuario` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `grupo_id` INT,
    `nome` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `senha` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255),
    `situacao` TINYINT(1) DEFAULT 1,
    `cadastrado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`grupo_id`) REFERENCES `grupo`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela empresa
CREATE TABLE IF NOT EXISTS `empresa` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `nome` VARCHAR(255) NOT NULL,
    `situacao` TINYINT(1) DEFAULT 1,
    `cadastrado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela empresa_usuario
CREATE TABLE IF NOT EXISTS `empresa_usuario` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `usuario_id` INT,
    `empresa_id` INT,
    `situacao` TINYINT(1) DEFAULT 1,
    `cadastrado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`usuario_id`) REFERENCES `usuario`(`id`),
    FOREIGN KEY (`empresa_id`) REFERENCES `empresa`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela servico
CREATE TABLE IF NOT EXISTS `servico` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `nome` VARCHAR(255) NOT NULL,
    `situacao` TINYINT(1) DEFAULT 1,
    `cadastrado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela empresa_servico
CREATE TABLE IF NOT EXISTS `empresa_servico` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `empresa_id` INT,
    `servico_id` INT,
    `situacao` TINYINT(1) DEFAULT 1,
    `cadastrado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`empresa_id`) REFERENCES `empresa`(`id`),
    FOREIGN KEY (`servico_id`) REFERENCES `servico`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela suporte
CREATE TABLE IF NOT EXISTS `suporte` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `empresa_id` INT,
    `servico_id` INT,
    `cliente_id` INT,
    `assunto` LONGTEXT NOT NULL,
    `status` VARCHAR(255) NOT NULL,
    `situacao` TINYINT(1) DEFAULT 1,
    `cadastrado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`empresa_id`) REFERENCES `empresa`(`id`),
    FOREIGN KEY (`servico_id`) REFERENCES `servico`(`id`),
    FOREIGN KEY (`cliente_id`) REFERENCES `usuario`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela suporte_mensagem
CREATE TABLE IF NOT EXISTS `suporte_mensagem` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `suporte_id` INT,
    `mensagem` LONGTEXT NOT NULL,
    `proprietario` VARCHAR(255) NOT NULL,
    `respondido` TINYINT(1) DEFAULT 0,
    `cadastrado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `alterado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`suporte_id`) REFERENCES `suporte`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela logs
CREATE TABLE IF NOT EXISTS `logs` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `usuario_id` INT,
    `acao` VARCHAR(255) NOT NULL,
    `descricao` TEXT NOT NULL,
    `ip` VARCHAR(45),
    `cadastrado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`usuario_id`) REFERENCES `usuario`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir grupo padrão
INSERT INTO `grupo` (`nome`) VALUES ('Administrador'), ('Usuário');

-- Inserir empresa padrão
INSERT INTO `empresa` (`nome`) VALUES ('Empresa Padrão');

-- Inserir serviços padrão
INSERT INTO `servico` (`nome`) VALUES 
('Suporte Técnico'),
('Dúvidas'),
('Solicitações'),
('Reclamações');

-- Inserir usuário administrador padrão
-- Senha: admin123 (já com hash)
INSERT INTO `usuario` (`grupo_id`, `nome`, `email`, `senha`) VALUES 
(1, 'Administrador', 'admin@sistema.com', '$2y$10$8VQD2VyAqGzxkK3g0s7zPu8v8PvXwV.hqMBjqYPRwYI1x9nmXxJVW');

-- Vincular usuário admin à empresa padrão
INSERT INTO `empresa_usuario` (`usuario_id`, `empresa_id`) VALUES (1, 1);

-- Vincular serviços à empresa padrão
INSERT INTO `empresa_servico` (`empresa_id`, `servico_id`) VALUES 
(1, 1), (1, 2), (1, 3), (1, 4); 