-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29-Maio-2025 às 19:01
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
-- Estrutura da tabela `encomenda_produtos`
--

CREATE TABLE `encomenda_produtos` (
  `id` int(11) NOT NULL,
  `encomenda_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `tamanho` varchar(10) DEFAULT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `encomenda_produtos`
--

INSERT INTO `encomenda_produtos` (`id`, `encomenda_id`, `produto_id`, `tamanho`, `quantidade`, `preco_unitario`) VALUES
(1, 1, 9, 'Único', 1, 85.00),
(2, 2, 5, 'Único', 1, 90.00),
(3, 3, 4, 'Único', 14, 90.00),
(4, 4, 19, '49mm', 14, 75.00),
(5, 5, 12, 'Único', 1, 50.00),
(6, 5, 1, '7.0\"', 1, 85.00),
(7, 6, 50, 'Único', 1, 54.90),
(8, 7, 27, 'Único', 1, 22.00),
(9, 7, 6, '129mm', 1, 75.00),
(10, 7, 49, 'Único', 1, 19.99),
(11, 7, 24, 'Único', 1, 89.90),
(12, 7, 53, 'Único', 1, 85.00),
(13, 8, 5, 'Único', 1, 90.00),
(14, 8, 28, 'Único', 1, 19.99),
(15, 8, 24, 'Único', 1, 89.90),
(16, 8, 15, 'Único', 1, 90.00),
(17, 8, 25, 'Único', 1, 67.50),
(18, 9, 5, 'Único', 110, 90.00),
(19, 10, 10, 'Único', 1123456789, 85.00),
(20, 11, 4, 'Único', 1, 90.00),
(21, 12, 4, 'Único', 1, 90.00),
(22, 13, 28, 'Único', 2147483647, 19.99),
(23, 14, 5, 'Único', 1, 90.00),
(24, 15, 28, 'Único', 2147483647, 19.99),
(25, 16, 4, 'Único', 1, 90.00),
(26, 17, 4, 'Único', 1, 90.00),
(27, 17, 28, 'Único', 1, 19.99),
(28, 17, 24, 'Único', 1, 89.90),
(29, 18, 12, 'Único', 1, 50.00),
(30, 18, 24, 'Único', 1, 89.90),
(31, 19, 28, 'Único', 1, 19.99),
(32, 19, 24, 'Único', 1, 89.90),
(33, 19, 53, 'Único', 1, 85.00),
(34, 20, 103, 'Único', 12, 20.00),
(35, 21, 103, 'Único', 3, 20.00),
(36, 22, 103, 'Único', 1, 20.00),
(37, 22, 24, 'Único', 10, 89.90),
(38, 23, 53, 'Único', 1, 85.00),
(39, 24, 6, 'Único', 1, 75.00),
(40, 24, 103, 'Único', 1, 20.00),
(41, 24, 110, 'Único', 1, 300.00),
(42, 26, 103, 'Único', 1, 20.00),
(43, 26, 110, '43', 1, 300.00),
(44, 27, 113, '40', 1, 79.99),
(45, 28, 113, '42', 2, 79.99),
(46, 29, 4, '8.75\"', 1, 90.00);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `encomenda_produtos`
--
ALTER TABLE `encomenda_produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `encomenda_id` (`encomenda_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `encomenda_produtos`
--
ALTER TABLE `encomenda_produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `encomenda_produtos`
--
ALTER TABLE `encomenda_produtos`
  ADD CONSTRAINT `encomenda_produtos_ibfk_1` FOREIGN KEY (`encomenda_id`) REFERENCES `encomendas` (`id`),
  ADD CONSTRAINT `encomenda_produtos_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
