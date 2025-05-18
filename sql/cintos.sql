
CREATE TABLE `cintos` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `comprimento` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `cintos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`);

ALTER TABLE `cintos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

ALTER TABLE `cintos`
  ADD CONSTRAINT `cintos_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;
COMMIT;
