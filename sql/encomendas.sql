-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29-Maio-2025 às 19:02
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
(20, 16, 'Retirada na loja', 240.00, 'EN682C6C4A554A3', '2025-05-20 12:49:30', 'Cartão', 'pickup'),
(21, 16, 'Retirada na loja', 60.00, 'EN68302E908AAAF', '2025-05-23 09:15:12', 'Cartão', 'pickup'),
(22, 30, 'rua das figueiras n5, 123-111, MAFRA', 924.00, 'EN6830317B2B90F', '2025-05-23 09:27:39', 'Cartão', 'delivery'),
(23, 36, 'Avenida de Portugal n44,Póvoa da Galega, 123-111, MAFRA', 90.00, 'EN6830FE9F7F80A', '2025-05-24 00:02:55', 'Cartão', 'delivery'),
(24, 38, 'Avenida da Liberdade n144 2eq, 2665-123, Lisboa', 400.00, 'EN6833767EBAD96', '2025-05-25 20:58:54', 'Cartão', 'delivery'),
(26, 38, 'Retirada na loja', 320.00, 'EN683382230F2EB', '2025-05-25 21:48:35', 'Cartão', 'pickup'),
(27, 44, 'Avenida da Liberdade n144 2eq, 2222-222, lisboa', 84.99, 'EN6836C1176D79D', '2025-05-28 08:53:59', 'Cartão', 'delivery'),
(28, 47, 'Retirada na loja', 159.98, 'EN6836DD3FE50A8', '2025-05-28 10:54:07', 'Cartão', 'pickup'),
(29, 48, 'Retirada na loja', 90.00, 'EN6836FAB5F06FE', '2025-05-28 12:59:49', 'Cartão', 'pickup');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `encomendas`
--
ALTER TABLE `encomendas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_encomenda` (`codigo_encomenda`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `encomendas`
--
ALTER TABLE `encomendas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `encomendas`
--
ALTER TABLE `encomendas`
  ADD CONSTRAINT `encomendas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
