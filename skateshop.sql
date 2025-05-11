-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11-Maio-2025 às 17:17
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `skateshop`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`) VALUES
(1, 'Deck'),
(2, 'Trucks'),
(3, 'Rodas'),
(4, 'Rolamentos'),
(5, 'T-shirts'),
(6, 'Sweats'),
(7, 'Pants'),
(8, 'Shorts'),
(9, 'Sapato'),
(10, 'Acessórios');

-- --------------------------------------------------------

--
-- Estrutura da tabela `decks`
--

CREATE TABLE `decks` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `tamanho` varchar(50) NOT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `estoque` int(11) DEFAULT 0,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `decks`
--

INSERT INTO `decks` (`id`, `produto_id`, `tamanho`, `marca`, `estoque`, `descricao`) VALUES
(1, 1, '8.5', 'Polar Skate CO,', 10, NULL),
(2, 4, '8\'', 'Fucking Awesome', 10, 'Free Griptape Included\r\n\r\nRandom Wood Veneer Selected.\r\n\r\nShape #2\r\n\r\n8.25\" x 31.79\"\r\n14.12\" Wheel Base'),
(4, 5, '8.0', '', 10, '<p>Free Griptape Included</p>\r\n\r\n<p>Random Wood Veneer Selected.</p>\r\n\r\n<p>Shape #1</p>\r\n\r\n<p>8.0\" x 31.66\"</p>\r\n<p>14\" Wheel Base.</p>\r\n\r\n<p>8.25\" x 31.79\"\r\n<p>14.12\" Wheel Base</p>');

-- --------------------------------------------------------

--
-- Estrutura da tabela `descricao_categoria`
--

CREATE TABLE `descricao_categoria` (
  `produto_id` int(11) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `tamanho` varchar(50) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `logo_marca` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `imagem`, `categoria_id`, `tamanho`, `categoria`, `marca`, `logo_marca`) VALUES
(1, 'Polar Skate Co. Toba - David Stenstrom Debut Pro Deck', 'Griptape included', 85.00, 'skateboard1.png', 1, '8.5', 'decks', 'Polar Skate CO', 'polar.png'),
(3, 'Ace AF1 Trucks Polished Pair', 'Par de trucks Ace AF1 com resistência superior.', 74.99, 'truckAF1.jpg', 2, '8.25', 'trucks', 'Ace Trucks MFG', 'ace.png'),
(4, 'Fucking Awesome-Curren Caples Remember To Forget Deck', 'Free Griptape Included\r\n\r\nRandom Wood Veneer Selected.\r\n\r\n8.25\" x 31.79\"\r\n14.12\" Wheel Base', 90.00, 'skateboard3.png', 1, '8', 'decks', 'Fucking Awesome', 'fuckingawesome.png'),
(5, 'Fucking Awesome Gino Iannucci Daybreak in Phuket Deck', '<p>Free Griptape Included</p>\r\n\r\n<p>Random Wood Veneer Selected.</p>\r\n\r\n<p>Shape #1</p>\r\n\r\n<p>8.0\" x 31.66\"</p>\r\n<p>14\" Wheel Base.</p>\r\n\r\n<p>8.25\" x 31.79\"\r\n<p>14.12\" Wheel Base</p>', 90.00, 'skateboard6.jpeg', 1, '8', 'decks', 'Fucking Awesome', 'fuckingawesome.png'),
(6, 'Ace Trucks MFG AF1 Carhartt WIP Trucks - Carhartt Orange / Polished', '<p>The Ace AF1 Carhartt WIP Trucks are made in collaboration with California brand Ace, and are constructed from a combination of aluminum alloy, steel and polyurethane. The trucks are detailed with a safety baseplate in Carhartt WIP orange, and feature an engraved hanger.</p>\r\n\r\n<p>-44 (8.25’’) / 55 (8.5’’)</p>\r\n\r\n<p>-67% Aluminum alloy, 29% steel, 4% polyurethane</p>\r\n\r\n<p>-Set of 2</p>\r\n\r\n<p>-Polished</p>\r\n\r\n<p>-Branded engraved hanger</p>\r\n\r\n<p>-Exclusive anodized orange baseplate</p>\r\n\r\n<p>-Includes anodized axle orange re-threader die</p>', 75.00, 'Stage5Trucks.webp', 2, '55', 'trucks', 'Ace Trucks MFG', 'ace.png');

-- --------------------------------------------------------

--
-- Estrutura da tabela `rodas`
--

CREATE TABLE `rodas` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `tamanho` varchar(50) NOT NULL,
  `dureza` varchar(50) NOT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `estoque` int(11) DEFAULT 0,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `rolamentos`
--

CREATE TABLE `rolamentos` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `tipo` varchar(50) NOT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `estoque` int(11) DEFAULT 0,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `trucks`
--

CREATE TABLE `trucks` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `tamanho` varchar(50) NOT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `estoque` int(11) DEFAULT 0,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `trucks`
--

INSERT INTO `trucks` (`id`, `produto_id`, `tamanho`, `marca`, `estoque`, `descricao`) VALUES
(3, 3, '8.25', 'Ace Trucks MFG', 15, '<p>Trucks Ace AF1 com resistência superior.</p>'),
(4, 6, '55', 'Ace Trucks MFG', 10, '<p>The Ace AF1 Carhartt WIP Trucks are made in collaboration with California brand Ace, and are constructed from a combination of aluminum alloy, steel and polyurethane. The trucks are detailed with a safety baseplate in Carhartt WIP orange, and feature an engraved hanger.</p>\n\n<p>-44 (8.25’’) / 55 (8.5’’)</p>\n\n<p>-67% Aluminum alloy, 29% steel, 4% polyurethane</p>\n\n<p>-Set of 2</p>\n\n<p>-Polished</p>\n\n<p>-Branded engraved hanger</p>\n\n<p>-Exclusive anodized orange baseplate</p>\n\n<p>-Includes anodized axle orange re-threader die</p>');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(100) DEFAULT NULL,
  `morada` text DEFAULT NULL,
  `verification_code` varchar(64) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`, `email`, `morada`, `verification_code`, `reset_token`, `reset_token_expiry`) VALUES
(9, 'admin', '$2y$10$ESqzVZhyFqtwn8yz5yCpqOC2OB1yXjIWaT82ISuO1AKN2fRKGFK5m', 'admin', '2025-03-06 16:23:39', 'admin@gmail.com', NULL, NULL, NULL, NULL),
(16, 'Joa1', '$2y$10$y8BN1Tu.9doL.LjHDsUfz.9N9.zG6PjLRd7DLH9BFuJ/BlJ2LdQ7m', 'user', '2025-04-22 16:38:40', 'joaopedroantunes1980@gmail.com', 'Avenida de Portugal n44Ac, Póvoa da Galega', NULL, NULL, NULL),
(25, 'Paullo', '$2y$10$4QPlnlscUtV2xqJAh/3Kee1CftJvgDa8HLU1hH3o.1F5itQ9IHtWi', '', '2025-05-09 21:01:37', 'joaopedroantunesps4@gmail.com', NULL, NULL, NULL, NULL),
(26, 'Leidi', '$2y$10$p5BoHnLGLfqz9R5.A8Kd7O35vw38dEWzMXWLMcNz0Qgsg3iYVCUTe', '', '2025-05-09 21:05:26', 'leidantunes@live.com.pt', NULL, NULL, NULL, NULL),
(27, 'miudo', '$2y$10$qQ9PiY5S40YgSW/SAavWc.w6WnhlLnMmyES7RNRC9WzbasN8ZwFAO', '', '2025-05-09 21:44:14', 'miudogamer0@gmail.com', NULL, NULL, NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices para tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `decks`
--
ALTER TABLE `decks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices para tabela `descricao_categoria`
--
ALTER TABLE `descricao_categoria`
  ADD PRIMARY KEY (`produto_id`,`categoria`);

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Índices para tabela `rodas`
--
ALTER TABLE `rodas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices para tabela `rolamentos`
--
ALTER TABLE `rolamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices para tabela `trucks`
--
ALTER TABLE `trucks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `decks`
--
ALTER TABLE `decks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `rodas`
--
ALTER TABLE `rodas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `rolamentos`
--
ALTER TABLE `rolamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `trucks`
--
ALTER TABLE `trucks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD CONSTRAINT `carrinho_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carrinho_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `decks`
--
ALTER TABLE `decks`
  ADD CONSTRAINT `decks_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `descricao_categoria`
--
ALTER TABLE `descricao_categoria`
  ADD CONSTRAINT `descricao_categoria_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);

--
-- Limitadores para a tabela `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Limitadores para a tabela `rodas`
--
ALTER TABLE `rodas`
  ADD CONSTRAINT `rodas_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `rolamentos`
--
ALTER TABLE `rolamentos`
  ADD CONSTRAINT `rolamentos_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `trucks`
--
ALTER TABLE `trucks`
  ADD CONSTRAINT `trucks_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
