-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22-Maio-2025 às 16:48
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
-- Estrutura da tabela `cintos`
--

CREATE TABLE `cintos` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `comprimento` varchar(10) DEFAULT NULL,
  `cor` varchar(20) DEFAULT NULL,
  `marca` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `cintos`
--

INSERT INTO `cintos` (`id`, `produto_id`, `comprimento`, `cor`, `marca`) VALUES
(1, 29, '110cm', 'Preto', 'Independent'),
(2, 49, '105cm', 'Castanho', 'Independent');

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
(1, 1, '8.5\"', 'Polar Skate Co.', 10, NULL),
(2, 4, '8\"', 'Fucking Awesome', 10, 'Free Griptape Included\r\n\r\nRandom Wood Veneer Selected.\r\n\r\nShape #2\r\n\r\n8.25\" x 31.79\"\r\n14.12\" Wheel Base'),
(4, 5, '8.0\"', '', 10, '<p>Free Griptape Included</p>\r\n\r\n<p>Random Wood Veneer Selected.</p>\r\n\r\n<p>Shape #1</p>\r\n\r\n<p>8.0\" x 31.66\"</p>\r\n<p>14\" Wheel Base.</p>\r\n\r\n<p>8.25\" x 31.79\"\r\n<p>14.12\" Wheel Base</p>'),
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
  `data_encomenda` datetime NOT NULL DEFAULT current_timestamp(),
  `metodo_pagamento` varchar(50) DEFAULT NULL,
  `tipo_entrega` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `encomendas`
--

INSERT INTO `encomendas` (`id`, `user_id`, `morada`, `total`, `codigo_encomenda`, `data_encomenda`, `metodo_pagamento`, `tipo_entrega`) VALUES
(1, 16, '', 85.00, 'F7017E9722', '2025-05-14 08:52:48', NULL, NULL),
(2, 16, '', 90.00, 'ACA7F50D57', '2025-05-14 08:54:24', NULL, NULL),
(3, 16, 'Avenida de Portugal n44,Póvoa da Galega', 1265.00, 'EN6827029DB34C8', '0000-00-00 00:00:00', 'Cartão', 'delivery'),
(4, 16, 'Avenida de Portugal n44,Póvoa da Galega', 1055.00, 'EN68270339897A5', '2025-05-16 10:19:53', 'Cartão', 'delivery'),
(5, 9, 'Retirada na loja', 135.00, 'EN682703B7D4B5F', '2025-05-16 10:21:59', 'Cartão', 'pickup'),
(6, 9, 'Retirada na loja', 54.90, 'EN68285CFC2CCA2', '2025-05-17 10:55:08', 'MB WAY', 'pickup'),
(7, 16, 'Avenida de Portugal n44,Póvoa da Galega, 1111-111, MAFRA', 296.89, 'EN6829B891EB34A', '2025-05-18 11:38:09', 'Cartão', 'delivery'),
(8, 16, 'Avenida de Portugal n44,Póvoa da Galega, 1111-111, MAFRA', 362.39, 'EN6829B9DD559CC', '2025-05-18 11:43:41', 'Cartão', 'delivery'),
(9, 27, 'Avenida de Portugal n44,Póvoa da Galega, 1111-111, MAFRA', 9905.00, 'EN6829BCD39E02C', '2025-05-18 11:56:19', 'Cartão', 'delivery'),
(10, 27, 'Retirada na loja', 99999999.99, 'EN6829BECE75E51', '2025-05-18 12:04:46', 'Cartão', 'pickup'),
(11, 27, 'Retirada na loja', 90.00, 'EN6829C073E02EA', '2025-05-18 12:11:47', 'Cartão', 'pickup'),
(12, 27, 'Retirada na loja', 90.00, 'EN6829C195CB944', '2025-05-18 12:16:37', 'Cartão', 'pickup'),
(13, 16, 'Retirada na loja', 99999999.99, 'EN6829C2188B734', '2025-05-18 12:18:48', 'Cartão', 'pickup'),
(14, 16, 'Retirada na loja', 90.00, 'EN6829C456D5FCC', '2025-05-18 12:28:22', 'Cartão', 'pickup'),
(15, 16, 'Retirada na loja', 99999999.99, 'EN6829C4E9CA38F', '2025-05-18 12:30:49', 'Cartão', 'pickup'),
(16, 16, 'Avenida de Portugal n44,Póvoa da Galega, 123-111, 123', 95.00, 'EN682A079037F57', '2025-05-18 17:15:12', 'Cartão', 'delivery'),
(17, 9, 'Retirada na loja', 199.89, 'EN682A686D9600B', '2025-05-19 00:08:29', 'Cartão', 'pickup'),
(18, 9, 'Retirada na loja', 139.90, 'EN682AFFB4E9F02', '2025-05-19 10:53:56', 'Cartão', 'pickup'),
(19, 9, 'Retirada na loja', 194.89, 'EN682BA1091D453', '2025-05-19 22:22:17', 'Cartão', 'pickup'),
(20, 16, 'Retirada na loja', 240.00, 'EN682C6C4A554A3', '2025-05-20 12:49:30', 'Cartão', 'pickup');

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
(2, 2, 5, 1, 90.00),
(3, 3, 4, 14, 90.00),
(4, 4, 19, 14, 75.00),
(5, 5, 12, 1, 50.00),
(6, 5, 1, 1, 85.00),
(7, 6, 50, 1, 54.90),
(8, 7, 27, 1, 22.00),
(9, 7, 6, 1, 75.00),
(10, 7, 49, 1, 19.99),
(11, 7, 24, 1, 89.90),
(12, 7, 53, 1, 85.00),
(13, 8, 5, 1, 90.00),
(14, 8, 28, 1, 19.99),
(15, 8, 24, 1, 89.90),
(16, 8, 15, 1, 90.00),
(17, 8, 25, 1, 67.50),
(18, 9, 5, 110, 90.00),
(19, 10, 10, 1123456789, 85.00),
(20, 11, 4, 1, 90.00),
(21, 12, 4, 1, 90.00),
(22, 13, 28, 2147483647, 19.99),
(23, 14, 5, 1, 90.00),
(24, 15, 28, 2147483647, 19.99),
(25, 16, 4, 1, 90.00),
(26, 17, 4, 1, 90.00),
(27, 17, 28, 1, 19.99),
(28, 17, 24, 1, 89.90),
(29, 18, 12, 1, 50.00),
(30, 18, 24, 1, 89.90),
(31, 19, 28, 1, 19.99),
(32, 19, 24, 1, 89.90),
(33, 19, 53, 1, 85.00),
(34, 20, 103, 12, 20.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `gorros`
--

CREATE TABLE `gorros` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `tamanho` varchar(20) DEFAULT NULL,
  `cor` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `gorros`
--

INSERT INTO `gorros` (`id`, `produto_id`, `tamanho`, `cor`) VALUES
(1, 28, 'Único', 'Preto'),
(11, 103, 'Único', 'Preto');

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
(1, 22, 'M', 'Carhartt', 5);

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
(7, 'Thaynan Costa Pro Deck', '<p>O deck profissional de Thaynan Costa pela Yardsale apresenta um design exclusivo que reflete o estilo único do skater brasileiro. Construído com materiais de alta qualidade, oferece durabilidade e performance para todos os níveis de skate.</p>', 80.00, 'thayboard.jpg', 1, '', 'decks', 'Yardsale', 'yardsale.png'),
(8, 'Trust Deck - Oskar Rozenberg', '<p>Oskar Rozenberg Signature Model<br>Artwork by Jacob Ovgren<br>Griptape included</p>\r\n<p>8.25\" X 32\"<br>Nose: 6.9\"<br>Wheel Base: 14.25\"<br>Tail: 6.55\"</p>\r\n<p>8.5&rdquo; X 31.9&rdquo; - Short<br>Nose: 7.0&rdquo;<br>Wheel Base: 14.125&rdquo;<br>Tail: 6.5&rdquo;</p>\r\n<p>&nbsp;</p>\r\n<p>Manufactured by BBS<br>Made in Mexico</p>', 85.00, '6825a3161f9c1_PolarTrustDeck2.png', 1, '', 'decks', 'Polar Skate Co.', 'polar.png'),
(9, 'Voices Deck - Nick Boserio', '<p>Nick Boserio Pro Model</p>\n<p>Artwork by Sirus F Gahan</p>\n<p>8.50\" Width x 32.125\" Length</p>\n<p>Wheelbase: 14.50\"</p>\n<p>Traditional Maple Construction</p>', 85.00, '5056336688608-2.webp', 1, '', 'decks', 'Polar Skate Co.', 'polar.png'),
(10, 'Bunny Deck - Aaron Herrington', '<p>Width: 8.625\"</p>\r\n<p>Length: 32.2\"</p>\r\n<p>Nose: 7.0\"</p>\r\n<p>Wheel Base: 14.375\"</p>\r\n<p>Tail: 6.55\"</p>\r\n<p>Artwork: Sirus F Gahan</p>\r\n\r\n', 85.00, 'polar-aaron-herrington-bunny-8.625-skateboard-deck.webp', 1, '', 'decks', 'Polar Skate Co.', 'polar.png'),
(11, 'Headless Angel Deck - Shin Sanbongi', '<p>Width: 9.25\"</p>\r\n<p>Length: 32.125\"</p>\r\n<p>Wheel Base: 14.5\"</p>\r\n<p>Nose: 7.0\"</p>\r\n<p>Tail:6.5\"</p>\r\n<p>Manufactured by BBS</p>\r\n<p>Made in Mexico</p>', 85.00, 'Polar-Shin-Sanbongi-Headless-Angel-Deck-9.25-2.webp', 1, '', 'decks', 'Polar Skate Co.', 'polar.png'),
(12, 'Butter Wired Tee - Fudge', '• 6.5oz (220 gsm) 100% Cotton T-Shirt\r\n• All Over Printed Pattern', 50.00, 'WiredTeeFudge1_1500x_38cc6931-e136-424b-8f08-0c3af4268778.webp', 5, '0', 'T-shirts', 'Butter', 'butter.png'),
(15, 'Fucking Awesome Madonna AOP Thermal Longsleeve - Multi', '<p>Cotton/Acrylic crewneck.</p>\r\n<p>Printed Artwork All Over.</p>\r\n<p>LongSleeve</p>', 90.00, 'FA_NEW_0007_Ebene4.webp', 6, '0', 'Sweats', 'Butter', 'butter.png'),
(19, 'Spitfire Formula Four Radials - 99A', '<p>Spitfire Formula Four Wheels</p>\r\n<p>99DU</p>\r\n<p>Radial&nbsp;shape</p>\r\n<p>Natural Urethane</p>\r\n<p>Set of Four</p>\r\n<p>100% True performance urethane</p>\r\n<p>Unmatched flatspot resistance</p>\r\n<p>The size in the wheel picture may be only figurative, please choose the size you wish in the available sizes section</p>', 75.00, '6825ec550d652_SPI-SKW-6061.webp', 3, '56', 'Rodas', 'Spitfire', 'spitfire.png'),
(21, 'Sweat Nike SB Icon Hoodie', '<p>Sweat com capuz Nike SB em algod&atilde;o e poli&eacute;ster.</p>', 64.90, '6826f431bd340_4d7cd0fb9dca4a10bb7d10df24c75490-removebg-preview.png', 6, '', 'Sweats', 'NikeSB', 'nikesb.png'),
(22, 'Dickies 874 Unisex Work Pants', '<p><strong>Tipo de cal&ccedil;as:&nbsp;</strong>Chino</p>\r\n<p><strong>G&eacute;nero:&nbsp;</strong>Unisexo</p>\r\n<p><strong>Corte da Pe&ccedil;a:&nbsp;</strong>Straight</p>\r\n<p><strong>Cor:&nbsp;</strong>Bege</p>\r\n<p><strong>Materiais:&nbsp;</strong>Algod&atilde;o e Pol&eacute;ster</p>\r\n<p><strong>Sustentabilidade:&nbsp;</strong>Materiais Reciclados</p>', 54.99, '6826ee6d1f9ec_dickies-removebg-preview.png', 7, '', 'Pants', 'Dickies', 'dickies.png'),
(23, 'Shorts Polar Surf Shorts', 'Shorts leves da Polar Skate Co. para dias quentes.', 49.99, '6826eb4877c53_Polar-Skate-Co-surf-shorts-black-collective-500x500.jpg', 8, '', 'Shorts', 'Polar Skate Co.', 'polar.png'),
(24, 'Nike SB Zoom Blazer Mid', '<p>Sapatos de skate Nike SB com sola Zoom Air.</p>', 89.90, '864349-007-PHSRH000-2000_2000x_03a472f5-8ccd-4970-bd14-d857508e0b43.png', 9, '', 'Sapato', 'NikeSB', 'nikesb.png'),
(25, 'Trucks Independent 139', '<p>Par de trucks Independent Stage 11 para decks 7.75\"-8.25\".</p>', 67.50, '682659156682d_id0tr0033_0_1.png', 2, '', 'Trucks', 'Independent', 'independent.png'),
(26, 'Rodas Spitfire Formula Four 52mm', '<p>Rodas de skate Spitfire para</p>\r\n<p>Conjunto das 4 rodas.</p>\r\n<p>Di&acirc;metro: 52mm.</p>\r\n<p>Dureza: 101A.</p>\r\n<p>As rodas Spitfire de 51mm, 52mm, e 53mm s&atilde;o perfeitas para&nbsp;<em>street</em>,&nbsp;<em>curbs</em>,&nbsp;<em>manualpads</em>&nbsp;ou mesmo&nbsp;<em>skatepark</em>&nbsp;com rampas pequenas, para manobras mais t&eacute;cnicas.</p>\r\n<p>Rodas Spitfire dispon&iacute;veis em diferentes di&acirc;metros e durez</p>\r\n<p>street e park.</p>', 44.99, 'rodas-spitfire-formula-four-conical-full-52-mm-101du.webp', 3, '', 'Rodas', 'Spitfire', 'spitfire.png'),
(27, 'Rolamentos Bones Reds', 'Rolamentos Bones Reds, os mais populares do mercado.', 22.00, 'rolamentos-bones-reds-1.jpg', 4, '', 'Rolamentos', 'Bones Reds', 'redbones.png'),
(28, 'Beanie Vans Core Basics', '<p>Gorro b&aacute;sico da Vans com logo bordado.</p>', 19.99, 'beanievans.webp', 10, '', 'gorros', 'Vans', 'vans.png'),
(29, 'Cinto Independent Cross Belt', '<p>Cinto em lona da Independent com fivela met&aacute;lica.</p>', 24.99, 'cintoidenpendet.jpeg', 10, '', 'Cintos', 'Independent', 'independent.png'),
(49, 'Cinto Independent Span', '<p>Cinto forte e resistente com logo.</p>', 19.99, 'cintoidenpendet12.jpeg', 10, '', 'Cintos', 'Independent', 'independent.png'),
(50, 'Calção Dickies Work Short', '<p>Loose fit</p>\r\n<p>65% recycled polyester, 35% cotton</p>\r\n<p>High-waist</p>\r\n<p>Five-pocket design</p>\r\n<p>Woven logo</p>', 54.90, '6826f3927aa40_6826efe56d963_dk0a4xozdnx1321-removebg-preview.png', 8, '', 'Shorts', 'Dickies', 'dickies.png'),
(53, 'Sk8Nation T-shirts', '', 85.00, 'img_68273d0e1a981.png', 5, '0', 'T-shirts', 'Sk8Nation', 'Sk8Nationlogo.png'),
(101, 'Sk8Nation Deck Limited Edition', '', 40.00, 'img_682a065ff2046.png', 1, '8', 'Deck', 'Sk8Nation', 'Sk8Nationlogo.png'),
(102, 'Sk8Nation Purple Deck Limited Edition', '<p>Free griptape included</p>', 45.00, 'polar-aaron-herrington-bunny-8.625-skateboard-deck.webp', 1, '8', 'Deck', 'Sk8Nation', 'Sk8Nationlogo.png'),
(103, 'Sk8Nation Beanie', '<p>Tamanho &uacute;nico&nbsp;</p>', 20.00, 'img_682a0c094ad6e.png', 10, '0', 'gorros', 'Sk8Nation', 'Sk8Nationlogo.png');

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
(2, 26, '53mm', '', 'OJ Wheels', 13, NULL);

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

--
-- Extraindo dados da tabela `rolamentos`
--

INSERT INTO `rolamentos` (`id`, `produto_id`, `tipo`, `marca`, `estoque`, `descricao`) VALUES
(1, 27, 'ABEC 7', 'Bones Reds', 50, 'Rolamentos Bones Reds com esferas de aço inoxidável, gaiola de nylon e lubrificação Speed Cream para desempenho superior e durabilidade');

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
(1, 23, 'L', 'Nike SB', 6);

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

--
-- Extraindo dados da tabela `shorts`
--

INSERT INTO `shorts` (`id`, `produto_id`, `tamanho`, `marca`, `estoque`) VALUES
(1, 23, '', 'Polar Skate Co.', 10),
(3, 50, '', 'Dickies', 10);

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
-- Estrutura da tabela `tamanhos_produto`
--

CREATE TABLE `tamanhos_produto` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `tamanho` varchar(5) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `disponivel` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tamanhos_produto`
--

INSERT INTO `tamanhos_produto` (`id`, `produto_id`, `tamanho`, `stock`, `disponivel`) VALUES
(1, 1, '7.0\"', 0, 1),
(2, 1, '7.25\"', 0, 1),
(3, 1, '7.5\"', 0, 1),
(4, 1, '7.75\"', 0, 1),
(5, 1, '8.0\"', 0, 0),
(6, 1, '8.25\"', 0, 0),
(7, 1, '8.5\"', 0, 1),
(8, 1, '8.75\"', 0, 1),
(9, 1, '9.0\"', 0, 0),
(10, 1, '9.25\"', 0, 0),
(11, 1, '9.5\"', 0, 1),
(12, 1, '9.75\"', 0, 0),
(13, 1, '10.0\"', 0, 0),
(14, 4, '7.0\"', 0, 0),
(15, 4, '7.25\"', 0, 0),
(16, 4, '7.5\"', 0, 1),
(17, 4, '7.75\"', 0, 1),
(18, 4, '8.0\"', 0, 1),
(19, 4, '8.25\"', 0, 1),
(20, 4, '8.5\"', 0, 1),
(21, 4, '8.75\"', 0, 0),
(22, 4, '9.0\"', 0, 0),
(23, 4, '9.25\"', 0, 0),
(24, 4, '9.5\"', 0, 0),
(25, 4, '9.75\"', 0, 1),
(26, 4, '10.0\"', 0, 1),
(27, 5, '7.0\"', 0, 1),
(28, 5, '7.25\"', 0, 0),
(29, 5, '7.5\"', 0, 0),
(30, 5, '7.75\"', 0, 0),
(31, 5, '8.0\"', 0, 1),
(32, 5, '8.25\"', 0, 0),
(33, 5, '8.5\"', 0, 0),
(34, 5, '8.75\"', 0, 1),
(35, 5, '9.0\"', 0, 1),
(36, 5, '9.25\"', 0, 0),
(37, 5, '9.5\"', 0, 1),
(38, 5, '9.75\"', 0, 1),
(39, 5, '10.0\"', 0, 0),
(40, 7, '7.0\"', 0, 1),
(41, 7, '7.25\"', 0, 1),
(42, 7, '7.5\"', 0, 1),
(43, 7, '7.75\"', 0, 0),
(44, 7, '8.0\"', 0, 0),
(45, 7, '8.25\"', 0, 0),
(46, 7, '8.5\"', 0, 0),
(47, 7, '8.75\"', 0, 0),
(48, 7, '9.0\"', 0, 1),
(49, 7, '9.25\"', 0, 0),
(50, 7, '9.5\"', 0, 0),
(51, 7, '9.75\"', 0, 0),
(52, 7, '10.0\"', 0, 0),
(53, 8, '7.0\"', 0, 0),
(54, 8, '7.25\"', 0, 0),
(55, 8, '7.5\"', 0, 0),
(56, 8, '7.75\"', 0, 1),
(57, 8, '8.0\"', 0, 1),
(58, 8, '8.25\"', 0, 1),
(59, 8, '8.5\"', 0, 0),
(60, 8, '8.75\"', 0, 1),
(61, 8, '9.0\"', 0, 1),
(62, 8, '9.25\"', 0, 1),
(63, 8, '9.5\"', 0, 0),
(64, 8, '9.75\"', 0, 0),
(65, 8, '10.0\"', 0, 0),
(66, 9, '7.0\"', 0, 1),
(67, 9, '7.25\"', 0, 1),
(68, 9, '7.5\"', 0, 1),
(69, 9, '7.75\"', 0, 1),
(70, 9, '8.0\"', 0, 1),
(71, 9, '8.25\"', 0, 1),
(72, 9, '8.5\"', 0, 0),
(73, 9, '8.75\"', 0, 0),
(74, 9, '9.0\"', 0, 1),
(75, 9, '9.25\"', 0, 1),
(76, 9, '9.5\"', 0, 0),
(77, 9, '9.75\"', 0, 0),
(78, 9, '10.0\"', 0, 0),
(79, 10, '7.0\"', 0, 0),
(80, 10, '7.25\"', 0, 1),
(81, 10, '7.5\"', 0, 1),
(82, 10, '7.75\"', 0, 1),
(83, 10, '8.0\"', 0, 0),
(84, 10, '8.25\"', 0, 0),
(85, 10, '8.5\"', 0, 0),
(86, 10, '8.75\"', 0, 0),
(87, 10, '9.0\"', 0, 0),
(88, 10, '9.25\"', 0, 1),
(89, 10, '9.5\"', 0, 1),
(90, 10, '9.75\"', 0, 1),
(91, 10, '10.0\"', 0, 1),
(92, 11, '7.0\"', 0, 1),
(93, 11, '7.25\"', 0, 1),
(94, 11, '7.5\"', 0, 1),
(95, 11, '7.75\"', 0, 0),
(96, 11, '8.0\"', 0, 1),
(97, 11, '8.25\"', 0, 1),
(98, 11, '8.5\"', 0, 1),
(99, 11, '8.75\"', 0, 1),
(100, 11, '9.0\"', 0, 0),
(101, 11, '9.25\"', 0, 1),
(102, 11, '9.5\"', 0, 0),
(103, 11, '9.75\"', 0, 0),
(104, 11, '10.0\"', 0, 1),
(105, 3, '129mm', 0, 1),
(106, 3, '139mm', 0, 1),
(107, 3, '149mm', 0, 0),
(108, 3, '159mm', 0, 0),
(109, 3, '169mm', 0, 0),
(110, 6, '129mm', 0, 1),
(111, 6, '139mm', 0, 0),
(112, 6, '149mm', 0, 1),
(113, 6, '159mm', 0, 1),
(114, 6, '169mm', 0, 1),
(115, 25, '129mm', 0, 1),
(116, 25, '139mm', 0, 0),
(117, 25, '149mm', 0, 0),
(118, 25, '159mm', 0, 0),
(119, 25, '169mm', 0, 0),
(120, 19, '49mm', 0, 1),
(121, 19, '50mm', 0, 0),
(122, 19, '51mm', 0, 0),
(123, 19, '52mm', 0, 1),
(124, 19, '53mm', 0, 1),
(125, 19, '54mm', 0, 0),
(126, 19, '55mm', 0, 0),
(127, 19, '56mm', 0, 0),
(128, 19, '57mm', 0, 0),
(129, 19, '58mm', 0, 0),
(130, 19, '59mm', 0, 0),
(131, 19, '60mm', 0, 0),
(132, 19, '61mm', 0, 0),
(133, 19, '62mm', 0, 0),
(134, 19, '63mm', 0, 1),
(135, 19, '64mm', 0, 1),
(136, 19, '65mm', 0, 1),
(137, 19, '66mm', 0, 1),
(138, 19, '67mm', 0, 1),
(139, 19, '68mm', 0, 0),
(140, 19, '69mm', 0, 0),
(141, 19, '70mm', 0, 1),
(142, 19, '71mm', 0, 0),
(143, 19, '72mm', 0, 1),
(144, 19, '73mm', 0, 1),
(145, 19, '74mm', 0, 1),
(146, 19, '75mm', 0, 0),
(147, 26, '49mm', 0, 1),
(148, 26, '50mm', 0, 0),
(149, 26, '51mm', 0, 1),
(150, 26, '52mm', 0, 1),
(151, 26, '53mm', 0, 1),
(152, 26, '54mm', 0, 1),
(153, 26, '55mm', 0, 0),
(154, 26, '56mm', 0, 1),
(155, 26, '57mm', 0, 1),
(156, 26, '58mm', 0, 0),
(157, 26, '59mm', 0, 0),
(158, 26, '60mm', 0, 1),
(159, 26, '61mm', 0, 1),
(160, 26, '62mm', 0, 0),
(161, 26, '63mm', 0, 1),
(162, 26, '64mm', 0, 0),
(163, 26, '65mm', 0, 0),
(164, 26, '66mm', 0, 1),
(165, 26, '67mm', 0, 1),
(166, 26, '68mm', 0, 1),
(167, 26, '69mm', 0, 0),
(168, 26, '70mm', 0, 1),
(169, 26, '71mm', 0, 0),
(170, 26, '72mm', 0, 0),
(171, 26, '73mm', 0, 0),
(172, 26, '74mm', 0, 0),
(173, 26, '75mm', 0, 1),
(174, 27, 'XS', 0, 1),
(175, 27, 'S', 0, 1),
(176, 27, 'M', 0, 0),
(177, 27, 'L', 0, 0),
(178, 24, '38', 0, 1),
(179, 24, '39', 0, 0),
(180, 24, '40', 0, 1),
(181, 24, '41', 0, 0),
(182, 24, '42', 0, 0),
(183, 24, '43', 0, 0),
(184, 24, '44', 0, 0),
(185, 24, '45', 0, 0),
(186, 24, '46', 0, 1),
(187, 24, '47', 0, 0);

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
(6, 25, '147', 'Thunder', 11, NULL);

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
(8, 53, 'M', 'Sk8Nation', 10);

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
(25, 'Paullo', '$2y$10$z2daoYae3WruJSGO9BKPHulg6fNfvVUJ/E4lFg2.fIh/ky2o2vfnK', 'user', '2025-05-09 21:01:37', 'joaopedroantunesps4@gmail.com', NULL, NULL, NULL, NULL),
(27, 'miudo', '$2y$10$qQ9PiY5S40YgSW/SAavWc.w6WnhlLnMmyES7RNRC9WzbasN8ZwFAO', 'user', '2025-05-09 21:44:14', 'miudogamer0@gmail.com', NULL, NULL, NULL, NULL),
(28, 'bizarro', '$2y$10$E3jEx5gSsfjIfe.p7jaJwulpRlHfBThw8nANOLbGBNxVUu2rBq/U2', 'user', '2025-05-12 10:04:10', 'dpzibarro@gmail.com', NULL, NULL, 'f971dccd060fd74af0930670ebfca0fbb5d3649c02d16ab2c3983dd2cf175a4a', '2025-05-18 19:15:44');

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
-- Índices para tabela `cintos`
--
ALTER TABLE `cintos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`);

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
-- Índices para tabela `gorros`
--
ALTER TABLE `gorros`
  ADD PRIMARY KEY (`id`),
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
-- Índices para tabela `tamanhos_produto`
--
ALTER TABLE `tamanhos_produto`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `cintos`
--
ALTER TABLE `cintos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `decks`
--
ALTER TABLE `decks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `encomendas`
--
ALTER TABLE `encomendas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `encomenda_produtos`
--
ALTER TABLE `encomenda_produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de tabela `gorros`
--
ALTER TABLE `gorros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `pants`
--
ALTER TABLE `pants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT de tabela `rodas`
--
ALTER TABLE `rodas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `rolamentos`
--
ALTER TABLE `rolamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `sapatos`
--
ALTER TABLE `sapatos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `shorts`
--
ALTER TABLE `shorts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `sweats`
--
ALTER TABLE `sweats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tamanhos_produto`
--
ALTER TABLE `tamanhos_produto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

--
-- AUTO_INCREMENT de tabela `trucks`
--
ALTER TABLE `trucks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `tshirts`
--
ALTER TABLE `tshirts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
-- Limitadores para a tabela `cintos`
--
ALTER TABLE `cintos`
  ADD CONSTRAINT `cintos_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

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
-- Limitadores para a tabela `gorros`
--
ALTER TABLE `gorros`
  ADD CONSTRAINT `gorros_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

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
-- Limitadores para a tabela `tamanhos_produto`
--
ALTER TABLE `tamanhos_produto`
  ADD CONSTRAINT `tamanhos_produto_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);

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
