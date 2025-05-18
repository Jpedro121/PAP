

CREATE TABLE `gorros` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `tamanho` varchar(20) DEFAULT NULL,
  `cor` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `gorros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`);


ALTER TABLE `gorros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;


ALTER TABLE `gorros`
  ADD CONSTRAINT `gorros_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;
COMMIT;
