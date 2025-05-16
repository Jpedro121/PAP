-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16-Maio-2025 às 09:40
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
(20, 'T-shirt Thrasher Flame Logo', '<p>T-shirt 100% algod&atilde;o com log&oacute;tipo Flame da Thrasher.</p>', 29.99, '68262c3060967_FLAME-BLACK-SHIRT-1.png', 5, '', 'T-shirts', 'Trasher', NULL),
(21, 'Sweat Nike SB Icon Hoodie', '<p>Sweat com capuz Nike SB em algod&atilde;o e poli&eacute;ster.</p>', 64.90, '68262d25548a8_4d7cd0fb9dca4a10bb7d10df24c75490.png', 6, '', 'Sweats', 'NikeSB', NULL),
(22, 'Pants Dickies 874 Work Pant', 'Calças clássicas de trabalho Dickies, resistentes e confortáveis.', 54.99, NULL, 7, '', 'Pants', 'Dickies', NULL),
(23, 'Shorts Polar Surf Shorts', 'Shorts leves da Polar Skate Co. para dias quentes.', 49.99, '6826eb4877c53_Polar-Skate-Co-surf-shorts-black-collective-500x500.jpg', 8, '', 'Shorts', 'Polar Skate Co.', 'polar.png'),
(24, 'Nike SB Zoom Blazer Mid', '<p>Sapatos de skate Nike SB com sola Zoom Air.</p>', 89.90, '68262d6cad57a_864349-007-PHSRH000-2000_2000x_03a472f5-8ccd-4970-bd14-d857508e0b43.png', 9, '', 'Sapato', 'NikeSB', NULL),
(25, 'Trucks Independent 139', '<p>Par de trucks Independent Stage 11 para decks 7.75\"-8.25\".</p>', 67.50, '682659156682d_id0tr0033_0_1.png', 2, '', 'Trucks', 'Independent', NULL),
(26, 'Rodas Spitfire Formula Four 52mm', 'Rodas de skate Spitfire para street e park.', 44.99, NULL, 3, '', 'Rodas', 'Spitfire', NULL),
(27, 'Rolamentos Bones Reds', 'Rolamentos Bones Reds, os mais populares do mercado.', 22.00, NULL, 4, '', 'Rolamentos', 'Bones Reds', NULL),
(28, 'Beanie Vans Core Basics', 'Gorro básico da Vans com logo bordado.', 19.99, NULL, 10, '', 'Acessórios', 'Vans', 'vans.png'),
(29, 'Cinto Independent Cross Belt', 'Cinto em lona da Independent com fivela metálica.', 24.99, NULL, 10, '', 'Acessórios', 'Independent', NULL),
(42, 'Calção Preto', 'Calção confortável para skate.', 29.99, 'calcao_preto.jpg', 8, '', '', 'Thrasher', NULL),
(43, 'Calção Bege', 'Ideal para o verão.', 27.99, 'calcao_bege.jpg', 8, '', '', 'NikeSB', NULL),
(44, 'Gorro Vermelho', 'Gorro quente e estiloso.', 14.99, 'gorro_vermelho.jpg', 10, '', '', 'Independent', NULL),
(45, 'Gorro Preto', 'Combina com tudo.', 14.99, 'gorro_preto.jpg', 10, '', '', 'Vans', NULL),
(46, 'Cinto Preto', 'Cinto resistente para o dia a dia.', 12.99, 'cinto_preto.jpg', 10, '', '', 'Carhartt', NULL),
(47, 'Cinto Castanho', 'Clássico e versátil.', 13.99, 'cinto_castanho.jpg', 10, '', '', 'Converse', NULL),
(48, 'Gorro Thrasher Flame Logo', 'Gorro com o logo icónico da Thrasher.', 24.99, 'gorro_thrasher.jpg', 10, '', '', 'Thrasher', NULL),
(49, 'Cinto Independent Span', 'Cinto forte e resistente com logo.', 19.99, 'cinto_independent.jpg', 10, '', '', 'Independent', NULL),
(50, 'Calção Dickies Work Short', 'Calção resistente para skate.', 54.90, 'calcao_dickies.jpg', 8, '', '', 'Dickies', NULL),
(51, 'Calção Nike SB Flex', 'Calção flexível e confortável para skate.', 44.90, 'calcao_nikesb.jpg', 7, '', '', 'NikeSB', NULL),
(52, 'Calção Vans Range Relaxed', 'Estilo relaxado, ideal para verão.', 39.90, 'calcao_vans.jpg', 7, '', '', 'Vans', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
