
CREATE TABLE `rolamentos` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `tipo` varchar(50) NOT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `estoque` int(11) DEFAULT 0,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `rolamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`);


ALTER TABLE `rolamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `rolamentos`
  ADD CONSTRAINT `rolamentos_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;
COMMIT;

