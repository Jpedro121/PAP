

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
(28, 'Beanie Vans Core Basics', 'Gorro básico da Vans com logo bordado.', 19.99, 'beaniesvans.webp', 10, '', 'gorros', 'Vans', 'vans.png'),
(29, 'Cinto Independent Cross Belt', 'Cinto em lona da Independent com fivela metálica.', 24.99, 'cintoidenpendet12.jpg', 10, '', 'Cintos', 'Independent', 'independent.png'),
(49, 'Cinto Independent Span', 'Cinto forte e resistente com logo.', 19.99, 'cintoindependent.jpeg', 10, '', 'Cintos', 'Independent', 'independent.png'),
(50, 'Calção Dickies Work Short', '<p>Loose fit</p>\r\n<p>65% recycled polyester, 35% cotton</p>\r\n<p>High-waist</p>\r\n<p>Five-pocket design</p>\r\n<p>Woven logo</p>', 54.90, '6826f3927aa40_6826efe56d963_dk0a4xozdnx1321-removebg-preview.png', 8, '', 'Shorts', 'Dickies', 'dickies.png'),
(53, 'Sk8Nation T-shirts', '', 85.00, 'img_68273d0e1a981.png', 5, '0', 'T-shirts', 'Sk8Nation', 'Sk8Nationlogo.png');
