

CREATE TABLE `shorts` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `tamanho` varchar(10) DEFAULT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `estoque` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `shorts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`);


ALTER TABLE `shorts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `shorts`
  ADD CONSTRAINT `shorts_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;
COMMIT;

