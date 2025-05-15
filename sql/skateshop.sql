-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15-Maio-2025 às 18:54
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
-- Estrutura da tabela `acessorios`
--

CREATE TABLE `acessorios` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `estoque` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `acessorios`
--

INSERT INTO `acessorios` (`id`, `produto_id`, `marca`, `estoque`) VALUES
(1, 28, 'Único', 8),
(2, 28, 'Único', 8),
(3, 29, 'Único', 5);

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

--
-- Extraindo dados da tabela `carrinho`
--

INSERT INTO `carrinho` (`id`, `user_id`, `produto_id`, `quantidade`, `preco`) VALUES
(43, 28, 15, 1, 90.00);

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
(1, 1, '8.5', 'Polar Skate Co.', 10, NULL),
(2, 4, '8\'', 'Fucking Awesome', 10, 'Free Griptape Included\r\n\r\nRandom Wood Veneer Selected.\r\n\r\nShape #2\r\n\r\n8.25\" x 31.79\"\r\n14.12\" Wheel Base'),
(4, 5, '8.0', '', 10, '<p>Free Griptape Included</p>\r\n\r\n<p>Random Wood Veneer Selected.</p>\r\n\r\n<p>Shape #1</p>\r\n\r\n<p>8.0\" x 31.66\"</p>\r\n<p>14\" Wheel Base.</p>\r\n\r\n<p>8.25\" x 31.79\"\r\n<p>14.12\" Wheel Base</p>'),
(7, 7, '8.375\"', 'Yardsale', 10, '<p>O deck profissional de Thaynan Costa pela Yardsale apresenta um design exclusivo que reflete o estilo único do skater brasileiro. Construído com materiais de alta qualidade, oferece durabilidade e performance para todos os níveis de skate.</p>'),
(8, 8, '8.5\"', 'Polar Skate Co.', 10, '<p>O Trust Deck de Oskar Rozenberg é uma colaboração com a Polar Skate Co., trazendo uma arte distinta e uma construção robusta. Ideal para skaters que buscam confiança e estilo em cada manobra.</p>'),
(9, 9, '8.25\"', 'Polar Skate Co.', 10, '<p>Assinado por Nick Boserio, o Voices Deck combina arte expressiva com a qualidade reconhecida da Polar Skate Co. Um deck versátil que atende tanto street quanto park skating.</p>'),
(10, 10, '8.625\"', 'Polar Skate Co.', 10, '<p>O Bunny Deck de Aaron Herrington destaca-se pelo seu design artístico e pela largura que proporciona maior estabilidade. Perfeito para skaters que valorizam estética e performance.</p>'),
(11, 11, '8.5\"', 'Polar Skate Co.', 10, '<p>Com uma arte intrigante, o Headless Angel Deck de Shin Sanbongi é uma peça que chama atenção tanto pela estética quanto pela funcionalidade. Construído para oferecer uma experiência de skate fluida e responsiva.</p>');

-- --------------------------------------------------------

--
-- Estrutura da tabela `encomendas`
--

CREATE TABLE `encomendas` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `morada` varchar(255) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `codigo_encomenda` varchar(20) NOT NULL,
  `data_encomenda` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `encomendas`
--

INSERT INTO `encomendas` (`id`, `user_id`, `morada`, `total`, `codigo_encomenda`, `data_encomenda`) VALUES
(1, 16, '', 85.00, 'F7017E9722', '2025-05-14 08:52:48'),
(2, 16, '', 90.00, 'ACA7F50D57', '2025-05-14 08:54:24');

-- --------------------------------------------------------

--
-- Estrutura da tabela `encomenda_produtos`
--

CREATE TABLE `encomenda_produtos` (
  `id` int(11) NOT NULL,
  `encomenda_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `encomenda_produtos`
--

INSERT INTO `encomenda_produtos` (`id`, `encomenda_id`, `produto_id`, `quantidade`, `preco_unitario`) VALUES
(1, 1, 9, 1, 85.00),
(2, 2, 5, 1, 90.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pants`
--

CREATE TABLE `pants` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `tamanho` varchar(10) DEFAULT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `estoque` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `pants`
--

INSERT INTO `pants` (`id`, `produto_id`, `tamanho`, `marca`, `estoque`) VALUES
(1, 22, 'M', 'Carhartt', 5),
(2, 22, 'M', 'Carhartt', 5),
(3, 22, 'M', 'Carhartt', 5);

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
(1, 'Polar Skate Co. Toba - David Stenstrom Debut Pro Deck', 'Griptape included', 85.00, 'skateboard1.png', 1, '8.5', 'decks', 'Polar Skate Co.', 'polar.png'),
(3, 'Ace AF1 Trucks Polished Pair', 'Par de trucks Ace AF1 com resistência superior.', 74.99, 'truckAF1.jpg', 2, '8.25', 'trucks', 'Ace Trucks MFG', 'ace.png'),
(4, 'Fucking Awesome-Curren Caples Remember To Forget Deck', 'Free Griptape Included\r\n\r\nRandom Wood Veneer Selected.\r\n\r\n8.25\" x 31.79\"\r\n14.12\" Wheel Base', 90.00, 'skateboard3.png', 1, '8', 'decks', 'Fucking Awesome', 'fuckingawesome.png'),
(5, 'Fucking Awesome Gino Iannucci Daybreak in Phuket Deck', '<p>Free Griptape Included</p>\r\n\r\n<p>Random Wood Veneer Selected.</p>\r\n\r\n<p>Shape #1</p>\r\n\r\n<p>8.0\" x 31.66\"</p>\r\n<p>14\" Wheel Base.</p>\r\n\r\n<p>8.25\" x 31.79\"\r\n<p>14.12\" Wheel Base</p>', 90.00, 'skateboard6.jpeg', 1, '8´0', 'decks', 'Fucking Awesome', 'fuckingawesome.png'),
(6, 'Ace Trucks MFG AF1 Carhartt WIP Trucks - Carhartt Orange / Polished', '<p>The Ace AF1 Carhartt WIP Trucks are made in collaboration with California brand Ace, and are constructed from a combination of aluminum alloy, steel and polyurethane. The trucks are detailed with a safety baseplate in Carhartt WIP orange, and feature an engraved hanger.</p>\r\n\r\n<p>-44 (8.25’’) / 55 (8.5’’)</p>\r\n\r\n<p>-67% Aluminum alloy, 29% steel, 4% polyurethane</p>\r\n\r\n<p>-Set of 2</p>\r\n\r\n<p>-Polished</p>\r\n\r\n<p>-Branded engraved hanger</p>\r\n\r\n<p>-Exclusive anodized orange baseplate</p>\r\n\r\n<p>-Includes anodized axle orange re-threader die</p>', 75.00, 'Stage5Trucks.webp', 2, '8\'55', 'trucks', 'Ace Trucks MFG', 'ace.png'),
(7, 'Thaynan Costa Pro Deck', '<p>O deck profissional de Thaynan Costa pela Yardsale apresenta um design exclusivo que reflete o estilo único do skater brasileiro. Construído com materiais de alta qualidade, oferece durabilidade e performance para todos os níveis de skate.</p>', 80.00, 'thayboard.jpg', 1, '', 'decks', 'Yardsale', NULL),
(8, 'Trust Deck - Oskar Rozenberg', '<p>Oskar Rozenberg Signature Model<br>Artwork by Jacob Ovgren<br>Griptape included</p>\r\n<p>8.25\" X 32\"<br>Nose: 6.9\"<br>Wheel Base: 14.25\"<br>Tail: 6.55\"</p>\r\n<p>8.5&rdquo; X 31.9&rdquo; - Short<br>Nose: 7.0&rdquo;<br>Wheel Base: 14.125&rdquo;<br>Tail: 6.5&rdquo;</p>\r\n<p>&nbsp;</p>\r\n<p>Manufactured by BBS<br>Made in Mexico</p>', 85.00, '6825a3161f9c1_PolarTrustDeck2.png', 1, '', 'decks', 'Polar Skate Co.', 'polar.png'),
(9, 'Voices Deck - Nick Boserio', '<p>Nick Boserio Pro Model</p>\n<p>Artwork by Sirus F Gahan</p>\n<p>8.50\" Width x 32.125\" Length</p>\n<p>Wheelbase: 14.50\"</p>\n<p>Traditional Maple Construction</p>', 85.00, '5056336688608-2.webp', 1, '', 'decks', 'Polar Skate Co.', 'polar.png'),
(10, 'Bunny Deck - Aaron Herrington', '<p>Width: 8.625\"</p>\r\n<p>Length: 32.2\"</p>\r\n<p>Nose: 7.0\"</p>\r\n<p>Wheel Base: 14.375\"</p>\r\n<p>Tail: 6.55\"</p>\r\n<p>Artwork: Sirus F Gahan</p>\r\n\r\n', 85.00, 'polar-aaron-herrington-bunny-8.625-skateboard-deck.webp', 1, '', 'decks', 'Polar Skate Co.', 'polar.png'),
(11, 'Headless Angel Deck - Shin Sanbongi', '<p>Width: 9.25\"</p>\r\n<p>Length: 32.125\"</p>\r\n<p>Wheel Base: 14.5\"</p>\r\n<p>Nose: 7.0\"</p>\r\n<p>Tail:6.5\"</p>\r\n<p>Manufactured by BBS</p>\r\n<p>Made in Mexico</p>', 85.00, 'Polar-Shin-Sanbongi-Headless-Angel-Deck-9.25-2.webp', 1, '', 'decks', 'Polar Skate Co.', 'polar.png'),
(12, 'Butter Wired Tee - Fudge', '• 6.5oz (220 gsm) 100% Cotton T-Shirt\r\n• All Over Printed Pattern', 50.00, 'WiredTeeFudge1_1500x_38cc6931-e136-424b-8f08-0c3af4268778.webp', 5, '0', 'T-shirts', 'Butter', NULL),
(15, 'Fucking Awesome Madonna AOP Thermal Longsleeve - Multi', '<p>Cotton/Acrylic crewneck.</p>\r\n<p>Printed Artwork All Over.</p>\r\n<p>LongSleeve</p>', 90.00, 'FA_NEW_0007_Ebene4.webp', 6, '0', 'Sweats', 'Butter', NULL),
(19, 'Spitfire Formula Four Radials - 99A', '<p>Spitfire Formula Four Wheels</p>\r\n<p>99DU</p>\r\n<p>Radial&nbsp;shape</p>\r\n<p>Natural Urethane</p>\r\n<p>Set of Four</p>\r\n<p>100% True performance urethane</p>\r\n<p>Unmatched flatspot resistance</p>\r\n<p>The size in the wheel picture may be only figurative, please choose the size you wish in the available sizes section</p>', 75.00, '6825ec550d652_SPI-SKW-6061.webp', 3, '56', 'Rodas', 'Spitfire', NULL),
(20, 'T-shirt Thrasher Flame Logo', 'T-shirt 100% algodão com logótipo Flame da Thrasher.', 29.99, NULL, 5, '', 'T-shirts', NULL, NULL),
(21, 'Sweat Nike SB Icon Hoodie', 'Sweat com capuz Nike SB em algodão e poliéster.', 64.90, NULL, 6, '', 'Sweats', NULL, NULL),
(22, 'Pants Dickies 874 Work Pant', 'Calças clássicas de trabalho Dickies, resistentes e confortáveis.', 54.99, NULL, 7, '', 'Pants', NULL, NULL),
(23, 'Shorts Polar Surf Shorts', 'Shorts leves da Polar Skate Co. para dias quentes.', 49.99, NULL, 8, '', 'Shorts', NULL, NULL),
(24, 'Nike SB Zoom Blazer Mid', 'Sapatos de skate Nike SB com sola Zoom Air.', 89.90, NULL, 9, '', 'Sapato', NULL, NULL),
(25, 'Trucks Independent 139', 'Par de trucks Independent Stage 11 para decks 7.75\"-8.25\".', 67.50, NULL, 2, '', 'Trucks', NULL, NULL),
(26, 'Rodas Spitfire Formula Four 52mm', 'Rodas de skate Spitfire para street e park.', 44.99, NULL, 3, '', 'Rodas', NULL, NULL),
(27, 'Rolamentos Bones Reds', 'Rolamentos Bones Reds, os mais populares do mercado.', 22.00, NULL, 4, '', 'Rolamentos', NULL, NULL),
(28, 'Beanie Vans Core Basics', 'Gorro básico da Vans com logo bordado.', 19.99, NULL, 10, '', 'Acessórios', NULL, NULL),
(29, 'Cinto Independent Cross Belt', 'Cinto em lona da Independent com fivela metálica.', 24.99, NULL, 10, '', 'Acessórios', NULL, NULL);

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

--
-- Extraindo dados da tabela `rodas`
--

INSERT INTO `rodas` (`id`, `produto_id`, `tamanho`, `dureza`, `marca`, `estoque`, `descricao`) VALUES
(1, 19, '56mm', '99A', 'Spitfire', 10, '<ul>\r\n<li>Spitfire Formula Four Wheels</li>\r\n<li>99DU</li>\r\n<li>Radial&nbsp;shape</li>\r\n<li>Natural Urethane</li>\r\n<li>Set of Four</li>\r\n<li>100% True performance urethane</li>\r\n<li>Unmatched flatspot resistance</li>\r\n</ul>\r\n<p>The size in the wheel picture may be only figurative, please choose the size you wish in the available sizes section</p>'),
(2, 26, '53mm', '', 'OJ Wheels', 13, NULL),
(3, 26, '53mm', '', 'OJ Wheels', 13, NULL),
(4, 26, '53mm', '', 'OJ Wheels', 13, NULL);

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
-- Estrutura da tabela `sapatos`
--

CREATE TABLE `sapatos` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `tamanho` varchar(10) DEFAULT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `estoque` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `sapatos`
--

INSERT INTO `sapatos` (`id`, `produto_id`, `tamanho`, `marca`, `estoque`) VALUES
(1, 23, 'L', 'Nike SB', 6),
(2, 24, '43', 'Vans', 9),
(3, 23, 'L', 'Nike SB', 6);

-- --------------------------------------------------------

--
-- Estrutura da tabela `shorts`
--

CREATE TABLE `shorts` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `tamanho` varchar(10) DEFAULT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `estoque` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sweats`
--

CREATE TABLE `sweats` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `tamanho` enum('XS','S','M','L','XL') NOT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `estoque` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `sweats`
--

INSERT INTO `sweats` (`id`, `produto_id`, `tamanho`, `marca`, `estoque`) VALUES
(1, 21, 'L', 'Polar SKate Co.', 7),
(2, 21, 'L', 'Polar SKate Co.', 7),
(3, 21, 'L', 'Polar SKate Co.', 7);

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
(4, 6, '55', 'Ace Trucks MFG', 10, '<p>The Ace AF1 Carhartt WIP Trucks are made in collaboration with California brand Ace, and are constructed from a combination of aluminum alloy, steel and polyurethane. The trucks are detailed with a safety baseplate in Carhartt WIP orange, and feature an engraved hanger.</p>\n\n<p>-44 (8.25’’) / 55 (8.5’’)</p>\n\n<p>-67% Aluminum alloy, 29% steel, 4% polyurethane</p>\n\n<p>-Set of 2</p>\n\n<p>-Polished</p>\n\n<p>-Branded engraved hanger</p>\n\n<p>-Exclusive anodized orange baseplate</p>\n\n<p>-Includes anodized axle orange re-threader die</p>'),
(6, 25, '147', 'Thunder', 11, NULL),
(7, 25, '147', 'Thunder', 11, NULL),
(8, 25, '147', 'Thunder', 11, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tshirts`
--

CREATE TABLE `tshirts` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `tamanho` enum('XS','S','M','L','XL') NOT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `estoque` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tshirts`
--

INSERT INTO `tshirts` (`id`, `produto_id`, `tamanho`, `marca`, `estoque`) VALUES
(2, 12, 'M', 'Butter', 20),
(5, 20, 'M', 'Santa Cruz', 12),
(6, 20, 'M', 'Santa Cruz', 12),
(7, 20, 'M', 'Santa Cruz', 12);

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
(16, 'Joa1', '$2y$10$6GjOF/j9gCaEjimCnSXCPuq2dnFeIEu6UJ3DQoOYnNhioWMSIQR6e', 'user', '2025-04-22 16:38:40', 'joaopedroantunes1980@gmail.com', 'Avenida de Portugal n44,Póvoa da Galega', NULL, NULL, NULL),
(25, 'Paullo', '$2y$10$4QPlnlscUtV2xqJAh/3Kee1CftJvgDa8HLU1hH3o.1F5itQ9IHtWi', '', '2025-05-09 21:01:37', 'joaopedroantunesps4@gmail.com', NULL, NULL, NULL, NULL),
(26, 'Leidi', '$2y$10$p5BoHnLGLfqz9R5.A8Kd7O35vw38dEWzMXWLMcNz0Qgsg3iYVCUTe', '', '2025-05-09 21:05:26', 'leidantunes@live.com.pt', NULL, NULL, NULL, NULL),
(27, 'miudo', '$2y$10$qQ9PiY5S40YgSW/SAavWc.w6WnhlLnMmyES7RNRC9WzbasN8ZwFAO', '', '2025-05-09 21:44:14', 'miudogamer0@gmail.com', NULL, NULL, NULL, NULL),
(28, 'bizarro', '$2y$10$E3jEx5gSsfjIfe.p7jaJwulpRlHfBThw8nANOLbGBNxVUu2rBq/U2', '', '2025-05-12 10:04:10', 'dpzibarro@gmail.com', NULL, NULL, NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `acessorios`
--
ALTER TABLE `acessorios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`);

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
-- Índices para tabela `encomendas`
--
ALTER TABLE `encomendas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_encomenda` (`codigo_encomenda`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices para tabela `encomenda_produtos`
--
ALTER TABLE `encomenda_produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `encomenda_id` (`encomenda_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices para tabela `pants`
--
ALTER TABLE `pants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`);

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
-- Índices para tabela `sapatos`
--
ALTER TABLE `sapatos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices para tabela `shorts`
--
ALTER TABLE `shorts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices para tabela `sweats`
--
ALTER TABLE `sweats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices para tabela `trucks`
--
ALTER TABLE `trucks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices para tabela `tshirts`
--
ALTER TABLE `tshirts`
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
-- AUTO_INCREMENT de tabela `acessorios`
--
ALTER TABLE `acessorios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `decks`
--
ALTER TABLE `decks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `encomendas`
--
ALTER TABLE `encomendas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `encomenda_produtos`
--
ALTER TABLE `encomenda_produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `pants`
--
ALTER TABLE `pants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de tabela `rodas`
--
ALTER TABLE `rodas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `rolamentos`
--
ALTER TABLE `rolamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `sapatos`
--
ALTER TABLE `sapatos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `shorts`
--
ALTER TABLE `shorts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `sweats`
--
ALTER TABLE `sweats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `trucks`
--
ALTER TABLE `trucks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `tshirts`
--
ALTER TABLE `tshirts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `acessorios`
--
ALTER TABLE `acessorios`
  ADD CONSTRAINT `acessorios_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

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
-- Limitadores para a tabela `encomendas`
--
ALTER TABLE `encomendas`
  ADD CONSTRAINT `encomendas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Limitadores para a tabela `encomenda_produtos`
--
ALTER TABLE `encomenda_produtos`
  ADD CONSTRAINT `encomenda_produtos_ibfk_1` FOREIGN KEY (`encomenda_id`) REFERENCES `encomendas` (`id`),
  ADD CONSTRAINT `encomenda_produtos_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);

--
-- Limitadores para a tabela `pants`
--
ALTER TABLE `pants`
  ADD CONSTRAINT `pants_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

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
-- Limitadores para a tabela `sapatos`
--
ALTER TABLE `sapatos`
  ADD CONSTRAINT `sapatos_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `shorts`
--
ALTER TABLE `shorts`
  ADD CONSTRAINT `shorts_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `sweats`
--
ALTER TABLE `sweats`
  ADD CONSTRAINT `sweats_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `trucks`
--
ALTER TABLE `trucks`
  ADD CONSTRAINT `trucks_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `tshirts`
--
ALTER TABLE `tshirts`
  ADD CONSTRAINT `tshirts_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
